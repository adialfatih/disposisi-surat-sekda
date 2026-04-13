<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    protected $table = 'users';

    public function get_user_by_username($username)
    {
        return $this->db
            ->where('username', $username)
            ->where('is_active', 1)
            ->limit(1)
            ->get($this->table)
            ->row();
    }
}