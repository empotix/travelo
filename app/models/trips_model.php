<?php

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
    class Trips_model extends CI_Model
    {

        /**
         * Constructor 
         *
         */
        function Trips_model()
        {
            parent::__construct();
        }

        function get_reservation($conditions = array(), $limit = array(), $conditions_or = array())
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

            $this->db->from('reservation');
            $this->db->join('reservation_status', 'reservation.status = reservation_status.id', 'inner');
            $this->db->join('users', 'reservation.userby = users.id');
            $this->db->select('reservation.id,reservation.list_id,reservation.userby,users.username,
					reservation.userto,reservation.checkin,reservation.checkout,reservation.no_quest,
					reservation.currency,reservation.price,reservation.topay ,reservation.admin_commission ,
					reservation.credit_type,reservation.ref_amount,reservation.status,reservation.book_date,
					reservation.is_payed,reservation_status.name,reservation.cleaning,reservation.security,reservation.extra_guest_price,
					reservation.guest_count');

            $result = $this->db->get();
            return $result;
        }

        function update_reservation($updateKey = array(), $updateData = array())
        {
            $this->db->update('reservation', $updateData, $updateKey);
        }

        function get_reservation_trips($conditions = array(), $conditions_in = array(), $limit = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            if (count($conditions_in) > 0)
                $this->db->where_in("reservation.status", $conditions_in);

            //Check For Limit	
            if (is_array($limit))
            {
                if (count($limit) == 1)
                    $this->db->limit($limit[0]);
                else if (count($limit) == 2)
                    $this->db->limit($limit[0], $limit[1]);
            }

            $this->db->from('reservation');
            $this->db->join('reservation_status', 'reservation.status = reservation_status.id', 'inner');
            $this->db->join('list', 'reservation.list_id = list.id', 'left');

            $this->db->select('reservation.id,reservation.list_id,reservation.userby,reservation.userto,reservation.checkin,reservation.checkout,reservation.no_quest,reservation.price,reservation.credit_type,reservation.ref_amount,reservation.status,reservation.book_date,reservation_status.name');

            $result = $this->db->get();
            return $result;
        }

        function get_reservation_most($conditions = array(), $conditions_or = array(), $limit = array())
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

            $this->db->from('reservation');
            $this->db->join('reservation_status', 'reservation.status = reservation_status.id', 'inner');
            $this->db->join('list', 'reservation.list_id = list.id', 'left');

            $this->db->select('list.id,reservation.list_id,reservation.userby,reservation.userto,reservation.checkin,reservation.checkout,reservation.no_quest,reservation.price,reservation.credit_type,reservation.ref_amount,reservation.status,reservation.book_date,reservation_status.name,list.user_id,list.title,list.price,list.review');

            $result = $this->db->get();
            return $result;
        }

        function insertReview($insertData = array())
        {
            $this->db->insert('reviews', $insertData);
        }

        function get_review($conditions = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('reviews');

            $result = $this->db->get();
            return $result;
        }

        function get_review_sum($conditions = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->select_sum('cleanliness');
            $this->db->select_sum('communication');
            $this->db->select_sum('accuracy');
            $this->db->select_sum('checkin');
            $this->db->select_sum('location');
            $this->db->select_sum('value');

            $result = $this->db->get('reviews');
            return $result;
        }

        function get_calendar($conditions = array(), $group_by = '')
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            if ($group_by == 'on')
                $this->db->group_by('list_id');

            $this->db->from('calendar');

            $result = $this->db->get();
            return $result;
        }

        function insert_calendar($insertData = array())
        {
            $this->db->insert('calendar', $insertData);
        }

        function update_calendar($updateKey = array(), $updateData = array())
        {
            $this->db->update('calendar', $updateData, $updateKey);
        }

        function delete_calendar($id = 0, $conditions = array())
        {
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);
            else
                $this->db->where('id', $id);
            $this->db->delete('calendar');
        }

        function update_pageViewed($list_id, $page_viewed)
        {
            $this->db->where('id', $list_id);
            $updateData = array("page_viewed" => $page_viewed + 1);
            $this->db->update('list', $updateData);

            return $this->db->get_where('list', array('id' => $list_id))->row()->page_viewed;
        }

    }

?>
