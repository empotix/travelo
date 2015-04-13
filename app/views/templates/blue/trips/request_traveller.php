<script src="<?php echo js_url(); ?>/jquery.countdown.js" type="text/javascript"></script>
<link href="<?php echo css_url() . '/reservation.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<style type="text/css">
    @media print{
        #Header { display:none; }
        body { background-color:#FFFFFF; background-image:none; color:#000000 }
        ul { color:#330200;}
        li { padding:10px 0 10px 0;  
             list-style-type: none; }
        #details_breakdown_1 { border-radius: 10px 10px 10px 10px;
                               border: 1px solid rgb(252, 178, 128);
                               margin: 0px 0px 20px 0px; }
        #left { display:none; }
        #printPrint { display:none; }
        #Footer { display:none; }
    }
</style>
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

    /* $confirmation .= '<tr>';
      $confirmation .= '<td>'.translate("Host fee").'</td>';
      $confirmation .= '<td>'.get_currency_symbol($result->list_id).get_currency_value1($result->list_id,$commission).'</tr>';
      $confirmation .= '</tr>'; */

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
                            <!--<li class="bottom">
                            <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Cancellation"); ?></span></span>
                            <span class="data"><span class="inner"><?php echo $nights; ?></span></span>
                            </li>-->
                        </ul>


                        <ul id="details_breakdown_1" class="dashed_table_1 clearfix">

                            <li class="top clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Rate") . '( ' . translate("per night") . ' )'; ?></span></span>
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

                            <!--<li class="clearfix">
                            <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Host fee"); ?></span></span>
                            <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $commission); ?></span></span>
                            </li>-->

                            <li class="clearfix">
                                <span class="label"><span class="inner"><span class="checkout_icon" id="icon_cal"></span><?php echo translate("Total Payout"); ?></span></span>
                                <span class="data"><span class="inner"><?php echo get_currency_symbol($result->list_id) . get_currency_value1($result->list_id, $subtotal); ?></span></span>
                            </li>

                            <li class="clearfix bottom">
                                <span class="label" ><span class="inner"><span class="checkout_icon" id="icon_cal"></span>
                                        <?php if ($result->status == 1)
                                            { ?>
                                                <?php echo translate("Expires in"); ?>
                                                <?php
                                                $timestamp = $result->book_date;
                                                $book_date = date('m/d/Y', $timestamp);
                                                $gmtTime = get_gmt_time(strtotime('+24 hours', $timestamp));
                                                $gmtTime = get_gmt_time(strtotime('-18 minutes', $gmtTime));
                                                $date = gmdate('D, d M Y H:i:s \G\M\T', $gmtTime);
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
                document.getElementById(id).innerHTML = data;
                location.reload(true);
            }
        });
    }

</script>
