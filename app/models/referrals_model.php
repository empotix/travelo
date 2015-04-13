<?php

    /**
     * DROPinn Referrals Model Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Models
     * @category	Referrals_model 
     * @author		Cogzidel Product Team
     * @version		Version 1.1
     * @link		http://www.cogzidel.com
     */
    class Referrals_model extends CI_Model
    {

        //Constructor
        function Referrals_model()
        {
            parent::__construct();
        }

        function insertReferrals($insertData = array())
        {
            $this->db->insert('referrals', $insertData);
        }

        function updateReferrals($updateKey = array(), $updateData = array())
        {
            $this->db->update('referrals', $updateData, $updateKey);
        }

        function insertReferralsAmount($insertData = array())
        {
            $this->db->insert('referrals_amount', $insertData);
        }

        function updateReferralsAmount($updateKey = array(), $updateData = array())
        {
            $this->db->update('referrals_amount', $updateData, $updateKey);
        }

        function insertReferralsBooking($insertData = array())
        {
            $this->db->insert('referrals_booking', $insertData);
        }

        function updateReferralsBooking($updateKey = array(), $updateData = array())
        {
            $this->db->update('referrals_booking', $updateData, $updateKey);
        }

        /* function get_details_by_ref($ref)
          {
          $this->db->where('activation_key', $ref);
          return $this->db->get('referrals');
          } */

        function get_details_by_email($ref)
        {
            $this->db->where('mail_to', $ref);
            return $this->db->get('referrals');
        }

        function get_details_by_Iid($id)
        {
            $this->db->where('invite_to', $id);
            return $this->db->get('referrals');
        }

        function get_details_refamount($user_id)
        {
            $this->db->where('user_id', $user_id);
            return $this->db->get('referrals_amount');
        }

        function get_user_by_refId($ref_id)
        {
            $this->db->where('ref_id', $ref_id);
            return $this->db->get('users');
        }

        function get_user_refId($referralcode)
        {
            $this->db->select('email,username');
            $this->db->where('referral_code', $referralcode);
            return $this->db->get('users');
        }

    }

?>