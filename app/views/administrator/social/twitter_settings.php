<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery.validate/1.6/jquery.validate.min.js"></script>
<script type="text/javascript">
    function startCallback() {
        //$("#message").html('<img src="<?php echo base_url() . 'images/loading.gif' ?>">');
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback(response) {
        if (response.length > 50)
        {
            window.location.href = "<?php echo base_url() . 'administrator'; ?>";
        }
        else
        {
            $('#message').show();
            $("#message").html(response);
            $("#message").delay(1800).fadeOut('slow');
        }
    }
    $(document).ready(function ()
    {
        $("#form").validate({
            rules: {
                twitter_api_id: {required: true},
                twitter_api_secret: {required: true}
            },
            messages: {
                twitter_api_id: {required: "Please enter the API ID."},
                twitter_api_secret: {required: "Please enter the Secret Key."}
            }

        });
    });
</script>


<div id="Fb_Settings">
    <div class="clsTitle">
        <h3><?php echo translate_admin('Twitter Connect Settings'); ?></h3>
    </div>
    <form action="<?php echo admin_url('social/twitter_settings'); ?>" method="post" id="form" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">
        <table class="table" cellpadding="2" cellspacing="0">
            <tr>
                <td class="clsName"><?php echo translate_admin('Twitter Application ID'); ?><span class="clsRed">*</span></td>
                <td><input type="text" size="30" name="twitter_api_id" value="<?php if (isset($twitter_api_id)) echo $twitter_api_id; ?>"></td>
            </tr>
            <tr>
                <td class="clsName"><?php echo translate_admin('Twitter Application Secret'); ?><span class="clsRed">*</span></td>
                <td><input type="text" size="55" name="twitter_api_secret" value="<?php if (isset($twitter_api_secret)) echo $twitter_api_secret; ?>"></td>
            </tr>
            <tr>
                <td></td>
                <td><div class="clearfix"> <span style="float:left; margin:0 10px 0 0;">
                            <input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" />
                        </span> <span style="float:left;">
                            <div id="message"></div>
                        </span> </div></td>
            </tr>
        </table>
    </form>
</div>