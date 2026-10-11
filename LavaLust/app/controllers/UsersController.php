<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersController extends Controller {

    public function index() {
        // I-load ang UsersModel
        $this->call->model('UsersModel');

        // Tawagin ang get_users() method mula sa UsersModel
        $data['users'] = $this->UsersModel->all();

        // Ipapasa ang data sa 'users/index' view
        $this->call->view('users/index', $data);
    }
}