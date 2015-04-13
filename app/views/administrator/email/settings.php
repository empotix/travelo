<script type="text/javascript">
    function startCallback() {
        $("#message").html('<img src="<?php echo base_url() . 'images/loading.gif' ?>">');
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback(response)
    {
        $('#message').show();
        $("#message").html(response);
        $("#message").delay(1800).fadeOut('slow');
    }
</script>

<div id="Email_Setting">

    <div class="clsTitle">
        <h3><?php echo translate_admin('E-Mail Settings'); ?></h3>
    </div>

    <form action="<?php echo admin_url('email/settings'); ?>" method="post" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">	

        <table class="table" cellpadding="2" cellspacing="0">

            <tr valign="top">
                <td class="clsName"><?php echo translate_admin('Email Mode'); ?></td>
                <td> 
                    <select name="mailer_mode" id="mailer_mode" >
                        <option value="html"> <?php echo translate_admin('HTML Mode'); ?> </option>
                        <option value="text"> <?php echo translate_admin('Plain Text Mode'); ?> </option>
                    </select> 
                </td>
            </tr>


            <tr valign="top">
                <td class="clsName"><?php echo translate_admin('Mailer Type'); ?></td>
                <td> 
                    <select name="mailer_type" id="mailer_type" >
                        <option value="1"> <?php echo translate_admin('PHP Mail Function'); ?> </option>
                        <option value="2"> <?php echo translate_admin("ISP's SMTP server"); ?> </option>
                        <option value="3"> <?php echo translate_admin("Google's SMTP Server"); ?> </option>
                    </select> 
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('SMTP Port'); ?></td>
                <td> <input type="text" size="23" name="smtp_port" value="<?php if (isset($smtp_port)) echo $smtp_port; ?>"> </td>
            </tr>		

            <tr>
                <td class="clsName"><?php echo translate_admin('SMTP Username'); ?></td>
                <td> <input type="text" size="23" name="smtp_user" value="<?php if (isset($smtp_user)) echo $smtp_user; ?>"> </td>
            </tr>	

            <tr>
                <td class="clsName"><?php echo translate_admin('SMTP Password'); ?></td>
                <td> <input type="text" size="23" name="smtp_pass" value="<?php if (isset($smtp_pass)) echo $smtp_pass; ?>"> </td>
            </tr>	

            <tr>
                <td></td>
                <td>
                    <div class="clearfix">
                        <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" /></span>
                        <span style="float:left;"><div id="message"></div></span>
                    </div>
                </td>
            </tr>

        </table>

    </form>
</div>

<script language="Javascript">
    $("#mailer_type").val('<?php echo $mailer_type; ?>');
    $("#mailer_mode").val('<?php echo $mailer_mode; ?>');
</script>