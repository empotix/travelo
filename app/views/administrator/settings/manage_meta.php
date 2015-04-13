<script type="text/javascript">
    function startCallback() {
        $("#message").html('<img src="<?php echo base_url() . 'images/loading.gif' ?>">');
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback(response) {
        $('#message').show();
        $("#message").html(response);
        $("#message").delay(1800).fadeOut('slow');
    }
</script>

<div id="Manage_Meta">

    <div class="clsTitle">
        <h3><?php echo translate_admin('Manage Meta'); ?></h3>
    </div>

    <form action="<?php echo admin_url('settings/manage_meta'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">	

        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName"><?php echo translate_admin('Meta Keyword'); ?><span class="clsRed">*</span></td>
                <td>
                    <textarea name="meta_keyword" style="width:400px; height:40px" rows="2" cols="60" class="text_area"><?php if (isset($meta_keyword)) echo $meta_keyword; ?></textarea>
                </td>
            </tr>		

            <tr>
                <td class="clsName"><?php echo translate_admin('Meta Description'); ?><span class="clsRed">*</span></td>
                <td>
                    <textarea name="meta_description" style="width:400px; height:40px" rows="2" cols="60" class="text_area"><?php if (isset($meta_description)) echo $meta_description; ?></textarea>
                </td>
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

        <?php echo form_close(); ?>

</div>
