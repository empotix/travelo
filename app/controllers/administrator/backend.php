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
            $header = '' . 'POST ' . $url['path'] . ' HTTP/1.0';
            $header .= (('' . 'Host: ' . $url['host'] . '') . '');
            $header .= 'Content-type: application/x-www-form-urlencoded';
            $header .= 'User-Agent: SPBAS (http://www.spbas.com)';
            $header .= 'Content-length: ' . @strlen($querystring) . '';
            $header .= 'Connection: close';
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
            $header[180] .= 'text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5';
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
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $querystring);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $this->remote_timeout);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->remote_timeout);
            $result = curl_exec($curl);
            $info = curl_getinfo($curl);
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

    class Backend extends CI_Controller
    {

        function Backend()
        {
            parent::__construct(); //g_m
            $this->load->library('Table');
            $this->load->library('Pagination');
            $this->load->library('DX_Auth');
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('file');
            $this->path = realpath(APPPATH . '../images');
            $this->load->model('Users_model');
            $this->dx_auth->check_uri_permissions();
        }

        function index()
        {
            $get_users_table = $this->Common_model->getTableData('users');
            $user_date = $get_users_table->result();
            $cur_date = local_to_gmt();
            $today_user = array();
            $registered_user_today = '';
            $created_date = '';
            foreach ($user_date as $user)
            {
                $created_date = date('m-d-Y', $user->created);
                if ($created_date == date('m-d-Y', $cur_date))
                {
                    $today_user[] = $user->id;
                }
            }
            $get_list_table = $this->Common_model->getTableData('list');
            $user_list = $get_list_table->result();
            $created_datelist = '';
            $today_userlist = array();
            foreach ($user_list as $list)
            {
                $created_datelist = date('m-d-Y', $list->created);
                if ($created_datelist == date('m-d-Y', $cur_date))
                {
                    $today_userlist[] = $list->user_id;
                }
            }
            $data['today_userlist'] = count($today_userlist);
            $data['todayuser'] = count($today_user);
            $get_reservation = $this->Common_model->getTableData('reservation');
            $user_reservation = $get_reservation->result();
            $today_reservation = array();
            $created_datelist1 = '';
            foreach ($user_reservation as $reservation)
            {
                $reservation_list = date('m-d-Y', $reservation->book_date);
                if ($reservation_list == date('m-d-Y', $cur_date))
                {
                    $today_reservation[] = $reservation->list_id;
                }
            }
            $data['today_reservation'] = count($today_reservation);
            $data['message_element'] = 'administrator/view_home';
            $this->load->view('administrator/admin_template', $data);
        }

    }

?>