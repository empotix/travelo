<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "textareas",
        theme: "simple"
    });
</script>

<div class="View_AddPage">

    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <div class="clsTitle"><h3><?php echo translate_admin('Add Page'); ?></h3></div>
    <form method="post" action="<?php echo admin_url('page/addPage') ?>">
        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName"><?php echo translate_admin('Page Name'); ?><span class="clsRed">*</span></td>
                <td class="clsMailID"><input type="text" name="page_name" value="<?php echo set_value('page_name'); ?>"><?php echo form_error('page_name'); ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Page Title'); ?><span class="clsRed">*</span></td>
                <td><input type="text" name="page_title" value="<?php echo set_value('page_title'); ?>"> <?php echo form_error('page_title'); ?> </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Page URL'); ?><span class="clsRed">*</span></td>
                <td><input type="text" name="page_url" value="<?php echo set_value('page_url'); ?>"><?php echo form_error('page_url'); ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Is link in footer') . '?'; ?></td>
                <td>
                    <select name="is_footer" id="is_footer" >
                        <option value="0" <?php if (set_value('is_footer') == '0') echo 'selected'; ?>> No </option>
                        <option value="1" <?php if (set_value('is_footer') == '1') echo 'selected'; ?>> Yes </option>
                    </select> 
                </td>
            </tr>
            <?php
                if (set_value('is_under') != '' && set_value('is_footer') == '1')
                {
                    ?>
                    <tr id='is_under_tr'>
                        <?php
                    }
                    else
                    {
                        ?>
                    <tr id='is_under_tr' style="display: none">
    <?php } ?>
                <td class="clsName"><?php echo translate_admin('Is link under the') . '?'; ?></td>
                <td>
                    <select name="is_under" id="is_under" >
                        <option value="discover" <?php if (set_value('is_under') == 'discover') echo 'selected'; ?>> Discover </option>
                        <option value="company" <?php if (set_value('is_under') == 'company') echo 'selected'; ?>> Company </option>
                    </select> 
                </td>
            </tr>

            <tr>
                <td class="clsName"> 
                    <?php echo translate_admin('Page Content'); ?><span class="clsRed">*</span></td><td>
                    <textarea id="elm1" name="page_content" rows="15" cols="80" style="width: 80%"><?php echo set_value('page_content'); ?></textarea>
<?php echo form_error('page_content'); ?>
                </td>
            </tr>

            <tr>
                <td></td>    
                <td>
                    <input type="hidden" name="page_operation" value="add"  />
                    <input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addPage" type="submit">
                </td>
            </tr>

        </table>
    </form>
</div>
<script language="Javascript">
    $(document).ready(function () {
        $('#is_footer').change(function () {
            var footer = $("#is_footer option:selected").val();
            if (footer == '0')
            {
                $('#is_under_tr').hide();
            }
            else
            {
                $('#is_under_tr').show();
            }
        });
    });
</script>
