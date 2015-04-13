<div id="Email_Template">

    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>	
    <div class="MainTop_Links clearfix">
        <div class="clsTitle">
            <h3><?php echo translate_admin('Manage Email Template'); ?></h3>
        </div>
        <div class="clsNav">
            <ul class="clearfix">
                <li><a href="<?php echo admin_url('email/addemailTemplate') ?>"><?php echo translate_admin('Add Email Settings'); ?></a></li>
            </ul>
        </div>

    </div>
    <table class="table" cellpadding="2" cellspacing="0" align="left">
        <tr>
            <th><?php echo translate_admin("S.No"); ?></th>
            <th><?php echo translate_admin("Title"); ?></th>
            <th><?php echo translate_admin("Code"); ?></th>
            <th><?php echo translate_admin("Action"); ?></th>
        </tr>

        <?php
            if (isset($email_settings) && $email_settings->num_rows() > 0)
            {
                $i = 1;
                foreach ($email_settings->result() as $email_setting)
                {
                    ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo ucfirst($email_setting->title); ?></td>
                        <td><?php echo $email_setting->type; ?></td>

                        <td><a href="<?php echo admin_url('email/edit/' . $email_setting->id) ?>">	<img src="<?php echo base_url() ?>images/edit_img.jpg"/></a>
                            <a href="<?php echo admin_url('email/delete/' . $email_setting->id) ?>"; onclick="return confirm('Are you sure want to delete??');"> 		<img src="<?php echo base_url() ?>images/Delete.png"/>
                            </a></td>
                    </tr>
                    <?php
                    $i++;
                }//Foreach End
            }
            else
            {
                echo '<tr><td colspan="6">' . translate_admin('No Template Found') . '</td></tr>';
            }
        ?>
    </table>

</div>
