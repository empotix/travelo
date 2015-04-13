<?php

    class Gallery extends CI_Model
    {

        var $path;
        var $gallery_path_url;
        var $logopath;

        //Constructor
        function Gallery()
        {
            parent::__construct();
            $this->path = realpath(APPPATH . '../images');
            $this->gallery_path_url = base_url() . 'images/';
            $this->logopath = realpath(APPPATH . '../');
        }

        function do_upload($id)
        {
            if (!is_dir($this->path . '/' . $id))
            {
                //echo $this->path.'/'.$id;
                mkdir($this->path . '/' . $id, 0777, true);
            }
            $config = array(
                'allowed_types' => 'jpg|jpeg|gif|png',
                'upload_path' => $this->path . '/' . $id
            );
            //echo $this->path.'/'.$id;
            $this->load->library('upload', $config);
            $this->upload->do_upload();
        }

        function do_upload_logo()
        {
            
        }

        public function get_images($id, $conditions = array(), $limit = array())
        {
            $images = array();
            if (is_dir($this->path . '/' . $id))
            {
                $files = scandir($this->path . '/' . $id);
                $files = array_diff($files, array('.', '..'));
                foreach ($files as $file)
                {
                    if ($file != 'Thumbs.db')
                    {
                        $images [] = array('url' => $this->gallery_path_url . $id . '/' . $file, 'path' => $this->path . '/' . $id . '/' . $file);
                    }
                }
            }
            return $images;
        }

        public function get_imagesG($id, $conditions = array(), $limit = array(), $orderby = array())
        {
            if ($id != '')
                $this->db->where('list_id', $id);

            //Check For Conditions
            if (is_array($conditions) and count($conditions) > 0)
                $this->db->where($conditions);

            //Check For Limit	
            if (is_array($limit))
            {
                if (count($limit) == 1)
                    $this->db->limit($limit[0]);
                else if (count($limit) == 2)
                    $this->db->limit($limit[0], $limit[1]);
            }

            //Check for Order by
            if (is_array($orderby) and count($orderby) > 0)
                $this->db->order_by($orderby[0], $orderby[1]);

            $this->db->from('list_photo');

            $result = $this->db->get();

            return $result;
        }

        public function helper_image($id)
        {
            $images = $this->get_images($id);
            if (count($images) == 0)
                $url = base_url() . 'images/no_image.jpg';
            else
                $url = $images[0]['url'];
            return $url;
        }

        function Udo_upload($id)
        {
            if (!is_dir($this->path . '/users/' . $id))
            {
                //echo $this->path.'/'.$id;
                mkdir($this->path . '/users/' . $id, 0777, true);
            }
            $config = array(
                'allowed_types' => 'jpg|jpeg|gif|png',
                'upload_path' => $this->path . '/users/' . $id . '/'
            );
            //echo $this->path.'/users/'.$id;
            $this->load->library('upload', $config);
            $this->upload->do_upload();
        }

        public function Uget_images($id)
        {
            $images = array();
            if (is_dir($this->path . '/users/' . $id))
            {
                $files = scandir($this->path . '/users/' . $id);
                $files = array_diff($files, array('.', '..'));
                foreach ($files as $file)
                {
                    $images [] = array('url' => $this->gallery_path_url . $id . '/users/' . $file);
                }
            }
            return $images;
        }

        public function profilepic($id, $pos = 0)
        {
            $query = $this->db->where('id', $id)->get('users');
            $q = $query->result();
            //echo $this->db->last_query(); exit;
            if (isset($q[0]->username))
            {
                $username = $q[0]->username;
                $userfacebook = $q[0]->fb_id;
                $usertwitter = $q[0]->twitter_id;
                $email = $q[0]->email;
            }
            else
            {
                $username = '';
            }
            if (isset($userfacebook) && (strlen($userfacebook) > 1) && ($query->row()->photo_status == "2"))
            //if( (strlen($userfacebook) > 1))
            {
                $url = 'https://graph.facebook.com/' . $userfacebook . '/picture?type=large';
            }
            elseif (isset($usertwitter) && (strlen($usertwitter) > 1))
            //elseif((strlen( $usertwitter) > 1))
            {
                /*  $trends_url = "http://api.twitter.com/1/users/show.json?user_id=".$usertwitter."&include_entities=true";

                  $ch = curl_init();

                  curl_setopt($ch, CURLOPT_URL, $trends_url);

                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

                  $curlout = curl_exec($ch);

                  curl_close($ch);

                  $response = json_decode($curlout, true);

                  $image_url = $response['profile_image_url'];

                  //			echo $image_url;
                  $url = str_replace("_normal","",$image_url); */
                //	$url = $trends_url;

                $tw_url = $this->db->where('email', $email)->from('profile_picture')->get()->row()->src;
                $ext = $this->db->where('email', $email)->from('profile_picture')->get()->row()->ext;

                $url = $tw_url . $ext;
            }
            else
            {
                $images = array();
                if (is_dir($this->path . '/users/' . $id))
                {
                    $files = scandir($this->path . '/users/' . $id);
                    $files = array_diff($files, array('.', '..'));

                    foreach ($files as $file)
                    {
                        $db = explode('.', $file);
                        if ($db[1] == 'jpg')
                        {
                            $images [] = array('url' => $this->gallery_path_url . 'users/' . $id . '/' . $file);
                        }
                    }

                    if (count($images) >= 1)
                    {
                        if ($pos == 1)
                        {
                            $url = $this->gallery_path_url . 'users/' . $id . '/userpic_thumb.jpg';
                        }
                        else if ($pos == 2)
                        {
                            $url = $this->gallery_path_url . 'users/' . $id . '/userpic_profile.jpg';
                        }
                        else if ($pos == 3)
                        {
                            $url = $this->gallery_path_url . 'users/' . $id . '/userpic_home.jpg';
                        }
                        else
                        {
                            $url = $this->gallery_path_url . 'users/' . $id . '/userpic.jpg';
                        }
                    }
                    else
                    {
                        if ($pos == 1)
                        {
                            $url = base_url() . 'images/no_avatar_thumb.jpg';
                        }
                        else if ($pos == 2)
                        {
                            $url = base_url() . 'images/no_avatar-xlarge.jpg';
                        }
                        else if ($pos == 3)
                        {
                            $url = base_url() . 'images/no_avatar-xlarge_home.jpg';
                        }
                        else
                        {
                            $url = base_url() . 'images/no_avatar.jpg';
                        }
                    }
                }
                else
                {
                    if ($pos == 1)
                    {
                        $url = base_url() . 'images/no_avatar_thumb.jpg';
                    }
                    else if ($pos == 2)
                    {
                        $url = base_url() . 'images/no_avatar-xlarge.jpg';
                    }
                    else
                    {
                        $url = base_url() . 'images/no_avatar.jpg';
                    }
                }
            }
            return $url;
        }

    }

?>