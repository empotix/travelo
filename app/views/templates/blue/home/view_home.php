<style>
    body {
        background:#f7f7f7 ;
    }
</style>
<?php $this->load->library('Twconnect'); ?>
<!--aircont-->
<div class="app_view">
    <div id="hero" class="search_intro" data-native-currency="USD" style="display: block; height: 499.268px;">
        <div class="callbacks_container">
            <ul class="rslides" id="slider4">
                <?php
                    foreach ($lists->result() as $row)
                    {
                        $url = base_url() . 'images/' . $row->list_id . '/' . $row->name;
                        $profile_pic = $this->Gallery->profilepic($row->user_id, 3);
                        $city = explode(',', $row->address);
                        ?>
                        <li>
                            <img src="<?php echo $url; ?>" alt="" style="height:500px!important;">
                            <div class="caption">
                                <div class="heart_but">

                                    <img width="40" height="40" src="images/heart_but.png" alt="no heart image">

                                </div>
                                <div class="room_head">
                                    <strong>
                                        <span> <a href="<?php echo base_url() . 'rooms/' . $row->list_id; ?>"><?php echo $row->title; ?></a> </span>
                                    </strong>
                                    <br>
                                    <!--<a href="<?php echo base_url() . 'rooms/' . $row->list_id; ?>"><?php echo $city[2] . " - " . get_currency_symbol() . "" . $row->price; ?></a>-->
                                    <a href="<?php echo base_url() . 'rooms/' . $row->list_id; ?>"><?php echo $city[2] . " - " . get_currency_symbol($row->list_id) . get_currency_value1($row->list_id, "" . $row->price); ?></a>
                                </div>
                                <span class="thumb_img"> 
                                    <img src="<?php echo $profile_pic; ?>" height="40" width="40" />
                                </span>
                            </div>
                        </li>
                    <?php } ?>
            </ul>
        </div> </div>
</div>
<div class="search-area">
    <div id="blob-bg" style="display: block; opacity: 0.5;">
        <img width="600" height="180" src="css/templates/blue/images/search_bg.png" alt="">
    </div>
    <?php
        $result = $this->db->where('status', 0)->limit(1)->get('admin_key');
        $key = '';
        if ($result->num_rows() != 0)
        {
            foreach ($result->result() as $row)
            {

                $key = $row->page_key;
            }
        }
    ?>

    <div class="container" >
        <!-- style=" width:auto;" -->
        <h1><?php echo translate($key); ?></h1>
        <!--<h2>Rent from people in 38,368 cities and 192 countries.</h2>-->
        <form id="search_form" class="custom show-search-options position-left" action="<?php echo site_url('search'); ?>" method ="post">
            <div class="input-wrapper">
                <input id="location" style="width: 350px;" class="location" type="text" value="<?php echo translate("Where_do_you_want_to_go"); ?>" name="location" autocomplete="off" onblur="if (this.value == '') {
                            this.value = '<?php echo translate("Where_do_you_want_to_go"); ?>';
                        }"
                       onfocus="if (this.value == '<?php echo translate("Where_do_you_want_to_go"); ?>') {
                                   this.value = '';
                               }" placeholder="<?php echo translate("Where_do_you_want_to_go"); ?>">
                <p id="enter_location_error_message" class="bad" style="display:none;">&#10; Please set location&#10; </p>
            </div>
            <div id="checkinWrapper" class="input-wrapper">
                <input id="checkin" class="checkin search-option ui-datepicker-target" type="text" value="Check in" name="checkin" onblur="if (this.value == '') {
                            this.value = 'Check in';
                        }"
                       onfocus="if (this.value == 'Check in') {
                                   this.value = '';
                               }" readonly>
                <span class="search-area-icon"></span>
            </div>
            <div id="checkoutWrapper" class="input-wrapper">
                <input id="checkout" class="checkout search-option ui-datepicker-target" type="text" value="Check out" name="checkout" onblur="if (this.value == '') {
                            this.value = 'Check out';
                        }"
                       onfocus="if (this.value == 'Check out') {
                                   this.value = '';
                               }" readonly>
                <span class="search-area-icon search-area-icon-checkout"></span>
            </div>
            <div class="input-wrapper">
                <div class="custom-select-container">
                    <div id="guests_caption" class="custom dropdown small current" aria-hidden="true">1 <?php echo translate("Guest"); ?></div>
                    <div class="custom selector" style="margin-top:-25px;"></div>
                    <select id="guests" class="search-option small" name="number_of_guests">
                        <option value="1">1 <?php echo translate("Guest"); ?></option>
                        <option value="2">2 <?php echo translate("Guests"); ?></option>
                        <option value="3">3 <?php echo translate("Guests"); ?></option>
                        <option value="4">4 <?php echo translate("Guests"); ?></option>
                        <option value="5">5 <?php echo translate("Guests"); ?></option>
                        <option value="6">6 <?php echo translate("Guests"); ?></option>
                        <option value="7">7 <?php echo translate("Guests"); ?></option>
                        <option value="8">8 <?php echo translate("Guests"); ?></option>
                        <option value="9">9 <?php echo translate("Guests"); ?></option>
                        <option value="10">10 <?php echo translate("Guests"); ?></option>
                        <option value="11">11 <?php echo translate("Guests"); ?></option>
                        <option value="12">12 <?php echo translate("Guests"); ?></option>
                        <option value="13">13 <?php echo translate("Guests"); ?></option>
                        <option value="14">14 <?php echo translate("Guests"); ?></option>
                        <option value="15">15 <?php echo translate("Guests"); ?></option>
                        <option value="16">16+ <?php echo translate("Guests"); ?></option>
                    </select>
                </div>
            </div>
            <button id="submit_location" class="blue_home" type="submit" value="Search" name="Submit">
                <i class="icon icon-search"></i>
                <!--<img src="css/templates/blue/images/search_icon1.png" />-->
                <?php echo translate("Search"); ?>&#10;
            </button>
        </form>
    </div>
