<?php
    $old_password = array(
        'name' => 'old_password',
        'id' => 'old_password',
        'size' => 30,
            //'value' => set_value('old_password')
    );

    $new_password = array(
        'name' => 'new_password',
        'id' => 'new_password',
        'size' => 30
    );

    $confirm_new_password = array(
        'name' => 'confirm_new_password',
        'id' => 'confirm_new_password',
        'size' => 30
    );
?>

<!--  Required external style sheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<style>
    .red{
        color:#FF0000;
        float:right;}
    </style>
    <!-- End of style sheet inclusion -->

    <?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>

    <?php
        if ($this->uri->segment(3) != $this->dx_auth->get_user_id()):
            redirect('users/edit');
        else:
            ?> 

            <?php
            $query = $this->db->get_where('users', array('id' => $this->uri->segment(3)));
            $q = array();
            $q = $query->result();
            ?>
            <?php $this->load->view(THEME_FOLDER . '/includes/profile_header'); ?>	
            <div id="dashboard_container">
            <div id="dashboard_left">
                <div class="Box" id="das_user_box">
                    <div class="Box_Content">
                        <div id="user_pic" onclick="show_ajax_image_box();"> <img alt="" src="<?php echo $this->Gallery->profilepic($this->dx_auth->get_user_id(), 2); ?>" title=""  /> </div>
                        <h1>
                            <?php if (strlen($this->dx_auth->get_username()) > 14): ?>
                                <?php
                                $query = $this->db->get_where('profiles', array('id' => $this->dx_auth->get_user_id()));
                                $q5 = $query->result();
                                echo $q5[0]->Fname . ' ' . $q5[0]->Lname;
                                //	$this->dx_auth->get_username(); 
                                ?>
                            <?php else: ?>
                                <?php echo $this->dx_auth->get_username(); ?>
                            <?php endif; ?>
                        </h1>
                        <h3><span><?php echo anchor('users/edit', 'Edit Profile') ?></span></h3>
                        <!-- middle -->
                    </div>
                </div>
                <!-- /user -->
                <div class="Box" id="quick_links">
                    <div class="Box_Head msgbg">
                        <h2><?php echo translate("Quick Links"); ?></h2>
                    </div>
                    <div class="Box_Content">
                        <ul>
                            <li><a href=<?php echo base_url() . 'hosting'; ?>> <?php echo translate("View/Edit Listings"); ?></a></li>
                            <li><a href="<?php echo site_url('hosting/my_reservation'); ?>"><?php echo translate("Reservations"); ?></a></li>
                        </ul>
                    </div>
                    <div style="clear:both"></div>
                </div>
            </div>
            <!-- /left -->
            <div id="dashboard_main">
                <div class="Box" id="View_Change_Pass">
                    <div class="Box_Content">
                        <fieldset>

                            <?php echo form_open($this->uri->uri_string()); ?>

                            <?php echo $this->dx_auth->get_auth_error(); ?>

                            <dl>
                                <dt><?php echo form_label('Old Password', $old_password['id']); ?></dt>
                                <dd>
                                    <?php echo form_password($old_password); ?>
                                    <span class="red"><?php echo form_error($old_password['name']); ?></span>
                                </dd>

                                <dt><?php echo form_label('New Password', $new_password['id']); ?></dt>
                                <dd>
                                    <?php echo form_password($new_password); ?>
                                    <span class="red"><?php echo form_error($new_password['name']); ?></span>
                                </dd>

                                <dt><?php echo form_label('Confirm New Password', $confirm_new_password['id']); ?></dt>
                                <dd>
                                    <?php echo form_password($confirm_new_password); ?>
                                    <span class="red"><?php echo form_error($confirm_new_password['name']); ?></span>
                                </dd>

                                <dt><label>&nbsp;</label></dt>

                                <dd>
                                    <button type="submit" class="btn blue gotomsg" name="change"><span><span><?php echo translate("Change Password"); ?></span></span></button>
                                </dd>
                            </dl>

                            <?php echo form_close(); ?>
                        </fieldset>
                    </div>
                </div>
            </div><!-- /main -->
            <div class="clear"></div>
        </div><!-- /dashboard -->
        <!-- /command_center -->

        </div>
<?php endif; ?>
<script type="text/javascript">
    jQuery("#user_pic").hover(
            function () {
                jQuery('#edit_image_hover').fadeIn(100);
            },
            function () {
                jQuery('#edit_image_hover').fadeOut(100);
            }
    );

</script>

<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 1049231994;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "666666";
    var google_conversion_label = "0W9CCND30wEQ-oSo9AM";
    var google_conversion_value = 0;
    /* ]]> */
</script>

<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
    <img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1049231994/?label=0W9CCND30wEQ-oSo9AM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>



