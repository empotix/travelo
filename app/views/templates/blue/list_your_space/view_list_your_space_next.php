<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=10" />
        <title>Calender</title>
        <link href="<?php echo css_url() . '/common.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo css_url() . '/demo.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo css_url() . '/listyourspace.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="<?php echo base_url() . 'css/templates/blue/jquery.ui.css'; ?>" />
        <script language="Javascript" type="text/javascript" src="<?php echo base_url() . 'js/jquery-1.9.1.js'; ?>"></script>
        <script language="Javascript" type="text/javascript" src="<?php echo base_url() . 'js/jquery-ui.js'; ?>"></script>
        <script language="Javascript" type="text/javascript" src="<?php echo base_url() . 'js/ajaxfileupload.js'; ?>"></script>
        <script language="Javascript" type="text/javascript" src="<?php echo base_url() . 'js/rotate3Di.js'; ?>"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
        <script type="text/javascript">
            $(function () {
                //$('#my_contain').fadeIn();

                jQuery.support.cors = true;

                var always = '';
                var some_times = '';
                var one_time = '';
                var calendar_type = <?php echo $calendar_type; ?>;
                var calendar = 0;
                var night_price = '';
                var week_price = '';
                var month_price = '';
                var price_index = 0;
                var calendar_status = <?php echo $lys_status->calendar; ?>;
                var price_status = <?php echo $lys_status->price; ?>;
                var overview_status = <?php echo $lys_status->overview; ?>;
                var address_status = <?php echo $lys_status->address; ?>;
                var amenities_status = <?php echo $lys_status->amenities; ?>;
                var listing_status = <?php echo $lys_status->listing; ?>;
                var photo_status = <?php echo $lys_status->photo; ?>;
                var title_status = <?php echo $lys_status->title; ?>;
                var summary_status = <?php echo $lys_status->summary; ?>;
                var title_index = 0;
                var summary_index = 0;
                var summary = '';
                var title = '';
                var amenities_index = 0;
                var beds_status = <?php echo $lys_status->beds; ?>;
                var bathrooms_status = <?php echo $lys_status->bathrooms; ?>;
                var photos_count = <?php echo $list_photo->num_rows(); ?>;
                var city = '<?php echo $city; ?>';
                var state = '<?php echo $state; ?>';
                var country = '<?php echo $country_name; ?>';

                if ('<?php echo $house_rule; ?>' == '')
                {
                    var detail_status = 0;
                }
                else
                {
                    var detail_status = 1;
                }

                if (calendar_type == 1)
                {
                    $('#calendar_first').hide();
                    $('#always').show();
                    $('#cal_plus').hide();
                    $('#cal_plus_after').show();
                    calendar = 1;

                }
                if (calendar_type == 2)
                {
                    $('#calendar_first').hide();
                    $('#some_times').show();
                    $('#cal_plus').hide();
                    $('#cal_plus_after').show();
                    calendar = 1;
                }
<?php
    if ($this->uri->segment(3) == 'edit_photo')
    {
        ?>
                        $('#cal').hide();
                        $('#photo').hide();
                        $('#photo_after').show();
                        if (calendar_type == 1 || calendar_type == 2 || calendar_type == 3)
                        {
                            $('#cal_after').show();
                        }
                        else
                        {
                            $('#cal1').show();


                        }
                        if (photos_count != 0)
                        {
                            $('#photo_plus_white').hide();
                            $('#photo_grn_white').show();
                            $('#container_photo').hide();
                            $('.container_add_photo').show();
                        }
                        else
                        {
                            $('#container_photo').show();
                        }
                        $('#cal_container').hide();
                        $('#photos_container').show();
                        $('#photo_ul').show();
        <?php
    }
?>
                if (calendar_type == 3)
                {
                    $('#calendar_first').hide();
                    $('#one_time').show();
                    $('#cal_plus').hide();
                    $('#cal_plus_after').show();
                    calendar = 1;
                }
                $('#home-1').mouseover(function () {
                    $('#home-2').css('opacity', '0.4');
                    $('#home-3').css('opacity', '0.4');
                });

                $('#home-1').mouseleave(function () {
                    $('#home-2').css('opacity', '1');
                    $('#home-3').css('opacity', '1');
                });

                $('#home-2').mouseover(function () {
                    $('#home-1').css('opacity', '0.4');
                    $('#home-3').css('opacity', '0.4');
                });

                $('#home-2').mouseleave(function () {
                    $('#home-1').css('opacity', '1');
                    $('#home-3').css('opacity', '1');
                });

                $('#home-3').mouseover(function () {
                    $('#home-1').css('opacity', '0.4');
                    $('#home-2').css('opacity', '0.4');
                });

                $('#home-3').mouseleave(function () {
                    $('#home-1').css('opacity', '1');
                    $('#home-2').css('opacity', '1');
                });

                $('#home-1').click(function ()
                {
                    $('#calendar_first').hide();
                    $('#always').show();
                    always = 1;
                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/calendar_type'; ?>',
                        type: 'POST',
                        data: {type: always, room_id: <?php echo $room_id ?>},
                        success: function (data)
                        {
                            //alert(data);
                            //	$('.plus_hv').removeClass();
                            $('#cal_plus').hide();
                            $('#cal_plus_after').show();
                            calendar = 1;
                            var total_status = 0;
                            total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                            var final_status = 6 - total_status;
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            if (final_status == 0)
                            {
                                $.ajax({
                                    url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                    type: 'POST',
                                    data: {room_id: <?php echo $room_id; ?>},
                                    success: function (data)
                                    {
                                        $('#steps_count').hide();
                                        $('#list_space').show();
                                        $('#list-button').rotate3Di(720, 750);
                                    }
                                })

                            }
                        }

                    })
                })
                $('#home-2').click(function ()
                {
                    $('#calendar_first').hide();
                    $('#some_times').show();
                    some_times = 2;
                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/calendar_type' ?>',
                        type: 'POST',
                        data: {type: some_times, room_id: <?php echo $room_id ?>},
                        success: function (data)
                        {
                            //alert(data);
                            $('#cal_plus').hide();
                            $('#cal_plus_after').show();
                            calendar = 1;
                            var total_status = 0;
                            total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                            var final_status = 6 - total_status;
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            if (final_status == 0)
                            {
                                $.ajax({
                                    url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                    type: 'POST',
                                    data: {room_id: <?php echo $room_id; ?>},
                                    success: function (data)
                                    {
                                        $('#steps_count').hide();
                                        $('#list_space').show();
                                        $('#list-button').rotate3Di(720, 750);
                                    }
                                })

                            }
                        }
                    })
                })
                $('#home-3').click(function ()
                {
                    $('#calendar_first').hide();
                    $('#one_time').show();
                    one_time = 3;
                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/calendar_type' ?>',
                        type: 'POST',
                        data: {type: one_time, room_id: <?php echo $room_id ?>},
                        success: function (data)
                        {
                            //alert(data);
                            $('#cal_plus').hide();
                            $('#cal_plus_after').show();
                            calendar = 1;
                            var total_status = 0;
                            total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                            var final_status = 6 - total_status;
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            if (final_status == 0)
                            {
                                $.ajax({
                                    url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                    type: 'POST',
                                    data: {room_id: <?php echo $room_id; ?>},
                                    success: function (data)
                                    {
                                        $('#steps_count').hide();
                                        $('#list_space').show();
                                        $('#list-button').rotate3Di(720, 750);
                                    }
                                })

                            }
                        }
                    })
                })
                $('#calendar_always').click(function ()
                {
                    $('#always').hide();
                    $('#one_time').hide();
                    $('#some_times').hide();
                    $('#calendar_first').show();
                    $('#home-1 .myButtonLink').css('background-image', 'url(<?php echo base_url() . 'images/tick-hover.png' ?>)');
                })
                $('#calendar_one').click(function ()
                {
                    $('#always').hide();
                    $('#one_time').hide();
                    $('#some_times').hide();
                    $('#calendar_first').show();
                    $('#home-3 .myButtonLink').css('background-image', 'url(<?php echo base_url() . 'images/tick-hover.png' ?>)');
                })
                $('#calendar_some').click(function ()
                {
                    $('#always').hide();
                    $('#one_time').hide();
                    $('#some_times').hide();
                    $('#calendar_first').show();
                    $('#home-2 .myButtonLink').css('background-image', 'url(<?php echo base_url() . 'images/cal-hover.png' ?>)');
                })
                $('#back_always').click(function ()
                {
                    $('#always').hide();
                    $('#calendar_first').show();
                    $('#home-1 .myButtonLink').css('background-image', 'url(<?php echo base_url() . 'images/tick-hover.png' ?>)');

                })
                $('#back_one').click(function ()
                {
                    $('#one_time').hide();
                    $('#calendar_first').show();
                    $('#home-3 .myButtonLink').css('background-image', 'url(<?php echo base_url() . 'images/tick-hover.png' ?>)');


                })
                $('#back_some').click(function ()
                {
                    $('#some_times').hide();
                    $('#calendar_first').show();
                    $('#home-2 .myButtonLink').css('background-image', 'url(<?php echo base_url() . 'images/cal-hover.png' ?>)');
                    $('#home-2 .myButtonLink').css('height', '99');
                    $('#home-2 .myButtonLink').css('width', '97');

                })
                $('#price').click(function ()
                {
                    $("#price-right-hover").show();
                    $("#overview-textbox-hover").hide();
                    if (calendar == 1)
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();


                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal1').show();

                    }
                    if (price_status == 0)
                    {
                        $('#price').hide();
                        $('#price_after').show();
                        $('#price_plus_after').hide();
                        $('#price_plus').show();
                    }
                    else
                    {
                        $('#price').hide();
                        $('#price_after').show();
                        $('#price_plus').hide();
                        $('#price_plus_after').show();
                        $('#large_length').hide();
                        $('#small_length').hide();
                    }

                    if (amenities_index == 1)
                    {
                        $('#amenities').show();
                        $('#amenities_after').hide();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }

                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }

                    $('#cal_container').hide();
                    $('#overview_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#price_container').show();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#static_circle_map').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                    if (detail_status == 1)
                    {
                        $('#detail_side').show();
                        $('#detail_side_after').hide();
                        $('#detail_plus').hide();
                    }
                    else
                    {
                        $('#detail_side_after').hide();
                        $('#detail_side').show();
                        $('#detail_plus').show();
                    }
                })

                $('#cal1').click(function ()
                {
                    $("#price-right-hover").hide();
                    $("#overview-textbox-hover").hide();
                    if (calendar == 1)
                    {
                        //$('#cal').show();
                        //$('#cal_after').hide();

                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();


                    }
                    else
                    {
                        $('#cal').show();
                        $('#cal1').hide();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                    }
                    if (amenities_index == 1)
                    {
                        $('#amenities').show();
                        $('#amenities_after').hide();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }
                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    $('#overview_after').hide();
                    $('#overview').show();
                    $('#cal_container').show();
                    $('#price_container').hide();
                    $('#overview_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#static_circle_map').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();
                })
                $('#cal_after').click(function ()
                {
                    $("#price-right-hover").hide();
                    $("#overview-textbox-hover").hide();
                    if (calendar == 1)
                    {
                        $('#cal').show();
                        $('#cal_after').hide();
                        $('#cal1').hide();

                    }
                    else
                    {
                        $('#cal').show();
                        $('#cal1').hide();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }
                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    if (amenities_index == 1)
                    {
                        $('#amenities').show();
                        $('#amenities_after').hide();
                    }
                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    $('#cal_container').show();
                    $('#price_container').hide();
                    $('#overview_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#static_circle_map').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                    if (detail_status == 1)
                    {
                        $('#detail_side').show();
                        $('#detail_side_after').hide();
                        $('#detail_plus').hide();
                    }
                    else
                    {
                        $('#detail_side_after').hide();
                        $('#detail_side').show();
                        $('#detail_plus').show();
                    }
                })
                var char_price = 0;
                $('#advance_price').click(function ()
                {
                    $('#advance_price').hide();
                    $('#advance_price1').hide();
                    $('#advance_price_after').show();
                    $('#advance_price_after1').show();
                })

                if ('<?php echo $cleaning_fee; ?>' != 0)
                {
                    $('#listing_cleaning_fee_native_checkbox').prop('checked', true);
                }

                if ($('#listing_cleaning_fee_native_checkbox').prop('checked') == true)
                {
                    $('#clean_textbox').show();
                }

                if ('<?php echo $extra_guest_price; ?>' != 0)
                {
                    $('#price_for_extra_person_checkbox').prop('checked', true);
                }

                if ($('#price_for_extra_person_checkbox').prop('checked') == true)
                {
                    $('#additional_textbox').show();
                }

                if ('<?php echo $security; ?>' != 0)
                {
                    $('#listing_security_deposit_native_checkbox').prop('checked', true);
                }

                if ($('#listing_security_deposit_native_checkbox').prop('checked') == true)
                {
                    $('#security_textbox').show();
                }

                $('#night_price').bind('keypress', function (event) {
                    var regex = new RegExp("^[a-zA-Z]+$");
                    var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
                    if (!regex.test(key)) {

                    }
                    else
                    {
                        event.preventDefault();
                        return false;
                    }
                });
                $('#night_price').keyup(function (event)
                {
                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '10');
                    var currency_value = parseInt($('#currency_hidden').val());

                    if (isNaN($(this).val() / 1) == false) {
                        night_price = parseInt($(this).val());
                        night_price = parseInt($('#night_price').val());
                        if (event.keyCode == 13)
                        {
                            if (night_price < currency_value)
                            {
                                $('#small_length').fadeIn();
                                $('#large_length').hide();

                            }
                            else if (night_price <= currency_value * 1000)
                            {
                                $('#small_length').fadeOut();
                                $('#large_length').fadeOut();
                            }
                            else if (night_price > currency_value * 1000)
                            {
                                $('#large_length').fadeIn();
                                $('#small_length').hide();
                                //$('#price_saving').fadeIn();

                            }
                            else
                            {
                                $('#small_length').fadeIn();
                                $('#large_length').hide();

                            }

                        }
                        else if (night_price < currency_value)
                        {
                            $('#small_length').fadeIn();
                            $('#large_length').hide();
                        }
                        else if (night_price <= currency_value * 1000)
                        {
                            $('#small_length').fadeOut();
                            $('#large_length').fadeOut();
                            //$('#price_saving').fadeIn();

                        }
                        else if (night_price > currency_value * 1000)
                        {
                            $('#large_length').fadeIn();
                            $('#small_length').hide();
                            //$('#price_saving').fadeIn();

                        }
                        else
                        {
                            $('#small_length').fadeIn();
                            $('#large_length').hide();

                        }
                    }
                    else
                    {
                        char_price = 1;
                    }
                })

                var currency_converter = function (from, to, amount)
                {
                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/currency_converter' ?>',
                        type: 'POST',
                        data: {from: from, to: to, amount: amount},
                        success: function (price)
                        {
                            $('#currency_hidden').val(price);

                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/get_currency' ?>',
                                type: 'POST',
                                data: {currency: to},
                                success: function (symbol)
                                {
                                    $('#currency_symbol_hidden').val(symbol);
                                    return_function(price, symbol);
                                }
                            })
                        }
                    })
                }


                function return_function(data, symbol)
                {
                    $('#small_length').text('Your price is too low. The minimum is ' + symbol + data + '.');
                    $('#large_length').text('Your price is too long. The maximum is ' + symbol + data * 1000 + '.');
                    $('#small_week_length').text('Your price is too low. The minimum is ' + symbol + data * 7 + '.');
                    $('#large_week_length').text('Your price is too long. The maximum is ' + symbol + data * 1400 + '.');
                    $('#large_month_length').text('Your price is too long. The maximum is ' + symbol + data * 6000 + '.');
                    $('#small_month_length').text('Your price is too low. The minimum is ' + symbol + data * 30 + '.');
                    $('#small_clean_length').text('Your price is too low. The minimum is ' + symbol + data + '.');
                    $('#large_clean_length').text('Your price is too long. The maximum is ' + symbol + data * 60 + '.');
                    $('#small_security_length').text('Your price is too low. The minimum is ' + symbol + data + '.');
                    $('#large_security_length').text('Your price is too long. The maximum is ' + symbol + data * 60 + '.');
                    $('#small_additional_length').text('Your price is too low. The minimum is ' + symbol + data + '.');
                    $('#large_additional_length').text('Your price is too long. The maximum is ' + symbol + data * 60 + '.');
                }

                currency_converter('USD', '<?php echo $currency; ?>', '10');

                $.ajax({
                    url: '<?php echo base_url() . 'rooms/get_currency' ?>',
                    type: 'POST',
                    data: {currency: '<?php echo $currency; ?>', room_id: <?php echo $room_id; ?>},
                    success: function (data)
                    {
                        $('#price_container .js-standard-price .center_night .input-addon #currency_symbol').replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + data + '</b></span>');
                        $('#advance_price_after1 .input-addon #currency_symbol').replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + data + '</b></span>');
                    }
                })
                var night_price_rotate = 0;

                $('#night_price').focusout(function ()
                {
                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '10');
                    var currency_value = parseInt($('#currency_hidden').val());

                    if (char_price == 1)
                    {
                        return false;
                    }

                    night_price = parseInt($('#night_price').val());

                    if (night_price > currency_value * 1000)
                    {
                        $('#night_price').val($('#hidden_price').val());
                        $('#price_plus').hide();
                        $('#price_plus_after').show();
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#night_price').val(data);
                            }
                        });

                    }

                    if (night_price == 0 || night_price == '')
                    {
                        $('#night_price').val($('#hidden_price').val());
                        price_index = 0;
                        night_price_rotate = 1;
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/replace_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                var total_status = 0;
                                total_status = calendar + price_index + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                if (final_status != 0)
                                {
                                    $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#list_space').hide();
                                            $('#steps_count').show();
                                        }
                                    })

                                }
                            }
                        });

                    }

                    var night_price_val = 0;
                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/get_price' ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            //alert(data);
                            if (data > 1 || data < currency_value * 1000 || data != currency_value * 10)
                            {
                                night_price_val = 0;
                            }
                            else
                            {
                                night_price_val = 1;
                            }
                        }
                    });

                    if (night_price < currency_value)
                    {
                        $('#small_length').fadeIn();
                        $('#night_price').val($('#hidden_price').val());
                        night_price = parseInt($('#night_price').val());
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/min_price' ?>',
                            type: 'POST',
                            data: {price: night_price, room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#price_plus').show();
                                $('#price_plus_after').hide();
                                $('#price_saving').fadeOut();
                            }
                        })
                    }
                    if (night_price <= currency_value * 1000 && night_price >= currency_value)
                    {
                        $('#small_length').fadeOut();
                        $('#large_length').fadeOut();
                        $('#price_saving').fadeIn();
                        $('#hidden_price').val(night_price);
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/add_price' ?>',
                            type: 'POST',
                            data: {price: night_price, room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#price_plus').hide();
                                $('#price_plus_after').show();
                                $('#des_plus').hide();
                                $('#des_plus_after').show();
                                price_index = 1;
                                price_status = 1;
                                var total_status = 0;
                                total_status = calendar + price_index + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                if (final_status == 0)
                                {
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#steps_count').hide();
                                            $('#list_space').show();
                                            if (night_price_rotate == 1)
                                            {
                                                night_price_rotate = 0;
                                                $('#list-button').rotate3Di(720, 750);
                                            }
                                        }
                                    })

                                }
                                $('#price_saving').fadeOut();
                            }
                        })
                    }

                    if (isNaN(night_price))
                    {
                        night_price = 0;
                    }
                    if (night_price == 0)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#night_price').val(data);
                            }
                        });
                        $('#small_length').fadeIn();
                        $('#large_length').hide();
                        $('#price_plus').show();
                        $('#price_plus_after').hide();
                        price_index = 1;
                        night_price_rotate = 1;
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/replace_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                var total_status = 0;
                                total_status = calendar + price_index + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                if (final_status != 0)
                                {
                                    $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#list_space').hide();
                                            $('#steps_count').show();
                                        }
                                    })

                                }
                            }
                        });
                    }
                })
                $("#currency_drop").change(function ()
                {
                    currency_converter('USD', $(this).val(), '10');

                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/add_currency' ?>',
                        type: 'POST',
                        data: {currency: $(this).val(), room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            $('#price_container .js-standard-price .center_night .input-addon #currency_symbol').replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + data + '</b></span>');
                            $('#advance_price_after1 .input-addon #currency_symbol').replaceWith('<span id="currency_symbol" class="input-prefix-curency"><b>' + data + '</b></span>');
                            $('#clean_textbox .row .col-4 .input-addon #clean_currency').replaceWith('<span class="input-prefix" id="clean_currency">' + data + '</b></span>');
                            $('#additional_textbox .row .col-4 .input-addon #additional_currency').replaceWith('<span class="input-prefix" id="additional_currency">' + data + '</b></span>');
                            $('#security_textbox .row .col-4 .input-addon #security_currency').replaceWith('<span class="input-prefix" id="security_currency">' + data + '</b></span>');
                        }
                    })
                });
                $('#week_price').keyup(function (event)
                {
                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '10');
                    var currency_value = parseInt($('#currency_hidden').val());

                    week_price = parseInt($(this).val());

                    if (event.keyCode == 13)
                    {
                        if (week_price < currency_value || week_price == '' || week_price == 0)
                        {
                            $('#small_week_length').fadeIn();
                            $('#large_week_length').fadeOut();
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/get_week_price' ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#week_price').val(data);
                                }
                            });
                        }
                        else if (week_price < currency_value * 1400)
                        {
                            $('#small_week_length').fadeOut();
                            $('#large_week_length').fadeOut();
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/add_price' ?>',
                                type: 'POST',
                                data: {week_price: week_price, room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#price_plus').hide();
                                    $('#price_plus_after').show();
                                    price_index = 1;
                                }
                            })
                        }
                        else
                        {
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/get_week_price' ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#week_price').val(data);
                                }
                            });
                            $('#small_week_length').hide();
                            $('#large_week_length').fadeIn();
                        }
                    }
                })

                $('#week_price').focusout(function ()
                {
                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '10');
                    var currency_value = parseInt($('#currency_hidden').val());

                    week_price = $('#week_price').val();

                    if (isNaN(week_price))
                    {
                        week_price = 0;
                    }

                    if (week_price < currency_value * 7)
                    {
                        $('#small_week_length').fadeIn();
                        $('#large_week_length').fadeOut();
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_week_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#week_price').val(data);
                            }
                        });
                    }
                    else if (week_price <= currency_value * 1400 && week_price >= currency_value)
                    {
                        $('#small_week_length').fadeOut();
                        $('#large_week_length').fadeOut();
                        $('#advance_price_saving').fadeIn();
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/add_price' ?>',
                            type: 'POST',
                            data: {week_price: week_price, room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#price_plus').hide();
                                $('#price_plus_after').show();
                                price_index = 1;
                                $('#advance_price_saving').fadeOut();
                            }
                        })
                    }
                    else if (week_price == '' || week_price == 0)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_week_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#week_price').val(data);
                            }
                        });
                        $('#small_week_length').show();
                        $('#large_week_length').hide();
                    }
                    else
                    {
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_week_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#week_price').val(data);
                            }
                        });
                        $('#small_week_length').hide();
                        $('#large_week_length').fadeIn();
                    }
                })
                $('#month_price').keyup(function (event)
                {
                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '10');
                    var currency_value = parseInt($('#currency_hidden').val());

                    month_price = parseInt($(this).val());
                    if (event.keyCode == 13)
                    {
                        if (month_price < currency_value * 30)
                        {
                            $('#small_month_length').fadeIn();
                            $('#large_month_length').fadeOut();
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/get_month_price' ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#month_price').val(data);
                                }
                            });
                        }
                        else if (month_price < currency_value * 6000)
                        {
                            $('#small_month_length').fadeOut();
                            $('#large_month_length').fadeOut();
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/add_price' ?>',
                                type: 'POST',
                                data: {month_price: month_price, room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#price_plus').hide();
                                    $('#price_plus_after').show();
                                    price_index = 1;
                                }
                            })
                        }
                        else if (month_price == '' || month_price == 0)
                        {
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/get_month_price' ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#month_price').val(data);
                                }
                            });
                            $('#small_month_length').show();
                            $('#large_month_length').hide();
                        }
                        if (month_price > currency_value * 6000)
                        {
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/get_month_price' ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#month_price').val(data);
                                }
                            });
                            $('#small_month_length').hide();
                            $('#large_month_length').fadeIn();
                        }
                        else
                        {
                            $.ajax({
                                url: '<?php echo base_url() . 'rooms/get_month_price' ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#month_price').val(data);
                                }
                            });
                            $('#small_month_length').hide();
                            $('#large_month_length').fadeIn();
                        }
                    }

                })

                $('#night_price').keydown(function (e) {
                    if (e.shiftKey || e.ctrlKey || e.altKey) {
                        e.preventDefault();
                    } else {
                        var key = e.keyCode;
                        if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
                            e.preventDefault();
                        }
                    }
                });

                $('#month_price').focusout(function ()
                {
                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '10');
                    var currency_value = parseInt($('#currency_hidden').val());

                    month_price = $('#month_price').val();
                    if (month_price < currency_value * 30)
                    {
                        $('#small_month_length').fadeIn();
                        $('#large_month_length').fadeOut();
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_month_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#month_price').val(data);
                            }
                        });
                    }
                    else if (month_price < currency_value * 6000)
                    {
                        $('#small_month_length').fadeOut();
                        $('#large_month_length').fadeOut();
                        $('#advance_price_saving').fadeIn();
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/add_price' ?>',
                            type: 'POST',
                            data: {month_price: month_price, room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#price_plus').hide();
                                $('#price_plus_after').show();
                                price_index = 1;
                                $('#advance_price_saving').fadeOut();
                            }
                        })
                    }
                    else if (month_price == '' || month_price == 0)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_month_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#month_price').val(data);
                            }
                        });
                        $('#small_month_length').show();
                        $('#large_month_length').hide();
                    }
                    else
                    {
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/get_month_price' ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#month_price').val(data);
                            }
                        });
                        $('#small_month_length').hide();
                        $('#large_month_length').fadeIn();
                    }
                })

                $('#listing_cleaning_fee_native_checkbox').change(function ()
                {
                    if (this.checked)
                    {
                        $('#clean_textbox').show();
                    }
                    else
                    {
                        $('#clean_textbox').hide();
                    }
                })

                $('#price_for_extra_person_checkbox').change(function ()
                {
                    if (this.checked)
                    {
                        $('#additional_textbox').show();
                    }
                    else
                    {
                        $('#additional_textbox').hide();
                    }
                })

                $('#listing_security_deposit_native_checkbox').change(function ()
                {
                    if (this.checked)
                    {
                        $('#security_textbox').show();
                    }
                    else
                    {
                        $('#security_textbox').hide();
                    }
                })

                $('#cleaning_price').bind("paste", function (e) {
                    e.preventDefault();
                });

                $('#security_price_textbox').bind("paste", function (e) {
                    e.preventDefault();
                });

                $('#extra_guest_price').bind("paste", function (e) {
                    e.preventDefault();
                });

                $('#week_price').bind("paste", function (e) {
                    e.preventDefault();
                });

                $('#month_price').bind("paste", function (e) {
                    e.preventDefault();
                });

                $('#night_price').bind("paste", function (e) {
                    e.preventDefault();
                });

                $('#cleaning_price').focusout(function ()
                {
                    var cleaning_price = $.trim($(this).val());

                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '5');

                    setTimeout(
                            function ()
                            {
                                var currency_value = parseInt($('#currency_hidden').val());

                                if (cleaning_price < currency_value)
                                {
                                    $('#large_clean_length').hide();
                                    $('#small_clean_length').show();
                                }

                                if (cleaning_price > currency_value * 60)
                                {
                                    $('#small_clean_length').hide();
                                    $('#large_clean_length').show();
                                }
                                if (cleaning_price >= currency_value && cleaning_price <= currency_value * 60)
                                {
                                    $('#small_clean_length').hide();
                                    $('#large_clean_length').hide();

                                    $.ajax({
                                        url: '<?php echo base_url() . 'rooms/cleaning_price' ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>, cleaning_price: cleaning_price},
                                        success: function (data)
                                        {
                                            $('#clean_price_saving').fadeIn();
                                            $('#clean_price_saving').fadeOut();
                                        }
                                    });
                                }
                            }, 500);

                })


                $('#security_price_textbox').focusout(function ()
                {
                    var security_price = $.trim($(this).val());

                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '5');

                    setTimeout(
                            function ()
                            {
                                var currency_value = parseInt($('#currency_hidden').val());

                                if (security_price < currency_value)
                                {
                                    $('#large_security_length').hide();
                                    $('#small_security_length').show();
                                }

                                if (security_price > currency_value * 60)
                                {
                                    $('#small_security_length').hide();
                                    $('#large_security_length').show();
                                }
                                if (security_price >= currency_value && security_price <= currency_value * 60)
                                {
                                    $('#small_security_length').hide();
                                    $('#large_security_length').hide();

                                    $.ajax({
                                        url: '<?php echo base_url() . 'rooms/security_price' ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>, security_price: security_price},
                                        success: function (data)
                                        {
                                            $('#clean_price_saving').fadeIn();
                                            $('#clean_price_saving').fadeOut();
                                        }
                                    });
                                }
                            }, 500);

                })

                $('#extra_guest_count').change(function ()
                {
                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/guest_count' ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>, guest_count: $(this).val()},
                        success: function (data)
                        {
                        }
                    });
                })

                $('#extra_guest_price').focusout(function ()
                {
                    var extra_guest_price = $.trim($(this).val());

                    var currency_drop = $("#currency_drop option:selected").text();
                    currency_converter('USD', currency_drop, '5');

                    setTimeout(
                            function ()
                            {
                                var currency_value = parseInt($('#currency_hidden').val());

                                if (extra_guest_price < currency_value)
                                {
                                    $('#large_additional_length').hide();
                                    $('#small_additional_length').show();
                                }

                                if (extra_guest_price > currency_value * 60)
                                {
                                    $('#small_additional_length').hide();
                                    $('#large_additional_length').show();
                                }
                                if (extra_guest_price >= currency_value && extra_guest_price <= currency_value * 60)
                                {
                                    $('#small_additional_length').hide();
                                    $('#large_additional_length').hide();

                                    $.ajax({
                                        url: '<?php echo base_url() . 'rooms/extra_guest_price' ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>, guest_price: extra_guest_price},
                                        success: function (data)
                                        {
                                            $('#clean_price_saving').fadeIn();
                                            $('#clean_price_saving').fadeOut();
                                        }
                                    });
                                    var extra_guest_count = $("#extra_guest_count").val();
                                    $.ajax({
                                        url: '<?php echo base_url() . 'rooms/guest_count' ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>, guest_count: extra_guest_count},
                                        success: function (data)
                                        {
                                        }
                                    });
                                }
                            }, 500);

                })

                $('#overview').click(function ()
                {
                    $("#price-right-hover").hide();
                    $("#overview-textbox-hover").show();
                    if (calendar == 0)
                    {
                        $('#cal').hide();
                        $('#cal1').show();


                        if (overview_status == 0)
                        {
                            $('#overview').hide();
                            $('#overview_after').show();
                        }
                        else if (overview_status == 1)
                        {
                            $('#overview').hide();
                            $('#overview_after').show();
                            $('#over_plus1').hide();
                            $('#over_plus_after1').show();
                            $('#over_plus_after').hide();
                        }
                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();




                    }
                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }
                    if (price_status == 0)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#price_plus_after').hide();
                        $('#des_plus_after').show();
                        $('#des_plus').hide();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }

                    }
                    if (overview_status == 1)
                    {
                        $('#overview').hide();
                        $('#overview_after').show();
                        $('#over_plus1').hide();
                        $('#over_plus_after1').show();
                    }
                    if (overview_status == 0)
                    {
                        $('#overview').hide();
                        $('#overview_after').show();
                    }

                    if (amenities_index == 1)
                    {
                        $('#amenities').show();
                        $('#amenities_after').hide();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').show();
                        $('#overview').hide();
                        $('#over_plus_after1').show();
                    }
                    else
                    {
                        $('#overview').hide();
                        $('#overview_after').show();
                        $('#over_plus_after1').hide();
                        $('#over_plus1').show();
                    }
                    $('#cal_container').hide();
                    $('#price_container').hide();
                    $('#overview_entire').show();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#static_circle_map').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                    if (detail_status == 1)
                    {
                        $('#detail_side').show();
                        $('#detail_side_after').hide();
                        $('#detail_plus').hide();
                    }
                    else
                    {
                        $('#detail_side_after').hide();
                        $('#detail_side').show();
                        $('#detail_plus').show();
                    }
                })

                $('#amenities').click(function ()
                {
                    $("#price-right-hover").hide();
                    $("#overview-textbox-hover").hide();
                    amenities_index = 1;
                    $('#cal_container').hide();
                    $('#price_container').hide();
                    $('#overview_entire').hide();
                    $('#photo_entire').hide();
                    $('#amenities').hide();
                    $('#amenities_after').show();
                    $('#amenities_entire').show();
                    $('#listing_entire').hide();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#static_circle_map').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                    if (calendar == 0)
                    {
                        $('#cal').hide();
                        $('#cal1').show();
                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();



                    }
                    if (overview_status == 0)
                    {
                        $('#overview').show();
                        $('#overview_after').hide();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }
                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    if (detail_status == 1)
                    {
                        $('#detail_side').show();
                        $('#detail_side_after').hide();
                        $('#detail_plus').hide();
                    }
                    else
                    {
                        $('#detail_side_after').hide();
                        $('#detail_side').show();
                        $('#detail_plus').show();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }
                })
                $('input:checkbox').change(function ()
                {

                    $('#amenities_saving').fadeIn();
                    if (this.checked)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/add_amenities"; ?>',
                            type: 'POST',
                            data: {amenity: $(this).val(), room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                amenities_status = 1;
                                //  alert(data); 

                            }
                        })
                    }
                    else
                    {
                        var amenity_checkbox = [];
                        $('input:checkbox').each(function () {
                            if (this.checked)
                            {
                                amenity_checkbox.push($(this).val());
                            }
                        });

                        $.ajax({
                            url: '<?php echo base_url() . "rooms/delete_amenities"; ?>',
                            type: 'POST',
                            data: {amenity: amenity_checkbox, room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                amenities_status = 1;
                            }
                        })
                    }
                    $('#amenities_saving').fadeOut();
                })

                $('#listing').click(function ()
                {
                    $("#price-right-hover").hide();
                    $("#overview-textbox-hover").hide();
                    $('#cal_container').hide();
                    $('#price_container').hide();
                    $('#overview_entire').hide();
                    $('#photo_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').show();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#static_circle_map').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                    if (calendar == 0)
                    {
                        $('#cal').hide();
                        $('#cal1').show();
                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();

                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').hide();
                        $('#listing_after').show();
                        $('#listing_plus1').hide();
                        $('#listing_plus_after1').show();
                    }
                    else
                    {
                        $('#listing').hide();
                        $('#listing_after').show();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }

                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }

                    if (detail_status == 1)
                    {
                        $('#detail_side').show();
                        $('#detail_side_after').hide();
                        $('#detail_plus').hide();
                    }
                    else
                    {
                        $('#detail_side_after').hide();
                        $('#detail_side').show();
                        $('#detail_plus').show();
                    }
                    $('#amenities').show();
                    $('#amenities_after').hide();

                })

                if (price_status == 1)
                {
                    $('#des_plus').hide();
                    $('#des_plus_after').show();
                }
                var total_status = 0;
                total_status = calendar_status + price_status + address_status + listing_status + photo_status + overview_status;
                var final_status = 6 - total_status;
                $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                if (final_status == 0)
                {
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            $('#steps_count').hide();
                            $('#list_space').show();
                        }
                    })

                }
                $('#summary').bind("cut copy paste", function (e) {
                    e.preventDefault();
                });
                jQuery.fn.wordCount = function (params) {
                    var p = {
                        counterElement: "display_count"
                    };
                    var total_words;

                    if (params) {
                        jQuery.extend(p, params);
                    }
                    this.keypress(function (e)
                    {
                        var keyCode = e.which ? e.which : e.keyCode
                        total_words = this.value.split(/[\s\.\?]+/).length;
                        var characterCode;
                        characterCode = e.keyCode;

                        $('#summary').keydown(function (e)
                        {
                            var keyCode = e.which ? e.which : e.keyCode;
                            if (e.keyCode == 46 || e.keyCode == 88 || e.keyCode == 8) {
                                total_words = this.value.split(/[\s\.\?]+/).length;
                                if (total_words < 51)
                                {
                                    if (total_words >= 45)
                                    {
                                        var last_words = 50 - total_words;
                                        jQuery('#' + p.counterElement).replaceWith('<span style="color:#959595;float:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;" id="display_count"><span style="color:red;padding-bottom: 2px;">' + last_words + '</span> <?php echo translate("WORDS LEFT"); ?></span>');
                                    }
                                    else
                                    {
                                        jQuery('#' + p.counterElement).html(51 - total_words + ' <?php echo translate("WORDS LEFT"); ?>');
                                    }

                                }
                                else if (keyCode == 32)
                                {
                                    return false;
                                }

                                var summary = $(this).val();

                                if (summary.length == 0)
                                {
                                    summary_index = 0;
                                    summary_status = 0;
                                }
                                else
                                {
                                    summary_index = 1;
                                    summary_status = 1;
                                }


                            }
                        })


                        if (total_words < 51)
                        {
                            if (total_words >= 45)
                            {
                                var last_words = 50 - total_words;
                                jQuery('#' + p.counterElement).replaceWith('<span style="color:#959595;float:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;" id="display_count"><span style="color:red;padding-bottom: 2px;">' + last_words + '</span> <?php echo translate("WORDS LEFT"); ?></span>');
                            }
                            else
                            {

                                jQuery('#' + p.counterElement).html(51 - total_words + ' <?php echo translate("WORDS LEFT"); ?>');
                            }

                        }
                        else if (keyCode == 32)
                        {
                            return false;
                        }

                        var summary = $(this).val();

                        if (summary.length == 0)
                        {
                            summary_index = 0;
                            summary_status = 0;
                        }
                        else
                        {
                            summary_index = 1;
                            summary_status = 1;
                        }

                    });
                };

                $('#summary').wordCount();

                $('#summary').keyup(function ()
                {
                    summary = $(this).val();

                    summary = $.trim(summary);

                    if (summary.length == 0)
                    {
                        $('#over_plus_after1').hide();
                        $('#over_plus1').show();
                        summary_status = 0;
                        overview_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#list-button').rotate3Di(720, 750);
                            $('#steps_count').hide();
                        }
                        else
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $('#list_space').hide();
                            $('#steps_count').show();
                        }
                    }
                    else
                    {
                        summary_status = 1;
                    }
                    if (summary.length >= 1 && title_status == 1)
                    {
                        summary_status = 1;
                        overview_status = 1;
                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;

                        if (final_status == 0)
                        {
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                }
                            })

                        }
                    }
                })
                var summary_rotate = 0;

                $('#summary').focusout(function ()
                {
                    if (summary.length == 0)
                    {
                        summary_rotate = 1;
                    }

                    if (title.length > 0 && summary.length > 0)
                    {
                        $('#over_plus_after1').show();
                        $('#over_plus1').hide();
                    }

                    title = $.trim($('#title').val());

                    if (summary.length > 0)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/add_desc"; ?>',
                            type: 'POST',
                            data: {desc: summary, room_id: <?php echo $room_id; ?>, summary_index: summary_index},
                            success: function (data)
                            {
                                $('#overview_saving').fadeIn();
                                var total_status = 0;
                                total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                //alert(final_status);
                                $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                if (final_status == 0)
                                {
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#steps_count').hide();
                                            $('#list_space').show();
                                            if (summary_rotate == 1)
                                            {
                                                summary_rotate = 0;
                                                $('#list-button').rotate3Di(720, 750);
                                            }
                                        }
                                    })

                                }
                                $('#overview_saving').fadeOut();
                            }
                        })
                    }

                })

                /* Character Count */

                $('#title').keyup(function (e)
                {

                    var keyCode = e.which ? e.which : e.keyCode;

                    var characterCode;
                    characterCode = e.keyCode;

                    $('#title').keydown(function (e)
                    {
                        var keyCode = e.which ? e.which : e.keyCode;
                        if (e.keyCode == 46 || e.keyCode == 88 || e.keyCode == 8) {
                            //alert($('#title').val().length);
                            var char_left = 35 - $(this).val().length;
                            if ($(this).val().length < 36)
                            {
                                if (char_left <= 5)
                                {

                                    $('#chars_count').replaceWith('<span id="chars_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + char_left + '</span> <?php echo translate("CHARACTERS LEFT"); ?></span>');
                                }
                                else
                                {

                                    $('#chars_count').html(char_left + ' <?php echo translate("CHARACTERS LEFT"); ?>');
                                }

                            }
                        }
                    })


                    var char_left = 35 - $(this).val().length;
                    if ($(this).val().length < 36)
                    {
                        if (char_left <= 5)
                        {
                            $('#chars_count').replaceWith('<span id="chars_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;margin-right:-13px;"><span style="color:red;padding-bottom: 2px;">' + char_left + '</span> <?php echo translate("CHARACTERS LEFT"); ?></span>');
                        }
                        else
                        {
                            $('#chars_count').html(char_left + ' <?php echo translate("CHARACTERS LEFT"); ?>');
                        }

                    }
                    var title = $(this).val();

                    title = $.trim(title);

                    if (title.length == 0)
                    {
                        title = '<?php echo $room_type_org; ?>';
                        $('#title_header').replaceWith('<span id="title_header">' + title + '</span>');
                        title_index = 0;
                        title_status = 0;
                    }
                    else
                    {
                        $('#title_header').replaceWith('<span id="title_header">' + title + '</span>');
                        title_index = 1;
                        title_status = 1;
                    }


                })

                $('#title').keyup(function ()
                {
                    title = $(this).val();

                    title = $.trim(title);


                    //alert(title);
                    if (title.length == 0)
                    {
                        $('#over_plus_after1').hide();
                        $('#over_plus1').show();
                        overview_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        //alert(final_status);

                        if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#list-button').rotate3Di(720, 750);
                            $('#steps_count').hide();
                        }
                        else
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $('#list_space').hide();
                            $('#steps_count').show();
                        }
                    }
                    if (title.length >= 1 && summary_status == 1)
                    {

                        var total_status = 0;
                        overview_status = 1;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        //alert(final_status);
                        //$('#steps').replaceWith('<span id="steps">'+final_status+' steps</span>');
                        if (final_status == 0)
                        {
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    //$('#steps_count').hide();
                                    //  $('#list_space').show();

                                    // $('#list-button').rotate3Di(720, 750);

                                }
                            })

                        }
                    }

                })
                var title_rotate = 0;
                $('#title').focusout(function ()
                {
                    if (title == '')
                    {
                        title = '<?php echo $room_type_org; ?>';
                    }
                    title = $.trim($('#title').val());
                    if (title.length == 0)
                    {
                        title_index = 0;
                        title_rotate = 1;
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/add_title_zero"; ?>',
                            type: 'POST',
                            data: {title: '<?php echo $room_type_org; ?>', room_id: <?php echo $room_id; ?>, title_index: title_index},
                            success: function (data)
                            {
                            }
                        });
                    }
                    summary = $.trim($('#summary').val());
                    if (title.length > 0 && summary.length > 0)
                    {
                        $('#over_plus_after1').show();
                        $('#over_plus1').hide();
                    }
                    if (title.length > 0)
                    {
                        $('#overview_saving').fadeIn();
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/add_title"; ?>',
                            type: 'POST',
                            data: {title: title, room_id: <?php echo $room_id; ?>, title_index: title_index},
                            success: function (data)
                            {

                                var total_status = 0;
                                total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                //alert(final_status);
                                $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                if (final_status == 0)
                                {
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#steps_count').hide();
                                            $('#list_space').show();

                                            if (title_rotate == 1)
                                            {
                                                title_rotate = 0;
                                                $('#list-button').rotate3Di(720, 750);
                                            }
                                        }
                                    })

                                }
                                $('#overview_saving').fadeOut();
                            }
                        })
                    }
                })
                $('#bedrooms').change(function ()
                {
                    var beds_val = 0;
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/get_bedrooms"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            if (data == 0)
                            {
                                beds_val = 1;
                            }
                            else
                            {
                                beds_val = 0;
                            }
                        }
                    })
                    $('#listing_saving').fadeIn();
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/add_bedrooms"; ?>',
                        type: 'POST',
                        data: {bedrooms: $('#bedrooms :selected').text(), room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            beds_status = 1;
                            if (beds_status == 1 && bathrooms_status == 1)
                            {
                                $('#listing').hide();
                                $('#listing_after').show();
                                $('#listing_plus1').hide();
                                $('#listing_plus_after1').show();
                                var total_status = 0;
                                listing_status = 1;
                                total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                //alert(final_statu);
                                $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                if (final_status == 0)
                                {
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#steps_count').hide();
                                            $('#list_space').show();
                                            if (beds_val == 1)
                                            {
                                                $('#list-button').rotate3Di(720, 750);
                                            }
                                        }
                                    })

                                }
                            }
                        }
                    })
                    $('#listing_saving').fadeOut();
                })
                $('#beds').change(function ()
                {
                    var beds_val = 0;
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/get_beds"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            if (data == 0)
                            {
                                beds_val = 1;
                            }
                            else
                            {
                                beds_val = 0;
                            }
                        }
                    })
                    $('#listing_saving').fadeIn();
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/add_beds"; ?>',
                        type: 'POST',
                        data: {beds: $('#beds :selected').text(), room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            beds_status = 1;
                            if (beds_status == 1 && bathrooms_status == 1)
                            {
                                $('#listing').hide();
                                $('#listing_after').show();
                                $('#listing_plus1').hide();
                                $('#listing_plus_after1').show();
                                var total_status = 0;
                                listing_status = 1;
                                total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                //alert(final_statu);
                                $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                if (final_status == 0)
                                {
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#steps_count').hide();
                                            $('#list_space').show();
                                            if (beds_val == 1)
                                            {
                                                $('#list-button').rotate3Di(720, 750);
                                            }
                                        }
                                    })

                                }
                            }
                        }
                    })
                    $('#listing_saving').fadeOut();
                })

                $('#bathrooms').change(function ()
                {
                    var bath_val = 0;
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/get_bath"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            if (data == '')
                            {
                                bath_val = 1;
                            }
                            else
                            {
                                bath_val = 0;
                            }
                        }
                    })
                    $('#listing_saving').fadeIn();
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/add_bathrooms"; ?>',
                        type: 'POST',
                        data: {bathrooms: $('#bathrooms :selected').text(), room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            bathrooms_status = 1;
                            if (beds_status == 1 && bathrooms_status == 1)
                            {
                                $('#listing').hide();
                                $('#listing_after').show();
                                $('#listing_plus1').hide();
                                $('#listing_plus_after1').show();
                                var total_status = 0;
                                listing_status = 1;
                                total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                var final_status = 6 - total_status;
                                //alert(final_status);
                                $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                if (final_status == 0)
                                {
                                    $.ajax({
                                        url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                        type: 'POST',
                                        data: {room_id: <?php echo $room_id; ?>},
                                        success: function (data)
                                        {
                                            $('#steps_count').hide();
                                            $('#list_space').show();
                                            if (bath_val == 1)
                                            {
                                                $('#list-button').rotate3Di(720, 750);
                                            }
                                        }
                                    })

                                }
                            }
                        }
                    })
                    $('#listing_saving').fadeOut();
                })

                $('#home_type_drop').change(function ()
                {
                    $('#listing_saving').fadeIn();
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/add_hometype"; ?>',
                        type: 'POST',
                        data: {hometype: $(this).val(), room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                        }
                    })
                    $('#listing_saving').fadeOut();
                })

                $('#room_type_drop').change(function ()
                {
                    $('#listing_saving').fadeIn();
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/add_roomtype"; ?>',
                        type: 'POST',
                        data: {roomtype: $(this).val(), room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                        }
                    })
                    $('#listing_saving').fadeOut();
                })

                $('#accommodates_drop').change(function ()
                {
                    $('#listing_saving').fadeIn();
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/add_accommodates"; ?>',
                        type: 'POST',
                        data: {accommodates: $(this).val(), room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                        }
                    })
                    $('#listing_saving').fadeOut();
                })

                if (beds_status == 1 && bathrooms_status == 1)
                {
                    $('#list_plus').hide();
                    $('#list_plus_after').show();
                }

                if (overview_status == 1)
                {
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#over_plus').hide();
                        $('#over_plus_after').show();
                    }
                    $('#overview_after').hide();
                    $('#overview').show();

                }
                $('#photo').click(function ()
                {

                    $('#cal_container').hide();
                    $('#overview_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#price_container').hide();
                    $('#amenities_after').hide();
                    $('#amenities').show();
                    $('#photos_container').show();
                    $('#photo').hide();
                    $('#photo_after').show();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#static_circle_map').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/photo_check'; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                            //alert(data);
                            if (data == 1)
                            {
                                $('#container_photo').hide();
                                $('.container_add_photo').show();
                                $('#photo_ul').show();
                            }
                        }
                    })

                    if (calendar == 0)
                    {
                        $('#cal').hide();
                        $('#cal1').show();
                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();

                    }
                    if (overview_status == 0)
                    {
                        $('#overview').show();
                        $('#overview_after').hide();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }

                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }
                    if (photo_status == 1)
                    {
                        $('#photo_plus_white').hide();
                        $('#photo_grn_white').show();
                    }

                    if (detail_status == 1)
                    {
                        $('#detail_side').show();
                        $('#detail_side_after').hide();
                        $('#detail_plus').hide();
                    }
                    else
                    {
                        $('#detail_side_after').hide();
                        $('#detail_side').show();
                        $('#detail_plus').show();
                    }
                })
