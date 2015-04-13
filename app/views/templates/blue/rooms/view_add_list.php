<link href="<?php echo css_url() . '/post_room.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- More Scripts more and more -->

<!--  End custom scripts -->
<div class="container_bg_listpage" id="View_Ad_List"> <?php echo form_open("rooms/addNewEntry", array('id' => 'new_room_form', 'name' => "add_list")) ?>
    <input id="redirect_params_sig" name="redirect_params[sig]" type="hidden" />
    <input id="redirect_params_action" name="redirect_params[action]" type="hidden" value="set_user" />
    <input id="retry_params_sig" name="retry_params[sig]" type="hidden" />
    <input id="retry_params_action" name="retry_params[action]" type="hidden" value="create" />
    <input id="retry_params_id" name="retry_params[id]" type="hidden" />
    <div id="post_a_room">
        <div id="view_list_head" class="clearfix">
            <div id="view_list_head_left" class="clsFloatLeft">
                <h1><?php echo translate("List your space."); ?></h1>
                <h2><?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate("lets you make money renting out your place."); ?> <?php echo translate("Your apartment will pay for itself!"); ?></h2>
            </div>
            <div id="view_list_head_right" class="clsFloatRight"> <img src="<?php echo css_url() . '/images/list_ur_img.png' ?>" alt="List your space"/> </div>
            <div class="clear"></div>
        </div>
        <div class="narrow_page_section post_room_step2">
            <div class="section_head">
                <h2 style="border-radius: 6px 6px 0px 0px;"> <?php echo translate("Details"); ?> <span class="header-badge public-badge"><span><?php echo translate("Public"); ?></span><b></b></span> </h2>
                <p><?php echo translate("Your place is amazing and unique. Tell potential guests why they would want to stay there. Highlight what makes your home a standout!"); ?> </p>
            </div>
            <ul id="details" class="narrow_page_section_content">
                <li>
                    <label for="hosting_property_type_id"><?php echo translate("Property Type:"); ?></label>
                    <?php
                        $sql = "SELECT * from property_type";

                        $resultset = $this->db->query($sql, array($sql))->result_array();
                    ?>
                    <select id="hosting_property_type_id" name="property_id">
                        <?php
                            foreach ($resultset as $property)
                            {

                                echo " <option value='" . $property['id'] . "'>" . $property['type'] . "</option>";
                            }
                        ?>
                     <!-- <option value="2"><?php echo "House"; ?></option>
                      <option value="3"><?php echo "Bed & Breakfast"; ?></option>
                      <option value="4"><?php echo "Cabin"; ?></option>
                      <option value="11"><?php echo "Villa"; ?></option>
                      <option value="5"><?php echo "Castle"; ?></option>
                      <option value="9"><?php echo "Dorm"; ?></option>
                      <option value="6"><?php echo "Treehouse"; ?></option>
                      <option value="8"><?php echo "Boat"; ?></option>
                      <option value="7"><?php echo "Automobile"; ?></option>
                      <option value="12"><?php echo "Igloo"; ?></option>
                      <option value="10"><?php echo "Lighthouse"; ?></option>-->
                    </select>
                </li>
                <li>
                    <label for="hosting_person_capacity"><?php echo translate("Accommodates:"); ?></label>
                    <select id="hosting_person_capacity" name="capacity">
                        <?php for ($i = 1; $i <= 16; $i++)
                            {
                                ?>
                                <option value="<?php echo $i; ?>"><?php echo $i;
                            if ($i == 16)
                                echo '+';
                            ?> </option>
    <?php } ?>
                    </select>
                </li>
                <li>
                    <label for="hosting_room_type"><?php echo translate("Privacy:"); ?></label>
                    <select id="hosting_room_type" name="room_type">
                        <option value="Private room" selected="selected"><?php echo translate("Private room"); ?></option>
                        <option value="Shared room"><?php echo translate("Shared room"); ?></option>
                        <option value="Entire home/apt"><?php echo translate("Entire home/apt"); ?></option>
                    </select>
                </li>
                <li>
                    <label for="hosting_bedrooms"><?php echo translate("Bedrooms:"); ?></label>
                    <select id="hosting_bedrooms" name="bedrooms">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                </li>
                <li>
                    <div class="label_with_description">
                        <label for="hosting_name" class="tall_label"><?php echo translate("Title:"); ?><sup style="color:#FF0000">*</sup></label>
                    </div>
                    <input class="large active" id="hosting_name" maxlength="35" name="Hname" size="50" style="margin-right:2px;" type="text" />

                    <span id="letter_count">35</span> <span id="title_message"></span> </li>
                <li>
                    <div class="label_with_description">
                        <label for="hosting_description" style="vertical-align:top;"><?php echo translate("Description:"); ?><sup style="color:#FF0000">*</sup> </label>
                    </div>
                    <textarea cols="40" id="hosting_description" name="desc" rows="20" style="height:200px;width:380px;"></textarea>
                </li>	
                <li>

                    <label id="hosting_price_native_label" for="hosting_price_native"><?php echo translate("Price:"); ?><sup style="color:#FF0000">*</sup></label>

                    <select id="currency_type" name="currency">
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                        <option value="EUR">EUR</option>
                        <option value="AUD">AUD</option>
                        <option value="SGD">SGD</option>
                        <option value="SEK">SEK</option>
                        <option value="DKK">DKK</option>
                        <option value="MXN">MXN</option>
                        <option value="BRL">BRL</option>
                        <option value="MYR">MYR</option>
                        <option value="PHP">PHP</option>
                        <option value="CHF">CHF</option>
                    </select>

                    <input class="active" id="hosting_price_native" onkeypress="return numeralsOnly(event)" name="native_price" size="30" style="width:50px; float:none; margin-right:10px; padding-left: 2px;" type="text" value="0" />
                    <input id="price_suggestion_low" name="price_suggestion_low" type="hidden" />
                    <input id="price_suggestion_high" name="price_suggestion_high" type="hidden" />
                    <span id="per-night-span"><?php echo translate("per night"); ?></span> <span id="sublet-rates" style="display: none;"> <span id="per-month-span"><?php echo translate("per month"); ?></span> <span id="flat-rate-span" style="display: none;"><?php echo translate("flat rate"); ?></span> </span>
                    <p id="price_suggestion"><?php echo translate("We suggest:"); ?><span class="currency_symbol"></span><span id="price_suggestion_low_text"></span> &mdash; <span class="currency_symbol"></span><span id="price_suggestion_high_text"></span><a class="tooltip" title="This range is determined by nearby listings."></a></p>
                </li>
                <li>
                    <label for="hosting_bedrooms"><?php echo translate("Cancellation Policy:"); ?></label>
                    <select id="hosting_bedrooms" name="cancellation_policy">
                        <option value="Flexible">Flexible</option>
                        <option value="Moderate">Moderate</option>
                        <option value="Strict">Strict</option>
                        <option value="Super Strict">Super Strict</option>
                        <option value="Long Term">Long Term</option>
                    </select>
                </li>
            </ul>
        </div>
        <div class="narrow_page_section post_room_step1">
            <div class="section_head">
                <h2> <?php echo translate("Find Your Address"); ?> <span class="public-badge"><?php echo translate("Protected"); ?></span> </h2>
                <p> <?php echo translate("Your contact information and full address are only shared with guests that you have accepted. We hide these details from everybody else."); ?> </p>
            </div>
            <div class="narrow_page_section_content rounded_bottom">
                <div id="address_section">
                    <div id="address_step1">
                        <p>
                            <?php
                                $viewer = getenv("HTTP_USER_AGENT");

                                if (preg_match("/Safari/i", "$viewer"))
                                {
                                    $browser = "safari";
                                }
                                elseif (preg_match('/Chrome/i', "$viewer"))
                                {
                                    $browser = "chrome";
                                }
                                else
                                {
                                    $browser = "other";
                                }
                            ?>

                            <label id="location_search_label" class="tall_label" for="location_search"><?php echo translate("Full address:"); ?><sup style="color:#FF0000">*</sup></label>
                            <?php
                                if ($browser == "safari" || $browser == "chrome")
                                {
                                    ?>
                                    <input type="text" class="location active" autocomplete="off" id="location_search" name="location_search" onchange="chrome()"/>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    <input type="text" class="location active" autocomplete="off" id="location_search" name="location_search" onchange="show1()"/>
    <?php } ?>

                        </p>

                        <p>
                            <label>&nbsp;</label>
                            <a id="change_location_link" style="" onclick="add();"><?php echo translate("Change Location"); ?></a>
                        </p>
                    </div>
                    <div id="way_too_vague" class="vague_address_warning rounded" style="display:none">
                        <p><?php echo translate("Whoops! The address you selected is not specific enough. Try entering your address with the full street number."); ?><br />
                        </p>
                    </div>
                    <div id="didyoumean" style="display:none">
                        <p><?php echo translate("Did you mean:"); ?></p>
                        <ul id="didyoumean-addresses">
                        </ul>
                    </div>
                    <div id="address_step2">
                        <div id="map_container">
                            <div id="map_canvas"></div>
                            <div id="step1_extras" style="">
                                <div id="selected_address">
                                    <p id="your_address"> <?php echo translate("Your Address:"); ?></span>
                                    <ul id="address_container">
                                        <li id="formatted_address"></li>
                                        <li id="exact_address_prompt">
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="contact_info_section_field" style="padding-top:15px;display:none;">
                                <p><label for="hosting_directions" class="tall_label"><?php echo translate("Directions to your place:"); ?></label>
                                    <textarea class="active" cols="40" disabled="disabled" id="hosting_directions" name="directions" rows="8" style="height:auto; width: 380px;"></textarea></p>
                            </div>
                        </div>
                        <ul id="location">
                            <input id="address_formatted_address_native" name="formatAddress" type="hidden" />
                            <input id="address_lat" name="lat" type="hidden" value=""/>
                            <input id="address_lng" name="lng" type="hidden" value=""/>
                            <input disabled="disabled" id="address_user_defined_location" name="udlocation" type="hidden" value="true" />

                        </ul>

                        <p>
                            <label for="hosting_bedrooms"><?php echo translate("Neighborhood Places:"); ?></label> 

                            <select id="area" name="areas" onclick="show();" style=" width:260px;">
                                <option value="Please Select Neighborhood place"><?php echo translate("Please Select Neighborhood place"); ?></option>
                            </select>

                        </p>
                        <p><div id="spining">
                        </div></p>

                    </div>
                </div>
                <div id="contact_info_section" class="get_started_subsection" style="">
                    <div class="contact_info_section_field">
                        <p><label for="hosting_email"><b class="header-badge protected-badge"></b><?php echo translate("Email Address:"); ?>

                                <sup style="color:#FF0000">*</sup></label>
                            <input class="large active" id="hosting_email" name="email" size="30" type="text" /></p>
                    </div>
                    <div class="contact_info_section_field">
                        <p><label for="hosting_phone"><?php echo translate("Phone:"); ?> <a class="tooltip" title="Primary Contact Number (cell or landline).&lt;br /&gt; We will give this number to the guest only after you accept their reservation request."></a><sup style="color:#FF0000">*</sup> </label>
                            <input id="hosting_phone" autocomplete="off" class="medium active" name="phone" size="30" type="text" />
                            <input id="hosting_phone_country" name="hosting[phone_country]" type="hidden" /></p>
                    </div>
                </div>
                <p>
                    <label>&nbsp;</label>
                    <button type="submit" class="btn green gotomsg" name="sub"><span><span><?php echo translate("Save & Continue"); ?></span></span></button>
                </p>
                <div id="error_summary" style="">
                    <p><?php echo translate("Please fix the following errors:"); ?></p>
                    <ul>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</form>
