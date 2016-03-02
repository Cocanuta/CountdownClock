<?php

/**
 * User_model.php
 * Created by Ben Cherrington.
 * Date: 02/03/2016
 * Time: 14:52
 */
class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function create_user($username, $email, $firstname, $lastname, $dob, $country, $password)
    {
        $data = array(
            'Username'      => $username,
            'FirstName'     => $firstname,
            'LastName'      => $lastname,
            'Email'         => $email,
            'DateOfBirth'   => $dob,
            'Country'       => $country,
            'Password'      => $this->hash_password($password),
        );

        return $this->db->insert('users', $data);
    }

    public function resolve_user_login($username, $password)
    {
        $this->db->select('Password');
        $this->db->from('users');
        $this->db->where('Username', $username);
        $hash = $this->db->get()->row('Password');

        return $this->verify_password_hash($password, $hash);
    }

    private function verify_password_hash($password, $hash)
    {
        return password_verify($password, $hash);
    }

    private function hash_password($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function get_all_users()
    {
        return $this->db->get('users');
    }

    public function get_user($user_id)
    {
        $this->db->from('users');
        $this->db->where('ID', $user_id);
        return $this->db->get()->row();
    }

    public function get_user_id_from_username($username)
    {
        $this->db->select('ID');
        $this->db->from('users');
        $this->db->where('Username', $username);

        return $this->db->get()->row('ID');
    }
}