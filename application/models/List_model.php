<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * List_model.php
 * Created by Ben Cherrington.
 * Date: 02/03/2016
 * Time: 14:52
 */
class List_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('clock_model');
    }

    /*
     * Add new clock to the database.
     */
    public function add_list($title, $description, $userid)
    {
        $data = array(
            'Title' => $title,
            'Description' => $description,
            'UserID' => $userid,
            'Clocks' => "",
        );
        return($this->db->insert('lists', $data));
    }

    /*
     * Delete list from database.
     */
    public function delete_list($listID)
    {
        $list = $this->get_list($listID);
        //Delete all clocks in this list.
        foreach($list->Clocks as $clock)
        {
            $this->clock_model->delete_clock($clock);
        }
        return $this->db->delete('lists', array('ID' => $listID));
    }

    /*
     * Update list data in database.
     */
    public function update_list($listID, $title, $description)
    {
        $data = array();

        if($title !== null)
        {
            $data['Title'] = $title;
        }
        if($description !== null)
        {
            $data['Description'] = $description;
        }
        $this->db->where('ID', $listID);
        return $this->db->update('lists', $data);
    }

    /*
     * Add new clock to list.
     */
    public function add_clock_to_list($listID, $clockID)
    {
        $this->db->from('lists');
        $this->db->where('ID', $listID);
        $data = $this->db->get()->row();
        $clocks = $this->decode_list($data->Clocks);
        array_push($clocks, $clockID);
        $data = array(
            'Clocks' => $this->encode_list($clocks),
        );
        $this->db->where('ID', $listID);
        return $this->db->update('lists', $data);
    }

    /*
     * Delete clock from list.
     */
    public function delete_clock_from_list($listID, $clockID)
    {
        $this->db->from('lists');
        $this->db->where('ID', $listID);
        $data = $this->db->get()->row();
        $clocks = array_diff($this->decode_list($data->Clocks), array($clockID));
        $data = array(
            'Clocks' => $this->encode_list($clocks),
        );
        $this->clock_model->delete_clock($clockID);
        $this->db->where('ID', $listID);
        return $this->db->update('lists', $data);
    }

    /*
     * Grab list from database with $listID and create ClockList Object.
     */
    public function get_list($listID)
    {
        $this->db->from('lists');
        $this->db->where('ID', $listID);
        $data = $this->db->get()->row();
        $clocks = array();
        foreach($this->decode_list($data->Clocks) as $clock)
        {
            $clocks[] = $this->clock_model->get_clock($clock);
        }
        return new ClockList($data->ID, $data->Title, $data->Description, $data->UserID, $clocks);
    }

    /*
     * Grab all lists based on $userID and create array of ClockList Objects.
     */
    public function get_all_users_lists($userID)
    {
        $this->db->from('lists');
        $this->db->where('UserID', $userID);
        $data = $this->db->get();
        $lists = array();
        foreach($data->result() as $list)
        {
            $clocks = array();

            foreach($this->decode_list($list->Clocks) as $clock)
            {
                $clocks[] = $this->clock_model->get_clock($clock);
            }
            $lists[] = new ClockList($list->ID, $list->Title, $list->Description, $list->UserID, $clocks);
        }
        return $lists;
    }

    /*
     * Convert the string into an array.
     */
    public function decode_list($listString)
    {
        return(array_filter(explode(",", $listString)));
    }

    /*
     * Convert the array into a string.
     */
    public function encode_list($listArray)
    {
        return(implode(",", $listArray));
    }
}

/*
 * ClockList class.
 * Holds all the data on a Clock List Object.
 */
class ClockList
{
    public $ID;
    public $Title;
    public $Description;
    public $UserID;
    public $Clocks;

    public function __construct($id, $title, $description, $userid, $clocks)
    {
        $this->ID = $id;
        $this->Title = $title;
        $this->Description = $description;
        $this->UserID = $userid;
        $this->Clocks = $clocks;
    }
}