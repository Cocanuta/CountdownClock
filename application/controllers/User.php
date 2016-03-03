<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User_model.php
 * Created by Ben Cherrington.
 * Date: 02/03/2016
 * Time: 14:52
 */

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('user_model', 'list_model', 'clock_model'));
    }

    public function register()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required|alpha_numeric|min_length[4]|is_unique[users.Username]', array('is_unique' => 'This username already exists. Please choose another one.'));
        $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|alpha_numeric');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[users.Email]', array('is_unique' => 'This email address is already in use. Please choose another one.'));
        $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required');
        $this->form_validation->set_rules('country', 'Country', 'trim|required');
        $this->form_validation->set_rules('timezone', 'Timezone', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('password_confirm', 'Confirm Password', 'trim|required|min_length[6]|matches[password]');

        if($this->form_validation->run() === false)
        {
            $header['pageTitle'] = " - Register";

            $this->load->view('header', $header);
            $this->load->view('user/register/register');
            $this->load->view('footer');
        }
        else
        {
            $username = $this->input->post('username');
            $firstname = $this->input->post('firstname');
            $lastname = $this->input->post('lastname');
            $email = $this->input->post('email');
            $dob = $this->input->post('dob');
            $country = $this->input->post('country');
            $timezone = $this->input->post('timezone');
            $password = $this->input->post('password');

            if($this->user_model->create_user($username, $email, $firstname, $lastname, $dob, $country, $timezone, $password))
            {
                $this->load->model('list_model');
                $this->list_model->add_list("Default List", $username."'s default list.", $this->user_model->get_user_id_from_username($username));
                $header['pageTitle'] = " - Registration Successful";

                $this->load->view('header', $header);
                $this->load->view('user/register/register_success');
                $this->load->view('footer');
            }
            else
            {
                $content['error'] = "There was a problem creating your new account. Please try again.";
                $header['pageTitle'] = " - Register";

                $this->load->view('header', $header);
                $this->load->view('user/register/register', $content);
                $this->load->view('footer');
            }
        }
    }

    public function login()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if($this->form_validation->run() == false)
        {
            $header['pageTitle'] = " - Login";

            $this->load->view('header', $header);
            $this->load->view('user/login/login');
            $this->load->view('footer');
        }
        else
        {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            if($this->user_model->resolve_user_login($username, $password))
            {
                $user_id = $this->user_model->get_user_id_from_username($username);
                $user = $this->user_model->get_user($user_id);

                $_SESSION['user_id']      = (int)$user->ID;
                $_SESSION['username']     = (string)$user->Username;
                $_SESSION['firstname']    = (string)$user->FirstName;
                $_SESSION['lastname']     = (string)$user->LastName;
                $_SESSION['country']     = (string)$user->Country;
                $_SESSION['timezone']     = (string)$user->Timezone;
                $_SESSION['logged_in']    = (bool)true;

                $header['pageTitle'] = " - Login Successful";

                $this->load->view('header', $header);
                $this->load->view('user/login/login_success');
                $this->load->view('footer');
            }
            else
            {
                $content['error'] = "Wrong Username or Password.";
                $header['pageTitle'] = " - Login";

                $this->load->view('header', $header);
                $this->load->view('user/login/login', $content);
                $this->load->view('footer');
            }
        }
    }

    public function logout()
    {
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
        {
            $this->session->sess_destroy();

            $header['pageTitle'] = " - Logged Out";

            $this->load->view('header', $header);
            $this->load->view('user/logout/logout_success');
            $this->load->view('footer');
        }
        else
        {
            redirect(base_url(''));
        }
    }

    public function manage()
    {
        if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true)
        {

            $header['pageTitle'] = " - Manage Profile";

            $this->load->view('header', $header);
            $this->load->view('user/account/manage');
            $this->load->view('footer');
        }
        else
        {
            redirect(base_url('login'));
        }
    }

    public function update_account()
    {
        if(isset($_SESSION['username']) && $_SESSION['logged_in'] === true)
        {
            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('firstname', 'First Name', 'trim|required|alpha_numeric');
            $this->form_validation->set_rules('lastname', 'Last Name', 'trim|required|alpha_numeric');
            $this->form_validation->set_rules('dob', 'Date of Birth', 'trim|required');
            $this->form_validation->set_rules('country', 'Country', 'trim|required');
            $this->form_validation->set_rules('timezone', 'Timezone', 'trim|required');

            if($this->form_validation->run() === false)
            {
                $user = $this->user_model->get_user($_SESSION['user_id']);

                $content['data'] = array(
                    'FirstName' => $user->FirstName,
                    'LastName' => $user->LastName,
                    'DateOfBirth' => $user->DateOfBirth,
                    'Country' => $user->Country,
                    'Timezone' => $user->Timezone,
                );

                $header['pageTitle'] = " - Update Profile";

                $this->load->view('header', $header);
                $this->load->view('user/account/update_account', $content);
                $this->load->view('footer');
            }
            else
            {
                $userid = $_SESSION['user_id'];
                $firstname = $this->input->post('firstname');
                $lastname = $this->input->post('lastname');
                $dob = $this->input->post('dob');
                $country = $this->input->post('country');
                $timezone = $this->input->post('timezone');

                if($this->user_model->update_user($userid, $firstname, $lastname, $dob, $country, $timezone))
                {
                    $user = $this->user_model->get_user($userid);

                    $_SESSION['firstname']    = (string)$user->FirstName;
                    $_SESSION['lastname']     = (string)$user->LastName;
                    $_SESSION['country']     = (string)$user->Country;
                    $_SESSION['timezone']     = (string)$user->Timezone;

                    $header['pageTitle'] = " - Profile Updated";

                    $this->load->view('header', $header);
                    $this->load->view('user/account/update_success');
                    $this->load->view('footer');
                }
                else
                {

                    $user = $this->user_model->get_user($_SESSION['user_id']);

                    $content['data'] = array(
                        'FirstName' => $user->FirstName,
                        'LastName' => $user->LastName,
                        'DateOfBirth' => $user->DateOfBirth,
                        'Country' => $user->Country,
                        'Timezone' => $user->Timezone,
                    );

                    $content['error'] = "There was a problem updating your profile. Please try again.";
                    $header['pageTitle'] = " - Update Profile";

                    $this->load->view('header', $header);
                    $this->load->view('user/account/update_account', $content);
                    $this->load->view('footer');
                }
            }
        }
        else
        {
            redirect(base_url('login'));
        }
    }

    public function update_password()
    {
        if(isset($_SESSION['username']) && $_SESSION['logged_in'] === true)
        {
            $this->load->helper('form');
            $this->load->library('form_validation');

            $this->form_validation->set_rules('oldPassword', 'Old Password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('newPassword', 'New Password', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('newPassword_confirm', 'Confirm Password', 'trim|required|min_length[6]|matches[newPassword]');

            if($this->form_validation->run() === false)
            {
                $header['pageTitle'] = " - Update Password";

                $this->load->view('header', $header);
                $this->load->view('user/account/update_password');
                $this->load->view('footer');
            }
            else
            {
                $userid = $_SESSION['user_id'];
                $username = $_SESSION['username'];
                $oldPassword = $this->input->post('oldPassword');
                $newPassword = $this->input->post('newPassword');

                if($this->user_model->update_user_password($username, $oldPassword, $newPassword))
                {

                    $header['pageTitle'] = " - Password Updated";

                    $this->load->view('header', $header);
                    $this->load->view('user/account/update_success');
                    $this->load->view('footer');
                }
                else
                {

                    $content['error'] = "There was a problem updating your password. Please try again.";
                    $header['pageTitle'] = " - Update Password";

                    $this->load->view('header', $header);
                    $this->load->view('user/account/update_password', $content);
                    $this->load->view('footer');
                }
            }
        }
        else
        {
            redirect(base_url('login'));
        }
    }

    public function profile($username = null)
    {
        $userID = null;
        if(!isset($username))
        {
            if(isset($_SESSION['username']) && $_SESSION['logged_in'] === true)
            {
                $userID = $_SESSION['user_id'];
            }
            else
            {
                redirect(base_url('users'));
            }
        }
        else
        {
            $userID = $this->user_model->get_user_id_from_username($username);
        }

        if($userID === null)
        {
            redirect(base_url('users'));
        }
        else
        {
            $user = $this->user_model->get_user($userID);
            $content['user'] = array(
                'Username' => $user->Username,
                'FirstName' => $user->FirstName,
                'LastName' => $user->LastName,
                'Country' => $user->Country,
            );
            $content['lists'] = $this->list_model->get_all_users_lists($userID);

            $header['pageTitle'] = " - ".$user->Username."'s Profile'";

            $this->load->view('header', $header);
            $this->load->view('user/profile/profile', $content);
            $this->load->view('footer');

        }
    }
}