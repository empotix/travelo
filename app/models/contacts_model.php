<?php

    error_reporting(0);

    /**
     * Dropinn Trips_model Class
     *
     * Help to handle tables related to static Faqs of the system.
     *
     * @package		Trips
     * @subpackage	Models
     * @category	Trips_model 
     * @author		Cogzidel Product Team
     * @version		Version 1.5.1
     * @link		http://www.cogzidel.com

     */
    class Contacts_model extends CI_Model
    {

        function Contacts_model()
        {
            parent::__construct();
        }

        function get_contacts($conditions = array(), $limit = array(), $conditions_or = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            if (count($conditions_or) > 0)
                $this->db->or_where($conditions_or);

            //Check For Limit	
            if (is_array($limit))
            {
                if (count($limit) == 1)
                    $this->db->limit($limit[0]);
                else if (count($limit) == 2)
                    $this->db->limit($limit[0], $limit[1]);
            }

            $this->db->from('contacts');
            $this->db->join('reservation_status', 'contacts.status = reservation_status.id', 'inner');

            $this->db->select('contacts.id,contacts.list_id,contacts.userby,contacts.userto,contacts.checkin,contacts.checkout,contacts.no_quest,contacts.currency,contacts.price,contacts.admin_commission ,contacts.status,contacts.send_date,reservation_status.name,contacts.contact_key');
            //echo 	$this->db->last_query(); exit;
            $result = $this->db->get();
            return $result;
        }

        function update_contact($updateKey = array(), $updateData = array())
        {
            $this->db->update('contacts', $updateData, $updateKey);
        }

    }

?>