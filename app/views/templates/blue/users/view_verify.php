<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->

<div class="clsShow_Notification" id='facebook_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Facebook Account Not Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='facebook_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Facebook Account Successfully Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='facebook_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Facebook Account Successfully Disconnected"); ?></span></p></div>

<div class="clsShow_Notification" id='google_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Google Account Not Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='google_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Google Account Successfully Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='google_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Google Account Successfully Disconnected"); ?></span></p></div>

<div class="clsShow_Notification" id='email_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Email Address Not Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='email_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Email Address Successfully Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='email_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Email Address Successfully Disconnected"); ?></span></p></div>

<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER . '/includes/profile_header'); ?>

<div id="verify_container" class="view_verify_Common">
    <div class="Box_verify" id="View_verify">
        <!--    <div class="Box_Head msgbg">
                <h2><?php echo translate("Profile Verification"); ?></h2>
            </div>-->
        <div class="box_list">
            <div class="verify">
                <div class="verify_id">
                    <p class="verify"><b style="margin-left:20px;"><?php echo translate('Verify Your ID'); ?></b></p>
                    <p class="verify_content"><?php echo translate("Getting your Verified ID is the easiest way to help build trust in the") . " " . $this->dx_auth->get_site_title() . " " . translate("community. We'll verify you by matching information from an online account to an official ID."); ?></p>
                    <p class="verify_content">Or, you can choose to only add the verifications you want below.</p>
                </div>
                <div class="verify_me">
                    <a href="<?php echo base_url() . 'home/verify'; ?>"><?php echo translate('verify me'); ?></a>
                </div>
            </div>

            <div class="current_verify">
                <h2> <i class="icon icon-ok-sign current-verifications-icon"></i><img src='<?php echo base_url() . "images/nott_success.png" ?>' alt='close' width="20px" /> <?php echo translate('Your Current Verifications'); ?></h2>
            </div>

            <div class="facebook" id="facebook_verify_disconnect" style="display: none">
                <div class="verify_id">
                    <p class="verify"><b><?php echo translate('Facebook'); ?></b></p>
                    <p class="verify_content"><?php echo translate('Sign in with Facebook and discover your trusted connections to hosts and guests all over the world.'); ?></p>
                </div>
                <div class="verify_me">
                    <button class="btn gray" id="facebook" class="facebook" onClick="facebook_disconnect()"><?php echo translate('Disconnect'); ?></button>
                </div>
            </div>
            <div class="email" id="email_verify_disconnect" style="display: none">
                <div class="verify_id">
                    <p class="verify"><b><?php echo translate('Email'); ?></b></p>
                    <p class="verify_content"><?php echo translate('You have confirmed your email:'); ?> <?php echo $users->email; ?>. <?php echo translate('A confirmed email is important to allow us to securely communicate with you.'); ?></p>
                </div>
                <div class="verify_me">
                    <button class="btn gray" onclick="email_disconnect()"><?php echo translate('Disconnect'); ?></button>
                </div>
            </div>

            <div class="email" id="no_verify" style="display: none">
                <div class="verify_id">
                    <p class="verify_content"><?php echo translate('You have no verifications yet. You can add more below.'); ?></p>
                </div>
            </div>
            <div class="google" id="google_verify_disconnect" style="display: none">
                <div class="verify_id">
                    <p class="verify"><b><?php echo translate('Google'); ?></b></p>
                    <p class="verify_content"><?php echo translate('Connect your') . ' ' . $this->dx_auth->get_site_title() . ' ' . translate('account to your Google account for simplicity and ease.'); ?></p>
                </div>
                <div class="verify_me" style="float:left;">
                    <button class="btn gray" onclick="google_disconnect()"><?php echo translate('Disconnect'); ?></button>
                </div>
            </div>
            <div class="current_verify">
                <h2> <img src='<?php echo base_url() . "images/st-add-more.png" ?>' alt='close' width="18px" /> <?php echo translate('Add More Verifications'); ?></h2>
            </div>
            <?php
                require_once APPPATH . 'libraries/openid.php';
                $openid = new LightOpenID(base_url());

                $openid->identity = 'https://www.google.com/accounts/o8/id';
                $openid->required = array(
                    'namePerson/first',
                    'namePerson/last',
                    'contact/email',
                );
                $openid->returnUrl = base_url() . 'users/google_verify';
            ?>


            <div class="google" id="google_verify" style="display: none">
                <div class="verify_id">
                    <p class="verify"> <img src='<?php echo base_url() . "images/follow-us-google-plus.png" ?>' alt='close' /><b><?php echo translate('Google'); ?></b></p>
                    <p class="verify_content"><?php echo translate('Connect your') . ' ' . $this->dx_auth->get_site_title() . ' ' . translate('account to your Google account for simplicity and ease.'); ?></p>
                </div>
                <div class="verify_me" style="float:left;">

                    <a href="<?php echo $openid->authUrl(); ?>"><?php echo translate('Connect'); ?></a>

                </div>
            </div>

            <script src="<?php echo base_url() . 'js/facebook_invite.js'; ?>"></script> 

            <script>
                        FB.init({
                            appId: '<?php echo $fb_app_id; ?>',
                            frictionlessRequests: true
                        });
                        function facebook()
                        {
                            FB.login(function (response) {
                                if (response.authResponse) {
                                    FB.api("/me", function (me) {
                                        $.ajax({
                                            type: "POST",
                                            url: '<?php echo base_url() . "users/facebook_verify"; ?>',
                                            data: {fb_id: me.id, email: me.email},
                                            success: function (data)
                                            {
                                                if (data == 'verified')
                                                {
                                                    $('#no_verify').hide();
                                                    $("#facebook_verify").hide();
                                                    $("#facebook_verify_disconnect").show();
                                                    $("#facebook_verify_success_msg").fadeIn(2000);
                                                    $("#facebook_verify_success_msg").fadeOut();
                                                }
                                                else
                                                {
                                                    $("#facebook_verify_error_msg").fadeIn(2000);
                                                    $("#facebook_verify_error_msg").fadeOut();
                                                }
                                            }
                                        });

                                    });
                                }
                            });
                        }
            </script>

            <div class="email" id="email_verify" style="display: none">
                <div class="verify_id">
                    <p class="verify"><img src='<?php echo base_url() . "images/follow-us-email-plus.png" ?>' alt='close' /><b><?php echo translate('Email'); ?></b></p>
                    <p class="verify_content"><?php echo translate('Please verify your email address by clicking the link in the message we just sent to: username'); ?></p>
                </div>
                <div class="verify_me">

                    <a href="<?php echo base_url() . 'users/email_verify'; ?>"><?php echo translate('Connect'); ?></a>

                </div>
            </div>

            <div class="facebook" id="facebook_verify" style="display: none">
                <div class="verify_id">
                    <p class="verify"><img src='<?php echo base_url() . "images/follow-us-facebook-plus.png" ?>' alt='close' /><b style="margin-left:5px;"><?php echo translate('Facebook'); ?></b></p>
                    <p class="verify_content"><?php echo translate('Sign in with Facebook and discover your trusted connections to hosts and guests all over the world.'); ?></p>
                </div>
                <div class="verify_me">
                    <!--<a id="facebook_disconnect">connect</a>-->
                    <a class="btn fb-blue" id="facebook" class="facebook" onClick="facebook()"><?php echo translate('Connect'); ?></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div> 
