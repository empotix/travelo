<?php

    /**
     * Dropinn Common_model Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package	Dropinn
     * @subpackage	Models
     * @category	Common_model 
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    class Common_model extends CI_Model
    {

        /**
         * Constructor 
         *
         */
        function Common_model()
        {
            parent::__construct();

            // load model
            $this->load->model('page_model');
        }

//Controller End

        /**
         * Set Style for the flash messages
         *
         * @access	public
         * @param	string	the type of the flash message
         * @param	string  flash message 
         * @return	string	flash message with proper style
         */
        function flash_message($type, $message)
        {
            switch ($type)
            {
                case 'success':
                    $data = '<div class="clsShow_Notification"><p class="success"><span>' . $message . '</span></p></div>';
                    break;
                case 'error':
                    $data = '<div class="clsShow_Notification"><p class="error"><span>' . $message . '</span></p></div>';
                    break;
            }
            return $data;
        }

//End of flash_message Function

        /**
         * Set Style for the flash messages in admin section
         *
         * @access	public
         * @param	string	the type of the flash message
         * @param	string  flash message 
         * @return	string	flash message with proper style
         */
        function admin_flash_message($type, $message)
        {
            switch ($type)
            {
                case 'success':
                    $data = '<div class="message"><div class="success">' . $message . '</div></div>';
                    break;
                case 'error':
                    $data = '<div class="message"><div class="error">' . $message . '</div></div>';
                    break;
            }
            return $data;
        }

//End of flash_message Function

        function getCountries($conditions = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('country');
            $this->db->select('country.id,country.country_symbol,country.country_name');
            $result = $this->db->get();
            return $result;
        }

        /**
         * Get getPages
         *
         * @access	public
         * @param	array	conditions to fetch data
         * @return	object	object with result set
         */
        function getPages()
        {
            $conditions = array('page.is_active' => 1);
            $pages = array();
            $pages['staticPages'] = $this->page_model->getPages($conditions);
            return $pages['staticPages'];
        }

        /**
         * Get getPages
         *
         * @access	public
         * @param	array	conditions to fetch data
         * @return	object	object with result set
         */
        function getSitelogo()
        {
            $conditions = array('settings.code' => 'SITE_LOGO');
            $data = array();
            $this->db->where($conditions);
            $this->db->from('settings');
            $this->db->select('settings.string_value');
            $result = $this->db->get();
            $data['site_logo'] = $result->result();
            return $data;
        }

        function getTableData($table = '', $conditions = array(), $fields = '', $like = array(), $limit = array(), $orderby = array(), $like1 = array(), $order = array(), $conditions1 = array())
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
            if (is_array($orderby) and count($orderby) > 0)
                $this->db->order_by('id', 'desc');

            //Check for Order by
            if (is_array($order) and count($order) > 0)
                $this->db->order_by($order[0], $order[1]);

            $this->db->from($table);

            //Check For Fields	 
            if ($fields != '')
                $this->db->select($fields);
            else
                $this->db->select();

            $result = $this->db->get();

            //pr($result->result());
            return $result;
        }

        function getHelpData($table = '', $conditions = array(), $fields = '', $like = array(), $limit = array(), $orderby = array(), $like1 = array(), $order = array(), $conditions1 = array())
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
            if (is_array($orderby) and count($orderby) > 0)
                $this->db->order_by('id', 'desc');

            //Check for Order by
            if (is_array($order) and count($order) > 0)
                $this->db->order_by($order[0], $order[1]);

            $this->db->where('status', 0)->from($table);

            //Check For Fields	 
            if ($fields != '')
                $this->db->select($fields);
            else
                $this->db->select();

            $result = $this->db->get();

            //pr($result->result());
            return $result;
        }

        function deleteTableData($table = '', $conditions = array())
        {
            //Check For Conditions
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->delete($table);
            return $this->db->affected_rows();
        }

//End of deleteTableData Function

        function insertData($table = '', $insertData = array())
        {
            $this->db->insert($table, $insertData);
            return $this->db->insert_id();
        }

//End of insertData Function

        function updateTableData($table = '', $id = 0, $conditions = array(), $updateData = array())
        {
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);
            else
                $this->db->where('id', $id);
            $this->db->update($table, $updateData);
        }

//End of updateTableData Function

        function inserTableData($table = '', $insertData = array())
        {

            $this->db->insert($table, $insertData);
            return $this->db->insert_id();
        }