<?php
    if ($total_status == 6)
    {
        ?>
                        $('#seelist_container').hide();
    <?php }
?>
                $('#list-button').click(function ()
                {
                    $.ajax({
                        url: '<?php echo base_url() . 'rooms/get_lys_status' ?>',
                        type: 'POST',
                        data: {room_id:<?php echo $room_id; ?>},
                        success: function (data)
                        {
                            //alert(data);return false;
                            //$('#final_photo').replaceWith('<div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(<?php echo base_url() . 'images/' . $room_id . '/'; ?>'+data+'); background-repeat:no-repeat; background-size:577px"></div>');
                            if (data == 6)
                            {
                                $.ajax({
                                    url: '<?php echo base_url() . 'rooms/list_pay' ?>',
                                    type: 'POST',
                                    data: {room_id:<?php echo $room_id; ?>},
                                    success: function (data)
                                    {
                                        if (data == '1')
                                        {
                                            $.ajax({
                                                url: '<?php echo base_url() . 'rooms/list_pay_status' ?>',
                                                type: 'POST',
                                                data: {room_id:<?php echo $room_id; ?>},
                                                success: function (data)
                                                {
                                                    if (data == 1)
                                                    {
                                                        window.location.href = "<?php echo base_url() . 'rooms/' . $room_id; ?>";
                                                    }
                                                    else
                                                    {
                                                        window.location.href = "<?php echo base_url() . "rooms/listpay/$room_id"; ?>";
                                                    }
                                                }
                                            })
                                        }
                                        else
                                        {
                                            $.ajax({
                                                url: '<?php echo base_url() . 'rooms/final_photo' ?>',
                                                type: 'POST',
                                                data: {room_id:<?php echo $room_id; ?>},
                                                success: function (data)
                                                {
                                                    if (data != 'no_image.jpg')
                                                    {
                                                        $('#final_photo').replaceWith('<div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(<?php echo base_url() . 'images/' . $room_id . '/'; ?>' + data + '); background-repeat:no-repeat; background-size:577px"></div>');
                                                    }
                                                    else
                                                    {
                                                        $('#final_photo').replaceWith('<div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(<?php echo base_url() . 'images/'; ?>' + data + '); background-repeat:no-repeat; background-size:577px"></div>');
                                                    }
                                                }
                                            })
                                            $.ajax({
                                                url: '<?php echo base_url() . 'rooms/get_lys_status' ?>',
                                                type: 'POST',
                                                data: {room_id:<?php echo $room_id; ?>},
                                                success: function (data)
                                                {
                                                    //alert(data);return false;
                                                    //$('#final_photo').replaceWith('<div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(<?php echo base_url() . 'images/' . $room_id . '/'; ?>'+data+'); background-repeat:no-repeat; background-size:577px"></div>');

                                                    if (data == 6)
                                                    {
                                                        $('#terms_side_2').show();
                                                        $('#detail_side_2').show();
                                                        $('#seelist_container').fadeIn();
                                                    }
                                                    else
                                                    {
                                                        window.location.href = "<?php echo base_url() . 'rooms/lys_next/edit/' . $room_id; ?>";
                                                    }
                                                }
                                            })
                                        }
                                    }
                                })

                            }
                            else
                            {
                                window.location.href = "<?php echo base_url() . 'rooms/lys_next/edit/' . $room_id; ?>";
                            }
                        }
                    })


                })
                $('#close_list').click(function ()
                {
                    $('#seelist_container').fadeOut();
                    window.location.href = "<?php echo base_url() . 'rooms/lys_next/edit/' . $room_id; ?>";
                })
                $('#see_list').click(function ()
                {
                    window.location.href = "<?php echo base_url() . 'rooms/' . $room_id; ?>";
                })
                $('#finish_list').click(function ()
                {
                    $('#my_contain').fadeOut();
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/first_popup"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>},
                        success: function (data)
                        {
                        }
                    })
                })

                $('#upload_file_btn').on('click', function () {

                    var urldata = base_url + 'rooms/add_photo_user_login';
                    //var pardata = "&test="+test;
                    $.ajax({
                        url: urldata,
                        // data:pardata,
                        success: function (data) {

                            if (!data) {
                                window.location.assign("<?php echo base_url(); ?>users/signin");
                            }
                        }})
                    if ($('#upload_file').val() == '')
                    {
                        //$('#no_file').show();
                        alert('No file choosed');
                        return false;
                    }
                    var image_name = $('#upload_file').val().toLowerCase();
                    var ext = image_name.split('.').pop();
                    //	var ext = $('#upload_file').val().split('.').pop();
                    if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
                        // extension is not allowed 
                        alert('Please choose the correct file');
                        return false;
                    }
                    $('#container_photo').hide();
                    $('.container_add_photo').show();

                    $('#upload_file_btn1').hide();
                    $('#upload_file_btn1_dis').show();
                    var request_in_process = false;
                    if (!request_in_process) {
                        request_in_process = true;
                        $.ajaxFileUpload({
                            url: '<?php echo base_url() . 'rooms/add_photo' ?>',
                            secureuri: false,
                            fileElementId: 'upload_file',
                            // type: 'POST',
                            dataType: 'text',
                            async: false,
                            success: function (data)
                            {
                                //alert(data);
                                if (data != 'no')
                                {
                                    $('#container_photo').hide();
                                    $('.container_add_photo').show();
                                    $('#photos_count').hide();
                                    $('#content').show();
                                    for (var i = 0; i < 50; i++)
                                    {
                                        $('.expand').css('width', i + '%');
                                    }
                                    $('#upload_file_btn1').show();
                                    $('#upload_file_btn1_dis').hide();

                                    setTimeout(function () {
                                        // Do something after 2 seconds\
                                        $('#container_photo').hide();
                                        $('.container_add_photo').show();
                                        $('#photo_ul').show();
                                        $('#photo_ul').replaceWith(data);
                                        for (var i = 50; i < 100; i++)
                                        {
                                            $('.expand').css('width', i + '%');
                                        }

                                        photo_status = 1;
                                        photos_count++;

                                        $('#content').hide();
                                        $('#photos_count').show();
                                        if (photos_count < 0)
                                        {
                                            $('#photos_count').replaceWith('<p id="photos_count">0 <?php echo translate('Photos'); ?></p>');
                                        }
                                        else
                                        {
                                            $('#photos_count').replaceWith('<p id="photos_count">' + photos_count + ' <?php echo translate('Photos'); ?></p>');
                                        }
                                        $('#photo_plus_white').hide();
                                        $('#photo_grn_white').show();
                                        $('#photo_ul').replaceWith(data);
                                        $('#photo_plus_white').hide();
                                        $('#photo_grn_white').show();
                                        var total_status = 0;
                                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                        var final_status = 6 - total_status;
                                        //alert(final_status);
                                        $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                        if (final_status == 0)
                                        {
                                            $.ajax({
                                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                                type: 'POST',
                                                data: {room_id: <?php echo $room_id; ?>},
                                                success: function (data)
                                                {
                                                    $('#steps_count').hide();
                                                    $('#list_space').show();
                                                    if (photos_count == 1)
                                                    {
                                                        $('#list-button').rotate3Di(720, 750);
                                                    }
                                                }
                                            })

                                        }
                                    }, 2000);
                                }
                                else
                                {
                                    alert('Please choose the correct file');
                                    return false;
                                }
                                var request_in_process = false;
                            }
                        });
                    }
                    //return false;
                });
                var upload_process = false;
                $('#upload_file_btn1').on('click', function () {


                    var urldata = base_url + 'rooms/add_photo_user_login';
                    //var pardata = "&test="+test;
                    $.ajax({
                        url: urldata,
                        // data:pardata,
                        success: function (data) {

                            if (!data) {
                                window.location.assign("<?php echo base_url(); ?>users/signin");
                            }
                        }})


                    if ($('#upload_file1').val() == '')
                    {
                        //$('#no_file1').show();
                        alert('No file choosed');
                        return false;
                    }
                    var image_name = $('#upload_file1').val().toLowerCase();
                    var ext = image_name.split('.').pop();
                    //	var ext = $('#upload_file1').val().split('.').pop();

                    if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext))) {
                        // extension is not allowed 
                        alert('Please choose the correct file');
                        return false;
                    }
                    $('#upload_file_btn1').hide();
                    $('#upload_file_btn1_dis').show();
                    var request_in_process = false;

                    if (!request_in_process) {
                        request_in_process = true;
                        upload_process = true;
                        $.ajaxFileUpload({
                            url: '<?php echo base_url() . 'rooms/add_photo1' ?>',
                            secureuri: false,
                            fileElementId: 'upload_file1',
                            // type: 'POST',
                            dataType: 'text',
                            async: false,
                            success: function (data)
                            {
                                //alert(data);
                                if (data != 'no')
                                {
                                    $('#photos_count').hide();
                                    $('#content').show();
                                    for (var i = 0; i < 50; i++)
                                    {
                                        $('.expand').css('width', i + '%');

                                    }
                                    setTimeout(function () {
                                        // Do something after 2 seconds\
                                        $('#container_photo').hide();
                                        $('.container_add_photo').show();
                                        $('#photo_ul').show();
                                        $('#photo_ul').replaceWith(data);
                                        for (var i = 50; i < 100; i++)
                                        {
                                            $('.expand').css('width', i + '%');
                                        }
                                        photo_status = 1;
                                        photos_count = photos_count + 1;
                                        $('#upload_file_btn1').show();
                                        $('#upload_file_btn1_dis').hide();
                                        $('#content').hide();
                                        $('#photos_count').show();
                                        if (photos_count < 0)
                                        {
                                            $('#photos_count').replaceWith('<p id="photos_count">0 <?php echo translate('Photos'); ?></p>');
                                        }
                                        else {
                                            $('#photos_count').replaceWith('<p id="photos_count">' + photos_count + ' <?php echo translate('Photos'); ?></p>');
                                        }
                                        $('#photo_plus_white').hide();
                                        $('#photo_grn_white').show();
                                        var total_status = 0;
                                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                        var final_status = 6 - total_status;
                                        //alert(final_status);
                                        $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                        if (final_status == 0)
                                        {
                                            $.ajax({
                                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                                type: 'POST',
                                                data: {room_id: <?php echo $room_id; ?>},
                                                success: function (data)
                                                {
                                                    $('#steps_count').hide();
                                                    $('#list_space').show();
                                                    if (photos_count == 1)
                                                    {
                                                        $('#list-button').rotate3Di(720, 750);
                                                    }
                                                }
                                            })

                                        }
                                        request_in_process = false;
                                        upload_process = false;
                                    }, 2000);

                                }
                                else
                                {
                                    alert('Please choose the correct file');
                                    request_in_process = false;
                                    upload_process = false;
                                    return false;
                                }
                                $('#upload_file1').replaceWith('<input type="file" style="z-index: 9999; position:absolute; width: 90px; padding: 5px 20px; cursor: default; opacity: 0; margin: -4px -119px 0;" id="upload_file1" name="upload_file1">');

                            }
                        });
                    }
                    //return false;
                });
                if (photo_status == 1)
                {

                    $('#photo_plus').hide();
                    $('#photo_grn').show();
                    var total_status = 0;
                    total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                    var final_status = 6 - total_status;
                    //alert(final_status);
                    $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                    if (final_status == 0)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/final_step"; ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#steps_count').hide();
                                $('#list_space').show();
                                //$('#list-button').rotate3Di(720, 750);
                            }
                        })

                    }
                }
                var request_in_process = false;
                $.fn.delete_photo = function (id) {
                    if (!request_in_process && !upload_process) {
                        request_in_process = true;
                        upload_process = true;
                        $.ajax({
                            url: '<?php echo base_url() . 'rooms/delete_photo'; ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>, photo_id: id},
                            async: false,
                            success: function (data)
                            {
                                photos_count = photos_count - 1;
                                $('#photo_ul').replaceWith(data);
                                if (photos_count < 0)
                                {
                                    photos_count = 0;
                                    $('#photos_count').replaceWith('<p id="photos_count">0 <?php echo translate('Photos'); ?></p>');
                                }
                                else {
                                    $('#photos_count').replaceWith('<p id="photos_count">' + photos_count + ' <?php echo translate('Photos'); ?></p>');
                                }
                                if (photos_count == 0)
                                {
                                    photo_status = 0;
                                    $('#photo_grn_white').hide();
                                    $('.photo_appear').hide();
                                    $('#photo_plus_white').show();
                                    //$('#container_photo').show();
                                    //$('.container_add_photo').hide();
                                    var total_status = 0;
                                    total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                                    var final_status = 6 - total_status;
                                    //alert(final_status);

                                    if (final_status == 0)
                                    {
                                        $.ajax({
                                            url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                            type: 'POST',
                                            data: {room_id: <?php echo $room_id; ?>},
                                            success: function (data)
                                            {
                                                $('#steps_count').hide();
                                                $('#list_space').show();
                                                $('#list-button').rotate3Di(720, 750);
                                            }
                                        })

                                    }
                                    else
                                    {
                                        $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                                        $('#steps_count').show();
                                        $('#list_space').hide();
                                    }
                                }
                                request_in_process = false;
                                upload_process = false;
                            }
                        })
                    }
                };
                $.fn.highlight = function (id) {
                    msg = $.trim($(this).val());
                    var max = 100;
                    $('#highlight_' + id).bind("cut copy paste", function (e) {
                        e.preventDefault();
                    });
                    $('#highlight_' + id).keypress(function (e) {
                        if (e.which < 0x20) {
                            // e.which < 0x20, then it's not a printable character
                            // e.which === 0 - Not a character
                            return;     // Do nothing
                        }
                        if (this.value.length == max) {
                            e.preventDefault();
                        } else if (this.value.length > max) {
                            // Maximum exceeded
                            this.value = this.value.substring(0, max);
                            //alert('Please give the highlights within 100 Characters');
                        }
                    });
                    if (msg.length == 100)
                    {
                        alert("You can't give the more than 100 characters");
                    }
                    if (msg.length <= max)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/photo_highlight"; ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>, photo_id: id, msg: msg},
                            success: function (data)
                            {
                            }
                        })
                    }
                }
                if (address_status == 1)
                {
                    $('#address_after').hide();
                    $('#address_side').show();
                    $('#addr_plus_after_grn').show();
                    $('#address_before').hide();
                    $('#addr_plus').hide();
                    $('#add_content').hide();
                    $('#add_address').hide();
                    $('#after_address').show();
                }
                else
                {
                    $('#address_after').hide();
                    $('#address_side').show();
                }
                $('#address_side').click(function ()
                {
                    $('#cal_container').hide();
                    $('#overview_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#price_container').hide();
                    $('#photos_container').hide();
                    $('#address_entire').show();
                    $('#address_right').show();
                    $('#address_side').hide();
                    $('#address_after').show();
                    $('#amenities_after').hide();
                    $('#amenities').show();
                    $('#main_entire_right').hide();
                    $("#price-right-hover").hide();
                    $('#overview-text-right').hide();
                    $('#summary-text-hover').hide();
                    $('#detail_container').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                    if (calendar == 0)
                    {
                        $('#cal').hide();
                        $('#cal1').show();
                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();

                    }
                    if (overview_status == 0)
                    {
                        $('#overview').show();
                        $('#overview_after').hide();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }

                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }
                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').show();
                        $('#address_side').hide();
                        $('#addr_plus_after_white').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                        $('#static_circle_map').show();
                        $('#address_right').hide();
                    }

                    if (detail_status == 1)
                    {
                        $('#detail_side').show();
                        $('#detail_side_after').hide();
                        $('#detail_plus').hide();
                    }
                    else
                    {
                        $('#detail_side_after').hide();
                        $('#detail_side').show();
                        $('#detail_plus').show();
                    }

                })
                $('select option[value="<?php echo $country_name; ?>"]').attr("selected", true);
                $('#add_address').click(function ()
                {
                    $('#address_popup1').delay(5000).show();
                })
                $('#address_popup1_close').click(function ()
                {
                    $('#address_popup1').delay(5000).hide();
                })
                $('#address_popup2_close').click(function ()
                {
                    $('#address_popup2').delay(5000).hide();
                })
                $('#close_popup3').click(function ()
                {
                    $('#address_popup3').delay(5000).hide();
                })
                $('#cancel_popup3').click(function ()
                {
                    $('#address_popup3').delay(5000).hide();
                })

                $('#address_popup1_cancel').click(function ()
                {
                    $('#address_popup1').delay(5000).hide();
                })
                $('#edit_address').click(function ()
                {
                    $('#address_popup2').hide();
                    $('#address_popup1').show();

                    if ($.trim($('#lys_street_address').val()) != '' && $.trim($('#city').val()) != '' && $.trim($('#zipcode').val()) != '')
                    {
                        $('.next_active').css('opacity', 1);
                        $('.disable-btn').hide();
                        $('.enable-btn').show();
                    }
                    else
                    {
                        $('.next_active').css('opacity', 0.65);
                        $('.disable-btn').show();
                        $('.enable-btn').hide();
                    }

                })
                $('#edit_popup3').click(function ()
                {
                    $('#address_popup3').hide();
                    $('#address_popup1').show();

                    if ($.trim($('#lys_street_address').val()) != '' && $.trim($('#city').val()) != '' && $.trim($('#zipcode').val()) != '')
                    {
                        $('.next_active').css('opacity', 1);
                        $('.disable-btn').hide();
                        $('.enable-btn').show();
                    }
                    else
                    {
                        $('.next_active').css('opacity', 0.65);
                        $('.disable-btn').show();
                        $('.enable-btn').hide();
                    }

                })
                $('#edit_address1').click(function ()
                {
                    $('#address_popup2').hide();
                    $('#address_popup1').show();

                    if ($.trim($('#lys_street_address').val()) == '' && $.trim($('#city').val()) == '' && $.trim($('#zipcode').val()) == '')
                    {
                        $('.next_active').css('opacity', 0.65);
                        $('.disable-btn').show();
                        $('.enable-btn').hide();
                    }
                    else
                    {
                        $('.next_active').css('opacity', 1);
                        $('.disable-btn').hide();
                        $('.enable-btn').show();
                    }

                })
                $('#lys_street_address').keyup(function ()
                {
                    if ($.trim($(this).val()) != '' && $.trim($('#city').val()) != '' && $.trim($('#zipcode').val()) != '')
                    {
                        $('.next_active').css('opacity', 1);
                        $('.disable-btn').hide();
                        $('.enable-btn').show();
                    }
                    else
                    {
                        $('.next_active').css('opacity', 0.65);
                        $('.disable-btn').show();
                        $('.enable-btn').hide();
                    }
                })
                $('#zipcode').keyup(function ()
                {
                    if ($.trim($(this).val()) != '' && $.trim($('#lys_street_address').val()) != '' && $.trim($('#city').val()) != '')
                    {
                        $('.next_active').css('opacity', 1);
                        $('.disable-btn').hide();
                        $('.enable-btn').show();
                    }
                    else
                    {
                        $('.next_active').css('opacity', 0.65);
                        $('.disable-btn').show();
                        $('.enable-btn').hide();
                    }
                })
                $('#city').keyup(function ()
                {
                    if ($.trim($(this).val()) != '' && $.trim($('#lys_street_address').val()) != '' && $.trim($('#zipcode').val()) != '')
                    {
                        $('.next_active').css('opacity', 1);
                        $('.disable-btn').hide();
                        $('.enable-btn').show();
                    }
                    else
                    {
                        $('.next_active').css('opacity', 0.65);
                        $('.disable-btn').show();
                        $('.enable-btn').hide();
                    }
                })
                $('.enable-btn').click(function ()
                {
                    $('#address_popup1').hide();
                    $('#address_popup2').show();
                    $('#str_street_address').replaceWith('<strong id="str_street_address">' + $('#lys_street_address').val() + '</strong>');
                    $('#str_city_state_address').replaceWith('<strong id="str_city_state_address">' + $('#city').val() + '  ' + $('#state').val() + '</strong>');
                    $('#str_country').replaceWith('<strong id="str_country">' + $('#country').val() + '</strong>');
                    $('#str_zipcode').replaceWith('<strong id="str_zipcode">' + $('#zipcode').val() + '</strong>');
                    /*  $('#str_street_address1').replaceWith('<strong id="str_street_address1">'+$('#lys_street_address').val()+'</strong>');
                     $('#str_city_state_address1').replaceWith('<strong id="str_city_state_address1">'+$('#city').val()+'  '+$('#state').val()+'</strong>');
                     $('#str_country1').replaceWith('<strong id="str_country1">'+$('#country').val()+'</strong>');
                     $('#str_zipcode1').replaceWith('<strong id="str_zipcode1">'+$('#zipcode').val()+'</strong>');*/
                    $('#str_street_address2').replaceWith('<strong id="str_street_address2">' + $('#lys_street_address').val() + '</strong>');
                    $('#str_city_state_address2').replaceWith('<strong id="str_city_state_address2">' + $('#city').val() + '  ' + $('#state').val() + '</strong>');
                    $('#str_country2').replaceWith('<strong id="str_country2">' + $('#country').val() + '</strong>');
                    $('#str_zipcode2').replaceWith('<strong id="str_zipcode2">' + $('#zipcode').val() + '</strong>');
                })
                $('.disable-btn').click(function ()
                {
                    alert('Please enter the data');
                })
                $('#pin-on-map').click(function ()
                {
                    $('#address_popup2').hide();
                    $('#address_popup3').show();
                    $('.disable_finish').show();
                    $('.enable_finish').hide();

                    if ($('#hidden_lat').val() == '')
                    {
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/get_address"; ?>',
                            type: 'POST',
                            dataType: 'json',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $.each(data, function (key, value)
                                {
                                    city = value['city'];
                                    $('#city').val(city);
                                    state = value['state'];
                                    $('#state').val(state);
                                    country = value['country'];
                                    $('#country').val(country);
                                    $('#hidden_lat').val(value['lat']);
                                    $('#hidden_lng').val(value['long']);
                                    $('#zipcode').val(value['zip_code']);
                                    $('#lys_street_address').val(value['street_address']);
                                    initialize();
                                })
                            }
                        })
                    } else {
                        initialize();
                    }


                })
                $('#hosting_bed_type').change(function ()
                {
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/add_bed_type"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>, bed_type: $(this).val()},
                        success: function (data)
                        {

                        }
                    });
                })

                $('#finish_popup3').click(function ()
                {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url() . "rooms/add_address"; ?>',
                        data: {room_id: <?php echo $room_id; ?>, country: $('#country').val(), city: $('#city').val(), state: $('#state').val(), street_address: $('#lys_street_address').val(), optional_address: $('#apt').val(), zipcode: $('#zipcode').val(), lat: $('#hidden_lat').val(), lng: $('#hidden_lng').val(), full_address: $('#hidden_address').val()},
                        success: function (data)
                        {
                            $('#str_street_address').replaceWith('<strong id="str_street_address">' + $('#lys_street_address').val() + '</strong>');
                            $('#str_city_state_address').replaceWith('<strong id="str_city_state_address">' + $('#city').val() + '  ' + $('#state').val() + '</strong>');
                            $('#str_country').replaceWith('<strong id="str_country">' + $('#country').val() + '</strong>');
                            $('#str_zipcode').replaceWith('<strong id="str_zipcode">' + $('#zipcode').val() + '</strong>');
                            $('#str_street_address1').replaceWith('<strong id="str_street_address1">' + $('#lys_street_address').val() + '</strong>');
                            $('#str_city_state_address1').replaceWith('<strong id="str_city_state_address1">' + $('#city').val() + '  ' + $('#state').val() + '</strong>');
                            $('#str_country1').replaceWith('<strong id="str_country1">' + $('#country').val() + '</strong>');
                            $('#str_zipcode1').replaceWith('<strong id="str_zipcode1">' + $('#zipcode').val() + '</strong>');
                            $('#str_street_address2').replaceWith('<strong id="str_street_address2">' + $('#lys_street_address').val() + '</strong>');
                            $('#str_city_state_address2').replaceWith('<strong id="str_city_state_address2">' + $('#city').val() + '  ' + $('#state').val() + '</strong>');
                            $('#str_country2').replaceWith('<strong id="str_country2">' + $('#country').val() + '</strong>');
                            $('#str_zipcode2').replaceWith('<strong id="str_zipcode2">' + $('#zipcode').val() + '</strong>');
                            // $('#hidden_lat').val($('#hidden_lat').val());
                            // $('#hidden_lng').val($('#hidden_lng').val());
                        }
                    });
                    $('#address_popup3').hide();
                    address_status = 1;
                    $('#address_before').hide();
                    $('#addr_plus_after_white').show();
                    $('#add_address').hide();
                    $('#add_content').hide();
                    $('#after_address').show();
                    lat = $('#hidden_lat').val();
                    lng = $('#hidden_lng').val();

                    $('#static_map').replaceWith('<img id="static_map" src="http://maps.googleapis.com/maps/api/staticmap?center=' + lat + ',' + lng + '&size=370x277&zoom=15&format=png&markers=color:red|label:|' + lat + ',' + lng + '&sensor=false&maptype=roadmap&style=feature:water|element:geometry.fill|weight:3.3|hue:0x00aaff|lightness:100|saturation:93|gamma:0.01|color:0x5cb8e4">');

                    $.ajax({
                        url: '<?php echo base_url() . "rooms/ajax_circle_map"; ?>',
                        type: 'POST',
                        data: {lat: lat, lng: lng},
                        success: function (data)
                        {
                            $('#static_circle_map').show();
                            $('#static_circle_map_img').replaceWith('<img width="210" height="210" id="static_circle_map_img" src="' + data + '">');
                        }
                    })

                    var total_status = 0;
                    total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                    var final_status = 6 - total_status;
                    $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                    if (final_status == 0)
                    {
                        $.ajax({
                            url: '<?php echo base_url() . "rooms/final_step"; ?>',
                            type: 'POST',
                            data: {room_id: <?php echo $room_id; ?>},
                            success: function (data)
                            {
                                $('#steps_count').hide();
                                $('#list_space').show();
                                $('#list-button').rotate3Di(720, 750);
                            }
                        })

                    }
                })

                $('#detail_side').click(function ()
                {
                    $('#overview_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#price_container').hide();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#address_side').hide();
                    $('#address_after').hide();
                    $('#amenities_after').hide();
                    $('#amenities').show();
                    $('#main_entire_right').hide();
                    $("#price-right-hover").hide();
                    $('#overview-text-right').hide();
                    $('#summary-text-hover').hide();
                    $('#static_circle_map').hide();
                    $('#terms_container').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#detail_container').show();
                    $('#detail_side').hide();
                    $('#detail_side_after').show();
                    $('#cal_container').hide();

                    if (calendar == 0)
                    {
                        $('#cal').hide();
                        $('#cal1').show();
                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();
                    }
                    if (overview_status == 0)
                    {
                        $('#overview').show();
                        $('#overview_after').hide();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }

                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }

                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    if ('<?php echo $house_rule; ?>' != '')
                    {
                        $('#detail_plus1').hide();
                    }

                    $('#terms_side').show();
                    $('#terms_side_after').hide();

                })

                $('#house_rules_textbox').focusout(function ()
                {
                    var house_rules = $.trim($(this).val());

                    $.ajax({
                        url: '<?php echo base_url() . "rooms/house_rules"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>, house_rules: house_rules},
                        success: function (data)
                        {
                            detail_status = 1;

                            $('#detail_saving').fadeIn();
                            $('#detail_saving').fadeOut();

                            if (house_rules == '')
                            {
                                detail_status = 0;
                                $('#detail_plus1').show();
                            }
                            else
                            {
                                $('#detail_plus1').hide();
                            }
                        }
                    })
                })

                $("#house_rules_textbox").mouseover(function () {

                    $("#ded").show();

                });
                if ('<?php echo $house_rule; ?>' != '')
                {
                    $('#detail_plus').hide();
                }
                $('#terms_side').click(function ()
                {
                    $('#cal_container').hide();
                    $('#terms_container').show();

                    $('#overview_entire').hide();
                    $('#amenities_entire').hide();
                    $('#listing_entire').hide();
                    $('#price_container').hide();
                    $('#photos_container').hide();
                    $('#address_entire').hide();
                    $('#address_right').hide();
                    $('#address_side').hide();
                    $('#address_after').hide();
                    $('#amenities_after').hide();
                    $('#amenities').show();
                    $('#main_entire_right').hide();
                    $("#price-right-hover").hide();
                    $('#overview-text-right').hide();
                    $('#summary-text-hover').hide();
                    $('#static_circle_map').hide();
                    $('#cleaning-price-right').hide();
                    $('#additional-price-right').hide();

                    $('#detail_container').hide();
                    $('#detail_side').show();
                    $('#detail_side_after').hide();
                    $('#cal_container').hide();
                    $('#terms_side').hide();
                    $('#terms_side_after').show();

                    if (calendar == 0)
                    {
                        $('#cal').hide();
                        $('#cal1').show();
                    }
                    else
                    {
                        $('#cal').hide();
                        $('#cal_after').show();
                        $('#cal1').hide();
                    }
                    if (overview_status == 0)
                    {
                        $('#overview').show();
                        $('#overview_after').hide();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    if (price_status == 1)
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus').hide();
                        $('#des_plus_after').show();
                        night_price = $('#night_price').val();

                        var total_status = 0;
                        total_status = calendar + price_status + address_status + listing_status + photo_status + overview_status;
                        var final_status = 6 - total_status;
                        if (final_status != 0)
                        {
                            $('#steps').replaceWith('<span id="steps">' + final_status + ' <?php echo translate('steps'); ?></span>');
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/final_step"; ?>',
                                type: 'POST',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $('#list_space').hide();
                                    $('#steps_count').show();
                                }
                            })

                        }
                        else if (final_status == 0)
                        {
                            $('#list_space').show();
                            $('#steps_count').hide();
                        }

                    }
                    else
                    {
                        $('#price_after').hide();
                        $('#price').show();
                        $('#des_plus_after').hide();
                        $('#des_plus').show();
                    }
                    if (title_status == 1 && summary_status == 1)
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus').hide();
                        $('#over_plus_after1').hide();
                        $('#over_plus_after').show();
                    }
                    else
                    {
                        $('#overview_after').hide();
                        $('#overview').show();
                        $('#over_plus_after').hide();
                        $('#over_plus').show();
                    }
                    if (beds_status == 1 && bathrooms_status == 1)
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                        $('#list_plus').hide();
                        $('#list_plus_after').show();
                    }
                    else
                    {
                        $('#listing').show();
                        $('#listing_after').hide();
                    }

                    if (photo_status == 1)
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_plus').hide();
                        $('#photo_grn').show();
                    }
                    else
                    {
                        $('#photo_after').hide();
                        $('#photo').show();
                        $('#photo_grn').hide();
                        $('#photo_plus').show();
                    }
                    if (address_status == 1)
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                        $('#addr_plus_after_grn').show();
                        $('#address_before').hide();
                        $('#addr_plus').hide();
                    }
                    else
                    {
                        $('#address_after').hide();
                        $('#address_side').show();
                    }
                    if ('<?php echo $house_rule; ?>' != '')
                    {
                        $('#detail_plus1').hide();
                    }
                })

                $('#cancel_policy').change(function ()
                {
                    $.ajax({
                        url: '<?php echo base_url() . "rooms/cancellation_policy"; ?>',
                        type: 'POST',
                        data: {room_id: <?php echo $room_id; ?>, policy: $(this).val()},
                        success: function (data)
                        {
                            $('#terms_plus').hide();
                            $('#terms_plus_after').hide();
                            $('#policy_saving').fadeIn();
                            $('#policy_saving').fadeOut();
                        }
                    })
                })

            });

        </script>

        <script type="text/javascript">

            $(document).ready(function () {

                var input1 = document.getElementById('lys_street_address');
                var autocomplete1 = new google.maps.places.Autocomplete(input1);
                google.maps.event.addListener(autocomplete1, 'place_changed', function () {
                    var place = autocomplete1.getPlace();

                    var lat = place.geometry.location.lat();
                    var lng = place.geometry.location.lng();
                    //alert(lat+','+lng);
                    $.getJSON("http://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + lng + "&sensor=false", function (data) {

                        if (data.status == 'OK')
                        {

                            $('#hidden_lat').val(lat);
                            $('#hidden_lng').val(lng);

                            address = data.results[0].formatted_address;
                            $('#hidden_address').val(address);
                            var state_status = 0;
                            var addr = {};
                            for (var ii = 0; ii < data.results[0].address_components.length; ii++)
                            {
                                // var street_number = route = street = city = state = zipcode = country = formatted_address = '';
                                var types = data.results[0].address_components[ii].types.join(",");
                                if (types == "street_number") {
                                    addr.street_number = data.results[0].address_components[ii].long_name;
                                }
                                if (types == "route" || types == "point_of_interest,establishment") {
                                    addr.route = data.results[0].address_components[ii].long_name;
                                    $('#lys_street_address').val(addr.route);
                                    if (addr.route == '[object HTMLInputElement]')
                                    {
                                        $('#lys_street_address').val('');
                                    }
                                }
                                if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political") {
                                    addr.city = (city == '' || types == "locality,political") ? data.results[0].address_components[ii].long_name : city;
                                    $('#city').val(addr.city);
                                    if (addr.city == '[object HTMLInputElement]')
                                    {
                                        $('#city').val('');
                                    }
                                }
                                if (types == "administrative_area_level_1,political") {
                                    state_status = 1;
                                    addr.state = data.results[0].address_components[ii].long_name;
                                    $('#state').val(addr.state);
                                    if (addr.state == '[object HTMLInputElement]')
                                    {
                                        $('#state').val('');
                                    }
                                }
                                if (state_status != 1)
                                {
                                    $('#state').val('');
                                }
                                if (types == "postal_code" || types == "postal_code_prefix,postal_code") {
                                    addr.zipcode = data.results[0].address_components[ii].long_name;
                                    $('#zipcode').val(addr.zipcode);
                                    if (addr.zipcode == '[object HTMLInputElement]')
                                    {
                                        $('#zipcode').val('');
                                    }
                                }
                                if (types == "country,political") {
                                    addr.country = data.results[0].address_components[ii].long_name;
                                    $("#country option").each(function () {
                                        if ($(this).text() == $.trim(addr.country)) {
                                            $('#country').val(addr.country);
                                        }
                                    });
                                }
                            }
                        }
                        else
                        {
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/get_address"; ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $.each(data, function (key, value)
                                    {
                                        city = value['city'];
                                        $('#city').val(city);
                                        state = value['state'];
                                        $('#state').val(state);
                                        country = value['country'];
                                        $('#country').val(country);
                                        $('#hidden_lat').val(value['lat']);
                                        $('#hidden_lng').val(value['long']);

                                        $('#zipcode').val(value['zip_code']);
                                    })
                                }
                            })
                        }
                    });
                    // if($('#city').val() !='' && $('#lys_street_address').val() != '')
                    //	{
                    $('.next_active').css('opacity', 1);
                    $('.disable-btn').hide();
                    $('.enable-btn').show();
                    //	}
                });
                $('#country').click(function ()
                {
                    $('.next_active').css('opacity', 0.65);
                    $('.disable-btn').show();
                    $('.enable-btn').hide();
                    $('#lys_street_address').val('');
                    $('#city').val('');
                    $('#state').val('');
                    $('#apt').val('');
                    $('#zipcode').val('');
                })

            });

        </script>
        <script>

            function initialize() {
                var latlong = new google.maps.LatLng($('#hidden_lat').val(), $('#hidden_lng').val());
                var marker1;
                var map1;
                var lat;
                var lng;
                var styles = [{
                        "featureType": "water",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {"weight": 3.3},
                            {"hue": "#00aaff"},
                            {"lightness": 100},
                            {"saturation": 93},
                            {"gamma": 0.01},
                            {"color": "#5cb8e4"}
                        ]
                    }
                ];
                var styledMap = new google.maps.StyledMapType(styles,
                        {name: "Styled Map"});

                var mapOptions = {
                    zoom: 13,
                    center: latlong,
                    scrollwheel: false
                };
                map1 = new google.maps.Map(document.getElementById('map-canvas1'), mapOptions);

                map1.setCenter(latlong);

                marker1 = new google.maps.Marker({
                    map: map1,
                    draggable: true,
                    //animation: google.maps.Animation.DROP,
                    position: latlong
                });
                map1.mapTypes.set('map-canvas1', styledMap);
                map1.setMapTypeId('map-canvas1');
                google.maps.event.addListener(marker1, 'dragend', function (event)
                {
                    map1.setCenter(marker1.getPosition());

                    lat = marker1.getPosition().lat();
                    lng = marker1.getPosition().lng();

                    $.getJSON("http://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + lng + "&sensor=true", function (data) {

                        if (data.status == 'OK')
                        {
                            $('.disable_finish').hide();
                            $('.enable_finish').show();

                            $('#hidden_lat').val(lat);
                            $('#hidden_lng').val(lng);

                            address = data.results[0].formatted_address;
                            $('#hidden_address').val(address);
                            var addr = {};
                            for (var ii = 0; ii < data.results[0].address_components.length; ii++)
                            {
                                var street_number = route = street = city = state = zipcode = country = formatted_address = '';
                                var types = data.results[0].address_components[ii].types.join(",");
                                if (types == "street_number") {
                                    addr.street_number = data.results[0].address_components[ii].long_name;
                                }
                                if (types == "route" || types == "point_of_interest,establishment") {
                                    addr.route = data.results[0].address_components[ii].long_name;
                                    $('#lys_street_address').val(addr.route);
                                    if (addr.route == '[object HTMLInputElement]')
                                    {
                                        $('#lys_street_address').val('');
                                    }
                                }
                                if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political") {
                                    addr.city = (city == '' || types == "locality,political") ? data.results[0].address_components[ii].long_name : city;
                                    $('#city').val(addr.city);
                                    if (addr.city == '[object HTMLInputElement]')
                                    {
                                        $('#city').val('');
                                    }
                                }
                                if (types == "administrative_area_level_1,political") {
                                    addr.state = data.results[0].address_components[ii].long_name;
                                    $('#state').val(addr.state);
                                    if (addr.state == '[object HTMLInputElement]')
                                    {
                                        $('#state').val('');
                                    }
                                }
                                if (types == "postal_code" || types == "postal_code_prefix,postal_code") {
                                    addr.zipcode = data.results[0].address_components[ii].long_name;
                                    $('#zipcode').val(addr.zipcode);
                                    if (addr.zipcode == '[object HTMLInputElement]')
                                    {
                                        $('#zipcode').val('');
                                    }
                                }
                                if (types == "country,political") {
                                    addr.country = data.results[0].address_components[ii].long_name;
                                    $("#country option").each(function () {
                                        if ($(this).text() == $.trim(addr.country)) {
                                            $('#country').val(addr.country);
                                        }
                                    });
                                }
                            }
                        }
                        else
                        {
                            $.ajax({
                                url: '<?php echo base_url() . "rooms/get_address"; ?>',
                                type: 'POST',
                                dataType: 'json',
                                data: {room_id: <?php echo $room_id; ?>},
                                success: function (data)
                                {
                                    $.each(data, function (key, value)
                                    {
                                        city = value['city'];
                                        $('#city').val(city);
                                        state = value['state'];
                                        $('#state').val(state);
                                        country = value['country'];
                                        $('#country').val(country);
                                        $('#hidden_lat').val(value['lat']);
                                        $('#hidden_lng').val(value['long']);
                                        $('#zipcode').val(value['zip_code']);

                                    })
                                }
                            })

                        }
                    });


                });
            }
        </script>	
        <style>
            html, body, #map-canvas1 {
                height: 100%;
                margin: 0px;
                padding: 0px;
            }
            #map-canvas1{
                width:479px;
                height: 300px;
            }
        </style>
        <script type="text/javascript">

            $(document).ready(function () {
                $("#week_price").mouseover(function () {
                    $("#summary-price-right").show();
                    $("#price_right").hide();
                    $("#cleaning-price-right").hide();
                    $("#additional-price-right").hide();
                });
                $("#night_price").mouseover(function () {
                    $("#summary-price-right").hide();
                    $("#price_right").show();
                    $("#cleaning-price-right").hide();
                    $("#additional-price-right").hide();
                });
                $("#month_price").mouseover(function () {
                    $("#summary-price-right").show();
                    $("#price_right").hide();
                    $("#cleaning-price-right").hide();
                    $("#additional-price-right").hide();
                });
                $("#js-cleaning-fee").mouseover(function () {
                    $("#cleaning-price-right").show();
                    $("#price_right").hide();
                    $("#summary-price-right").hide();
                    $("#additional-price-right").hide();
                });
                $("#js-additional-guests").mouseover(function () {
                    $("#cleaning-price-right").hide();
                    $("#price_right").hide();
                    $("#summary-price-right").hide();
                    $("#additional-price-right").show();
                });

                $("#title").mouseover(function () {
                    $("#overview-text-right").show();
                    $("#summary-text-hover").hide();
                });
                $("#summary").mouseover(function () {
                    $("#overview-text-right").hide();
                    $("#summary-text-hover").show();
                });

            });
        </script>
        <script>
            function isNumberKey(evt) {
                var charCode = (evt.which) ? evt.which : event.keyCode
                if (charCode > 31 && (charCode < 48 || charCode > 57))
                    return false;
                return true;
            }

            $(window).scroll(function () {
                if ($(window).scrollTop() >= 20) {
                    $('.header_bottom_nav').addClass('fixed-top');
                }
                else {
                    $('.header_bottom_nav').removeClass('fixed-top');
                }
            });

            $(window).scroll(function () {
                if ($(window).scrollTop() >= 30) {
                    $('#sidebar_main_entire').addClass('fixed-left');
                }
                else {
                    $('#sidebar_main_entire').removeClass('fixed-left');
                }
            });

        </script>
    </head>
    <body>
        <input type='hidden' id="currency_hidden" value=""/>
        <input type='hidden' id="currency_symbol_hidden" value=""/>
        <div id="mystick_head" class="header_bottom_nav">
            <div class="left_lys_ent">
                <a class="Entire_Left" href="<?php echo base_url() . 'hosting'; ?>"></a>
                <span class="eelt"></span>
