<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Auth extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->library('session');
    }
    
    public function login() {
        $this->call->view('auth/login');
    }

    public function authenticate() {
        $username = $this->io->post('username');
        $password = $this->io->post('password');

        if ($username === 'admin' && $password === 'admin123') {
            $this->session->set_userdata('logged_in', true);
            $this->session->set_userdata('username', $username);
            redirect('products');
        } else {
            $this->session->set_flashdata('error', 'Invalid username or password');
            redirect('login');
        }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}