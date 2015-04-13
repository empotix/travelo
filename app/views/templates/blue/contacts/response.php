<script src="<?php echo js_url(); ?>/jquery.countdown.js" type="text/javascript"></script>
<link href="<?php echo css_url() . '/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<div class="container_bg">
    <div id="Reserve_Continer">
        <div id="View_Request" class="clearfix">
            <div id="left">

                <!-- /user -->
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
            <div id="main_reserve">
                <div class="Box">
                    <div class="Box_Head">
                        <h2><?php echo "Contact Response"; ?> </h2></div>
                    <div class="Box_Content">
                        <ul id="details_breakdown_1" class="dashed_table_1 clearfix">
                            <li class="top clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Property"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_list_by_id($result->list_id)->title; ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check in"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo date("F j, Y", strtotime($checkin)); ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check out"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo date("F j, Y", strtotime($checkout)); ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Night"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo $nights; ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Guest"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo $no_quest; ?></span></span>
                            </li>
                            <?php if ($status == 4)
                                {
                                    ?>
                                    <li class="bottom">
                                        <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Message"); ?></span></span>
                                        <span class="data"><span class="inner"><?php echo $message; ?></span></span>
                                    </li>
                                <?php
                                }
                                else
                                {
                                    ?>
                                    <li class="clearfix">
                                        <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("URL for Booking"); ?></span></span>
                                        <span class="data"><span class="inner"><a href="<?php echo $url; ?>"><?php echo $url; ?></a></span></span>
                                    </li>
                                    <li class="bottom">
                                        <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Message"); ?></span></span>
                                        <span class="data"><span class="inner"><?php echo $message; ?></span></span>
                                    </li>	
    <?php } ?>	
                        </ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>