<html>
    <head>
        <link href="<?php echo css_url() . '/common.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo css_url() . '/demo.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
        <link href="<?php echo css_url() . '/popup.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
    </head>
    <script src="<?php echo js_url(); ?>/facebook_invite.js"></script>

    <script type="text/javascript">
        FB.init({
            appId: '<?php echo $fb_app_id; ?>',
            frictionlessRequests: true
        });
        function send_invitation() {
            FB.ui(
                    {
                        method: 'send',
                        //  to : fb_frnd_id,
                        link: '<?php echo base_url() . "users/signup?airef=" . $referral_code; ?>',
                    }, requestCallback);
            function requestCallback(response) {

            }
        }
        function fb_share()
        {
            FB.ui(
                    {
                        method: 'feed',
                        name: 'Take a trip!',
                        link: '<?php echo base_url() . "users/signup?airef=" . $referral_code; ?>',
                        picture: '<?php echo base_url() . "logo/logo.png"; ?>',
                        caption: "We'll help you pay for it",
                        description: 'Discover and book unique spaces around the world with <?php echo $this->dx_auth->get_site_title(); ?>. Join now and save $25 off your first trip of $75 or more!'
                    },
            function (response) {

            }
            );
        }

    </script>

    <script type="text/javascript">
        $(document).ready(function () {
            $("#overlay_form").validate({
                debug: false,
                rules: {
                    emails: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    emails:
                            {
                                require: "You must enter the mail-id",
                                email: "Please enter correct mail-id"

                            }
                },
            });
        });
    </script>
    <style>
        label.error { width: 250px; display: inline; color: red;}
    </style>

    <script>
        $(document).ready(function ()
        {

            $('#pop1').click(function ()
            {
                $('.action_bar_container').hide();
                $('.email_wrapper').show();
            })
            var counter = 3;
            $('.email_friend_add').click(function ()
            {
                counter++;
                $('#textbox_groups').append('<input type="text" class="email_gray_text" id="email_id' + counter + '" name="email_id' + counter + '" placeholder="friend@example.com" />');
                $("input[id*=email_id" + counter + "]").each(function () {
                    $(this).rules("add", {
                        email: true,
                        messages: {
                            email: "Please enter a valid Email address"
                        }
                    });
                });
            })
            $('#back').click(function ()
            {
                $('.email_wrapper').hide();
                $('.action_bar_container').show();
            })

            $('#email_form').submit(function ()
            {
                var k = 0;
                $('[name^="email_id"]').each(function () {

                    if ($.trim($(this).val()).length == 0)
                    {
                        k++;
                    }
                })

                if (counter == k)
                {
                    $.validator.addClassRules("first_box", {
                        required: true
                    });
                }
                else
                {
                    $.validator.addClassRules("first_box", {
                        required: false
                    });
                }
            })
        })
        $(document).ready(function () {

            $("#email_form").validate();

            for (var i = 1; i <= 3; i++)
            {
                $("input[id*=email_id" + i + "]").each(function () {
                    $(this).rules("add", {
                        email: true,
                        messages: {
                            email: "Please enter a valid Email address"
                        }
                    });
                });
            }
        });

    </script>
    <body>

        <div class="container_referral referral">
            <h1 class="invite_friend"><?php echo translate('Invite Your Friends'); ?></h1>

            <div class="shelf_wrapper">
                <div class="offer_wrapper">
                    <div class="user_left">
                        <div class="offer_invite">
                            <div class="invite_user_left"></div>
                            <div class="invite_user_label">
                                <span class="invite_label"><?php echo translate('Friend Uses'); ?> <?php echo $this->dx_auth->get_site_title(); ?></span>
                            </div>
                            <div class="invite_user_bottom"></div>
                        </div>
                    </div>

                    <div class="equals"></div>

                    <div class="coupon_wrapper">
                        <div class="coupon">
                            <div class="coupon_side_left"></div>
                            <div class="coupon_side_middle">
                                <span class="coupon_amount"><?php echo get_currency_symbol1() . get_currency_value(100); ?></span>
                                <div class="travel_credit_tag"><?php echo translate('Travel Credit'); ?></div>
                            </div>
                            <div class="coupon_side_right"></div>
                        </div>
                    </div>

                </div>
                <div class="big_self"></div>
            </div>
            <p class="referral_offer"><?php echo translate("You'll get"); ?> <?php echo get_currency_symbol1() . get_currency_value(25); ?> <?php echo translate('when they take a trip &'); ?> <span><?php echo get_currency_symbol1() . get_currency_value(75); ?> <?php echo translate('when they rent out their place.'); ?></span></p>
            <div class="action_bar_container">
                <div class="action_bar">
                    <span class="fl_left"><?php echo translate('Get Started'); ?></span>
                    <span class="sm_arrow fl_left"></span>
                    <a class="invite_fb_blue" onclick='send_invitation()'></a>
                    <span class="on_connect fl_left"><?php echo translate('or'); ?></span>

                    <span class="invite_btn-green" id="pop1"></span>
                </div>
            </div>
            <div class="email_wrapper" style="display: none">
                <div class="share_email">
                    <div class="email_top"></div>
                    <div class="email_middle">
                        <form class="referrals_email_form" id="email_form" action="<?php echo base_url() . 'home/fun_invite_mail'; ?>" method="post" autocomplete="on">
                            <div class="email_middle_form_inner">
                                <span class="email_explanation"><?php echo translate('To:'); ?></span><br/>
                                <div id="textbox_groups">
                                    <input type="text" class="email_gray_text first_box" id="email_id1" name="email_id1" value="friend@example.com" />                       
                                    <?php echo form_error('email_id1'); ?>
                                    <input type="text" class="email_gray_text" id="email_id2" name="email_id2" value="friend@example.com" />
                                    <input type="text" class="email_gray_text" id="email_id3" name="email_id3" value="friend@example.com" />
                                </div>
                                <a class="email_friend_add"><?php echo translate('Add Another Friend'); ?></a>
                            </div>

                            <div class="email_middle_form_inner_right">
                                <span class="email_explanation"><?php echo translate('Message:'); ?></span>

                                <textarea cols="49" rows="10" name="msg" class="email_textbox"><?php echo translate("I've been renting out my place on"); ?>' <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate('to meet interesting people and earn extra cash. I highly recommend you join me by listing your space. Cheers!'); ?> <?php echo $username; ?>
                                </textarea><br/>
                                <input type="submit" style="float:right;margin-top:7px;" class="email_submit_large submit" value="send invites" />
                            </div>
                        </form>
                    </div>
                    <div class="email_bottom"></div>
                    <div class="shadow_wrapper"></div>
                </div>
                <div class="back_button">
                    <a id="back">&larr; <?php echo translate('Back'); ?></a>
                </div>
            </div>

        </div>
        <div class="container_referral_ referral_shares">
            <div class="rbs">
                <label class="share_link_text"><?php echo translate('Share Link'); ?>:</label><input class="share_link_box" type="text" value="<?php echo base_url() . 'users/signup?airef=' . $referral_code; ?>" readonly/>
                <i class="fbshare" onClick="fb_share();"></i>


                <a class="twshare" onClick="window.open(this.href, 'child', 'height=300,width=500');
                        return false" href="http://twitter.com/intent/tweet?text=I've been using <?php echo $this->dx_auth->get_site_title(); ?> and love it! Save $25 on your next trip if you sign up now: <?php echo base_url() . 'users/signup?airef=' . $referral_code;
                   ;
                                    ?>&via=<?php echo $this->dx_auth->get_site_title(); ?>" target="_blank">
                </a>
            </div>

        </div>
        <!--  <div class="container_link">
              <a href="#">Terms & Conditions</a>
        </div>-->

        <div id="lightbox-shadow" style="display: block;"></div>
    <body>
        <br />
        <form id="overlay_form" name="overlay_form" class="form" style="display:none" method="post" action='<?php echo base_url() . "home/fun_invite_mail"; ?>'>
            <h4> <?php echo translate("Enter your friend's email id :"); ?></h4>
            <img src='<?php echo base_url() . "images/close_pop.png" ?>' alt='quit' class='close' id='close' />
            <div align="left">
                <table><tr><td valign="top">
                            <label class='emails'><?php echo translate("Email"); ?><span class="star">*</span></label></td>
                        <td><textarea name="emails" id="emails" rows="5" columns="20"></textarea></td></tr>
                    <span id="email" style="color:red"></span><br /><br />
                    <tr><td valign="top"><label class="message"><?php echo translate("Add your message"); ?> </label></td>
                        <td><textarea name="message" rows="5" columns="20"></textarea></td></tr></table>
                <span id="message" style="color:red"></span><br /><br />
                <center><input type="submit" value="Send" class="btn blue gotomsg" name="submit" id="submit"/></center>
            </div>
        </form>
        <div id="overlay_form_fb" class="overlay_form_fb" style="display:none">
        </div> 

    </body>
</html>
