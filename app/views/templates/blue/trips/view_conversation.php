<link href="<?php echo css_url() . '/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<div class="container_bg clearfix" id="Conversation_Blk" >
    <div class="Convers_left clsFloatLeft">
        <div class="Box">

            <div class="Box_Content">
                <div class="clsConever_Bred">
                    <ul class="clearfix">
                        <li><a href="<?php echo site_url('message/inbox'); ?>"><?php echo translate("Inbox"); ?></a></li>
                        <li><h3><?php echo translate("Conversation with"); ?> <?php echo ucfirst($conv_userData->username); ?>.</h3></li>
                    </ul>
                    <div style="clear:both"></div>
                </div>
                <div class="clsConver_Porfile_Blk clearfix">
                    <div class="clsConver_Pro_Img clsFloatLeft">
                        <a href="javascript:void(0);"><img height="68" width="68" src="<?php echo $this->Gallery->profilepic($conv_userData->id, 2); ?>" alt="Profile" /></a>
                    </div>
                    <div class="clsConverPro_Name_Des clsFloatLeft">
                        <h3><a href="<?php echo site_url('users/profile') . '/' . $conv_userData->id; ?>"><?php echo ucfirst($conv_userData->username); ?></a></h3>
                        <p><span><?php echo translate("Member since"); ?></span> <?php echo get_user_times($conv_userData->created, get_user_timezone()); ?></p>
                    </div>
                    <div style="clear:both"></div>
                </div>
                <div id="selconver_pane_inner">
                    <ul class="clearfix">
                        <li class="clearfix">
                            <div class="clsConSamll_Pro_Img clsFloatLeft">
                                <p><a href="<?php echo site_url('users/profile') . '/' . $conv_userData->id; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($this->dx_auth->get_user_id(), 2); ?>" /></a></p>
                                <p><?php echo translate("You"); ?></p>
                            </div>
                            <?php echo form_open('trips/conversation/' . $conversation_id); ?>
                            <div class="clsType_Conver clsFloatLeft">
                                <textarea name="comment"></textarea>
                                <input type="hidden" name="list_id" value="<?php echo $list_id; ?>" />
                                <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>" />
                                <input type="hidden" name="contact_id" value="<?php echo $contact_id; ?>" />
                                <input type="hidden" name="userto" value="<?php echo $conv_userData->id; ?>" />
                                <input type="hidden" name="userby" value="<?php echo $this->dx_auth->get_user_id(); ?>" />
                                <br />
                                <?php if (form_error('comment'))
                                    { ?>
                                        <?php echo form_error('comment'); ?>
    <?php } ?>
                                <p>
                                    <button name="submit" class="button1" type="submit"><span><span><?php echo translate("Send Message"); ?></span></span></button>
                                </p>
                                <span class="clsTypeCon_LArrow"></span>
                            </div>
<?php echo form_close(); ?>
                            <div style="clear:both"></div>
                        </li>
                        <?php foreach ($messages->result() as $message)
                            {

                                if ($message->userby == $this->dx_auth->get_user_id())
                                {
                                    ?>

                                    <li class="clearfix">
                                        <div class="clsConSamll_Pro_Img clsFloatLeft">
                                            <p><a href="<?php echo site_url('users/profile') . '/' . $message->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($message->userby, 2); ?>" /></a></p>
                                            <p><?php echo translate("You"); ?></p>
                                        </div>
                                        <div class="TypeConver_Ans clsFloatLeft">
                                                    <?php if ($message->message_type != 3)
                                                    { ?>
                                                <div class="TypeConver_Head">
                                                    <p>
                                                    <?php echo $message->name; ?> <?php echo translate("about"); ?> "<?php echo anchor('rooms/' . $message->list_id, get_list_by_id($message->list_id)->title); ?>"
                                                    </p>
                <?php if ($message->message_type != 6)
                { ?>
                                                        <p><label><?php echo get_user_times($message->checkin, get_user_timezone()) . ' - ' . get_user_times($message->checkout, get_user_timezone()); ?></label><label><?php echo $message->no_quest . ' ' . translate("guest"); ?></label></p>
                                                <?php } ?>
                                                    <span class="clsTypeConver_Arow1"></span>
                                                </div>
            <?php } ?>

                                            <?php if ($message->message_type == 3)
                                            { ?>
                                                <div class="TypeConver_Inner_SLft">
                                                    <p> <?php echo $message->message; ?> </p>
                                                    <span class="clsTypeCon_SLArrow"></span>
                                                </div>
            <?php }
            else
            { ?>
                                                <div class="TypeConver_Inner">
                                                    <p> <?php echo $message->message; ?> </p>
                                                </div>
            <?php } ?>

                                        </div>
                                    </li>

                                                <?php }
                                                else
                                                { ?>

                                    <li class="clearfix TypeAns_Right">
                                        <div class="TypeConver_Ans clsFloatLeft">
                                            <?php if ($message->message_type != 3)
                                            { ?>
                                                <div class="TypeConver_Head">
                                                    <p>
                <?php echo $message->name; ?> <?php echo translate("about"); ?> "<?php echo anchor('rooms/' . $message->list_id, get_list_by_id($message->list_id)->title); ?>"
                                                    </p>
                                                    <!--<?php if ($message->message_type != 6)
                { ?>
                                                        <p><label><?php echo get_user_times($message->checkin, get_user_timezone()) . ' - ' . get_user_times($message->checkout, get_user_timezone()); ?></label><label><?php echo $message->no_quest . ' ' . translate("guest"); ?></label></p>
                <?php } ?>-->
                                                    <span class="clsTypeConver_Arow1"></span>
                                                </div>
                                            <?php } ?>

            <?php if ($message->message_type == 3)
            { ?>
                                                <div class="TypeConver_Inner_SRgt">
                                                    <p> <?php echo $message->message; ?> </p>
                                                    <span class="clsTypeCon_SRArrow"></span>
                                                </div>
                                    <?php }
                                    else
                                    { ?>
                                                <div class="TypeConver_Inner">
                                                    <p> <?php echo $message->message; ?> </p>
                                                </div>
            <?php } ?>

                                        </div>
                                        <div class="clsConSamll_Pro_Img clsFloatLeft">
                                            <p><a href="<?php echo site_url('users/profile') . '/' . $message->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($message->userby, 2); ?>" /></a></p>
                                            <p><?php echo ucfirst(get_user_by_id($message->userby)->username); ?></p>
                                        </div>
                                    </li>

        <?php }
    } ?>
                    </ul>
                    <div style="clear:both"></div>
                </div>

            </div>

        </div>
    </div>
    <div class="Convers_Rgt clsFloatRight">
        <div class="Box" id="quick_links">
            <div class="Box_Head">
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
    <div style="clear:both"></div>
</div>
