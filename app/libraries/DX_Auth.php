<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class DX_Auth
    {

        // Private
        var $_banned;
        var $_ban_reason;
        var $_auth_error;
        var $_captcha_image;

        function DX_Auth()
        {
            $this->ci = & get_instance();

            log_message('debug', 'DX Auth Initialized');

            $this->ci->load->library('Session');
            $this->ci->load->model('Users_model');
            $this->ci->load->database();

            // Load DX Auth config
            $this->ci->load->config('dx_auth');


            // Load DX Auth event
            $this->ci->load->library('DX_Auth_Event');

            $this->_init();
        }

        function _init()
        {
            // When we load this library, auto Login any returning users
            $this->autologin();

            // Init helper config variable
            $this->email_activation = $this->ci->config->item('DX_email_activation');

            $this->allow_registration = $this->ci->config->item('DX_allow_registration');
            $this->captcha_registration = $this->ci->config->item('DX_captcha_registration');

            $this->captcha_login = $this->ci->config->item('DX_captcha_login');

            // URIs
            $this->banned_uri = $this->ci->config->item('DX_banned_uri');
            $this->deny_uri = $this->ci->config->item('DX_deny_uri');
            $this->login_uri = $this->ci->config->item('DX_login_uri');
            $this->logout_uri = $this->ci->config->item('DX_logout_uri');
            $this->register_uri = $this->ci->config->item('DX_register_uri');
            $this->activate_uri = $this->ci->config->item('DX_activate_uri');
            $this->forgot_password_uri = $this->ci->config->item('DX_forgot_password_uri');
            $this->reset_password_uri = $this->ci->config->item('DX_reset_password_uri');
            $this->change_password_uri = $this->ci->config->item('DX_change_password_uri');
            $this->cancel_account_uri = $this->ci->config->item('DX_cancel_account_uri');

            // Forms view
            $this->login_view = $this->ci->config->item('DX_login_view');
            $this->register_view = $this->ci->config->item('DX_register_view');
            $this->forgot_password_view = $this->ci->config->item('DX_forgot_password_view');
            $this->change_password_view = $this->ci->config->item('DX_change_password_view');
            $this->cancel_account_view = $this->ci->config->item('DX_cancel_account_view');

            // Pages view
            $this->deny_view = $this->ci->config->item('DX_deny_view');
            $this->banned_view = $this->ci->config->item('DX_banned_view');
            $this->logged_in_view = $this->ci->config->item('DX_logged_in_view');
            $this->logout_view = $this->ci->config->item('DX_logout_view');

            $this->register_success_view = $this->ci->config->item('DX_register_success_view');
            $this->activate_success_view = $this->ci->config->item('DX_activate_success_view');
            $this->forgot_password_success_view = $this->ci->config->item('DX_forgot_password_success_view');
            $this->reset_password_success_view = $this->ci->config->item('DX_reset_password_success_view');
            $this->change_password_success_view = $this->ci->config->item('DX_change_password_success_view');

            $this->register_disabled_view = $this->ci->config->item('DX_register_disabled_view');
            $this->activate_failed_view = $this->ci->config->item('DX_activate_failed_view');
            $this->reset_password_failed_view = $this->ci->config->item('DX_reset_password_failed_view');
        }

        function _gen_pass($len = 8)
        {
            // No Zero (for user clarity);
            $pool = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $str = '';
            for ($i = 0; $i < $len; $i++)
            {
                $str .= substr($pool, mt_rand(0, strlen($pool) - 1), 1);
            }

            return $str;
        }

        function _encode($password)
        {
            $majorsalt = $this->ci->config->item('DX_salt');

            // if PHP5
            if (function_exists('str_split'))
            {
                $_pass = str_split($password);
            }
            // if PHP4
            else
            {
                $_pass = array();
                if (is_string($password))
                {
                    for ($i = 0; $i < strlen($password); $i++)
                    {
                        array_push($_pass, $password[$i]);
                    }
                }
            }

            // encrypts every single letter of the password
            foreach ($_pass as $_hashpass)
            {
                $majorsalt .= md5($_hashpass);
            }


            return md5($majorsalt);
        }

        function _array_in_array($needle, $haystack)
        {
            // Make sure $needle is an array for foreach
            if (!is_array($needle))
            {
                $needle = array($needle);
            }

            // For each value in $needle, return TRUE if in $haystack
            foreach ($needle as $pin)
            {
                if (in_array($pin, $haystack))
                    return TRUE;
            }
            // Return FALSE if none of the values from $needle are found in $haystack
            return FALSE;
        }

        function _email($to, $from, $subject, $message)
        {
            $this->ci->load->library('Email');
            $email = $this->ci->email;

            $email->from($from);
            $email->to($to);
            $email->subject($subject);
            $email->message($message);

            return $email->send();
        }

        // Set last ip and last login function when user login
        function _set_last_ip_and_last_login($user_id)
        {
            $data = array();

            if ($this->ci->config->item('DX_login_record_ip'))
            {
                $data['last_ip'] = $this->ci->input->ip_address();
            }

            if ($this->ci->config->item('DX_login_record_time'))
            {
                $data['last_login'] = date('Y-m-d H:i:s', time());
            }

            if (!empty($data))
            {
                $this->ci->load->model('users_model', 'users');
                $this->ci->Users_model->set_user($user_id, $data);
            }
        }

        function _increase_login_attempt()
        {
            if ($this->ci->config->item('DX_count_login_attempts') AND ! $this->is_max_login_attempts_exceeded())
            {
                $this->ci->load->model('dx_auth/login_attempts', 'login_attempts');
                $this->ci->login_attempts->increase_attempt($this->ci->input->ip_address());
            }
        }

        function _clear_login_attempts()
        {
            if ($this->ci->config->item('DX_count_login_attempts'))
            {
                $this->ci->load->model('dx_auth/login_attempts', 'login_attempts');
                // Clear login attempts for current IP
                $this->ci->login_attempts->clear_attempts($this->ci->input->ip_address());
            }
        }

        function _get_role_data($role_id)
        {
            // Load models
            $this->ci->load->model('dx_auth/roles', 'roles');
            $this->ci->load->model('dx_auth/permissions', 'permissions');

            // Clear return value
            $role_name = '';
            $parent_roles_id = array();
            $parent_roles_name = array();
            $permission = array();
            $parent_permissions = array();


            $query = $this->ci->roles->get_role_by_id($role_id);

            // Check if role exist
            if ($query->num_rows() > 0)
            {

                $role = $query->row();


                $role_name = $role->name;


                if ($role->parent_id > 0)
                {
                    $parent_roles_id[] = $role->parent_id;

                    $finished = FALSE;
                    $parent_id = $role->parent_id;

                    while ($finished == FALSE)
                    {
                        $i_query = $this->ci->roles->get_role_by_id($parent_id);

                        if ($i_query->num_rows() > 0)
                        {
                            $i_role = $i_query->row();

                            if ($i_role->parent_id == 0)
                            {
                                $parent_roles_name[] = $i_role->name;
                                $finished = TRUE;
                            }
                            else
                            {
                                // Change parent id for next looping
                                $parent_id = $i_role->parent_id;

                                // Add to result array
                                $parent_roles_id[] = $parent_id;
                                $parent_roles_name[] = $i_role->name;
                            }
                        }
                        else
                        {
                            // Remove latest parent_roles_id since parent_id not found
                            array_pop($parent_roles_id);
                            // Stop looping
                            $finished = TRUE;
                        }
                    }
                }
            }


            $permission = $this->ci->permissions->get_permission_data($role_id);

            // Get user role parent permissions
            if (!empty($parent_roles_id))
            {
                $parent_permissions = $this->ci->permissions->get_permissions_data($parent_roles_id);
            }


            // Set return value
            $data['role_name'] = $role_name;
            $data['parent_roles_id'] = $parent_roles_id;
            $data['parent_roles_name'] = $parent_roles_name;
            $data['permission'] = $permission;
            $data['parent_permissions'] = $parent_permissions;

            return $data;
        }

        function _create_autologin($user_id)
        {
            $result = FALSE;

            // User wants to be remembered
            $user = array(
                'key_id' => substr(md5(uniqid(rand() . $this->ci->input->cookie($this->ci->config->item('sess_cookie_name')))), 0, 16),
                'user_id' => $user_id
            );

            // Load Models
            $this->ci->load->model('dx_auth/user_autologin', 'user_autologin');

            // Prune keys
            $this->ci->user_autologin->prune_keys($user['user_id']);

            if ($this->ci->user_autologin->store_key($user['key_id'], $user['user_id']))
            {
                // Set Users AutoLogin cookie
                $this->_auto_cookie($user);

                $result = TRUE;
            }

            return $result;
        }

        function autologin()
        {
            $result = FALSE;

            if ($auto = $this->ci->input->cookie($this->ci->config->item('DX_autologin_cookie_name')) AND ! $this->ci->session->userdata('DX_logged_in'))
            {
                // Extract data
                $auto = unserialize($auto);

                if (isset($auto['key_id']) AND $auto['key_id'] AND $auto['user_id'])
                {
                    // Load Models				
                    $this->ci->load->model('dx_auth/user_autologin', 'user_autologin');

                    // Get key
                    $query = $this->ci->user_autologin->get_key($auto['key_id'], $auto['user_id']);

                    if ($result = $query->row())
                    {
                        // User verified, log them in
                        $this->_set_session($result);
                        // Renew users cookie to prevent it from expiring
                        $this->_auto_cookie($auto);

                        // Set last ip and last login
                        $this->_set_last_ip_and_last_login($auto['user_id']);

                        $result = TRUE;
                    }
                }
            }

            return $result;
        }

        function _delete_autologin()
        {
            if ($auto = $this->ci->input->cookie($this->ci->config->item('DX_autologin_cookie_name')))
            {
                // Load Cookie Helper
                $this->ci->load->helper('cookie');

                // Load Models
                $this->ci->load->model('dx_auth/user_autologin', 'user_autologin');

                // Extract data
                $auto = unserialize($auto);

                // Delete db entry
                $this->ci->user_autologin->delete_key($auto['key_id'], $auto['user_id']);

                // Make cookie expired
                set_cookie($this->ci->config->item('DX_autologin_cookie_name'), '', -1);
            }
        }

        function _set_session($data)
        {
            // Get role data
            $role_data = $this->_get_role_data($data->role_id);

            // Set session data array
            $user = array(
                'DX_user_id' => $data->id,
                'DX_username' => $data->username,
                'DX_emailId' => $data->email,
                'DX_refId' => $data->ref_id,
                'DX_role_id' => $data->role_id,
                'DX_role_name' => $role_data['role_name'],
                'DX_parent_roles_id' => $role_data['parent_roles_id'],
                'DX_parent_roles_name' => $role_data['parent_roles_name'],
                'DX_permission' => $role_data['permission'],
                'DX_parent_permissions' => $role_data['parent_permissions'],
                'DX_logged_in' => TRUE
            );

            $this->ci->session->set_userdata($user);
        }

        function _auto_cookie($data)
        {
            $this->ci->load->helper('cookie');

            $cookie = array(
                'name' => $this->ci->config->item('DX_autologin_cookie_name'),
                'value' => serialize($data),
                'expire' => $this->ci->config->item('DX_autologin_cookie_life')
            );

            set_cookie($cookie);
        }

        function check_uri_permissions($allow = TRUE)
        {
            // First check if user already logged in or not
            if ($this->is_logged_in())
            {
                // If user is not admin
                if (!$this->is_admin())
                {
                    // Get variable from current URI
                    $controller = '/' . $this->ci->uri->rsegment(1) . '/';
                    if ($this->ci->uri->rsegment(2) != '')
                    {
                        $action = $controller . $this->ci->uri->rsegment(2) . '/';
                    }
                    else
                    {
                        $action = $controller . 'index/';
                    }


                    $roles_allowed_uris = $this->get_permissions_value('uri');

                    // Variable to determine if URI found
                    $have_access = !$allow;
                    // Loop each roles URI permissions
                    foreach ($roles_allowed_uris as $allowed_uris)
                    {
                        if ($allowed_uris != NULL)
                        {
                            // Check if user allowed to access URI
                            if ($this->_array_in_array(array('/', $controller, $action), $allowed_uris))
                            {
                                $have_access = $allow;
                                // Stop loop
                                break;
                            }
                        }
                    }

                    // Trigger event
                    $this->ci->dx_auth_event->checked_uri_permissions($this->get_user_id(), $have_access);

                    if (!$have_access)
                    {
                        // User didn't have previlege to access current URI, so we show user 403 forbidden access
                        $this->deny_access();
                    }
                }
            }
            else
            {
                // User haven't logged in, so just redirect user to login page
                $this->deny_access('login');
            }
        }

        function get_permission_value($key, $check_parent = TRUE)
        {
            // Default return value
            $result = NULL;

            // Get current user permission
            $permission = $this->ci->session->userdata('DX_permission');

            // Check if key is in user permission array
            if (array_key_exists($key, $permission))
            {
                $result = $permission[$key];
            }
            // Key not found
            else
            {
                if ($check_parent)
                {
                    // Get current user parent permissions
                    $parent_permissions = $this->ci->session->userdata('DX_parent_permissions');

                    // Check parent permissions array				
                    foreach ($parent_permissions as $permission)
                    {
                        if (array_key_exists($key, $permission))
                        {
                            $result = $permission[$key];
                            break;
                        }
                    }
                }
            }

            // Trigger event
            $this->ci->dx_auth_event->got_permission_value($this->get_user_id(), $key);

            return $result;
        }

        function get_permissions_value($key, $array_key = 'default')
        {
            $result = array();

            $role_id = $this->ci->session->userdata('DX_role_id');
            $role_name = $this->ci->session->userdata('DX_role_name');

            $parent_roles_id = $this->ci->session->userdata('DX_parent_roles_id');
            $parent_roles_name = $this->ci->session->userdata('DX_parent_roles_name');

            // Get current user permission
            $value = $this->get_permission_value($key, FALSE);

            if ($array_key == 'role_id')
            {
                $result[$role_id] = $value;
            }
            elseif ($array_key == 'role_name')
            {
                $result[$role_name] = $value;
            }
            else
            {
                array_push($result, $value);
            }

            // Get current user parent permissions
            $parent_permissions = $this->ci->session->userdata('DX_parent_permissions');

            $i = 0;
            foreach ($parent_permissions as $permission)
            {
                if (array_key_exists($key, $permission))
                {
                    $value = $permission[$key];
                }

                if ($array_key == 'role_id')
                {
                    // It's safe to use $parents_roles_id[$i] because array order is same with permission array
                    $result[$parent_roles_id[$i]] = $value;
                }
                elseif ($array_key == 'role_name')
                {
                    // It's safe to use $parents_roles_name[$i] because array order is same with permission array
                    $result[$parent_roles_name[$i]] = $value;
                }
                else
                {
                    array_push($result, $value);
                }

                $i++;
            }

            // Trigger event
            $this->ci->dx_auth_event->got_permissions_value($this->get_user_id(), $key);

            return $result;
        }

        function deny_access($uri = 'deny')
        {
            $this->ci->load->helper('url');

            if ($uri == 'login')
            {
                redirect($this->ci->config->item('DX_login_uri'), 'location');
            }
            else if ($uri == 'banned')
            {
                redirect($this->ci->config->item('DX_banned_uri'), 'location');
            }
            else
            {
                redirect($this->ci->config->item('DX_deny_uri'), 'location');
            }
            exit;
        }

        // Get Site Title
        function get_site_title()
        {
            $site_title = $this->ci->db->get_where('settings', array('code' => 'SITE_TITLE'))->row()->string_value;
            return $site_title;
        }

        // Get Site Super Admin Email ID
        function get_site_sadmin()
        {
            $site_admin = $this->ci->db->get_where('settings', array('code' => 'SITE_ADMIN_MAIL'))->row()->string_value;
            return $site_admin;
        }

        // Get user id
        function get_user_id()
        {
            return $this->ci->session->userdata('DX_user_id');
        }

        // Get username string
        function get_username()
        {
            return $this->ci->session->userdata('DX_username');
        }

        // Get email string
        function get_emailId()
        {
            return $this->ci->session->userdata('DX_emailId');
        }

        // Get refId string
        function get_refId()
        {
            return $this->ci->session->userdata('DX_refId');
        }

        // Get user role id
        function get_role_id()
        {
            return $this->ci->session->userdata('DX_role_id');
        }

        // Get user role name
        function get_role_name()
        {
            return $this->ci->session->userdata('DX_role_name');
        }

        // Check is user is has admin privilege
        function is_admin()
        {
            return strtolower($this->ci->session->userdata('DX_role_name')) == 'admin';
        }

        function is_role($roles = array(), $use_role_name = TRUE, $check_parent = TRUE)
        {
            // Default return value
            $result = FALSE;

            // Build checking array
            $check_array = array();

            if ($check_parent)
            {
                // Add parent roles into check array
                if ($use_role_name)
                {
                    $check_array = $this->ci->session->userdata('DX_parent_roles_name');
                }
                else
                {
                    $check_array = $this->ci->session->userdata('DX_parent_roles_id');
                }
            }

            // Add current role into check array
            if ($use_role_name)
            {
                array_push($check_array, $this->ci->session->userdata('DX_role_name'));
            }
            else
            {
                array_push($check_array, $this->ci->session->userdata('DX_role_id'));
            }

            // If $roles not array then we add it into an array
            if (!is_array($roles))
            {
                $roles = array($roles);
            }

            if ($use_role_name)
            {
                // Convert check array into lowercase since we want case insensitive checking
                for ($i = 0; $i < count($check_array); $i++)
                {
                    $check_array[$i] = strtolower($check_array[$i]);
                }

                // Convert roles into lowercase since we want insensitive checking
                for ($i = 0; $i < count($roles); $i++)
                {
                    $roles[$i] = strtolower($roles[$i]);
                }
            }

            // Check if roles exist in check_array
            if ($this->_array_in_array($roles, $check_array))
            {
                $result = TRUE;
            }

            return $result;
        }

        // Check if user is logged in
        function is_logged_in()
        {
            return $this->ci->session->userdata('DX_logged_in');
        }

        // Check if user is a banned user, call this only after calling login() and returning FALSE
        function is_banned()
        {
            return $this->_banned;
        }

        // Get ban reason, call this only after calling login() and returning FALSE
        function get_ban_reason()
        {
            return $this->_ban_reason;
        }

        // Check if username is available to use, by making sure there is no same username in the database
        function is_username_available($username)
        {
            $this->ci->load->model('users_model', 'users');
            $this->ci->load->model('dx_auth/user_temp', 'user_temp');

            $users = $this->ci->Users_model->check_username($username);
            $temp = $this->ci->user_temp->check_username($username);

            return $Users_model->num_rows() + $temp->num_rows() == 0;
        }

        // Check if email is available to use, by making sure there is no same email in the database
        function is_email_available($email)
        {
            // Load Models
            $this->ci->load->model('users_model', 'users');
            $this->ci->load->model('dx_auth/user_temp', 'user_temp');

            $users = $this->ci->Users_model->check_email($email);
            $temp = $this->ci->user_temp->check_email($email);

            return $Users_model->num_rows() + $temp->num_rows() == 0;
        }

        // Check if login attempts bigger than max login attempts specified in config
        function is_max_login_attempts_exceeded()
        {
            $this->ci->load->model('dx_auth/login_attempts', 'login_attempts');

            return ($this->ci->login_attempts->check_attempts($this->ci->input->ip_address())->num_rows() >= $this->ci->config->item('DX_max_login_attempts'));
        }

        function get_auth_error()
        {
            return $this->_auth_error;
        }

        function login($login, $password, $remember = TRUE)
        {
            // Load Models
            $this->ci->load->model('users_model', 'users');
            $this->ci->load->model('dx_auth/user_temp', 'user_temp');
            $this->ci->load->model('dx_auth/login_attempts', 'login_attempts');

            // Default return value
            $result = FALSE;

            if (!empty($login) AND ! empty($password))
            {
                // Get which function to use based on config
                if ($this->ci->config->item('DX_login_using_username') AND $this->ci->config->item('DX_login_using_email'))
                {
                    $get_user_function = 'get_login';
                }
                else if ($this->ci->config->item('DX_login_using_email'))
                {
                    $get_user_function = 'get_user_by_email';
                }
                else
                {
                    $get_user_function = 'get_user_by_username';
                }

                // Get user query
                if ($query = $this->ci->Users_model->$get_user_function($login) AND $query->num_rows() == 1)
                {
                    // Get user record
                    $row = $query->row();

                    // Check if user is banned or not
                    if ($row->banned > 0)
                    {
                        // Set user as banned
                        $this->_banned = TRUE;
                        // Set ban reason
                        $this->_ban_reason = $row->ban_reason;
                    }
                    // If it's not a banned user then try to login
                    else
                    {
                        $password = $this->_encode($password);
                        $stored_hash = $row->password;

                        // Is password matched with hash in database ?
                        if (crypt($password, $stored_hash) === $stored_hash)
                        {
                            // Log in user 
                            $this->_set_session($row);

                            if ($row->newpass)
                            {
                                // Clear any Reset Passwords
                                $this->ci->Users_model->clear_newpass($row->id);
                            }

                            if ($remember)
                            {
                                // Create auto login if user want to be remembered
                                $this->_create_autologin($row->id);
                            }

                            // Set last ip and last login
                            $this->_set_last_ip_and_last_login($row->id);
                            // Clear login attempts
                            $this->_clear_login_attempts();

                            // Trigger event
                            $this->ci->dx_auth_event->user_logged_in($row->id);

                            // Set return value
                            $result = TRUE;
                        }
                        else
                        {
                            // Increase login attempts
                            $this->_increase_login_attempt();
                            // Set error message
                            $this->_auth_error = $this->ci->lang->line('auth_login_incorrect_password');
                        }
                    }
                }
                // Check if login is still not activated
                elseif ($query = $this->ci->user_temp->$get_user_function($login) AND $query->num_rows() == 1)
                {
                    // Set error message
                    $this->_auth_error = $this->ci->lang->line('auth_not_activated');
                }
                else
                {
                    // Increase login attempts
                    $this->_increase_login_attempt();
                    // Set error message
                    $this->_auth_error = $this->ci->lang->line('auth_login_username_not_exist');
                }
            }

            return $result;
        }

        function logout()
        {
            // Trigger event
            $this->ci->dx_auth_event->user_logging_out($this->ci->session->userdata('DX_user_id'));

            // Delete auto login
            if ($this->ci->input->cookie($this->ci->config->item('DX_autologin_cookie_name')))
            {
                $this->_delete_autologin();
            }

            // Destroy session
            $this->ci->session->sess_destroy();
        }

        function register($username, $password, $email)
        {
            // Load Models
            $this->ci->load->model('users_model', 'users');
            $this->ci->load->model('dx_auth/user_temp', 'user_temp');

            $this->ci->load->helper('url');

            // Default return value
            $result = FALSE;


            srand((double) microtime() * 1000000);
            $coupon_code = rand(10000, 99999);

            // New user array
            $new_user = array(
                'username' => $username,
                'password' => crypt($this->_encode($password)),
                'email' => $email,
                'ref_id' => md5($username),
                'coupon_code' => $coupon_code,
                'last_ip' => $this->ci->input->ip_address()
            );

            // Do we need to send email to activate user
            if ($this->ci->config->item('DX_email_activation'))
            {
                $new_user['activation_key'] = md5(rand() . microtime());

                $insert = $this->ci->user_temp->create_temp($new_user);
            }
            else
            {
                // Create user 
                $insert = $this->ci->Users_model->create_user($new_user);
                // Trigger event
                $this->ci->dx_auth_event->user_activated($this->ci->db->insert_id());
            }

            if ($insert)
            {
                // Replace password with plain for email
                $new_user['password'] = $password;

                $result = $new_user;

                // Send email based on config
                // Check if user need to activate it's account using email
                if ($this->ci->config->item('DX_email_activation'))
                {
                    // Create email
                    $from = $this->ci->config->item('DX_webmaster_email');
                    $subject = sprintf($this->ci->lang->line('auth_activate_subject'), $this->ci->config->item('DX_website_name'));

                    // Activation Link
                    $new_user['activate_url'] = site_url($this->ci->config->item('DX_activate_uri') . "{$new_user['username']}/{$new_user['activation_key']}");

                    // Trigger event and get email content
                    $this->ci->dx_auth_event->sending_activation_email($new_user, $message);

                    // Send email with activation link
                    $this->_email($email, $from, $subject, $message);
                }
                else
                {
                    // Check if need to email account details						
                    if ($this->ci->config->item('DX_email_account_details'))
                    {
                        // Create email
                        $from = $this->ci->config->item('DX_webmaster_email');
                        $subject = sprintf($this->ci->lang->line('auth_account_subject'), $this->ci->config->item('DX_website_name'));

                        // Trigger event and get email content
                        $this->ci->dx_auth_event->sending_account_email($new_user, $message);

                        // Send email with account details
                        $this->_email($email, $from, $subject, $message);
                    }
                }
            }

            return $result;
        }

        function forgot_password($login)
        {
            // Default return value
            $result = FALSE;

            if ($login)
            {
                // Load Model
                $this->ci->load->model('users_model', 'users');
                // Load Helper
                $this->ci->load->helper('url');

                // Get login and check if it's exist 
                if ($query = $this->ci->Users_model->get_login($login) AND $query->num_rows() == 1)
                {
                    // Get User data
                    $row = $query->row();

                    // Check if there is already new password created but waiting to be activated for this login
                    if (!$row->newpass_key)
                    {
                        // Appearantly there is no password created yet for this login, so we create new password
                        $data['password'] = $this->_gen_pass();

                        // Encode & Crypt password
                        $encode = crypt($this->_encode($data['password']));

                        // Create key
                        $data['key'] = md5(rand() . microtime());

                        // Create new password (but it haven't activated yet)
                        $this->ci->Users_model->newpass($row->id, $encode, $data['key']);

                        // Create reset password link to be included in email
                        $data['reset_password_uri'] = site_url($this->ci->config->item('DX_reset_password_uri') . "{$row->username}/{$data['key']}");

                        // Create email
                        $from = $this->ci->config->item('DX_webmaster_email');
                        $subject = $this->ci->lang->line('auth_forgot_password_subject');

                        // Trigger event and get email content
                        $this->ci->dx_auth_event->sending_forgot_password_email($data, $message);

                        // Send instruction email
                        $this->_email($row->email, $from, $subject, $message);

                        $result = TRUE;
                    }
                    else
                    {
                        // There is already new password waiting to be activated
                        $this->_auth_error = $this->ci->lang->line('auth_request_sent');
                    }
                }
                else
                {
                    $this->_auth_error = $this->ci->lang->line('auth_username_or_email_not_exist');
                }
            }

            return $result;
        }

        function reset_password($username, $key = '')
        {
            // Load Models
            $this->ci->load->model('users_model', 'users');
            $this->ci->load->model('dx_auth/user_autologin', 'user_autologin');

            // Default return value
            $result = FALSE;

            // Default user_id set to none
            $user_id = 0;

            // Get user id
            if ($query = $this->ci->Users_model->get_user_by_username($username) AND $query->num_rows() == 1)
            {
                $user_id = $query->row()->id;

                // Try to activate new password
                if (!empty($username) AND ! empty($key) AND $this->ci->Users_model->activate_newpass($user_id, $key) AND $this->ci->db->affected_rows() > 0)
                {
                    // Clear previously setup new password and keys
                    $this->ci->user_autologin->clear_keys($user_id);

                    $result = TRUE;
                }
            }
            return $result;
        }

        function activate($username, $key = '')
        {
            // Load Models
            $this->ci->load->model('users_model', 'users');
            $this->ci->load->model('dx_auth/user_temp', 'user_temp');

            // Default return value
            $result = FALSE;

            if ($this->ci->config->item('DX_email_activation'))
            {
                // Delete user whose account expired (not activated until expired time)
                $this->ci->user_temp->prune_temp();
            }

            // Activate user
            if ($query = $this->ci->user_temp->activate_user($username, $key) AND $query->num_rows() > 0)
            {
                // Get user 
                $row = $query->row_array();

                $del = $row['id'];

                // Unset any unwanted fields
                unset($row['id']); // We don't want to copy the id across
                unset($row['activation_key']);

                // Create user
                if ($this->ci->Users_model->create_user($row))
                {
                    // Trigger event
                    $this->ci->dx_auth_event->user_activated($this->ci->db->insert_id());

                    // Delete user from temp
                    $this->ci->user_temp->delete_user($del);

                    $result = TRUE;
                }
            }

            return $result;
        }

        function change_password($old_pass, $new_pass)
        {
            // Load Models
            $this->ci->load->model('users_model', 'users');

            // Default return value
            $result = FAlSE;

            // Search current logged in user in database
            if ($query = $this->ci->Users_model->get_user_by_id($this->ci->session->userdata('DX_user_id')) AND $query->num_rows() > 0)
            {
                // Get current logged in user
                $row = $query->row();

                $pass = $this->_encode($old_pass);

                // Check if old password correct
                if (crypt($pass, $row->password) === $row->password)
                {
                    // Crypt and encode new password
                    $new_pass = crypt($this->_encode($new_pass));

                    // Replace old password with new password
                    $this->ci->Users_model->change_password($this->ci->session->userdata('DX_user_id'), $new_pass);

                    // Trigger event
                    $this->ci->dx_auth_event->user_changed_password($this->ci->session->userdata('DX_user_id'), $new_pass);

                    $result = TRUE;
                }
                else
                {
                    $this->_auth_error = $this->ci->lang->line('auth_incorrect_old_password');
                }
            }

            return $result;
        }

        function cancel_account($password)
        {
            // Load Models
            $this->ci->load->model('users_model', 'users');

            // Default return value
            $result = FAlSE;

            // Search current logged in user in database
            if ($query = $this->ci->Users_model->get_user_by_id($this->ci->session->userdata('DX_user_id')) AND $query->num_rows() > 0)
            {
                // Get current logged in user
                $row = $query->row();

                $pass = $this->_encode($password);

                // Check if password correct
                if (crypt($pass, $row->password) === $row->password)
                {
                    // Trigger event
                    $this->ci->dx_auth_event->user_canceling_account($this->ci->session->userdata('DX_user_id'));

                    // Delete user
                    $result = $this->ci->Users_model->delete_user($this->ci->session->userdata('DX_user_id'));

                    // Force logout
                    $this->logout();
                }
                else
                {
                    $this->_auth_error = $this->ci->lang->line('auth_incorrect_password');
                }
            }

            return $result;
        }

        /* End of main function */

        /* Captcha related function */

        function captcha()
        {
            $this->ci->load->helper('url');

            //$this->ci->load->plugin('dx_captcha');

            $captcha_dir = trim($this->ci->config->item('DX_captcha_path'), './');

            $vals = array(
                'img_path' => './' . $captcha_dir . '/',
                'img_url' => base_url() . $captcha_dir . '/',
                'font_path' => $this->ci->config->item('DX_captcha_fonts_path'),
                'font_size' => $this->ci->config->item('DX_captcha_font_size'),
                'img_width' => $this->ci->config->item('DX_captcha_width'),
                'img_height' => $this->ci->config->item('DX_captcha_height'),
                'show_grid' => $this->ci->config->item('DX_captcha_grid'),
                'expiration' => $this->ci->config->item('DX_captcha_expire')
            );

            //$cap = create_captcha($vals);

            $store = array(
                'captcha_word' => $cap['word'],
                'captcha_time' => $cap['time']
            );

            // Plain, simple but effective
            $this->ci->session->set_flashdata($store);

            // Set our captcha
            $this->_captcha_image = $cap['image'];
        }

        function get_captcha_image()
        {
            return $this->_captcha_image;
        }

        // Check if captcha already expired
        // Use this in callback function in your form validation
        function is_captcha_expired()
        {
            // Captcha Expired
            list($usec, $sec) = explode(" ", microtime());
            $now = ((float) $usec + (float) $sec);

            // Check if captcha already expired
            return (($this->ci->session->flashdata('captcha_time') + $this->ci->config->item('DX_captcha_expire')) < $now);
        }

        // Check is captcha match with code
        // Use this in callback function in your form validation
        function is_captcha_match($code)
        {
            if ($this->ci->config->item('DX_captcha_case_sensitive'))
            {
                $result = ($code == $this->ci->session->flashdata('captcha_word'));
            }
            else
            {
                $result = strtolower($code) == strtolower($this->ci->session->flashdata('captcha_word'));
            }

            return $result;
        }

        function get_recaptcha_reload_link($text = 'Get another CAPTCHA')
        {
            return '<a href="javascript:Recaptcha.reload()">' . $text . '</a>';
        }

        function get_recaptcha_switch_image_audio_link($switch_image_text = 'Get an image CAPTCHA', $switch_audio_text = 'Get an audio CAPTCHA')
        {
            return '<div class="recaptcha_only_if_image"><a href="javascript:Recaptcha.switch_type(\'audio\')">' . $switch_audio_text . '</a></div>
			<div class="recaptcha_only_if_audio"><a href="javascript:Recaptcha.switch_type(\'image\')">' . $switch_image_text . '</a></div>';
        }

        function get_recaptcha_label($image_text = 'Enter the words above', $audio_text = 'Enter the numbers you hear')
        {
            return '<span class="recaptcha_only_if_image">' . $image_text . '</span>
			<span class="recaptcha_only_if_audio">' . $audio_text . '</span>';
        }

        // Get captcha image
        function get_recaptcha_image()
        {
            return '<div id="recaptcha_image"></div>';
        }

        function get_recaptcha_input()
        {
            return '<input type="text" id="recaptcha_response_field" name="recaptcha_response_field" />';
        }

        function get_recaptcha_html()
        {
            // Load reCAPTCHA helper function
            $this->ci->load->helper('recaptcha');

            // Add custom theme so we can get only image
            $options = "<script>
			var RecaptchaOptions = {
				 theme: 'custom',
				 custom_theme_widget: 'recaptcha_widget'
			};
			</script>";

            // Get reCAPTCHA javascript and non javascript HTML
            $html = recaptcha_get_html($this->ci->config->item('DX_recaptcha_public_key'));

            return $options . $html;
        }

        function is_recaptcha_match()
        {
            $this->ci->load->helper('recaptcha');

            $resp = recaptcha_check_answer($this->ci->config->item('DX_recaptcha_private_key'), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

            return $resp->is_valid;
        }

        /* End of Recaptcha function */
    }

?>