</div>


</div>
<!-- JS Start For Facebook Verification -->
<script>
<?php
    if ($users->facebook_verify != 'yes')
    {
        ?>

            $("#facebook_verify").show();
    <?php
    }
    else
    {
        ?>
            $("#facebook_verify_disconnect").show();
    <?php }
?>
    function facebook_disconnect()
    {
        $("#facebook_verify").show();
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "users/facebook_verify_disconnect"; ?>',
            success: function (data)
            {
                $("#facebook_verify_disconnect").hide();

                $.getJSON('<?php echo base_url() . "users/facebook_verify_disconnect"; ?>', function (data) {
                    if (data.google != 'yes' && data.email != 'yes')
                    {
                        $('#no_verify').show();
                    }
                });
                $('#facebook_verify_disconnect_msg').fadeIn(2000);
                $('#facebook_verify_disconnect_msg').fadeOut();
                $("#facebook_verify").show();
            }
        });

    }
</script>
<!-- JS End For Facebook Verification -->

<!-- JS Start For Google Verification -->
<script>
<?php if ($users->google_verify != 'yes')
    {
        ?>

            $("#google_verify_disconnect").hide();
            $("#google_verify").show();
    <?php
    }
    else
    {
        ?>$('#no_verify').hide();
                    $("#google_verify").hide();
                    $("#google_verify_disconnect").show();

    <?php } ?>
            function google_disconnect()
            {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url() . "users/google_verify_disconnect"; ?>',
                    success: function (data)
                    {
                        $("#google_verify_disconnect").hide();

                        $.getJSON('<?php echo base_url() . "users/google_verify_disconnect"; ?>', function (data) {
                            if (data.fb != 'yes' && data.email != 'yes')
                            {
                                $('#no_verify').show();

                            }
                        });
                        $('#google_verify_disconnect_msg').fadeIn(2000);
                        $('#google_verify_disconnect_msg').fadeOut();
                        $("#google_verify").show();
                    }
                });
            }
</script>

<!-- JS Start For Email Verification -->
<script>
<?php
    if ($users->email_verify != 'yes')
    {
        ?>

            $("#email_verify").show();
            $("#email_verify_disconnect").hide();
    <?php
    }
    else
    {
        ?>
            $("#email_verify").hide();
            $("#email_verify_disconnect").show();
    <?php }
?>
    function email_disconnect()
    {
        $.ajax({
            type: "POST",
            url: '<?php echo base_url() . "users/email_verify_disconnect"; ?>',
            success: function (data)
            {

                $("#email_verify_disconnect").hide();

                $.getJSON('<?php echo base_url() . "users/email_verify_disconnect"; ?>', function (data) {
                    if (data.fb != 'yes' && data.google != 'yes')
                    {
                        $('#no_verify').show();

                    }
                });
                $('#email_verify_disconnect_msg').fadeIn(2000);
                $('#email_verify_disconnect_msg').fadeOut();
                $("#email_verify").show();
            }
        });
    }
</script>
<!-- JS End For Email Verification -->

<script>
<?php
    if ($users->email_verify != 'yes' && $users->facebook_verify != 'yes' && $users->google_verify != 'yes')
    {
        ?>
            $('#no_verify').show();
    <?php }
?>
</script>
