<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * ApiAuthController  (Lab 6)
 *
 * Token-based (JWT) authentication for the React frontend.
 *
 *   POST /api/auth/register   create an account
 *   POST /api/auth/login      returns access_token + refresh_token
 *   POST /api/auth/refresh    exchange a refresh_token for new tokens
 *   POST /api/auth/logout     revoke the refresh_token
 *   GET  /api/auth/me         current user (needs Bearer token)
 *
 * Loading the LavaLust "api" library handles CORS headers and the
 * OPTIONS pre-flight request automatically.
 */
class ApiAuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('api');
        $this->call->model('UsersModel');
    }

    /** Read the JSON request body without HTML-escaping it. */
    private function input(): array
    {
        $data = json_decode(file_get_contents('php://input') ?: '', true);
        if (is_array($data)) {
            return $data;
        }
        return $_POST ?: [];
    }

    private function public_user(array $user): array
    {
        return [
            'id'         => (int) $user['id'],
            'username'   => $user['username'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'created_at' => $user['created_at'] ?? null,
        ];
    }

    private function fail_validation(array $errors)
    {
        $this->api->respond([
            'error'  => 'Validation failed',
            'errors' => $errors,
            'status' => 422,
        ], 422);
    }

    public function register()
    {
        $in       = $this->input();
        $username = trim((string) ($in['username'] ?? ''));
        $email    = strtolower(trim((string) ($in['email'] ?? '')));
        $password = (string) ($in['password'] ?? '');

        $errors = [];
        if (!preg_match('/^[A-Za-z0-9_.-]{3,50}$/', $username)) {
            $errors['username'] = 'Username must be 3-50 characters (letters, numbers, . _ -).';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 255) {
            $errors['email'] = 'A valid email address is required.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }
        if ($errors) {
            $this->fail_validation($errors);
        }

        if ($this->UsersModel->username_exists($username)) {
            $this->api->respond_error('Username is already taken.', 409);
        }
        if ($this->UsersModel->email_exists($email)) {
            $this->api->respond_error('Email is already registered.', 409);
        }

        $id = $this->UsersModel->create_user([
            'username'   => $username,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'role'       => 'user',
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $user = $this->UsersModel->find_by_id($id);
        $this->api->respond([
            'message' => 'Account created. You can now log in.',
            'user'    => $this->public_user($user),
        ], 201);
    }

    public function login()
    {
        // Slow down brute-force attempts: 10 tries / minute per IP
        $this->api->rate_limit('login_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown'), 10, 60);

        $in       = $this->input();
        $login    = trim((string) ($in['username'] ?? ($in['email'] ?? '')));
        $password = (string) ($in['password'] ?? '');

        if ($login === '' || $password === '') {
            $this->fail_validation(['credentials' => 'Username and password are required.']);
        }

        $user = $this->UsersModel->find_by_login($login);

        if (!$user || !password_verify($password, $user['password']) || (int) $user['is_active'] !== 1) {
            $this->api->respond_error('Invalid username or password.', 401);
        }

        $tokens = $this->api->issue_tokens([
            'id'     => (int) $user['id'],
            'role'   => $user['role'],
            'scopes' => ['read', 'write'],
        ]);

        $this->api->respond([
            'message' => 'Login successful.',
            'user'    => $this->public_user($user),
            'tokens'  => $tokens,
        ]);
    }

    public function refresh()
    {
        $in    = $this->input();
        $token = (string) ($in['refresh_token'] ?? '');

        if ($token === '') {
            $this->api->respond_error('refresh_token is required.', 400);
        }

        // Validates, rotates the token and sends the JSON response itself.
        $this->api->refresh_access_token($token);
    }

    public function logout()
    {
        $in    = $this->input();
        $token = (string) ($in['refresh_token'] ?? '');

        if ($token !== '') {
            $this->api->revoke_refresh_token($token);
        }

        $this->api->respond(['message' => 'Logged out.']);
    }

    public function me()
    {
        $payload = $this->api->require_jwt();
        $user    = $this->UsersModel->find_by_id($payload['sub']);

        if (!$user) {
            $this->api->respond_error('User not found.', 404);
        }

        $this->api->respond(['user' => $this->public_user($user)]);
    }
}
