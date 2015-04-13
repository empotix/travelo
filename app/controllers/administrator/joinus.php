<?php

    /**
     * Dropinn system page Class
     *
     * Permits admin to handle the FAQ System of the site
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Skills 
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @created		December 22 2008
     * @link		http://www.cogzidel.com

     */
    class Joinus extends CI_Controller
    {

        //Global variable  
        public $outputData;  //Holds the output data for each view

        /**
         * Constructor 
         *
         * Loads language files and models needed for this controller
         */

        function Joinus()
        {

            parent::__construct();


            //load validation library
            $this->load->library('form_validation');
            $this->load->model('common_model');

            //Load Form Helper
            $this->load->helper('form');
            $this->dx_auth->check_uri_permissions();
        }

//Controller End 
        // --------------------------------------------------------------------

        /**
         * Loads Faqs settings page.
         *
         * @access	private
         * @param	nil
         * @return	void
         */
        public function viewJoinus()
        {
            $site = array();
            $i = 1;
            $query = "select name,url from joinus";
            $sql = $this->db->query($query);
            $result = $sql->result_array();
            foreach ($result as $res)
            {
                $site[$i] = $res['url'];
                $i = $i + 1;
            }
            $data['twitter'] = $site[1];
            $data['facebook'] = $site[2];
            $data['google'] = $site[3];
            $data['youtube'] = $site[4];
            $data['message'] = "";
            $data['message_element'] = "administrator/viewJoinus";
            $this->load->view('administrator/admin_template', $data);
        }

        public function updateJoinus()
        {
            $twitter = $this->input->post('twitter');
            $fb = $this->input->post('facebook');
            $google = $this->input->post('google');
            $youtube = $this->input->post('youtube');
            $sql1 = "update joinus set url='" . $twitter . "' where name='Twitter'";
            $sql2 = "update joinus set url='" . $fb . "' where name='Facebook'";
            $sql3 = "update joinus set url='" . $google . "' where name='Google'";
            $sql4 = "update joinus set url='" . $youtube . "' where name='Youtube'";
            $this->db->query($sql1);
            $this->db->query($sql2);
            $this->db->query($sql3);
            $this->db->query($sql4);
            $site = array();
            $i = 1;
            $query = "select name,url from joinus";
            $sql = $this->db->query($query);
            $result = $sql->result_array();
            foreach ($result as $res)
            {
                $site[$i] = $res['url'];
                $i = $i + 1;
            }
            $data['twitter'] = $site[1];
            $data['facebook'] = $site[2];
            $data['google'] = $site[3];
            $data['youtube'] = $site[4];
            $data['message'] = translate_admin("Updated Successfully");
            $data['message_element'] = "administrator/viewJoinus";
            $this->load->view('administrator/admin_template', $data);
        }

        //Function end	
    }

//End  Page Class

/* End of file Page.php */ 
/* Location: ./app/controllers/admin/Page.php */
