<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Search extends CI_Controller
    {

        public function Search()
        {
            parent::__construct();

            $this->load->helper('url');

            $this->load->library('Form_validation');
            $this->load->library('Pagination');
            $this->load->library('email');
            $this->load->helper('form');

            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->load->model('Trips_model');
            $this->load->model('Rooms_model');

            $this->facebook_lib->enable_debug(TRUE);
        }

        public function index()
        {
            //Get the checkin and chekout dates

            $checkin = '';
            $checkout = '';
            $stack = array();
            $room_types = array();

            $checkin = $this->input->post('checkin');
            $checkout = $this->input->post('checkout');
            $nof_guest = $this->input->post('number_of_guests');

            if ($this->input->post('room_types1'))
            {
                $room_types1 = $this->input->post('room_types1');
                $data['room_types1'] = $room_types1;
            }
            if ($this->input->post('room_types2'))
            {
                $room_types2 = $this->input->post('room_types2');
                $data['room_types2'] = $room_types2;
            }
            if ($this->input->post('room_types3'))
            {
                $room_types3 = $this->input->post('room_types3');
                $data['room_types3'] = $room_types3;
            }

            if ($this->input->post('room_types4'))
            {
                $room_types1 = $this->input->post('room_types4');
                $data['room_types1'] = $room_types1;
            }
            if ($this->input->post('room_types5'))
            {
                $room_types2 = $this->input->post('room_types5');
                $data['room_types2'] = $room_types2;
            }
            if ($this->input->post('room_types6'))
            {
                $room_types3 = $this->input->post('room_types6');
                $data['room_types3'] = $room_types3;
            }

            if ($this->input->post('room_types11'))
            {
                $room_types1 = $this->input->post('room_types11');
                $data['room_types1'] = $room_types1;
            }
            if ($this->input->post('room_types22'))
            {
                $room_types2 = $this->input->post('room_types22');
                $data['room_types2'] = $room_types2;
            }
            if ($this->input->post('room_types33'))
            {
                $room_types3 = $this->input->post('room_types33');
                $data['room_types3'] = $room_types3;
            }

            $min = $this->input->post('min');
            $max = $this->input->post('max');

            //get starred list status		
            $star = $this->input->get('starred');

            $page = $this->input->get('page', 1);
            $data['page'] = $page;

            $array_items = array(
                'Vcheckin' => '',
                'Vcheckout' => '',
                'Vcheckout' => '',
            );
            $this->session->unset_userdata($array_items);

            if ($this->input->post('checkin') != '' || $this->input->post('checkin') != 'mm/dd/yy')
            {
                $freshdata = array(
                    'Vcheckin' => $this->input->post('checkin'),
                    'Vcheckout' => $this->input->post('checkout'),
                    'Vnumber_of_guests' => $this->input->post('number_of_guests'),
                );
                $this->session->set_userdata($freshdata);
            }

            if ($this->input->post('location'))
            {
                $location = $this->input->post('location');
                //$this->session->unset_userdata('location1');
                $this->session->set_userdata('location', $location);
            }
            else if ($this->input->get('location'))
            {
                $location = $this->input->get('location');
            }
            else if ($this->session->userdata('ajax_search_location') != '')
            {
                $location = $this->session->userdata('ajax_search_location');
            }
            $ser = $this->input->post('searchbox');
            if ($this->input->post('searchbox') != '')
            {
                $location = $this->input->post('searchbox');
                //$this->session->unset_userdata('location');
                $this->session->set_userdata('location1', $location);
            }
            if (isset($location))
            {
                $pieces = explode(",", $location);
                $data['pieces'] = $pieces;
                $check = $this->input->post('checkin');
                if (isset($check))
                {
                    $checkin = $this->input->post('checkin');
                    //$this->session->set_userdata('checkin',$checkin);
                }
                else
                    $checkin = 'mm/dd/yy';

                if ($this->input->post('checkout'))
                {
                    $checkout = $this->input->post('checkout');
                    //$this->session->set_userdata('checkout',$checkout);
                }
                else
                    $checkout = 'mm/dd/yy';

                if ($this->input->post('number_of_guests'))
                {
                    $number_of_guests = $this->input->post('number_of_guests');
                    //$this->session->set_userdata('number_of_guests',$number_of_guests);
                }
                else
                    $number_of_guests = '1';

                /* 	if(!$this->input->post('location'))
                  {
                  $location = $this->session->userdata('location');
                  }
                  if($location == '')
                  {
                  $location = $this->session->userdata('location1');
                  }
                  if($this->input->get('location'))
                  {
                  $location = $this->input->get('location');
                  } */
                if (!$this->input->post('checkin'))
                {
                    //$checkin = $this->session->userdata('checkin');
                }
                if (!$this->input->post('checkout'))
                {
                    //$checkout = $this->session->userdata('checkout');
                }
                if (!$this->input->post('number_of_guests'))
                {
                    //$number_of_guests = $this->session->userdata('number_of_guests');
                }

                $data['property_type'] = $this->Common_model->getTableData('property_type')->result_array();
                $data['query'] = $location;
                $data['checkin'] = $checkin;
                $data['checkout'] = $checkout;
                $data['number_of_guests'] = $number_of_guests;
                $data['room_types'] = $room_types;
                $data['min'] = $min;
                $data['max'] = $max;
                $data['amnities'] = $this->Rooms_model->get_amnities();
            }
            $data['title'] = get_meta_details('Search_Elements', 'title');
            $data["meta_keyword"] = get_meta_details('Search_Elements', 'meta_keyword');
            $data["meta_description"] = get_meta_details('Search_Elements', 'meta_description');
            $data['message_element'] = 'view_search_result';
            $this->load->view('template', $data);
        }

        public function ajax_get_results()
        {
            $this->load->library("Ajax_pagination");

            //get starred list status		
            $star = $this->input->get('starred');

            //Get the checkin and chekout dates
            $checkin = '';
            $checkout = '';
            $stack = array();
            $room_types = array();
            $property_type_id = array();

            $this->session->set_userdata('ajax_search_location', $this->input->get('location'));

            $checkin = $this->input->get('checkin');
            $checkout = $this->input->get('checkout');
            $nof_guest = $this->input->get('guests');
            $room_types = $this->input->get('room_types');



            $search_view = $this->input->get('search_view');

            $min = $this->input->get('price_min');
            $max = $this->input->get('price_max');

            $keywords = $this->input->get('keywords');

            $search_by_map = $this->input->get('search_by_map');
            $sw_lat = $this->input->get('sw_lat');
            $sw_lng = $this->input->get('sw_lng');
            $ne_lat = $this->input->get('ne_lat');
            $ne_lng = $this->input->get('ne_lng');

            $min_bedrooms = $this->input->get('min_bedrooms');
            $property_type = $this->input->get('property_type');
            $min_bathrooms = $this->input->get('min_bathrooms');
            $min_beds = $this->input->get('min_beds');
            $min_bed_type = $this->input->get('min_bed_type');

            $property_type_id = $this->input->get('property_type_id');
            $hosting_amenities = $this->input->get('hosting_amenities');

            $page = $this->input->get('page');
            $sort = $this->input->get('sort');
            /* if(empty($sort))
              {
              $sort = 1;
              } */

            $data['page'] = $page;


            if ($checkin != '--' && $checkout != '--' && $checkin != "yy-mm-dd" && $checkout != "yy-mm-dd")
            {
                // Specify the start date. This date can be any English textual format  
                $date_from = $checkin;
                $date_from = strtotime($date_from); // Convert date to a UNIX timestamp  
                // Specify the end date. This date can be any English textual format  
                $date_to = $checkout;
                $date_to = strtotime($date_to); // Convert date to a UNIX timestamp  
                $arr = array();
                // Loop from the start date to end date and output all dates inbetween  
                for ($i = $date_from; $i <= $date_to; $i+=86400)
                {

                    $arr[] = $i;
                }
                $ans = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `booked_days` = '" . get_gmt_time(strtotime($checkin)) . "' OR `booked_days` = '" . get_gmt_time(strtotime($checkout)) . "' GROUP BY `list_id`");

                if ($ans->num_rows() == 0)
                {
                    $ans = $this->db->where_in('booked_days', $arr)->group_by('list_id')->get('calendar');
                }

                $a = $ans->result();
                $this->db->flush_cache();
                // Now after the checkin is completed
                if (!empty($a))
                {
                    foreach ($a as $a1)
                    {
                        array_push($stack, $a1->list_id);
                    }
                }
            }

            $condition = '';
            $location = $this->input->get('location');
            $pieces = explode(",", $location);

            $print = "";
            $len = count($pieces);

            $condition .= "(`status` != '0')";

            if ($search_by_map)
            {
                $condition .= "AND (`lat` BETWEEN $sw_lat AND $ne_lat) AND (`long` BETWEEN $sw_lng AND $ne_lng)";
            }
            else
            {
                if ($location != '')
                {
                    $i = 1;
                    foreach ($pieces as $address)
                    {
                        $this->db->flush_cache();
                        $address = $this->db->escape_like_str($address);

                        if ($i == $len)
                            $and = "";
                        else
                            $and = " AND ";

                        if ($i == 1)
                            $condition .= " AND (";

                        $condition .= "`address`  LIKE '%" . $address . "%' OR `neighbor`  LIKE '%" . $address . "%'" . $and;

                        if ($i == $len)
                            $condition .= ")";

                        $i++;
                    }
                }
            }

            if (!empty($min_bedrooms))
            {
                $condition .= " AND (`bedrooms` = '" . $min_bedrooms . "')";
            }
            if ($property_type != 1)
            {
                $condition .= " AND (`property_id` = '" . $property_type . "')";
            }
            if (!empty($min_bathrooms))
            {
                $condition .= " AND (`bathrooms` = '" . $min_bathrooms . "')";
            }

            if (!empty($min_beds))
            {
                $condition .= " AND (`beds` = '" . $min_beds . "')";
            }

            if (!empty($min_bed_type))
            {
                $condition .= " AND (`bed_type` = '" . $min_bed_type . "')";
            }

            if (!empty($stack))
            {
                $condition .= " AND (`id` NOT IN(" . implode(',', $stack) . "))";
            }

            if ($nof_guest > 1)
            {
                $condition .= " AND (`capacity` >= '" . $nof_guest . "')";
            }

            if (is_array($room_types))
            {
                if (count($room_types) > 0)
                {
                    $i = 1;
                    foreach ($room_types as $room_type)
                    {
                        if ($i == count($room_types))
                            $and = "";
                        else
                            $and = " AND ";
                        $or = " OR ";

                        if ($i == 1)
                            $condition .= " AND (";

                        $condition .= "`room_type` = '" . $room_type . "'" . $or . "`neighbor` = '" . $room_type . "'" . $or . "`neighbor` = '" . $room_type . "'" . $or . "`room_type` = '" . $room_type . "'" . $or . "`room_type` = '" . $room_type . "'" . $and;

                        if ($i == count($room_types))
                            $condition .= ")";

                        $i++;
                    }
                }
            }

            if (is_array($hosting_amenities))
            {
                if (count($hosting_amenities) > 0)
                {
                    $i = 1;
                    foreach ($hosting_amenities as $amenity)
                    {
                        if ($i == count($hosting_amenities))
                            $and = "";
                        else
                            $and = " AND ";

                        if ($i == 1)
                            $condition .= " AND (";

                        $condition .= "`amenities`  LIKE '%" . $amenity . "%'" . $and;

                        if ($i == count($hosting_amenities))
                            $condition .= ")";

                        $i++;
                    }
                }
            }


            if (isset($min))
            {
                if ($min > 0)
                {
                    $condition .= " AND (`price` >= '" . $min . "')";
                }
            }
            else
            {
                if (isset($max))
                {
                    $min = 0;
                }
            }

            if (isset($max))
            {
                if ($max > $min)
                {
                    $condition .= " AND (`price` <= '" . $max . "')";
                }
            }

            if (is_array($property_type_id))
            {
                if (count($property_type_id) > 0)
                {
                    $i = 1;
                    foreach ($property_type_id as $property_id)
                    {
                        if ($i == count($property_type_id))
                        {
                            $and = "";
                        }
                        else
                        {
                            $and = " OR ";
                        }

                        if ($i == 1)
                            $condition .= " AND (";

                        $condition .= "`property_id` = '" . $property_id . "'" . $and;

                        if ($i == count($property_type_id))
                            $condition .= ")";

                        $i++;
                    }
                }
            }

            if (!empty($keywords))
            {
                $keywords = $this->db->escape_like_str($keywords);

                $condition .= " AND (`address`  LIKE '%" . $keywords . "%' OR  `title`  LIKE '%" . $keywords . "%' OR  `desc`  LIKE '%" . $keywords . "%')";
            }

            //Final query
            $condition .= " AND (`status` != '0') AND (`user_id` != '0') AND (`address` != '0') AND (`is_enable` = '1')";

            // Get offset and limit for page viewing
            $start = (int) $page;

            // Number of record showing per page
            $per_page = 20;

            if ($start > 0)
                $offset = ($start - 1) * $per_page;
            else
                $offset = $start * $per_page;

            if ($sort == 2)
            {
                $order = "ORDER BY price ASC";
            }
            else if ($sort == 3)
            {
                $order = "ORDER BY price DESC";
            }
            else if ($sort == 4)
            {
                $order = "ORDER BY id DESC";
            }
            else
            {
                $order = "ORDER BY id ASC";
            }
            //My ShortLists	
            if ($search_view == 2)
            {
                $constraint = "";
                $shortlists = $this->db->where('id', $this->dx_auth->get_user_id())->get('users')->row()->shortlist;
                $my_lists = explode(',', $shortlists);
                $i = 1;
                foreach ($my_lists as $list)
                {
                    if ($i == count($my_lists))
                        $OR = "";
                    else
                        $OR = " OR ";

                    $data['query'] = $this->db->query("SELECT * FROM (`list`) WHERE $condition $order LIMIT $offset,$per_page");

                    if ($data['query']->num_rows() != 0)
                    {
                        foreach ($data['query']->result() as $row)
                        {
                            if ($row->id == $list)
                            {
                                $constraint .= "`id`= '" . $list . "'" . $OR;
                            }
                            else
                            {
                                
                            }
                        }
                    }

                    $i++;
                }
                if ($constraint == '')
                {
                    $data['query'] = $this->db->query("SELECT * FROM (`list`) where $condition AND id=0 $order LIMIT $offset,$per_page");
                    $total_rows = 0;
                }
                else
                {
                    $data['query'] = $this->db->query("SELECT * FROM (`list`) where $condition AND $constraint $order LIMIT $offset,$per_page");
                    $total_rows = $this->db->query("SELECT * FROM (`list`) where $condition AND $constraint")->num_rows();
                }
            }
            else
            {
                $data['query'] = $this->db->query("SELECT * FROM (`list`) WHERE $condition $order LIMIT $offset,$per_page");
                $this->session->unset_userdata('query');
                $this->session->set_userdata('query', "SELECT * FROM (`list`) WHERE $condition ORDER BY id DESC");
                $total_rows = $this->db->query("SELECT * FROM (`list`) WHERE $condition")->num_rows();
            }

            $config['base_url'] = site_url('search') . '?checkin=' . urlencode($checkin) . '&amp;checkout=' . urlencode($checkout) . '&amp;guests=' . $nof_guest . '&amp;location=' . urlencode($location) . '&amp;min_bathrooms=' . $min_bathrooms . '&amp;min_bedrooms=' . $min_bedrooms . '&amp;min_beds=' . $min_beds . '&amp;min_bed_type=' . $min_bed_type . '&amp;per_page=' . $per_page . '&amp;search_view=1&amp;sort=' . $sort;

            $config['per_page'] = $per_page;

            $config['cur_page'] = $start;

            $config['total_rows'] = $total_rows;

            $this->ajax_pagination->initialize($config);

            $pagination = $this->ajax_pagination->create_links(false);

            $tCount = $data['query']->num_rows();

            $properties = '';
            $sno = 1;
            foreach ($data['query']->result() as $row)
            {
                //main photo
                $url = getListImage($row->id);

                //for map slider full list images
                $images = $this->Gallery->get_imagesG($row->id);
                $picture_ids = '';
                foreach ($images->result() as $image)
                {
                    $picture_ids .= '"' . $image->list_id . '/' . $image->name . '",';
                }

                $profile_pic = $this->Gallery->profilepic($row->user_id, 2);

                if ($tCount == $sno)
                    $comma = '';
                else
                    $comma = ',';

                $neighbor = $row->neighbor;
                $final_price = get_currency_value1($row->id, $row->price);

                /* Offer price calculate */

                if ($checkin != '--' && $checkout != '--' && $checkin != "yy-mm-dd" && $checkout != "yy-mm-dd")
                {


                    $daysdiff = (strtotime($checkout) - strtotime($checkin) ) / (60 * 60 * 24);
                }

//My shortlist
                $short_listed = 0;
                $cur_user_id = $this->dx_auth->get_user_id();
                if ($cur_user_id)
                {
                    $shortlist = $this->Common_model->getTableData('users', array('id' => $cur_user_id))->row()->shortlist;
                    $my = explode(',', $shortlist);
                    foreach ($my as $list)
                    {
                        if ($list == $row->id)
                            $short_listed = 1;
                    }
                }
                /* end of offer calculate	 */

                $properties .= '{
							"user_thumbnail_url":"' . $profile_pic . '",
							"user_is_superhost":false,
							"lat":' . $row->lat . ',
							"has_video":false,
							"recommendation_count":0,
							"lng":' . $row->long . ',
							"user_id":' . $row->user_id . ',
							"user_name":"' . get_user_by_id($row->user_id)->username . '",
							"symbol":"' . get_currency_symbol($row->id) . '",
							"review_count":' . $row->review . ',
							"address":"' . $row->address . '",
							"name":"' . $row->title . '",
							"picture_ids":[' . substr($picture_ids, 0, -1) . '],
							"hosting_thumbnail_url":"' . $url . '",
							"id":' . $row->id . ',
							"page_viewed":' . $row->page_viewed . ',
							"price":' . $final_price . ',
							"short_listed":' . $short_listed . '
							}' . $comma;
                $sno++;
            }

            $startlist = 1 + $offset;
            $endlist = $offset + $per_page;

            if ($endlist > $total_rows)
            {
                $endlist = $total_rows;
            }

            $ajax_result = '{
																				"results_count_html":"\n<b>' . $startlist . ' &ndash; ' . $endlist . '</b> of <b>' . $total_rows . ' listings</b>",
																				"results_count_top_html":"  ' . $total_rows . ' ' . translate('results') . '\n",
																				"view_type":' . $search_view . ',
																				"results_pagination_html":"' . $pagination . '\n",
																				"present_standby_option":false,
																				"properties":[';

            $ajax_result .= $properties;

            $ajax_result .='],
																			"banner_info":{},
																			"sort":' . $sort . '
																			}';

            echo $ajax_result;
        }

        public function dateconvert($date)
        {
            $ckout = explode('/', $date);
            $diff = $ckout[2] . '-' . $ckout[0] . '-' . $ckout[1];
            return $diff;
        }

        public function get_maps()
        {
            $this->load->view('template', $data);
        }

        public function add_my_shortlist()
        {
            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                echo "error";
            }
            else
            {
                $list_id = $this->input->post('list_id');
                $user_id = $this->dx_auth->get_user_id();
                $shortlist = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->shortlist;
                if ($shortlist == "")
                {
                    $data = array('shortlist' => $list_id);
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $data);
                }
                else
                {
                    $my_shortlist = $shortlist . ',' . $list_id;
                    $data = array('shortlist' => $my_shortlist);
                    $this->db->where('id', $user_id);
                    $this->db->update('users', $data);
                }


                $count_list = array();
                $count_wishlist = 0;
                $lists = $this->db->select('shortlist')->get('users');

                foreach ($lists->result() as $rows_count)
                {
                    if ($rows_count->shortlist)
                    {
                        $count_list[] = $rows_count->shortlist;
                    }
                }
                foreach ($count_list as $list_room)
                {
                    $view_list = explode(",", $list_room);
                    $count = count($view_list);
                    for ($i = 0; $i < $count; $i++)
                    {
                        if ($room_id == $view_list[$i])
                        {
                            $count_wishlist++;
                        }
                        else
                        {
                            $count_wishlist;
                        }
                    }
                }
            }
        }

        public function remove_my_shortlist()
        {
            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
            {
                echo "error";
            }
            else
            {
                $list_id = $this->input->post('list_id');
                $user_id = $this->dx_auth->get_user_id();
                $shortlist = $this->Common_model->getTableData('users', array('id' => $this->dx_auth->get_user_id()))->row()->shortlist;
                //Remove the selected list from the All short lists
                $result = "";
                $my = explode(',', $shortlist);
                foreach ($my as $list)
                {
                    if ($list != $list_id)
                    {
                        $result .= $list . ",";
                    }
                }
                //Remove Comma from last character
                if ((substr($result, -1)) == ',')
                    $my_shortlist = substr_replace($result, "", -1);
                else
                    $my_shortlist = $result;

                $data = array('shortlist' => $my_shortlist);
                $this->db->where('id', $user_id);
                $this->db->update('users', $data);
            }
        }

        public function login_check()
        {
            if ((!$this->dx_auth->is_logged_in()) && (!$this->facebook_lib->logged_in()))
                echo "error";
            else
                echo "success";
        }

        public function sample()
        {
            $neighbor = '';

            $location = $this->input->get('location');
            $query = $this->session->userdata('query');

            $query = $this->db->query($query);
            $results = $query->result_array();

            if ($query->num_rows() != 0)
            {
                foreach ($results as $row)
                {
                    $zz = $row['neighbor'];
                    $neighbor = $row['title'] . " : " . $zz . '<br>' . $neighbor;
                }
            }
            else
            {
                $empty = '';
            }
            echo $neighbor;
        }

    }

?>
