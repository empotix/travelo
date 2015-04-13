<script type="text/javascript">
    function startCallback() {

        if ($('#new_password').val() == $('#confirm_password').val())
        {
            if ($('#confirm_password').val() != "")
            {
                $('#error').hide();
                $("#message").html('<img src="<?php echo base_url() . 'images/loading.gif' ?>">');
                // make something useful before submit (onStart)
                return true;
            }
            else
            {
                $('#error1').show();
                return false;
            }
        }
        else
        {
            $('#error').show();
            return false;
        }
    }

    function completeCallback(response) {
        $('#message').show();
        $("#message").html(response);
        $("#message").delay(1800).fadeOut('slow');
    }
</script>

<div id="Change_Password">

    <div class="clsTitle">
        <h3><?php echo translate_admin('Change Password'); ?></h3>
    </div>

    <form action="<?php echo admin_url('settings/change_password'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">	

        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName"><?php echo translate_admin('Old Password'); ?><span class="clsRed">*</span></td>
                <td> <input id="old_password" type="text" size="55" name="old_password" value=""> <?php echo form_error('old_password'); ?> </td>
            </tr>			

            <tr>
                <td class="clsName"><?php echo translate_admin('New Password'); ?><span class="clsRed">*</span></td>
                <td> 
                    <input id="new_password" type="text" name="new_password" size="55" value=""> 
                    <p id="error" style="display:none; color:#CC3300"> <?php echo translate_admin('Password and Confirm Password didnt match.'); ?> </p>
                    <p id="error1" style="display:none; color:#CC3300"> <?php echo 'Password or Confirm Password should not be empty.'; ?> </p>
                </td>
            </tr>		

            <tr>
                <td class="clsName"><?php echo translate_admin('Confirm Password'); ?><span class="clsRed">*</span></td>
                <td> <input id="confirm_password" type="text" size="55" name="confirm_password" value=""></td>
            </tr>			

            <tr>
                <td></td>
                <td>
                    <div class="clearfix">
                        <span style="float:left; margin:0 10px 0 0;">
                            <input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
                        </span>
                        <span style="float:left;"><div id="message"></div></span>
                    </div>
                </td>
            </tr>

        </table>

        <?php echo form_close(); ?>

</div>
