<?php

    /**
     * DROPinn Admin List Controller Class
     *
     * helps to achieve common tasks related to the site like flash message formats,pagination variables.
     *
     * @package		DROPinn
     * @subpackage	Controllers
     * @category	Admin List
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    class Lists extends CI_Controller
    {

        function Lists()
        {
            parent::__construct();

            $this->load->library('Table');
            $this->load->library('Pagination');
            $this->load->library('form_validation');

            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('file');
            $this->load->helper('text');
            $this->load->model('Users_model');
            $this->load->model('Rooms_model');
            $this->load->model('Common_model');

            $this->path = realpath(APPPATH . '../images');

            // Protect entire controller so only admin, 
            // and users that have granted role in permissions table can access it.
            $this->dx_auth->check_uri_permissions();
        }

        function index()
        {
            $query = $this->db->get('list');

            // Get offset and limit for page viewing
            $start = (int) $this->uri->segment(4, 0);

            // Number of record showing per page
            $row_count = 10;

            if ($start > 0)
                $offset = ($start - 1) * $row_count;
            else
                $offset = $start * $row_count;


            // Get all users
            $data['users'] = $this->db->get('list', $row_count, $offset)->result();

            // Pagination config
            $p_config['base_url'] = admin_url('lists/index');
            $p_config['uri_segment'] = 4;
            $p_config['num_links'] = 5;
            $p_config['total_rows'] = $query->num_rows();
            $p_config['per_page'] = $row_count;

            // Init pagination
            $this->pagination->initialize($p_config);
            // Create pagination links
            $data['pagination'] = $this->pagination->create_links2();


            $data['message_element'] = "administrator/view_lists";
            $this->load->view('administrator/admin_template', $data);
        }

        function managelist()
        {
            $check = $this->input->post('check');
            if ($this->input->post('delete'))
            {
                if (empty($check))
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to select atleast one!')));
                    redirect_admin('lists');
                }
                foreach ($check as $id)
                {
                    $reservation_status = $this->Common_model->getTableData('reservation', array('list_id' => $id, 'status !=' => '10'));
                    if ($reservation_status->num_rows() > 0)
                    {
                        $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, The selected listing is in process or resevered by someone')));
                        redirect_admin('lists');
                    }
                    else
                    {
                        $this->db->delete('list', array('id' => $id));
                        $this->db->delete('list_photo', array('id' => $id));
                        $this->db->delete('price', array('id' => $id));
                        $this->db->delete('calendar', array('list_id' => $id));
                        $this->db->delete('messages', array('list_id' => $id));
                        $this->db->delete('referrals_booking', array('list_id' => $id));
                        $this->db->delete('reviews', array('list_id' => $id));
                        $this->db->delete('statistics', array('list_id' => $id));
                        $this->db->delete('contacts', array('list_id' => $id));
                        $this->db->delete('reservation', array('list_id' => $id));
                        $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate_admin('Rooms deleted successfully.')));
                    }
                }
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('List Deleted Successfully')));
                redirect_admin('lists');
            }
            else if ($this->input->post('featured'))
            {

                if (empty($check))
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to select atleast one!')));
                    redirect_admin('lists');
                }

                foreach ($check as $c)
                {
                    $this->Common_model->updateTableData('list', $c, NULL, array("is_featured" => '1'));
                }
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Updated Successfully')));
                redirect_admin('lists');
            }
            else if ($this->input->post('unfeatured'))
            {

                if (empty($check))
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to select atleast one!')));
                    redirect_admin('lists');
                }
                foreach ($check as $c)
                {
                    $sql = "update list set is_featured=0 where id='" . $c . "'";
                    $this->db->query($sql);
                }
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('List unfeatured Successfully')));
                redirect_admin('lists');
            }
            else if ($this->input->post('edit'))
            {
                if (empty($check))
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to select atleast one!')));
                    redirect_admin('lists');
                }
                if (count($check) == 1)
                {
                    $query = $this->db->get_where('list', array('id' => $check[0]));
                    $data['result'] = $query->row();

                    $data['amnities'] = $this->Rooms_model->get_amnities();

                    $data['property_types'] = $this->db->order_by('id', 'asc')->get('property_type');

                    $query3 = $this->db->get_where('price', array('id' => $check[0]));
                    $data['price'] = $query3->row();

                    $data['list_images'] = $this->Gallery->get_imagesG($check[0]);

                    $data['message_element'] = "administrator/view_edit_list";
                    $this->load->view('administrator/admin_template', $data);
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select any one list to edit!')));
                    redirect_admin('lists');
                }
            }
            else if ($this->input->post('update_desc'))
            {
                $listId = $this->input->post('list_id');

                $data = array(
                    'property_id' => $this->input->post('property_id'),
                    'room_type' => $this->input->post('room_type'),
                    'title' => $this->input->post('title'),
                    'desc' => $this->input->post('desc'),
                    'capacity' => $this->input->post('capacity'),
                    'bedrooms' => $this->input->post('bedrooms'),
                    'beds' => $this->input->post('beds'),
                    'bed_type' => $this->input->post('hosting_bed_type'),
                    'bathrooms' => $this->input->post('hosting_bathrooms'),
                    'house_rule' => $this->input->post('manual'),
                    'cancellation_policy' => $this->input->post('cancellation_policy'),
                    'address' => $this->input->post('address')
                );
                $address_explode = explode(',', $this->input->post('address'));
                if (count($address_explode) < 3)
                {
                    echo "<p>Please give the proper address.</p>";
                }
                else
                {
                    $this->db->where('id', $listId);
                    $this->db->update('list', $data);

                    echo "<p>List Description Updated Successfully</p>";
                }
            }
            else if ($this->input->post('update_aminities'))
            {
                $listId = $this->input->post('list_id');

                $amenity = $this->input->post('amenities');
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

                if ($amenities != '')
                {
                    $updateData['amenities'] = $amenities;
                }
                if ($amenities == '')
                {
                    echo "<p>Sorry, You have to select atleast one!</p>";
                    exit;
                }

                $updateKey = array('id' => $listId);
                $this->Rooms_model->update_list($updateKey, $updateData);

                echo "<p>List Aminities Updated Successfully</p>";
            }
            else if ($this->input->post('update_photo'))
            {
                $listId = $this->input->post('list_id');
                $images = $this->input->post('image');
                $is_main = $this->input->post('is_main');

                $fimages = $this->Gallery->get_imagesG($listId);
                if ($is_main != '')
                {
                    foreach ($fimages->result() as $row)
                    {
                        if ($row->id == $is_main)
                            $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 1));
                        else
                            $this->Common_model->updateTableData('list_photo', $row->id, NULL, array("is_featured" => 0));
                    }
                }

                if (!empty($images))
                {
                    foreach ($images as $key => $value)
                    {
                        $image_name = $this->Gallery->get_imagesG(NULL, array('id' => $value))->row()->name;
                        //		unlink($this->path.'/'.$listId.'/'.$image_name);

                        $conditions = array("id" => $value);
                        $this->Common_model->deleteTableData('list_photo', $conditions);
                    }
                }

                if (isset($_FILES["userfile"]["name"]))
                {
                    $insertData['list_id'] = $listId;

                    if (!is_dir($this->path . '/' . $listId))
                    {
                        mkdir($this->path . '/' . $listId, 0777, true);
                        $insertData['is_featured'] = 1;
                    }

                    $config = array(
                        'allowed_types' => 'jpg|jpeg|gif|png',
                        'upload_path' => $this->path . '/' . $listId,
                        'encrypt_name' => TRUE,
                        'remove_spaces' => TRUE
                    );

                    $this->load->library('upload', $config);
                    $this->upload->do_upload();
                    $this->outputData['file'] = $this->upload->data();
                    $insertData['name'] = $this->outputData['file']['file_name'];

                    if ($this->outputData['file']['file_name'] != '')
                        $this->Common_model->insertData('list_photo', $insertData);
                }

                $rimages = $this->Gallery->get_imagesG($listId);
                $i = 1;
                $replace = '<ul class="clearfix">';
                foreach ($rimages->result() as $rimage)
                {
                    if ($rimage->is_featured == 1)
                        $checked = 'checked="checked"';
                    else
                        $checked = '';

                    $url = base_url() . 'images/' . $rimage->list_id . '/' . $rimage->name;
                    $replace .= '<li><p><label><input type="checkbox" name="image[]" value="' . $rimage->id . '" /></label>';
                    $replace .= '<img src="' . $url . '" width="150" height="150" /><input type="radio" ' . $checked . ' name="is_main" value="' . $rimage->id . '" /></p></li>';
                    $i++;
                }
                $replace .= '</ul>';

                echo $replace . "#" . "<p>List Photo's Updated successfully</p>";
            }
            else if ($this->input->post('update_price'))
            {
                $listId = $this->input->post('list_id');

                $data = array(
                    'currency' => $this->input->post('currency'),
                    'night' => $this->input->post('nightly'),
                    'week' => $this->input->post('weekly'),
                    'month' => $this->input->post('monthly'),
                    'addguests' => $this->input->post('extra'),
                    'guests' => $this->input->post('guests'),
                    'security' => $this->input->post('security'),
                    'cleaning' => $this->input->post('cleaning')
                );

                $dataP = array();
                $dataP['price'] = $this->input->post('nightly');

                $this->db->where('id', $listId);
                $this->db->update('price', $data);

                $this->db->where('id', $listId);
                $this->db->update('list', $dataP);

                echo "<p>List Price Updated successfully</p>";
            }
            else
            {
                redirect_admin('lists');
            }
        }

        public function view_all()
        {
            //Get Groups
            $this->load->model('aminity_model');
            $data['aminity'] = $this->aminity_model->getaminity();

            //$data['area']   =   $this->place_model->getplace1();
            //Load View	
            $data['message_element'] = "administrator/aminity/view_aminity";
            $this->load->view('administrator/admin_template', $data);
        }

        function view_aminity()
        {


            $data['message_element'] = "administrator/view_add_aminity";
            $this->load->view('administrator/admin_template', $data);
        }

        public function editaminity()
        {

            $this->load->model('aminity_model');

            //Get id of the category	
            $id = is_numeric($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

            //Intialize values for library and helpers	
            $this->form_validation->set_error_delimiters($this->config->item('field_error_start_tag'), $this->config->item('field_error_end_tag'));

            if ($this->input->post('submit'))
            {
                //Set rules
                $this->form_validation->set_rules('name', 'Aminity Name', 'required|trim|xss_clean');
                $this->form_validation->set_rules('desc', 'Aminity Description', 'required|trim|xss_clean');

                if ($this->form_validation->run())
                {
                    //prepare update data
                    $updateData = array();
                    $updateData['name'] = $this->input->post('name');
                    $updateData['description'] = $this->input->post('desc');


                    //Edit Faq Category
                    $updateKey = array('amnities.id' => $this->uri->segment(4));

                    $this->aminity_model->updateaminity($updateKey, $updateData);

                    //Notification message
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Amenity updated successfully')));
                    redirect_admin('lists/view_all');
                }
            } //If - Form Submission End
            //Set Condition To Fetch The Faq Category
            $condition = array('amnities.id' => $id);

            //Get Groups
            $data['aminity'] = $this->aminity_model->getaminity($condition);

            //Load View	
            $data['message_element'] = "administrator/aminity/edit_aminity";
            $this->load->view('administrator/admin_template', $data);
        }

        public function delete_aminity()
        {
            $this->load->model('aminity_model');
            $id = $this->uri->segment(4, 0);

            if ($id == 0)
            {
                $getaminity = $this->aminity_model->getaminity();
                $aminitylist = $this->input->post('aminitylist');
                if (!empty($aminitylist))
                {
                    foreach ($aminitylist as $res)
                    {
                        $condition = array('amnities.id' => $res);
                        $this->aminity_model->deleteaminity(NULL, $condition);
                    }
                }
                else
                {
                    $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Please select Amenity')));
                    redirect_admin('lists/view_all');
                }
            }
            else
            {
                $condition = array('amnities.id' => $id);
                $this->aminity_model->deleteaminity(NULL, $condition);
            }
            //Notification message
            $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('success', translate_admin('Amenity deleted successfully')));
            redirect_admin('lists/view_all');
        }

        function addaminity()
        {
            $aminity = $this->input->post('addaminitie');
            $desc = $this->input->post('desc_aminitie');
            if (empty($aminity) && empty($desc))
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to select atleast one!')));
                redirect_admin('lists');
            }
            else
            {
                $nul = "NULL";
                $data = array(
                    'id' => NULL,
                    'name' => $this->input->post('addaminitie'),
                    'description' => $this->input->post('desc_aminitie')
                );
                $this->Common_model->insertData('amnities', $data);

                echo "<p>Additional Amenity added successfully</p>";
            }
        }

        function addaminities()
        {
            $aminity1 = $this->input->post('addaminitie');
            $desc1 = $this->input->post('desc_aminitie');
            if (empty($aminity1) && empty($desc1))
            {
                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Sorry, You have to fill all fields!')));
                redirect_admin('lists/view_aminity');
            }
            else
            {
                $nul = "NULL";
                $data = array(
                    'id' => NULL,
                    'name' => $this->input->post('addaminitie'),
                    'description' => $this->input->post('desc_aminitie')
                );
                $this->Common_model->insertData('amnities', $data);

                $this->session->set_flashdata('flash_message', $this->Common_model->admin_flash_message('error', translate_admin('Amenity added successfully!')));
                redirect_admin('lists/view_aminity');
            }
        }

    }

?>
