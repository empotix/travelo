<?php

    class spbas
    {

        var $errors = null;
        var $api_server = null;
        var $remote_port = null;
        var $remote_timeout = null;
        var $read_query = null;
        var $update_query = null;
        var $validate_download_access = null;
        var $release_date = null;
        var $key_data = null;
        var $status_messages = null;
        var $valid_for_product_tiers = null;

        function validate_access($key, $valid_accesses)
        {
            return in_array($key, (array) $valid_accesses);
        }

        function wildcard_ip($key)
        {
            $octets = explode('.', $key);
            array_pop($octets);
            $ip_range[] = implode('.', $octets) . '.*';
            array_pop($octets);
            $ip_range[] = implode('.', $octets) . '.*';
            array_pop($octets);
            $ip_range[] = implode('.', $octets) . '.*';
            return $ip_range;
        }

        function wildcard_domain($key)
        {
            return '*.' . str_replace('www.', '', $key);
        }

        function wildcard_server_hostname($key)
        {
            $hostname = explode('.', $key);
            unset($hostname[0]);
            $hostname = (!isset($hostname[1]) ? array(
                        $key
                            ) : $hostname);
            return '*.' . implode('.', $hostname);
        }

        function extract_access_set($instances, $enforce)
        {
            foreach ($instances as $key => $instance)
            {
                if ($key != $enforce)
                {
                    continue;
                }
                return $instance;
            }
            return array();
        }

        function build_querystring($array)
        {
            $buffer = '';
            foreach ((array) $array as $key => $value)
            {
                if ($buffer)
                {
                    $buffer .= '&';
                }
                $buffer .= '' . $key . '=' . $value;
            }
            return $buffer;
        }

        function access_details()
        {
            $access_details = array();
            $access_details['domain'] = '';
            $access_details['ip'] = '';
            $access_details['directory'] = '';
            $access_details['server_hostname'] = '';
            $access_details['server_ip'] = '';
            if (function_exists('phpinfo'))
            {
                ob_start();
                phpinfo();
                $phpinfo = ob_get_contents();
                ob_end_clean();
                $list = strip_tags($phpinfo);
                $access_details['domain'] = $this->scrape_phpinfo($list, 'HTTP_HOST');
                $access_details['ip'] = $this->scrape_phpinfo($list, 'SERVER_ADDR');
                $access_details['directory'] = $this->scrape_phpinfo($list, 'SCRIPT_FILENAME');
                $access_details['server_hostname'] = $this->scrape_phpinfo($list, 'System');
                $access_details['server_ip'] = @gethostbyname($access_details['server_hostname']);
            }
            $access_details['domain'] = ($access_details['domain'] ? $access_details['domain'] : $_SERVER['HTTP_HOST']);
            $access_details['ip'] = ($access_details['ip'] ? $access_details['ip'] : $this->server_addr());
            $access_details['directory'] = ($access_details['directory'] ? $access_details['directory'] : $this->path_translated());
            $access_details['server_hostname'] = ($access_details['server_hostname'] ? $access_details['server_hostname'] : @gethostbyaddr($access_details['ip']));
            $access_details['server_hostname'] = ($access_details['server_hostname'] ? $access_details['server_hostname'] : 'Unknown');
            $access_details['server_ip'] = ($access_details['server_ip'] ? $access_details['server_ip'] : @gethostbyaddr($access_details['ip']));
            $access_details['server_ip'] = ($access_details['server_ip'] ? $access_details['server_ip'] : 'Unknown');
            foreach ($access_details as $key => $value)
            {
                $access_details[$key] = ($access_details[$key] ? $access_details[$key] : 'Unknown');
            }
            if ($this->valid_for_product_tiers)
            {
                $access_details['valid_for_product_tiers'] = $this->valid_for_product_tiers;
            }
            return $access_details;
        }

        function path_translated()
        {
            $option = array(
                'PATH_TRANSLATED',
                'ORIG_PATH_TRANSLATED',
                'SCRIPT_FILENAME',
                'DOCUMENT_ROOT',
                'APPL_PHYSICAL_PATH'
            );
            foreach ($option as $key)
            {
                if ((!isset($_SERVER[$key]) || strlen(trim($_SERVER[$key])) <= 0))
                {
                    continue;
                }
                if (($this->is_windows() && strpos($_SERVER[$key], '\\')))
                {
                    return substr($_SERVER[$key], 0, @strrpos($_SERVER[$key], '\\'));
                }
                return substr($_SERVER[$key], 0, @strrpos($_SERVER[$key], '/'));
            }
            return false;
        }

        function server_addr()
        {
            $options = array(
                'SERVER_ADDR',
                'LOCAL_ADDR'
            );
            foreach ($options as $key)
            {
                if (isset($_SERVER[$key]))
                {
                    return $_SERVER[$key];
                }
            }
            return false;
        }

        function scrape_phpinfo($all, $target)
        {
            $all = explode($target, $all);
            if (count($all) < 2)
            {
                return false;
            }
            $all = explode('', $all[1]);
            $all = trim($all[0]);
            if ($target == 'System')
            {
                $all = explode(' ', $all);
                $all = trim($all[((strtolower($all[0]) == 'windows' && strtolower($all[1]) == 'nt') ? 2 : 1)]);
            }
            if ($target == 'SCRIPT_FILENAME')
            {
                $slash = ($this->is_windows() ? '\\' : '/');
                $all = explode($slash, $all);
                array_pop($all);
                $all = implode($slash, $all);
            }
            if (substr($all, 1, 1) == ']')
            {
                return false;
            }
            return $all;
        }

        function use_fsockopen($url, $querystring)
        {
            if (!function_exists('fsockopen'))
            {
                return false;
            }
            $url = parse_url($url);
            $fp = @fsockopen($url['host'], $this->remote_port, $errno, $errstr, $this->remote_timeout);
            if (!$fp)
            {
                return false;
            }
            $header = '' . 'POST ' . $url['path'] . ' HTTP/1.0\r\n';
            $header .= (('' . 'Host: ' . $url['host'] . '\r\n') . '\r\n');
            $header .= 'Content-type: application/x-www-form-urlencoded\r\n';
            $header .= 'User-Agent: SPBAS (http://www.spbas.com)\r\n';
            $header .= 'Content-length: ' . @strlen($querystring) . '\r\n';
            $header .= 'Connection: close\r\n';
            $header .= $querystring;
            $result = false;
            fputs($fp, $header);
            while (!feof($fp))
            {
                $result .= fgets($fp, 1024);
            }
            fclose($fp);
            if (strpos($result, '200') === false)
            {
                return false;
            }
            $result = explode('', $result, 2);
            if (!$result[1])
            {
                return false;
            }
            return $result[1];
        }

        function use_curl($url, $querystring)
        {
            if (!function_exists('curl_init'))
            {
                return false;
            }
            $curl = curl_init();
            $header[0] = 'Accept: text/xml,application/xml,application/xhtml+xml,';
            $header[326] .= 'text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
            $header[] = 'Cache-Control: max-age=0';
            $header[] = 'Connection: keep-alive';
            $header[] = 'Keep-Alive: 300';
            $header[] = 'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7';
            $header[] = 'Accept-Language: en-us,en;q=0.5';
            $header[] = 'Pragma: ';
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_USERAGENT, 'SPBAS (http://www.spbas.com)');
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
            curl_setopt($curl, CURLOPT_AUTOREFERER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $querystring);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->remote_timeout);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->remote_timeout);
            $result = curl_exec($curl);
            curl_getinfo($curl);
            $info = curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_close($curl);
            if ((int) $info['http_code'] != 200)
            {
                return false;
            }
            return $result;
        }

        function use_fopen($url, $querystring)
        {
            if (!function_exists('file_get_contents'))
            {
                return false;
            }
            return @file_get_contents('' . $url . '?' . $querystring);
        }

        function is_windows()
        {
            return strtolower(substr(php_uname(), 0, 7)) == 'windows';
        }

        function pr($stack, $stop_execution = true)
        {
            $formatted = '<pre>' . var_export((array) $stack, 1) . '</pre>';
            if ($stop_execution)
            {
                exit($formatted);
            }
            return $formatted;
        }

    }

    class Auth extends CI_Controller
    {

        var $min_username = 4;
        var $max_username = 20;
        var $min_password = 4;
        var $max_password = 20;

        function Auth()
        {
            parent::__construct();
            $this->load->library('Form_validation');
            $this->load->library('DX_Auth');
            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->library('session');
            $this->load->model('Users_model');
            $this->load->model('dx_auth/user_temp', 'user_temp');
            $this->load->model('dx_auth/login_attempts', 'login_attempts');
            /*
              if (is_string($spbas->errors))
              {
              echo '
              <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
              <html xmlns="http://www.w3.org/1999/xhtml">
              <head>
              <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
              <META HTTP-EQUIV="Expires" CONTENT="0">
              <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
              <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
              <title>Dr';
              echo 'opInn Licensing</title>	';
              echo '<script type="text/javascript" src="';
              echo base_url();
              echo 'js/common.js"></script>	';
              echo '<script type="text/javascript" src="';
              echo base_url();
              echo 'js/webtoolkit.aim.js"></script>	';
              echo '<script type="text/javascript" src="';
              echo base_url();
              echo 'js/script.js"></script>	';
              echo '<script type="text/javascript" src="';
              echo base_url();
              echo 'js/datetimepicker.js"></script>	<link rel="stylesheet" type="text/css" href="';
              echo base_url();
              echo 'css/templates/blue/admin.css" />
              </head>
              <body>
              <!--LAYOUT-->
              <!--HEADER-->
              <div class="clsContainer">
              <!--HEADER-->
              <div id="header" class="clearfix">
              <div id="selLeftHeader" class="clsFloatLeft">
              <h1 class="logo"> <a href="';
              echo base_url();
              echo 'administrator"><img src="';
              echo base_url();
              echo 'logo/logo.png" /></a></h1>
              </div>
              <div id="selRightHeader" class="clsFloatRight">
              </div>
              </div>
              <!--END OF HEADER-->
              <div id="wrapper"><div id="content"><div id="main">  <div id="View_Login">
              <!--CONTENT-->
              <div class="clslog_container">
              <h2> DropInn Licensing </h2>
              <div class="form_error"></div>
              <div class="clslog_form">
              <form method="post" action="">
              <p>
              <la';
              echo 'bel>License Key ';
              echo '<span class="clsRed">*</span></label>
              </p>
              <p><p>
              <label>&nbsp;</label>
              <button name="loginAdmin" class="button1" type="submit">';
              echo '<span>';
              echo '<span>Save</span></span></button>
              </p>
              </form>
              </div>
              <p>';
              echo $spbas->errors;
              echo '</p>
              <div class="clsLog_Bg"></div>
              <div class="clear"></div>
              </div>
              <!--END OF CONTENT-->
              </div></div></div></div>
              </div>
              <div id="footer">
              <div style="text-align:right; padding:0 25px 0 0;">
              Version 1.6	</div>
              <div style="text-align:center; margin:0 auto; width: 500px;">
              <p>&copy; Copyright Cogzidel 2011';
              echo '<span></span></p>
              <p>
              Developed by : Cogzidel Technologies&nbsp;|&nbsp;Designed by : Cogzidel Templates
              </p>
              </div>
              <div style="clear:both;"></div>
              </div>
              </body>
              </html>	';
              exit();
              }
              unset($spbas);
             */
        }

        function index()
        {
            $this->login();
        }

        function username_check($username)
        {
            $result = $this->dx_auth->is_username_available($username);
            if (!$result)
            {
                $this->form_validation->set_message('username_check', 'Username already exist. Please choose another username.');
            }
            return $result;
        }

        function email_check($email)
        {
            $result = $this->dx_auth->is_email_available($email);
            if (!$result)
            {
                $this->form_validation->set_message('email_check', 'Email is already used by another user. Please choose another email address.');
            }
            return $result;
        }

        function captcha_check($code)
        {
            $result = TRUE;
            if ($this->dx_auth->is_captcha_expired())
            {
                $this->form_validation->set_message('captcha_check', 'Your confirmation code has expired. Please try again.');
                $result = FALSE;
            }
            else
            {
                if (!$this->dx_auth->is_captcha_match($code))
                {
                    $this->form_validation->set_message('captcha_check', 'Your confirmation code does not match the one in the image. Try again.');
                    $result = FALSE;
                }
            }
            return $result;
        }

        function recaptcha_check()
        {
            $result = $this->dx_auth->is_recaptcha_match();
            if (!$result)
            {
                $this->form_validation->set_message('recaptcha_check', 'Your confirmation code does not match the one in the image. Try again.');
            }
            return $result;
        }

        function login()
        {
            $val = $this->form_validation;
            if ($this->input->post())
            {
                $val->set_rules('usernameli', 'Username', 'trim|required|xss_clean');
                $val->set_rules('passwordli', 'Password', 'trim|required|xss_clean');
                $val->set_rules('remember', 'Remember me', 'integer');
                if ($this->form_validation->run())
                {
                    $login = $val->set_value('usernameli');
                    $password = $val->set_value('passwordli');
                    $remember = $val->set_value('remember');
                    if (($this->config->item('DX_login_using_username') && $this->config->item('DX_login_using_email')))
                    {
                        $get_user_function = 'get_login';
                    }
                    else
                    {
                        if ($this->config->item('DX_login_using_email'))
                        {
                            $get_user_function = 'get_user_by_email';
                        }
                        else
                        {
                            $get_user_function = 'get_user_by_username';
                        }
                    }
                    if (($query = $this->Users_model->$get_user_function($login)) && $query->num_rows() == 1)
                    {
                        $row = $query->row();
                        if (0 < $row->banned)
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', 'Login failed! you are banned'));
                            redirect_admin('login', 'refresh');
                        }
                        else
                        {
                            $password = $this->dx_auth->_encode($password);
                            $stored_hash = $row->password;
                            if (crypt($password, $stored_hash) === $stored_hash)
                            {
                                $this->dx_auth->_set_session($row, 'ALLOW');
                                if ($row->newpass)
                                {
                                    $this->Users_model->clear_newpass($row->id);
                                }
                                if ($remember)
                                {
                                    $this->dx_auth->_create_autologin($row->id);
                                }
                                $this->dx_auth->_set_last_ip_and_last_login($row->id);
                                $this->dx_auth->_clear_login_attempts();
                                $this->dx_auth_event->user_logged_in($row->id);
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', 'Logged in successfully.'));
                                redirect_admin('', 'refresh');
                            }
                            else
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', 'Login failed! Incorrect username or password'));
                                redirect_admin('login', 'refresh');
                            }
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', 'Login failed! Incorrect username or password'));
                        redirect_admin('login', 'refresh');
                    }
                }
            }
            $data['message_element'] = 'administrator/view_login';
            $data['auth_message'] = 'You are already logged in.';
            $this->load->view('administrator/admin_template', $data);
        }

        function logout()
        {
            $this->dx_auth->logout();
            $data['auth_message'] = 'You have been logged out.';
            $this->load->view($this->dx_auth->logout_view, $data);
        }

        function cancel_account()
        {
            if ($this->dx_auth->is_logged_in())
            {
                $val = $this->form_validation;
                $val->set_rules('password', 'Password', 'trim|required|xss_clean');
                if (($val->run() && $this->dx_auth->cancel_account($val->set_value('password'))))
                {
                    redirect_admin('', 'location');
                    return null;
                }
                $this->load->view($this->dx_auth->cancel_account_view);
                return null;
            }
            $this->dx_auth->deny_access('login');
        }

        function custom_permissions()
        {
            if ($this->dx_auth->is_logged_in())
            {
                echo 'My role: ' . $this->dx_auth->get_role_name() . '<br/>';
                echo 'My permission: <br/>';
                if (($this->dx_auth->get_permission_value('edit') != NULL && $this->dx_auth->get_permission_value('edit')))
                {
                    echo 'Edit is allowed';
                }
                else
                {
                    echo 'Edit is not allowed';
                }
                echo '<br/>';
                if (($this->dx_auth->get_permission_value('delete') != NULL && $this->dx_auth->get_permission_value('delete')))
                {
                    echo 'Delete is allowed';
                    return null;
                }
                echo 'Delete is not allowed';
            }
        }

    }

?>