<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
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
            $password = $this->input->post('password');

            if($this->user_model->create_user($username, $email, $firstname, $lastname, $dob, $country, $password))
            {
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
}