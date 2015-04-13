<?php

    /**
     * DROPinn Users Controller Class
     *
     * It helps shows the home page with slider.
     *
     * @package		Users
     * @subpackage	Controllers
     * @category	Referrals
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com
     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Home extends CI_Controller
    {

        //Constructor
        public function Home()
        {
            parent::__construct();

            $this->load->database();

            $this->config_data->db_config_fetch();

            //Manage site Status 
            if ($this->config->item('site_status') == 1)
            {
                redirect('maintenance');
            }

            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->helper('cookie');
            $this->load->library('twconnect');
            $this->load->library('Form_validation');

            $this->load->model('Referrals_model');
            $this->load->model('Message_model');
            $this->load->model('Rooms_model');
            $this->load->model('Trips_model');
            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->facebook_lib->enable_debug(TRUE);
        }

        public function index()
        {
            $conditions = array("list.is_featured" => 1, "list.is_enable" => 1, "list.status" => 1, "list_photo.is_featured" => 1, "list_photo.is_featured" => 1);
            $limit = array(30);
            $data['lists'] = $this->Rooms_model->get_rooms_byImage($conditions, NULL, $limit);


            $data['categories'] = $this->Common_model->getTableData('toplocation_categories')->result();

            $data['other_countries'] = $this->Common_model->getTableData('toplocations', array('category_id' => 1));

            $data['world'] = $this->Common_model->getTableData('toplocations', array('category_id' => 2));

            $limit = array(3);
            $orderby = array("id", "desc");
            $conditionL = array("list.is_enable" => 1, "list.status" => 1);
            $data['latest_ads'] = $this->Rooms_model->get_rooms($conditionL, NULL, $limit, $orderby);


            $this->db->distinct()->select('neigh_city.city_name')->select('neigh_city.id')->where('neigh_city.is_home', 1);
            $this->db->from('neigh_city');
            $this->db->join('neigh_city_place', 'neigh_city_place.city_name = neigh_city.city_name');
            $this->db->join('neigh_post', 'neigh_post.city = neigh_city_place.city_name');
            $this->db->where('neigh_city_place.is_featured', 1)->where('neigh_post.is_featured', 1);
            $data['cities'] = $this->db->get();

            $data["title"] = $this->dx_auth->get_site_title();
            $data["meta_keyword"] = $this->Common_model->getTableData('settings', array('code' => 'META_KEYWORD'))->row()->string_value;
            $data["meta_description"] = $this->Common_model->getTableData('settings', array('code' => 'META_DESCRIPTION'))->row()->string_value;
            $data['message_element'] = "home/view_home";
            $this->load->view('template', $data);
        }

        //Ajax page
        public function view_most()
        {
            $view_most = $this->input->post('view');

            if ($view_most == 'most_viewed')
            {
                $conditions = array("list.is_enable" => 1, "list.status" => 1);
                $limit = array(4);
                $orderby = array("page_viewed", "desc");
                $data['mosts'] = $this->Rooms_model->get_rooms($conditions, NULL, $limit, $orderby);
            }
            else if ($view_most = 'most_booked')
            {
                $conditions = array('reservation.status >=' => 8);
                $limit = array(4);
                $data['mosts'] = $this->Trips_model->get_reservation_most($conditions, NULL, $limit);
            }
            else
            {
                $conditions = array('status' => 1, 'review !=' => 0);
                $limit = array(4);
                $orderby = array('review', 'desc');
                $data['mosts'] = $this->Rooms_model->get_rooms($conditions, NULL, $limit, $orderby);
            }

            $this->load->view(THEME_FOLDER . '/home/view_most', $data);
        }

        public function dashboard()
        {

            if ($this->uri->segment(3) != '')
            {
                redirect('info/deny');
            }

            if (( $this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {


                $details = $this->Referrals_model->get_details_refamount('165');

                if ($details->num_rows() == 0)
                {
                    $data['travel_credit'] = 0;
                }
                else
                {
                    $amount = $details->row()->amount;
                    $data['travel_credit'] = $amount;
                }

                $conditions = array("messages.userto" => $this->dx_auth->get_user_id(), "messages.is_read !=" => 1);
                $check = $this->Message_model->get_messages($conditions, NULL, array('messages.id', 'desc'));

                if ($check->num_rows() != 0)
                {
                    foreach ($check->result() as $row)
                    {
                        $result = $this->db->where('id', $row->list_id)->get('list');
                        if ($result->num_rows() == 0)
                        {
                            $this->db->delete('messages', array('list_id' => $row->list_id));
                        }
                    }
                }

                $conditions = array("messages.userto" => $this->dx_auth->get_user_id(), "messages.is_read !=" => 1);
                $data['new_notify'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id', 'desc'));

                $conditions = array("messages.userto" => $this->dx_auth->get_user_id(), "messages.is_read" => 0);
                $data['new_notify_rows'] = $this->Message_model->get_messages($conditions)->num_rows();
                if ($this->dx_auth->get_username() == "")
                {
                    $query = $this->Common_model->getTableData('profiles', array('id' => $this->dx_auth->get_user_id()))->row();

                    $data['name'] = $query->Fname . ' ' . $query->Lname;
                }
                else
                {
                    $data['name'] = $this->dx_auth->get_username();
                }

                $data['referral_code'] = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_code;

                $data['verification'] = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row();
                $data['payout'] = $this->db->where('user_id', $this->dx_auth->get_user_id())->get('payout_preferences');
                $data['title'] = get_meta_details('Dashboard', 'title');
                $data["meta_keyword"] = get_meta_details('Dashboard', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Dashboard', 'meta_description');
                $data['message_element'] = "home/view_dashboard";
                $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
                $data['fb_app_secret'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_SECRET'))->row()->string_value;
                $this->load->view('template', $data);
                //$this->load->view(THEME_FOLDER.'/home/view_dashboard',$data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        function fun_invite_mail()
        {
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $this->load->library('email');

                $username = $this->dx_auth->get_username();
                $user_id = $this->dx_auth->get_user_id();

                if ($this->input->post())
                {
                    $message = $this->input->post('msg');
                    $count = count($this->input->post());
                    $referral_code = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row()->referral_code;
                    //$xss_email       = $this->security->xss_clean($this->input->post('email_id')); 
                    $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));
                    for ($i = 1; $i <= $count; $i++)
                    {
                        $this->form_validation->set_rules("email_id$i", 'Email', 'trim|xss_clean|callback__check_user_email');
                    }
                    if ($this->form_validation->run())
                    {
                        for ($i = 1; $i <= $count; $i++)
                        {
                            //$this->form_validation->set_rules("email_id$i", 'Email', 'trim|xss_clean|callback__check_user_email');

                            $email_to = $this->input->post('email_id' . $i);


                            $admin_email = $this->dx_auth->get_site_sadmin();

                            $email_name = 'invite_friend';

                            $mailer_mode = $this->Common_model->getTableData('email_settings', array('code' => 'MAILER_MODE'))->row()->value;

                            if ($mailer_mode == 'html')
                            {
                                $anchor = anchor(base_url() . "users/signup?airef=" . $referral_code, 'Click here');
                            }
                            else
                            {
                                $anchor = 'Click this link' . '  ' . base_url() . "users/signup?airef=" . $referral_code;
                            }

                            $splVars = array("{site_name}" => $this->dx_auth->get_site_title(), "{username}" => ucfirst($username), "{click_here}" => $anchor, "{dynamic_content}" => $message);

                            //Send Mail
                            $this->Email_model->sendMail($email_to, $admin_email, $this->dx_auth->get_site_title(), $email_name, $splVars);
                        }

                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Mail sent successfully.')));
                        redirect('referrals');
                    }

                    $data['title'] = get_meta_details('Invite Friends Via Email', 'title');
                    $data["meta_keyword"] = get_meta_details('Invite Friends Via Email', 'meta_keyword');
                    $data["meta_description"] = get_meta_details('Invite Friends Via Email', 'meta_description');

                    $data['message_element'] = "home/referrals";
                    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('You are enter the already user email address.')));
                    redirect('referrals');
                    $this->load->view('template', $data);
                }
                else
                {
                    redirect('users/signin');
                }
            }
        }

        function _check_user_email($email)
        {
            if ($this->dx_auth->is_email_available($email))
            {
                return true;
            }
            else
            {
                $this->form_validation->set_message('_check_user_email', translate('Sorry this email has already been registered'));
                return false;
            }//If end 
        }

        function fun_invite_fb()
        {
            $fb_app_id = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
            $fb_app_secret = $this->db->get_where('settings', array('code' => 'SITE_FB_API_SECRET'))->row()->string_value;
            $user_fb_id = $this->input->post('fb_id');
            $img_path = base_url() . 'images/close_red.png';
            echo '<script type="text/javascript">
			$("#close_fb").click(function(){
	$("#overlay_form_fb").fadeOut(500);
});</script>';
            echo "<div class='fb_popup_css'><div class='overlay_form_fb'><img src='" . $img_path . "' alt='quit' class='close_fb' id='close_fb'/>";
            $facebook = array(
                'appId' => $fb_app_id,
                'secret' => $fb_app_secret,
                'cookie' => true
            );
            $this->load->library('facebook', $facebook);
            $user = $user_fb_id;
            if ($user)
            {
                try
                {
                    //get the facebook friends list
                    $user_friends = $this->facebook->api('/' . $user . '/friends');
                }
                catch (FacebookApiException $e)
                {
                    error_log($e);
                    $user = NULL;
                }
            }
            if (isset($user_friends))
            {
                echo '<h2> Facebook Friends List </h2> 
<div class="demo">';
                foreach ($user_friends['data'] as $user_friend)
                {
                    echo '<br><table><tr><td>
<img src="https://graph.facebook.com/' . $user_friend["id"] . '/picture" width="30" height="30"/></td><td>
<div  class="frnd_list">' . $user_friend["name"] . '</td><td><a href="#" style="text-decoration: none" onclick="send_invitation(' . $user_friend["id"] . ');">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Invite </a></div>
</td></tr></table>';
                }
            }
            else
            {
                echo 'Sorry! You are not loggedin or you have no friends';
            }
            echo '</div></div></div>';
        }

        function popular()
        {
            $this->load->model('common_model');
            $result = $this->common_model->popular();
            foreach ($result->row() as $row)
            {
                $shortlist = $row;
            }
            $data['shortlist'] = $shortlist;
            $data['title'] = get_meta_details('Popular', 'title');
            $data["meta_keyword"] = get_meta_details('Popular', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Popular', 'meta_description');

            $data['message_element'] = "home/view_popular";
            $this->load->view('template', $data);
        }

        function neighborhoods()
        {
            $conditions = array("list.is_featured" => 1, "list.is_enable" => 1, "list.status" => 1, "list_photo.is_featured" => 1, "list_photo.is_featured" => 1);
            $data['lists'] = $this->Rooms_model->get_rooms_byImage($conditions, NULL);
            $data['title'] = get_meta_details('Neighborhoods', 'title');
            $data["meta_keyword"] = get_meta_details('Neighborhoods', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Neighborhoods', 'meta_description');

            $this->db->distinct()->select('neigh_city.city_name')->select('neigh_city.id')->where('neigh_city.is_home', 1);
            $this->db->from('neigh_city');
            $this->db->join('neigh_city_place', 'neigh_city_place.city_name = neigh_city.city_name');
            $this->db->join('neigh_post', 'neigh_post.city = neigh_city_place.city_name');
            $this->db->where('neigh_city_place.is_featured', 1)->where('neigh_post.is_featured', 1);
            $data['cities'] = $this->db->get();


            $data['message_element'] = "home/view_neighborhoods";
            $this->load->view('template', $data);
        }

        function friends()
        {
            $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;

            if (( $this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $data['name'] = $this->dx_auth->get_username();
                $data['title'] = get_meta_details('Friends', 'title');
                $data["meta_keyword"] = get_meta_details('Friends', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Friends', 'meta_description');

                $data['message_element'] = "home/view_friends";
                $this->load->view('template', $data);
            }
            else
            {
                $this->session->set_userdata('redirect_to', 'home/friends');
                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('You must logged in.')));
                redirect('users/signin');
            }
        }

        function email_exists($email)
        {
            $query = $this->Common_model->getTableData('users', array('email' => $email));
            $q = $query->num_rows();
            if ($q == 1)
                return TRUE;
            else
                return FALSE;
        }

        function ajax_email_exists()
        {
            $email = $this->input->post('email');
            //echo $email;exit;
            $query = $this->Common_model->getTableData('users', array('email' => $email));
            $q = $query->num_rows();
            if ($q == 1)
                echo '0';
            else
                echo '1';
        }

        function fb_exists($id)
        {
            $query = $this->Common_model->getTableData('users', array('fb_id' => $id));
            $q = $query->num_rows();
            if ($q == 1)
                return TRUE;
            else
                return FALSE;
        }

        function fun_friends_fb_id()
        {

            $id = $this->input->post('fb_id');
            $name = $this->input->post('fb_name');
            $friends_count = $this->input->post('friends_count');

            $i = 0;  // Count for fb user contained Lists

            foreach ($id as $fb_id)
            {

                $result = $this->db->select('*')->where('fb_id', $fb_id)->from('users')->get();

                if ($result->num_rows() != 0)
                {

                    $user_id = $result->row()->id;

                    $list_user_id = $this->db->where('user_id', $user_id)->get('list');

                    if ($list_user_id->num_rows() != 0)
                    {
                        $i++;
                        foreach ($list_user_id->result() as $list_row)
                        {
                            $conditions = array('list_id' => $list_row->id);
                            $limit = array(1);
                            $result_img = $this->Gallery->get_imagesG(NULL, $conditions, $limit);

                            if ($result_img->num_rows() != 0)
                            {
                                foreach ($result_img->result() as $image)
                                {

                                    echo '<div class="fb_main"><a href="' . base_url() . 'rooms/' . $image->list_id . '">
					       <img src="' . base_url() . 'images/' . $image->list_id . '/' . $image->name . '" width="995" height="427"/></a>';
                                }
                            }
                            else
                            {
                                echo '<div class="fb_main"><a href="' . base_url() . 'rooms/' . $list_row->id . '">
					<img src="' . base_url() . 'images/no_image.jpg" width="995" height="427"/></a>';
                            }
                            echo '<div class="connect_fb">';
                            echo '<img src="https://graph.facebook.com/' . $fb_id . '/picture" width="30" height="30"/><br>' . $result->row()->username . ' Saved<br>';
                            echo '<a class="fb_title" href="' . base_url() . 'rooms/' . $list_row->id . '">' . $list_row->title . '</a><br></div>';
                            echo '<div class="fb_price_bg"><div class="fb_price"><p class="fb_dollar">' . get_currency_symbol($list_row->id) . '<p>' . get_currency_value1($list_row->id, $list_row->price) . '<br><p class="per_night">' . translate('per night') . '</p></div></div></div>';
                        }
                    }
                }
            }
            if ($i == 0)
            {
                echo '<br><br><div class="list-view" data-feed-container="">
<div class="no-fb-friends-container">
<h2 style="font-size: 24px; line-height: 1.5;">' . translate('Looks like none of your friends have signed up for') . ' ' . $this->dx_auth->get_site_title() . ' ' . translate('yet.') . '</h2>
<br><a class="btn green" href="' . base_url() . 'home/dashboard/">' . translate('Invite them now') . '!</a>
</div>
</div>';
            }

            /* else {
              echo '<br><br><img src="'.base_url().'images/page2_spinner.gif">';
              } */
        }

        function help($refer = '', $pages = '')
        {
            $query = $this->Common_model->getHelpData('help', array('id' => $refer));
            $page = $this->Common_model->getHelpData('help', array('page_refer' => $pages));
            $guide = $this->Common_model->getHelpData('help', array('page_refer' => 'guide'));
            $result = $this->Common_model->getHelpData('help');
            if ($query->num_rows() == 0)
            {
                if ($guide->num_rows() != 0)
                {
                    $row_guide = $guide->row();

                    $data['guide_question'] = $row_guide->question;
                    $data['guide_description'] = $row_guide->description;
                    $data['guide_status'] = $row_guide->status;
                }
                $data['question'] = '';
                $data['description'] = '';
                $data['page_refer'] = '';
                $data['result'] = $result;
                $data['guide'] = $guide;
                $data['title'] = get_meta_details('Help', 'title');
                $data["meta_keyword"] = get_meta_details('Help', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Help', 'meta_description');
                $data['message_element'] = 'view_help';
                $this->load->view('template', $data);
            }
            else
            {

                $row = $query->row();
                if ($guide->num_rows() != 0)
                {
                    $row_guide = $guide->row();

                    $data['guide_question'] = $row_guide->question;
                    $data['guide_status'] = $row_guide->status;
                    $data['id'] = $row_guide->id;
                }
                $data['question'] = $row->question;
                $data['description'] = $row->description;
                $data['page_refer'] = $row->page_refer;
                $data['result'] = $result;
                $data['guide'] = $guide;
                $data['title'] = get_meta_details('Help', 'title');
                $data["meta_keyword"] = get_meta_details('Help', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Help', 'meta_description');
                $data['message_element'] = 'view_help';
                $this->load->view('template', $data);
            }
        }

        function verify()
        {
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $data['users'] = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row();
                $data['profiles'] = $this->db->where('id', $this->dx_auth->get_user_id())->from('profiles')->get()->row();
                $data['fb_app_id'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_ID'))->row()->string_value;
                $data['fb_app_secret'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_SECRET'))->row()->string_value;
                $data['fb_app_secret'] = $this->db->get_where('settings', array('code' => 'SITE_FB_API_SECRET'))->row()->string_value;
                $data['title'] = get_meta_details('Verify', 'title');
                $data["meta_keyword"] = get_meta_details('Verify', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Verify', 'meta_description');
                $data['message_element'] = 'home/verify';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        function help_autocomplete()
        {
            $val = $this->input->get('val');
            $result = $this->db->where('status', 0)->like('question', $val)->get('help');
            foreach ($result->result() as $row)
            {
                $question[] = $row->question;
            }
            echo json_encode($question);
        }

        function help_id()
        {
            $val = $this->input->get('val');
            $result = $this->db->where('question', $val)->get('help');
            $question_id = '';
            foreach ($result->result() as $row)
            {
                $question_id = $row->id;
            }
            echo $question_id;
        }

    }

    /* End of file home.php */
    /* Location: ./app/controllers/home.php */
?>