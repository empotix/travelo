<?php

    error_reporting(0);

    class Maintenance extends CI_Controller
    {

        function __construct()
        {
            parent::__construct();

            $this->load->database();

            $this->config_data->db_config_fetch();

            //Manage site Status 
            if ($this->config->item('site_status') != 1)
            {
                redirect('');
            }

            /**
             * ----------------------------------
             * CONTROLLER WIDE MAINTENANCE MODE
             * ----------------------------------
             */
            // $this->maintenance_mode->set(TRUE, '01.23.45.67');
        }

        function index()
        {

            //$this->maintenance_mode->set(FALSE, '01.23.45.67'); // no need to comment this out, just set it to FALSE and it will be ignored.
            // this method() will display an error when called because the maintenance mode is ON in the __construct().

            /**
             * --------------------------------
             * LOAD VIEWS
             * --------------------------------
             */
            $this->load->view('maintenance');
        }

    }
    