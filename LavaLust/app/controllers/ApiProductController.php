<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * ApiProductController  (Lab 6)
 *
 * JSON CRUD for the products table. Every endpoint requires a valid
 * JWT:  Authorization: Bearer <access_token>
 *
 *   GET    /api/products          list products
 *   GET    /api/products/{id}     show one product
 *   POST   /api/products          add a product
 *   PUT    /api/products/{id}     update a product
 *   PATCH  /api/products/{id}     update a product (partial)
 *   DELETE /api/products/{id}     delete a product
 */
class ApiProductController extends Controller
{
    /** @var array JWT payload of the authenticated user */
    private $auth;

    public function __construct()
    {
        parent::__construct();

        // Loading the api library also answers CORS pre-flight (OPTIONS) requests.
        $this->call->library('api');
        $this->call->model('Product_model');

        // Authentication gate: stops with 401 JSON if the token is missing/invalid/expired.
        $this->auth = $this->api->require_jwt();
    }

    private function input(): array
    {
        $data = json_decode(file_get_contents('php://input') ?: '', true);
        if (is_array($data)) {
            return $data;
        }
        return $_POST ?: [];
    }

    private function format(array $row): array
    {
        return [
            'id'           => (int) $row['id'],
            'product_name' => $row['product_name'],
            'description'  => $row['description'],
            'price'        => (float) $row['price'],
            'quantity'     => (int) $row['quantity'],
            'created_at'   => $row['created_at'],
        ];
    }

    /**
     * Validate and normalise product fields.
     * Returns [cleanData, errors].
     */
    private function validate(array $in): array
    {
        $errors = [];
        $name   = trim((string) ($in['product_name'] ?? ''));
        $desc   = isset($in['description']) ? trim((string) $in['description']) : '';
        $price  = $in['price'] ?? null;
        $qty    = $in['quantity'] ?? null;

        if ($name === '' || (function_exists("mb_strlen") ? mb_strlen($name) : strlen($name)) > 100) {
            $errors['product_name'] = 'Product name is required (max 100 characters).';
        }
        if ($price === null || $price === '' || !is_numeric($price) || $price < 0 || $price > 99999999.99) {
            $errors['price'] = 'Price must be a number between 0 and 99,999,999.99.';
        }
        if ($qty === null || $qty === '' || filter_var($qty, FILTER_VALIDATE_INT) === false || (int) $qty < 0) {
            $errors['quantity'] = 'Quantity must be a whole number, 0 or more.';
        }

        $clean = [
            'product_name' => $name,
            'description'  => $desc,
            'price'        => is_numeric($price) ? round((float) $price, 2) : 0,
            'quantity'     => (int) $qty,
        ];

        return [$clean, $errors];
    }

    private function find_or_404($id): array
    {
        if (!ctype_digit((string) $id)) {
            $this->api->respond_error('Invalid product id.', 400);
        }
        $row = $this->Product_model->get_product_by_id((int) $id);
        if (!$row) {
            $this->api->respond_error('Product not found.', 404);
        }
        return $row;
    }

    // GET /api/products
    public function index()
    {
        $rows = $this->Product_model->get_all_products() ?: [];
        $this->api->respond([
            'data'  => array_map([$this, 'format'], $rows),
            'count' => count($rows),
        ]);
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $row = $this->find_or_404($id);
        $this->api->respond(['data' => $this->format($row)]);
    }

    // POST /api/products
    public function store()
    {
        [$clean, $errors] = $this->validate($this->input());

        if ($errors) {
            $this->api->respond(['error' => 'Validation failed', 'errors' => $errors, 'status' => 422], 422);
        }

        $id  = $this->Product_model->insert_product($clean);
        $row = $this->Product_model->get_product_by_id($id);

        $this->api->respond([
            'message' => 'Product created.',
            'data'    => $this->format($row),
        ], 201);
    }

    // PUT|PATCH /api/products/{id}
    public function update($id)
    {
        $existing = $this->find_or_404($id);

        // Fields that were not sent keep their current value (so PATCH works too)
        $merged = array_merge($existing, array_intersect_key(
            $this->input(),
            array_flip(['product_name', 'description', 'price', 'quantity'])
        ));

        [$clean, $errors] = $this->validate($merged);

        if ($errors) {
            $this->api->respond(['error' => 'Validation failed', 'errors' => $errors, 'status' => 422], 422);
        }

        $this->Product_model->update_product((int) $id, $clean);
        $row = $this->Product_model->get_product_by_id((int) $id);

        $this->api->respond([
            'message' => 'Product updated.',
            'data'    => $this->format($row),
        ]);
    }

    // DELETE /api/products/{id}
    public function destroy($id)
    {
        $this->find_or_404($id);
        $this->Product_model->delete_product((int) $id);

        $this->api->respond(['message' => 'Product deleted.']);
    }
}
