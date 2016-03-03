<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clock.php
 * Created by Ben Cherrington.
 * Date: 02/03/2016
 * Time: 14:52
 */

class Clock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('user_model', 'list_model', 'clock_model'));
    }

    /*
     * The add clock page.
     */
    public function add_clock()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('date', 'Date', 'trim|required');
        $this->form_validation->set_rules('list', 'List', 'required');

        if($this->form_validation->run() === false)
        {
            $content['lists'] = $this->list_model->get_all_users_lists($_SESSION['user_id']);
            $header['pageTitle'] = " - Add Clock";

            $this->load->view('header', $header);
            $this->load->view('clocks/add_new', $content);
            $this->load->view('footer');
        }
        else
        {
            $title = $this->input->post('title');
            $date = $this->input->post('date');
            $userID = $_SESSION['user_id'];
            $listID = $this->input->post('list');

            if($this->clock_model->add_clock($title, $date, $userID, $listID))
            {
                $header['pageTitle'] = " - Clock Added";

                $this->load->view('header', $header);
                $this->load->view('clocks/add_success');
                $this->load->view('footer');
            }
            else
            {
                $content['error'] = "There was a problem adding this clock. Please try again.";
                $content['lists'] = $this->list_model->get_all_users_lists($_SESSION['user_id']);
                $header['pageTitle'] = " - Add Clock";

                $this->load->view('header', $header);
                $this->load->view('clocks/add_new', $content);
                $this->load->view('footer');
            }
        }
    }

    public function add_list()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'Title', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');

        if($this->form_validation->run() === false)
        {
            $header['pageTitle'] = " - Add List";

            $this->load->view('header', $header);
            $this->load->view('lists/add_new');
            $this->load->view('footer');
        }
        else
        {
            $title = $this->input->post('title');
            $description = $this->input->post('description');
            $userID = $_SESSION['user_id'];

            if($this->list_model->add_list($title, $description, $userID))
            {
                $header['pageTitle'] = " - List Added";

                $this->load->view('header', $header);
                $this->load->view('lists/add_success');
                $this->load->view('footer');
            }
            else
            {
                $content['error'] = "There was a problem adding this list. Please try again.";
                $header['pageTitle'] = " - Add List";

                $this->load->view('header', $header);
                $this->load->view('lists/add_new', $content);
                $this->load->view('footer');
            }
        }
    }

    public function clocks($clockID)
    {
        if(!isset($clockID))
        {
            redirect(base_url(''));
        }
        else
        {
            $clock = $this->clock_model->get_clock($clockID);
            if($clock !== null)
            {
                $content['clock'] = $clock;
                $user = $this->user_model->get_user($clock->UserID);
                $content['user'] = array(
                    'Username' => $user->Username,
                );
                $header['pageTitle'] = " - ".$clock->Title." Countdown Clock";

                $this->load->view('header', $header);
                $this->load->view('clocks/single', $content);
                $this->load->view('footer');

            }
            else
            {
                redirect(base_url(''));
            }

        }
    }

    public function lists($listID)
    {
        if(!isset($listID))
        {
            redirect(base_url(''));
        }
        else
        {
            $list = $this->list_model->get_list($listID);
            if($list !== null)
            {
                $content['list'] = $list;
                $user = $this->user_model->get_user($list->UserID);
                $content['user'] = array(
                    'Username' => $user->Username,
                );
                $header['pageTitle'] = " - ".$list->Title." List";

                $this->load->view('header', $header);
                $this->load->view('lists/single', $content);
                $this->load->view('footer');

            }

        }
    }

    public function delete_list()
    {
        if(isset($_POST['listID']))
        {
            $list_to_delete = $this->list_model->get_list($_POST['listID']);
            if($list_to_delete !== null)
            {
                if((int)$_SESSION['user_id'] === (int)$list_to_delete->UserID)
                {
                    if(isset($_POST['confirm_delete']) && (bool)$_POST['confirm_delete'] === true)
                    {
                        $header['pageTitle'] = " - List Deleted";
                        $this->list_model->delete_list($_POST['listID']);

                        $this->load->view('header', $header);
                        $this->load->view('lists/delete_success');
                        $this->load->view('footer');
                    }
                    else
                    {
                        $header['pageTitle'] = " - Delete List";
                        $content['list'] = $list_to_delete;

                        $this->load->view('header', $header);
                        $this->load->view('lists/delete', $content);
                        $this->load->view('footer');
                    }
                }
                else
                {
                    redirect(base_url(''));
                }
            }
            else
            {
                redirect(base_url(''));
            }
        }
        else
        {
            redirect(base_url(''));
        }
    }

    public function delete_clock()
    {
        if(isset($_POST['clockID']))
        {
            $clock_to_delete = $this->clock_model->get_clock($_POST['clockID']);
            if($clock_to_delete !== null)
            {
                if((int)$_SESSION['user_id'] === (int)$clock_to_delete->UserID)
                {
                    if(isset($_POST['confirm_delete']) && (bool)$_POST['confirm_delete'] === true)
                    {
                        $header['pageTitle'] = " - Clock Deleted";
                        $this->list_model->delete_clock_from_list($_POST['listID'], $_POST['clockID']);

                        $this->load->view('header', $header);
                        $this->load->view('clocks/delete_success');
                        $this->load->view('footer');
                    }
                    else
                    {
                        $header['pageTitle'] = " - Delete Clock";
                        $content['clock'] = $clock_to_delete;
                        $content['listID'] = $_POST['listID'];

                        $this->load->view('header', $header);
                        $this->load->view('clocks/delete', $content);
                        $this->load->view('footer');
                    }
                }
                else
                {
                    redirect(base_url(''));
                }
            }
            else
            {
                redirect(base_url(''));
            }
        }
        else
        {
            redirect(base_url(''));
        }
    }
}