<?php $ori_room_type = $this->db->select('room_type')->where('id', $room_id)->get('list')->row()->room_type; ?>
                <span id="title_header"><?php if ($lys_status->title == 1) echo $room_type;
    else echo translate("$ori_room_type"); ?></span>

            </div>
            <a class="Entire_Right" target="_blank" href="<?php echo base_url() . 'rooms/' . $room_id; ?>">
                <div class="entright">
                    <span class="entrt"></span>
                    <span><?php echo translate('Preview'); ?></span>
                </div>
            </a>
        </div>

        <div id="sidebar_main_entire" class="main_entire">
            <div class="main_entire_inner">
                <div class="entire_contain_bottom">
                    <h4><?php echo translate('BASICS'); ?></h4>
                    <p class="entire_title active_entire" id="cal"><img src="<?php echo base_url(); ?>images/calender_hv.png" /> <?php echo translate('Calendar'); ?>
                        <img class="plus_hv" id="cal_plus" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                        <img class="plus_hv" id="cal_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
                    </p>
                    <p class="entire_title" id="cal_after" style="display: none"><img src="<?php echo base_url(); ?>images/calender.png" /> <?php echo translate('Calendar'); ?> 
                        <img class="plus_hv" src="<?php echo base_url(); ?>images/tick_grn.png" />
                    </p>
                    <p class="entire_title" id="cal1" style="display: none"><img src="<?php echo base_url(); ?>images/calender.png" /> <?php echo translate('Calendar'); ?> 
                        <img class="plus_hv" src="<?php echo base_url(); ?>images/plus_normal.png" />
                    </p>
                    <p class="entire_title" id="cal1_after" style="display: none"><img src="<?php echo base_url(); ?>images/calender.png" /> <?php echo translate('Calendar'); ?> 
                        <img class="plus_hv" src="<?php echo base_url(); ?>images/tick_grn.png" />
                    </p>
                    <p class="entire_title" id="price"><img src="<?php echo base_url(); ?>images/star_pricing.png" /> <?php echo translate('Pricing'); ?> 
                        <img class="plus_hv" id="des_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                        <img class="plus_hv" id="des_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
                    </p>
                    <p class="entire_title active_entire" id="price_after" style="display: none"><img src="<?php echo base_url(); ?>images/star_pricing_hv.png" /> <?php echo translate('Pricing'); ?> 
                        <img class="plus_hv" id="price_plus" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                        <img class="plus_hv" id="price_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />

                    </p>
                </div>
                <div class="entire_contain_bottom">
                    <h4><?php echo translate('DESCRIPTION'); ?></h4>
                    <p class="entire_title" id="overview"><img style="margin-top:3px" src="<?php echo base_url(); ?>images/overview.png" /> <?php echo translate('Overview'); ?> 
                        <img class="plus_hv" id="over_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                        <img class="plus_hv" id="over_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
                    </p>       
                    <p class="entire_title active_entire" id="overview_after" style="display: none"><img src="<?php echo base_url(); ?>images/overview_hv.png" /> <?php echo translate('Overview'); ?>  
                        <img class="plus_hv" id="over_plus1" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                        <img class="plus_hv" id="over_plus_after1" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
                    </p>
