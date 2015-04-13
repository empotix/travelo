<script src="<?php echo base_url() . 'js/facebook_invite.js'; ?>"></script>
<script>
    FB.init({
        appId: '<?php echo $fb_app_id; ?>',
        frictionlessRequests: true
    });
    FB.getLoginStatus(function (response) {
        if (response.status === 'connected') {
            $("#first_div").hide();
            FB.api('/me/friends', function (response) {
                if (response.data) {

                    var count = 0;
                    $.each(response.data, function (index, friend) {
                        count++;
                    });
                    var i = 0;
                    var id = new Array();
                    var name = new Array();
                    $.each(response.data, function (index, friend) {
                        id[i] = friend.id;
                        name[i] = friend.name;

                        i++;
                    });
                    //alert(id[0]);
                    // return false;

                    // alert(friend.name + ' has id:' + friend.id);
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url() . "home/fun_friends_fb_id"; ?>',
                        data: {fb_id: id, fb_name: name, friends_count: count, match_count: i},
                        success: function (data)
                        {

                            if (data)
                            {
                                //alert(data);
                                $('#div').html(data);
                            }
                        }
                    });
                    //  });
                } else {
                    alert("Error!");
                }
            });
        } else if (response.status === 'not_authorized') {
            // the user is logged in to Facebook, 
            // but has not authenticated your app
        } else {
            // the user isn't logged in to Facebook.
            $("#first_div").show();
        }
    });
    function facebook()
    {
        FB.login(function (response) {
            if (response.authResponse) {
                $("#first_div").hide();
                /*FB.api('/me', function(me) {
                 var id=me.id;
                 $.ajax({
                 type: "POST",
                 url: '<?php //echo base_url()."home/fun_friends_fb_id";  ?>//',
                 /*data: { user_id: id, user_name: me.name, user_email: me.email, user_fname: me.first_name, user_lname: me.last_name },
                 success: function(data)
                 {
                 
                 }
                 });
                 
                 });*/
                FB.api('/me/friends', function (response) {
                    if (response.data) {
                        var count = 0;
                        $.each(response.data, function (index, friend) {
                            count++;
                        });
                        var i = 0;
                        var id = new Array();
                        var name = new Array();
                        $.each(response.data, function (index, friend) {
                            id[i] = friend.id;
                            name[i] = friend.name;

                            i++;
                        });

                        $.ajax({
                            type: "POST",
                            url: '<?php echo base_url() . "home/fun_friends_fb_id"; ?>',
                            data: {fb_id: id, fb_name: name, friends_count: count, match_count: i},
                            success: function (data)
                            {
                                if (data)
                                {
                                    //alert(data);
                                    $('#div').html(data);
                                }
                            }
                        });
                        //  });
                    } else {
                        alert("Error!");
                    }
                });
            }
        });
    }
</script>
<style>
    body {
        background: url("https://a2.muscache.com/airbnb/static/wishlist/blue_cloud_bg-b47c42cb6f74f3c876451a1eeb58f7ee.jpg") repeat-x scroll center 82px #F3F9FD;
    }
</style>
<?php
    echo '<div id="first_div" class="contain" style="display:block">';
    echo '<div class="landette">';
    echo '<h2>' . translate("See what your friends are saving to their Wish Lists on") . ' ' . $this->dx_auth->get_site_title() . '!</h2><br><br>';
    echo "<div class='fb-conntect'>";
    echo '<button class="btn fb-blue facebook_me" id="facebook" class="facebook" onclick="facebook()">
	<span class="icon-container">
		<i class="icon icon-facebook"></i>
	</span>
<span class="fb-img">Connect with facebook</span></button></div></div>';
    echo '<div class="landette-bottom"></div>';
    echo '<div class="friends-feed"><div class="whole"><img src="' . base_url() . 'images/FB_banner.png" width="995" />';
    echo '<div class="friends-feed_sub_icon_new"><div class="sub_icon_img_new"><img src="' . base_url() . 'images/no_avatar-xlarge.png" width="60" height="62" ><div class="address">Raja<br/><a>New South Wales, Australia</a></div></div></div>';
    echo '<div class="friends-feed_rate_card_first"><img src="' . base_url() . 'images/dollar_bg.png" width="60" height="85" /><p class="doller_symbol">$</p><p class="price">135</p><p class="pernight_doller">Per Night</p></div></div>';

    echo '<div class="landette-sub">';
    echo '<div class="friends-feed_sub"><img src="' . base_url() . 'images/banner4.jpg" width="480" height="300" /></div>';
    echo '<div class="friends-feed_sub_icon"><div class="sub_icon_img"><img src="' . base_url() . 'images/no_avatar-xlarge.png" width="60" height="62" ><div class="address">Murali<br/><a>Victoria,<br> Tasmania</a></div></div></div>';
    echo '<div class="friends-feed_rate_card"><img src="' . base_url() . 'images/dollar_bg.png" width="60" height="85" /><p class="doller_symbol">$</p><p class="price">135</p><p class="pernight_doller">Per Night</p></div>';
    echo '</div>';

    echo '<div class="landette-sub">';
    echo '<div class="friends-feed_sub"><img src="' . base_url() . 'images/banner3.png" width="480" height="300" /></div>';
    echo '<div class="friends-feed_sub_icon"><div class="sub_icon_img"><img src="' . base_url() . 'images/no_avatar-xlarge.png" width="60" height="62" ><div class="address">Arunkumar.G<br/><a>Tamilnadu, India</a></div></div></div>';
    echo '<div class="friends-feed_rate_card"><img src="' . base_url() . 'images/dollar_bg.png" width="60" height="85" /><p class="doller_symbol">$</p><p class="price">135</p><p class="pernight_doller">Per Night</p>';
    echo '</div>';

    echo '</div>';
    echo '</div>';

    echo '<div class="friends-feed1"><div class="whole"><img src="' . base_url() . 'images/friends-feed-map.jpg" width="1010" />';
    echo '</div>';

    echo '</div>';
    echo '</div>';
    echo '<div class="div" id="div"></div>';
?>
