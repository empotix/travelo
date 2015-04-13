<?php

    class aminity_model extends CI_Model
    {

        //Constructor
        function aminity_model()
        {
            parent::__construct();
        }

        function addplace($country, $state, $city)
        {
            $data = array(
                'country' => $country,
                'state' => $state,
                'city' => $city
            );
            $this->db->insert('neighbor_city', $data);
        }

        function addplace1($city_id, $area)
        {
            $data = array(
                'city_id' => $city_id,
                'area' => $area
            );
            $this->db->insert('neighbor_area', $data);
        }

        function getaminity($conditions = array(), $like = array(), $like_or = array())
        {
            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            //Check For like statement
            if (is_array($like_or) and count($like_or) > 0)
                $this->db->or_like($like_or);
// Puhal changes End. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)			
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('amnities');
            $this->db->select();
            $result = $this->db->get();
            return $result;
        }

//End of getFaqs Function

        function getplace1($conditions = array(), $like = array(), $like_or = array())
        {
            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            //Check For like statement
            if (is_array($like_or) and count($like_or) > 0)
                $this->db->or_like($like_or);
// Puhal changes End. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)			
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('neighbor_area');
            $this->db->select();
            $result = $this->db->get();
            return $result;
        }

        function updateaminity($updateKey = array(), $updateData = array())
        {
            $this->db->update('amnities', $updateData, $updateKey);
        }

        function deleteaminity($id = 0, $conditions = array())
        {
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);
            else
                $this->db->where('id', $id);
            $this->db->delete('amnities');
        }

    }

?>