<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "textareas",
        theme: "simple"
    });
</script>

<div class="View_AddAdmin_key">

    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <div class="clsTitle"><h3><?php echo translate_admin('Add Admin_key'); ?></h3></div>
    <form method="post" action="<?php echo admin_url('admin_key/addAdmin_key') ?>">
        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName"><?php echo translate_admin('Page_key'); ?><span class="clsRed">*</span></td>
                <td class="clsMailID"><input type="text" name="Admin_key" value="<?php echo set_value('Admin_key'); ?>"><?php echo form_error('Admin_key'); ?></td>
            </tr>  		
            <tr>
                <td class="clsName"><?php echo translate_admin('Status') . '?'; ?></td>
                <td>
                    <select name="is_footer" id="is_footer" >
                        <option value="0"> Active </option>
                        <option value="1"> In Active </option>
                    </select> 
                </td>
            </tr>

            <tr>
                <td></td>    
                <td>
                    <input type="hidden" name="Admin_key_operation" value="add"  />
                    <input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addAdmin_key" type="submit">
                </td>
            </tr>

        </table>
    </form>
</div>
