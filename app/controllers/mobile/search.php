<?php

    /**
     * DROPinn Search Controller Class
     *
     * helps to achieve common tasks related to the site for mobile app like android and iphone.
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Search
     * @author		Cogzidel Product Team
     * @version		Version 1.0
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Search extends CI_Controller
    {

        public function Search()
        {
            parent::__construct();

            $this->load->helper('url');

            $this->load->library('DX_Auth');

            $this->load->model('Users_model');
            $this->load->model('Gallery');
        }

        public function index()
        {
            //Get the checkin and chekout dates
            $checkin = '';
            $checkout = '';
            $stack = array();
            $room_types = array();
            $property_type_id = array();
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
            $min_bathrooms = $this->input->get('min_bathrooms');
            $min_beds = $this->input->get('min_beds');

            $property_type_id = $this->input->get('property_type_id');
            $hosting_amenities = $this->input->get('hosting_amenities');


            $array_items = array(
                'Vcheckin' => '',
                'Vcheckout' => '',
                'Vcheckout' => '',
            );
            $this->session->unset_userdata($array_items);

            if ($this->input->post('checkin') != '' || $this->input->post('checkin') != 'mm/dd/yy')
            {
                $freshdata = array(
                    'Vcheckin' => $this->input->get('checkin'),
                    'Vcheckout' => $this->input->get('checkout'),
                    'Vnumber_of_guests' => $this->input->get('number_of_guests'),
                );
                $this->session->set_userdata($freshdata);
            }

            if ($checkin != '--' && $checkout != '--' && $checkin != "yy-mm-dd" && $checkout != "yy-mm-dd")
            {
                $ans = $this->db->query("SELECT id,list_id FROM `calendar` WHERE `booked_days` = '" . $checkin . "' OR `booked_days` = '" . $checkout . "' GROUP BY `list_id`");
                //echo $this->db->last_query();exit;
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

            $query = $this->input->get('location');
            $pieces = explode(",", $query);

            $print = "";
            $len = count($pieces);

            if ($search_by_map)
            {
                $this->db->where("lat BETWEEN $sw_lat AND $ne_lat");
                $this->db->where("long BETWEEN $sw_lng AND $ne_lng");
            }
            else
            {
                if ($query != '')
                {
                    foreach ($pieces as $test)
                    {
                        $this->db->flush_cache();
                        $test = $this->db->escape_like_str($test);
                        $this->db->like('address', $test);
                    }
                }
            }

            if (!empty($min_bedrooms))
            {
                $this->db->where('bedrooms', $min_bedrooms);
            }

            if (!empty($min_bathrooms))
            {
                $this->db->where('bathrooms', $min_bathrooms);
            }

            if (!empty($min_beds))
            {
                $this->db->where('beds', $min_beds);
            }

            if (!empty($stack))
            {
                $this->db->where_not_in('id', $stack);
            }

            if ($nof_guest > 1)
            {
                $this->db->where('capacity', $nof_guest);
            }

            if (is_array($room_types))
            {
                if (count($room_types) > 0)
                {
                    foreach ($room_types as $room_type)
                    {
                        $this->db->where('room_type', $room_type);
                    }
                }
            }


            if (is_array($hosting_amenities))
            {
                if (count($hosting_amenities) > 0)
                {
                    foreach ($hosting_amenities as $amenity)
                    {
                        $this->db->like('amenities', $amenity);
                    }
                }
            }


            if (isset($min))
            {
                if ($min > 0)
                {
                    $this->db->where('price >=', $min);
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
                    $this->db->where('price <=', $max);
                }
            }


            if (is_array($property_type_id))
            {
                if (count($property_type_id) > 0)
                {
                    $i = 1;
                    foreach ($property_type_id as $r)
                    {
                        if ($i == 1)
                            $this->db->where('property_id', $r);
                        else
                            $this->db->or_where('property_id', $r);

                        $i++;
                    }
                }
            }

            if (!empty($keywords))
            {
                $keywords = $this->db->escape_like_str($keywords);

                $this->db->like('address', $keywords);
                $this->db->or_like('title', $keywords);
                $this->db->or_like('desc', $keywords);
            }

            //Exececute the query
            $this->db->where('status !=', 0);
            $this->db->where('user_id !=', 0);
            $this->db->where('address !=', '0');
            $data['query'] = $this->db->get('list');
            $tCount = $data['query']->num_rows();
            //echo $this->db->last_query();exit;

            $properties = '';
            $sno = 1;
            if ($data['query']->num_rows() > 0)
            {
                foreach ($data['query']->result() as $row)
                {
                    $images = $this->Gallery->get_images($row->id);
                    if (count($images) == 0)
                        $url = base_url() . 'images/no_image.jpg';
                    else
                        $url = $images[0]['url'];

                    $profile_pic = $this->Gallery->profilepic($row->user_id, 3);

                    if ($tCount == $sno)
                        $comma = '';
                    else
                        $comma = ',';

                    $properties .= '{
					               "available":true,
																				"user_thumbnail_url":"' . $profile_pic . '",
																				"user_is_superhost":false,
																				"lat":' . $row->lat . ',
																				"has_video":false,
																				"recommendation_count":0,
																				"lng":' . $row->long . ',
																				"user_id":' . $row->user_id . ',
																				"user_name":"' . get_user_by_id($row->user_id)->username . '",
																				"review_count":' . $row->review . ',
																				"address":"' . $row->address . '",
																				"city":"' . $row->city . '",
																				"state":"' . $row->state . '",
																				"country":"' . $row->country . '",
																				"name":"' . $row->title . '",
																				"hosting_thumbnail_url":"' . $url . '",
																				"id":' . $row->id . ',
																				"price":' . $row->price . ',
																				"currency":"' . $row->currency . '"
																				}' . $comma;


                    $sno++;
                }
            }
            else
            {
                $properties = '{"available":false,"reason_message":"Your search was a little too specific, searching for a different city."}';
            }


            $ajax_result = '[';

            $ajax_result .= $properties;

            $ajax_result .=']';

            echo $ajax_result;
        }

        public function dateconvert($date)
        {
            $ckout = explode('/', $date);
            $diff = $ckout[2] . '-' . $ckout[0] . '-' . $ckout[1];
            return $diff;
        }

    }

?>