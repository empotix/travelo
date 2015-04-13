<?php

    class Rooms extends CI_Controller
    {

        public function Rooms()
        {
            parent::__construct();
            $this->load->helper('url');
            $this->path = realpath(APPPATH . '../images');
            $this->gallery_path_url = base_url() . 'images/';
            $this->logopath = realpath(APPPATH . '../');
            $this->load->library('DX_Auth');

            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->load->model('Message_model');
            $this->load->model('Trips_model');
            $this->load->model('Rooms_model');

            $this->facebook_lib->enable_debug(TRUE);
            $this->load->library('image_lib');
            $this->path = realpath(APPPATH . '../images');
        }

        public function add()
        {
            $data['user_id'] = $this->input->get("user_id");
            $data['address'] = $this->input->get("address");

            $level = explode(',', $data['address']);
            $keys = array_keys($level);
            $country = $level[end($keys)];
            if (is_numeric($country) || ctype_alnum($country))
                $country = $level[$keys[count($keys) - 1]];
            if (is_numeric($country) || ctype_alnum($country))
                $country = $level[$keys[count($keys) - 1]];
            $data['country'] = trim($country);

            $data['lat'] = $this->input->get("latitude");
            $data['long'] = $this->input->get("langtitude");
            $property_type = $this->input->get('property_id');  // it's a property type
            $data['property_id'] = $this->db->get_where('property_type', array('type' => $property_type))->row()->id;
            $data['room_type'] = $this->input->get("room_type");
            $data['bedrooms'] = $this->input->get("bedrooms");
            $data['title'] = $this->input->get("title");
            $data['desc'] = $this->input->get("description");
            $data['capacity'] = $this->input->get('capacity');
            $data['price'] = $this->input->get("price");
            $data['currency'] = trim($this->input->get('currency'));
            $data['phone'] = $this->input->get("phone");

            $this->db->insert('list', $data);

            //Getting the info just entered
            $this->db->where('user_id', $data['user_id']);
            $this->db->where('title', $data['title']);
            $this->db->where('desc', $data['desc']);

            $query = $this->db->get('list');

            $result = $query->result();

            $data2['id'] = $result[0]->id;
            $data2['night'] = $data['price'];
            $data2['currency'] = $data['currency'];
            $this->db->insert('price', $data2);

            $lys_status['id'] = $result[0]->id;
            $lys_status['user_id'] = $data['user_id'];
            $lys_status['calendar'] = '1';
            $lys_status['price'] = '1';
            $lys_status['overview'] = '1';
            $lys_status['title'] = '1';
            $lys_status['summary'] = '1';
            $lys_status['photo'] = '1';
            $lys_status['amenities'] = '1';
            $lys_status['address'] = '1';
            $lys_status['listing'] = '1';
            $lys_status['beds'] = '1';
            $lys_status['bathrooms'] = '1';

            $this->db->insert('lys_status', $lys_status);

            echo '[{"reason_message":"List added successfully."}]';
            exit;
        }

        public function update($param = '')
        {

            if ($param != '')
            {
                $amenity = $this->input->get('amenities');
                $aCount = count($amenity);

                $amenities = '';
                if (is_array($amenity))
                {
                    if (count($amenity) > 0)
                    {
                        $i = 1;
                        foreach ($amenity as $value)
                        {
                            if ($i == $aCount)
                                $comma = '';
                            else
                                $comma = ',';

                            $amenities .= $value . $comma;
                            $i++;
                        }
                    }
                }

                $updateData = array(
                    'property_id' => $this->input->get('property_id'),
                    'room_type' => $this->input->get('room_type'),
                    'title' => $this->input->get('hosting_descriptions'),
                    'desc' => $this->input->get('desc'),
                    'capacity' => $this->input->get('capacity'),
                    'bedrooms' => $this->input->get('bedrooms'),
                    'beds' => $this->input->get('beds'),
                    'bed_type' => $this->input->get('hosting_bed_type'),
                    'bathrooms' => $this->input->get('hosting_bathrooms'),
                    'manual' => $this->input->get('manual'),
                    'street_view' => $this->input->get('street_view'),
                    'directions' => $this->input->get('hosting_directions')
                );

                if (isset($_POST['address']['formatted_address_native']))
                {
                    $address = $_POST['address']['formatted_address_native'];
                    if (!empty($address))
                    {
                        $address = urlencode($address);
                        $address = str_replace('+', '%20', $address);
                        $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');
                        $output = json_decode($geocode);

                        $updateData['address'] = $_POST['address']['formatted_address_native'];

                        $level = explode(',', $updateData['address']);
                        $keys = array_keys($level);
                        $country = $level[end($keys)];
                        if (is_numeric($country) || ctype_alnum($country))
                            $country = $level[$keys[count($keys) - 1]];
                        if (is_numeric($country) || ctype_alnum($country))
                            $country = $level[$keys[count($keys) - 1]];
                        $updateData['country'] = trim($country);

                        $updateData['lat'] = $output->results[0]->geometry->location->lat;
                        $updateData['long'] = $output->results[0]->geometry->location->lng;
                    }
                }

                if ($amenities != '')
                {
                    $updateData['amenities'] = $amenities;
                }

                $updateKey = array('id' => $param);
                $this->Rooms_model->update_list($updateKey, $updateData);

                //echo $this->db->last_query();exit;

                echo '{"redirect_to":"' . base_url() . 'rooms/' . $param . '","result":"success"}';
            }
        }

        public function edit_photo($param = '')
        {

            $this->load->model('Gallery');
            $listId = $param;
            $images = $this->input->get('image');
            if (!empty($images))
            {
                foreach ($images as $image)
                {
                    unlink($image);
                }
            }

            if (isset($_FILES["userfile"]["name"]))
            {
                if (!is_dir($this->path . '/' . $listId))
                {
                    //echo $this->path.'/'.$id;
                    mkdir($this->path . '/' . $listId, 0777, true);
                }
                $config = array(
                    'allowed_types' => 'jpg|jpeg|gif|png',
                    'upload_path' => $this->path . '/' . $listId
                );
                //echo $this->path.'/'.$id;
                $this->load->library('upload', $config);
                $this->upload->do_upload();
            }

            $rimages = $this->Gallery->get_images($listId);
            $i = 1;
            $replace = '<ul class="clearfix">';
            foreach ($rimages as $rimage)
            {
                $replace .= '<li><p><label><input type="checkbox" name="image[]" value="' . $rimage['path'] . '" /></label>';
                $replace .= '<img src="' . $rimage['url'] . '" width="150" height="150" /></p></li>';
                $i++;
            }
            $replace .= '</ul>';

            echo $replace;
        }

        public function update_price()
        {
            $data = array(
                'currency' => $this->input->get('currency'),
                'night' => $this->input->get('nightly'),
                'week' => $this->input->get('weekly'),
                'month' => $this->input->get('monthly'),
                'addguests' => $this->input->get('extra'),
                'guests' => $this->input->get('guests'),
                'security' => $this->input->get('security'),
                'cleaning' => $this->input->get('cleaning')
            );

            $this->db->where('id', $this->uri->segment(3));
            $this->db->update('price', $data);

            redirect('rooms/edit_price/' . $this->uri->segment(3), 'refresh');
        }

        public function edit_price()
        {
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $data['title'] = "Edit the price information for your site";
                $data['message_element'] = 'rooms/view_edit_price';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function change_status()
        {
            $sow_hide = $this->input->get('stat');
            $row_id = $this->input->get('rid');

            if ($sow_hide == 1)
            {
                $data['status'] = 0;
                $this->db->where('id', $row_id);
                $this->db->update('list', $data);
                redirect('hosting');
            }
            else
            {
                $data['status'] = 1;
                $this->db->where('id', $row_id);
                $this->db->update('list', $data);
                redirect('hosting');
            }
        }

        public function change_availability($param = '')
        {
            if ($param != '')
            {
                $is_available = $this->input->post('is_available');
                if ($is_available == 0)
                {
                    echo '{"result":"unavailable","message":"Your listing will be hidden from public search results.","available":false,"prompt":"Your listing can now be activated!"}';
                }
                else
                {
                    echo '{"result":"available","message":"Your listing will now appear in public search results.","available":true,"prompt":"Your listing is active."}';
                }
            }
        }

        public function recent_view()
        {
            //$conditions     = array("list.is_enable" => 1, "list.status" => 1);
//$limit          = array(12);
//$orderby        = array("page_viewed", "desc");
//$mosts  = $this->Rooms_model->get_rooms($conditions, NULL, $limit, $orderby);
            $mosts = $this->db->select('*')->order_by('page_viewed', 'desc')->limit('12')->from('list')->get();
            if ($mosts->num_rows() != 0)
            {
                echo "[ ";
                foreach ($mosts->result() as $row)
                {

                    $image_query = $this->db->select('name')->where('list_id', $row->id)->where('is_featured', 1)->from('list_photo')->get();
                    if ($image_query->num_rows() != 0)
                    {
                        foreach ($image_query->result() as $rows)
                        {
                            $image_name = $rows->name;
                        }
                        $images = base_url() . 'images/' . $row->id . '/' . $image_name;
                    }
                    else
                    {
                        $images = base_url() . 'images/no_image.jpg';
                    }
                    //$json[] = "{ \"id\":".$id.",\"title\":\"".$row->title."\",\"country\":\"".$country."\",\"image_url\":\"".$image."\" },";
                    $json[] = "{ \"id\":" . $row->id . ",\"title\":\"" . $row->title . "\",\"country\":\"" . $row->country . "\",\"image_url\":\"" . base_url() . "files/timthumb.php?src=" . $images . "&h=309&w=598&zc=&q=100\",\"address\":\"" .
                            $row->address . "\",\"price\":\"" . $row->price . "\"},";
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
                echo " ]";
            }
        }

        public function neighborhoods()
        {
            $conditions = array("list.is_enable" => 1, "list.status" => 1);
//$limit          = array(12);
            $orderby = array("page_viewed", "desc");
            $mosts = $this->Rooms_model->get_rooms($conditions, NULL, $orderby);
//$items= '';
            if ($mosts->num_rows() != 0)
            {
                echo "[ ";
                foreach ($mosts->result() as $row)
                {
                    $items[] = $row->country;
                    //$itemsid[] = $row->id;
                }
                //echo'<pre>';print_r(array_unique($items));exit;
                $result_id = array();
                $i = 0;
                $result_country = array_unique($items);
                //print_r($result_country);exit;
                $final_country = array();
                $i = 0;
                foreach ($result_country as $row_country)
                {
                    if ($i < 12)
                    {
                        $final_country[] = $row_country;
                        $i++;
                    }
                }
                //print_r($final_country);
                foreach ($final_country as $rows_country)
                {
                    $conditions = array("country" => $rows_country);
                    $mosts1 = $this->Rooms_model->get_rooms($conditions, NULL, $orderby);
                    foreach ($mosts1->result() as $row_ids)
                    {
                        $list_id[] = $row_ids->id;
                    }
                    $count = count($list_id);
                    //$json[] = "{ \"id\":".$id.",\"title\":\"".$row->title."\",\"country\":\"".$country."\",\"image_url\":\"".$image."\" },";
                    $json[] = "{\"country\":\"" . $rows_country . "\",\"image_url\":\"" . getListImage($list_id[$count - 1]) . "\"},";
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
                echo " ]";
            }
        }

        public function selected_view()
        {
            $room_id = $this->input->get('room_id');

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
                    $bathrooms = $row->bathrooms;
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
                    $amenities = $row->amenities;

                    $price_query = $this->db->where('id', $room_id)->from('price')->get();

                    if ($price_query->num_rows() != 0)
                    {
                        foreach ($price_query->result() as $row)
                        {
                            if ($currency == 'USD' || $currency == '0')
                            {
                                $price = $price;
                                $cleaning_fee = $row->cleaning;
                                $extra_guest_fee = $row->addguests . '/guest after' . $row->guests;
                                $Wprice = $row->week;
                                $Mprice = $row->month;
                            }
                            else
                            {

                                $params_price = array('amount' => $price, 'currFrom' => $currency, 'currInto' => 'USD');
                                $params_clean = array('amount' => $row->cleaning, 'currFrom' => $currency, 'currInto' => 'USD');
                                $params_guest = array('amount' => $row->addguests, 'currFrom' => $currency, 'currInto' => 'USD');
                                $params_week = array('amount' => $row->week, 'currFrom' => $currency, 'currInto' => 'USD');
                                $params_month = array('amount' => $row->month, 'currFrom' => $currency, 'currInto' => 'USD');

                                $price = round(google_convert($params_price));
                                $cleaning_fee = round(google_convert($params_clean));
                                $extra_guest_fee = round(google_convert($params_guest));
                                $Wprice = round(google_convert($params_week));
                                $Mprice = round(google_convert($params_month));
                            }
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

                    $amenities = $this->db->get_where('list', array('id' => $room_id))->row()->amenities;
                    $property_type = $this->db->get_where('property_type', array('id' => $property_id))->row()->type;
                    $in_arr = explode(',', $amenities);
                    $result = $this->db->get('amnities');
                    $user_name = $this->db->where('id', $user_id)->select('username')->from('users')->get();
                    if ($user_name->num_rows() != 0)
                    {
                        foreach ($user_name->result() as $row)
                        {
                            $hoster_name = $row->username;
                        }
                    }
                    else
                    {
                        $hoster_name = 'No Username';
                    }



                    echo "[ { \"id\":" . $room_id . ",\"user_id\":" . $user_id . ",\"hoster_name\":\"" . $hoster_name . "\",\"title\":\"" . $title . "\",\"country\":\"" . $country .
                    "\",\"city\":\"" . $city . "\",\"state\":\"" . $state . "\",\"cancellation_policy\":\"" . $cancellation_policy .
                    "\",\"address\":\"" . $address . "\",\"image_url\":\"" . base_url() . "files/timthumb.php?src=" . $image . "&h=309&w=598&zc=&q=100\",
	           \"room_type\":\"" . $room_type . "\",\"bedrooms\":" . $bedrooms . ",\"bathrooms\":" . $bathrooms . ",\"bed_type\":\"" . $bed_type . "\",
	           \"desc\":\"" . $desc_tags . "\",\"capacity\":" . $capacity . ",\"price\":\"$" . $price .
                    "\",\"cleaning_fee\":\"$" . $cleaning_fee . "\",\"extra_guest_fee\":\"$" . $extra_guest_fee . "\",\"weekly_price\":\"$" . $Wprice .
                    "\",\"monthly_price\":\"$" . $Mprice . "\",\"email\":\"" . $email . "\",\"phone\":\"" . $phone . "\",\"review\":\"" . $review .
                    "\",\"lat\":" . $lat . ",\"long\":" . $long . ",\"property_type\":\"" . $property_type . "\",\"street_view\":" . $street_view .
                    ",\"sublet_price\":" . $sublet_price . ",\"sublet_status\":" . $sublet_status . ",\"sublet_startdate\":\"" . $sublet_startdate .
                    "\",\"sublet_enddate\":\"" . $sublet_enddate . "\",\"currency\":\"" . $currency . "\",\"manual\":\"" . $manual . "\",\"page_viewed\":" . $page_viewed
                    . ",\"neighbor\":\"" . $neighbor . "\",\"amenities\":\"";
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

        function availability()
        {
            $checkin = $this->input->get('checkin');
            $checkin_time = $checkin;

            $checkin_time = get_gmt_time(strtotime($checkin_time));

            $checkout = $this->input->get('checkout');
            $checkout_time = $checkout;

            $checkout_time = get_gmt_time(strtotime($checkout_time));

            $id = $this->input->get('room_id');


            $conditions = array("id" => $id);
            $result = $this->Common_model->getTableData('list', $conditions);
            if ($result->num_rows() == 0)
            {
                echo "[ { \"status\":\"Access Denied\" } ]";
            }
            else
            {
                $status = 1;
                $daysexist = $this->db->query("SELECT id,list_id,booked_days FROM `calendar` WHERE `list_id` = '" . $id . "' AND (`booked_days` >= '" . $checkin_time . "' AND `booked_days` <= '" . $checkout_time . "') GROUP BY `id`");
                $rowsexist = $daysexist->num_rows();
                if ($rowsexist > 0)
                {
                    $status = 0;
                }
                if ($status == 0)
                {
                    echo "[ { \"status\":\"NO\" } ]";
                }
                else
                {
                    echo "[ { \"status\":\"YES\" } ]";
                }
            }
        }

        function property_type()
        {
            $property_type = $this->db->select('*')->from('property_type')->get();
            echo json_encode($property_type->result());
        }

        function amenities()
        {
            $amenities = $this->db->select('*')->from('amnities')->get();
            echo json_encode($amenities->result());
        }

    }

?>