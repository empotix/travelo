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

<div id="Lang_Front">

    <div class="clsTitle">
        <h3><?php echo translate_admin('Front-end Language Settings'); ?></h3>
    </div>

    <form action="<?php echo admin_url('settings/lang_front'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">	

        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName"><?php echo translate_admin('Select Language Translator'); ?><span class="clsRed">*</span></td>
                <td>
                    <select id="language_translator" name="language_translator" onChange="javascript:showhide(this.value);">
                        <option value="1"> <?php echo translate_admin('Core Langauge Translator'); ?> </option>
                        <option value="2"> <?php echo translate_admin('Google Langauge Translator'); ?> </option>
                    </select>
                </td>
            </tr>		

            <?php
                if ($language_translator == 1)
                {
                    $showC = 'table-row';
                    $showG = 'none';
                }
                else
                {
                    $showC = 'none';
                    $showG = 'table-row';
                }
            ?>

            <tr id="core" style="display:<?php echo $showC; ?>">
                <td class="clsName"><?php echo translate_admin('Select Default Language'); ?><span class="clsRed">*</span></td>
                <td>
                    <?php $default_lang = $this->db->where('code', 'FRONTEND_LANGUAGE')->get('settings')->row()->string_value; ?>
                    <select id="core_lang" name="core_lang">
                        <?php foreach ($languages as $language)
                            {
                                ?>
                                <option value="<?php echo $language->code; ?>" <?php if ($language->code == $default_lang) echo 'selected="selected"' ?>> <?php echo $language->name; ?> </option>
    <?php } ?>
                    </select>
                </td>
            </tr>

<!--<tr id="google" style="display:<?php echo $showG; ?>">
                        <td class="clsName"><?php echo translate_admin('Select Default Language'); ?><span class="clsRed">*</span></td>
                        <td> 
                        <select id="google_lang" name="google_lang">
            <?php foreach ($languages as $language)
                {
                    ?>
                                           <option value="<?php echo $language->code; ?>"> <?php echo $language->name; ?> </option>
    <?php } ?>
                        </select>
                        </td>
</tr>		-->

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

<script language="Javascript">
    jQuery("#language_translator").val('<?php echo $language_translator; ?>');
//jQuery("#core_lang").val('<?php echo $core_lang; ?>');
    /*jQuery("#google_lang").val('<?php echo $google_lang; ?>');*/

    function showhide(id)
    {
        if (id == 1)
        {
            document.getElementById("core").style.display = "table-row";
            /*document.getElementById("google").style.display           = "none";*/
        }
        else
        {
            document.getElementById("core").style.display = "none";
            /*document.getElementById("google").style.display           = "table-row";	*/
        }

    }
</script>