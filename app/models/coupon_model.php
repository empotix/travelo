<?php

    class coupon_model extends CI_Model
    {

        //Constructor
        function coupon_model()
        {
            parent::__construct();
        }

        function get_coupon($conditions = array(), $like = array(), $like_or = array())
        {
            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            //Check For like statement
            if (is_array($like_or) and count($like_or) > 0)
                $this->db->or_like($like_or);
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('coupon');
            $this->db->select();
            $result = $this->db->get();
            return $result;
        }

        function updatecoupon($updateKey = array(), $updateData = array())
        {
            $this->db->update('coupon', $updateData, $updateKey);
        }

        function deletecoupon($id = 0, $conditions = array())
        {
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);
            else
                $this->db->where('id', $id);
            $this->db->delete('coupon');
        }

        function updateSubscription($updateKey = array(), $updateData = array())
        {
            $this->db->update('subscription', $updateData, $updateKey);
        }

        function getSubscription($conditions = array(), $like = array(), $like_or = array())
        {
            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            //Check For like statement
            if (is_array($like_or) and count($like_or) > 0)
                $this->db->or_like($like_or);
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('subscription');
            $this->db->select();
            $result = $this->db->get();
            return $result;
        }

        function insertSubscription($insertData = array())
        {
            $this->db->insert('subscription', $insertData);
        }

        function getBankTransfer($conditions = array(), $like = array(), $like_or = array())
        {
            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            //Check For like statement
            if (is_array($like_or) and count($like_or) > 0)
                $this->db->or_like($like_or);
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('bank_transfer');
            $this->db->select();
            $result = $this->db->get();
            return $result;
        }

        function insertBankTransfer($insertData = array())
        {
            $this->db->insert('bank_transfer', $insertData);
        }

        function updateBankTransfer($updateData = array())
        {
            $this->db->update('bank_transfer', $updateData);
        }

    }

?>