</div>

<script type="text/javascript">
    var CogzidelCurrencyInitializer = (function () {

        var my = {};

        my.USD = 1;
        my.EUR = 0.6957;
        my.DKK = 5.187;
        my.CAD = 0.9747;
        my.JPY = 80.89;
        my.GBP = 0.6112;
        my.AUD = 0.9372;
        my.ZAR = 6.8205;

        return my;
    }());
    Cogzidel.Currency.setCurrencyConversions(CogzidelCurrencyInitializer);

</script>
<script type="text/javascript">
    var Translations = {
        title: {
            great: "Great Title!",
            pretty_good: "That title is pretty short!",
            please_enter: "Please enter a title!"

        },
        address: "Address",
        email_address: "Email Address",
        title : "Title",
                description: "Description",
        price: "Price",
        phone_number: "Phone Number",
        address_error: "We need your address - the street number and apartment are private except for paying guests.",
        email_address_error: "A valid email address is required.",
        phone_number_error: "A valid phone number is required for our records. It is only revealed to paying guests. Please include your full country code and area code in your native format.",
        room_name_error: "Please provide a title for your space! It will show up in Search Results.",
        description_error: "Please provide a description.",
        price_error: "Please enter a price.",
        priceTooSmall_error: "Price must be at least 10",
        video_lightbox_title: "Cogzidel.com - How It Works",
        private_room_phrase: 'Private room',
        shared_room_phrase: 'Shared room',
        entire_home_phrase: 'Entire home/apt',
        not_so_vague: "The address you entered is not clear enough. \u003Cbr /\u003E\u003Cbr /\u003EZoom the map and drag this pin to your exact location.",
        not_so_vague_2: "Continue dragging this pin until the address on the left is as close to your real address as possible.",
        your_listing: "Your listing",
        sublet_real_end: "Make sure the end date for your sublet is a real date.",
        sublet_real_start: "Make sure the start date for your sublet is a real date.",
        sublet_start_before: "The end date for your sublet has to come after the start date.",
        sublet_min_nights: "Sublets have to be a minimum of 14 nights."
    };

    var Urls = {
        ajax_worth: '/rooms/ajax_worth'
    }

    jQuery(document).ready(function () {
        PostRoom.mapZoomLevel = 1;
        PostRoom.hostingLat = 0.0;
        PostRoom.hostingLng = 0.0;
        PostRoom.localized_hiw_video_code = 'SaOFuW011G8';
        PostRoom.SUBLET_MARKETS = ["New York", "San Francisco"];
        PostRoom.MINIMUM_SUBLET_STAY_MS = 14 * 86400000;
        PostRoom.SUBLET_CROSSOVER_MS = 25 * 86400000;

        var curr = $('#currency_type').val();

        var opts = {};

        PostRoom.init(opts);

        $('#price_suggestion').hide();
    });

    jQuery(document).ready(function () {
        Cogzidel.init({userLoggedIn: false});
    });

    function numeralsOnly(evt) {
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode :
                ((evt.which) ? evt.which : 0));
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            alert("Enter the rounded price value.");
            return false;
        }
        return true;
    }

    $(document).ready(function () {

        var curr = "<?php echo get_currency_code(); ?>";

        document.getElementById('currency_type').value = 'sdfasfd';

        document.add_list.currency_type.value = curr;
        // $('#curr').html(data);
    });

    var j = 1;
    var k = 0;
    function chrome()
    {
        k = 1;
        setTimeout("show()", 500);
    }
    function show1() {

        var lat = document.getElementById('address_lat').value;
        var lng = document.getElementById('address_lng').value;

        k = 1;
        j = 1;
        var combo = document.getElementById("area");
        combo.options.length = 0;

        var option = document.createElement("option");
        option.text = "Please Select Neighborhood place";
        option.value = "nothing select";
        try {
            combo.add(option, null);
        } catch (error) {
            combo.add(option);
        }
    }
    function show2()
    {
        k = 1;
        j = 1;
        var combo = document.getElementById("area");
        combo.options.length = 0;


    }
    function add()
    {
        k = 1;
    }

    function show() {
//j=j+1;
        if (k == 1)
        {
            $("#spining").html('<img src="<?php echo base_url() . "images/spinner.gif"; ?>">');
            var lat = document.getElementById('address_lat').value;
            var lng = document.getElementById('address_lng').value;
            var combo = document.getElementById("area");

            if (lat != '')
            {

                var dataString = "lat=" + lat + "&lng=" + lng;

                b_url = "<?php echo base_url() . 'rooms/area' ?>";
                $.ajax({
                    type: "POST",
                    url: b_url,
                    data: dataString,
                    success: function (data) {

                        $("#spining").html("");
                        if (data == '')
                        {
                            combo.options.length = 0;
                            var option = document.createElement("option");
                            option.text = "No neighbor places available";
                            option.value = "No neighbor";
                            try {
                                combo.add(option, null);
                            } catch (error) {
                                combo.add(option);
                            }

                        }
                        else
                        {
                            combo.options.length = 0;

                            var split = data.split(',');
                            var i;
                            for (i = 0; i < split.length - 1; i++)
                            {
                                var option = document.createElement("option");


                                option.text = split[i];
                                option.value = split[i];
                                try {
                                    combo.add(option, null);
                                } catch (error) {
                                    combo.add(option);
                                }

                            }

                        }
                    }

                });

                k = 0;
            }
            else
            {
                alert("Please enter address followed by city,state,country");
                $("#spin").html("");
            }

        }
    }
</script>
<script type="text/javascript" src="<?php echo js_url(); ?>/jquery-ui-1.8.14.custom.min.js"></script>
<script src="<?php echo js_url(); ?>/post_home.js" type="text/javascript"></script>


