<?php

    /**
     * DROPinn Message Controller Class
     *
     * It helps to do the message system
     *
     * @package		Dropinn
     * @subpackage	Controllers
     * @category	Message
     * @author		Cogzidel Product Team
     * @version		Version 1.6
     * @link		http://www.cogzidel.com

     */
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Message extends CI_Controller
    {

        public function Message()
        {
            parent::__construct();

            $this->load->helper('url');
            $this->load->helper('cookie');

            $this->load->model('Message_model');
            $this->facebook_lib->enable_debug(TRUE);
        }

        public function inbox()
        {
            if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
            {
                $conditions = array("messages.userto " => $this->dx_auth->get_user_id());
                $data['messages'] = $this->Message_model->get_messages($conditions, NULL, array('messages.id', 'desc'));
                //var_dump($data['messages']); exit;
                $data['title'] = get_meta_details('Inbox', 'title');
                $data["meta_keyword"] = get_meta_details('Inbox', 'meta_keyword');
                $data["meta_description"] = get_meta_details('Inbox', 'meta_description');

                $data['message_element'] = 'message/inbox';
                $this->load->view('template', $data);
            }
            else
            {
                redirect('users/signin');
            }
        }

        public function starred()
        {
            $message_id = $this->input->post('message_id');
            $to_change = $this->input->post('to_change');
            $updateKey = array('id' => $message_id);

            $updateData = array();
            $updateData['is_starred'] = $to_change;
            $this->Message_model->updateMessage($updateKey, $updateData);

            if ($to_change == 0)
            {
                echo translate("Message unstarred successfully.");
            }
            else
            {
                echo translate("Message starred successfully.");
            }
        }

        //Ajax page
        public function delete()
        {
            $message_id = $this->input->post('message_id');

            $this->Message_model->deleteMessage($message_id);

            $conditions = array("messages.userto " => $this->dx_auth->get_user_id());
            $data['messages'] = $this->Message_model->get_messages($conditions);

            //echo $this->load->view(THEME_FOLDER.'/message/ajax_inbox',$data);					

            echo "success";
        }

        public function is_read()
        {
            $message_id = $this->input->post('message_id');

            $updateKey = array('id' => $message_id);
            $updateData = array();
            $updateData['is_read '] = 1;
            $this->Message_model->updateMessage($updateKey, $updateData);
        }

    }

    /* End of file message.php */
    /* Location: ./app/controllers/message.php */
?>
