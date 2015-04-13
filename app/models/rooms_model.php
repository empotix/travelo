<?php

    /**
     * DROPinn Rooms Model Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Models
     * @category	Rooms_model 
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com
     */
    class Rooms_model extends CI_Model
    {

        //Constructor
        function Rooms_model()
        {
            parent::__construct();
        }

        function get_room($conditions = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('list');

            $result = $this->db->get();
            return $result;
        }

        function get_rooms($conditions = array(), $conditions1 = array(), $limit = array(), $orderby = array(), $orderbyId = array(), $like = array(), $like1 = array())
        {
            //Check For Conditions
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);

            //Check For Conditions
            if (is_array($conditions1) and count($conditions1) > 0)
                $this->db->or_where($conditions1);

            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            if (is_array($like1) and count($like1) > 0)
                $this->db->or_like($like1);

            //Check For Limit	
            if (is_array($limit))
            {
                if (count($limit) == 1)
                    $this->db->limit($limit[0]);
                else if (count($limit) == 2)
                    $this->db->limit($limit[0], $limit[1]);
            }


            //Check for Order by
            if ($orderbyId == 1)
                $this->db->order_by('id', 'desc');

            //Check for Order by
            if (is_array($orderby) and count($orderby) > 0)
                $this->db->order_by($orderby[0], $orderby[1]);

            $this->db->from('list');

            $result = $this->db->get();
            return $result;
        }

        function get_rooms_byImage($conditions = array(), $conditions1 = array(), $limit = array(), $orderby = array(), $orderbyId = array(), $like = array(), $like1 = array())
        {
            //Check For Conditions

            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);

            //Check For Conditions
            if (is_array($conditions1) and count($conditions1) > 0)
                $this->db->or_where($conditions1);

            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            if (is_array($like1) and count($like1) > 0)
                $this->db->or_like($like1);

            //Check For Limit	
            if (is_array($limit))
            {
                if (count($limit) == 1)
                    $this->db->limit($limit[0]);
                else if (count($limit) == 2)
                    $this->db->limit($limit[0], $limit[1]);
            }


            //Check for Order by
            if ($orderbyId == 1)
                $this->db->order_by('id', 'desc');

            //Check for Order by
            if (is_array($orderby) and count($orderby) > 0)
                $this->db->order_by($orderby[0], $orderby[1]);

            $this->db->from('list');
            $this->db->join('list_photo', 'list.id = list_photo.list_id', 'inner');


            $result = $this->db->get();
            return $result;
        }

        function get_amnities()
        {
            $this->db->from('amnities');

            $result = $this->db->get();
            return $result;
        }

        function update_list($updateKey = array(), $updateData = array())
        {

            $this->db->update('list', $updateData, $updateKey);
        }

        function recent_model($conditions = array(), $conditions1 = array(), $limit = array(), $orderby = array(), $orderbyId = array(), $like = array(), $like1 = array())
        {
            //Check For Conditions
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);

            //Check For Conditions
            if (is_array($conditions1) and count($conditions1) > 0)
                $this->db->or_where($conditions1);

            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            if (is_array($like1) and count($like1) > 0)
                $this->db->or_like($like1);

            //Check For Limit	
            if (is_array($limit))
            {
                if (count($limit) == 1)
                    $this->db->limit($limit[0]);
                else if (count($limit) == 2)
                    $this->db->limit($limit[0], $limit[1]);
            }

            //Check for Order by
            if ($orderbyId == 1)
                $this->db->order_by('id', 'desc');

            //Check for Order by
            if (is_array($orderby) and count($orderby) > 0)
                $this->db->order_by($orderby[0], $orderby[1]);

            //$this->db->select('list.id,list.title,list_photo.name,list.address');
            $this->db->select('list.id,list.title');
            //$this->db->select('list_photo.name,list.address')->where('list_photo.is_featured',1);
            $this->db->select('list_photo.name,list.address,list.price')->where('list_photo.is_featured', 1);
            $this->db->from('list');
            $this->db->join('list_photo', 'list_photo.list_id = list.id', 'inner');
            $result = $this->db->get();
            return $result;
        }

    }

?>