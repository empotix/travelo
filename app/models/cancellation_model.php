<?php

    /**
     * Dropinn Page_model Class
     *
     * Help to handle tables related to static pages of the system.
     *
     * @package		Dropinn
     * @subpackage	Models
     * @category	Common_model 
     * @author		Cogzidel Product Team
     * @version		Version 1.5
     * @link		http://www.cogzidel.com

      <Dropinn>
      Copyright (C) <2013>  <Cogzidel Technologies>

      This program is free software: you can redistribute it and/or modify
      it under the terms of the GNU General Public License as published by
      the Free Software Foundation, either version 3 of the License, or
      (at your option) any later version.

      This program is distributed in the hope that it will be useful,
      but WITHOUT ANY WARRANTY; without even the implied warranty of
      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
      GNU General Public License for more details.

      You should have received a copy of the GNU General Public License
      along with this program.  If not, see <http://www.gnu.org/licenses/>
      If you want more information, please email me at bala.k@cogzidel.com or
      contact us from http://www.cogzidel.com/contact

     */
    class Cancellation_model extends CI_Model
    {

        /**
         * Constructor 
         *
         */
        function Cancellation_model()
        {
            parent::__construct();
        }

//Controller End

        /**
         * Delete page
         *
         * @access	private
         * @param	array	an associative array of insert values
         * @return	void
         */
        function deleteCancellation($id = 0, $conditions = array())
        {
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);
            else
                $this->db->where('id', $id);
            $this->db->delete('cancellation_policy');
        }

//End of addFaqCategory Function

        /**
         * Get Static Pages
         *
         * @access	private
         * @param	array	conditions to fetch data
         * @return	object	object with result set
         */
        // Puhal changes Start. For the popup pages Privacy Policy and the Company & Conditions (Sep 17 Issue 2)	 
        function getcancellation($conditions = array(), $like = array(), $like_or = array())
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

            $this->db->from('cancellation_policy');
            $this->db->select();
            $result = $this->db->get();
            return $result;
        }

//End of getFaqs Function

        /**
         * Add  Static Page
         *
         * @access	private
         * @param	array	an associative array of insert values
         * @return	void
         */
        function addCancellation($insertData = array())
        {
            $this->db->insert('cancellation_policy', $insertData);
        }

//End of addFaqCategory Function
        // --------------------------------------------------------------------

        /**
         * Update Static Page
         *
         * @access	private
         * @param	array	an associative array - for update key values
         * @param	array	an associative array of update data
         * @return	void
         */
        function updateCancellation($updateKey = array(), $updateData = array())
        {
            $this->db->update('cancellation_policy', $updateData, $updateKey);
        }

//End of updateFaq Function 
    }

// End Page_model Class
   
/* End of file Page_model.php */ 
/* Location: ./app/models/Page_model.php */