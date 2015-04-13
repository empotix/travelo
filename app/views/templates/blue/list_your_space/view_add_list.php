<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=10" />
        <title>List your space</title>
        <link href="<?php echo css_url() . '/common.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo css_url() . '/demo.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo css_url() . '/listyourspace.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.0.min.js"></script>-->
        <script>
            $(document).ready(function () {
                //$('.menu').dropit();
                var home_type_index = 0;
                var home_type = '';
                var input = '';
                var room_type_index = 0;
                var room_type = '';
                var accommodates = '';
                var accom_index = 0;
                var text = '';
                var city = '';
                var address = '';
                var lat = '';
                var lng = '';
                $("#click").click(function () {
                    $("#dropdown").slideToggle("slow");
                });

                $("#click_accom").click(function () {
                    $("#dropdown_accom").slideToggle("slow");
                });
                $("#click_accom2").click(function () {
                    $("#dropdown_accom2").slideToggle("slow");
                });
                /* $(document).click( function(){
         
                 $('#dropdown').hide();
                 $('#dropdown_accom').hide();
                 $('#dropdown_accom2').hide();
         
                 }); */
                $('#dropdown li').click(function () {
                    var text = $(this).text();
                    // alert('Index is: ' + index + ' and text is ' + text);
                    $("#dropdown").hide();
                    $('#apartment_before').hide();
                    var site_title = '<?php echo $this->dx_auth->get_site_title() . ' ' . translate("guests love the variety of home types available."); ?>';
                    $("#other_after").html('<div class="btn-type"><label class="hosting-onboarding light-btn"><div class="inner"><i class="icon_other_hover"></i><span class="hover">' + text + '</span></div><div class="circle"><div class="inner"><i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i></div></div></label><label id="background_panel" class="hosting-onboarding background_panel light-btn-center"><span class="guest_value">' + site_title + '</span> </label></div>');
                    $("#other_after").show();
                    home_type = text;
                    home_type_index = 1;
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && input != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                });
                $('#other_after').click(function ()
                {
                    $('#other_after').hide();
                    $('#apartment_before').show();
                    home_type_index = 0;
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && input != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $('#apt_first').click(function ()
                {
                    $('#apartment_before').hide();
                    $('#apartment_after').show();
                    //	alert($('#apartment_span').text());
                    home_type_index = 1;
                    home_type = $('#apartment_span').text();
                    city = $('#city_after_span').text();
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && city != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                        $('#final').hide();
                    }

                })
                $('#apartment_click_after').click(function ()
                {
                    $('#apartment_after').hide();
                    $('#apartment_before').show();
                    //	if(home_type_index == 1)
                    //	{
                    home_type_index = 0;
                    //	}
                    //	if(room_type_index != 1 && home_type_index != 1 && accom_index != 1 && city == '')
                    // {
                    $('#continue').show();
                    $('#continue2').hide();
                    $('#final').hide();
                    /*  }
                     else if(room_type_index == 1 && home_type_index == 1 && accom_index == 1 && city != '')
                     {
                     $('#continue').hide();
                     $('#continue2').show();
                     $('#final').hide();
                     }*/
                })
                $('#house_first').click(function ()
                {
                    $('#apartment_before').hide();
                    $('#house_after').show();
                    //alert($('#house_span').text());
                    home_type_index = 1;
                    home_type = $('#house_span').text();
                    city = $('#city_after_span').text();
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && city != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                        $('#final').hide();
                    }
                })
                $('#house_click_after').click(function ()
                {
                    $('#house_after').hide();
                    $('#apartment_before').show();
                    //alert($('#house_span_after').text());
                    if (home_type_index == 1)
                    {
                        home_type_index = home_type_index - 1;
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $('#bed_first').click(function ()
                {
                    $('#apartment_before').hide();
                    $('#bnb_after').show();
                    home_type_index = 1;
                    home_type = $('#bnb_span').text();
                    city = $('#city_after_span').text();
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && city != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                        $('#final').hide();
                    }
                })
                $('#bnb_click_after').click(function ()
                {
                    $('#bnb_after').hide();
                    $('#apartment_before').show();
                    if (home_type_index == 1)
                    {
                        home_type_index = home_type_index - 1;
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $("#entire").click(function ()
                {
                    $("#room_type").hide();
                    $("#entire_after_main").show();
                    room_type_index = 1;
                    room_type = $("#entire_span").text();
                    //alert(room_type);
                    city = $('#city_after_span').text();
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && city != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                        $('#final').hide();
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $("#entire_after").click(function ()
                {
                    $("#room_type").show();
                    $("#entire_after_main").hide();
                    if (room_type_index == 1)
                    {
                        room_type_index = room_type_index - 1;
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $("#private").click(function ()
                {
                    $("#room_type").hide();
                    $("#private_after_main").show();
                    room_type_index = 1;
                    room_type = $("#private_span").text();
                    //alert(room_type);
                    city = $('#city_after_span').text();
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && city != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                        $('#final').hide();
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $("#private_after").click(function ()
                {
                    $("#room_type").show();
                    $("#private_after_main").hide();
                    if (room_type_index == 1)
                    {
                        room_type_index = room_type_index - 1;
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $("#shared").click(function ()
                {
                    $("#room_type").hide();
                    $("#shared_after_main").show();
                    room_type_index = 1;
                    room_type = $("#shared_span").text();
                    //alert(room_type);
                    city = $('#city_after_span').text();
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && city != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                        $('#final').hide();
                    }
                })
                $("#shared_after").click(function ()
                {
                    $("#room_type").show();
                    $("#shared_after_main").hide();
                    if (room_type_index == 1)
                    {
                        room_type_index = room_type_index - 1;
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })

                $('#dropdown_accom li').click(function () {
                    accommodates = $(this).text();

                    // alert('Index is: ' + index + ' and text is ' + text);
                    $('#accom').hide();

                    $("#accom_after").show();
                    $("#accom2_span").replaceWith('<span id="#accom2_span">' + accommodates + '</span>');
                    accom_index = 1;

                });

                $('#dropdown_accom2 li').click(function () {
                    accommodates = $(this).text();

                    $("#accom_after").hide();
                    $("#accom_after").show();
                    $("#accom_after #accom2 #click_accom2 span").replaceWith('<span id="#accom2_span">' + accommodates + '</span>');
                    $('#dropdown_accom2').hide();

                });
                var city_type = 0;
                $('#city_before').click(function ()
                {
                    if (accommodates == '')
                    {
                        $('#accom').hide();
                        accommodates = 2;
                        $("#accom_after").show();
                        $("#accom2_span").replaceWith('<span id="#accom2_span">2</span>');
                        accom_index = 1;
                    }
                })
                input = document.getElementById('lys_address');
                autocomplete = new google.maps.places.Autocomplete(input);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {

        // alert($('#lys_address').val());
                    address = $('#lys_address').val();

                    var place = autocomplete.getPlace();
                    var lat = place.geometry.location.lat();
                    var lng = place.geometry.location.lng();

                    $('#lat').val(lat);
                    $('#lng').val(lng);

                    var city = '';
                    var state = '';
                    var zipcode = '';
                    var country = '';
                    var street_address = '';

                    jQuery.support.cors = true;

                    $.getJSON("http://maps.googleapis.com/maps/api/geocode/json?latlng=" + lat + "," + lng + "&sensor=true", function (data) {

                        address = data.results[0].formatted_address;
                        $('#hidden_address').val(address);
                        $("#city").hide();
                        $('#city_after').show();
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
                                //  $('#lys_street_address').val(addr.route);
                            }
                            if (types == "sublocality,political" || types == "locality,political" || types == "neighborhood,political" || types == "administrative_area_level_3,political") {
                                addr.city = (city == '' || types == "locality,political") ? data.results[0].address_components[ii].long_name : city;
                                $('#city_addr').val(addr.city);
                                $('#city').val(addr.city);
                            }
                            if (types == "administrative_area_level_1,political") {
                                addr.state = data.results[0].address_components[ii].long_name;
                                $('#state').val(addr.state);
                            }
                            if (types == "postal_code" || types == "postal_code_prefix,postal_code") {
                                addr.zipcode = data.results[0].address_components[ii].long_name;
                                // $('#zipcode').val(addr.zipcode);
                            }
                            if (types == "country,political") {
                                addr.country = data.results[0].address_components[ii].long_name;
                                $('#country').val(addr.country);
                            }
                        }
                        city_type = 1;
                    });

                    var explode = $('#lys_address').val().split(',');
                    var explode1 = explode[0].split(' ');
                    city = explode1[0];

                    $('#city_label').html('<div class="inner"><i class="icon_city_hover"></i><span id="city_after_span">' + city + '</span></div><div class="circle"><div class="inner"><i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i></div></div></label>');
                    if (room_type_index == 1 && home_type_index == 1 && accom_index == 1 && input != '')
                    {
                        $('#continue').hide();
                        $('#continue2').show();
                    }
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                });

                $('#city_label').click(function ()
                {
                    city = '';
                    $('#city_after').hide();
                    $('#city').show();
                    if (room_type_index != 1 || home_type_index != 1 || accom_index != 1 || city == '')
                    {
                        $('#continue').show();
                        $('#continue2').hide();
                        $('#final').hide();
                    }
                })
                $('#continue2').click(function ()
                {
                    city = $('#city_addr').val();
                    state = $('#state').val();
                    country = $('#country').val();
                    lat = $('#lat').val();
                    lng = $('#lng').val();

                    $('#continue2').hide();
                    $('#final').show();

                    $.ajax({
                        type: "POST",
                        dataType: "text",
                        url: '<?php echo base_url() . "rooms/lys_new"; ?>',
                        data: {home: home_type, room: room_type, accom: accommodates, addr: address, city: city, lat: lat, lng: lng, state: state, country: country},
                        success: function (data)
                        {
                            window.location.href = '<?php echo base_url() . "rooms/lys_next/id/"; ?>' + data;
                        }
                    });
                })

            });
            $("#apt_first").mouseenter(function () {
                $("#apt_first_hover").show();
            }).mouseleave(function () {
                $("#apt_first_hover").hide();
            });
        </script>

    </head>
    <body>

        <input type="hidden" id="city_addr"/>
        <input type="hidden" id="state"/>
        <input type="hidden" id="country"/>
        <input type="hidden" id="lat"/>
        <input type="hidden" id="lng"/>

        <ul id="location">
            <input id="address_formatted_address_native" name="formatAddress" type="hidden" />
            <input id="address_lat" name="lat" type="hidden" value=""/>
            <input id="address_lng" name="lng" type="hidden" value=""/>
            <input disabled="disabled" id="address_user_defined_location" name="udlocation" type="hidden" value="true" />

        </ul>
        <div class="container_lys">
            <div class="hr padding-panel">
                <h1 class="iyf"><span class="invite_friend_listspace"><?php echo translate('List Your Space'); ?></span></h1>
                <p class="lead"><?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('lets you make money renting out your place.'); ?></p>
            </div>
        </div>

        <div class="panel-background-blue-radial">
            <div class="container_lys container_inner_pannel">
                <div class="martin">
                    <div class="row_list">
                        <div class="row_left_lys fl_right">
                            <h2><?php echo translate('Home Type'); ?></h2>
                        </div>
                        <div class="row_right_lys fl_left" id="apartment_before">
                            <div class="btn-type">
                                <label id="apt_first" class="hosting-onboarding light-btn">
                                    <i class="icon_apt_build"></i>
                                    <span id="apartment_span"><?php $property1 = $this->db->where('id', 1)->get('property_type')->row()->type;
    echo $property1; ?></span>
                                    <div class="apt_first_hover"><i class="apt_in-drop"></i><?php echo translate('Your space is an apartment, flat, or unit in a multi-unit building.'); ?></div>
                                </label>
                                <label id="house_first" class="hosting-onboarding light-btn-center">
                                    <i class="icon_house"></i>
                                    <span id="house_span"><?php $property2 = $this->db->where('id', 2)->get('property_type')->row()->type;
    echo $property2; ?></span>
                                    <div class="house_first_hover"><i class="house_in-drop"></i><?php echo translate('Your space is a single-family house or townhouse.'); ?></div>
                                </label>
                                <label id="bed_first" class="hosting-onboarding light-btn-center">
                                    <i class="icon_breakfast"></i>
                                    <span id="bnb_span"><?php $property3 = $this->db->where('id', 3)->get('property_type')->row()->type;
    echo $property3; ?></span>
                                    <div class="bed_first_hover"><i class="bed_in-drop"></i><?php echo translate('You rent out several rooms within an establishment. Your service includes breakfast.'); ?></div>
                                </label>
                            </div>
                            <div class="btn-type-last" style="margin-left:-2px;">
                                <button id="click" style="font:inherit;" class="light-btn-right other_right other_drop">
                                    <i class="icon_other"></i>
                                    <span style="font-size:14px;"><?php echo 'Other'; ?></span>
                                    <i class="icon_caret_dropdown"></i>
                                </button>
                                <ul id="dropdown" class="dropdown_other">
                                    <?php
                                        $property = $this->db->get('property_type');
                                        $i = 0;
                                        foreach ($property->result() as $value)
                                        {
                                            $i++;
                                            if ($i > 3)
                                            {
                                                echo '<li>';
                                                ?>
                                                <a href="#"><?php echo $value->type; ?></a>
            <?php
            echo '</li>';
        }
    }
?>  
                                </ul>
                            </div>
                        </div>
                        <div style="display:none" class="row_right_lys fl_left" id="apartment_after">
                            <div class="btn-type">
                                <label class="hosting-onboarding light-btn" id="apartment_click_after">
                                    <div class="inner-cont">
                                        <i class="icon_apt_build_hover"></i>
                                        <span class="hover" id="apartment_span_after"><?php $property1 = $this->db->where('id', 1)->get('property_type')->row()->type;
    echo $property1; ?></span>
                                    </div>

                                    <div class="circle">                  
                                        <div class="inner">
                                            <i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i>
                                        </div>
                                    </div>
                                </label>
                                <label id="background_panel" class="hosting-onboarding background_panel light-btn-center">
                                    <span class="guest_value"><?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('guests love the variety of home types available.'); ?></span> 
                                </label>
                            </div>
                        </div>
                        <div style="display:none" class="row_right_lys fl_left" id="house_after">
                            <div class="btn-type">
                                <label class="hosting-onboarding light-btn" id="house_click_after">
                                    <div class="inner">
                                        <i class="icon_house_hover"></i>
                                        <span class="hover" id="house_span_after"><?php $property2 = $this->db->where('id', 2)->get('property_type')->row()->type;
    echo $property2; ?></span>
                                    </div>
                                    <div class="circle">                  
                                        <div class="inner">
                                            <i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i>
                                        </div>
                                    </div>
                                </label>
                                <label id="background_panel" class="hosting-onboarding background_panel light-btn-center">
                                    <span class="guest_value"><?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('guests love the variety of home types available.'); ?></span> 
                                </label>
                            </div>
                        </div>
                        <div style="display:none" class="row_right_lys fl_left" id="bnb_after">
                            <div class="btn-type">
                                <label class="hosting-onboarding light-btn" id="bnb_click_after">
                                    <div class="inner">
                                        <i class="icon_breakfast_hover"></i>
                                        <span class="hover" id="bnb_span_after"><?php $property3 = $this->db->where('id', 3)->get('property_type')->row()->type;
    echo $property3; ?></span>
                                    </div>
                                    <div class="circle">                  
                                        <div class="inner">
                                            <i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i>
                                        </div>
                                    </div>
                                </label>
                                <label id="background_panel" class="hosting-onboarding background_panel light-btn-center">
                                    <span class="guest_value"><?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('guests love the variety of home types available.'); ?></span> 
                                </label>
                            </div>
                        </div>
                        <div style="display:none;" class="row_right_lys fl_left" id="other_after">

                        </div>
                    </div>

                    <div class="row_list">
                        <div class="row_left_lys fl_right">
                            <h2><?php echo translate('Room Type'); ?></h2>
                        </div>
                        <div class="row_right_lys fl_left" id="">
                            <div class="btn-type" id="room_type">
                                <label id="entire" class="hosting-onboarding light-btn">
                                    <i class="icon_ent_home"></i>
                                    <span id="entire_span"><?php echo translate('Entire Home/Apt'); ?></span>
                                    <div class="entire_first_hover"><i class="apt_in-drop"></i><?php echo translate("You're renting out an entire home."); ?></div>
                                </label>

                                <label id="private" class="hosting-onboarding light-btn-center">
                                    <i class="icon_private"></i>
                                    <span id="private_span"><?php echo translate('Private Room'); ?></span>
                                    <div class="private_first_hover"><i class="apt_in-drop"></i><?php echo translate("You're renting out a private room within a home."); ?></div>
                                </label>

                                <div class="btn-type-last" style="margin-left:-2px;" id="shared">
                                    <button id="shared_first" class="light-btn-right other_right">
                                        <i class="icon_shareroom"></i>
                                        <span style="font-size: 12px; font-family: Helvetica;" id="shared_span"><?php echo translate('Shared Room'); ?></span>
                                        <div class="shared_first_hover"><i class="apt_in-drop"></i><?php echo translate("You're renting out a common area, such as an airbed in a living room."); ?></div>
                                    </button>

                                </div>
                            </div>
                        </div>
                        <div style="display:none" class="row_right_lys fl_left" id="entire_after_main">
                            <div class="btn-type">
                                <label class="hosting-onboarding light-btn" id="entire_after">
                                    <div class="inner">
                                        <i class="icon_apt_hover"></i>
                                        <span class="hover"><?php echo translate('Entire Home/Apt'); ?></span>
                                    </div>
                                    <div class="circle">                  
                                        <div class="inner">
                                            <i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i>
                                        </div>
                                    </div>
                                </label>
                                <label id="room_type"  class="hosting-onboarding background_panel light-btn-center">
                                    <span class="guest_value"><?php echo translate('Room type is one of the most important criteria for'); ?> <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('guests.'); ?></span> 
                                </label>
                            </div>
                        </div>
                        <div style="display:none" class="row_right_lys fl_left" id="private_after_main">
                            <div class="btn-type">
                                <label class="hosting-onboarding light-btn" id="private_after">
                                    <div class="inner">
                                        <i class="icon_private_hover"></i>
                                        <span class="hover"><?php echo translate('Private Room'); ?></span>
                                    </div>
                                    <div class="circle">                  
                                        <div class="inner">
                                            <i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i>
                                        </div>
                                    </div>
                                </label>
                                <label id="room_type" class="hosting-onboarding background_panel light-btn-center">
                                    <span class="guest_value"><?php echo translate('Room type is one of the most important criteria for'); ?> <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('guests.'); ?></span> 
                                </label>
                            </div>
                        </div>
                        <div style="display:none" class="row_right_lys fl_left" id="shared_after_main">
                            <div class="btn-type">
                                <label class="hosting-onboarding light-btn" id="shared_after">
                                    <div class="inner">
                                        <i class="icon_shareroom_hover"></i>
                                        <span class="hover"><?php echo translate('Shared Room'); ?></span>
                                    </div>
                                    <div class="circle">                  
                                        <div class="inner">
                                            <i class="icon-icon-right"><img src="<?php echo css_url(); ?>/images/icon-icon-up.png"></i>
                                        </div>
                                    </div>
                                </label>
                                <label id="room_type" class="hosting-onboarding background_panel light-btn-center">
                                    <span class="guest_value"><?php echo translate('Room type is one of the most important criteria for'); ?> <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('guests.'); ?></span> 
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row_list">
                        <div class="row_left_lys fl_right">
                            <h2><?php echo translate('Accommodates'); ?></h2>
                        </div>
                        <div class="row_right_lys fl_left">
                            <div class="btn-type-last" id="accom">
                                <button id="click_accom" class="light-btn-right-accom other_right accom_panel">
                                    <i class="icon_accom"></i>
                                    <span>2</span>
                                    <i class="icon_caret_dropdown_accom"></i>
                                </button>
                                <ul id="dropdown_accom" style="min-width:125px;" class="dropdown_other">
                                    <li>
                                        <a href="#">1</a>
                                    </li>
                                    <li>
                                        <a href="#">2</a>
                                    </li>
                                    <li>
                                        <a href="#">3</a>
                                    </li>
                                    <li>
                                        <a href="#">4</a>
                                    </li>
                                    <li>
                                        <a href="#">5</a>
                                    </li>
                                    <li>
                                        <a href="#">6</a>
                                    </li>
                                    <li>
                                        <a href="#">7</a>
                                    </li>
                                    <li>
                                        <a href="#">8</a>
                                    </li>
                                    <li>
                                        <a href="#">9</a>
                                    </li>
                                    <li>
                                        <a href="#">10</a>
                                    </li>
                                    <li>
                                        <a href="#">11</a>
                                    </li>
                                    <li>
                                        <a href="#">12</a>
                                    </li>
                                    <li>
                                        <a href="#">13</a>
                                    </li>
                                    <li>
                                        <a href="#">14</a>
                                    </li>
                                    <li>
                                        <a href="#">15</a>
                                    </li>
                                    <li>
                                        <a href="#">16+</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div style="display:none" class="row_right_lys fl_left" id="accom_after">
                            <div class="btn-type" id="accom2">
                                <button id="click_accom2" style="border-radius:5px 0 0 5px;" class="light-btn-right-accom other_right accom_panel">
                                    <i class="icon_accom_hover"></i>
                                    <span id="accom2_span">
                                    </span>
                                    <i class="icon_caret_dropdown_accom"></i></button>
                                <ul id="dropdown_accom2" style="min-width:125px;" class="dropdown_other">
                                    <li>
                                        <a href="#">1</a>
                                    </li>
                                    <li>
                                        <a href="#">2</a>
                                    </li>
                                    <li>
                                        <a href="#">3</a>
                                    </li>
                                    <li>
                                        <a href="#">4</a>
                                    </li>
                                    <li>
                                        <a href="#">5</a>
                                    </li>
                                    <li>
                                        <a href="#">6</a>
                                    </li>
                                    <li>
                                        <a href="#">7</a>
                                    </li>
                                    <li>
                                        <a href="#">8</a>
                                    </li>
                                    <li>
                                        <a href="#">9</a>
                                    </li>
                                    <li><a href="#">10</a>

                                    </li>
                                    <li><a href="#">11</a>

                                    </li>
                                    <li>
                                        <a href="#">12</a>
                                    </li><li>
                                        <a href="#">13</a> 
                                    </li> 
                                    <li><a href="#">14</a></li><li><a href="#">15</a></li><li><a href="#">16+</a>

                                    </li></ul><label class="hosting-onboarding background_panel light-btn-center">
                                    <span class="guest_value"><?php echo translate("Whether you're hosting a lone traveler or a large group, it's important for your guests to feel comfortable."); ?></span></label>
                            </div>
                        </div>   

                        <div class="row_list" >
                            <div class="row_left_lys fl_right">
                                <h2><?php echo translate('City'); ?></h2>
                            </div>
                            <div class="row_right_lys fl_left" id="city_before" >
                                <div class="btn-type-last">
                                    <div style="height: 30px;" class="light-btn-right-accom other_right" id="city">
                                        <i class="icon_city"></i>
                                        <input class="city_input" type="text" id="lys_address" placeholder="San Francisco, Room, Shibuya..." />

                                    </div>
                                </div>
                            </div>
                            <div style="display:none" class="row_right_lys fl_left" id="city_after">
                                <div class="btn-type">
                                    <label class="hosting-onboarding light-btn" id="city_label">
                                    </label>
                                    <label class="hosting-onboarding background_panel light-btn-center forbigcty"><span class="guest_value" id="forparacity"><?php echo translate('What a great place to call home!'); ?></span> </label>
                                </div>
                            </div>
                        </div>

                        <div  class="mb2 row_list">
                            <div class="row_left_lys fl_right">
                                <h2>&nbsp;</h2>
                            </div>
                            <div class="row_right_lys fl_left" id="continue">
                                <div class="btn-type-last pink_btn">
                                    <span class="submit_lys"><?php echo translate('CONTINUE'); ?></span>
                                    <span class="submit_lys_tick"></span>
                                </div>
                            </div>
                            <div style="display:none;cursor: pointer;width:168px;" class="row_right_lys fl_left" id="continue2">
                                <div class="btn-type-last pink_btn_hover">
                                    <span class="submit_lys"><?php echo translate('CONTINUE'); ?></span>
                                    <span style="height:20px;" class="submit_lys_tick"></span>
                                </div>
                            </div>

                            <div style="display:none;" class="row_right_lys fl_left" id="final">

                                <div id="pink-btn" class="btn-type-last pink_btn_hover">

                                    <span class="submit_lys"><?php echo translate('CREATING YOUR LISTING...'); ?></span>
                                    <span class="submit_lys_loader"><img src="<?php echo base_url(); ?>images/spinning_arrows_on_pink.gif" /></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="contain_feedback padding-panel panel-bottom">
                <div class="container panel-inner-padding">
                    <div style="margin:0;" class="row_list">

                        <div class="span_lys">
                            <div class="image">
                                <img src="<?php echo base_url(); ?>images/host_couple.jpg" height="98" width="98" />
                                <h3><?php echo translate('Trust & Safety'); ?></h3>
                                <p><?php echo translate("World-class security & communications features mean you never have to accept a booking unless you're 100% comfortable."); ?></p>
                            </div>
                        </div>
                        <div class="span_lys offset">
                            <div class="image">
                                <img src="<?php echo base_url(); ?>images/host_couple.jpg" height="98" width="98" />
                                <h3>$1,000,000 <?php echo translate('Host Guarantee'); ?></h3>
                                <p><?php echo translate("Your peace of mind is priceless. So we don't charge for it. Every single booking on DropInn is covered by our Host Guarantee - at no cost to you."); ?></p>
                            </div>
                        </div>
                        <div class="span_lys offset">
                            <div class="image">
                                <img src="<?php echo base_url(); ?>images/host_couple.jpg" height="98" width="98" />
                                <h3><?php echo translate('Secure Payments'); ?></h3>
                                <p><?php echo translate('Our fast, flexible payment system puts money in your bank account 24 hours after guests check in.'); ?></p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </body>
</html>
