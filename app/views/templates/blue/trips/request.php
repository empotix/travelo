<script src="<?php echo js_url(); ?>/jquery.countdown.js" type="text/javascript"></script>
<link href="<?php echo css_url() . '/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<?php
// Print The Confrmation
    $confirmation = '';
    $confirmation .= '<p style="padding:5px 5px 5px 725px"><a style="color:#38859B;cursor:pointer;" onClick="javascript:window.print();"><strong>' . translate('Print') . '</strong></a></p>';
    $confirmation .= '<table border="1" width="100%">';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Property") . '</td>';
    $confirmation .= '<td>' . get_list_by_id($result->list_id)->title . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Check in") . '</td>';
    $confirmation .= '<td>' . date("F j, Y", $result->checkin) . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Check out") . '</td>';
    $confirmation .= '<td>' . date("F j, Y", $result->checkout) . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Nights") . '</td>';
    $confirmation .= '<td>' . $nights . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Guests") . '</td>';
    $confirmation .= '<td>' . $no_quest . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Cancellation") . '</td>';
    $confirmation .= '<td>' . $policy . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Average Rate") . '( ' . translate("per night") . ' )' . '</td>';
    $confirmation .= '<td>' . get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $per_night) . '</tr>';
    $confirmation .= '</tr>';

    if ($cleaning != 0)
    {
        $confirmation .= '<tr>';
        $confirmation .= '<td>' . translate("Cleaning Fee") . '</td>';
        $confirmation .= '<td>' . get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $cleaning) . '</tr>';
        $confirmation .= '</tr>';
    }
    if ($security != 0)
    {
        $confirmation .= '<tr>';
        $confirmation .= '<td>' . translate("Security Fee") . '</td>';
        $confirmation .= '<td>' . get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $security) . '</tr>';
        $confirmation .= '</tr>';
    }
    if (isset($extra_guest_price))
    {
        if ($extra_guest_price != 0)
        {
            $confirmation .= '<tr>';
            $confirmation .= '<td>' . translate("Additional Guest Fee") . '</td>';
            $confirmation .= '<td>' . get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $extra_guest_price) . '</tr>';
            $confirmation .= '</tr>';
        }
    }

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Subtotal") . '</td>';
    $confirmation .= '<td>' . get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $subtotal) . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Total Payout") . '</td>';
    $confirmation .= '<td>' . get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $total_payout) . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Status") . '</td>';
    $confirmation .= '<td>' . translate($result->name) . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '</table>';
?>

<script type="text/javascript">
    function print_confirmation() {
        var myWindow;
        myWindow = window.open('', '_blank', 'width=800,height=500');
        myWindow.document.write("<p><?php echo addslashes($confirmation); ?></p>");
        myWindow.print();
    }
    $(document).ready(function ()
    {
        if ($('#expire').text() == '0:0:0')
        {
            $('#req_accept').hide();
            $('#req_decline').hide();
            $('#expired').show();
            $('#expired1').show();
            $('#expires_in').hide();
        }
    })
</script>

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
                        <h2><?php echo translate("Reservation Request"); ?> </h2><span class="View_MyPrint">
                            <a href="javascript:void(0);" onclick="javascript:print_confirmation();"><?php echo translate("Print Confirmation"); ?></a>
                        </span></div>
                    <div class="Box_Content">

                        <?php if ($result->status != 1)
                            { ?>

                            <?php } ?>
                        <ul id="details_breakdown_1" class="dashed_table_1 clearfix">
                            <li class="top clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Property"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_list_by_id($result->list_id)->title; ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check in"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo date("F j, Y", $result->checkin); ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Check out"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo date("F j, Y", $result->checkout); ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Nights"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo $nights; ?></span></span>
                            </li>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Guests"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo $no_quest; ?></span></span>
                            </li>

                            <li class="bottom">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Cancellation"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo $policy; ?></span></span>
                            </li>
                        </ul>


                        <ul id="details_breakdown_1" class="dashed_table_1 clearfix">

                            <li class="top clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Average Rate") . '( ' . translate("per night") . ' )'; ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $per_night); ?></span></span>
                            </li>
                            <?php
                                if ($cleaning != 0)
                                {
                                    ?>
                                    <li class="clearfix">
                                        <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Cleaning Fee"); ?></span></span>
                                        <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $cleaning); ?></span></span>
                                    </li>
                                <?php } ?>

                            <?php
                                if ($security != 0)
                                {
                                    ?>
                                    <li class="clearfix">
                                        <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Security Fee"); ?></span></span>
                                        <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $security); ?></span></span>
                                    </li>
                                <?php } ?>

                            <?php
                                if (isset($extra_guest_price))
                                {
                                    if ($extra_guest_price != 0)
                                    {
                                        ?>
                                        <li class="clearfix">
                                            <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Additional Guest Fee"); ?></span></span>
                                            <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $extra_guest_price); ?></span></span>
                                        </li>
        <?php }
    }
