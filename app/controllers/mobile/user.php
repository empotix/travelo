<?php

    /**
     * DROPinn User Controller Class
     *
     * helps to achieve common tasks related to the site for mobile app like android and iphone.
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	User
     * @author		Cogzidel Product Team
     * @version		Version 1.0
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class User extends CI_Controller
    {

        public function User()
        {
            parent::__construct();

            $this->load->helper('url');

            $this->load->library('DX_Auth');

            $this->load->model('Users_model');
            $this->load->model('Gallery');
            $this->load->model('Trips_model');
            $this->load->model('Message_model');
            $this->_table = 'users';
        }

        public function index()
        {
            
        }

        public function login()
        {
            $username = $this->input->get('username');
            $password = $this->input->get('password');

            if (!empty($username) AND ! empty($password))
            {
                if ($query = $this->get_login($username) AND $query->num_rows() == 1)
                {
                    // Get user record
                    $row = $query->row();

                    // Check if user is banned or not
                    if ($row->banned > 0)
                    {
                        echo '[{"status":"Sorry! The user was banned."}]';
                        exit;
                    }
                    else
                    {
                        $password = $this->_encode($password);
                        $stored_hash = $row->password;

                        // Is password matched with hash in database ?
                        if (crypt($password, $stored_hash) === $stored_hash)
                        {
                            $profile_pic = $this->Gallery->profilepic($row->id, 2);
                            echo '[{"status":"Successfully logged in.","user_id":"' . $row->id . '","username":"' . $username . '","profile_pic":"' . $profile_pic . '"}]';
                            exit;
                        }
                        else
                        {
                            echo '[{"status":"Sorry! The password is invalid."}]';
                            exit;
                        }
                    }
                }
                else
                {
                    echo '[{"status":"Sorry! The username is invalid."}]';
                    exit;
                }
            }
        }

        protected function get_login($login)
        {
            $this->db->where('username', $login);
            $this->db->or_where('email', $login);
            return $this->db->get($this->_table);
        }

        protected function _encode($password)
        {
            $majorsalt = $this->config->item('DX_salt');

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

            // encrypts the string combinations of every single encrypted letter
            // and finally returns the encrypted password
            return md5($majorsalt);
        }

        public function signup()
        {
            $username = $this->input->get('username');
            $email_id = $this->input->get('email_id');
            $password = $this->input->get('password');
            $repassword = $this->input->get('repassword');

            if (!$this->dx_auth->is_username_available($username))
            {
                echo '[{"status":"Sorry! Username is not available."}]';
                exit;
            }

            if (!$this->dx_auth->is_email_available($email_id))
            {
                echo '[{"status":"Sorry! This email has already been registered."}]';
                exit;
            }

            if (strlen($password) < 4)
            {
                echo '[{"status":"Sorry! Password has too less characters."}]';
                exit;
            }

            if ($password != $repassword)
            {
                echo '[{"status":"Sorry! Passwords do not match."}]';
                exit;
            }

            $data = $this->dx_auth->register($username, $password, $email_id);

            $add['id'] = $data['user_id'];
            $add['email'] = $email_id;
            $this->db->insert('profiles', $add);

            $profile_pic = $this->Gallery->profilepic($data['user_id'], 2);
            echo '[{"status":"Welcome to DropInn.","user_id":"' . $data['user_id'] . '","username":"' . $data['username'] . '","profile_pic":"' . $profile_pic . '"}]';
            exit;
        }

        public function fb_signup()
        {
            $username = $this->input->get('username');
            $email_id = $this->input->get('email_id');
            $fb_id = $this->input->get('fb_id');
            $fname = $this->input->get('fname');
            $lname = $this->input->get('lname');
            $live = $this->input->get('live');
            $work = $this->input->get('work');
            $phnum = $this->input->get('phnum');
            $describe = $this->input->get('describe');
            $src = $this->input->get('src');
            $user_agent = $this->input->get('user_agent');
            $last_ip = $this->input->get('last_ip');

            if (!$this->dx_auth->is_username_available($username))
            {
                //echo '[{"status":"Sorry! Username is not available."}]';exit;
                $this->load->model('users_model');
                $users = $this->users_model->get_user_by_username($username)->row_array();
                echo "[{\"status\":\"Sorry! Username is not available.\",\"user_id\":\"" . $users['id'] . "\"}]";
                exit;
            }

            if (!$this->dx_auth->is_email_available($email_id))
            {
                //echo '[{"status":"Sorry! This email has already been registered."}]';exit;
                $this->load->model('users_model');
                $users = $this->users_model->getUserIdByEmail($email_id);
                echo "[{\"status\":\"Sorry! This email has already been registered\",\"user_id\":\"" . $users['id'] . "\"}]";
                exit;
            }
            $id_query = $this->db->select('id')->limit(1)->order_by('id', 'desc')->from('users')->get();

            if ($id_query->num_rows() != 0)
            {
                foreach ($id_query->result() as $row)
                {
                    $id = $row->id + 1;
                }
            }
            $add['Fname'] = $fname;
            $add['Lname'] = $lname;
            $add['id'] = $id;
            $add['email'] = $email_id;
            $add['live'] = $live;
            $add['work'] = $work;
            $add['phnum'] = $phnum;
            $add['describe'] = $describe;
            $this->Common_model->insertData('profiles', $add);

            $img['email'] = $email_id;
            $img['src'] = $src;
            $this->Common_model->insertData('profile_picture', $img);

            $notification = array();
            $notification['user_id'] = $id;
            $notification['new_review '] = 1;
            $notification['leave_review'] = 1;
            $this->Common_model->insertData('user_notification', $notification);

            $last_login = date('Y-m-d h:i:s', time());
            $auto['key_id'] = md5($last_login);
            $auto['user_id'] = $id;
            $auto['user_agent'] = $user_agent;
            $auto['last_ip'] = $last_ip;
            $auto['last_login'] = $last_login;
            $this->Common_model->insertData('user_autologin', $auto);

            $data = $this->dx_auth->register($username, $fb_id, $email_id, $fb_id);
            $update_data['photo_status'] = '2';
            $this->db->where('username', $username)->update('users', $update_data);

            echo "[{\"status\":\"Successfully registered\",\"user_id\":" . $id . "}]";
        }

        function user_data()
        {
            $user_id = $this->input->get('user_id');

            $condition = array('id' => $user_id);

            $email = $this->db->get_where('users', array('id' => $user_id))->row()->email;
            $username = $this->db->get_where('users', array('id' => $user_id))->row()->username;
            $photo_status = $this->db->get_where('users', array('id' => $user_id))->row()->photo_status;

            $condition1 = array('email' => $email);
            $result_picture = $this->Common_model->getTableData('profile_picture', $condition1);
            $src = base_url() . 'images/no_avatar.jpg';
            foreach ($result_picture->result() as $row)
            {
                $src = $row->src;
            }

            if ($photo_status == 1)
            {
                $src = $this->Gallery->profilepic($user_id, 2);
            }

            $result_user = $this->Common_model->getTableData('profiles', $condition);
            if ($result_user->num_rows() != 0)
            {
                foreach ($result_user->result() as $row)
                {
                    echo "[{ \"id\":" . $row->id . ",\"Fname\":\"" . $row->Fname . "\",\"Lname\":\"" . $row->Lname . "\",\"live\":\"" . $row->live . "\",
		\"work\":\"" . $row->work . "\",\"phnum\":\"" . $row->phnum . "\",\"describe\":\"" . $row->describe . "\",
		\"email\":\"" . $row->email . "\",\"username\":\"" . $username . "\",\"image_src\":\"" . $src . "\"}]";
                }
            }
            else
            {
                echo "[{\"status\":\"Incorrect User id\"}]";
            }
        }

        function edit_user_data()
        {
            $user_id = $this->input->get('user_id');

            $Fname = $this->input->get('Fname');
            $Lname = $this->input->get('Lname');
            $phnum = $this->input->get('phnum');
            $live = $this->input->get('live');
            $work = $this->input->get('work');
            $describe = $this->input->get('desc');
            $data2['email'] = $this->input->get('email');
            $data2['timezone'] = $this->input->get('timezones');
            if (!$user_id)
            {
                echo "[{\"status\":\"Please enter user id\"}]";
            }
            else
            {

                $data = array(
                    'Fname' => $this->input->get('Fname'),
                    'Lname' => $this->input->get('Lname'),
                    'phnum' => $this->input->get('phnum'),
                    'live' => $this->input->get('live'),
                    'work' => $this->input->get('work'),
                    'describe' => $this->input->get('desc')
                );

                $param = $user_id;
                $data2['photo_status'] = 1;
                $this->db->where('id', $param);
                $this->db->update('users', $data2);
                $rows = $this->Common_model->getTableData('profiles', array('id' => $param))->num_rows();

                if ($Fname != '' && $Lname != '' && $phnum != '' && $live != '' && $work != '' && $describe != '' && $data2['email'] != '' && $data2['timezone'] != '')
                {
                    if ($rows == 0)
                    {
                        $data['id'] = $param;
                        if ($Fname != '' && $Lname != '' && $phnum != '' && $live != '' && $work != '' && $describe != '')
                        {
                            $this->Common_model->insertData('profiles', $data);
                        }
                    }
                    else
                    {
                        $this->db->where('id', $param);
                        if ($Fname != '' && $Lname != '' && $phnum != '' && $live != '' && $work != '' && $describe != '')
                        {
                            $this->db->update('profiles', $data);
                        }
                    }
                    if ($data2['email'] != '' && $data2['timezone'] != '')
                    {
                        $this->db->where('id', $param);
                        $this->db->update('users', $data2);
                        echo "[{\"status\":\"Successfully updated\"}]";
                    }
                    else
                    {

                        echo "[{\"status\":\"Failed\"}]";
                    }
                }
                else
                {
                    echo "[{\"status\":\"Failed\"}]";
                }
            }
        }

        /* public function image_upload() 
          {
          $status = "";
          $msg = "";
          $file_element_name = 'uploadedfile';
          $user_id = $this->input->get('user_id');
          if ($status != "error")
          {

          //	$config['upload_path'] = '/var/ftp/virtual_users/tastenote/tastenote.com/files/gastronote/';
          $config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']).'/images/'.$user_id.'/';
          //Set the upload path
          //$config['upload_path'] = '/opt/lampp/htdocs/vignesh/dropinn-1.6.6/images/'.$user_id.'/';
          $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
          $config['encrypt_name']	= TRUE; // Change image name
          $this->load->library('upload', $config);
          $this->upload->initialize($config);
          if(!$this->upload->do_upload($file_element_name)){
          $status = 'error';
          $msg = $this->upload->display_errors('','');
          $data = "";
          }
          else {
          $data = $this->upload->data(); // Get the uploaded file information

          $this->load->library('image_lib');
          $config['image_library'] = 'gd2';
          $config['source_image']	= $data['full_path'];
          $config['new_image']    = 'images/'.$user_id.'/'.$data['raw_name'].$data['file_ext'];
          $config['create_thumb'] = TRUE;
          $config['maintain_ratio'] = TRUE;
          //$config['width'] = '260';
          $config['width'] = '205';
          $config['height'] = '1';
          $config['master_dim'] = 'width';

          $this->image_lib->initialize($config);
          if(!$this->image_lib->resize())
          echo $this->image_lib->display_errors();

          $config['new_image']    = 'images/'.$user_id.'/'.$data['raw_name'].'_detail'.$data['file_ext'];
          $config['create_thumb'] = TRUE;
          $config['maintain_ratio'] = TRUE;
          $config['width'] = '600';
          $config['height'] = '1';
          $config['master_dim'] = 'width';

          $this->image_lib->initialize($config);
          if(!$this->image_lib->resize())
          echo $this->image_lib->display_errors();



          $this->load->library('SimpleImage');
          $img = new SimpleImage();
          $img->load($data['full_path'])->resize(47, 48)->save('images/'.$user_id.'/'.$data['raw_name'].'_small_thumb.png');

          $map_path = getcwd();
          $image_map = base_url().'images/'.$user_id.'/'.$data['raw_name'].'_small_thumb.png';
          $layout_image = base_url().'/images/map_layout.png';
          $merged = explode('_small_thumb.',$data['raw_name'].'_small_thumb.png');
          $image_merged = $map_path.'images/'.$user_id.'/'.$merged[0].'_map.png';

          merge( $layout_image , $image_map , $image_merged);



          if($data) {
          $status = "success";
          $msg = "File successfully uploaded";
          }
          else {
          unlink($data['full_path']); // delete the file if not insert the details
          $status = "error";
          $msg = "Something went wrong when saving the file, please try again.";
          }
          echo $data['raw_name'].$data['file_ext'];
          }

          @unlink($_FILES[$file_element_name]);
          }
          // Response as json
          //echo json_encode(array('status' => $status, 'msg' => $msg, 'upload_data' => $data));


          } */

        public function image_upload()
        {
            $id = $this->input->get('user_id');
            $this->path = realpath(APPPATH . '../images/users');

            //$filename = dirname($_SERVER['SCRIPT_FILENAME']).'/images/users'.$id;
            $status = "";
            $msg = "";
            $file_element_name = 'uploadedfile';

            if ($status != "error")
            {
                if (!is_dir($this->path . '/' . $id))
                {
                    mkdir($this->path . '/' . $id, 0777, true);
                }
                //if(!file_exists($filename)) 
                //{
                //	mkdir(dirname($_SERVER['SCRIPT_FILENAME']).'/images/users'.$id, 0777, true);
                //}

                $config['upload_path'] = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/users/' . $id;  //Set the upload path

                $config['allowed_types'] = 'gif|jpg|png|jpeg'; // Set image type
                //$config['encrypt_name']	= TRUE; // Change image name
                $config['file_name'] = 'userpic_profile.jpg';
                $config['overwrite'] = TRUE;
                $this->db->query('update users set photo_status = 1 where id = ' . $id);
                $this->load->library('upload', $config);

                $this->upload->initialize($config);
                if (!$this->upload->do_upload($file_element_name))
                {

                    $status = 'error';
                    $msg = $this->upload->display_errors('', '');
                    $data = "";
                }
                else
                {


                    $file_data = $this->upload->data();
                    //base_url().'images/users/'.$user_id.'/userpic.jpg'
                    //$thumb1 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_thumb.jpg';
                    //GenerateThumbFile($target_path,$thumb1,107,78);
                    //$thumb2 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_profile.jpg';
                    //GenerateThumbFile($target_path,$thumb2,209,209);
                    //$thumb3 = realpath(APPPATH . '../images/users').'/'.$id.'/userpic_home.jpg';
                    //GenerateThumbFile($target_path,$thumb3,40,40);
                    //$this->db->query('update users set photo_status = 1 where id = '.$id);
                    $data = $this->upload->data(); // Get the uploaded file information
                    echo base_url() . 'images/users/' . $id . '/userpic.jpg';
                }

                @unlink($_FILES[$file_element_name]);
            }
        }

        public function uploadImage()
        {
            if ($this->input->get("user_id") && $this->input->get("image_url"))
            {
                extract($this->input->get());
                $updateData['profile_image'] = $image_url;
                $edit = $this->mobile_model->updateProfile(array('id' => $user_id), $updateData);
                $query = json_encode($edit);
                stripslashes($query);
                echo '[{"status":"success"}]';
            }
            else
            {
                echo "[" . json_encode(array('status' => "failed")) . "]";
            }
        }

        function change_password()
        {
            $user_id = $this->input->get('user_id');
            $this->session->set_userdata('DX_user_id', $user_id);
            $result = $this->db->where('id', $user_id)->from('users')->get();
            $opwd = $this->input->get('old_password');
            //echo $opwd;
            $old_pass = $this->_check_password($this->input->get('old_password'));
            if ($result->num_rows() != 0)
            {
                if ($old_pass == 1)
                {
                    $new_pass = crypt($this->dx_auth->_encode($this->input->get('new_password')));
                    //echo $new_pass;
                    $this->db->where('id', $user_id)->update('users', array('password' => $new_pass));
                    //if($this->dx_auth->change_password($this->input->get('old_password'), $this->input->get('new_password')))
                    //{
                    echo "[{\"status\":\"Successfully Changed\"}]";
                    //}
                    //else {
                    //	echo "[{\"status\":\"Please enter correct old password\"}]";
                    //}
                }
                else
                {
                    echo "[{\"status\":\"Please enter correct old password\"}]";
                }
            }
            else
            {
                echo "[{\"status\":\"Please use valid user id\"}]";
            }
        }

        function _check_password($old_pass)
        {
            $password = $old_pass;

            $user_id = $this->session->userdata('DX_user_id');

            $stored_hash = $this->db->where('id', $user_id)->get('users')->row()->password;

            //echo $password;
            $password = $this->dx_auth->_encode($password);
            // echo $password;
            // echo crypt($password, $stored_hash);
            // echo $stored_hash;
            // exit;
            if (crypt($password, $stored_hash) === $stored_hash)
            {

                return true;
            }
            else
            {

                //$this->form_validation->set_message('_check_password', 'Your Old Password Is Wrong');
                return false;
            }//If end
        }

        function view_listing()
        {
            $user_id = $this->input->get('user_id');
            $this->session->set_userdata('DX_user_id', $user_id);
            $result = $this->db->where('id', $user_id)->from('users')->get();
            if ($result->num_rows() != 0)
            {
                $lists = $this->db->where('user_id', $user_id)->from('list')->get();
                if ($lists->num_rows() != 0)
                {
                    echo "[";
                    foreach ($lists->result() as $row)
                    {
                        $search = array('\'', '"', '(', ')', '!', '{', '[', '}', ']', '<', '>');
                        $replace = array('&sq', '&dq', '&obr', '&cbr', '&ex', '&obs', '&oabr', '&cbs', '&cabr');
                        $desc_replace = str_replace($search, $replace, $row->desc);
                        $desc_tags = stripslashes($desc_replace);

                        $url = getListImage($row->id);
                        $json[] = "{ \"room_id\":" . $row->id . ",\"title\":\"" . $row->title . "\",\"desc\":\"" . $desc_tags . "\",\"address\":\"" . $row->address . "\",
		\"country\":\"" . $row->country . "\",\"price\":\"" . $row->price . "\",\"image_src\":\"" . $url . "\",\"currency\":\"" . $row->currency . "\"},";
                    }
                    $count = count($json);
                    $end = $count - 1;
                    $slice = array_slice($json, 0, $end);
                    foreach ($slice as $row)
                    {
                        echo $row;
                    }
                    $comma = end($json);
                    $json = substr_replace($comma, "", -1);
                    echo $json;
                    echo "]";
                }
                else
                {
                    echo "[{\"status\":\"No List\"}]";
                }
            }
            else
            {
                echo "[{\"status\":\"Please logged in\"}]";
            }
        }

        function listing_details()
        {
            $room_id = $this->input->get('room_id');
            $user_id = $this->input->get('user_id');
            $user_check = $this->db->where('user_id', $user_id)->where('id', $room_id)->from('list')->get();
            $user_valid = $this->db->where('id', $user_id)->from('users')->get();
            if ($user_valid->num_rows() != 0)
            {
                if ($user_check->num_rows() != 0)
                {
                    $conditions = array("id" => $room_id, "list.is_enable" => 1, "list.status" => 1);
                    $result = $this->Common_model->getTableData('list', $conditions);

                    if ($result->num_rows() != 0)
                    {
                        foreach ($result->result() as $row)
                        {
                            $id = $row->id;
                            $user_id = $row->user_id;
                            $address = $row->address;
                            $country = '';
                            $city = '';
                            $state = '';
                            $cancellation_policy = $row->cancellation_policy;
                            $room_type = $row->room_type;
                            $bedrooms = $row->bedrooms;
                            $beds = $row->beds;
                            $bed_type = $row->bed_type;
                            if ($row->bathrooms == NULL)
                            {
                                $bathrooms = 0;
                            }
                            else
                            {
                                $bathrooms = $row->bathrooms;
                            }
                            $title = $row->title;
                            $desc = $row->desc;
                            $capacity = $row->capacity;
                            $price = $row->price;
                            $email = $row->email;
                            $phone = $row->phone;
                            $review = $row->review;
                            $lat = $row->lat;
                            $long = $row->long;
                            $property_id = $row->property_id;
                            $street_view = $row->street_view;
                            $sublet_price = $row->sublet_price;
                            $sublet_status = $row->sublet_status;
                            $sublet_startdate = $row->sublet_startdate;
                            $sublet_enddate = $row->sublet_enddate;
                            $currency = $row->currency;
                            $manual = $row->manual;
                            $page_viewed = $row->page_viewed;
                            $neighbor = $row->neighbor;
                            $directions = $row->directions;

                            $price_query = $this->db->where('id', $room_id)->from('price')->get();

                            if ($price_query->num_rows() != 0)
                            {
                                foreach ($price_query->result() as $row)
                                {
                                    $price = $price;
                                    $cleaning_fee = $row->cleaning;
                                    $extra_guest_fee = $row->addguests . '/guest after' . $row->guests;
                                    $additional_guests_price = $row->addguests;
                                    $additional_guests_after = $row->guests;
                                    $Wprice = $row->week;
                                    $Mprice = $row->month;
                                }
                            }
                            else
                            {
                                $Wprice = '';
                                $Mprice = '';
                                $cleaning_fee = '';
                                $price = '';
                                $extra_guest_fee = '';
                            }


                            $conditions = array("id" => $room_id, "list.is_enable" => 1, "list.status" => 1);
                            $result = $this->Common_model->getTableData('list', $conditions);

                            $today_month = date("F");
                            $today_date = date("j");
                            $today_year = date("Y");
                            $conditions_statistics = array("list_id" => $room_id, "date" => trim($today_date), "month" => trim($today_month), "year" => trim($today_year));
                            $result_statistics = $this->Common_model->add_page_statistics($room_id, $conditions_statistics);

                            $list = $list = $result->row();
                            $title = $list->title;
                            $page_viewed = $list->page_viewed;

                            $page_viewed = $this->Trips_model->update_pageViewed($room_id, $page_viewed);


                            $id = $room_id;
                            $checkin = $this->session->userdata('Vcheckin');
                            $checkout = $this->session->userdata('Vcheckout');
                            $guests = $this->session->userdata('Vnumber_of_guests');

                            $ckin = explode('/', $checkin);
                            $ckout = explode('/', $checkout);

                            //check admin premium condition and apply so for
                            $query = $this->Common_model->getTableData('paymode', array('id' => 2));
                            $row = $query->row();


                            if (($ckin[0] == "mm" && $ckout[0] == "mm") or ( $ckin[0] == "" && $ckout[0] == ""))
                            {

                                if ($Wprice == 0)
                                {
                                    $data['Wprice'] = $price * 7;
                                }
                                else
                                {
                                    $data['Wprice'] = $Wprice;
                                }
                                if ($Mprice == 0)
                                {
                                    $data['Mprice'] = $price * 30;
                                }
                                else
                                {
                                    $data['Mprice'] = $Mprice;
                                }

                                if ($row->is_premium == 1)
                                {
                                    if ($row->is_fixed == 1)
                                    {
                                        $fix = $row->fixed_amount;
                                        $amt = $price + $fix;
                                        $commission = $fix;
                                        $Fprice = $amt;
                                    }
                                    else
                                    {
                                        $per = $row->percentage_amount;
                                        $camt = floatval(($price * $per) / 100);
                                        $amt = $price + $camt;
                                        $commission = $camt;
                                        $Fprice = $amt;
                                    }

                                    if ($Wprice == 0)
                                    {
                                        $data['Wprice'] = $price * 7;
                                    }
                                    else
                                    {
                                        $data['Wprice'] = $Wprice;
                                    }
                                    if ($Mprice == 0)
                                    {
                                        $data['Mprice'] = $price * 30;
                                    }
                                    else
                                    {
                                        $data['Mprice'] = $Mprice;
                                    }
                                }
                            }
                            else
                            {
                                $diff = strtotime($ckout[2] . '-' . $ckout[0] . '-' . $ckout[1]) - strtotime($ckin[2] . '-' . $ckin[0] . '-' . $ckin[1]);
                                $days = ceil($diff / (3600 * 24));

                                if ($guests > $guests)
                                {
                                    $price = ($price * $days) + ($days * $xprice->addguests);
                                }
                                else
                                {
                                    $price = $price * $days;
                                }

                                if ($Wprice == 0)
                                {
                                    $data['Wprice'] = $price * 7;
                                }
                                else
                                {
                                    $data['Wprice'] = $Wprice;
                                }
                                if ($Mprice == 0)
                                {
                                    $data['Mprice'] = $price * 30;
                                }
                                else
                                {
                                    $data['Mprice'] = $Mprice;
                                }

                                $commission = 0;

                                if ($row->is_premium == 1)
                                {
                                    if ($row->is_fixed == 1)
                                    {
                                        $fix = $row->fixed_amount;
                                        $amt = $price + $fix;
                                        $commission = $fix;
                                        $Fprice = $amt;
                                    }
                                    else
                                    {
                                        $per = $row->percentage_amount;
                                        $camt = floatval(($price * $per) / 100);
                                        $amt = $price + $camt;
                                        $commission = $camt;
                                        $Fprice = $amt;
                                    }

                                    if ($Wprice == 0)
                                    {
                                        $data['Wprice'] = $price * 7;
                                    }
                                    else
                                    {
                                        $data['Wprice'] = $Wprice;
                                    }
                                    if ($Mprice == 0)
                                    {
                                        $data['Mprice'] = $price * 30;
                                    }
                                    else
                                    {
                                        $data['Mprice'] = $Mprice;
                                    }
                                }
                            }

                            $conditions = array('list_id' => $room_id);
                            $image_query = $this->db->select('name')->where('list_id', $room_id)->where('is_featured', 1)->from('list_photo')->get();

                            if ($image_query->num_rows() != 0)
                            {
                                foreach ($image_query->result() as $row)
                                {
                                    $image_name = $row->name;
                                }
                                $image = base_url() . 'images/' . $room_id . '/' . $image_name;
                            }
                            else
                            {
                                $image = base_url() . 'images/no_image.jpg';
                            }

                            $conditions = array('list_id' => $room_id, 'userto' => $list->user_id);
                            $result = $this->Trips_model->get_review($conditions);

                            $conditions = array('list_id' => $room_id, 'userto' => $list->user_id);
                            $stars = $this->Trips_model->get_review_sum($conditions)->row();

                            $title = substr($title, 0, 70);

                            $level = explode(',', $address);
                            $keys = array_keys($level);
                            $country = $level[end($keys)];
                            if (is_numeric($country) || ctype_alnum($country))
                                $country = $level[$keys[count($keys) - 2]];
                            if (is_numeric($country) || ctype_alnum($country))
                                $country = $level[$keys[count($keys) - 3]];


                            $search = array('\'', '"', '(', ')', '!', '{', '[', '}', ']');
                            $replace = array('&sq', '&dq', '&obr', '&cbr', '&ex', '&obs', '&oabr', '&cbs', '&cabr');
                            $desc_replace = str_replace($search, $replace, $desc);
                            $desc_tags = stripslashes($desc_replace);
                            if ($street_view == 0)
                            {
                                $street_view_str = 'Hide Street View';
                            }
                            elseif ($street_view == 1)
                            {
                                $street_view_str = 'Nearby (within 2 blocks)';
                            }
                            else
                            {
                                $street_view_str = 'Closest to My Address';
                            }
                            $amenities = $this->db->get_where('list', array('id' => $room_id))->row()->amenities;
                            $property_type = $this->db->get_where('property_type', array('id' => $property_id))->row()->type;
                            $in_arr = explode(',', $amenities);
                            $result = $this->db->get('amnities');

                            echo "[ { \"id\":" . $room_id . ",\"user_id\":" . $user_id . ",\"title\":\"" . $title . "\",\"country\":\"" . $country .
                            "\",\"city\":\"" . $city . "\",\"state\":\"" . $state . "\",\"cancellation_policy\":\"" . $cancellation_policy .
                            "\",\"address\":\"" . $address . "\",\"image_url\":\"" . $image . "\",\"room_type\":\"" . $room_type . "\",\"bedrooms\":" . $bedrooms .
                            ",\"beds\":" . $beds . ",\"bathrooms\":" . $bathrooms . ",\"bed_type\":\"" . $bed_type . "\",\"desc\":\"" . $desc_tags . "\",\"capacity\":" . $capacity . ",\"price\":\"" . $price .
                            "\",\"cleaning_fee\":\"" . $cleaning_fee . "\",\"additional_guest_fee\":\"" . $additional_guests_price . "\",
	           \"additional_guest_after\":\"" . $additional_guests_after . "\",\"weekly_price\":\"" . $Wprice .
                            "\",\"monthly_price\":\"" . $Mprice . "\",\"email\":\"" . $email . "\",\"phone\":\"" . $phone . "\",\"review\":\"" . $review .
                            "\",\"lat\":" . $lat . ",\"long\":" . $long . ",\"property_type\":\"" . $property_type . "\",\"street_view\":\"" . $street_view_str .
                            "\",\"sublet_price\":" . $sublet_price . ",\"sublet_status\":" . $sublet_status . ",\"sublet_startdate\":\"" . $sublet_startdate .
                            "\",\"sublet_enddate\":\"" . $sublet_enddate . "\",\"currency\":\"" . $currency . "\",\"manual\":\"" . $manual . "\",\"page_viewed\":" . $page_viewed
                            . ",\"neighbor\":\"" . $neighbor . "\",\"directions\":\"" . $directions . "\",\"amenities\":\"";
                            if ($result->num_rows() != 0)
                            {
                                if ($amenities)
                                {
                                    foreach ($result->result() as $row)
                                    {
                                        if (in_array($row->id, $in_arr))
                                        {
                                            $json[] = $row->name . ",";
                                        }
                                    }
                                    $count = count($json);
                                    $end = $count - 1;
                                    $slice = array_slice($json, 0, $end);
                                    foreach ($slice as $row)
                                    {
                                        echo $row;
                                    }
                                    $comma = end($json);
                                    $json = substr_replace($comma, "", -1);
                                    echo $json . "\"";
                                    echo "} ]";
                                    exit;
                                }
                            }
                            else
                            {
                                $json[] = '';
                            }
                            echo "\"} ]";
                        }
                    }
                    else
                    {
                        echo "[ { \"status\":\"Access Denied\" } ]";
                    }
                }
                else
                {
                    echo "[ { \"status\":\"Check your room id\" } ]";
                }
            }
            else
            {
                echo "[ { \"status\":\"Check your user id\" } ]";
            }
        }

        function edit_listing()
        {
            $room_id = $this->input->get('room_id');
            $user_id = $this->input->get('user_id');
            $property_type = $this->input->get('property_type');
            $room_type = $this->input->get('room_type');
            $title = $this->input->get('title');
            $desc = $this->input->get("desc");
            $amenities = $this->input->get('amenities');
            $accommodates = $this->input->get('accommodates');
            $bed_rooms = $this->input->get('bed_rooms');
            $beds = $this->input->get('beds');
            $bed_type = $this->input->get('bed_type');
            $bath_rooms = $this->input->get('bath_rooms');
            $manual = $this->input->get('manual');
            $cancellation_policy = $this->input->get('cancellation_policy');
            $address = $this->input->get('address');
            $neighborhoods = $this->input->get('neighborhoods');
            $street_view = $this->input->get('street_view');
            $directions = $this->input->get('directions');
            $nightly_price = $this->input->get('nightly_price');
            $weekly_price = $this->input->get('weekly_price');
            $monthly_price = $this->input->get('monthly_price');
            $additional_guests_after = $this->input->get('additional_guests_after');
            $additional_guests_fee = $this->input->get('additional_guests_fee');
            $cleaning_fees = $this->input->get('cleaning_fees');
            $lat = $this->input->get('lat');
            $long = $this->input->get('long');

            $property_id = $this->db->get_where('property_type', array('type' => $property_type))->row()->id;

            if ($street_view == 'Hide Street View')
            {
                $street_view_str = 0;
            }
            elseif ($street_view == 'Nearby (within 2 blocks)')
            {
                $street_view_str = 1;
            }
            else
            {
                $street_view_str = 2;
            }
            $in_arr = explode(',', $amenities);
            $result = $this->db->get('amnities');
            $amenities_id = '';
            if ($result->num_rows() != 0)
            {
                if ($amenities)
                {
                    foreach ($result->result() as $row)
                    {
                        if (in_array($row->name, $in_arr))
                        {
                            $json[] = $row->id . ",";
                        }
                    }
                    $count = count($json);
                    $end = $count - 1;
                    $slice = array_slice($json, 0, $end);
                    foreach ($slice as $row)
                    {
                        echo $row;
                    }
                    $comma = end($json);
                    $json = substr_replace($comma, "", -1);
                    $amenities_id = $json;
                }
            }
            else
            {
                $amenities_id = '';
            }

            $level = explode(',', $address);
            $keys = array_keys($level);
            $country = $level[end($keys)];
            if (is_numeric($country) || ctype_alnum($country))
                $country = $level[$keys[count($keys) - 2]];
            if (is_numeric($country) || ctype_alnum($country))
                $country = $level[$keys[count($keys) - 3]];

            $updateData = array(
                'property_id' => $property_id,
                'room_type' => $room_type,
                'title' => $title,
                'desc' => $desc,
                'capacity' => $accommodates,
                'cancellation_policy' => $cancellation_policy,
                'bedrooms' => $bed_rooms,
                'beds' => $beds,
                'bed_type' => $bed_type,
                'bathrooms' => $bath_rooms,
                'manual' => $manual,
                'street_view' => $street_view_str,
                'directions' => $directions,
                'neighbor' => $neighborhoods,
                'address' => $address,
                'lat' => $lat,
                'long' => $long,
                'amenities' => $amenities_id,
                'price' => $nightly_price,
                'country' => $country
            );

            $data = array(
                'night' => $nightly_price,
                'week' => $weekly_price,
                'month' => $monthly_price,
                'addguests' => $additional_guests_fee,
                'guests' => $additional_guests_after,
                'cleaning' => $cleaning_fees
            );

            $user_check = $this->db->where('user_id', $user_id)->where('id', $room_id)->from('list')->get();
            $user_valid = $this->db->where('id', $user_id)->from('users')->get();

            if ($user_valid->num_rows() != 0)
            {
                if ($user_check->num_rows() != 0)
                {
                    $updateKey = array('id' => $room_id);
                    if ($property_id && $property_type && $title && $desc && $amenities && $accommodates && $bed_rooms && $beds && $bed_type && $bath_rooms && $manual && $cancellation_policy && $address && $neighborhoods && $street_view && $directions && $lat && $long && $nightly_price && $weekly_price && $monthly_price && $additional_guests_after && $additional_guests_fee && $cleaning_fees)
                    {
                        $this->load->model('Rooms_model');
                        $this->Rooms_model->update_list($updateKey, $updateData);
                        $this->Common_model->updateTableData('price', $room_id, NULL, $data);
                        echo "[ { \"status\":\"Updated Successfully.\" } ]";
                        exit;
                    }
                    else
                    {
                        echo "[ { \"status\":\"Please Enter All Details.\" } ]";
                        exit;
                    }
                }
                else
                {
                    echo "[ { \"status\":\"Check your room id\" } ]";
                    exit;
                }
            }
            else
            {
                echo "[ { \"status\":\"Check your user id\" } ]";
                exit;
            }
        }

        function twitter_signup()
        {

            if ($this->input->get("email") && $this->input->get("firstname") && $this->input->get("lastname") && $this->input->get("username") && $this->input->get("twitter_id") && $this->input->get("image_url") && $this->input->get('user_agent') && $this->input->get('last_ip'))
            {

                extract($this->input->get());

                $user_agent = $this->input->get('user_agent');
                $last_ip = $this->input->get('last_ip');

                $emailCheck = $this->db->query("select users.email from users where email = '$email' ")->result();
                if (count($emailCheck) == 0)
                {


                    $usernameCheck = $this->db->query("select users.username from users where username = '$username' ")->result();
                    if (count($usernameCheck) == 0)
                    {

                        $twitterCheck = $this->db->query("select users.twitter_id from users where twitter_id = '$twitter_id' ")->result();
                        if (count($twitterCheck) == 0)
                        {

                            $twitter_image_url = $image_url;

                            $data['email'] = $this->input->get("email");
                            $data['username'] = $this->input->get("username");
                            $data['twitter_id'] = $this->input->get("twitter_id");
                            $last_login = date('Y-m-d h:i:s', time());
                            $data['last_ip'] = $last_ip;
                            $data['last_login'] = $last_login;

                            $this->db->insert('users', $data);

                            $user_id = $this->db->where('email', $data['email'])->select('*')->from('users')->get()->row()->id;
                            $add['Fname'] = $this->input->get("firstname");
                            $add['Lname'] = $this->input->get("lastname");
                            $add['id'] = $user_id;
                            $add['email'] = $data['email'];
                            $this->Common_model->insertData('profiles', $add);

                            $img['email'] = $data['email'];
                            $img['src'] = $image_url;
                            $this->Common_model->insertData('profile_picture', $img);

                            $notification = array();
                            $notification['user_id'] = $user_id;
                            $notification['new_review '] = 1;
                            $notification['leave_review'] = 1;
                            $this->Common_model->insertData('user_notification', $notification);

                            $last_login = date('Y-m-d h:i:s', time());
                            $auto['key_id'] = md5($last_login);
                            $auto['user_id'] = $user_id;
                            $auto['user_agent'] = $user_agent;
                            $auto['last_ip'] = $last_ip;
                            $auto['last_login'] = $last_login;

                            $this->Common_model->insertData('user_autologin', $auto);


                            echo '[{"user_id":"' . $user_id . '","status":"success","twitter_uid":"' . $this->input->get("twitter_id") . '","profile_image":"' . $image_url . '"}]';
                        }
                        else
                        {
                            echo '[{"status":"Twitter Id Already Taken"}]';
                        }
                    }
                    else
                    {
                        echo '[{"status":"Username Already Taken"}]';
                    }
                }
                else
                {
                    echo '[{"status":"Email is Already Registered"}]';
                }
            }
            else
            {
                echo '[{"status":"Failed"}]';
            }
        }

        function inbox()
        {
            $user_id = $this->input->get('user_id');
            $conditions = array("messages.userto " => $user_id);
            $data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id', 'desc'));
            if ($data['messages']->num_rows() != 0)
            {
                $value = $data['messages']->result();
                echo "[";
                foreach ($value as $row)
                {
                    $test = $row->photo_status;
                    $condition1 = $row->email;
                    if ($test == "2")
                    {
                        $result_picture = $this->Common_model->getTableData('profile_picture', $condition1);
                        foreach ($result_picture->result() as $ro)
                        {
                            $src = $ro->src;
                        }
                    }
                    elseif ($test == "1")
                    {
                        $src = base_url() . 'images/users/' . $row->userby . '/userpic.jpg';
                    }
                    else
                    {
                        $src = base_url() . 'images/no_avatar.jpg';
                    }

                    $search = array('<br>');
                    $replace = array('&br&');
                    $message = str_replace($search, $replace, $row->message);

                    $json[] = "{ \"id\":" . $row->id . ",\"list_id\":\"" . $row->list_id . "\",\"reservation_id\":\"" . $row->reservation_id . "\",\"contact_id\":\"" . $row->contact_id . "\",
		\"conversation_id\":\"" . $row->conversation_id . "\",\"userby\":\"" . $row->userby . "\",\"userto\":\"" . $row->userto . "\",
		\"email\":\"" . $row->email . "\",\"username\":\"" . $row->username . "\",\"src\":\"" . $src . "\",\"subject\":\"" . $row->subject . "\",\"message\":\"" . $message . "\",\"created\":\"" . $row->created . "\",\"is_read\":\"" . $row->is_read . "\",\"message_type\":\"" . $row->message_type . "\",\"is_starred\":\"" . $row->is_starred . "\",\"name\":\"" . $row->name . "\",\"url\":\"" . $row->url . "\",\"checkin\":\"" . $row->checkin . "\",\"checkout\":\"" . $row->checkout . "\",\"price\":\"" . $row->price . "\",\"no_quest\":\"" . $row->no_quest . "\"},";
                }

                $count = count($json);
                $end = $count - 1;
                $slice = array_slice($json, 0, $end);
                foreach ($slice as $row)
                {
                    echo $row;
                }
                $comma = end($json);
                $json = substr_replace($comma, "", -1);
                echo $json;
                echo "]";
//,message_type.url,reservation.checkin,reservation.checkout,reservation.price,reservation.no_quest,contacts.checkin,contacts.checkout
                //echo strip_tags(json_encode($data['messages']->result()));
            }
            else
            {
                echo '[{"status":"Empty"}]';
            }
        }

    }

?>
