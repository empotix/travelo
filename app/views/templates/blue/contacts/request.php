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
    $confirmation .= '<td>' . date("F j, Y", strtotime($result->checkin)) . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Check out") . '</td>';
    $confirmation .= '<td>' . date("F j, Y", strtotime($result->checkout)) . '</tr>';
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
    $confirmation .= '<td>' . translate("Message") . '</td>';
    $confirmation .= '<td>' . $message . '</tr>';
    $confirmation .= '</tr>';

    $confirmation .= '<tr>';
    $confirmation .= '<td>' . translate("Rate") . '( ' . translate("per night") . ' )' . '</td>';
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
    $confirmation .= '<td>' . get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $subtotal) . '</tr>';
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
                        <h2><?php echo "Contact Request"; ?> </h2><span class="View_MyPrint">
                            <a href="javascript:void(0);" onclick="javascript:print_confirmation();"><?php echo translate("Print"); ?></a>
                        </span></div>
                    <div class="Box_Content">

                        <?php if ($result->status != 1)
                            {
                                ?>

    <?php } ?>
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

                            <li class="bottom">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Message"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo $message; ?></span></span>
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
                                    <?php
                                    }
                                }
                            ?>

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Subtotal"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $subtotal); ?></span></span>
                            </li>

                            <!--<li class="clearfix">
                            <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo "Host fee"; ?></span></span>
                            <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $commission); ?></span></span>
                            </li>-->

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Total Payout"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $subtotal); ?></span></span>
                            </li>

                            <li class="clearfix bottom">
                                <span class="label" style="text-align:left;"><span class="inner"><span class="checkout_icon" id="icon_cal"></span>
                                        <?php if ($result->status == 1)
                                            {
                                                ?>
                                                <?php echo ""; ?>
                                            <?php
                                            }
                                            else
                                            {
                                                ?>
                                        <?php echo translate("Status"); ?>
    <?php } ?>
                                    </span></span>

