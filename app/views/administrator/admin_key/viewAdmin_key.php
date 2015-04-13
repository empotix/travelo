
<div id="View_Admin_keys">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li class="clsNoBorder"><a href="<?php echo admin_url('admin_key/addAdmin_key') ?>"><?php echo translate_admin('Add Key'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Static Admin_key"); ?></h3>
        </div>
    </div>

    <form action="<?php echo admin_url('admin_key/deleteAdmin_key') ?>" name="manageAdmin_key" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Page Key'); ?></th>
            <!--<th><?php echo translate_admin('Page Ref.'); ?></th>-->
            <th><?php echo translate_admin('Created'); ?></th>
            <th><?php echo translate_admin('Status'); ?></th>
            <th><?php echo translate_admin('Edit'); ?></th>

            <?php
                $i = 1;
                if (isset($Admin_key) and $Admin_key->num_rows() > 0)
                {
                    foreach ($Admin_key->result() as $Admin_key)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="Admin_keylist[]" id="Admin_keylist[]" value="<?php echo $Admin_key->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $Admin_key->page_key; ?></td>
                            <!--<td><?php echo $Admin_key->page_refer; ?></td>-->
                            <td><?php echo get_user_times($Admin_key->created, get_user_timezone()); ?></td>
                            <?php
                            if ($Admin_key->status == '0')
                            {
                                $status = translate_admin('Active');
                            }
                            else
                            {
                                $status = translate_admin('In Active');
                            }
                            ?>
                            <td><?php echo $status; ?></td>
                            <td><a href="<?php echo admin_url('admin_key/editAdmin_key/' . $Admin_key->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                <a href="<?php echo admin_url('admin_key/deleteAdmin_key/' . $Admin_key->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
                            </td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
                else
                {
                    echo '<tr><td colspan="5">' . translate_admin('No Admin_keys Found') . '</td></tr>';
                }
            ?>
        </table>

    </form>	
</div>