//End of inserTableData Function		

        function getToplocation($conditions = array())
        {
            //Check For Conditions
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('toplocations');
            $this->db->join('toplocation_categories', 'toplocations.category_id= toplocation_categories.id', 'inner');
            $this->db->select('toplocations.id,toplocations.category_id,toplocations.search_code,toplocations.name,toplocation_categories.id as categories_id,toplocation_categories.name as categoryname');
            $result = $this->db->get();
            $results = $result->result();

            return $result;
        }

        function get_statistics($conditions = array())
        {

            $this->db->select('year');
            $this->db->from('statistics');
            $this->db->order_by("year", "asc");
            $this->db->where($conditions);
            $query = $this->db->get();
            $results = $query->result();
            return $results;
        }

        function add_page_statistics($room_id, $conditions_statistics = array())
        {
            $today_date = date("j");
            $today_month = date("F");
            $today_year = date("Y");
            $this->db->select('*');
            $this->db->from('statistics');
            $this->db->where($conditions_statistics);
            $query1 = $this->db->get();
            $results = $query1->result();
            $count = count($results);
            if ($count == 0)
            {

                $data = array('list_id' => $room_id,
                    'page_view' => '1',
                    'date' => trim($today_date),
                    'month' => trim($today_month),
                    'year' => trim($today_year)
                );
                $this->db->insert('statistics', $data);
            }
            else
            {
                foreach ($results as $data)
                {
                    $page_view = $data->page_view;
                }
                $page_view = $page_view + 1;
                $condition = array('list_id' => $room_id,
                    'date' => trim($today_date),
                    'month' => trim($today_month),
                    'year' => trim($today_year)
                );

                $data = array(
                    'page_view' => $page_view,
                );

                $this->db->where($condition);
                $this->db->update('statistics', $data);
            }
        }

        function get_coupon()
        {
            $this->db->select('coupon.id,coupon.couponcode,coupon.coupon_price,coupon.currency,coupon.expirein,coupon.status');
            $this->db->where('coupon.status', 0);
            $result = $this->db->get('coupon');
            //print_r($result);exit;
            return $result;
        }

        function insert_coupon_users($insertData = array())
        {
            $this->db->insert('coupon_users', $insertData);
        }

        function get_coupon_users_list()
        {
            $list_id = $this->input->post('hosting_id');
            $coupon_code = $this->input->post('coupon_code');
            $user_id = ($this->dx_auth->get_user_id());
            $this->db->select('coupon_users.id,coupon_users.list_id,coupon_users.user_id,coupon_users.used_coupon_code,coupon_users.status');
            $this->db->where('coupon_users.status', 1);
            $this->db->where('coupon_users.used_coupon_code', $coupon_code);
            //$this->db->where('coupon_users.list_id',$list_id);
            $this->db->where('coupon_users.user_id', $user_id);
            $result = $this->db->get('coupon_users');
            /* $str = $this->db->last_query();
              print_r($str);exit; */
            return $result;
        }

        function get_coupon_users()
        {

            $list_id = $this->input->post('hosting_id');
            $coupon_code = $this->input->post('coupon_code');
            $user_id = ($this->dx_auth->get_user_id());
            $this->db->select('coupon_users.id,coupon_users.list_id,coupon_users.user_id,coupon_users.used_coupon_code,coupon_users.status');
            $this->db->where('coupon_users.status', 1);
            $this->db->where('coupon_users.user_id', $user_id);
            $this->db->where('coupon_users.used_coupon_code', $coupon_code);
            //$this->db->where('coupon_users.list_id',$list_id);
            $result = $this->db->get('coupon_users');
            /* $str = $this->db->last_query();
              print_r($str);exit; */
            return $result;
        }

        function popular()
        {
            $query = "SELECT shortlist FROM users ORDER BY LENGTH( shortlist ) DESC , shortlist DESC";  //query for getting shortlist column from users table order by shortlist length
            $result = $this->db->query($query);
            return $result;
        }

        function city_name($city_id)
        {
            $result = $this->db->select('city_name')->where('id', $city_id)->get('neigh_city');
            if ($result->num_rows() != 0)
            {
                return $result->row()->city_name;
            }
            else
            {
                return false;
            }
        }

        function place_name($place_id)
        {
            $result = $this->db->where('id', $place_id)->get('neigh_city_place');
            if ($result->num_rows() != 0)
            {
                return $result->row()->place_name;
            }
            else
            {
                return false;
            }
        }

    }

// End Common_model Class

    /* End of file Common_model.php */
    /* Location: ./app/models/Common_model.php */
?>
