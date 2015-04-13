<?php

    class Users_model extends CI_Model
    {

        public function Users_model()
        {
            parent::__construct();

            // Other stuff
            $this->_prefix = $this->config->item('DX_table_prefix');
//            $this->_table = $this->_prefix . $this->config->item('DX_users_table');
            $this->_table = 'users';
            $this->_roles_table = $this->_prefix . $this->config->item('DX_roles_table');
        }

        // General function


        function getUserStatusByTwitterUid($twitter_id)
        {
            //echo $twitter_id;exit;	
            $this->db->select('email,twitter_id');
            $this->db->where('twitter_id', $twitter_id);
            //	$this->db->where('status','1');
            $query = $this->db->get('users');
            $aUserDetails = $query->row_array();
            if ($aUserDetails)
            {
                if ($aUserDetails['twitter_id'] != "")
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        function getUserByTwitterUid($tw_uid)
        {
            $this->db->select('twitter_id');
            $this->db->where('twitter_id', $tw_uid);
            $query = $this->db->get('users');
            $aUserDetails = $query->row_array();
            if ($aUserDetails)
            {
                if ($aUserDetails['twitter_id'] != "")
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }

        /* function getUserIdByEmail($sEmail) {
          $this->db->select('user_id,twitter_id');
          $this->db->where('email',$sEmail);
          $query = $this->db->get('user');
          return $query->row_array();
          } */

        function getUserIdByEmail($sEmail)
        {
            $this->db->select('id,twitter_id');
            $this->db->where('email', $sEmail);
            $query = $this->db->get('users');
            return $query->row_array();
        }

        function get_all($offset = 0, $row_count = 0)
        {
            $users_table = $this->_table;
            $roles_table = $this->_roles_table;

            if ($offset >= 0 AND $row_count > 0)
            {
                $this->db->select("$users_table.*", FALSE);
                $this->db->select("$roles_table.name AS role_name", FALSE);
                $this->db->join($roles_table, "$roles_table.id = $users_table.role_id");
                $this->db->order_by("$users_table.id", "ASC");

                $query = $this->db->get($this->_table, $row_count, $offset);
            }
            else
            {
                $query = $this->db->get($this->_table);
            }

            return $query;
        }

        function get_user_by_id($user_id)
        {

            $this->db->where('id', $user_id);

            $result = $this->db->get($this->_table);

            if ($result->num_rows() != 0)
            {
                return $result;
            }
            else
            {
                return false;
            }
        }

        /* function get_user_by_username($username)
          {
          $this->db->where('username', $username);
          return $this->db->get($this->_table);
          } */

        function get_user_by_username($username)
        {
            $this->db->where('username', $username);
            return $this->db->get($this->_table);
        }

        function get_user_by_email($email)
        {
            $this->db->where('email', $email);
            return $this->db->get($this->_table);
        }

        function get_login($login)
        {
            $this->db->where('username', $login);
            $this->db->or_where('email', $login);
            return $this->db->get($this->_table);
        }

        function check_ban($user_id)
        {
            $this->db->select('1', FALSE);
            $this->db->where('id', $user_id);
            $this->db->where('banned', '1');
            return $this->db->get($this->_table);
        }

        function check_username($username)
        {
            $this->db->select('1', FALSE);
            $this->db->where('LOWER(username)=', strtolower($username));
            return $this->db->get($this->_table);
        }

        function check_email($email)
        {
            $this->db->select('1', FALSE);
            $this->db->where('LOWER(email)=', strtolower($email));
            return $this->db->get($this->_table);
        }

        function ban_user($user_id, $reason = NULL)
        {
            $data = array(
                'banned' => 1,
                'ban_reason' => $reason
            );
            return $this->set_user($user_id, $data);
        }

        function unban_user($user_id)
        {
            $data = array(
                'banned' => 0,
                'ban_reason' => NULL
            );
            return $this->set_user($user_id, $data);
        }

        function set_role($user_id, $role_id)
        {
            $data = array(
                'role_id' => $role_id
            );
            return $this->set_user($user_id, $data);
        }

        // User table function

        function create_user($data)
        {
            return $this->db->insert($this->_table, $data);
        }

        function get_user_field($user_id, $fields)
        {
            $this->db->select($fields);
            $this->db->where('id', $user_id);
            return $this->db->get($this->_table);
        }

        function set_user($user_id, $data)
        {
            $data['last_login'] = strtotime(date('Y-m-d H:i:s', time()));
            $this->db->where('id', $user_id);
            return $this->db->update($this->_table, $data);
        }

        function delete_user($user_id)
        {
            $this->db->where('id', $user_id);
            $this->db->delete($this->_table);
            return $this->db->affected_rows() > 0;
        }

        function delete_user_fb($fb_email)
        {
            $this->db->where('email', $fb_email);
            $row = $this->db->get('users')->row();

            $this->db->where('id', $row->id);
            $this->db->delete('users');

            $this->db->where('id', $row->id);
            $this->db->delete('profiles');

            return array($row->ref_id, $row->coupon_code, $row->created, $row->id);
        }

        // Forgot password function

        function newpass($user_id, $pass, $key)
        {
            $data = array(
                'newpass' => $pass,
                'newpass_key' => $key,
                'newpass_time' => date('Y-m-d h:i:s', time() + $this->config->item('DX_forgot_password_expire'))
            );
            return $this->set_user($user_id, $data);
        }

        function activate_newpass($user_id, $key)
        {
            $this->db->set('password', 'newpass', FALSE);
            $this->db->set('newpass', NULL);
            $this->db->set('newpass_key', NULL);
            $this->db->set('newpass_time', NULL);
            $this->db->where('id', $user_id);
            $this->db->where('newpass_key', $key);

            return $this->db->update($this->_table);
        }

        function clear_newpass($user_id)
        {
            $data = array(
                'newpass' => NULL,
                'newpass_key' => NULL,
                'newpass_time' => NULL
            );
            return $this->set_user($user_id, $data);
        }

        // Change password function

        function change_password($user_id, $new_pass)
        {

            $this->db->set('password', $new_pass);
            $this->db->where('id', $user_id);
            return $this->db->update($this->_table);
        }

        function set_twitter_id($uid)
        {
            $this->db->set('twitter_id', $uid);
            //$this->db->where('id', $user_id);
            return $this->db->get('users');
        }

        function hostmail($param)
        {
            //echo $param;
            $data = array();
            $this->db->select('user_id');
            $this->db->from('list');
            $this->db->where('id', $param);
            $query = $this->db->get();
            foreach ($query->result() as $row)
            {
                $userid = $row->user_id;
            }
            $data['hostuserid'] = $userid;
            $this->db->select('email');
            $this->db->from('payout_preferences');
            $this->db->where('user_id', $userid);
            $this->db->where('is_default', '1');
            $query1 = $this->db->get();
            foreach ($query1->result() as $row1)
            {
                $email = $row1->email;
            }
            $data['email'] = $email;
            return $data;
        }

        function getuserselected($search = "", $offset = "", $row_count = "")
        {
            $users_table = $this->_table;
            $roles_table = $this->_roles_table;
            $search = $this->input->post('usersearch');

            if ($offset >= 0 AND $row_count > 0)
            {


                $this->db->like("$users_table.username", $search);
                $this->db->or_like("$users_table.email", $search);
                //$this->db->or_like("$users_table.role" , $search); 
                $this->db->select("$users_table.*", FALSE);
                $this->db->select("$roles_table.name AS role_name", FALSE);
                $this->db->join($roles_table, "$roles_table.id = $users_table.role_id");
                $this->db->order_by("$users_table.id", "ASC");

                $query = $this->db->get($this->_table);
            }
            else
            {
                $query = $this->db->get($this->_table);
            }

            return $query;
        }

        function createTwitterUser($twitter_id, $username)
        {

            $newUserData = array(
                'twitter_id' => $twitter_id,
                'username' => $username,
                'role_id' => 1,
                'created' => time(),
                'last_ip' => $this->input->ip_address(),
                'last_login' => strtotime(date('Y-m-d H:i:s', time()))
            );

            $this->db->insert('users', $newUserData);

            $user_id = $this->db->insert_id();

            $name = explode(' ', $username);
            $firstname = $name[0];
            $lastname = @$name[1];

            $newUserProfile = array(
                'id' => $user_id,
                'Fname' => $firstname,
                'Lname' => $lastname
            );
            $this->db->insert('profiles', $newUserProfile);



            return $user_id;
        }

        function createFacebookUser($fb_id, $username)
        {

            $newUserData = array(
                'fb_id' => $fb_id,
                'username' => $username,
                'role_id' => 1,
                'created' => time(),
                'last_ip' => $this->input->ip_address(),
                'last_login' => strtotime(date('Y-m-d H:i:s', time()))
            );

            $this->db->insert('users', $newUserData);

            $user_id = $this->db->insert_id();

            $name = explode(' ', $username);
            $firstname = $name[0];
            $lastname = @$name[1];

            $newUserProfile = array(
                'id' => $user_id,
                'Fname' => $firstname,
                'Lname' => $lastname
            );
            $this->db->insert('profiles', $newUserProfile);



            return $user_id;
        }

        function getUserInfobyTwitterid($twitter_id)
        {
            $this->db->select('id,email,twitter_id,role_id,username');
            $this->db->where('twitter_id', $twitter_id);
            $query = $this->db->get('users');
            $aUserDetails = $query->row_array();

            return $aUserDetails;
        }

        function getUserInfobyfb_id($fb_id)
        {
            $this->db->select('id,email,fb_id,role_id,username');
            $this->db->where('fb_id', $fb_id);
            $query = $this->db->get('users');
            $aUserDetails = $query->row_array();

            return $aUserDetails;
        }

        function TwitterUserLast($twitter_id)
        {
            $this->db->where('twitter_id', $twitter_id)->update('users', array('last_ip' => $this->input->ip_address(), 'last_login' => strtotime(date('Y-m-d H:i:s', time()))));
        }

        function FacebookUserLast($fb_id)
        {
            $this->db->where('fb_id', $fb_id)->update('users', array('last_ip' => $this->input->ip_address(), 'last_login' => strtotime(date('Y-m-d H:i:s', time()))));
        }

        function check_username_twitter($username)
        {

            $this->db->where('users.username', $username);
            $query = $this->db->get('users');
            $getcount = $query->num_rows();
            if ($getcount == 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

        function check_email_twitter($email)
        {

            $this->db->where('users.email', $email);
            $query = $this->db->get('users');
            $getcount = $query->num_rows();
            if ($getcount == 0)
            {
                return true;
            }
            else
            {
                return false;
            }
        }

// Export CSV
        // txt file

        function exportall_user_txt()
        {
            $this->db->select();
            $this->db->from('users');
            $data = $this->db->get();
            $this->load->dbutil();
            $delimiter = "|";
            $newline = "\r\n";
            $query = $this->dbutil->csv_from_result($data, $delimiter, $newline);
            $site_name = $this->dx_auth->get_site_title();
            $name = 'Export' . $site_name . '.txt';
            force_download($name, $query);
        }

        function export_particular_user_txt($id)
        {
            $user_id = '';
            for ($i = 0; $i < count($id); $i++)
            {
                if ($i == count($id) - 1)
                    $user_id .= 'id = ' . $id[$i];
                else
                    $user_id .= 'id = ' . $id[$i] . ' OR ';
            }
            //$data=$this->db->query('SELECT * FROM users WHERE '.$user_id);
            $data = $this->db->query('
									SELECT id,role_id,ref_id,fb_id,twitter_id,coupon_code,username,password,email,timezone,banned,
									ban_reason,newpass,newpass_key,newpass_time,last_ip,last_login,created,modified FROM users WHERE ' . $user_id);

            $this->load->dbutil();
            $delimiter = "|";
            $newline = "\r\n";
            $query = $this->dbutil->csv_from_result($data, $delimiter, $newline);
            $site_name = $this->dx_auth->get_site_title();
            $name = 'Export' . $site_name . '.txt';
            force_download($name, $query);
        }

        // csv file
        function exportall_user_csv()
        {
            $this->db->select();
            $this->db->from('users');
            $data = $this->db->get();
            $this->load->dbutil();
            $delimiter = ",";
            $newline = "\r\n";
            $query = $this->dbutil->csv_from_result($data, $delimiter, $newline);
            $site_name = $this->dx_auth->get_site_title();
            $name = 'Export' . $site_name . '.csv';
            force_download($name, $query);
        }

        function export_particular_user_csv($id)
        {
            $user_id = '';
            for ($i = 0; $i < count($id); $i++)
            {
                if ($i == count($id) - 1)
                    $user_id .= 'id = ' . $id[$i];
                else
                    $user_id .= 'id = ' . $id[$i] . ' OR ';
            }
            //$data=$this->db->query('SELECT * FROM users WHERE '.$user_id);
            $data = $this->db->query('
									SELECT id,role_id,ref_id,fb_id,twitter_id,coupon_code,username,password,email,timezone,banned,
									ban_reason,newpass,newpass_key,newpass_time,last_ip,last_login,created,modified FROM users WHERE ' . $user_id);
            $this->load->dbutil();
            $delimiter = ",";
            $newline = "\r\n";
            $query = $this->dbutil->csv_from_result($data, $delimiter, $newline);
            $site_name = $this->dx_auth->get_site_title();
            $name = 'Export' . $site_name . '.csv';
            force_download($name, $query);
        }

// Export CSV	


        function get_list_by_id($table = '', $search_term = '', $list_id)
        {
            $this->db->where($search_term, $list_id);
            $query = $this->db->get($table);
            $lists = $query->row_array();
            return $lists;
        }

    }

?>