?>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Subtotal"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $subtotal); ?></span></span>
                            </li>

                            <!-- <li class="clearfix">
                            <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Host fee"); ?></span></span>
                            <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $commission); ?></span></span>
                            </li> -->


                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Total Payout"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $subtotal); ?></span></span>
                            </li>
                            <li class="clearfix bottom">
                                <span class="label" ><span class="inner"><span class="checkout_icon" id="icon_cal"></span>
                                        <?php if ($result->status == 1)
                                            { ?>
                                                <span id="expires_in">
                                                <?php echo translate("Expires in"); ?></span>
                                                <div id="expired1" style="display: none"><?php echo translate('Expired'); ?></div>
                                                <?php
                                                $hourdiff = round((time() - $result->book_date) / 3600, 0);
                                                $reservation_id = $this->uri->segment(3);
                                                /*
                                                  if($hourdiff > 24)
                                                  {
                                                  $reservation_id   	= $this->uri->segment(3);
                                                  $is_block								 	= $this->input->post('is_block');
                                                  $comment 								 	= $this->input->post('comment');

                                                  $checkin 								 	= $this->input->post('checkin');
                                                  $checkout 								 = $this->input->post('checkout');

                                                  $admin_email 						= $this->dx_auth->get_site_sadmin();
                                                  $admin_name  						= $this->dx_auth->get_site_title();

                                                  $conditions    				= array('reservation.id' => $reservation_id);
                                                  $row           				= $this->Trips_model->get_reservation($conditions)->row();

                                                  $query1     				= $this->Users_model->get_user_by_id($row->userby);
                                                  $traveler_name 				= $query1->row()->username;
                                                  $traveler_email 			= $query1->row()->email;

                                                  $query2     						 = $this->Users_model->get_user_by_id($row->userto);
                                                  $host_name 								= $query2->row()->username;
                                                  $host_email 							= $query2->row()->email;

                                                  $list_title        = $this->Common_model->getTableData('list', array('id' => $row->list_id))->row()->title;

                                                  //for calendar
                                                  //if($is_block == 'on')
                                                  //{
                                                  $this->db->select_max('group_id');
                                                  $group_id               = $this->db->get('calendar')->row()->group_id;

                                                  if(empty($group_id)) echo $countJ = 0; else $countJ = $group_id;

                                                  $insertData['list_id']      = $row->list_id;
                                                  $insertData['group_id']     = $countJ + 1;
                                                  $insertData['availability'] = 'Available';
                                                  $insertData['booked_using'] = 'Other';

                                                  $checkin  = date('m/d/Y', $checkin);
                                                  $checkout = date('m/d/Y', $checkout);
                                                  $days     = getDaysInBetween($checkin, $checkout);

                                                  $count = count($days);
                                                  $i = 1;
                                                  foreach ($days as $val)
                                                  {
                                                  if($count == 1)
                                                  {
                                                  $insertData['style'] = 'single';
                                                  }
                                                  else if($count > 1)
                                                  {
                                                  if($i == 1)
                                                  {
                                                  $insertData['style'] = 'left';
                                                  }
                                                  else if($count == $i)
                                                  {
                                                  $insertData['notes'] = '';
                                                  $insertData['style'] = 'right';
                                                  }
                                                  else
                                                  {
                                                  $insertData['notes'] = '';
                                                  $insertData['style'] = 'both';
                                                  }
                                                  }

                                                  $insertData['booked_days'] = $val;
                                                  $this->Trips_model->insert_calendar($insertData);
                                                  $i++;
                                                  }
                                                  $this->db->where('list_id',$row->list_id)->where('availability','Available')->delete('calendar');
                                                  $query = $this->db->get('calendar');
                                                  $row1 = $query->last_row();
                                                  if($row1->availability == 'Not Available')
                                                  {
                                                  $this->db->where('group_id',$row1->group_id)->delete('calendar');
                                                  }
                                                  //}

                                                  //Send Message Notification To Traveller
                                                  $insertData = array(
                                                  'list_id'         => $row->list_id,
                                                  'reservation_id'  => $reservation_id,
                                                  'userby'          => $row->userto,
                                                  'userto'          => $row->userby,
                                                  'message'         => "Sorry, Your reservation request is declined by $host_name for $list_title.",
                                                  'created'         => local_to_gmt(),
                                                  'message_type'    => 2
                                                  );
                                                  $this->Message_model->sentMessage($insertData, 1);
                                                  $message_id     = $this->db->insert_id();


                                                  $updateKey      		  = array('id' => $reservation_id);
                                                  $updateData               = array();
                                                  $updateData['status ']    = 4;
                                                  $this->Trips_model->update_reservation($updateKey,$updateData);

                                                  //Send Mail To Traveller
                                                  $email_name = 'traveler_reservation_declined';
                                                  $splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{comment}" => $comment);
                                                  $this->Email_model->sendMail($traveler_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);

                                                  //Send Mail To Host
                                                  $email_name = 'host_reservation_declined';
                                                  $splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name), "{comment}" => $comment);
                                                  $this->Email_model->sendMail($host_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);

                                                  //Send Mail To Administrator
                                                  $email_name = 'admin_reservation_declined';
                                                  $splVars    = array("{site_name}" => $this->dx_auth->get_site_title(), "{traveler_name}" => ucfirst($traveler_name), "{list_title}" => $list_title,  "{host_name}" => ucfirst($host_name));
                                                  $this->Email_model->sendMail($admin_email,$admin_email,ucfirst($admin_name),$email_name,$splVars);

                                                  } */
                                                ?>
                                                <!--<?php
                                                $timestamp = $result->book_date;
// $timestamp = strtotime('+1 day',$timestamp);
                                                $book_date = date('m/d/Y', $timestamp);

// $gmtTime   = get_gmt_time($timestamp);

                                                $gmtTime = get_gmt_time(strtotime('+24 hours', $timestamp));
//$gmtTime   = get_gmt_time(strtotime('-20 minutes',$gmtTime));
//$date=gmdate("Year: %Y Month: %m Day: %d - %h:%i %a",$gmdate);
                                                $date = gmdate('D, d M Y H:i:s +\G\M\T', $gmtTime);
                                                ?>-->
                                                <?php
                                                $timestamp = $result->book_date;
                                                $book_date = date('m/d/Y', $timestamp);
                                                $gmtTime = get_gmt_time(strtotime('+24 hours', $timestamp));
//$gmtTime   = get_gmt_time(strtotime('-20 minutes',$gmtTime));
//$date=gmdate("Year: %Y Month: %m Day: %d - %h:%i %a",$gmdate);
                                                $date = gmdate('D, d M Y H:i:s +\G\M\T', $gmtTime);
                                                ?>
                                                <div id="expire" style="font-size:20px;"></div>
                                    <?php }
                                    else
                                    { ?>
        <?php echo translate("Status"); ?>
    <?php } ?>
                                    </span></span>

