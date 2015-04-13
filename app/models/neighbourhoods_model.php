<?php

    error_reporting(0);

    class Neighbourhoods_model extends CI_Model
    {

        function Neighbourhoods_model()
        {
            parent::__construct();
        }

        function city_id($city)
        {
            return $this->db->where('city_name', $city)->get('neigh_city')->row()->id;
        }

        function place_id($place)
        {
            return $this->db->where('place_name', $place)->get('neigh_city_place')->row()->id;
        }

        function addtag($data)
        {
            return $this->db->insert('neigh_tag', $data);
        }

        function username($id)
        {
            return $this->db->where('id', $id)->get('users')->row()->username;
        }

        function updatetag($data, $id)
        {
            return $this->db->where('id', $id)->update('neigh_tag', $data);
        }

        function updateknowledge($data, $id)
        {
            return $this->db->where('id', $id)->update('neigh_knowledge', $data);
        }

    }

?>