</div>
<div class="mid_banner_cont">
    <div id="mid_cont" class="midpos">
        <h1> <?php echo translate("Neighborhood Guides"); ?> </h1>
        <p> <?php echo translate("Not_sure"); ?> </p>
        <ul class="recent_view clearfix">
            <?php
                if (isset($cities))
                {
                    if ($cities->num_rows() != 0)
                    {
                        foreach ($cities->result() as $city)
                        {
                            ?>
                            <li class="rec_view1">
                                <div class="newbut">
                                <!--<img src="css/templates/blue/images/new_but.png" style="opacity:1;"/>-->
                                    <?php
                                    $city_created = $this->db->where('city_name', $city->city_name)->get('neigh_city')->row()->created;
                                    $month = 60 * 60 * 24 * 30; // month in seconds
                                    if (time() - $city_created < $month)
                                    {
                                        // within the last 30 days ...
                                        echo translate("new");
                                    }
                                    ?> 
                                <!--<span> new </span>-->
                                </div>
                                <!--<a href="<?php echo site_url() . "rooms/" . $row->id; ?>"></a>-->
                                <a href='<?php echo site_url() . "neighbourhoods/city/$city->id"; ?>' class="home">
                                    <?php
                                    $image_name = $this->db->where('city_name', $city->city_name)->from('neigh_city')->get()->row()->image_name;
                                    $city_id = $this->db->where('city_name', $city->city_name)->from('neigh_city')->get()->row()->id;
                                    ?>
                                    <img src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/home.jpg'; ?>"/>
                                    <div class="room_n">
                                        <label class="room_name"><?php echo $city->city_name; ?></label>
                                        <?php
                                        $this->db->distinct()->select('neigh_city_place.place_name')->where('neigh_city_place.is_featured', 1)->where('neigh_city_place.city_name', $city->city_name);
                                        $this->db->join('neigh_post', 'neigh_post.place = neigh_city_place.place_name')->where('neigh_post.is_featured', 1);
                                        $this->db->from('neigh_city_place');
                                        $place_ = $this->db->get();
                                        ?>
                                        <label class="neigh_count"><?php echo $place_->num_rows() . ' ' . translate('Neighbourhoods'); ?></label>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                    <p class="text-center">
                        <a href='<?php echo base_url() . 'home/neighborhoods'; ?>'><?php echo translate('All neighborhood guides'); ?></a>
                    </p>
                    <?php
                }
                else
                {
                    ?>
                    <?php echo translate("No Neighbourhoods"); ?>
                    <?php
                }
            }
            else
            {
                echo translate("No Neighbourhood Places");
            }
        ?>
    </div></div>
<div id="list_home">

    <div class="travel">
        <h3> <?php echo translate("Travel"); ?> </h3>
        <p> <?php echo translate("From_apartments"); ?></p>
        <a href="<?php echo site_url('pages/view/travel'); ?>"> <?php echo translate("See most booked"); ?> <span> >> </span> </a>
    </div>

    <div class="host">
        <h3> <?php echo translate("Host"); ?>  </h3>
        <p>  <?php echo translate("Renting_out"); ?> </p>
        <a href="<?php echo site_url('pages/view/why_host'); ?>"> <?php echo translate("Learn_more"); ?> >> </a>
    </div>

    <div class="work">
        <h3> <?php echo translate("How It Works"); ?> </h3>
        <p> <?php echo translate("From_our"); ?></p>
        <a href="<?php echo site_url('info/how_it_works'); ?>"> <?php echo translate("Visit the trust & safety center"); ?> >> </a>
    </div>
</div>
</div>
<!--end air-->
<script>
    // You can also use "$(window).load(function() {"
    $(function () {
        // Slideshow 4
        $("#slider4").responsiveSlides({
            auto: true,
            pager: false,
            nav: true,
            speed: 500,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });

    });
</script>

<script type="text/javascript">
    $(document).ready(function () {
        preloader();
        $("#guests").change(function () {
            var guest = $("#guests").val();
            var temp_guest = "";
            if (guest == "1")
            {
                temp_guest = guest + " " + "<?php echo translate("Guest"); ?>";
            }
            else if (guest == "16")
            {
                temp_guest = guest + "+ " + "<?php echo translate("Guests"); ?>";
            }
            else
            {
                temp_guest = guest + " " + "<?php echo translate("Guests"); ?>";
            }
            $("#guests_caption").html(temp_guest);
        });
    })
// Home Page Checkin Checkout date function below lines jQuery
    jQuery(document).ready(function () {
        Translations.review = "review";
        Translations.reviews = "reviews";
        Translations.night = "night";
        var opts = {};
        CogzidelHomePage.init(opts);
        CogzidelHomePage.defaultSearchValue = "Where are you going?";
    });


    jQuery(document).ready(function () {
        Cogzidel.init({userLoggedIn: false});
    });

    Cogzidel.FACEBOOK_PERMS = "email,user_birthday,user_likes,user_education_history,user_hometown,user_interests,user_activities,user_location";
</script>
<script language="JavaScript">
    function preloader()
    {
        // counter
        var i = 0;
        // create object
        imageObj = new Image();
        // set image list
        images = new Array();
<?php
    $i = 0;
    foreach ($lists->result() as $row)
    {
        $url = base_url() . 'images/' . $row->list_id . '/' . $row->name;
        ?>
                images[<?php echo $i; ?>] = "<?php echo $url; ?>"
        <?php
        $i++;
    } $num_rows = $lists->num_rows();
    $total_rows = $num_rows - 1;
?>
        // start preloading
        for (i = 0; i <=<?php echo $total_rows; ?>; i++)
        {
            imageObj.src = images[i];
        }
    }

    $(document).ready(function () {
        $("#view_most").html('<img src="<?php echo base_url() . "images/loader.gif"; ?>">');
    })
</script>
<script type="text/javascript">

    $(document).ready(function () {

        var input = document.getElementById('location');
        autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {

        });
    });


</script>
