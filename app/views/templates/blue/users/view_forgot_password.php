<div id="forgot_password_container" class="Box">
    <div class="Box_Head1">
        <h2> <?php echo translate("Reset Password"); ?> </h2>
    </div>
    <div class="Box_Content">
        <p> <?php echo translate("Enter your e-mail address to have the password associated with that account reset. A new password will be e-mailed to the address."); ?> </p>
        <form id="Forgot" name="Forgot" action="<?php echo site_url('users/forgot_password'); ?>" method="post">

            <p><input id="password" name="email" type="text" value="" class="required" /></p>
            <span style="color:#FF0000"><p id="message"></p></span>
            <p>
                <button type="submit" class="btn blue gotomsg" name="commit"><span><span><?php echo translate("Reset Password"); ?></span></span></button>
            </p>
        </form>
    </div>
</div>
<style>
    .Frm_Error_Msg{
        color:#FF0000;}
</style>
<script type="text/javascript">
    $(document).ready(function () {
        $("#Forgot").validate({
            errorElement: "p",
            errorClass: "Frm_Error_Msg",
            focusInvalid: false,
            submitHandler: function (form)
            {
                $.post("<?php echo site_url('users/forgot_password'); ?>", $("#Forgot").serialize(),
                        function (data)
                        {
                            $("#message").show();
                            $("#message").html(data);
                            $("#message").delay(3000).fadeOut('slow');

                        }
                );
            }
        });
    })

</script>