<?php if ($result->status == 1)
    { ?>

                                        <span class="data" style="padding: 10px 10px 34px;"><span class="inner">
                                                <input type="hidden" name="reservation_id" id="reservation_id" value="<?php echo $result->id; ?>" />
                                                <a class="Reserve_Accept" id="req_accept" href="javascript:show_hide(1);"><?php echo translate("Accept"); ?></a>
                                                <a class="Reserve_Decline" id="req_decline" href="javascript:show_hide(2);"><?php echo translate("Decline"); ?></a>
                                                <div id="expired" style="display: none"><?php echo translate('Expired'); ?></div>
                                                <div id="accept" style="display:none">
                                                    <form name="accept_req" action="<?php echo site_url('trips/accept'); ?>" method="post">
                                                        <p>
                                                            <input type="checkbox" id="block_date" name="block_date" />
        <?php echo translate("Block my calendar from") . " " . get_user_times($result->checkin, get_user_timezone()) . " " . translate("through") . " " . get_user_times($result->checkout, get_user_timezone()); ?>
                                                        </p>

                                                        <p><?php echo translate("Type optional message to guest") . "..."; ?></p>

                                                        <p><textarea name="comment" id="comment"></textarea></p>

                                                        <p>
                                                            <input type="hidden" id="checkout" name="reservation_id" value="<?php echo $result->id ?>" />
                                                            <input type="hidden" id="checkin" name="checkin" value="<?php echo $result->checkin; ?>" />
                                                            <input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
                                                            <input type="button" class="accept_button" name="accepted" value="<?php echo translate("Accept"); ?>" onclick="javascript:req_action('accept');" />
                                                        </p>
                                                    </form>
                                                </div>
                                                <div id="decline" style="display:none">
                                                    <form name="decline_req" action="<?php echo site_url('trips/decline'); ?>" method="post">

                                                        <p><?php echo translate("Type optional message to guest") . "..."; ?></p>

                                                        <p><textarea name="comment" id="comment2"></textarea></p>

                                                        <p>
                                                            <input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
                                                            <input type="hidden" id="checkin" name="checkin" value="<?php echo $result->checkin; ?>" />
                                                            <input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
                                                            <input type="button" class="decline_button" name="decliend" value="<?php echo translate("Decline"); ?>" onclick="javascript:req_action('decline');" />
                                                        </p>
                                                    </form>
                                                </div>
                                            </span>


                                        </span>
                                    <?php }
                                    else
                                    { ?>

                                        <span class="data" style="padding: 10px 10px 34px;"><span class="inner">
        <?php
        echo translate($result->name);
        ?>
                                            </span></span>

    <?php } ?>
                            </li>
                        </ul>
                        <div style="clear:both"></div>
                    </div>

                </div>
            </div>
            <div style="clear:both"></div>
        </div>
    </div></div>