<?php
    if ($lys_status_count == 6)
    {
        ?>
                            <p class="entire_title" id="detail_side"><img style="margin-top:0px" src="<?php echo base_url(); ?>images/detail.png" /> <?php echo translate('Detail'); ?> 
                                <img class="plus_hv" id="detail_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                            </p>    
                            <p class="entire_title active_entire" id="detail_side_after" style="display: none"><img src="<?php echo base_url(); ?>images/detail_hv.png" /> <?php echo translate('Detail'); ?>  
                                <img class="plus_hv" id="detail_plus1" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                            </p>
    <?php } ?> 
                    <p class="entire_title" id="detail_side_2" style="display: none"><img style="margin-top:0px" src="<?php echo base_url(); ?>images/detail.png" /> <?php echo translate('Detail'); ?> 
                        <img class="plus_hv" id="detail_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                    </p>  
                    <p class="entire_title" id="photo"><img src="<?php echo base_url(); ?>images/photos.png" /> <?php echo translate('Photos'); ?>  
                        <img class="plus_hv" id="photo_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                        <img class="plus_hv" id="photo_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
                        <img class="plus_hv" id="photo_grn" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
                    </p>
                    <p class="entire_title active_entire" id="photo_after" style="display: none"><img src="<?php echo base_url(); ?>images/photos_hv.png" /> <?php echo translate('Photos'); ?> 
                        <img class="plus_hv" id="photo_plus_white" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                        <img class="plus_hv" id="photo_grn_white" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
                    </p>
                </div>
                <div class="entire_contain_bottom">
                    <h4><?php echo translate('SETTINGS'); ?></h4>
                    <p class="entire_title" id="amenities"><img src="<?php echo base_url(); ?>images/entire_amenities.png" /> <?php echo translate('Amenities'); ?></p>
                    <p class="entire_title active_entire" id="amenities_after" style="display: none"><img src="<?php echo base_url(); ?>images/entire_amenities_hv.png" /> <?php echo translate('Amenities'); ?>
                    </p>
                    <p class="entire_title" id="address_side"><img src="<?php echo base_url(); ?>images/entire_address.png" /> <?php echo translate('Address'); ?> 
                        <img class="plus_hv" id="addr_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                        <img class="plus_hv" id="addr_plus_after_grn" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
                    </p>
                    <p class="entire_title active_entire" id="address_after" style="display: none"><img src="<?php echo base_url(); ?>images/entire_address_hv.png" /> <?php echo translate('Address'); ?> 
                        <img class="plus_hv" id="address_before" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                        <img class="plus_hv" id="addr_plus_after_white" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
                    </p>
                    <?php if ($lys_status_count == 6)
                        {
                            ?>
                            <p class="entire_title" id="terms_side"><img style="margin-top:-3px" src="<?php echo base_url(); ?>images/terms.png" /> <?php echo translate('Terms'); ?> 
                                <img class="plus_hv" id="terms_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                            </p>   
                            <p class="entire_title active_entire" id="terms_side_after" style="display: none"><img style="margin-top:-3px" src="<?php echo base_url(); ?>images/terms_hv.png" /> <?php echo translate('Terms'); ?> 
                                <img class="plus_hv" id="terms_plus_after" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                            </p>
    <?php } ?>	
                    <p class="entire_title" id="terms_side_2" style="display: none"><img style="margin-top:-3px" src="<?php echo base_url(); ?>images/terms.png" /> <?php echo translate('Terms'); ?> 
                        <img class="plus_hv" id="terms_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                    </p>   
                    <p class="entire_title" id="listing"><img style="margin-top:-3px" src="<?php echo base_url(); ?>images/entire_listing.png" /> <?php echo translate('Listing'); ?> 
                        <img class="plus_hv" id="list_plus" src="<?php echo base_url(); ?>images/plus_normal.png" />
                        <img class="plus_hv" id="list_plus_after" style="display: none" src="<?php echo base_url(); ?>images/tick_grn.png" />
                    </p>         
                    <p class="entire_title active_entire" id="listing_after" style="display: none"><img style="margin-top:-3px" src="<?php echo base_url(); ?>images/entire_listing_hv.png" /> <?php echo translate('Listing'); ?> 
                        <img class="plus_hv" id="listing_plus1" src="<?php echo base_url(); ?>images/plus_normal_hv.png" />
                        <img class="plus_hv" id="listing_plus_after1" style="display: none" src="<?php echo base_url(); ?>images/tick.png" />
                    </p>
                </div>
            </div>
            <div class="entire_count" id="steps_count">
                <p><?php echo translate('Complete'); ?> <span id="steps">6 <?php echo translate('steps'); ?></span> <?php echo translate('to'); ?> <br/> <?php echo translate('list your space'); ?></p>
            </div>
            <div style="display:none;" class="entire_count-button" id="list_space">
                <p>
                    <button id="list-button" class="btn-special btn-yellow-list" style="">
