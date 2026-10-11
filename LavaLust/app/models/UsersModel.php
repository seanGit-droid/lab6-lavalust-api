<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

class UsersModel extends Model {

    public function all() {
        return $this->db->table('users')->get_all();
    }

    public function find_by_id($id) {
        return $this->db->table('users')->where('id', $id)->get();
    }

    /** Find a user by username OR email. */
    public function find_by_login($login) {
        return $this->db->table('users')
            ->where(['username' => $login])
            ->or_where(['email' => $login])
            ->get();
    }

    public function username_exists($username) {
        return (bool) $this->db->table('users')->where(['username' => $username])->get();
    }

    public function email_exists($email) {
        return (bool) $this->db->table('users')->where(['email' => $email])->get();
    }

    public function create_user($data) {
        return $this->db->table('users')->insert($data);
    }
}
