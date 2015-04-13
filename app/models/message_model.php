<?php

    /**
     * Dropinn Message_model Class
     *
     * Help to handle tables related to static Faqs of the system.
     *
     * @package        Message
     * @subpackage    Models
     * @category    Message_model 
     * @author        Cogzidel Product Team
     * @version        Version 1.5
     * @link        http://www.cogzidel.com

     */
    class Message_model extends CI_Model
    {

        /**
         * Constructor 
         *
         */
        function Message_model()
        {
            parent::__construct();
        }

//Controller End

        function sentMessage($insertData = array(), $isCoversation = 0)
        {
            if ($isCoversation != 0)
            {
                $userby = $insertData['userby'];
                $userto = $insertData['userto'];

                $query = $this->db->query("SELECT MAX(`conversation_id`) as conversation_id FROM `messages` WHERE (`userby` = '" . $userby . "' AND `userto` ='" . $userto . "') OR (`userby` = '" . $userto . "' AND `userto` ='" . $userby . "')");
                $row = $query->row();


                if ($query->num_rows() > 0 && $row->conversation_id != 0)
                {
                    $insertData['conversation_id'] = $row->conversation_id;
                }
                else
                {
                    $this->db->select_max('conversation_id');
                    $query = $this->db->get('messages');
                    $row = $query->row();
                    $insertData['conversation_id'] = $row->conversation_id + 1;
                }
            }

            $this->db->insert('messages', $insertData);
        }

        function updateMessage($updateKey = array(), $updateData = array())
        {
            $this->db->update('messages', $updateData, $updateKey);
        }

        function get_message($conditions = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            $this->db->from('messages');

            $result = $this->db->get();
            return $result;
        }

        function get_messages($conditions = array(), $or_where = array(), $orderby = array())
        {
            if (count($conditions) > 0)
                $this->db->where($conditions);

            if (count($or_where) > 0)
                $this->db->or_where($or_where);

            if (is_array($orderby) and count($orderby) > 0)
                $this->db->order_by($orderby[0], $orderby[1]);

            $this->db->from('messages');

            $this->db->join('message_type', 'messages.message_type = message_type.id', 'inner');
            $this->db->join('reservation', 'messages.reservation_id = reservation.id', 'left');
            $this->db->join('contacts', 'messages.contact_id = contacts.id', 'left');
            $this->db->join('users', 'messages.userby = users.id', 'right');
//$this->db->select('photo_status,email');
//$res = $this->db->get();
//$va = $res->result();
//foreach($







            $this->db->select('messages.id,messages.list_id,messages.reservation_id,messages.contact_id,messages.conversation_id,messages.userby,messages.userto,messages.subject,messages.message,messages.created,messages.is_read,messages.message_type,messages.is_starred,message_type.name,message_type.url,reservation.checkin,reservation.checkout,reservation.price,reservation.no_quest,contacts.checkin,contacts.checkout,users.username,users.email,users.photo_status');

            $result = $this->db->get();

//echo "<pre>";
//$value = $result->result();
//foreach($value as $val)
//{
//$data = $val->photo_status;
//$data = $val->photo_status;
//if($data == "2")
//{
//echo "sasi";
//$this->db->select('messages.id,messages.list_id,messages.reservation_id,messages.contact_id,messages.conversation_id,messages.userby,messages.userto,messages.subject,messages.message,messages.created,messages.is_read,messages.message_type,messages.is_starred,message_type.name,message_type.url,reservation.checkin,reservation.checkout,reservation.price,reservation.no_quest,contacts.checkin,contacts.checkout,users.username');
//}
//else
//{
//echo "Ajith";
            // $this->db->select('messages.id,messages.list_id,messages.reservation_id,messages.contact_id,messages.conversation_id,messages.userby,messages.userto,messages.subject,messages.message,messages.created,messages.is_read,messages.message_type,messages.is_starred,message_type.name,message_type.url,reservation.checkin,reservation.checkout,reservation.price,reservation.no_quest,contacts.checkin,contacts.checkout,users.username');
//}
//}
//exit;
//exit;
            return $result;
        }

        function reserve_notify($insertData = array(), $host_name, $traveller_name, $list_name)
        {
            $insertData['subject'] = 'You have a new reservation request from ' . ucfirst($traveller_name) .
                    /* $insertData['message'] = 'Dear '.$host_name.',
                      <br><br>
                      You have a new reservation request from '.$traveller_name.'. The booked property is: '.anchor('rooms/'.$insertData['list_id'],$list_name).'
                      <br>
                      Please click the following link to accept the booking
                      <br>
                      '.anchor('reservation/accept/'.$insertData['reservation_id'],'Accept your booking').'
                      <br><br>
                      Please click the following link to deny the booking <br>
                      '.anchor('reservation/deny/'.$insertData['reservation_id'],'Deny your booking').''; */

                    $this->db->insert('messages', $insertData);
        }

        /**
         * delete Faq
         *
         * @access    private
         * @param    array    an associative array of insert values
         * @return    void
         */
        function deleteMessage($id = 0, $conditions = array())
        {
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);
            else
                $this->db->where('id', $id);
            $this->db->delete('messages');
        }

//End of addFaqCategory Function
    }

?>