<?php echo translate('List Space'); ?>
                    </button>
                </p>
            </div>
        </div>

        <div class="center_entire" id="cal_container">
            <div class="contain_calender" id="calendar_first">
                <div class="calendarHeader">
                    <h4 class="calendar-h4">
                        <a class="calen_left" href="#"><i class="icon icon-chevron-left">></i></a>
                        <div class="cc" id="calendar_top"><?php echo translate('Calendar'); ?></div>
                        <a class="calen_right" href="#"><i class="icon icon-chevron-left"><</i></a>
                    </h4>
                </div>
                <div id="calendar-tick" class="calendarHeaderBorder"></div> 
                <div class="center_entire_left">
                    <div class="calender_row">

                        <h3><?php echo translate('When is your listing available?'); ?></h3>
                        <div class="calender_div">
                            <!--   <div id="home-1" class="calender_left_img">
                               <a class="myButtonLink" href="#"></a>
                               <a class="myButtonLink_after" style="display: none;background: url<?php //echo base_url().'images/tick-hover.png';  ?>) no-repeat;" href="#LinkURL"></a>
                                  <p class="calender_list">Always</p>
                                  <p class="calender_content">List all dates as available</p>
                               </div>-->
                            <div id="home-2" class="calender_left_img">
                                <a class="myButtonLink"></a>
                                <a class="myButtonLink_after" style="display: none;background: url(<?php echo base_url() . 'images/cal-hover.png'; ?>) no-repeat;"></a>
                                <p class="calender_list"><?php echo translate('Sometimes'); ?></p>
                                <p class="calender_content"><?php echo translate('List all dates as available'); ?></p>
                            </div>
                            <!--  <div id="home-3" class="calender_left_img">
                                  <a class="myButtonLink" href="#LinkURL"></a>
                                  <a class="myButtonLink_after" style="display: none;background: url<?php //echo base_url().'images/tick-hover.png';  ?>) no-repeat;" href="#LinkURL"></a>
                                  <p class="calender_list">One Time</p>
                                  <p class="calender_content">List only one time period as available</p>
                            </div>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="contain_calender"  id="always" style="display: none">
                <div class="calendarHeader">
                    <h4>
                        <a class="calen_left" href="#"><img src="<?php echo base_url(); ?>images/calender_left_arrow.png" /></a>
                        <div class="cc" id="calendar_always">Calendar</div>
                        <a class="calen_right" href="#"><img src="<?php echo base_url(); ?>images/calender_right_arrow.png" /></a>
                    </h4>
                </div>
                <div id="calendar-tick" class="calendarHeaderBorder"></div> 
                <div class="center_entire_left">
                    <div class="calender_row">

                        <div class="calender_div">
                            <div class="calender-always">
                                <img src="<?php echo base_url(); ?>images/calender_list.png" />
                                <h3><?php echo translate('Always Available'); ?></h3>
                                <p class="available"><?php echo translate('This is your calendar! After listing your space, return here to update your availability.'); ?></p>
                                <p class="choose_again" id="back_always"><img src="<?php echo base_url(); ?>images/left-arrow.png" /> <?php echo translate('CHOOSE AGAIN'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contain_calender" id="one_time" style="display: none">
                <div class="calendarHeader">
                    <h4>
                        <a class="calen_left" href="#"><img src="<?php echo base_url(); ?>images/calender_left_arrow.png" /></a>
                        <div class="cc" id="calendar_one"><?php echo translate('Calendar'); ?></div>
                        <a class="calen_right" href="#"><img src="<?php echo base_url(); ?>images/calender_right_arrow.png" /></a>
                    </h4>
                </div>
                <div class="center_entire_left">
                    <div class="calender_row">

                        <div class="calender_div">
                            <div class="calender-always">
                                <img src="<?php echo base_url(); ?>images/calender_list.png" />
                                <h3><?php echo translate('One Time Available'); ?></h3>
                                <p class="listing-date"><?php echo translate('Select the dates your listing is available.'); ?></p>
                                <div class="date-pic">
                                    <input type="text" value="Start Date" class="start-date" /> <span>to</span> <input type="text" value="End Date" class="start-date" /> <input type="text" value="Save" class="save" />
                                </div>
                                <p class="available"><?php echo translate('After listing your space, return here to set custom prices and availability.'); ?></p>
                                <p class="choose_again" id="back_one"><img src="<?php echo base_url(); ?>images/left-arrow.png" /> <?php echo translate('CHOOSE AGAIN'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="contain_calender" id="some_times" style="display: none">
                <div class="calendarHeader">
                    <h4>
                        <a class="calen_left" href="#"><i class="icon icon-chevron-left">></i></a>
                        <div class="cc" id="calendar_some"><?php echo translate('Calendar'); ?></div>
                        <a class="calen_right" href="#"><i class="icon icon-chevron-left">></i></a>
                    </h4>
                </div>
                <div class="calendarHeaderBorder" id="calendar-tick"></div>
                <div class="center_entire_left">
                    <div class="calender_row">

                        <div class="calender_div">
                            <div class="calender-always">
                                <img src="<?php echo base_url(); ?>images/calender_list.png" />
                                <h3><?php echo translate('Some times Available'); ?></h3>
                                <p class="available"><?php echo translate('This is your calendar! After listing your space, return here to update your availability.'); ?></p>
                                <p class="choose_again" id="back_some"><img src="<?php echo base_url(); ?>images/left-arrow.png" /><?php echo translate('CHOOSE AGAIN'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="center_entire" id="price_container" style="display: none">
            <div class="js-standard-price">
                <div style="height:10px;">
                    <div style="display:none;" class="js-saving-progress saving-progress" id="price_saving">
                        <h5><?php echo translate('saving...'); ?></h5>
                    </div>
                </div>
                <div style="" class="center_baseprice center_entire_left">
                    <h3 style="font-weight:bold;"><?php echo translate('Base Price'); ?></h3>
                    <p><?php echo translate('The base nightly price and default currency for your listing.'); ?></p>
                </div>
                <div style="" class="center_night center_entire_left">
                    <p><?php echo translate('Per night'); ?></p>
                    <div class="input-addon">
                        <span id="currency_symbol"  class="input-prefix-curency"><b>$</b></span>
                        <input class="night" type="text" id="night_price" value="<?php if ($lys_status->price == 1) echo $price; ?>"/>
                        <input type="hidden" id="hidden_price"/>
                    </div>
                    <p data-error="price" class="ml-error" id="small_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too low. The minimum is 10.'); ?></p>
                    <p data-error="price" class="ml-error" id="large_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too long. The maximum is 10000.'); ?></p>
                    <div class="suggest"><?php echo translate('suggested'); ?></div>
                    <div class="currency_entire"><?php echo translate('Currency'); ?></div>
                    <select class="currency_price" id="currency_drop" style="height: 37px;">
                        <option value="USD" <?php if ($currency == 'USD') echo 'selected'; ?>>USD</option>
                        <option value="GBP" <?php if ($currency == 'GBP') echo 'selected'; ?>>GBP</option>
                        <option value="EUR" <?php if ($currency == 'EUR') echo 'selected'; ?>>EUR</option>
                        <option value="AUD" <?php if ($currency == 'AUD') echo 'selected'; ?>>AUD</option>
                        <option value="SGD" <?php if ($currency == 'SGD') echo 'selected'; ?>>SGD</option>
                        <option value="SEK" <?php if ($currency == 'SEK') echo 'selected'; ?>>SEK</option>
                        <option value="DKK" <?php if ($currency == 'DKK') echo 'selected'; ?>>DKK</option>
                        <option value="MXN" <?php if ($currency == 'MXN') echo 'selected'; ?>>MXN</option>
                        <option value="BRL" <?php if ($currency == 'BRL') echo 'selected'; ?>>BRL</option>
                        <option value="MYR" <?php if ($currency == 'MYR') echo 'selected'; ?>>MYR</option>
                        <option value="PHP" <?php if ($currency == 'PHP') echo 'selected'; ?>>PHP</option>
                        <option value="CHF" <?php if ($currency == 'CHF') echo 'selected'; ?>>CHF</option>
                    </select>
                </div>
            </div>
            <hr style="" class="hr_center">
            <?php
                if ($this->uri->segment(3) != 'edit')
                {
                    ?>
                    <div  id="advance_price1" class="longer-stays">
                        <p id="advance_price1"><?php echo translate('Want to offer a discount for longer stays?'); ?> 
                            <span class="link_color" id="advance_price"><?php echo translate('You can also set weekly and monthly prices.'); ?></span></p>
                    </div>
                        <?php } ?>
<?php
    if ($this->uri->segment(3) != 'edit')
    {
        ?>
                    <div style="display: none;margin-top:9px;" class="center_baseprice center_entire_left" id="advance_price_after">
                    <?php }
                    else
                    {
                        ?>
                        <div style="margin-top:9px;" class="center_baseprice center_entire_left" id="advance_price_after">
                            <?php } ?>
                    <h3 style="font-weight:bold;"><?php echo translate('Long-Term Prices'); ?></h3>
                    <p><?php echo translate('Offer discounted prices for stays one week or longer.'); ?></p>
                </div>
<?php
    if ($this->uri->segment(3) != 'edit')
    {
        ?>
                        <div style="display: none;" class="center_night center_entire_left" id="advance_price_after1">
    <?php }
    else
    {
        ?>
                            <div class="center_night center_entire_left" id="advance_price_after1">
    <?php } ?>
                        <div style="height:10px;">
                            <div style="display:none;" class="js-saving-progress saving-progress" id="advance_price_saving">
                                <h5><?php echo translate('saving...'); ?></h5>
                            </div>
                        </div>
                        <p><?php echo translate('Per Week'); ?></p>
                        <div class="input-addon">
                            <span id="currency_symbol" class="input-prefix-curency"><b>$</b></span>
                            <input class="night" type="text" id="week_price" onkeypress="return isNumberKey(event)" value="<?php if ($lys_status->price == 1 && $week_price != 0) echo $week_price; ?>"/>
                        </div>
                        <p data-error="price" class="ml-error" id="small_week_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too low. The minimum is 70.'); ?></p>
                        <p data-error="price" class="ml-error" id="large_week_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too long. The maximum is 14000.'); ?></p>
                        <div class="suggest"><?php echo translate('suggested'); ?></div>
                        <p class="reservation" style="clear:both;"><?php echo translate('If set, this price applies to any reservation 7 nights or longer.'); ?> </p>
                        <div class="per-month-margin">
                            <div style="height:10px;">
                                <div style="display:none;" class="js-saving-progress saving-progress">
                                    <h5><?php echo translate('saving...'); ?></h5>
                                </div></div>
                            <p><?php echo translate('Per Month'); ?></p>
                            <div class="input-addon">
                                <span id="currency_symbol" class="input-prefix-curency"><b>$</b></span>
                                <input class="night" type="text" id="month_price" onkeypress="return isNumberKey(event)" value="<?php if ($lys_status->price == 1 && $month_price != 0) echo $month_price; ?>" />
                            </div>
                            <p data-error="price" class="ml-error" id="small_month_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too low. The minimum is 300.'); ?></p>
                            <p data-error="price" class="ml-error" id="large_month_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too long. The maximum is 60000.'); ?></p>
                            <p class="reservation" style="clear:both;"><?php echo translate('If set, this price applies to any reservation 28 nights or longer.'); ?> </p>
                        </div>
                    </div>

                    <!-- price -->

<?php if ($this->uri->segment(3) == 'edit')
    {
        ?>
                            <hr class="hr_center" style="">
                            <div id="additional_price_container">
                                <div style="height:10px;">
                                    <div style="display:none;" class="js-saving-progress saving-progress" id="clean_price_saving">
                                        <h5><?php echo translate('saving...'); ?></h5>
                                    </div>
                                </div>
                                <div class="listing_container">
                                    <div class="control-left" style="width:155px;">
                                        <h2>Additional Charges</h2>
                                        <p class="common_text">These charges are added to the reservation total.</p>
                                    </div>
                                    <div class="control-right">

                                        <div class="col-8">


                                            <div id="js-cleaning-fee" class="row-space-3 js-tooltip-trigger">
                                                <label for="listing_cleaning_fee_native_checkbox" class="label-large">
                                                    <input type="checkbox" data-extras="true" name="cleaning_fees" id="listing_cleaning_fee_native_checkbox">
                                                    Cleaning Fee
                                                </label>
                                                <div id="clean_textbox" class="hide" style="display: none">
                                                    <div class="row row-table row-space-1">
                                                        <div class="col-4 col-middle">
                                                            <div class="input-addon">
                                                                <span class="input-prefix" id="clean_currency"><?php echo $currency_symbol; ?></span>
                                                                <input type="text" id="cleaning_price" data-extras="true" value="<?php echo $cleaning_fee; ?>" onkeypress="return isNumberKey(event)" name="listing_cleaning_fee_native" class="autosubmit-text input-stem input-large">
                                                            </div>
                                                            <p data-error="price" class="ml-error" id="small_clean_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too low. The minimum is 5.'); ?></p>
                                                            <p data-error="price" class="ml-error" id="large_clean_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too long. The maximum is 300.'); ?></p>    
                                                        </div>
                                                        <div class="col-8 col-middle">

                                                        </div>
                                                    </div>

                                                    <p data-error="extras_price" class="ml-error hide"></p>
                                                </div>
                                            </div>


                                            <!--<div id="js-weekend-pricing" class="row-space-3 js-tooltip-trigger">
                                              <label for="listing_weekend_price_native_checkbox" class="label-large">
                                                <input type="checkbox" data-extras="true" id="listing_weekend_price_native_checkbox">
                                                Weekend Pricing
                                              </label>
                                            
                                              <div data-checkbox-id="listing_weekend_price_native_checkbox" class="hide">
                                                <div class="row row-table row-space-1">
                                                  <div class="col-4 col-middle">
                                                    <div class="input-addon">
                                                      <span class="input-prefix">₱</span>
                                                      <input type="text" data-extras="true" value="" name="listing_weekend_price_native" class="autosubmit-text input-stem input-large">
                                                    </div>
                                                  </div>
                                                  <div class="col-8 col-middle">
                                                    
                                                  </div>
                                                </div>
                                            
                                                <p class="text-muted">
                                                  Price is <strong>per night</strong> and applied to every Friday and Saturday in your calendar.
                                                </p>
                                              </div>
                                            </div>-->


                                            <div id="js-additional-guests" class="row-space-3 js-tooltip-trigger">
                                                <label for="price_for_extra_person_checkbox" class="label-large">
                                                    <input type="checkbox" data-extras="true" id="price_for_extra_person_checkbox">
                                                    Additional Guests
                                                </label>

                                                <div id="additional_textbox" class="hide" style="display: none">
                                                    <div class="row row-space-1 row-condensed price_additional">
                                                        <div class="col-4 price_add_1">
                                                            <div class="input-addon">
                                                                <span class="input-prefix" id="additional_currency"><?php echo $currency_symbol; ?></span>
                                                                <input type="text" data-extras="true" value="<?php echo $extra_guest_price; ?>" id="extra_guest_price" onkeypress="return isNumberKey(event)" name="listing_price_for_extra_person_native" class="autosubmit-text input-stem input-large">
                                                            </div>
                                                        </div>
                                                        <div class="col-5 text-right price_add_2">
                                                            <label class="label-large" style="padding-top: 11px;">For each guest after</label>
                                                        </div>
                                                        <div class="col-3">
                                                            <div id="guests-included-select"><div class="select
                                                                                                  select-large
                                                                                                  select-block">
                                                                    <select name="guests_included" id="extra_guest_count" class="addi_price_select">

                                                                        <option value="1" <?php if ($guest_count == 1) echo 'selected'; ?>>1</option>

                                                                        <option value="2" <?php if ($guest_count == 2) echo 'selected'; ?>>2</option>

                                                                        <option value="3" <?php if ($guest_count == 3) echo 'selected'; ?>>3</option>

                                                                        <option value="4" <?php if ($guest_count == 4) echo 'selected'; ?>>4</option>

                                                                        <option value="5" <?php if ($guest_count == 5) echo 'selected'; ?>>5</option>

                                                                        <option value="6" <?php if ($guest_count == 6) echo 'selected'; ?>>6</option>

                                                                        <option value="7" <?php if ($guest_count == 7) echo 'selected'; ?>>7</option>

                                                                        <option value="8" <?php if ($guest_count == 8) echo 'selected'; ?>>8</option>

                                                                        <option value="9" <?php if ($guest_count == 9) echo 'selected'; ?>>9</option>

                                                                        <option value="10" <?php if ($guest_count == 10) echo 'selected'; ?>>10</option>

                                                                        <option value="11" <?php if ($guest_count == 11) echo 'selected'; ?>>11</option>

                                                                        <option value="12" <?php if ($guest_count == 12) echo 'selected'; ?>>12</option>

                                                                        <option value="13" <?php if ($guest_count == 13) echo 'selected'; ?>>13</option>

                                                                        <option value="14" <?php if ($guest_count == 14) echo 'selected'; ?>>14</option>

                                                                        <option value="15" <?php if ($guest_count == 15) echo 'selected'; ?>>15</option>

                                                                        <option value="16" <?php if ($guest_count == 16) echo 'selected'; ?>>16+</option>

                                                                    </select>
                                                                </div>
                                                                <p data-error="price" class="ml-error" id="small_additional_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price for each extra person too low. The minimum is 5.'); ?></p>
                                                                <p data-error="price" class="ml-error" id="large_additional_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price for each extra person is too high. The maximum is 300.'); ?></p>    

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p data-error="price_for_extra_person" class="ml-error hide">

                                                    </p>
                                                    <p class="text-muted">
                                                        per person per night
                                                    </p>
                                                </div>
                                            </div>


                                            <div class="row-space-3">
                                                <label for="listing_security_deposit_native_checkbox" class="label-large">
                                                    <input type="checkbox" data-extras="true" id="listing_security_deposit_native_checkbox">
                                                    Security Deposit
                                                </label>

                                                <div id="security_textbox" class="hide" style="display: none">
                                                    <div class="row row-space-1">
                                                        <div class="col-4">
                                                            <div class="input-addon">
                                                                <span class="input-prefix" id="security_currency"><?php echo $currency_symbol; ?></span>
                                                                <input type="text" data-extras="true" id="security_price_textbox" value="<?php echo $security; ?>" onkeypress="return isNumberKey(event)" name="listing_security_deposit_native" class="autosubmit-text input-stem input-large">
                                                            </div>
                                                            <p data-error="price" class="ml-error" id="small_security_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too low. The minimum is 5.'); ?></p>
                                                            <p data-error="price" class="ml-error" id="large_security_length" style="display: none;color: #F72C37;margin-top: 25px;"><?php echo translate('Your price is too long. The maximum is 300.'); ?></p>    
                                                        </div>
                                                    </div>
                                                    <p data-error="security_deposit" class="ml-error hide"></p>
                                                    <p class="text-muted">
                                                        This deposit is held by DropInn and refunded to the guest unless you make claim within 48 hours of guest checkout.
                                                    </p>
                                                </div>
                                            </div>


                                        </div>


                                    </div>

                                </div>
                            </div>
    <?php } ?>

                    <!-- price -->	

                </div>
                <div id="price-right-hover" style="float:left;">
                    <div class="main_entire_right" style="display:none;" id="price_right">
                        <div style="margin-top:15px;" class="main_entire_right_inner">
                            <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <b class="head-hover"><?php echo translate('Setting a price'); ?></b></p>
                            <div class="inner_entire">
                                <p><?php echo translate("For new listings with no reviews, it's important to set a competitive price. Once you get your first booking and review, you can raise your price!"); ?></p>
                                <p><?php echo translate("The suggested nightly price tip is based on:"); ?></p>
                                <p style="margin-bottom:0px;"><?php echo translate("1.Seasonal travel demand in your area."); ?></p>
                                <p style="margin-bottom:0px;"><?php echo translate("2.The median nightly price of recent Airbnb bookings in your city."); ?></p>
                                <p style="margin-bottom:0px;"><?php echo translate("3.The details of your listing."); ?></p>
                            </div>
                        </div>
                    </div>

                    <div id="summary-price-right" style="display:none;" class="main_entire_right">
                        <div style="margin-top:81%;" class="main_entire_right_inner">
                            <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <b class="head-hover"><?php echo translate("Offer a discount"); ?></b></p>
                            <div class="inner_entire">
                                <p><?php echo translate("Most hosts offer a discount for longer stays of a week or more."); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div style="display:none;" id="cleaning-price-right" class="main_entire_right">
                    <div class="main_entire_right_inner" style="margin-top:150%;">
                        <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"> <?php echo translate("A great summary"); ?></b></p>
                        <div class="inner_entire">
                            <p><?php echo translate("The cleaning fee is added to the total cost of every reservation at your listing."); ?></p>
                        </div>
                    </div>
                </div>
                <div style="display:none;" id="additional-price-right" class="main_entire_right">
                    <div class="main_entire_right_inner" style="margin-top:162%;">
                        <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"> <?php echo translate("A great summary"); ?></b></p>
                        <div class="inner_entire">
                            <p><?php echo translate("The additional guest charge is added to the nightly price of your listing (for each guest over the number you specify)."); ?></p>
                        </div>
                    </div>
                </div>
                <div class="center_entire" id="overview_entire" style="display: none">
                    <div style="margin-top:26px;" class="title-overview center_entire_left" style="">
                        <h2><?php echo translate("Overview"); ?></h2>
                        <p class="text_overview"><?php echo translate("A title and summary displayed on your public listing page."); ?></p>
                    </div>
                    <div class="summary-overview center_entire_left" style="">
                        <div>
                            <div style="height:10px;">
                                <div style="display:none;" class="js-saving-progress saving-progress" id="overview_saving">
                                    <h5><?php echo translate("saving..."); ?></h5>
                                </div>
                            </div>
                            <h3 class="overview_head"><?php echo translate("Title"); ?></h3>
                            <input type="text" class="text_characters" oncontextmenu="return false" placeholder="<?php echo translate('Write a title'); ?>" id="title" maxlength="35" value="<?php if ($lys_status->title == 1) echo $room_type; ?>"/><br>
                            <span id="chars_count" style="color:#959595;float:right;text-align:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;"><?php
                                if ($lys_status->title == 1)
                                {
                                    $count = strlen($room_type);
                                    echo 35 - $count . ' ' . translate("CHARACTERS LEFT");
                                }
                                else
                                    echo '35 ' . ' ' . translate("CHARACTERS LEFT");
?></span>
                        </div>
                        <div class="overview">
                            <div style="height:10px;">
                                <div style="display:none;" class="js-saving-progress saving-progress">
                                    <h5><?php echo translate("saving..."); ?></h5>
                                </div></div>
                            <h3 class="overview_head"><?php echo translate("Summary"); ?></h3>
                            <textarea type="text" id="summary" oncontextmenu="return false" class="text_words" placeholder="<?php echo translate('Write a summary in 50 words or less'); ?>"><?php if ($lys_status->summary == 1) echo $desc; ?></textarea>
                            <span style="color:#959595;float:right;font-weight:bold;font-size:12px;text-rendering:optimizelegibility;" id="display_count"><?php
                                if ($lys_status->summary == 1)
                                {
                                    $count = count(explode(" ", $desc));
                                    echo 50 - $count . ' ' . translate("WORDS LEFT");
                                }
                                else
                                    echo '50 ' . ' ' . translate("WORDS LEFT");
?></span>
                        </div>
                    </div>
                </div>
                <div style="float:left;" id="overview-textbox-hover">
                    <div style="display:none;" id="overview-text-right" style="" class="main_entire_right">
                        <div class="main_entire_right_inner" style="margin-top:15px;">
                            <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"><?php echo translate("A great title"); ?></b></p>
                            <div class="inner_entire">
                                <p> <?php echo translate("A great title is unique and descriptive!  It should highlight the main attractions of your space."); ?></p>
                                <p><b class="head-hover"><?php echo translate("Example:"); ?></b></p>
                                <p style="margin-bottom:0px;"><?php echo translate("Charming Victorian in the Mission."); ?></p>
                                <p style="margin-bottom:0px;"><?php echo translate("Cozy 2BD with Parking Included."); ?></p>
                                <p style="margin-bottom:0px;"><?php echo translate("Amazing View from a Modern Loft"); ?></p>
                            </div>
                        </div>
                    </div>
                    <div style="display:none;" id="summary-text-hover" class="main_entire_right">
                        <div class="main_entire_right_inner" style="margin-top:15px;">
                            <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"> <?php echo translate("A great summary"); ?></b></p>
                            <div class="inner_entire">
                                <p><?php echo translate("A great summary is rich and exciting! It should cover the major features of your space and neighborhood in 250 characters or less."); ?></p>
                                <p><b class="head-hover"><?php echo translate("Example:"); ?></b><br><?php echo translate("Our cool and comfortable one bedroom apartment with exposed brick has a true city feeling! It comfortably fits two and is centrally located on a quiet street, just two blocks from Washington Park. Enjoy a gourmet kitchen, roof access, and easy access to all major subway lines!"); ?></p>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- detail -->


                <div class="center_entire" id="detail_container" style="display: none">
                    <div style="height:10px;">
                        <div style="display:none;" class="js-saving-progress saving-progress" id="detail_saving">
                            <h5><?php echo translate("saving..."); ?></h5>
                        </div>
                    </div>
                    <div class="listing_container">
                        <div class="control-left" style="width:155px;">
                            <h2>Extra details</h2>
                            <p class="common_text">Other information you wish to share on your public listing page.</p>
                        </div>
                        <div class="control-right" style="width:175px;">

                            <div class="list-type_bed">
                                <p class="control-list">House Rules</p>
                                <textarea id="house_rules_textbox" placeholder="How do you expect your guests to behave?" rows="4" name="house_rules" class="house_rules">
<?php
    echo $house_rule;
?>
                                </textarea>
                            </div>


                        </div>

                    </div>
                    <div style="float:left;" id="details-textbox-hover">
                        <div style="display:none;" id="details-text-right" style="" class="main_entire_right">
                            <div class="main_entire_right_inner" style="margin-top:15px;">
                                <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover"><?php echo translate("A great title"); ?></b></p>
                                <div class="inner_entire">
                                    <p> <?php echo translate("A great title is unique and descriptive!  It should highlight the main attractions of your space."); ?></p>
                                    <p><b class="head-hover"><?php echo translate("Example:"); ?></b></p>
                                    <p style="margin-bottom:0px;"><?php echo translate("Charming Victorian in the Mission."); ?></p>
                                    <p style="margin-bottom:0px;"><?php echo translate("Cozy 2BD with Parking Included."); ?></p>
                                    <p style="margin-bottom:0px;"><?php echo translate("Amazing View from a Modern Loft"); ?></p>
                                </div>
                            </div>
                        </div>
                        <div id="ded" style="display:none" id="details_text_hover" class="main_entire_right">
                            <div class="main_entire_right_inner" style="margin-top:15px;">
                                <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /><b class="head-hover">House Rules</b></p>
                                <div class="inner_entire">
                                    <ul class="head-hover">
                                        <li>How do you expect your guests to behave?</li>
                                        <li>Do you allow pets?</li>
                                        <li>Do you have rules against smoking?</li>
                                    </ul>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- detail -->

                <!-- Terms -->


                <div class="center_entire" id="terms_container" style="display: none">
                    <div style="height:10px;">
                        <div style="display:none;" class="js-saving-progress saving-progress" id="policy_saving">
                            <h5><?php echo translate('saving...'); ?></h5>
                        </div>
                    </div>
                    <div class="listing_container">
                        <div class="control-left" style="width:155px; margin-left: -28px">
                            <h2>Terms</h2>
                            <p class="common_text">The requirements and conditions to book a reservation at your listing. </p>
                        </div>
                        <div class="control-right" style="width:440px;">

                            <!--<div id="min-max-nights" class="row row-space-2">
                                    <div class="terms_segment_1">
                              <label class="label-large">Minimum Stay</label>
                              <div class="input-addon">
                                <input type="text" class="input-stem input-large" value="10" id="min-nights" name="min_nights_input_value">
                                <span class="input-suffix">nights</span>
                              </div>
                            </div>
                            <div class="terms_segment_1">
                              <label class="label-large">Maximum Stay</label>
                              <div class="input-addon">
                                <input type="text" class="input-stem input-large" value="30" id="max-nights" name="max_nights_input_value">
                                <span class="input-suffix">nights</span>
                              </div>
                            </div>
                            <p style="display:none;" class="ml-error" id="min-max-error"></p>
                            </div>-->

                            <!--<div class="row row-space-2">
                              <div class="terms_segment_2">
                                <label class="label-large">Check in after</label>
                                <div id="check-in-time-select"><div class="select
                                        select-large
                                        select-block">
                              <select name="check_in_time">
                                
                                  <option value="">Flexible</option>
                                
                                  <option value="0" selected="selected">12:00 AM (midnight)</option>
                                
                                  <option value="1">1:00 AM</option>
                                
                                  <option value="2">2:00 AM</option>
                                
                                  <option value="3">3:00 AM</option>
                                
                                  <option value="4">4:00 AM</option>
                                
                                  <option value="5">5:00 AM</option>
                                
                                  <option value="6">6:00 AM</option>
                                
                                  <option value="7">7:00 AM</option>
                                
                                  <option value="8">8:00 AM</option>
                                
                                  <option value="9">9:00 AM</option>
                                
                                  <option value="10">10:00 AM</option>
                                
                                  <option value="11">11:00 AM</option>
                                
                                  <option value="12">12:00 PM (noon)</option>
                                
                                  <option value="13">1:00 PM</option>
                                
                                  <option value="14">2:00 PM</option>
                                
                                  <option value="15">3:00 PM</option>
                                
                                  <option value="16">4:00 PM</option>
                                
                                  <option value="17">5:00 PM</option>
                                
                                  <option value="18">6:00 PM</option>
                                
                                  <option value="19">7:00 PM</option>
                                
                                  <option value="20">8:00 PM</option>
                                
                                  <option value="21">9:00 PM</option>
                                
                                  <option value="22">10:00 PM</option>
                                
                                  <option value="23">11:00 PM</option>
                                
                              </select>
                            </div>
                            </div>
                              </div>
                              <div class="terms_segment_2">
                                <label class="label-large">Check out before</label>
                                <div id="check-out-time-select"><div class="select
                                        select-large
                                        select-block">
                              <select name="check_out_time">
                                
                                  <option value="">Flexible</option>
                                
                                  <option value="0" selected="selected">12:00 AM (midnight)</option>
                                
                                  <option value="1">1:00 AM</option>
                                
                                  <option value="2">2:00 AM</option>
                                
                                  <option value="3">3:00 AM</option>
                                
                                  <option value="4">4:00 AM</option>
                                
                                  <option value="5">5:00 AM</option>
                                
                                  <option value="6">6:00 AM</option>
                                
                                  <option value="7">7:00 AM</option>
                                
                                  <option value="8">8:00 AM</option>
                                
                                  <option value="9">9:00 AM</option>
                                
                                  <option value="10">10:00 AM</option>
                                
                                  <option value="11">11:00 AM</option>
                                
                                  <option value="12">12:00 PM (noon)</option>
                                
                                  <option value="13">1:00 PM</option>
                                
                                  <option value="14">2:00 PM</option>
                                
                                  <option value="15">3:00 PM</option>
                                
                                  <option value="16">4:00 PM</option>
                                
                                  <option value="17">5:00 PM</option>
                                
                                  <option value="18">6:00 PM</option>
                                
                                  <option value="19">7:00 PM</option>
                                
                                  <option value="20">8:00 PM</option>
                                
                                  <option value="21">9:00 PM</option>
                                
                                  <option value="22">10:00 PM</option>
                                
                                  <option value="23">11:00 PM</option>
                                
                              </select>
                            </div>
                            </div>
                              </div>
                            </div>-->

                            <div class="row-space-2" style="margin-left: 10px;">
                                <label class="label-large">Cancellation Policy</label>
                                <div id="cancellation-policy-select"><div class="select
                                                                          select-large
                                                                          select-block">
                                        <select name="cancel_policy" id="cancel_policy" class="cancel_policy">

                                            <option value="Flexible" <?php if ($cancellation_policy == 'Flexible') echo 'selected'; ?>>Flexible: Full refund 1 day prior to arrival, except fees</option>

                                            <option value="Moderate" <?php if ($cancellation_policy == 'Moderate') echo 'selected'; ?>>Moderate: Full refund 5 days prior to arrival, except fees</option>

                                            <option value="Strict" <?php if ($cancellation_policy == 'Strict') echo 'selected'; ?>>Strict: 50% refund up until 1 week prior to arrival, except fees</option>

                                            <option value="Super Strict" <?php if ($cancellation_policy == 'Super Strict') echo 'selected'; ?>>Super Strict: 50% refund up until 30 days prior to arrival, except fees</option>

                                            <option value="Long Term" <?php if ($cancellation_policy == 'Long Term') echo 'selected'; ?>>Long Term: First month down payment, 30 day notice for lease termination</option>

                                        </select>
                                    </div>
                                </div>
                                <a target="_blank" id="js-learn-more" href="<?php echo base_url(); ?>pages/cancellation_policy">
                                    Learn More »
                                </a>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- Terms -->

                <div class="center_entire" id="amenities_entire" style="display: none">

                    <div>
                        <div class="non_container">
                            <div style="height:10px;">
                                <div style="display:none;" class="js-saving-progress saving-progress" id="amenities_saving">
                                    <h5><?php echo translate("saving..."); ?></h5>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="control-label">
                                    <h3><?php echo translate("Most Common"); ?></h3>
                                    <p class="common_text"><?php echo translate("Common amenities at most DropInn listing"); ?></p>    
                                </div>
                                <div class="amenities-control">
                                    <?php
                                        if ($result_amenites != '')
                                        {
                                            $amenities_expload = explode(',', $result_amenites);
                                        }
                                        foreach ($amenities->result() as $row)
                                        {
                                            if ($result_amenites != '')
                                            {
                                                if (in_array($row->id, $amenities_expload))
                                                {
                                                    $checked = 'checked';
                                                }
                                                else
                                                {
                                                    $checked = '';
                                                }
                                            }
                                            else
                                            {
                                                $checked = '';
                                            }
                                            ?>
                                            <div class="controls">
                                                <input type="checkbox" id="<?php echo $row->id; ?>" name="amenities_<?php echo $row->id; ?>" value="<?php echo $row->id; ?>" <?php echo $checked; ?>/> <?php echo $row->name; ?>
                                            </div>
                                                <?php
                                            }
                                        ?>
                                </div>
                            </div>        
                        </div>
                    </div>
                </div>

                <div class="center_entire" id="listing_entire" style="display: none">
                    <div class="listing">
                        <div style="height:10px;">
                            <div style="display:none;" class="js-saving-progress saving-progress" id="listing_saving">
                                <h5><?php echo translate("saving..."); ?></h5>
                            </div>
                        </div>
                        <div class="listing_container">
                            <div class="control-left">
                                <h2><?php echo translate("Listing Info"); ?></h2>
                                <p class="common_text"><?php echo translate("Basic information about your listing."); ?></p>
                            </div>
                            <div class="control-right">
                                <div class="list-type">
                                    <p class="control-list"><?php echo translate("Home Type"); ?></p>
                                    <select class="control_select-box" id="home_type_drop">
<?php
    $property = $this->db->get('property_type');
    foreach ($property->result() as $value)
    {
        ?> <option <?php if ($home_type == $value->type) echo 'selected'; ?>><?php echo $value->type; ?></option>
    <?php } ?>  
                                    </select>
                                </div>

                                <div class="list-type">
                                    <p class="control-list"><?php echo translate("Room Type"); ?></p>
                                    <select class="control_select-box" id="room_type_drop">
                                        <option <?php if ($room_type_only == 'Entire Home/Apt') echo 'selected'; ?>><?php echo translate("Entire Home/Apt"); ?></option>
                                        <option <?php if ($room_type_only == 'Private Room') echo 'selected'; ?>><?php echo translate("Private Room"); ?></option>
                                        <option <?php if ($room_type_only == 'Shared Room') echo 'selected'; ?>><?php echo translate("Shared Room"); ?></option>
                                    </select>
                                </div>

                                <div class="list-type">
                                    <p class="control-list"><?php echo translate("Accommodates"); ?></p>
                                    <select class="control_select-box" id="accommodates_drop">
                                        <option <?php if ($accommodates == '1') echo 'selected'; ?>>1</option>
                                        <option <?php if ($accommodates == '2') echo 'selected'; ?>>2</option>
                                        <option <?php if ($accommodates == '3') echo 'selected'; ?>>3</option>
                                        <option <?php if ($accommodates == '4') echo 'selected'; ?>>4</option>
                                        <option <?php if ($accommodates == '5') echo 'selected'; ?>>5</option>
                                        <option <?php if ($accommodates == '6') echo 'selected'; ?>>6</option>
                                        <option <?php if ($accommodates == '7') echo 'selected'; ?>>7</option>
                                        <option <?php if ($accommodates == '8') echo 'selected'; ?>>8</option>
                                        <option <?php if ($accommodates == '9') echo 'selected'; ?>>9</option>
                                        <option <?php if ($accommodates == '10') echo 'selected'; ?>>10</option>
                                        <option <?php if ($accommodates == '11') echo 'selected'; ?>>11</option>
                                        <option <?php if ($accommodates == '12') echo 'selected'; ?>>12</option>
                                        <option <?php if ($accommodates == '13') echo 'selected'; ?>>13</option>
                                        <option <?php if ($accommodates == '14') echo 'selected'; ?>>14</option>
                                        <option <?php if ($accommodates == '15') echo 'selected'; ?>>15</option>
                                        <option <?php if ($accommodates == '16') echo 'selected'; ?>>16+</option>
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>
                    <p class="hr_center"></p>

                    <div class="listing">
                        <div style="height:10px;">
                            <div style="display:none;" class="js-saving-progress saving-progress">
                                <h5><?php echo translate("saving..."); ?></h5>
                            </div></div>
                        <div class="listing_container">
                            <div class="control-left" style="width:175px;">
                                <h2><?php echo translate("Rooms and Beds"); ?></h2>
                                <p class="common_text"><?php echo translate("The number of rooms and beds guests can access."); ?></p>
                            </div>
                            <div class="control-right" style="width:175px;">

                                <div class="list-type_bed">
                                    <p class="control-list"><?php echo translate("Bedrooms"); ?></p>
                                    <select class="control_select-box_bed" id="bedrooms">
                                        <option selected disabled value=""><?php echo translate("Select..."); ?>
                                        <option <?php if ($bedrooms == '1') echo 'selected'; ?>>1</option>
                                        <option <?php if ($bedrooms == '2') echo 'selected'; ?>>2</option>
                                        <option <?php if ($bedrooms == '3') echo 'selected'; ?>>3</option>
                                        <option <?php if ($bedrooms == '4') echo 'selected'; ?>>4</option>
                                        <option <?php if ($bedrooms == '5') echo 'selected'; ?>>5</option>
                                        <option <?php if ($bedrooms == '6') echo 'selected'; ?>>6</option>
                                        <option <?php if ($bedrooms == '7') echo 'selected'; ?>>7</option>
                                        <option <?php if ($bedrooms == '8') echo 'selected'; ?>>8</option>
                                        <option <?php if ($bedrooms == '9') echo 'selected'; ?>>9</option>
                                        <option <?php if ($bedrooms == '10') echo 'selected'; ?>>10</option>
                                        <option <?php if ($bedrooms == '11') echo 'selected'; ?>>11</option>
                                        <option <?php if ($bedrooms == '12') echo 'selected'; ?>>12</option>
                                        <option <?php if ($bedrooms == '13') echo 'selected'; ?>>13</option>
                                        <option <?php if ($bedrooms == '14') echo 'selected'; ?>>14</option>
                                        <option <?php if ($bedrooms == '15') echo 'selected'; ?>>15</option>
                                        <option <?php if ($bedrooms == '16') echo 'selected'; ?>>16+</option>
                                    </select>
                                </div>
                                <div class="list-type_bed">
                                    <p class="control-list"><?php echo translate("Beds"); ?></p>
                                    <select class="control_select-box_bed" id="beds"><?php echo translate("Select..."); ?>
                                        <option selected disabled value=""><?php echo translate("Select..."); ?>
                                        <option <?php if ($beds == '1') echo 'selected'; ?>>1</option>
                                        <option <?php if ($beds == '2') echo 'selected'; ?>>2</option>
                                        <option <?php if ($beds == '3') echo 'selected'; ?>>3</option>
                                        <option <?php if ($beds == '4') echo 'selected'; ?>>4</option>
                                        <option <?php if ($beds == '5') echo 'selected'; ?>>5</option>
                                        <option <?php if ($beds == '6') echo 'selected'; ?>>6</option>
                                        <option <?php if ($beds == '7') echo 'selected'; ?>>7</option>
                                        <option <?php if ($beds == '8') echo 'selected'; ?>>8</option>
                                        <option <?php if ($beds == '9') echo 'selected'; ?>>9</option>
                                        <option <?php if ($beds == '10') echo 'selected'; ?>>10</option>
                                        <option <?php if ($beds == '11') echo 'selected'; ?>>11</option>
                                        <option <?php if ($beds == '12') echo 'selected'; ?>>12</option>
                                        <option <?php if ($beds == '13') echo 'selected'; ?>>13</option>
                                        <option <?php if ($beds == '14') echo 'selected'; ?>>14</option>
                                        <option <?php if ($beds == '15') echo 'selected'; ?>>15</option>
                                        <option <?php if ($beds == '16') echo 'selected'; ?>>16+</option>
                                    </select>
                                </div>

                                <div class="list-type_bed">
                                    <p class="control-list"><?php echo translate("Bed type"); ?></p>
                                    <select class="control_select-box_bed" id="hosting_bed_type" name="hosting_bed_type">                    	
                                        <option selected disabled value=""><?php echo translate("Select..."); ?>
                                        <option value="Airbed" <?php if ($bed_type == 'Airbed') echo 'selected'; ?> ><?php echo translate_admin("Airbed"); ?></option>
                                        <option value="Futon" <?php if ($bed_type == 'Futon') echo 'selected'; ?>><?php echo translate_admin("Futon"); ?></option>
                                        <option value="Pull-out Sofa" <?php if ($bed_type == 'Pull-out Sofa') echo 'selected'; ?>><?php echo translate_admin("Pull-out Sofa"); ?></option>
                                        <option value="Couch" <?php if ($bed_type == 'Couch') echo 'selected'; ?>><?php echo translate_admin("Couch"); ?></option>
                                        <option value="Real Bed" <?php if ($bed_type == 'Real Bed') echo 'selected'; ?>><?php echo translate_admin("Real Bed"); ?></option>
                                    </select>
                                </div>

                                <div class="list-type_bed">
                                    <p class="control-list"><?php echo translate("Bathrooms"); ?></p>
                                    <select class="control_select-box_bed" id="bathrooms">
                                        <option selected disabled><?php echo translate("Select..."); ?></option>
                                        <option <?php if ($bathrooms == '0') echo 'selected'; ?>>0</option>
                                        <option <?php if ($bathrooms == '0.5') echo 'selected'; ?>>0.5</option>
                                        <option <?php if ($bathrooms == '1') echo 'selected'; ?>>1</option>
                                        <option <?php if ($bathrooms == '1.5') echo 'selected'; ?>>1.5</option>
                                        <option <?php if ($bathrooms == '2') echo 'selected'; ?>>2</option>
                                        <option <?php if ($bathrooms == '2.5') echo 'selected'; ?>>2.5</option>
                                        <option <?php if ($bathrooms == '3') echo 'selected'; ?>>3</option>
                                        <option <?php if ($bathrooms == '3.5') echo 'selected'; ?>>3.5</option>
                                        <option <?php if ($bathrooms == '4') echo 'selected'; ?>>4</option>
                                        <option <?php if ($bathrooms == '4.5') echo 'selected'; ?>>4.5</option>
                                        <option <?php if ($bathrooms == '5') echo 'selected'; ?>>5</option>
                                        <option <?php if ($bathrooms == '5.5') echo 'selected'; ?>>5.5</option>
                                        <option <?php if ($bathrooms == '6') echo 'selected'; ?>>6</option>
                                        <option <?php if ($bathrooms == '6.5') echo 'selected'; ?>>6.5</option>
                                        <option <?php if ($bathrooms == '7') echo 'selected'; ?>>7</option>
                                        <option <?php if ($bathrooms == '7.5') echo 'selected'; ?>>7.5</option>
                                        <option <?php if ($bathrooms == '8+') echo 'selected'; ?>>8+</option>
                                    </select>
                                </div>

                            </div>

                        </div>
                    </div>

<!--<p>If you wish, you can permanently <span class="link_color">delete this listing.</span></p>-->

                </div>

                <div class="center_entire" id="photos_container" style="display: none">
                    <div class="container_photo" id="container_photo">
                        <div class="price_upload">
                            <img src="<?php echo base_url(); ?>images/inbox.png" />
                            <h3><?php echo translate("Add a photo or two!"); ?></h3>
                            <p><?php echo translate("Or three, or more! Guests love photos that highlight the features of your space."); ?></p>
                           <!-- <img src="<?php echo base_url(); ?>images/add_photo.png" />-->
                            <button class="pin-on-map"><?php echo translate("Add Photos"); ?></button>
                            <p id="no_file" style="display: none"><?php echo translate("No File Choosen"); ?></p>
                            <input type="file" style="z-index: 9999; position:absolute; width: 90px; padding: 5px 20px; cursor: default; opacity: 0; margin: -4px -119px 0;" id="upload_file" name="upload_file">

                            <br>
                            <button id="upload_file_btn" class="upload_btn"><?php echo translate("Upload"); ?></button>
                            <button id="upload_file_btn_dis" class="upload_btn" style="display: none;" disabled><?php echo translate("Upload"); ?></button>
                        </div>
                    </div>
                    <div class="container_add_photo" style="display: none">
                        <div class="panel_photos">

                            <div class="add_photo">
                              <!-- <img src="<?php echo base_url(); ?>images/add_photo.png" />-->
                                <button class="pin-on-map"><?php echo translate("Add Photos"); ?></button><p id="no_file1" style="display: none"><?php echo translate("No File Choosen"); ?></p>
                                <input type="file" style="z-index: 9999; position:absolute; width: 90px; padding: 5px 20px; cursor: default; opacity: 0; margin: -4px -119px 0;" id="upload_file1" name="upload_file1">

                                <br>
                                <button id="upload_file_btn1" class="upload_btn"><?php echo translate("Upload"); ?></button>
                                <button id="upload_file_btn1_dis" class="upload_btn" style="display: none;" disabled><?php echo translate("Upload"); ?></button>
                            </div>

                            <div class="one_photos">
                                <div id="content" class="fullwidth" style="display: none">
                                    <div class="expand"></div>
                                </div>
                                <p id="photos_count"><?php echo $list_photo->num_rows(); ?> <?php echo translate("Photos"); ?></p>
                            </div>	

                        </div>
                    </div>
                    <div class="rel_photo_appear">
                        <div class="photo_appear">
<?php echo translate("Your first photo appears in search results!"); ?>
                        </div>
                    </div>
                    <div class="ul_overflow">
                        <ul class="photo_img" id="photo_ul" style="display: none">
<?php
    if ($list_photo->num_rows() != 0)
    {
        foreach ($list_photo->result() as $row)
        {
            ?>

                                        <li class="photo_img_sub" id="<?php echo 'list_photo_' . $row->id; ?>">
                                            <div  id="pannel_photo_item_id" class="pannel_photo_item">
                                                <div class="first-photo-ribbon"></div>
                                                <div class="photo-drag-target"></div>
                                                <a class="media-link"><img width="100%" src="<?php echo base_url() . 'images/' . $room_id . '/' . $row->name; ?>"></a>
                                                <button data-photo-id="29701026" class="delete-photo-btn js-delete-photo-btn" onClick="$(this).delete_photo('<?php echo $row->id; ?>')">
                                                    <i ><img src="<?php echo base_url(); ?>css/templates/blue/images/delete-32.png"></i>
                                                </button>
                                                <div class="panel-body panel-condensed">
                                                    <textarea name="" id="highlight_<?php echo $row->id; ?>" rows="3" placeholder="<?php echo translate('What are the highlights of this photo?'); ?>" class="input-large" onKeyUp="$(this).highlight('<?php echo $row->id; ?>')"><?php echo trim($row->highlights); ?></textarea>
                                                </div>
                                            </div>
                                        </li>
                                <?php
                            }
                        }
                    ?>

                    </div>

                    </ul>
                </div>

            </div>
            <div class="center_entire" id="address_entire" style="display: none">
                <div class="title-address center_entire_left" style="margin-top:30px;height:480px;">
                    <h2><?php echo translate("Address"); ?></h2>
                    <p class="text_address"><?php echo translate("Your exact address is private and only shared with guests after a reservation is confirmed."); ?></p>
                </div>
                <div class="lys_address">
<?php
    if ($street_address != '')
    {
        ?>
                            <img id="static_map" src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $lat . ',' . $lng; ?>&size=370x277&zoom=15&format=png&markers=color:red|label:|<?php echo $lat . ',' . $lng; ?>&sensor=false&maptype=roadmap&style=feature:water|element:geometry.fill|weight:3.3|hue:0x00aaff|lightness:100|saturation:93|gamma:0.01|color:0x5cb8e4">
        <?php
    }
    else
    {
        ?>
                            <img id="static_map" src="<?php echo base_url(); ?>images/map_lys.png" />
        <?php
    }
?>
                    <p class="add_content" id="add_content"><?php echo translate("This Listing has no address."); ?></p>
                    <!--<img id="add_address" src="<?php echo base_url(); ?>images/add_address.png" />-->
                    <button id="add_address" class="pin-on-map"><?php echo translate("Add address"); ?></button>
                    <div id="after_address" style="display: none;padding-top: 20px;">
                        <strong id="str_street_address1"><?php echo $street_address; ?></strong><br>
                        <strong id="str_city_state_address1"><?php echo $city . ' ' . $state; ?></strong><br>
                        <strong id="str_zipcode1"><?php echo $zipcode; ?></strong><br>
                        <strong id="str_country1"><?php echo $country_name; ?></strong><br>
                        <a id="edit_address1"><?php echo translate("edit address"); ?></a>
                    </div>
                </div>
            </div>

            <div class="main_entire_right" style="display: none;padding-top: 40px;" id="address_right">
                <div class="main_entire_right_inner">
                    <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <?php echo translate("Your Address is Private"); ?></p>
                    <div class="inner_entire">
                        <p class="text_inner"><?php echo translate("It will only be shared with guests after a reservation is confirmed."); ?></p>
                    </div>
                </div>
            </div>
            <div id="static_circle_map" class="main_entire_right" style="display: none;padding-top: 40px;" style="display: none">
                <div class="main_entire_right_inner">
                    <p class="thimbthumb"><img src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <?php echo translate("Your Address is Private"); ?></p>
                    <div class="inner_entire">
                        <p class="text_inner"><?php echo translate("It will only be shared with guests after a reservation is confirmed."); ?></p>
                    </div>
                </div>
                <img id="static_circle_map_img" width="210" height="210" src="<?php echo $MapURL; ?>" />
                <p class="text_inner text_inner1">
                                                <?php echo translate("This is how your location appears on your listing page."); ?> 
                    <a target="_blank" href="<?php echo base_url() . 'rooms/' . $room_id; ?>"> <?php echo translate("View on listing page"); ?> »</a>
                </p>
            </div>
            <style>
                .pac-container{
                    position: relative;
                    z-index: 9999;
                }
            </style>
            <div class="modal-list modal-with-background-image1" id="address_popup1" style="display: none">
                <div id="my_form">
                    <div class="modal-content">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="media-body">
                                    <a  class="pull-list-right" id="address_popup1_close">
                                        <img class="img-align" src="<?php echo base_url(); ?>images/list-close.png">
                                    </a>
                                    <h3 class="nav-heading">
                                        <p><span style="font-size:18px;"><?php echo translate("Enter Address"); ?></span></p>
                                        <p><small class="addrs_small"><?php echo translate("What is your listing's address?"); ?></small></p>
                                    </h3>
                                </div>
                            </div>
                            <div class="panel-body">
                                <!--<form id="js-address-form">-->
                                <div class="row-space-2">
                                    <label for="country" class="label-large"><?php echo translate("Country"); ?></label>
                                    <div id="country-select"><div class="select select-block select-large">
                                            <select id="country" name="country_code">
<?php
    foreach ($country->result() as $country_list)
    {
        if (country_name == $country_list)
        {
            $selected = 'selected';
        }
        else
        {
            $selected = '';
        }
        echo '<option value="' . $country_list->country_name . '"' . $selected . '>' . $country_list->country_name . '</option>';
    }
?>

                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div id="localized-fields">
                                    <div class="row-space-2">
                                        <label for="street" class="label-large"><?php echo translate("Street Address"); ?></label>
                                        <input type="text" placeholder="e.g. 123 Main St." class="input-large" value="<?php echo $street_address; ?>" id="lys_street_address">
                                    </div>
                                    <input type="hidden" name="hidden_address" id="hidden_address"/>
                                    <input type="hidden" name="hidden_lat" id="hidden_lat"/>
                                    <input type="hidden" name="hidden_lng" id="hidden_lng"/>
                                    <div class="row-space-2">
                                        <label for="apt" class="label-large"><?php echo translate("Apt, Suite, Bldg. (optional)"); ?></label>
                                        <input type="text" placeholder="e.g. Apt #7" class="input-large" value="<?php echo $optional_address; ?>" id="apt" name="apt">
                                    </div>

                                    <div class="row-space-2">
                                        <label for="city" class="label-large"><?php echo translate("City"); ?></label>
                                        <input type="text" placeholder="e.g. San Francisco" class="input-large" value="<?php echo $city; ?>" id="city" name="city">
                                    </div>

                                    <div class="row-space-2">
                                        <label for="state" class="label-large"><?php echo translate("State"); ?></label>
                                        <input type="text" placeholder="e.g. CA" class="input-large" value="<?php echo $state; ?>" id="state" name="state">
                                    </div>

                                    <div class="row-space-2">
                                        <label for="zipcode" class="label-large"><?php echo translate("ZIP Code"); ?></label>
                                        <input type="text" placeholder="e.g. 94103" class="input-large" value="<?php echo $zipcode; ?>" id="zipcode" name="zipcode">
                                    </div>

                                </div>
                                <!--</form>-->
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button class="cancel-btn" id="address_popup1_cancel">
<?php echo translate("Cancel"); ?>
                            </button>
                            <button id="next-btn" onclick="disable()" class="disable-btn btn-primarybtn next_active">
<?php echo translate("Next"); ?>
                            </button>
                            <button id="next-btn" class="enable-btn btn-primarybtn next_active" style="display: none">
<?php echo translate("Next"); ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main_entire_right" style="display:none;" id="calendar_right">
                <div class="main_entire_right_inner">
                    <p class="thimbthumb" style="margin-top:28%;"><img style="position:relative;" src="<?php echo base_url(); ?>images/thimbthumb_new.png" /> <span class="nonmal"><?php echo translate("Choose the option that best fits your listing's availability. Don't worry, you can change this any time."); ?></span></p>
                </div>
            </div>
            <div class="modal-list modal-with-background-image1" id="address_popup2" style="display: none">
                <div id="my_form" style="margin-top:200px;">
                    <div class="modal-content">
                        <div class="panel">
                            <div class="panel-header">
                                <div class="media-body">
                                    <a  class="pull-list-right" id="address_popup2_close">
                                        <img class="img-align" src="<?php echo base_url(); ?>images/list-close.png">
                                    </a>
                                    <h3 class="nav-heading">
                                        <span style="font-size:18px;"><?php echo translate("Location Not Found"); ?></span><br>
                                        <small class="addrs_small"><?php echo translate("Manually pin this listing's location on a map."); ?></small>
                                    </h3>
                                </div>
                            </div>
                            <div class="panel-align panel-body">
                                <p class="panel-para"><?php echo translate("We couldn't automatically find this listing's location, but if the address below is correct you can manually pin it's location on the map instead."); ?></p>

                                <strong id="str_street_address"></strong><br>
                                <strong id="str_city_state_address"></strong><br>
                                <strong id="str_zipcode"></strong><br>
                                <strong id="str_country"></strong><br>
                            </div>
                            <div class="panel-footer">
                                <button class="cancel-btn" id="edit_address">
<?php echo translate("Edit Address"); ?>
                                </button>
                                <button  class="pin-on-map" id="pin-on-map">
<?php echo translate("Pin on Map"); ?>
                                </button>
                            </div>


                        </div>


                    </div>
                </div>
            </div>

            <div class="modal-list modal-with-background-image1" id="address_popup3" style="display: none">
                <div id="my_form">
                    <div class="modal-content">
                        <div class="panel-borderhead panel">
                            <div class="panel-header">
                                <div class="media-body">
                                    <a  class="pull-list-right">
                                        <img class="img-align" id="close_popup3" src="<?php echo base_url(); ?>images/list-close.png">
                                    </a>
                                    <h3 class="nav-heading">
                                        <span style="font-size:18px;"><?php echo translate("Pin Location"); ?></span><br>
                                        <small class="addrs_small"><?php echo translate("Move the map to pin your listing's exact location."); ?></small>
                                    </h3>
                                </div>
                            </div>
                            <div class="panel-body">


                                <div class="panel">
                                    <div id="map-canvas1"></div>
                                </div>
                                <div style="border-top:none;" class="panel-border panel-align panel-body">
                                    <strong id="str_street_address2"></strong><br>
                                    <strong id="str_city_state_address2"></strong><br>
                                    <strong id="str_zipcode2"></strong><br>
                                    <strong id="str_country2"></strong><br>
                                    <a data-event-name="edit_address_click" class="edit-address-link" id="edit_popup3"><?php echo translate("edit address"); ?></a>
                                </div>

                            </div>
                            <div style="border:none;" class="panel-footer">
                                <!-- <button class="cancel-btn" id="cancel_popup3">
                                   Cancel
                                </button>-->
                                <button  class="pin-on-map enable_finish" id="finish_popup3" style="display: none">
<?php echo translate("Finish"); ?>
                                </button>
                                <button id="finish_popup3" class="pin-on-map disable_finish"  onClick="alert('Pin your listing exact location on map to continue.');" style="opacity: 0.65;">
                                <?php echo translate("Finish"); ?>
                                </button>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
            <div class="entire_footer" style="clear:both;">
                <div class="delete_box">
                    <p class="entire_title">&copy; <?php echo $this->dx_auth->get_site_title(); ?>, Inc.</p>
                </div>
                <div class="footer_container">
                    <ul>
                        <li><a href="<?php echo site_url('pages/view/about'); ?>"><?php echo translate("About"); ?>&nbsp;&nbsp;|</a></li>
                        <li><a href="<?php echo base_url() . 'home/help'; ?>"><?php echo translate("Help"); ?>&nbsp;&nbsp;|</a></li>
                        <li><a href="<?php echo site_url('pages/view/press'); ?>"><?php echo translate("Press"); ?>&nbsp;&nbsp;|</a></li>
                        <li><a href="<?php echo site_url('pages/view/responsible_hosting'); ?>"><?php echo translate("Responsible Hosting"); ?>&nbsp;&nbsp;|</a></li>
                        <li><a href="<?php echo site_url('pages/view/policies'); ?>"><?php echo translate("Policies"); ?>&nbsp;&nbsp;|</a></li>
                        <li><a href="<?php echo site_url('pages/view/terms'); ?>"><?php echo translate("Terms & Privacy"); ?></a></li>
                    </ul>
                </div>
                <div id="language" class="footer_right">
                    <div id="language_display" class="rounded_top">
                        <div class="football_img"> <img class="img_lang" src="<?php echo css_url(); ?>/images/football.png" /> </div>
                        <div id="language_display_currency" class="language_set"> &nbsp; <?php if ($this->session->userdata('language') == "") echo "English";
                    else echo $this->session->userdata('language'); ?></div></div>
                    <div class="arrow_sym">  </div>
                    <div id="language_selector_container" class="single_Lang" style="display:none;">
                        <div id="language_selector">
                            <ul id="locale2">
                                            <?php
                                                $languages_core = $this->Common_model->getTableData('language')->result();
                                                foreach ($languages_core as $language)
                                                {
                                                    ?>
                                        <li class="language option" id="language_selector_<?php echo $language->code; ?>" name="<?php echo $language->code; ?>"><?php echo $language->name; ?></li> <?php } ?>						
                            </ul>
                        </div>
                    </div>																								
                </div>
            </div>

                                        <?php
                                            if ($this->uri->segment(3) == 'edit_photo' || $this->uri->segment(3) == 'edit' || $total_status == 6 || $this->session->userdata('popup_status') == 1)
                                            {
                                                ?>
                    <div class="modal-list modal-with-background-image" id="my_contain" style="display: none">
        <?php
    }
    else
    {
        ?>
                        <div class="modal-list modal-with-background-image" id="my_contain">
        <?php
    }
?>
                    <div class="modal-table-list">
                        <div class="modal-cell-list">
                            <div class="modal-content-list"style="max-width:570px;">
                                <div  class="modal-body_img-list modal-body-list modal-body-picture-list">
                                </div>
                                <div class="modal-body-list modal-body-content-list text-center-list row-table-list">
                                    <div class="col-middle-list">
                                        <h1 class="row-space-7-list text-lite-list">

                                <?php echo translate("We've created your listing."); ?>

                                        </h1>
                                        <div class="p-relative steps-remaining-circle"><h1 class="steps-remaining-text">6</h1></div>
                                        <div class="new_row">
                                            <div class="p-relative col_listspace">
                                                <div class="h3_text"><?php echo translate("more steps to <br /> list your space"); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer-list text-center-list">
                                    <button  class="finish_my_list" id="finish_list">
                                        <?php echo translate("Finish my listing"); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>	

                <div class="modal-list modal-with-background-image" id="seelist_container" style="display: none">
                    <div class="modal-table-list">
                        <div class="modal-cell-list">
                            <div class="modal-content-list">
<?php
    $list_is_featured_photo_result = $this->db->where('list_id', $room_id)->where('is_featured', 1)->get('list_photo');
    if ($list_is_featured_photo_result->num_rows() != 0)
    {
        $list_is_featured_photo = $list_is_featured_photo_result->row()->name;
    }
    else
    {
        $list_is_featured_photo = '';
    }
?>
                                <div  class="modal-seelist-img modal-body-list modal-body-picture-list" id="final_photo" style="background: url(<?php echo base_url() . 'images/' . $room_id . '/' . $list_is_featured_photo; ?>); background-repeat:no-repeat; background-size:577px">
                                </div>
                                <div class="modal-body-list modal-body-content-list text-center-list row-table-list">
                                    <div class="col-middle-list">
                                        <i class="icon icon-ok-alt icon-size-3"><img src="<?php echo base_url(); ?>images/tick-tick.png"></i>
                                        <div style="line-height: 1.5;" class="h1 text-lite-list"><?php echo translate("Your space is listed!"); ?></div>
                                    </div>
                                </div>
                                <div class="panel-footer-list text-center-list">
                                    <button class="cancel-btn" id="close_list">
<?php echo translate("Close"); ?>
                                    </button>
                                    <button  class="see-btn" id="see_list">
<?php echo translate("See your listing"); ?><span class="arow_img"><img src="<?php echo base_url(); ?>images/arrow-fw.png"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                </body>
                </html>