<?php if ($result->status == 1)
    {
        ?>
                                        <span class="data" style="padding: 10px 10px 34px;"><span class="inner">
                                                <input type="hidden" name="contact_id" id="contact_id" value="<?php echo $result->id; ?>" />
                                                <!-- Pre-Approve -->
                                                <br><br>
                                                <div id="approve">
                                                    <a class="Reserve_approve" id="req_approve" href="javascript:show_hide(1);"><?php echo "Pre-Approve"; ?></a>
                                                </div>
                                                <div id="approve_form" style="display:none">
                                                    <form name="approve_req" action="<?php echo site_url('contacts/accept'); ?>" method="post">
                                                        <p>
                                                        <p>
                                                            <input type="hidden" id="title" name="title" value="<?php echo $list; ?>" />
                                                            <input type="hidden" id="checkin" name="list" value="<?php echo $result->checkin; ?>" />
                                                            <input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
                                                            <input type="hidden" id="guests" name="guests" value="<?php echo $no_quest; ?>" />
                                                            <input type="hidden" id="price_approve" name="price_approve" value="<?php echo $subtotal; ?>" />	
                                                        </p>
                                                        <p><b>If Mark books your space, the reservation will be automatically accepted</b></p>
                                                        <p><?php echo translate("Optional message") . "..."; ?></p>
                                                        <p><textarea class="comment_contact" name="comment_approve" id="comment_approve"></textarea></p>
                                                        <p>
                                                            <input type="button" class="accept_button" name="approved" value="<?php echo "Send message"; ?>" onclick="javascript:req_action('approve');" />
                                                        </p>
                                                    </form>
                                                </div>
                                                <br><br>
                                                <!-- Special-offer -->
                                                <div id="special">
                                                    <a class="Reserve_special" id="req_special" href="javascript:show_hide(2);"><?php echo "Make a special offer"; ?></a>
                                                </div>
                                                <div id="special_form" style="display:none">
                                                    <form name="special_req" action="<?php echo site_url('contacts/accept'); ?>" method="post">
                                                        <p>
                                                        <p>
                                                            <input type="hidden" id="title" name="title" value="<?php echo $list; ?>" />
                                                            <input type="hidden" id="checkin" name="list" value="<?php echo $result->checkin; ?>" />
                                                            <input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
                                                            <input type="hidden" id="guests" name="guests" value="<?php echo $no_quest; ?>" />	
                                                        </p>
                                                        <p align="center"><b>Price</b> Kr <input type="text" id="price_special" name="price_special" value="<?php echo $subtotal; ?>" /></p>
                                                        <p align="center">Enter the price for the reservation including all additional costs</p>
                                                        <p></p>
                                                        <p><?php echo translate("Optional message") . "..."; ?></p>
                                                        <p><textarea class="comment_contact" name="comment_special" id="comment_special"></textarea></p>
                                                        <p>
                                                            <input type="button" class="accept_button" name="offered" value="<?php echo "Send message"; ?>" onclick="javascript:req_action('special');" />
                                                        </p>
                                                    </form>
                                                </div>
                                                <br><br>

                                                <!-- Disscuss-More -->
                                                <div id="discuss">
                                                    <a class="Reserve_discuss" id="req_accept" href="javascript:show_hide(3);"><?php echo "Let's Discuss More"; ?></a>
                                                </div>
                                                <div id="discuss_form" style="display:none">
                                                    <form name="discuss_req" action="<?php echo site_url('contacts/discuss'); ?>" method="post">
                                                        <p>
                                                        <p>
                                                            <input type="hidden" id="checkin" name="list" value="<?php echo $result->checkin; ?>" />
                                                            <input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />	
                                                        </p>
                                                        <p><b>I need More information from the guest, or they need more information from me.</b></p>
                                                        <p></p>
                                                        <p>Use the space below to request additional information or answer questions from the guest.</p>
                                                        <p><?php echo translate("Add a personal message here") . "..."; ?></p>
                                                        <p><textarea class="comment_contact" name="comment_discuss" id="comment_discuss"></textarea></p>
                                                        <p>
                                                            <input type="button" class="accept_button" name="discussed" value="<?php echo "Send message"; ?>" onclick="javascript:req_action('discuss');" />
                                                        </p>
                                                    </form>
                                                </div>
                                                <br><br>

                                                <!-- Decline -->
                                                <div id="decline_option">
                                                    <a class="Reserve_decline_contact" id="req_decline" href="javascript:show_hide(4);"><?php echo translate("Decline"); ?></a>
                                                </div>
                                                <div id="decline" style="display:none">
                                                    <form name="decline_req" action="<?php echo site_url('contacts/decline'); ?>" method="post">

                                                        <p><?php echo translate("Optional message") . "..."; ?></p>
                                                        <p><textarea class="comment_contact" name="comment_decline" id="comment_decline"></textarea></p>

                                                        <p>
                                                            <input type="hidden" id="checkin" name="checkin" value="<?php echo $result->checkin; ?>" />
                                                            <input type="hidden" id="checkout" name="checkout" value="<?php echo $result->checkout; ?>" />
                                                            <input type="button" class="decline_button" name="decliend" value="<?php echo translate("Decline"); ?>" onclick="javascript:req_action('decline');" />
                                                        </p>
                                                    </form>
                                                </div>
                                            </span>


                                        </span>
                                            <?php
                                            }
                                            else
                                            {
                                                ?>

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
    {
        ?>

        <?php /* $('#expire').countdown({
          until: new Date("<?php echo $date; ?>"),
          format: 'dHMS',
          layout:'{dn}:'+'{hn}:'+'{mn}:'+'{sn}',
          onExpiry: liftOff,
          expiryText:"Expired"
          });
         */ ?>

            function liftOff()
            {
                var contact_id = $("#contact_id").val();

                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url('contacts/out'); ?>",
                    async: true,
                    data: "contact_id=" + contact_id,
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
            $('#approve_form').show();
            $('#special_form').hide();
            $('#discuss_form').hide();
            $('#decline').hide();
        }
        else if (id == 2)
        {
            $('#approve_form').hide();
            $('#special_form').show();
            $('#discuss_form').hide();
            $('#decline').hide();
        }
        else if (id == 3)
        {
            $('#approve_form').hide();
            $('#special_form').hide();
            $('#discuss_form').show();
            $('#decline').hide();
        }
        else
        {
            $('#approve_form').hide();
            $('#special_form').hide();
            $('#discuss_form').hide();
            $('#decline').show();
        }
    }


    function req_action(id)
    {
        var contact_id = $("#contact_id").val();

        if (id == "approve")
        {
            var price = $("#price_approve").val();
            var comment = $("#comment_approve").val();
            var action = "accept";
        }
        else if (id == "special")
        {
            var price = $("#price_special").val();
            var comment = $("#comment_special").val();
            var action = "accept";
        }
        else if (id == "discuss")
        {
            var comment = $("#comment_discuss").val();
            var action = id;
        }
        else
        {
            var comment = $("#comment_decline").val();
            var action = id;
        }

        var checkin = $("#checkin").val();
        var checkout = $("#checkout").val();

        if (id == "discuss")
            var ok = confirm("Are you sure to " + action + "?");
        else
            var ok = confirm("Are you sure to " + action + " request?");
        if (!ok)
        {
            return false;
        }
        document.getElementById(id).innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('contacts'); ?>/" + action,
            async: true,
            data: "comment=" + comment + "&contact_id=" + contact_id + "&checkin=" + checkin + "&checkout=" + checkout + "&price=" + price,
            success: function (data)
            {
                document.getElementById(id).innerHTML = data;
                location.reload(true);
            }
        });
    }

</script>