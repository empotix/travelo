<?php

    /**
     * Dropinn help_model Class
     *
     * Help to handle tables related to static helps of the system.
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
    class help_model extends CI_Model
    {

        /**
         * Constructor 
         *
         */
        function help_model()
        {
            parent::__construct();
        }

//Controller End

        /**
         * Delete help
         *
         * @access	private
         * @param	array	an associative array of insert values
         * @return	void
         */
        function deletehelp($id = 0, $conditions = array())
        {
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);
            else
                $this->db->where('id', $id);
            $this->db->delete('help');
        }

//End of addFaqCategory Function

        /**
         * Get Static helps
         *
         * @access	private
         * @param	array	conditions to fetch data
         * @return	object	object with result set
         */
        // Puhal changes Start. For the popup helps Privacy Policy and the Company & Conditions (Sep 17 Issue 2)	 
        function gethelps($conditions = array(), $like = array(), $like_or = array())
        {
            //Check For like statement
            if (is_array($like) and count($like) > 0)
                $this->db->like($like);

            //Check For like statement
            if (is_array($like_or) and count($like_or) > 0)
                $this->db->or_like($like_or);
// Puhal changes End. For the popup helps Privacy Policy and the Company & Conditions (Sep 17 Issue 2)			
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('help');
            $this->db->select();
            $result = $this->db->get();
            return $result;
        }

//End of getFaqs Function

        /**
         * Add  Static help
         *
         * @access	private
         * @param	array	an associative array of insert values
         * @return	void
         */
        function addhelp($insertData = array())
        {

            $this->db->get('help');
            $this->db->insert('help', $insertData);
        }

//End of addFaqCategory Function
        // --------------------------------------------------------------------

        /**
         * Update Static help
         *
         * @access	private
         * @param	array	an associative array - for update key values
         * @param	array	an associative array of update data
         * @return	void
         */
        function updatehelp($updateKey = array(), $updateData = array())
        {
            $this->db->update('help', $updateData, $updateKey);
        }

//End of updateFaq Function 
    }

// End help_model Class
   
/* End of file help_model.php */ 
/* Location: ./app/models/help_model.php */