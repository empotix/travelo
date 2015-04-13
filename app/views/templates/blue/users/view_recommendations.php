<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>

<?php $this->load->view(THEME_FOLDER . '/includes/profile_header'); ?>	
<div id="dashboard_container">   
    <div class="Box" id="View_GetRecom_1">
        <div class="Box_Head msgbg"><h2><?php echo translate("Share This URL"); ?></h2></div>  
        <div class="Box_Content">
            <?php echo form_open("users/recommendation", 'id="form"') ?>
            <p><input type="text" id="share_url" onClick="jQuery('#share_url').focus();
                    jQuery('#share_url').select();" name="share_url" value="<?php echo base_url() . 'users/vouch/' . $this->dx_auth->get_user_id(); ?>" size="70"></p>
            <p><?php echo translate("Share this Personal Recommendation URL with your friends so they can leave you recommendations."); ?>
                <a href="<?php echo site_url('pages/view') . '/recommendation_help'; ?>"><?php echo translate("Help!"); ?></a></p>
        </div>
    </div>
    <div class="Box" id="View_GetRecom_2">
        <div class="Box_Head msgbg"><h2><?php echo translate("Email your friends"); ?></h2></div>
        <div class="Box_Content">
            <p><?php echo translate("Enter email addresses, separated by commas. We will send an individual email that includes your"); ?> <span style="font-weight:bold;"><?php echo translate("Personal Recommendation URL"); ?></span> <?php echo translate("to each person."); ?></p>
            <div id="message" style="display:none"></div>
            <p><textarea name="email_to_friend" id="email_valid" value="" cols="40" ></textarea> <?php echo form_error('email_to_friend'); ?> </p>

            <p>
                <button type="submit" class="btn blue gotomsg" name="commit"><span><span><?php echo translate("Invite these people"); ?></span></span></button>
            </p>
        </div> 
    </div>     
</div>
<script>
    $('.button1').click(function () {
        var emailAddress = $('#email_valid').val();
        var mail = emailAddress.split(',');
        for (i = 0; i < mail.length; i++) {
            var emailRegex = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
            var valid = emailRegex.test(mail[i]);
            if (!valid) {
                alert("Please Enter the valid e-mail address");
                return false;
            } else
                $("#message").show();
            $("#message").html('<p style="color:#009933"><strong><em> Email Sent successfully </em></strong></p>');
            $("#message").delay(2000).fadeOut('slow');
            return true;
        }

    });
</script>
