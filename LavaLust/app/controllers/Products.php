<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class Products extends Controller {

    public function __construct() {
        parent::__construct();
        $this->call->model('Product_model');
    }

    public function index() {
        $data['products'] = $this->Product_model->get_all_products();
        $this->call->view('products/index', $data);
    }

    public function create() {
        $this->call->view('products/create');
    }

    public function store() {
        $data = array(
            'product_name' => $_POST['name'] ?? $_POST['product_name'] ?? '',
            'description'  => $_POST['description'] ?? '',
            'price'        => $_POST['price'] ?? 0,
            'quantity'     => $_POST['quantity'] ?? 0
        );

        $this->Product_model->insert_product($data);
        redirect('products');
    }

    public function edit($id) {
    $product = $this->Product_model->get_product_by_id($id);

    // Kung sakaling nakabalot sa indexed array ang result ng LavaLust query
    if (is_array($product) && isset($product[0])) {
        $data['product'] = $product[0];
    } else {
        $data['product'] = $product;
    }

    $this->call->view('products/edit', $data);
}

public function update($id) {
    $data = array(
        'product_name' => $_POST['product_name'] ?? $_POST['name'] ?? '',
        'description'  => $_POST['description'] ?? '',
        'price'        => $_POST['price'] ?? 0,
        'quantity'     => $_POST['quantity'] ?? 0
    );

    $this->Product_model->update_product($id, $data);
    redirect('products');
}

    public function delete($id) {
        $this->Product_model->delete_product($id);
        redirect('products');
    }
}