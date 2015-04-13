
<div class="clsShow_Notification" id='facebook_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Facebook Account Not Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='facebook_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Facebook Account Successfully Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='facebook_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Facebook Account Successfully Disconnected"); ?></span></p></div>

<div class="clsShow_Notification" id='google_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Google Account Not Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='google_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Google Account Successfully Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='google_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Google Account Successfully Disconnected"); ?></span></p></div>

<div class="clsShow_Notification" id='email_verify_error_msg' style="display: none"><p class="error"><span><?php echo translate("Your Email Address Not Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='email_verify_success_msg' style="display: none"><p class="success"><span><?php echo translate("Your Email Address Successfully Verified"); ?></span></p></div>
<div class="clsShow_Notification" id='email_verify_disconnect_msg' style="display: none"><p class="success"><span><?php echo translate("Your Email Address Successfully Disconnected"); ?></span></p></div>

<script>
    function verify()
    {
        $('#full_verify').hide();
        $('#profile').show();
<?php
    if ($users->facebook_verify != 'yes')
    {
        ?>
                $('#facebook_verify').show();
        <?php
    }

    if ($users->google_verify != 'yes')
    {
        ?>
                $('#google_verify').show();
        <?php
    }

    if ($users->email_verify != 'yes')
    {
        ?>
                $("#email_verify").show();
    <?php }
?>
    }
    function back()
    {
        $('#full_verify').show();
        $('#profile').hide();
    }
<?php
    if ($users->email_verify == 'yes' && $users->facebook_verify == 'yes' && $users->google_verify == 'yes')
    {
        ?>
            $('verify_via').hide();
        <?php
    }
?>
</script>
<?php
    if ($this->input->get())
    {
        if ($this->input->get('google') == 'verified' || $this->input->get('google') == 'not_verified' || $this->input->get('email') == 'verify')
        {
            ?>
            <script>
                window.onload = verify;
            </script>
            <?php
        }
    }
?>
</script>
<div id="display" style="margin: 30px 0 0 0;">
    <div class="container_verify" id="container">
        <div class="full_verify" id="full_verify">
            <h1><?php echo translate('Thanks for choosing to verify your ID!'); ?></h1>
            <p class="full_content"><?php echo translate('Verifying your ID is an easy way to help build trust in the') . ' ' . $this->dx_auth->get_site_title() . ' ' . ('community. We believe anonymity erodes trust, so we verify the IDs of our guests and hosts to help ensure the safety of our growing community.'); ?></a></p>
            <p class="full_content"><?php echo translate('By verifying your identification, you give') . ' ' . $this->dx_auth->get_site_title() . ' ' . translate('and our service providers permission to use this information for verification and risk assessment purposes. If you do not consent to our use of your information, please do not verify your identification. The information you provide is governed by our'); ?>
                <?php echo translate('Privacy Policy'); ?></a>.</p>
            <div class="inner_verify">
                <?php
                    $i = 0;
                    if ($users->facebook_verify != 'yes')
                    {
                        $i = $i + 1;
                    }
                    if ($users->google_verify != 'yes')
                    {
                        $i = $i + 1;
                    }
                    if ($users->email_verify != 'yes')
                    {
                        $i = $i + 1;
                    }
                ?>
                <h3 class="thin"><?php echo translate('You have'); ?> <?php echo $i; ?> <?php echo translate('things left to do:'); ?></h3>
                <div class="shadow">
                    <ul class="verification-summary">
                        <?php
                            if ($users->facebook_verify != 'yes')
                            {
                                ?> 
                                <li class="default"><i class="icon icon-ok-sign space1"></i><img src='<?php echo base_url() . "images/nott_success.png" ?>' alt='close' /><?php echo translate('Verify your Facebook address'); ?></li>
                            <?php } ?>
                        <?php
                            if ($users->google_verify != 'yes')
                            {
                                ?> 
                                <li class="default"><i class="icon icon-ok-sign space1"></i><img src='<?php echo base_url() . "images/nott_success.png" ?>' /><?php echo translate('Verify your Google address'); ?></li>
                            <?php } ?>
                        <?php
                            if ($users->email_verify != 'yes')
                            {
                                ?> 
                                <li class="default"><i class="icon icon-ok-sign space1"></i><img src='<?php echo base_url() . "images/nott_success.png" ?>' /><?php echo translate('Verify your email address'); ?></li>
                            <?php } ?>
                    </ul>
                </div>
                <a class="verify_button" href='#verification' onClick="verify()"><?php echo translate('verify me'); ?></a>
            </div>

        </div>

        <div id="profile" style="display:none;">
            <div class="box_verify">
                <div class="box_me">
                    <p class="online_profile"><?php echo translate('Online Profile'); ?></p>
                </div>
                <div class="verify_content_me">
                    <h2 class="profile_heading"><?php echo translate('Verify Online Profile'); ?></h2>
                    <p class="verified_facebook"><?php echo translate("Verifying your online profile lets us match details to your official identification to help ensure that you're a 'real person' online, rather than a robot or a spammer."); ?></p>
                    <p class="veirfied"><?php echo translate('Already verified via:'); ?></p>
                    <div  class="verified_facebook" id="facebook_verify_me" style='display: none'><?php echo translate('Facebook'); ?></div>
                    <div  class="verified_facebook" id="google_verify_me" style='display: none'><?php echo translate('Google'); ?></div>
                    <div  class="verified_facebook" id="email_verify_me" style='display: none'><?php echo translate('Email'); ?></div>
                    <?php
                        if ($users->facebook_verify == 'yes')
                        {
                            echo '<p class="verified_facebook" id="facebook">' . translate("Facebook") . '</p>';
                        }
                        if ($users->google_verify == 'yes')
                        {
                            echo '<p class="verified_facebook" id="google">' . translate("Google") . '</p>';
                        }
                        if ($users->email_verify == 'yes')
                        {
                            echo '<p class="verified_facebook" id="email">' . translate("Email") . '</p>';
                        }
                        if ($users->email_verify != 'yes' && $users->facebook_verify != 'yes' && $users->google_verify != 'yes')
                        {
                            echo "<p class='dont_verified_facebook' id='no_verification'>" . translate("You Don't Have Any Verifications.") . "</p>";
                        }
                        if ($users->email_verify == 'yes' && $users->facebook_verify == 'yes' && $users->google_verify == 'yes')
                        {
                            echo "<p class='choose' id='all_verification'>" . translate("You Did All Verifications.") . "</p>";
                        }
                        else
                        {
                            echo '<p class="choose" id="verify_via">' . translate("Choose the verify via:") . '</p>';
                        }
                    ?>
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
                    $openid->returnUrl = base_url() . 'users/google_verify_detail';
                ?>
                <div id="google_verify" style="display: none">
                    <div class="verify_me" style="float:left;">
                        <a href="<?php echo $openid->authUrl(); ?>"><?php echo translate('Google'); ?></a>
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
                                                $("#facebook_verify_me").show();
                                                $('#no_verification').hide();
                                                $('#fb_veri').show();
                                                $('.title_no_one').hide();
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
                    <div class="verify_me">
                        <a href="<?php echo base_url() . 'users/email_verify?email=verify'; ?>"><?php echo translate('Email'); ?></a>
                    </div>
                </div>
                <script> $('#facebook_verify').show();</script>
                <div class="facebook" id="facebook_verify" style="display: none">
                    <div class="verify_me">
                        <a id="facebook" class="facebook_me" onClick="facebook()">
                            Facebook
                        </a>
                    </div>
                </div><br/><br/><br/><br/>
                <div class="back">
                    <a href="#" onClick="back()" class="verify_back"><?php echo translate('Back'); ?></a>
                </div>
            </div>
        </div>

        <div class="full_verify_right">
            <div class="span_verify" data-cid="view29" data-view="progress_widget">
                <div class="verification-progress-container">
                    <p class="progress-header"><?php echo translate('Verification Progress:'); ?></p>
                    <div class="progress-bar-container progress-bar-well space1">
                        <?php
                            if ($i == 3)
                            {
                                $width = 0;
                            }
                            if ($i == 2)
                            {
                                $width = 35;
                            }
                            if ($i == 1)
                            {
                                $width = 70;
                            }
                            if ($i == 0)
                            {
                                $width = 100;
                            }
                        ?>
                        <div class="progress-bar progress-bar-green" style="width:<?php echo $width; ?>%"></div>
                    </div>
                </div>
            </div>

            <div class="verification_picture">
                <img src="<?php
                    if ($this->session->userdata('image_url') != '')
                    {
                        $image_url = $this->session->userdata('image_url');

                        echo $image_url;
                        $split = explode('.', $image_url);
                        $url = $split[0] . '.' . $split[1] . '.' . $split[2];
                        $email = $this->db->where('id', $this->dx_auth->get_user_id())->from('users')->get()->row()->email;
                        $data_tw['src'] = $url;
                        $data_tw['ext'] = '.' . $split[3];
                        $data_tw['email'] = $email;
                        $this->db->insert('profile_picture', $data_tw);
                    }
                    else
                    {

                        echo $this->Gallery->profilepic($this->dx_auth->get_user_id(), 2);
                    }
                ?>" width="232" height="217" />
                <div class="picture_description">
                    <p class="picture_heading"><?php echo $users->username; ?></p>
                    <p class="picture_city"><?php
                            if (isset($profiles->live))
                            {
                                echo $profiles->live;
                            }
                            else
                            {
                                echo translate('Address') . ' ' . translate("Doesn't Specified");
                            }
                        ?></p>
                    <p class="member"><?php echo translate('Member Since') . ' ' . date('F', $users->created) . ' ' . date('y', $users->created); ?></p>
                </div>
            </div>
            <div class="verification_details" id="verification_details_bar">
                <p class="verification"><?php echo translate('Verifications'); ?></p>
                <p class="title" id='fb_veri' style="display: none"><br>Facebook</p>
                <p class="title"><?php
                        if ($users->facebook_verify == 'yes')
                        {
                            echo '<br>Facebook</p>';
                            $url = 'https://graph.facebook.com/fql?q=SELECT%20friend_count%20FROM%20user%20WHERE%20uid%20=' . $users->fb_id;
                            $json = file_get_contents($url);
                            $count = json_decode($json, TRUE);
                            foreach ($count['data'] as $row)
                            {
                                echo '<p class="list">' . $row["friend_count"] . ' ' . 'Friends</p>';
                            }
                        }
                        if ($users->google_verify == 'yes')
                        {
                            echo ' <p class="title">' . translate('Google') . '</p><p class="list">' . translate('Verified') . '</p>';
                        }
                        if ($users->email_verify == 'yes')
                        {
                            echo ' <p class="title">' . translate('Email') . '</p><p class="list">' . translate('Verified') . '</p>';
                        }
                        if ($users->email_verify != 'yes' && $users->google_verify != 'yes' && $users->facebook_verify != 'yes')
                        {
                            echo '<p class="title_no_one">' . translate("No One Verified") . '</p>';
                        }
                    ?> </p>

            </div>

        </div>