<script type="text/javascript">
<?php if ($result->status == 1)
    { ?>
            $('#expire').countdown({
                until: new Date("<?php echo $date; ?>"),
                format: 'dHMS',
                layout: '{hn}:' + '{mn}:' + '{sn}',
                onExpiry: liftOff,
                expiryText: "Expired"
            });

            function liftOff()
            {
                var reservation_id = $("#reservation_id").val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('trips/out'); ?>",
                    async: true,
                    data: "reservation_id=" + reservation_id,
                    success: function (data)
                    {
                        location.reload(true);
                    }
                });
            }

    <?php } ?>

    function show_hide(id)
    {
        if (id == 1)
        {
            document.getElementById('req_accept').className = 'Reserve_click';
            document.getElementById('req_decline').className = 'Reserve_Decline';
            $('#decline').hide();
            $('#accept').show();
        }
        else
        {
            document.getElementById('req_accept').className = 'Reserve_Accept';
            document.getElementById('req_decline').className = 'Reserve_click';
            $('#decline').show();
            $('#accept').hide();
        }
    }

    function req_action(id)
    {
        var reservation_id = $("#reservation_id").val();

        if (id == "accept")
        {
            var is_block = $("#block_date").val();
            var comment = $("#comment").val();
        }
        else
        {
            var is_block = $("#block_date2").val();
            var comment = $("#comment2").val();
        }

        var checkin = $("#checkin").val();
        var checkout = $("#checkout").val();

        var ok = confirm("Are you sure to " + id + " request?");
        if (!ok)
        {
            return false;
        }

        document.getElementById(id).innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('trips'); ?>/" + id,
            async: true,
            data: "is_block=" + is_block + "&comment=" + comment + "&reservation_id=" + reservation_id + "&checkin=" + checkin + "&checkout=" + checkout,
            success: function (data)
            {
                window.location.href = '<?php echo base_url(); ?>' + data + '/' + reservation_id;
                //alert('<?php echo base_url(); ?>'+data);
                // document.getElementById(id).innerHTML = data;
                //	location.reload(true);
            }
        });
    }

</script>
