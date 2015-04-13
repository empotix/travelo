<?php
    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Neighbourhoods extends CI_Controller
    {

        public function Neighbourhoods()
        {
            parent::__construct();

            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->helper('cookie');
            $this->load->model('Users_model');
            $this->load->model('Email_model');
            $this->load->model('Common_model');
            $this->load->model('Neighbourhoods_model');
        }

        public function city($city1 = '', $places = '')
        {
            //$city1 = $this->uri->segment(3);
            //echo $this->Common_model->city_name($city1);
            if ($this->db->where('id', $city1)->get('neigh_city')->num_rows() == 0)
            {
                redirect('info/deny');
            }
            $city = $this->Common_model->city_name($city1);

            if ($places == 'places')
            {
                if ($this->input->post('filter'))
                {
                    if ($this->input->post('category_id'))
                    {
                        extract($this->input->post());
                        $category_id1 = array();
                        $ex = explode(',', $category_id);
                        foreach ($ex as $row)
                        {
                            $category_id1[] = $row;
                        }
                        if (count($category_id1) > 0)
                        {
                            $join = '';
                            $i = 1;
                            $this->db->distinct()->select('t0.place')->from('neigh_place_category t0');
                            foreach ($category_id1 as $row)
                            {
                                $this->db->join("neigh_place_category t$i", "t0.place=t$i.place")->where("t$i.category_id", $row);
                                $i++;
                            }
                            $this->db->where('t0.city', $city);
                            $this->db->join('neigh_post', 'neigh_post.place=t0.place');
                            $cate_place = $this->db->get();
                            if ($cate_place->num_rows() != 0)
                            {
                                foreach ($cate_place->result() as $place_cate)
                                {
                                    $final_place_cate[] = $place_cate->place;
                                }
                                $neighbourhoods = $this->db->where_in('place_name', $final_place_cate)->get('neigh_city_place');
                            }
                            else
                            {
                                ?>

                                <div id="places" class="section">
                                    <div class="recommendations-wrapper">
                                        <div id="recommendations">
                                            <div class="container">
                                                <h3 style="margin-top:2%;font-size:18px;">
                                                    <span class="results">0 <?php echo translate('neighbourhoods match'); ?>.</span> 
                                                    <a href="<?php echo base_url() . 'search?location=' . $city; ?>" target="_blank"><?php echo translate('See all listings'); ?></a>
                                                </h3>
                                                <p></p>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <?php
                                exit;
                            }
                        }
                        ?>

                        <div class="section" id="places">
                            <div class="recommendations-wrapper">
                                <div id="recommendations">
                                    <div class="container">
                                        <?php
                                        //$city1 = $this->uri->segment(3);
                                        $city = $this->Common_model->city_name($city1);

                                        $count = 0;

                                        $count = $neighbourhoods->num_rows();
                                        $explode = explode(',', $category_id);
                                        $category = array();
                                        foreach ($explode as $row)
                                        {
                                            $category[] = $this->db->where('id', $row)->get('neigh_category')->row()->category;
                                        }
                                        ?>
                                        <h3 style="margin-top:2%;font-size:18px;">
                                            <span class="results"><?php
                                                echo $count . ' ' . translate('neighbourhoods match') . ' ';
                                                foreach ($category as $row)
                                                    echo '"' . $row . '".';
                                                ?></span> 
                                            <a target="_blank" href="<?php echo base_url() . 'search?location=' . $city ?>"><?php echo translate('See all listings'); ?></a>
                                        </h3>
                                        <p></p>
                                    </div>
                                </div>

                            </div>
                            <div id="neighborhood_tiles" class="container hidden" style="display: block;">
                                <ul class="trait-neighborhoods neighborhoods">

                                    <?php
                                    if ($neighbourhoods->num_rows() != 0)
                                    {
                                        foreach ($neighbourhoods->result() as $row)
                                        {
                                            ?>

                                            <li style="display: list-item;" data-neighborhood-permalink="kings-cross" data-neighborhood-id="1805" class="trait-tile tile ">
                                                <div class="photo">
                                                    <h3 class="shiftbold"><a class="" href="<?php echo base_url() . 'neighbourhoods/city_detail/' . $row->city_id . '/' . $row->id; ?>"><?php echo $row->place_name; ?></a></h3>
                                                    <a class="" href="<?php echo base_url() . 'neighbourhoods/city_detail/' . $row->city_id . '/' . $row->id; ?>">
                                                        <img width="315" height="210" src="<?php echo base_url() . '/images/neighbourhoods/' . $row->city_id . '/' . $row->id . '/' . $row->image_name; ?>" alt="Kings Cross - London"></a>
                                                </div>
                                                <div class="blurb">
                                                    <p>
                                                        <?php
                                                        if ($row->quote)
                                                        {
                                                            echo $row->quote;
                                                        }
                                                        else
                                                        {
                                                            echo translate('No Quote');
                                                        }
                                                        ?>.</p>
                                                    <ul class="tags">
                                                        <?php
                                                        $category_place = $this->db->where('city', $row->city_name)->where('place', $row->place_name)->get('neigh_tag');

                                                        foreach ($category_place->result() as $row)
                                                        {
                                                            echo '<li>' . $row->tag . '</li>';
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                                <div style="display: none;" class="sub friends">
                                                    <ul>
                                                    </ul>
                                                    <p>
                                                    </p>
                                                </div>
                                            </li>


                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        echo translate('No Neighbourhood Places');
                                    }

                                    echo'</ul>

    </div></div>';
                                }
                                else
                                {
                                    ?>
                                    <div id="places" class="section">
                                        <div class="recommendations-wrapper">
                                            <div id="recommendations">
                                                <div class="container">
                                                    <h3 style="margin-top:2%;font-size:18px;">
                                                        <span class="results">0 <?php echo translate('neighbourhoods match'); ?>.</span> 
                                                        <a href="<?php echo base_url() . 'search?location=' . $city; ?>" target="_blank"><?php echo translate('See all listings'); ?></a>
                                                    </h3>
                                                    <p></p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                            else
                            {

                                $data['cities'] = $this->db->where('city_name', $city)->get('neigh_city');

                                $post_place = $city;

                                $this->db->distinct()->select('neigh_city_place.place_name')->where('neigh_city_place.is_featured', 1)->where('neigh_city_place.city_name', $city);
                                $this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name');
                                $this->db->from('neigh_city_place');
                                $place_ = $this->db->get();

                                if ($place_->num_rows() != 0)
                                {
                                    foreach ($place_->result() as $row)
                                    {
                                        $city_place[] = $row->place_name;
                                    }
                                    $data['all_places'] = $this->db->where_in('place_name', $city_place)->get('neigh_city_place');
                                }
                                $this->db->select('*')->where('neigh_city_place.is_featured', 1)->where('neigh_city_place.city_name', $city);
                                $this->db->from('neigh_city_place');

                                $data['places'] = $this->db->get();


                                $data['place_category'] = $this->db->select('*')->from('neigh_place_category')->where('neigh_place_category.city', $city)->join('neigh_post', 'neigh_post.place=neigh_place_category.place')->get();

                                $data['categories'] = $this->db->get('neigh_category');
                                if ($data['places']->num_rows() != 0)
                                {
                                    $place = $data['places']->row()->place_name;
                                }

                                $places = $this->db->distinct()->select('category_id')->where('city', $city)->limit(1)->get('neigh_place_category');

                                foreach ($places->result() as $row)
                                {
                                    foreach ($data['categories']->result() as $row_cat)
                                    {

                                        if ($row->category_id == $row_cat->id)
                                        {
                                            $data['first_category'] = $row_cat->category;
                                        }
                                    }
                                }
                                // $city = $this->uri->segment(3);

                                if (isset($data['first_category']))
                                {

                                    $category_id = $this->db->where('category', $data['first_category'])->get('neigh_category')->row()->id;


                                    $this->db->where('neigh_place_category.category_id', $category_id)->where('neigh_place_category.city', $city);
                                    $this->db->join('neigh_city_place', 'neigh_place_category.place=neigh_city_place.place_name');
                                    $this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name');
                                    $this->db->select('neigh_place_category.place');
                                    $this->db->from('neigh_place_category');
                                    $city_place_ = $this->db->get();

                                    if ($city_place_->num_rows() != 0)
                                    {
                                        foreach ($city_place_->result() as $row)
                                        {
                                            $city_p[] = $row->place;
                                        }
                                        $data['neighbourhoods'] = $this->db->where_in('place_name', $city_p)->get('neigh_city_place');
                                    }

                                    $post_place = $city;

                                    $count = $this->db->where('category_id', $category_id)->where('city', $post_place)->get('neigh_place_category')->num_rows();
                                }
                                $data['title'] = get_meta_details('Neighbourhoods', 'title');
                                $data["meta_keyword"] = get_meta_details('Neighbourhoods', 'meta_keyword');
                                $data["meta_description"] = get_meta_details('Neighbourhoods', 'meta_description');
                                $data['message_element'] = "neighbourhoods/city_places";
                                $this->load->view('template', $data);
                            }
                        }
                        else
                        {
                            //$city = $this->Common_model->city_name($city1);

                            $data['cities'] = $this->db->where('city_name', $city)->get('neigh_city');

                            $this->db->distinct()->select('neigh_city_place.place_name')->where('neigh_city_place.is_featured', 1)->where('neigh_city_place.city_name', $city);
                            $this->db->from('neigh_city_place');
                            $this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name');
                            $place_n = $this->db->limit(3)->get();

                            if ($place_n->num_rows() != 0)
                            {
                                foreach ($place_n->result() as $row)
                                {
                                    $place_ne[] = $row->place_name;
                                }
                                $data['index_places'] = $this->db->where_in('place_name', $place_ne)->get('neigh_city_place');
                            }

                            $this->db->distinct()->select('neigh_city_place.place_name')->where('neigh_city_place.is_featured', 1)->where('neigh_city_place.city_name', $city);
                            $this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name');
                            $this->db->from('neigh_city_place');
                            $place_ = $this->db->get();

                            if ($place_->num_rows() != 0)
                            {
                                foreach ($place_->result() as $row)
                                {
                                    $city_place[] = $row->place_name;
                                }
                                $data['all_places'] = $this->db->where_in('place_name', $city_place)->get('neigh_city_place');
                            }

                            $data['categories'] = $this->db->get('neigh_category');

                            $data['title'] = get_meta_details('Neighbourhoods', 'title');
                            $data["meta_keyword"] = get_meta_details('Neighbourhoods', 'meta_keyword');
                            $data["meta_description"] = get_meta_details('Neighbourhoods', 'meta_description');
                            $data['message_element'] = "neighbourhoods/city";
                            //print_r($data['cities']->result());exit;
                            $this->load->view('template', $data);
                        }
                    }

                    function category_count()
                    {
                        extract($this->input->post());

                        $city1 = $this->uri->segment(3);
                        $city = $this->Common_model->city_name($city1);

                        $category_id1 = array();
                        $ex = explode(',', $category_id);
                        foreach ($ex as $row)
                        {
                            $category_id1[] = $row;
                        }

                        $i = 1;
                        $this->db->distinct()->select('t0.place')->from('neigh_place_category t0');
                        foreach ($category_id1 as $row)
                        {
                            $this->db->join("neigh_place_category t$i", "t0.place=t$i.place")->where("t$i.category_id", $row);
                            $i++;
                        }
                        $this->db->where('t0.city', $city);
                        $this->db->join('neigh_post', 'neigh_post.place=t0.place');
                        $result = $this->db->get();

                        if ($result->num_rows() != 0)
                        {
                            $explode = explode(',', $category_id);
                            foreach ($explode as $row)
                            {
                                $count = $result->num_rows();
                                $arr[] = array('id' => $row, 'count' => $count);
                            }
                        }
                        else
                        {
                            $explode = explode(',', $category_id);
                            foreach ($explode as $row)
                            {
                                $res_cate = $this->db->where('id', $row)->get('neigh_category')->row()->category;

                                $count = $result->num_rows();
                                $arr[] = array('id' => $row, 'count' => $count);
                            }
                        }

                        $un_explode = explode(',', $un_check);
                        for ($i = 3; $i < count($un_explode); $i++)
                        {
                            if (is_numeric($un_explode[$i]))
                            {
                                $check = $this->db->where('city', $city)->get('neigh_post');
                                if ($check->num_rows() != 0)
                                {
                                    $post_place = $check->row()->place;

                                    $result1 = $this->db->distinct()->select('neigh_post.place')->where('neigh_place_category.city', $city)->where('neigh_place_category.category_id', $un_explode[$i])->join('neigh_post', 'neigh_post.place=neigh_place_category.place')->get('neigh_place_category');
                                }
                                if ($result1->num_rows() != 0)
                                {
                                    $count1 = $result1->num_rows();

                                    $arr1[] = array('id' => $un_explode[$i], 'count' => $count1);
                                }
                            }
                        }
                        if (isset($arr1))
                        {
                            $merge = array_merge($arr, $arr1);
                        }
                        else
                        {
                            $merge = $arr;
                        }

                        echo json_encode($merge);
                    }

                    function city_detail($city = '', $place_id = '')
                    {
                        $city = $this->Common_model->city_name($city);
                        $place = $this->Common_model->place_name($place_id);

                        if ($this->db->where('place', $place)->get('neigh_post')->num_rows() == 0)
                        {
                            redirect('info/deny');
                        }

                        $data['cities'] = $this->db->where('city_name', $city)->get('neigh_city');
                        $data['detail_place'] = $this->db->where('city', $city)->where('place', $place)->where('is_featured', 1)->get('neigh_post');

                        $data['categories'] = $this->db->get('neigh_category');

                        $this->db->distinct()->select('neigh_city_place.place_name')->where('neigh_city_place.is_featured', 1)->where('neigh_city_place.city_name', $city);
                        $this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name');
                        $this->db->from('neigh_city_place');
                        $place_ = $this->db->get();

                        if ($place_->num_rows() != 0)
                        {
                            foreach ($place_->result() as $row)
                            {
                                $city_place[] = $row->place_name;
                            }
                            $data['places'] = $this->db->where('id', $place_id)->get('neigh_city_place');
                        }
                        $data['lists'] = $this->db->like('address', $place)->where('is_featured', 1)->order_by('page_viewed')->get('list');

                        $this->db->select('*')->where('neigh_city_place.is_featured', 1)->where('neigh_city_place.city_name', $city)->where_not_in('neigh_city_place.city_name', $city);
                        ;
                        $this->db->from('neigh_city_place');
                        $this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name');
                        $data['index_places'] = $this->db->limit(3)->get();

                        $data['photographers'] = $this->db->where('city', $city)->where('place', $place)->where('is_featured', 1)->get('neigh_photographer');

                        $data['list_count'] = $this->db->like('address', $place)->get('list')->num_rows();

                        $data['place_map'] = $this->db->where('id', $place_id)->where('is_featured', 1)->get('neigh_city_place');

                        $data['tag'] = $this->db->where('city', $city)->where('place', $place)->where('shown', 1)->get('neigh_tag');

                        $data['knowledges'] = $this->db->where('city', $city)->where('place', $place)->where('shown', 1)->get('neigh_knowledge');

                        $data['title'] = get_meta_details('Neighbourhoods', 'title');
                        $data["meta_keyword"] = get_meta_details('Neighbourhoods', 'meta_keyword');
                        $data["meta_description"] = get_meta_details('Neighbourhoods', 'meta_description');
                        $data['message_element'] = "neighbourhoods/detail_place";
                        $this->load->view('template', $data);
                    }

                    function saved()
                    {
                        $place = $this->input->post('place');
                        $city = $this->input->post('city');

                        $ids = $this->db->where('city_name', $city)->where('place_name', $place)->get('neigh_city_place')->row();
                        $city_id = $ids->city_id;
                        $place_id = $ids->id;

                        if ($this->dx_auth->get_user_id())
                        {
                            $data = array('city_id' => $city_id, 'city' => $city, 'place_id' => $place_id, 'place' => $place, 'user_id' => $this->dx_auth->get_user_id());
                            $check = $this->db->where('place_id', $place_id)->where('user_id', $this->dx_auth->get_user_id())->get('saved_neigh');
                            if ($check->num_rows() == 0)
                            {
                                $this->db->insert('saved_neigh', $data);
                                $count = $this->db->where('city_id', $city_id)->where('user_id', $this->dx_auth->get_user_id())->get('saved_neigh');
                                echo $count->num_rows();
                            }
                            else
                            {
                                $result = $this->db->where('city', $city)->where('place', $place)->where('user_id', $this->dx_auth->get_user_id())->delete('saved_neigh');
                                $count = $this->db->where('city_id', $city_id)->where('user_id', $this->dx_auth->get_user_id())->get('saved_neigh');
                                echo $count->num_rows() . ',';
                            }
                        }
                        else
                        {
                            $count = $this->db->where('city_id', $city_id)->where('user_id', $this->dx_auth->get_user_id())->get('saved_neigh');
                            echo 'signin';
                        }
                    }

                    function suggest_tag()
                    {
                        $tag = $this->input->post('tag');
                        $tag = trim($tag);

                        $city1 = $this->input->get('city');

                        $place1 = $this->input->get('place');

                        $city = $this->Common_model->city_name($city1);

                        $place = $this->Common_model->place_name($place1);

                        //$ids = $this->db->where('city_name',$city)->where('place_name',$place)->get('neigh_city_place')->row();
                        $city_id = $this->input->get('city');
                        $place_id = $this->input->get('place');

                        if ($this->dx_auth->get_user_id())
                        {
                            $data = array('city_id' => $city_id, 'city' => $city, 'place_id' => $place_id, 'place' => $place, 'user_id' => $this->dx_auth->get_user_id(), 'tag' => $tag);
                            $check = $this->db->where('tag', $tag)->get('neigh_tag');
                            if ($check->num_rows() == 0)
                            {
                                $this->db->insert('neigh_tag', $data);
                                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Thanks for your tag suggestion! You might see it on the site soon.')));
                                redirect('neighbourhoods/city_detail/' . $city1 . '/' . $place1);
                            }
                            else
                            {
                                $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error', translate('This tag already used. Please use other words.')));
                                redirect('neighbourhoods/city_detail/' . $city1 . '/' . $place1);
                            }
                        }
                        else
                        {
                            //$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please Logged In.'));
                            redirect('users/signin');
                        }
                    }

                    function saved_delete()
                    {
                        $city = $this->input->post('city');
                        $place = $this->input->post('place');
                        $user_id = $this->dx_auth->get_user_id();

                        $result = $this->db->where('city', $city)->where('place', $place)->where('user_id', $user_id)->delete('saved_neigh');
                        if ($result)
                        {
                            $count = $this->db->where('city', $city)->where('user_id', $user_id)->get('saved_neigh');
                            echo $count->num_rows();
                        }
                    }

                    function saved_delete_remove()
                    {
                        $city = $this->input->post('city');
                        $place = $this->input->post('place');
                        $user_id = $this->dx_auth->get_user_id();

                        $result = $this->db->where('city', $city)->where('place', $place)->where('user_id', $user_id)->delete('saved_neigh');
                        if ($result)
                        {
                            $count = $this->db->where('city', $city)->where('user_id', $user_id)->get('saved_neigh');
                            //echo $count->num_rows();
                            if ($count->num_rows() != 0)
                            {
                                ?>

                                <ul class="saved_drop">
                                    <?php
                                    foreach ($count->result() as $save)
                                    {
                                        ?>
                                        <li>
                                            <a href="<?php echo base_url() . 'neighbourhoods/city_detail/' . $save->city_id . '/' . $save->place_id; ?>"><?php echo $save->place; ?></a>
                                            <a id="remove" href="#" onclick="remove_fun('<?php echo $save->place; ?>');" class="remove">x</a>
                                        </li>
                                    <?php } ?>
                                </ul>
                                <li>
                                    <a href="<?php echo base_url() . 'search?location=' . $place; ?>" target="_blank" class="to-p"><?php echo translate('See listings in all saved neighborhoods'); ?> »</a>
                                </li>

                                <?php
                            }
                        }
                    }

                    function local_knowledge()
                    {
                        $knowledge = $this->input->post('knowledge');
                        $knowledge = trim($knowledge);

                        $city1 = $this->input->get('city');
                        $place1 = $this->input->get('place');

                        $city = $this->Common_model->city_name($city1);
                        $place = $this->Common_model->place_name($place1);

                        $post_id = $this->db->where('city', $city)->where('place', $place)->get('neigh_post')->row()->id;

                        //$ids = $this->db->where('city_name',$city)->where('place_name',$place)->get('neigh_city_place')->row();
                        $city_id = $this->input->get('city');
                        $place_id = $this->input->get('place');

                        if ($this->dx_auth->get_user_id())
                        {
                            $data = array('post_id' => $post_id, 'city_id' => $city_id, 'city' => $city, 'place_id' => $place_id, 'place' => $place, 'user_id' => $this->dx_auth->get_user_id(), 'knowledge' => $knowledge, 'user_type' => 'Guest');

                            $this->db->insert('neigh_knowledge', $data);

                            $this->session->set_flashdata('flash_message', $this->Common_model->flash_message('success', translate('Thank you so much for contributing to this page! We do our best to build these community pages out from contributions like yours.')));
                            redirect('neighbourhoods/city_detail/' . $city1 . '/' . $place1);
                        }
                        else
                        {
                            //$this->session->set_flashdata('flash_message', $this->Common_model->flash_message('error','Please Logged In.'));
                            redirect('users/signin');
                        }
                    }

                }
            ?>
