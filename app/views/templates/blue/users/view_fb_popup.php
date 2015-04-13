<script type="text/javascript">
    $(document).ready(function () {
        $("#signup").validate({
            rules: {
                username: {
                    required: true,
                    remote: "<?php echo site_url('facebook'); ?>/check_username_fb"

                },
                email: {
                    required: true,
                    email: true,
                    remote: "<?php echo site_url('facebook'); ?>/check_email_fb"
                },
            },
            messages: {
                username: {
                    required: "Username required",
                    remote: 'Username already taken'

                },
                email: {
                    required: "Email required",
                    remote: "Email already taken"
                }



            }
        });
    });

</script>

<?php
    $fb_id = $this->session->userdata('fb_id');

    $username = $this->session->userdata('username');
?>


<!-- OUR PopupBox DIV-->
<?php echo form_open("facebook/Facebook_MailId_Popup", array('name' => 'signup', 'id' => 'signup', 'class' => 'container_bg2')); ?>
<div class="signup_h1" id="section_signup">
    <h1>Sign up for Facebook </h1>
    <div class="twitter_input">
        <div class="email">
            <span style="text-align: left;margin: 0px 0px 0px 25px;">Email</span><span style="color:#FF0000">*</span>
            <input type="text" name="email" style="margin: 0px 0px 0px 10px;"/><br />
        </div>
        <div class="username">
            <span>Username</span><span style="color:#FF0000">*</span> <input type="text" name="username" value="<?php echo $username; ?>" style="margin: 0px 0px 0px 10px;"/>
        </div>
        <button type="submit" value="Submit" class="btn blue gotomsg"><span>Sign up</span></button>
    </div>
</div>
<?php echo form_close(); ?>