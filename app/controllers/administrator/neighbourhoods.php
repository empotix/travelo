<?php

    /**
     * DROPinn Admin Email Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Admin Email
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com
     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Neighbourhoods extends CI_Controller
    {

        public function Neighbourhoods()
        {
            parent::__construct();

            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('file');
            $this->load->helper('string');
            $this->load->helper('text');
            //load validation library
            $this->load->library('form_validation');
            $this->load->library('email');
            $this->load->library('Table');
            $this->load->library('Pagination');
            $this->load->library('image_lib');

            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->load->model('Neighbourhoods_model');
            // Protect entire controller so only admin, 
            // and users that have granted role in permissions table can access it.
            $this->dx_auth->check_uri_permissions();
            $this->path = realpath(APPPATH);
        }

        /**
         * Loads Email settings page.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        function index()
        {

            //Get All Email Termplates List
            $data['email_settings'] = $this->Email_model->getEmailSettings();

            $data['message_element'] = "administrator/email/template";
            $this->load->view('administrator/admin_template', $data);
        }

//End of index function

        /**
         * add EmailSettings.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function viewcity()
        {
            //Get Groups 

            $data['cities'] = $this->db->get('neigh_city');

            //Load View	
            $data['message_element'] = "administrator/neighbourhoods/viewcity";
            $this->load->view('administrator/admin_template', $data);
        }

        public function editcity()
        {
            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('submit'))
            {
                //Set rules
                $this->form_validation->set_rules('city', 'city', 'required|trim|min_length[3]|max_length[30]|xss_clean');
                $this->form_validation->set_rules('city_desc', 'city description', 'required|trim|xss_clean');
                $this->form_validation->set_rules('around', 'around', 'required|trim|xss_clean');
                $this->form_validation->set_rules('known', 'known', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    $city = $this->input->post('city');
                    $city_desc = $this->input->post('city_desc');
                    $around = $this->input->post('around');
                    $known = $this->input->post('known');

                    if ($_FILES["city_image"]["name"] != '')
                    {

                        if (isset($_FILES["city_image"]["name"]))
                        {
                            $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/neighbourhoods/';
                            $result = $this->db->where('city_name', $city)->from('neigh_city')->get()->num_rows();

                            $city_id = $this->uri->segment(4);

                            if ($result == 0)
                            {
                                mkdir($path . '/' . $city_id, 0777, true);
                            }
                            else
                            {
                                rmdir($path . "/{$city_id}");
                                mkdir($path . '/' . $city_id, 0777, true);
                            }

                            $config = array(
                                'allowed_types' => 'jpg|jpeg|gif|png',
                                'upload_path' => "./images/neighbourhoods/{$city_id}/",
                                'remove_spaces' => TRUE,
                                'min_width' => 1300,
                                'min_height' => 676
                            );
                            $this->load->library('upload', $config);

                            if (!$this->upload->do_upload('city_image'))
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                redirect_admin("neighbourhoods/editcity/$id");
                            }
                            else
                            {

                                $upload_data = $this->upload->data();
                                $image_name = $upload_data['file_name'];
                                $config['image_library'] = 'gd2';
                                $config['source_image'] = "./images/neighbourhoods/{$city_id}/$image_name";
//$config['create_thumb'] = TRUE;
//$config['new_image'] = '/opt/lampp/htdocs/vignesh/CI/images/resize.jpg';
                                $config['maintain_ratio'] = TRUE;
                                $config['width'] = 1300;
                                $config['height'] = 676;

                                $this->image_lib->initialize($config);

                                if (!$this->image_lib->resize())
                                {
                                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                    redirect_admin("neighbourhoods/editcity/$id");
                                }

                                $upload_data = $this->upload->data();
                                $image_name = $upload_data['file_name'];
                                $image_config['image_library'] = 'gd2';
                                $image_config['source_image'] = "./images/neighbourhoods/{$city_id}/$image_name";
                                $image_config['new_image'] = "./images/neighbourhoods/{$city_id}/crop.jpg";
                                $image_config['quality'] = "100%";
                                $image_config['maintain_ratio'] = FALSE;
                                $image_config['width'] = 1245;
                                $image_config['height'] = 100;
                                $image_config['x_axis'] = '0';
                                $image_config['y_axis'] = '320';

                                $this->image_lib->clear();
                                $this->image_lib->initialize($image_config);

                                $upload_data = $this->upload->data();
                                $image_name = $upload_data['file_name'];
                                $image_config1['image_library'] = 'gd2';
                                $image_config1['source_image'] = "./images/neighbourhoods/{$city_id}/$image_name";
                                $image_config1['new_image'] = "./images/neighbourhoods/{$city_id}/home.jpg";
                                $image_config1['quality'] = "100%";
                                $image_config1['maintain_ratio'] = FALSE;
                                $image_config1['width'] = 315;
                                $image_config1['height'] = 180;

                                $this->image_lib->clear();
                                $this->image_lib->initialize($image_config1);

                                if (!$this->image_lib->resize())
                                {
                                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                    redirect_admin("neighbourhoods/editcity/$id");
                                }

                                $updateKey = $this->uri->segment(4);

                                $edit_city = $this->db->where('id', $updateKey)->get('neigh_city')->row()->city_name;

                                //echo $edit_city;exit;

                                $edit_place = $this->db->where('city_name', $edit_city)->get('neigh_city_place');

                                if ($edit_place->num_rows() != 0)
                                {
                                    $update_city['city_name'] = $city;
                                    $this->db->where('city_name', $edit_city)->update('neigh_city_place', $update_city);

                                    $edit_post = $this->db->where('city', $edit_city)->get('neigh_post');

                                    if ($edit_post->num_rows() != 0)
                                    {
                                        $update_city1['city'] = $city;
                                        $this->db->where('city', $edit_city)->update('neigh_post', $update_city1);
                                    }
                                }

                                $upload_data = $this->upload->data();
                                $updateData['image_name'] = $upload_data['file_name'];
                                $updateData['city_name'] = $city;
                                $updateData['city_desc'] = $city_desc;
                                $updateData['around'] = $around;
                                $updateData['known'] = $known;
                                $updateData['created'] = time();
                                $updateData['is_home'] = $this->input->post('is_home');

                                $this->db->where('id', $updateKey)->update('neigh_city', $updateData);
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('City updated successfully')));
                                redirect_admin('neighbourhoods/viewcity');
                            }
                        }
                    }
                    else
                    {

                        $updateKey = $this->uri->segment(4);

                        $edit_city = $this->db->where('id', $updateKey)->get('neigh_city')->row()->city_name;

                        //echo $edit_city;exit;

                        $edit_place = $this->db->where('city_name', $edit_city)->get('neigh_city_place');

                        if ($edit_place->num_rows() != 0)
                        {
                            $update_city['city_name'] = $city;
                            $this->db->where('city_name', $edit_city)->update('neigh_city_place', $update_city);

                            $edit_post = $this->db->where('city', $edit_city)->get('neigh_post');

                            if ($edit_post->num_rows() != 0)
                            {
                                $update_city1['city'] = $city;
                                $this->db->where('city', $edit_city)->update('neigh_post', $update_city1);
                            }
                        }

                        $updateData['image_name'] = $this->db->get_where('neigh_city', array('id' => $id))->row()->image_name;
                        $updateData['city_name'] = $city;
                        $updateData['city_desc'] = $city_desc;
                        $updateData['around'] = $around;
                        $updateData['known'] = $known;
                        $updateData['created'] = time();
                        $updateData['is_home'] = $this->input->post('is_home');

                        $updateKey = $this->uri->segment(4);

                        $this->db->where('id', $updateKey)->update('neigh_city', $updateData);
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('City updated successfully')));
                        redirect_admin('neighbourhoods/viewcity');
                    }
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('neigh_city.id' => $id);

            $data['cities'] = $this->db->where('id', $id)->get('neigh_city');
            if ($data['cities']->num_rows() == 0)
            {
                redirect('info');
            }
            //Load View	
            $data['message_element'] = "administrator/neighbourhoods/editcity";
            $this->load->view('administrator/admin_template', $data);
        }

        public function deletecity()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {

                $this->load->model('place_model');
                $getplace = $this->place_model->getplace();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('id' => $res);
                        $city = $this->db->where('id', $res)->get('neigh_city')->row()->city_name;
                        $result = $this->db->where('city_name', $city)->get('neigh_city_place');
                        if ($result->num_rows() != 0)
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Delete This City Contained Places First.')));
                            redirect_admin('neighbourhoods/viewcity');
                        }
                        else
                        {
                            $this->db->delete('neigh_city', $condition);
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any Neighborhood Place')));
                    redirect_admin('neighbourhoods/viewcity');
                }
            }
            else
            {
                $condition = array('id' => $id);
                $city = $this->db->where('id', $id)->get('neigh_city')->row()->city_name;
                $result = $this->db->where('city_name', $city)->get('neigh_city_place');
                if ($result->num_rows() != 0)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Delete This City Contained Places First.')));
                    redirect_admin('neighbourhoods/viewcity');
                }
                else
                {
                    $this->db->delete('neigh_city', $condition);
                }
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Neighborhood City deleted successfully')));
            redirect_admin('neighbourhoods/viewcity');
        }

        function addcity()
        {
            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {

                $this->form_validation->set_rules('city', 'city', 'required|trim|min_length[3]|max_length[30]|xss_clean');
                $this->form_validation->set_rules('city_desc', 'city description', 'required|trim|xss_clean');
                $this->form_validation->set_rules('around', 'around', 'required|trim|xss_clean');
                $this->form_validation->set_rules('known', 'known', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    $city = $this->input->post('city');
                    $city_desc = $this->input->post('city_desc');
                    $around = $this->input->post('around');
                    $known = $this->input->post('known');

                    if (isset($_FILES["city_image"]["name"]))
                    {
                        $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/neighbourhoods/';
                        $result = $this->db->where('city_name', $city)->from('neigh_city')->get()->num_rows();

                        $city_id = $this->db->order_by('id', 'desc')->get('neigh_city')->row()->id;
                        $city_id = $city_id + 1;

                        if ($result == 0)
                        {
                            if (!is_dir($path . '/' . $city_id))
                            {
                                mkdir($path . '/' . $city_id, 0777, true);
                            }
                            else
                            {
                                unlink($path . "/{$city_id}");
                            }
                        }
                        else
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('This City already used.')));
                            redirect_admin('neighbourhoods/addcity');
                        }
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('city_image'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addcity');
                        }
                        else
                        {

                            $upload_data = $this->upload->data();
                            $image_name = $upload_data['file_name'];
                            $config['image_library'] = 'gd2';
                            $config['source_image'] = "./images/neighbourhoods/{$city_id}/$image_name";
//$config['create_thumb'] = TRUE;
//$config['new_image'] = '/opt/lampp/htdocs/vignesh/CI/images/resize.jpg';
                            $config['maintain_ratio'] = TRUE;
                            $config['width'] = 1300;
                            $config['height'] = 676;


                            $this->image_lib->clear();
                            $this->image_lib->initialize($config);

                            if (!$this->image_lib->resize())
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                redirect_admin("neighbourhoods/addcity");
                            }



                            $upload_data = $this->upload->data();
                            $image_name = $upload_data['file_name'];
                            $image_config1['image_library'] = 'gd2';
                            $image_config1['source_image'] = "./images/neighbourhoods/{$city_id}/$image_name";
                            $image_config1['new_image'] = "./images/neighbourhoods/{$city_id}/home.jpg";
                            $image_config1['quality'] = "100%";
                            $image_config1['maintain_ratio'] = FALSE;
                            $image_config1['width'] = 315;
                            $image_config1['height'] = 180;

                            $this->image_lib->clear();
                            $this->image_lib->initialize($image_config1);

                            if (!$this->image_lib->resize())
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                redirect_admin("neighbourhoods/addcity");
                            }



                            $upload_data = $this->upload->data();
                            $image_name = $upload_data['file_name'];
                            $image_config['image_library'] = 'gd2';
                            $image_config['source_image'] = "./images/neighbourhoods/{$city_id}/$image_name";
                            $image_config['new_image'] = "./images/neighbourhoods/{$city_id}/crop.jpg";
                            $image_config['quality'] = "100%";
                            $image_config['maintain_ratio'] = FALSE;
                            $image_config['width'] = 1245;
                            $image_config['height'] = 100;
                            $image_config['x_axis'] = '0';
                            $image_config['y_axis'] = '320';

                            $this->image_lib->clear();
                            $this->image_lib->initialize($image_config);

                            if (!$this->image_lib->crop())
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                redirect_admin("neighbourhoods/addcity");
                            }

                            $upload_data = $this->upload->data();
                            $data7['image_name'] = $upload_data['file_name'];
                            $data7['city_name'] = $city;
                            $data7['city_desc'] = $city_desc;
                            $data7['around'] = $around;
                            $data7['known'] = $known;
                            $data7['created'] = time();
                            $data7['is_home'] = $this->input->post('is_home');
                            $result = $this->db->where('city_name', $city)->from('neigh_city')->get()->num_rows();
                            if ($result == 0)
                            {
                                $this->db->insert('neigh_city', $data7);
                            }
                            else
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('This City Already Used')));
                                redirect_admin('neighbourhoods/addcity');
                            }
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                        redirect_admin('neighbourhoods/addcity');
                    }

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Neighborhood Place Added Successfully')));
                    redirect_admin('neighbourhoods/viewcity');
                }
            }

            //Load View
            $data['message_element'] = "administrator/neighbourhoods/addcity";
            $this->load->view('administrator/admin_template', $data);
        }

        public function viewcity_place()
        {
            //Get Groups 

            $data['places'] = $this->db->get('neigh_city_place');

            //Load View	
            $data['message_element'] = "administrator/neighbourhoods/viewcity_place";
            $this->load->view('administrator/admin_template', $data);
        }

        public function editcity_place()
        {
            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('submit'))
            {
                //Set rules
                $this->form_validation->set_rules('place', 'place', 'required|trim|min_length[3]|max_length[30]|xss_clean');
                $this->form_validation->set_rules('quote', 'quote', 'required|trim|xss_clean');
                $this->form_validation->set_rules('short_desc', 'short description', 'required|trim|xss_clean');
                $this->form_validation->set_rules('long_desc', 'long description', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    $place = $this->input->post('place');
                    $city = $this->input->post('city');
                    $quote = $this->input->post('quote');
                    $short_desc = $this->input->post('short_desc');
                    $long_desc = $this->input->post('long_desc');
                    $category = $this->input->post('category');

                    $address = $place . '+' . $city;
                    $address = urlencode($address);
                    $address = str_replace('+', '%20', $address);
                    $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');
                    $output = json_decode($geocode);

                    if (isset($output->results[0]->geometry->location->lat))
                    {
                        $lat = $output->results[0]->geometry->location->lat;
                        ;
                        $lng = $output->results[0]->geometry->location->lng;
                    }
                    else
                    {
                        $lat = '';
                        $lng = '';
                    }

                    if ($_FILES["place_image"]["name"] != '')
                    {

                        if (isset($_FILES["place_image"]["name"]))
                        {
                            $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/neighbourhoods/';
                            $result = $this->db->where('place_name', $place)->from('neigh_city_place')->get()->num_rows();

                            $city_name = $this->db->where('id', $this->uri->segment(4))->get('neigh_city_place')->row()->city_name;

                            $city_id = $this->db->where('city_name', $city_name)->get('neigh_city')->row()->id;

                            $place_id = $this->uri->segment(4);

                            //if($result == 0)
                            //     {
                            if (!is_dir($path . "/{$city_id}/{$place_id}"))
                            {
                                mkdir($path . "/{$city_id}/{$place_id}", 0777, true);
                            }
                            else
                            {
                                rmdir($path . "/{$city_id}/{$place_id}");
                                mkdir($path . "/{$city_id}/{$place_id}", 0777, true);
                            }
                            //	}

                            $config = array(
                                'allowed_types' => 'jpg|jpeg|gif|png',
                                'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}/",
                                'remove_spaces' => TRUE
                            );
                            $this->load->library('upload', $config);
                            // $this->upload->initialize($config);

                            if (!$this->upload->do_upload('place_image'))
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                redirect_admin("neighbourhoods/editcity_place/$id");
                            }
                            else
                            {


                                $updateKey = $this->uri->segment(4);

                                $edit_place = $this->db->where('id', $updateKey)->get('neigh_city_place')->row()->city_name;


                                $edit_post = $this->db->where('city', $edit_place)->get('neigh_post');

                                if ($edit_post->num_rows() != 0)
                                {
                                    $update_city1['city'] = $city;
                                    $this->db->where('city', $edit_place)->update('neigh_post', $update_city1);
                                }

                                $edit_place = $this->db->where('id', $updateKey)->get('neigh_city_place')->row()->place_name;

                                $edit_post = $this->db->where('place', $edit_place)->get('neigh_post');

                                if ($edit_post->num_rows() != 0)
                                {
                                    $update_city1['place'] = $place;
                                    $this->db->where('place', $edit_place)->update('neigh_post', $update_city1);
                                }


                                $upload_data = $this->upload->data();
                                $updateData['image_name'] = $upload_data['file_name'];
                                $updateData['quote'] = $quote;
                                $updateData['city_name'] = $city;
                                $updateData['place_name'] = $place;
                                $updateData['short_desc'] = $short_desc;
                                $updateData['long_desc'] = $long_desc;
                                $updateData['lat'] = $lat;
                                $updateData['lng'] = $lng;

                                $city_id = $this->db->where('city_name', $city)->get('neigh_city')->row()->id;
                                if ($city_id)
                                {
                                    $updateData['city_id'] = $city_id;
                                    $updateData['created'] = time();
                                    $updateData['is_featured'] = $this->input->post('is_home');

                                    $updateKey = $this->uri->segment(4);

                                    $this->db->where('id', $updateKey)->update('neigh_city_place', $updateData);

                                    $data8['place'] = $place;
                                    $data8['city'] = $city;
                                    $this->db->delete('neigh_place_category', $data8);
                                    foreach ($category as $row)
                                    {
                                        $data8['category_id'] = $row;
                                        $this->db->insert('neigh_place_category', $data8);
                                    }

                                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Place updated successfully')));
                                    redirect_admin('neighbourhoods/viewcity_place');
                                }
                                else
                                {
                                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('This City Already Used')));
                                    redirect_admin("neighbourhoods/editcity_place/$id");
                                }
                            }
                        }
                    }
                    else
                    {

                        $updateKey = $this->uri->segment(4);

                        $edit_place = $this->db->where('id', $updateKey)->get('neigh_city_place')->row()->city_name;

                        //echo $edit_place;exit;	
                        $edit_post = $this->db->where('city', $edit_place)->get('neigh_post');

                        if ($edit_post->num_rows() != 0)
                        {
                            $update_city['city'] = $city;
                            $this->db->where('city', $edit_place)->update('neigh_post', $update_city);
                        }

                        $edit_place = $this->db->where('id', $updateKey)->get('neigh_city_place')->row()->place_name;

                        $edit_post = $this->db->where('place', $edit_place)->get('neigh_post');

                        if ($edit_post->num_rows() != 0)
                        {
                            $update_city1['place'] = $place;
                            $this->db->where('place', $edit_place)->update('neigh_post', $update_city1);
                        }

                        $updateData['city_name'] = $city;
                        $updateData['quote'] = $quote;
                        $updateData['place_name'] = $place;
                        $updateData['short_desc'] = $short_desc;
                        $updateData['long_desc'] = $long_desc;
                        $updateData['lat'] = $lat;
                        $updateData['lng'] = $lng;

                        $city_id = $this->db->where('city_name', $city)->get('neigh_city')->row()->id;

                        $updateData['city_id'] = $city_id;
                        $updateData['created'] = time();
                        $updateData['is_featured'] = $this->input->post('is_home');

                        $updateKey = $this->uri->segment(4);

                        $this->db->where('id', $updateKey)->update('neigh_city_place', $updateData);

                        $data8['place'] = $place;
                        $data8['city'] = $city;
                        $this->db->delete('neigh_place_category', $data8);

                        foreach ($category as $row)
                        {
                            $data8['category_id'] = $row;
                            $this->db->insert('neigh_place_category', $data8);
                        }

                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Place updated successfully')));
                        redirect_admin('neighbourhoods/viewcity_place');
                    }
                }
            }

            //Set Condition To Fetch The Faq Category
            $condition = array('neigh_city_place.id' => $id);
            $data['cities'] = $this->db->get('neigh_city');
            $data['places'] = $this->db->where('id', $id)->get('neigh_city_place');

            if ($data['places']->num_rows() == 0)
            {
                redirect('info');
            }

            $data['categories'] = $this->db->from('neigh_category')->get();
            //Load View	
            $data['message_element'] = "administrator/neighbourhoods/editcity_place";
            $this->load->view('administrator/admin_template', $data);
        }

        function addcity_place()
        {
            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {

                $this->form_validation->set_rules('place', 'place', 'required|trim|min_length[3]|max_length[30]|xss_clean');
                $this->form_validation->set_rules('quote', 'quote', 'required|trim|xss_clean');
                $this->form_validation->set_rules('short_desc', 'short description', 'required|trim|xss_clean');
                $this->form_validation->set_rules('long_desc', 'long description', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    $place = $this->input->post('place');
                    $city = $this->input->post('city');
                    $quote = $this->input->post('quote');
                    $short_desc = $this->input->post('short_desc');
                    $long_desc = $this->input->post('long_desc');
                    $category = $this->input->post('category');

                    $address = $place . '+' . $city;
                    $address = urlencode($address);
                    $address = str_replace('+', '%20', $address);
                    $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address=' . $address . '&sensor=false');
                    $output = json_decode($geocode);

                    if (isset($output->results[0]->geometry->location->lat))
                    {
                        $lat = $output->results[0]->geometry->location->lat;
                        ;
                        $lng = $output->results[0]->geometry->location->lng;
                    }
                    else
                    {
                        $lat = '';
                        $lng = '';
                    }

                    $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/neighbourhoods/';

                    $result = $this->db->where('place_name', $place)->from('neigh_city_place')->get()->num_rows();

                    $city_id = $this->db->where('city_name', $city)->get('neigh_city')->row()->id;

                    $place_id = $this->db->order_by('id', 'desc')->get('neigh_city_place')->row()->id;
                    $place_id = $place_id + 1;
                    //	if($result == 0)
                    //  {
                    if (!is_dir($path . "/{$city_id}/{$place_id}"))
                    {
                        mkdir($path . "/{$city_id}/{$place_id}", 0777, true);
                    }
                    else
                    {
                        rmdir($path . "/{$city_id}/{$place_id}");
                        mkdir($path . "/{$city_id}/{$place_id}", 0777, true);
                    }


                    /* 	 $this->load->library('upload');

                      $files = $_FILES;
                      $cpt = count($_FILES['place_image']['name']);
                      for($i=0; $i<$cpt; $i++)
                      {

                      $_FILES['place_image']['name']= $files['place_image']['name'][$i];
                      $_FILES['place_image']['type']= $files['place_image']['type'][$i];
                      $_FILES['place_image']['tmp_name']= $files['place_image']['tmp_name'][$i];
                      $_FILES['place_image']['error']= $files['place_image']['error'][$i];
                      $_FILES['place_image']['size']= $files['place_image']['size'][$i]; */
                    $config = array(
                        'allowed_types' => 'jpg|jpeg|gif|png',
                        'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}/",
                        'remove_spaces' => TRUE
                    );
                    $this->load->library('upload', $config);
                    // $this->upload->initialize($config);
                    if (!$this->upload->do_upload('place_image'))
                    {
                        print_r($this->upload->display_errors());
                        exit;
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                        redirect_admin('neighbourhoods/addcity_place');
                    }
                    else
                    {
                        $upload_data = $this->upload->data();
                        $data7['image_name'] = $upload_data['file_name'];
                        $data7['city_name'] = $city;
                        $data7['quote'] = $quote;
                        $data7['place_name'] = $place;
                        $data7['short_desc'] = $short_desc;
                        $data7['long_desc'] = $long_desc;
                        $data7['lat'] = $lat;
                        $data7['lng'] = $lng;

                        // $categories = $this->place_category($category);
                        // $data7['category'] = $categories;
                        $city_id = $this->db->where('city_name', $city)->get('neigh_city')->row()->id;
                        if ($city_id)
                        {
                            $data7['city_id'] = $city_id;
                            $data7['created'] = time();
                            $data7['is_featured'] = $this->input->post('is_home');

                            $this->db->insert('neigh_city_place', $data7);
                            foreach ($category as $row)
                            {

                                $data8['place'] = $place;
                                $data8['city'] = $city;
                                $data8['category_id'] = $row;
                                $this->db->insert('neigh_place_category', $data8);
                            }
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Place Added Successfully')));
                            redirect_admin('neighbourhoods/viewcity_place');
                        }
                        else
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('This City Already Used')));
                            redirect_admin('neighbourhoods/addcity_place');
                        }
                    }
                    //   }
                    /* else
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','This City Already Used'));
                      redirect_admin('neighbourhoods/addcity_place');
                      } */
                }  //exit;		 		
                //Notification message
                // $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success','Neighborhood Place Added Successfully'));
                //  redirect_admin('neighbourhoods/viewcity_place');
            }
            //	}
            //Load View
            $data['cities'] = $this->db->get('neigh_city');
            $data['categories'] = $this->db->from('neigh_category')->get();
            $data['message_element'] = "administrator/neighbourhoods/addcity_place";
            $this->load->view('administrator/admin_template', $data);
        }

        function place_category($category)
        {
            $aCount = count($category);

            $categories = '';
            if (is_array($category))
            {
                if (count($category) > 0)
                {
                    $i = 1;
                    foreach ($category as $value)
                    {
                        if ($i == $aCount)
                            $comma = '';
                        else
                            $comma = ',';

                        $categories .= $value . $comma;
                        $i++;
                    }
                    return $categories;
                }
            }
        }

        public function deletecity_place()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {

                $this->load->model('place_model');
                $getplace = $this->place_model->getplace();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('id' => $res);

                        $place = $this->db->where('id', $res)->get('neigh_city_place')->row()->place_name;
                        $result = $this->db->where('place', $place)->get('neigh_post');
                        if ($result->num_rows() != 0)
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Delete This Place Contained Posts First.')));
                            redirect_admin('neighbourhoods/viewcity_place');
                        }
                        else
                        {
                            $place_id = $this->db->where('id', $res)->get('neigh_city_place')->row()->id;
                            $saved_neigh = array('place_id' => $place_id);
                            $this->db->delete('saved_neigh', $saved_neigh);
                            $this->db->delete('neigh_city_place', $condition);
                            $data = array('place' => $place);
                            $this->db->delete('neigh_place_category', $data);
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any Neighborhood Place')));
                    redirect_admin('neighbourhoods/viewcity_place');
                }
            }
            else
            {
                $condition = array('id' => $id);
                $place = $this->db->where('id', $id)->get('neigh_city_place')->row()->place_name;
                $result = $this->db->where('place', $place)->get('neigh_post');
                if ($result->num_rows() != 0)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Delete This Place Contained Posts First.')));
                    redirect_admin('neighbourhoods/viewcity_place');
                }
                else
                {
                    $place_id = $this->db->where('id', $id)->get('neigh_city_place')->row()->id;
                    $saved_neigh = array('place_id' => $place_id);
                    $this->db->delete('saved_neigh', $saved_neigh);
                    $this->db->delete('neigh_city_place', $condition);
                    $data = array('place' => $place);
                    $this->db->delete('neigh_place_category', $data);
                }
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Neighborhood Place deleted successfully')));
            redirect_admin('neighbourhoods/viewcity_place');
        }

        function cities()
        {
            $result = $this->db->select('city_name')->from('neigh_city')->get();
            //echo json_encode($result->result());
            foreach ($result->result() as $row)
            {
                $arr[] = $row->city_name;
            }
            // Return data
            echo json_encode($arr);
        }

        function addcategory()
        {
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');
            if ($this->input->post('submit'))
            {
                $this->form_validation->set_rules('category', 'category', 'required|trim|min_length[3]|max_length[30]|xss_clean');

                if ($this->form_validation->run())
                {
                    $data['category'] = $this->input->post('category');
                    // $data['status'] = $this->input->post('status');
                    $data['created'] = time();
                    $result = $this->db->where('category', $data['category'])->from('neigh_category')->get();
                    if ($result->num_rows() == 0)
                    {
                        $this->db->insert('neigh_category', $data);
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Category Added Successfully')));
                        redirect_admin('neighbourhoods/viewcategory');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Already Used Category')));
                        redirect_admin('neighbourhoods/addcategory');
                    }
                }
            }
            $data['message_element'] = "administrator/neighbourhoods/addcategory";
            $this->load->view('administrator/admin_template', $data);
        }

        function editcategory()
        {
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            if ($this->input->post('submit'))
            {

                $this->form_validation->set_rules('category', 'category', 'required|trim|min_length[3]|max_length[30]|xss_clean');

                if ($this->form_validation->run())
                {
                    $data['category'] = $this->input->post('category');
                    // $data['status'] = $this->input->post('status');
                    $data['created'] = time();
                    $updateKey = $this->uri->segment(4);

                    $this->db->where('id', $updateKey)->update('neigh_category', $data);
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Category Added Successfully')));
                    redirect_admin('neighbourhoods/viewcategory');
                }
            }
            $condition = array('neigh_category.id' => $id);

            $data['categories'] = $this->db->where('id', $id)->get('neigh_category');
            if ($data['categories']->num_rows() == 0)
            {
                redirect('info');
            }
            //Load View	
            $data['message_element'] = "administrator/neighbourhoods/editcategory";
            $this->load->view('administrator/admin_template', $data);
        }

        function viewcategory()
        {
            $data['categories'] = $this->db->get('neigh_category');

            //Load View	
            $data['message_element'] = "administrator/neighbourhoods/viewcategory";
            $this->load->view('administrator/admin_template', $data);
        }

        public function deletecategory()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {

                $this->load->model('place_model');
                $getplace = $this->place_model->getplace();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('id' => $res);
                        //$this->db->delete('neigh_category',$condition);
                        $category_id = $this->db->where('id', $res)->get('neigh_category')->row()->id;
                        $result = $this->db->where('category_id', $category_id)->get('neigh_place_category');
                        if ($result->num_rows() != 0)
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Delete This Category Contained Places First.')));
                            redirect_admin('neighbourhoods/viewcategory');
                        }
                        else
                        {
                            $this->db->delete('neigh_category', $condition);
                            $data = array('category_id' => $category_id);
                            $this->db->delete('neigh_place_category', $data);
                        }
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any Category')));
                    redirect_admin('neighbourhoods/viewcategory');
                }
            }
            else
            {
                $condition = array('id' => $id);
                $category_id = $this->db->where('id', $id)->get('neigh_category')->row()->id;
                $result = $this->db->where('category_id', $category_id)->get('neigh_place_category');
                if ($result->num_rows() != 0)
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Delete This Category Contained Places First.')));
                    redirect_admin('neighbourhoods/viewcategory');
                }
                else
                {
                    $this->db->delete('neigh_category', $condition);
                    $data = array('category_id' => $category_id);
                    $this->db->delete('neigh_place_category', $data);
                }
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Category deleted successfully')));
            redirect_admin('neighbourhoods/viewcategory');
        }

        function visiter_name()
        {
            $val = $this->input->get('val');
            $result = $this->db->like('username', $val)->get('users');
            foreach ($result->result() as $row)
            {
                $user_name[] = $row->username;
            }
            echo json_encode($user_name);
        }

        function addpost()
        {
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {

                $this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
                $this->form_validation->set_rules('place', 'place', 'required|trim|xss_clean');
                $this->form_validation->set_rules('image_title', 'image title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('image_desc', 'image description', 'required|trim|xss_clean');


                if ($this->form_validation->run())
                {
                    $city = $this->input->post('city');
                    $place = $this->input->post('place');
                    $image_title = $this->input->post('image_title');
                    $image_desc = $this->input->post('image_desc');
                    $is_featured = $this->input->post('is_home');
                    /*   $visitor_type = $this->input->post('visitor');
                      $visitor_name = $this->input->post('visitor_name');
                      $visitor_review = $this->input->post('visitor_review'); */

                    if ($_FILES["big_image1"]["size"] != 0 && $_FILES["small_image1"]["size"] != 0 && $_FILES["small_image2"]["size"] != 0 && $_FILES["small_image3"]["size"] != 0 && $_FILES["small_image4"]["size"] != 0 && $_FILES["small_image5"]["size"] != 0 && $_FILES["big_image2"]["size"] != 0 && $_FILES["big_image3"]["size"] != 0)
                    {

                        $city_id = $this->db->where('city_name', $city)->get('neigh_city')->row()->id;

                        $place_id = $this->db->where('place_name', $place)->get('neigh_city_place')->row()->id;

                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('big_image1'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('small_image1'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('small_image2'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('small_image3'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('big_image1'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('small_image4'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('small_image5'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('big_image2'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        elseif (!$this->upload->do_upload('big_image3'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin('neighbourhoods/addpost');
                        }
                        else
                        {

                            $data7['big_image1'] = $this->remove_space($_FILES["big_image1"]["name"]);
                            $data7['small_image1'] = $this->remove_space($_FILES["small_image1"]["name"]);
                            $data7['small_image2'] = $this->remove_space($_FILES["small_image2"]["name"]);
                            $data7['small_image3'] = $this->remove_space($_FILES["small_image3"]["name"]);
                            $data7['small_image4'] = $this->remove_space($_FILES["small_image4"]["name"]);
                            $data7['small_image5'] = $this->remove_space($_FILES["small_image5"]["name"]);
                            $data7['big_image2'] = $this->remove_space($_FILES["big_image2"]["name"]);
                            $data7['big_image3'] = $this->remove_space($_FILES["big_image3"]["name"]);

                            $data7['city'] = $city;
                            $data7['place'] = $place;
                            $data7['image_title'] = $image_title;
                            $data7['image_desc'] = $image_desc;
                            $data7['created'] = time();
                            $data7['is_featured'] = $is_featured;
                            /* $data7['visitor_type'] = $visitor_type;  
                              $data7['visitor_name'] = $visitor_name;
                              $data7['visitor_review'] = $visitor_review; */

                            if ($this->db->insert('neigh_post', $data7))
                            {
                                //print_r($this->upload->data());exit;
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Neighborhood Place Post Added Successfully')));
                                redirect_admin('neighbourhoods/viewpost');
                            }
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                        redirect_admin('neighbourhoods/addpost');
                    }

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Neighborhood Place Post Added Successfully')));
                    redirect_admin('neighbourhoods/viewpost');
                }
            }

            //Load View
            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city_place');
            $data['places'] = $this->db->get('neigh_city_place');
            $data['message_element'] = "administrator/neighbourhoods/addpost";
            $this->load->view('administrator/admin_template', $data);
        }

        function remove_space($filename)
        {
            $ex = explode(' ', $filename);
            return implode('_', $ex);
        }

        function editpost()
        {
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {
                $this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
                $this->form_validation->set_rules('place', 'place', 'required|trim|xss_clean');
                $this->form_validation->set_rules('image_title', 'image title', 'required|trim|xss_clean');
                $this->form_validation->set_rules('image_desc', 'image description', 'required|trim|xss_clean');


                if ($this->form_validation->run())
                {
                    $city = $this->input->post('city');
                    $place = $this->input->post('place');
                    $image_title = $this->input->post('image_title');
                    $image_desc = $this->input->post('image_desc');
                    $is_featured = $this->input->post('is_home');
                    /* $visitor_type = $this->input->post('visitor');
                      $visitor_name = $this->input->post('visitor_name');
                      $visitor_review = $this->input->post('visitor_review'); */

                    $city_name = $this->db->where('id', $this->uri->segment(4))->get('neigh_post')->row()->city;

                    $city_id = $this->db->where('city_name', $city_name)->get('neigh_city')->row()->id;

                    $place_name = $this->db->where('id', $this->uri->segment(4))->get('neigh_post')->row()->place;

                    $place_id = $this->db->where('place_name', $place_name)->get('neigh_city_place')->row()->id;

                    $updateKey = $this->uri->segment(4);

                    if ($_FILES["big_image1"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('big_image1'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['big_image1'] = $this->remove_space($_FILES["big_image1"]["name"]);

                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    if ($_FILES["small_image1"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('small_image1'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['small_image1'] = $this->remove_space($_FILES["small_image1"]["name"]);
                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    if ($_FILES["small_image2"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('small_image2'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['small_image2'] = $this->remove_space($_FILES["small_image2"]["name"]);
                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    if ($_FILES["small_image3"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('small_image3'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['small_image3'] = $this->remove_space($_FILES["small_image3"]["name"]);

                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    if ($_FILES["small_image4"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('small_image4'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['small_image4'] = $this->remove_space($_FILES["small_image4"]["name"]);

                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    if ($_FILES["small_image5"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('small_image5'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['small_image5'] = $this->remove_space($_FILES["small_image5"]["name"]);

                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    if ($_FILES["big_image2"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('big_image2'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['big_image2'] = $this->remove_space($_FILES["big_image2"]["name"]);

                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    if ($_FILES["big_image3"]["name"] != '')
                    {
                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}",
                            'remove_spaces' => TRUE
                        );
                        $this->load->library('upload', $config);

                        if (!$this->upload->do_upload('big_image3'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editpost/$id");
                        }
                        else
                        {
                            $image_data['big_image3'] = $this->remove_space($_FILES["big_image3"]["name"]);
                            $this->db->where('id', $updateKey)->update('neigh_post', $image_data);
                        }
                    }

                    /* 	if(isset($_FILES["big_image1"]["name"]) && isset($_FILES["small_image1"]["name"]) && isset($_FILES["small_image2"]["name"])
                      && isset($_FILES["small_image3"]["name"]) && isset($_FILES["small_image4"]["name"]) && isset($_FILES["small_image5"]["name"])
                      && isset($_FILES["big_image2"]["name"]) && isset($_FILES["big_image3"]["name"]))
                      {



                      $config = array(
                      'allowed_types' => 'jpg|jpeg|gif|png',
                      'upload_path' => "./images/neighbourhoods/{$city_id}/{$place_id}"
                      );
                      $this->load->library('upload', $config);

                      if(!$this->upload->do_upload('big_image1'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('small_image1'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('small_image2'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('small_image3'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('big_image1'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('small_image4'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('small_image5'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('big_image2'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      elseif(!$this->upload->do_upload('big_image3'))
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      }
                      else{



                      $data7['city']            = $city;
                      $data7['place']           = $place;
                      $data7['image_title']     = $image_title;
                      $data7['created']         = time();
                      $data7['is_featured']     = $is_featured;
                      $data7['image_desc']      = $image_desc;
                      /* $data7['visitor_type']    = $visitor_type;
                      $data7['visitor_name']    = $visitor_name;
                      $data7['visitor_review']  = $visitor_review;

                      $updateKey 							= $this->uri->segment(4);

                      $this->db->where('id',$updateKey)->update('neigh_post', $data7);
                      }
                      }
                      else
                      {
                      $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error','Please Upload Correct File'));
                      redirect_admin("neighbourhoods/editpost/$id");
                      } */
                    $data7['city'] = $city;
                    $data7['place'] = $place;
                    $data7['image_title'] = $image_title;
                    $data7['created'] = time();
                    $data7['is_featured'] = $is_featured;
                    $data7['image_desc'] = $image_desc;

                    $this->db->where('id', $updateKey)->update('neigh_post', $data7);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Neighborhood Place Post Added Successfully')));
                    redirect_admin('neighbourhoods/viewpost');
                }
            }

            //Load View
            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city_place');
            $data['posts'] = $this->db->where('id', $id)->get('neigh_post');

            if ($data['posts']->num_rows() == 0)
            {
                redirect('info');
            }

            $data['places'] = $this->db->get('neigh_city_place');
            $data['message_element'] = "administrator/neighbourhoods/editpost";
            $this->load->view('administrator/admin_template', $data);
        }

        function viewpost()
        {
            $data['posts'] = $this->db->get('neigh_post');
            $data['cities'] = $this->db->get('neigh_city');
            //Load View	
            $data['message_element'] = "administrator/neighbourhoods/viewpost";
            $this->load->view('administrator/admin_template', $data);
        }

        public function deletepost()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {

                $this->load->model('place_model');
                $getplace = $this->place_model->getplace();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $place_name = $this->db->where('id', $res)->get('neigh_post')->row()->place;

                        $place_id = $this->db->where('place_name', $place_name)->get('neigh_city_place')->row()->id;
                        $saved_neigh = array('place_id' => $place_id);
                        $this->db->delete('saved_neigh', $saved_neigh);
                        $condition = array('id' => $res);
                        $this->db->delete('neigh_post', $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any Neighborhood Place Post')));
                    redirect_admin('neighbourhoods/viewpost');
                }
            }
            else
            {
                $place_name = $this->db->where('id', $id)->get('neigh_post')->row()->place;

                $place_id = $this->db->where('place_name', $place_name)->get('neigh_city_place')->row()->id;
                $saved_neigh = array('place_id' => $place_id);
                $this->db->delete('saved_neigh', $saved_neigh);
                $condition = array('id' => $id);
                $this->db->delete('neigh_post', $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Neighborhood Place Post deleted successfully')));
            redirect_admin('neighbourhoods/viewpost');
        }

        function place_drop()
        {
            $city = $this->input->get('city');
            $result = $this->db->select('place_name')->where('city_name', $city)->get('neigh_city_place');
            echo "<select name='place' style='width:292px'>";
            if ($result->num_rows() != 0)
            {
                foreach ($result->result() as $row)
                {
                    echo "<option value='" . $row->place_name . "'>" . $row->place_name . "</option>";
                }
            }
            else
            {
                echo "<option value='no'>" . 'No Places' . "</option>";
            }
            echo "</select>";
        }

        function rooms_title_drop()
        {
            $id = $this->input->get('id');
            $result = $this->db->select('title')->where('id', $id)->get('list');
            if ($result->num_rows() != 0)
            {
                echo '<input type="text" name="room_title" id="rooms_title" value="' . $result->row()->title . '" style="width:292px" readonly>';
            }
            else
            {
                echo '<input type="text" name="room_title" id="rooms_title" value="No Title" style="width:292px" readonly>';
            }
        }

        function addphotographer()
        {
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {

                $this->form_validation->set_rules('city', 'city', 'required|trim|xss_clean');
                $this->form_validation->set_rules('place', 'place', 'required|trim|xss_clean');
                $this->form_validation->set_rules('photo_grapher_name', 'photographer name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('photo_grapher_desc', 'photographer description', 'required|trim|xss_clean');


                if ($this->form_validation->run())
                {
                    $city = $this->input->post('city');
                    $place = $this->input->post('place');
                    $photo_grapher_name = $this->input->post('photo_grapher_name');
                    $photo_grapher_desc = $this->input->post('photo_grapher_desc');
                    $photo_grapher_web = $this->input->post('photo_grapher_web');
                    if ($_FILES["photo_grapher_image"]["name"] != '')
                    {

                        if (isset($_FILES["photo_grapher_image"]["name"]))
                        {
                            $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/neighbourhoods/photographer/';
                            $result = $this->db->where('photographer_name', $photo_grapher_name)->from('neigh_photographer')->get()->num_rows();
                            if ($result == 0)
                            {
                                if (!is_dir($path . '/' . $photo_grapher_name))
                                {
                                    mkdir($path . '/' . $photo_grapher_name, 0777, true);
                                }
                                else
                                {
                                    rmdir($path . "/{$photo_grapher_name}");
                                    mkdir($path . '/' . $photo_grapher_name, 0777, true);
                                }
                            }

                            $config = array(
                                'allowed_types' => 'jpg|jpeg|gif|png',
                                'upload_path' => "./images/neighbourhoods/photographer/{$photo_grapher_name}/"
                            );
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);

                            if (!$this->upload->do_upload('photo_grapher_image'))
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                                redirect_admin('neighbourhoods/viewphotographer');
                            }
                            else
                            {

                                $Data['city'] = $city;
                                $Data['place'] = $place;
                                $Data['photographer_name'] = $photo_grapher_name;
                                $Data['photographer_desc'] = $photo_grapher_desc;
                                $Data['photographer_image'] = $this->remove_space($_FILES["photo_grapher_image"]["name"]);
                                $Data['photographer_web'] = $photo_grapher_web;

                                $city_id = $this->db->where('city_name', $city)->get('neigh_city_place')->row()->id;
                                if ($city_id)
                                {
                                    $Data['city_id'] = $city_id;
                                    $Data['created'] = time();
                                    $Data['is_featured'] = $this->input->post('is_home');

                                    $this->db->insert('neigh_photographer', $Data);
                                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Photographer updated successfully')));
                                    redirect_admin('neighbourhoods/viewphotographer');
                                }
                                else
                                {
                                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('This Photographer Already Used')));
                                    redirect_admin('neighbourhoods/addphotographer');
                                }
                            }
                        }
                    }
                }
            }
            //Load View
            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city_place');
            $data['places'] = $this->db->get('neigh_city_place');
            $data['message_element'] = "administrator/neighbourhoods/addphotographer";
            $this->load->view('administrator/admin_template', $data);
        }

        function editphotographer()
        {

            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {

                $city = $this->input->post('city');
                $place = $this->input->post('place');
                $photo_grapher_name = $this->input->post('photo_grapher_name');
                $photo_grapher_desc = $this->input->post('photo_grapher_desc');
                $photo_grapher_web = $this->input->post('photo_grapher_web');

                if ($_FILES["photo_grapher_image"]["name"] != '')
                {

                    if (isset($_FILES["photo_grapher_image"]["name"]))
                    {
                        $path = dirname($_SERVER['SCRIPT_FILENAME']) . '/images/neighbourhoods/photographer/';
                        $result = $this->db->where('photographer_name', $photo_grapher_name)->from('neigh_photographer')->get()->num_rows();
                        if ($result == 0)
                        {
                            if (!is_dir($path . '/' . $photo_grapher_name))
                            {
                                mkdir($path . '/' . $photo_grapher_name, 0777, true);
                            }
                            else
                            {
                                rmdir($path . "/{$photo_grapher_name}");
                                mkdir($path . '/' . $photo_grapher_name, 0777, true);
                            }
                        }

                        $config = array(
                            'allowed_types' => 'jpg|jpeg|gif|png',
                            'upload_path' => "./images/neighbourhoods/photographer/{$photo_grapher_name}/"
                        );
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload('photo_grapher_image'))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please Upload Correct File')));
                            redirect_admin("neighbourhoods/editphotographer/$id");
                        }
                        else
                        {

                            $Data['city'] = $city;
                            $Data['place'] = $place;
                            $Data['photographer_name'] = $photo_grapher_name;
                            $Data['photographer_desc'] = $photo_grapher_desc;
                            $Data['photographer_image'] = $this->remove_space($_FILES["photo_grapher_image"]["name"]);
                            $Data['photographer_web'] = $photo_grapher_web;

                            $city_id = $this->db->where('city_name', $city)->get('neigh_city_place')->row()->id;
                            if ($city_id)
                            {
                                $Data['city_id'] = $city_id;
                                $Data['created'] = time();
                                $Data['is_featured'] = $this->input->post('is_home');

                                $id = $this->uri->segment(4);

                                $this->db->where('id', $id)->update('neigh_photographer', $Data);
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Photographer updated successfully')));
                                redirect_admin('neighbourhoods/viewphotographer');
                            }
                            else
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('This Photographer Already Used')));
                                redirect_admin("neighbourhoods/editphotographer/$id");
                            }
                        }
                    }
                }
                else
                {
                    $Data['city'] = $city;
                    $Data['place'] = $place;
                    $Data['photographer_name'] = $photo_grapher_name;
                    $Data['photographer_desc'] = $photo_grapher_desc;
                    $updateid = $this->uri->segment(4);
                    $Data['photographer_image'] = $this->db->where('id', $updateid)->from('neigh_photographer')->get()->row()->photographer_image;
                    $Data['photographer_web'] = $photo_grapher_web;

                    $city_id = $this->db->where('city_name', $city)->get('neigh_city_place')->row()->id;
                    if ($city_id)
                    {
                        $Data['city_id'] = $city_id;
                        $Data['created'] = time();
                        $Data['is_featured'] = '1';
                        $updateid = $this->uri->segment(4);
                        $this->db->where('id', $updateid)->update('neigh_photographer', $Data);
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Photographer updated successfully')));
                        redirect_admin('neighbourhoods/viewphotographer');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('This Photographer Already Used')));
                        redirect_admin("neighbourhoods/editphotographer/$id");
                    }
                }
            }
            //Load View
            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city_place');
            $data['photographers'] = $this->db->where('id', $id)->get('neigh_photographer');
            if ($data['photographers']->num_rows() == 0)
            {
                redirect('info');
            }
            $data['places'] = $this->db->get('neigh_city_place');
            $data['message_element'] = "administrator/neighbourhoods/editphotographer";
            $this->load->view('administrator/admin_template', $data);
        }

        function viewphotographer()
        {
            $data['photographers'] = $this->db->get('neigh_photographer');
            $data['cities'] = $this->db->get('neigh_city');
            $data['places'] = $this->db->get('neigh_city_place');
            $data['message_element'] = "administrator/neighbourhoods/viewphotographer";
            $this->load->view('administrator/admin_template', $data);
        }

        public function deletephotographer()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {

                $this->load->model('place_model');
                $getplace = $this->place_model->getplace();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('id' => $res);
                        $this->db->delete('neigh_photographer', $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any Photographer')));
                    redirect_admin('neighbourhoods/viewphotographer');
                }
            }
            else
            {
                $condition = array('id' => $id);
                $this->db->delete('neigh_photographer', $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Photographer deleted successfully')));
            redirect_admin('neighbourhoods/viewphotographer');
        }

        function checked_category()
        {
            extract($this->input->post());
            $result = $this->db->where('id', $edit_id)->get('neigh_city_place')->row()->place_name;

            $cate = $this->db->where('place', $result)->get('neigh_place_category');
            if ($cate->num_rows() != 0)
            {
                foreach ($cate->result() as $row)
                {
                    $id[] = $row->category_id;
                }
                echo json_encode($id);
            }
        }

        function addtag()
        {
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {

                $this->form_validation->set_rules('tag', 'tag', 'required|trim|xss_clean|min_length[4]|max_length[15]');


                if ($this->form_validation->run())
                {
                    extract($this->input->post());

                    $data['tag'] = $tag;
                    $data['shown'] = $is_shown;
                    $data['user_id'] = $this->dx_auth->get_user_id();
                    $data['city'] = $city;
                    $data['place'] = $place;
                    $data['city_id'] = $this->Neighbourhoods_model->city_id($city);
                    $data['place_id'] = $this->Neighbourhoods_model->place_id($place);

                    $check = $this->db->where('tag', $tag)->get('neigh_tag');
                    if ($check->num_rows() == 0)
                    {
                        if ($this->Neighbourhoods_model->addtag($data))
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Tag added successfully')));
                            redirect_admin('neighbourhoods/viewtag');
                        }
                        else
                        {
                            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Tag not added successfully')));
                            redirect_admin('neighbourhoods/addtag');
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate_admin('This tag already used.Please use other words.')));
                        redirect_admin('neighbourhoods/addtag');
                    }
                }
            }
            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city_place');
            $data['places'] = $this->db->get('neigh_city_place');
            $data['message_element'] = "administrator/neighbourhoods/addtag";
            $this->load->view('administrator/admin_template', $data);
        }

        function viewtag()
        {
            $data['tags'] = $this->db->get('neigh_tag');
            $data['message_element'] = "administrator/neighbourhoods/viewtag";
            $this->load->view('administrator/admin_template', $data);
        }

        function edittag()
        {
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;


            if ($this->input->post('submit'))
            {
                $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

                $this->form_validation->set_rules('tag', 'tag', 'required|trim|xss_clean|min_length[4]|max_length[15]');


                if ($this->form_validation->run())
                {
                    extract($this->input->post());

                    $data['tag'] = $tag;
                    $data['shown'] = $is_shown;
                    $data['user_id'] = $this->db->where('id', $id)->get('neigh_tag')->row()->user_id;
                    $data['city'] = $city;
                    $data['place'] = $place;
                    $data['city_id'] = $this->Neighbourhoods_model->city_id($city);
                    $data['place_id'] = $this->Neighbourhoods_model->place_id($place);

                    //$check = $this->db->where('tag',$tag)->where('id',$id)->get('neigh_tag');
                    // if($check->num_rows() == 1)
                    // {
                    if ($this->Neighbourhoods_model->updatetag($data, $id))
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Tag updated successfully')));
                        redirect_admin('neighbourhoods/viewtag');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Tag not updated successfully')));
                        redirect_admin('neighbourhoods/viewtag');
                    }
                    //	}
//else {
//	    $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','This tag already used.Please use other words.'));
//		redirect_admin('neighbourhoods/viewtag');
//}
                }
            }

            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city');

            $data['tags'] = $this->db->where('id', $id)->get('neigh_tag');

            if ($data['tags']->num_rows() == 0)
            {
                redirect('info');
            }

            $data['places'] = $this->db->get('neigh_city_place');
            $data['message_element'] = "administrator/neighbourhoods/edittag";
            $this->load->view('administrator/admin_template', $data);
        }

        public function deletetag()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {

                //$this->load->model('place_model');
                //	$getplace	 =	$this->place_model->getplace();
                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('id' => $res);

                        $this->db->delete('neigh_tag', $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any Tag')));
                    redirect_admin('neighbourhoods/viewtag');
                }
            }
            else
            {
                $condition = array('id' => $id);

                $this->db->delete('neigh_tag', $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Tag deleted successfully')));
            redirect_admin('neighbourhoods/viewtag');
        }

        function viewknowledge()
        {
            $data['knowledges'] = $this->db->get('neigh_knowledge');
            $data['message_element'] = "administrator/neighbourhoods/viewknowledge";
            $this->load->view('administrator/admin_template', $data);
        }

        function addknowledge()
        {
            $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

            if ($this->input->post('submit'))
            {

                $this->form_validation->set_rules('knowledge', 'knowledge', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    extract($this->input->post());

                    $data['knowledge'] = $knowledge;
                    $data['shown'] = $is_shown;
                    $data['user_id'] = $this->dx_auth->get_user_id();
                    $data['post_id'] = $post_id;
                    $result = $this->db->where('id', $post_id)->get('neigh_post');
                    $data['city'] = $result->row()->city;
                    $data['place'] = $result->row()->place;
                    $data['city_id'] = $this->Neighbourhoods_model->city_id($data['city']);
                    $data['place_id'] = $this->Neighbourhoods_model->place_id($data['place']);

                    $data['user_type'] = $this->input->post('user_type');
                    $data['room_id'] = $this->input->post('room_id');
                    $data['room_title'] = $this->input->post('room_title');

                    if ($this->db->insert('neigh_knowledge', $data))
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Knowledge added successfully')));
                        redirect_admin('neighbourhoods/viewknowledge');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Knowledge not added successfully')));
                        redirect_admin('neighbourhoods/addknowledge');
                    }
                }
            }
            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city_place');
            $data['places'] = $this->db->get('neigh_city_place');
            $data['rooms'] = $this->db->where('user_id', $this->dx_auth->get_user_id())->order_by("id", "asc")->get('list');
            $data['posts'] = $this->db->get('neigh_post');
            $data['message_element'] = "administrator/neighbourhoods/addknowledge";
            $this->load->view('administrator/admin_template', $data);
        }

        function check_city($city)
        {
            if ($city == 'Select City' || $city == 'No Place')
            {
                exit;
                $this->form_validation->set_message('check_city', 'The %s field can not be the word "test"');
                return false;
            }
            else
            {
                return true;
            }
        }

        function editknowledge()
        {
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;


            if ($this->input->post('submit'))
            {
                $this->form_validation->set_error_delimiters('<p style="clear:both;color: #FF0000;"><label>&nbsp;</label>', '</p>');

                $this->form_validation->set_rules('knowledge', 'knowledge', 'required|trim|xss_clean|min_length[6]');


                if ($this->form_validation->run())
                {
                    extract($this->input->post());

                    $data['knowledge'] = $knowledge;
                    $data['shown'] = $is_shown;
                    $data['user_id'] = $this->db->where('id', $id)->get('neigh_knowledge')->row()->user_id;
                    $data['post_id'] = $post_id;
                    $result = $this->db->where('id', $post_id)->get('neigh_post');
                    $data['city'] = $result->row()->city;
                    $data['place'] = $result->row()->place;

                    $data['city_id'] = $this->Neighbourhoods_model->city_id($data['city']);
                    $data['place_id'] = $this->Neighbourhoods_model->place_id($data['place']);

                    $data['user_type'] = $this->input->post('user_type');
                    $data['room_id'] = $this->input->post('room_id');
                    $data['room_title'] = $this->input->post('room_title');

                    if ($this->Neighbourhoods_model->updateknowledge($data, $id))
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Knowledge updated successfully')));
                        redirect_admin('neighbourhoods/viewknowledge');
                    }
                    else
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Knowledge not updated successfully')));
                        redirect_admin('neighbourhoods/viewknowledge');
                    }
                }
            }

            $data['cities'] = $this->db->distinct()->select('city_name')->get('neigh_city');

            $check = $this->db->where('id', $id)->get('neigh_knowledge');

            if ($check->num_rows() == 0)
            {
                redirect('info');
            }
            else
            {
                $data['knowledges'] = $this->db->where('id', $id)->get('neigh_knowledge');
            }
            $data['user_id'] = $this->db->where('id', $id)->get('neigh_knowledge')->row()->user_id;
            $data['rooms'] = $this->db->where('user_id', $data['user_id'])->order_by("id", "asc")->get('list');
            $data['places'] = $this->db->get('neigh_city_place');
            $data['posts'] = $this->db->get('neigh_post');
            $data['message_element'] = "administrator/neighbourhoods/editknowledge";
            $this->load->view('administrator/admin_template', $data);
        }

        function check_user_type()
        {
            extract($this->input->get());
            echo $this->db->where('id', $id)->get('neigh_knowledge')->row()->user_type;
            exit;
        }

        public function deleteknowledge()
        {
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {

                $pagelist = $this->input->post('pagelist');
                if (!empty($pagelist))
                {
                    foreach ($pagelist as $res)
                    {
                        $condition = array('id' => $res);

                        $this->db->delete('neigh_knowledge', $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any local knowledge')));
                    redirect_admin('neighbourhoods/viewknowledge');
                }
            }
            else
            {
                $condition = array('id' => $id);

                $this->db->delete('neigh_knowledge', $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Local Knowledge deleted successfully')));
            redirect_admin('neighbourhoods/viewknowledge');
        }

        function get_room_id()
        {
            extract($this->input->get());
            $place = $this->db->where('id', $post_id)->get('neigh_post')->row()->place;
            $result = $this->db->select('id')->where('user_id', $user_id)->like('address', $place)->get('list');
            //	print_r($result->result());exit;
            if ($result->num_rows() != 0)
            {
                echo '<select name="post_id" id="post_id" style="width:292px" onchange="get_room_id(this.value)">';
                foreach ($result->row() as $row)
                {
                    echo '<option>' . $row . '</option>';
                }
                echo '</select>';
            }
            else
            {
                echo 'Empty';
            }
        }

        function check_the_lists()
        {
            extract($this->input->get());
            $place = $this->db->where('id', $post_id)->get('neigh_post')->row()->place;
            $result = $this->db->select('id')->like('address', $place)->get('list');
            //	print_r($result->result());exit;
            if ($result->num_rows() != 0)
            {
                echo '<select name="room_id" id="room_id" style="width:292px" onchange="get_room_id(this.value)">';
                foreach ($result->row() as $row)
                {
                    echo '<option>' . $row . '</option>';
                }
                echo '</select>';
            }
            else
            {
                echo 'Empty';
            }
        }

    }

?>
