<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clock_model.php
 * Created by Ben Cherrington.
 * Date: 02/03/2016
 * Time: 14:52
 */
class Clock_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('list_model');
    }

    /*
     * Add a clock to the database.
     * Using the $listID argument, call the add_clock_to_list function from list_model.
     */
    public function add_clock($title, $date, $userid, $listID)
    {
        //Create an array to hold the MySQL query.
        $data = array(
            'Title' => $title,
            'Date' => $date,
            'UserID' => $userid,
        );
        //Check if the data was successfully added to the MySQL db.
        if($this->db->insert('clocks', $data))
        {
            //Add the clock to the desired list.
            return $this->list_model->add_clock_to_list($listID, $this->db->insert_id());
        }
    }

    /*
     * Delete a clock from the database.
     * This function is only called from the list_model as the clock must first be removed from a list.
     */
    public function delete_clock($clock)
    {
        return $this->db->delete('clocks', array('ID' => $clock));
    }

    /*
     * Get clock from Database.
     * returns a Clock object.
     */
    public function get_clock($clockID)
    {

        $this->db->from('clocks');
        $this->db->where('ID', $clockID);
        $data = $this->db->get()->row();

        if($data !== null)
        {
            return new ClockObject($data->ID, $data->Title, $data->Date, $data->UserID);
        }
        else
        {
            return null;
        }
    }

    /*
     * Update a clock in the database.
     */
    public function update_clock($clockID, $title, $date)
    {
        $data = array();

        if($title !== null)
        {
            $data['Title'] = $title;
        }
        if($date !== null)
        {
            $data['Date'] = $date;
        }
        $this->db->where('ID', $clockID);
        return $this->db->update('clocks', $data);
    }
}

/*
 * Clock Object class.
 * Holds the clock information.
 */
class ClockObject
{
    public $ID;
    public $Title;
    public $Date;
    public $UserID;

    public function __construct($id, $title, $date, $userid)
    {
        $this->ID = $id;
        $this->Title = $title;
        $this->Date = $date;
        $this->UserID = $userid;
    }
}