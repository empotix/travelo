<!--  Stylesheets -->
<link href="<?php echo css_url(); ?>/edit_listing.css" media="screen" rel="stylesheet" type="text/css" />
<!-- End of inclusion of style sheets -->

<!--Required Data from db  -->
<div class="container_bg" id="View_Edit_List">
    <div id="View_Edit_Heading">
        <div class="heading_content clearfix">
            <div class="edit_listing_photo">
                <?php $url = getListImage($room_id); ?>
                <img alt="Host_pic" height="65" src="<?php echo $url; ?>" /> </div>
            <div class="listing_info">
                <h3><?php echo anchor('rooms/' . $room_id, $list->title, array('id' => "listing_title_banner")) ?></h3>
                <?php echo anchor('rooms/' . $room_id, translate('View Listing'), array('class' => "clsLink2_Bg")) ?>  <span id="availability-error-message"></span> </span> </div>
            <div class="edit_view_all_list">
                <?php echo anchor('hosting', translate('View All Listing'), array('class' => 'btn large blue')); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div id="View_Edit_Content" class="clearfix">
        <div class="View_Edit_Nav"> 
            <div class="nav-container">
                <?php $this->load->view(THEME_FOLDER . '/includes/editList_header.php'); ?>
            </div>
        </div>
        <div class="View_Edit_Main_Content">
            <div id="notification-area"></div>
            <div id="dashboard-content">
                <div id="transparent_bg_overlay"></div>
                <!-- this is just here so that the RJS for the advanced pricing doesn't break -->
                <span id="default_daily_price" style="display: none;"></span>
                <ul class="panels" id="nav_pricing_panels">
                    <li class="selected">
                        <form id="form_submit" action=<?php echo base_url() . 'rooms/edit_price/' . $room_id; ?> method="post">
                            <div class="Box editlist_Box">
                                <div class="top">
                                    <h2><?php echo translate("Basic Pricing"); ?></h2>
                                </div>
                                <div class="middle">
                                    <ul>
                                        <li>
                                            <label for="hosting_price_native"><?php echo translate("Nightly"); ?><span style="color:#FF0000">&nbsp;*</span></label>
                                            <span class="currency_symbol"><?php echo get_currency_symbol($room_id); ?></span>
                                            <input id="hosting_price_native" name="nightly" size="30" maxlength="5"  type="text" value=<?php echo get_currency_value1($room_id, $list_price->night); ?> />
                                            <?php echo form_error('nightly'); ?>
                                        </li>
                                        <li>
                                            <label for="hosting_weekly_price_native"><?php echo translate("Weekly"); ?></label>
                                            <span class="currency_symbol"><?php echo get_currency_symbol($room_id); ?></span>
                                            <input id="hosting_weekly_price_native" name="weekly" maxlength="8" value=<?php if ($list_price->week)
                                                echo get_currency_value1($room_id, $list_price->week);
                                            else
                                                echo '""';
                                            ?> size="30" type="text" />
                                            <span class="protip"><?php echo translate("We recommend "); ?> <em><?php echo get_currency_symbol($room_id) . get_currency_value1($room_id, round(get_price1($list_price->night))); ?> to <?php echo get_currency_symbol($room_id) . get_currency_value1($room_id, round(get_price2($list_price->night))); ?> </em> <?php echo translate("based on your nightly price"); ?></span> </li>
                                        <li>
                                            <label for="hosting_monthly_price_native"><?php echo translate("Monthly"); ?></label>
                                            <span class="currency_symbol"><?php echo get_currency_symbol($room_id); ?></span>
                                            <input id="hosting_monthly_price_native" name="monthly" maxlength="10" value=<?php if ($list_price->month)
                                                       echo get_currency_value1($room_id, $list_price->month);
                                                   else
                                                       echo '""';
                                            ?> size="30" type="text" />
                                            <span class="protip"><?php echo translate("We recommend "); ?><em><?php echo get_currency_symbol($room_id) . get_currency_value1($room_id, round(get_price3($list_price->night))); ?> to <?php echo get_currency_symbol($room_id) . get_currency_value1($room_id, round(get_price4($list_price->night))); ?></em> <?php echo translate("based on your nightly price"); ?></span> </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="Box editlist_Box">
                                <div class="top">
                                    <h2><?php echo translate("Additional Costs"); ?></h2>
                                </div>
                                <div class="middle">
                                    <ul>
                                        <li>
                                            <label for="hosting_price_for_extra_person_native"><?php echo translate("Additional Guests"); ?></label>
                                            <span class="currency_symbol"><?php echo get_currency_symbol($room_id); ?></span>
                                            <input id="hosting_price_for_extra_person_native" name="extra" size="30" maxlength="3" type="text" value=<?php if ($list_price->addguests)
                                                        echo get_currency_value1($room_id, $list_price->addguests);
                                                    else
                                                        echo '""';
                                            ?> />
                                            <span class="protip"><?php echo translate("Per night for each guest after"); ?>
                                                <select id="hosting_guests_included" name="guests">
<?php for ($i = 1; $i <= 16; $i++)
    {
        ?>
                                                            <option value="<?php echo $i; ?>"><?php echo $i;
        if ($i == 16)
            echo '+';
        ?> </option>
    <?php } ?>
                                                </select>
                                            </span> </li>
                                        <li>
                                            <label for="hosting_extras_price_native"><?php echo translate("Cleaning Fees"); ?></label>
                                            <span class="currency_symbol"><?php echo get_currency_symbol($room_id); ?></span>
                                            <input id="hosting_extras_price_native" name="cleaning" size="30" maxlength="3" type="text" value=<?php if ($list_price->cleaning)
        echo get_currency_value1($room_id, $list_price->cleaning);
    else
        echo '""';
?> />
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <button style="margin: 10px 0px 0px;" class="btn green gotomsg" type="submit"><span><span><?php echo translate("Save"); ?></span></span></button>
                            <p id="show" style="display: none; float: right; color:red;margin-right:300px; "></p>
                            <div>
                                <div class="clear"></div>
                            </div>
                        </form>
                        <!-- End of the basic str -->
                    </li>

                </ul>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
</div>
<!-- edit_room -->

<script type="text/javascript">
    jQuery("#hosting_guests_included").val('<?php if (isset($list_price->guests))
        echo $list_price->guests;
    else
        echo '1';
?>');
    $('#form_submit').submit(function () {
        var Nightly = parseInt($('#hosting_price_native').val());
        var weekly = parseInt($('#hosting_weekly_price_native').val());
        var monthly = parseInt($('#hosting_monthly_price_native').val());
        var guests = $('#hosting_price_for_extra_person_native').val();
        var cleaning = $('#hosting_extras_price_native').val();
        if (Nightly >= 15001 || Nightly <= 9 || Nightly >= 15001.00 || Nightly <= 9.00) {
            $('#show').show();
            $('#show').html('Enter the nightly price within 10 to 15000');
            return false;
        }
        else if (weekly <= Nightly) {
            $('#show').show();
            $('#show').html('Weekly price is greater than nightly price');
            return false;
        }
        else if (monthly <= weekly || monthly <= Nightly) {
            $('#show').show();
            $('#show').html('Monthly price is greater than weekly and nightly price');
            return false;
        }
        else if (guests >= 301) {
            $('#show').show();
            $('#show').html('Maximum of additional guests price is 300');
            return false;
        }
        else if (cleaning >= 301) {
            $('#show').show();
            $('#show').html('Maximum of Cleaning fees is 300');
            return false;
        }

    });
</script>
