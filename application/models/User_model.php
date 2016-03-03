<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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

    public function create_user($username, $email, $firstname, $lastname, $dob, $country, $timezone, $password)
    {
        $data = array(
            'Username'      => $username,
            'FirstName'     => $firstname,
            'LastName'      => $lastname,
            'Email'         => $email,
            'DateOfBirth'   => $dob,
            'Country'       => $country,
            'Timezone'      => $timezone,
            'Password'      => $this->hash_password($password),
        );

        return $this->db->insert('users', $data);
    }

    public function update_user_password($username, $oldPassword, $newPassword)
    {
        if($this->resolve_user_login($username, $oldPassword))
        {
            $data = array(
                'Password' => $this->hash_password($newPassword),
            );

            $this->db->where('ID', $this->get_user_id_from_username($username));
            return $this->db->update('users', $data);
        }
        else
        {
            return false;
        }
    }

    public function update_user($userid, $firstname, $lastname, $dob, $country, $timezone)
    {
        $data = array();

        if($firstname !== null)
        {
            $data['FirstName'] = $firstname;
        }
        if($lastname !== null)
        {
            $data['LastName'] = $lastname;
        }
        if($dob !== null)
        {
            $data['DateOfBirth'] = $dob;
        }
        if($country !== null)
        {
            $data['Country'] = $country;
        }
        if($timezone !== null)
        {
            $data['Timezone'] = $timezone;
        }

        $this->db->where('ID', $userid);
        return $this->db->update('users', $data);
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