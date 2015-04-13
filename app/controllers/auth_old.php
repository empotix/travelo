<?php

    /**
     * DROPinn Auth Controller Class
     *
     * It helps to show the user account details
     *
     * @package     Dropinn
     * @subpackage  Controllers
     * @category    Auth
     * @author      Cogzidel Product Team
     * @version     Version 1.6
     * @link        http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Auth extends CI_Controller
    {

        public $min_username = 4;
        public $max_username = 20;
        public $min_password = 4;
        public $max_password = 20;

        public function Auth()
        {
            parent::__construct();

            $this->load->library("Form_validation");
            $this->load->library("DX_Auth");
            $this->load->helper("url");
            $this->load->helper("form");
            $this->load->library("session");
            $this->load->model("Users_model");
            $this->load->model("dx_auth/user_temp", "user_temp");
            $this->load->model("dx_auth/login_attempts", "login_attempts");
        }

        public function index()
        {
            $this->login();
        }

        public function username_check($username)
        {
            $result = $this->dx_auth->is_username_available($username);
            if (!$result)
            {
                $this->form_validation->set_message("username_check", "Username already exist. Please choose another username.");
            }
            return $result;
        }

        public function email_check($email)
        {
            $result = $this->dx_auth->is_email_available($email);
            if (!$result)
            {
                $this->form_validation->set_message("email_check", "Email is already used by another user. Please choose another email address.");
            }
            return $result;
        }

        public function captcha_check($code)
        {
            $result = TRUE;
            if ($this->dx_auth->is_captcha_expired())
            {
                $this->form_validation->set_message("captcha_check", "Your confirmation code has expired. Please try again.");
                $result = FALSE;
            }
            else
            {
                if (!$this->dx_auth->is_captcha_match($code))
                {
                    $this->form_validation->set_message("captcha_check", "Your confirmation code does not match the one in the image. Try again.");
                    $result = FALSE;
                }
            }
            return $result;
        }

        public function recaptcha_check()
        {
            $result = $this->dx_auth->is_recaptcha_match();
            if (!$result)
            {
                $this->form_validation->set_message("recaptcha_check", "Your confirmation code does not match the one in the image. Try again.");
            }
            return $result;
        }

        public function login()
        {
            $val = $this->form_validation;
            if ($this->input->post())
            {
                $val->set_rules("usernameli", "Username", "trim|required|xss_clean");
                $val->set_rules("passwordli", "Password", "trim|required|xss_clean");
                $val->set_rules("remember", "Remember me", "integer");
                if ($this->form_validation->run())
                {
                    if ($this->config->item("DX_login_using_username") && $this->config->item("DX_login_using_email"))
                    {
                        $get_user_function = "get_login";
                    }
                    else
                    {
                        if ($this->config->item("DX_login_using_email"))
                        {
                            $get_user_function = "get_user_by_email";
                        }
                        else
                        {
                            $get_user_function = "get_user_by_username";
                        }
                    }
                    $query = $this->Users_model->get_user_function($login);
                    if ($query && $query->num_rows() == 1)
                    {
                        $row = $val;
                        if ($row->banned > 0)
                        {
                            $this->session->set_flashdata("flash_message", $this->Common_model->admin_flash_message("error", "Login failed! you are banned"));
                            redirect_admin("login", "refresh");
                        }
                        else
                        {
                            $password = $this->dx_auth->_encode($password);
                            $stored_hash = $row->password;
                            if (crypt($password, $stored_hash) === $stored_hash)
                            {
                                $this->dx_auth->_set_session($row, "ALLOW");
                                if ($row->newpass)
                                {
                                    $this->Users_model->clear_newpass($row->id);
                                }
                                if ($remember)
                                {
                                    $this->dx_auth->_create_autologin($row->id);
                                }
                                $this->dx_auth->_set_last_ip_and_last_login($row->id);
                                $this->dx_auth->_clear_login_attempts();
                                $this->dx_auth_event->user_logged_in($row->id);
                                $this->session->set_flashdata("flash_message", $this->Common_model->admin_flash_message("success", "Logged in successfully."));
                                redirect_admin("", "refresh");
                            }
                            else
                            {
                                $this->session->set_flashdata("flash_message", $this->Common_model->admin_flash_message("error", "Login failed! Incorrect username or password"));
                                redirect_admin("login", "refresh");
                            }
                        }
                    }
                    else
                    {
                        $this->session->set_flashdata("flash_message", $this->Common_model->admin_flash_message("error", "Login failed! Incorrect username or password"));
                        redirect_admin("login", "refresh");
                    }
                }
            }
            $data['message_element'] = "administrator/view_login";
            $data['auth_message'] = "You are already logged in.";
            $this->load->view("administrator/admin_template", $data);
        }

        public function logout()
        {
            $this->dx_auth->logout();
            $data['auth_message'] = "You have been logged out.";
            $this->load->view($this->dx_auth->logout_view, $data);
        }

        public function cancel_account()
        {
            if ($this->dx_auth->is_logged_in())
            {
                $val = $this->form_validation;
                $val->set_rules("password", "Password", "trim|required|xss_clean");
                if ($val->run() && $this->dx_auth->cancel_account($val->set_value("password")))
                {
                    redirect_admin("", "location");
                }
                else
                {
                    $this->load->view($this->dx_auth->cancel_account_view);
                }
            }
            else
            {
                $this->dx_auth->deny_access("login");
            }
        }

        public function custom_permissions()
        {
            if ($this->dx_auth->is_logged_in())
            {
                echo "My role: " . $this->dx_auth->get_role_name() . "<br/>";
                echo "My permission: <br/>";
                if ($this->dx_auth->get_permission_value("edit") != NULL && $this->dx_auth->get_permission_value("edit"))
                {
                    echo "Edit is allowed";
                }
                else
                {
                    echo "Edit is not allowed";
                }
                echo "<br/>";
                if ($this->dx_auth->get_permission_value("delete") != NULL && $this->dx_auth->get_permission_value("delete"))
                {
                    echo "Delete is allowed";
                }
                else
                {
                    echo "Delete is not allowed";
                }
            }
        }

    }

?>