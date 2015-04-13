<div id="Edit_Location">
    <div class="clsTitle">
        <h3><?php echo translate_admin('Change Password'); ?></h3></div>

    <form action="<?php echo admin_url('members/changepassword/' . $this->uri->segment(4, 0)); ?>" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <tr>
                <td class="label" valign="top"><?php echo translate_admin("New Password"); ?>:<sup>*</sup></td>
                <td> 
                    <input type="password" name="new_password" value="" />
                    <span style="color:#FF0000"><?php echo form_error('new_password'); ?></span>
                </td>
            </tr>

            <tr>
                <td style="vertical-align:top;"><?php echo translate_admin("Confirm Password"); ?> :<sup>*</sup></td>
                <td>
                    <input type="password" name="confirm_new_password" value="" />
                    <span style="color:#FF0000"><?php echo form_error('confirm_new_password'); ?></span>
                </td>
            </tr>                
        </table>
        <p>
            <button type="submit" class="button1"  name="commit"><span><span><?php echo translate_admin("Update"); ?></span></span></button>
        </p>

    </form>
</div>
