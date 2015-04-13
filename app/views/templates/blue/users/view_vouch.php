<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<style>
    #left {
        width: 259px;
        float:left;
    }
    #main {
        float: right;
        width: 692px;
    }
    #main p {
        padding:0 0 10px 0;
    }
    .clsH1_long_Border h1 {
        background:#F4F4F4;
        font-size:15px;
        padding:5px 10px;
        margin:0 0 10px 0;
        position:relative;
    }
    .clsH1_long_Border h1 span {
        position:absolute;
        right:10px;
        top:5px;
    }
</style>
<!-- End of style sheet inclusion -->
<div class="container_bg" id="View_Vouch">

    <div id="dashboard" class="clsDes_Top_Spac">
        <div>
            <div class="clsH1_long_Border">
                <?php //print_r($user->username);exit;?>
                <h1> <?php echo ucfirst($user->username); ?>
                    <?php if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
                        {
                            ?>
                            <span style="float:right;"><?php echo translate("Member from"); ?> <?php echo get_user_times($user->created, get_user_timezone()); ?></span> 
    <?php } ?>
                </h1>
            </div>
            <div style="clear:both"></div>
        </div>
        <div style="clear:both"></div>
        <div id="left">
            <div id="user_box" class="Box">
                <div class="Box_Content">
                    <div id="user_pic" onClick="show_ajax_image_box();" style="text-align:center; padding:0 0 10px 0;"> 

                        <img width="230" src="<?php echo $this->Gallery->profilepic($user->id, 2); ?>" />
                    </div>
                </div>
                <!-- middle -->
            </div>
            <!-- /user -->
        </div>
        <!-- /left -->
        <div id="main">
            <?php
                //if( ($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()) || ($this->twitter->is_logged_in()) ){
                if (($this->dx_auth->is_logged_in()) || ($this->facebook_lib->logged_in()))
                {
                    ?> 

                    <div class="Box">
                        <div class="Box_Head msgbg">
                            <h2><?php echo translate("Vouch for"); ?> <?php echo ucfirst($user->username); ?></h2>
                        </div>
                        <div class="Box_Content">
        <?php echo form_open('users/vouch/' . $this->uri->segment(3)); ?>
                            <p style="font-weight:normal; font-style:italic; font-size:16px;"> <?php echo translate("Please write a few sentences explaining why"); ?> &nbsp;<?php echo ucfirst($user->username); ?> &nbsp;<?php echo translate("is a great person."); ?> </p>
                            <p><?php echo translate("Enter your recommendation here and then click the Recommend button."); ?> </p>
                            <input type="hidden" name="userto" value="<?php echo $this->uri->segment(3); ?>">
                            <input type="hidden" name="userby" value="<?php echo $this->dx_auth->get_user_id(); ?>">
                            <p>
                                <textarea id="recommend" name="message" cols="75"> </textarea>
                            </p>
                            <span style="color:#FF0000"><?php echo form_error('message'); ?></span>
                            <p>
                                <button name="friends_recommend" class="btn blue gotomsg" type="submit"><span><span><?php echo translate("Recommend"); ?></span></span></button>
                            </p>
                    <?php echo form_close(); ?> 
                        </div>
                    </div>
    <?php } ?>
            <!--List-->
            <div class="Box">
                <div class="Box_Head msgbg">
                    <h2><?php echo translate("My Listing"); ?></h2>
                </div>
                <div class="Box_Content" style="padding:0 10px 10px 10px;">

                    <table id="user_result_list" width="100%">
                        <tbody>
                            <?php
                                if ($lists->num_rows() > 0)
                                {
                                    foreach ($lists->result() as $list)
                                    {
                                        ?>
                                        <tr class="even" id="room_<?php echo $list->id; ?>">
                                            <td class="place_image"><a class="thumbnail" href="<?php echo base_url() . 'rooms/' . $list->id; ?>"><img width="75" height="50" title="Test room" src="<?php echo getListImage($list->id); ?>" alt="Test room"><span><img width="100" height="100" title="Test room" src="<?php echo getListImage($list->id); ?>" alt="Test room"></span></a> </td>
                                            <td class="main"><div class="first-line title"><a href="<?php echo base_url() . 'rooms/' . $list->id; ?>"><?php echo $list->title; ?></a></div>
                                                <div><?php echo $list->address; ?></div></td>
                                        </tr>
                                    <?php
                                    }
                                }
                                else
                                {
                                    echo translate("There is no List");
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--List-->
            <!--Recommendation-->
            <div class="Box">
                <div class="Box_Head msgbg">
                    <h2><?php echo translate("Recommendations"); ?></h2>
                </div>

                <div class="Box_Content">

                    <table style="width:100%;" class="quotes" id="vouch_recom_tab">
                        <tbody>
                            <?php
                                if ($recommends->num_rows() > 0)
                                {
                                    foreach ($recommends->result() as $row)
                                    {
                                        if ($this->db->where('id', $row->userby)->get('users')->num_rows() != 0)
                                        {
                                            ?>
                                            <tr>
                                                <td width="82">
                                                    <div class="review_prof_img">
                                                        <a onclick="window.open(this.href);
                                                                return false;" href="<?php base_url(); ?>"><img width="76" height="76" title="Mahes W" src="<?php echo $this->Gallery->profilepic($row->userby, 1); ?>" alt="Mahes W"></a><a target="blank" href="<?php echo site_url('users/profile') . '/' . $row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a>
                                                    </div>
                                                </td>
                                                <td valign="top">
                                                    <div class="review_right_content">
                                            <?php echo $row->message; ?>
                                                        <span class="review_right_arrow"></span>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php
                                        }
                                    }
                                }
                                else
                                {
                                    echo '<p>' . translate("There is no Recommend") . '</p>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!--Recommendation-->
        <!-- /main -->
        <div class="clear"></div>
    </div>
    <!-- /dashboard -->
</div>
<!-- /command_center -->