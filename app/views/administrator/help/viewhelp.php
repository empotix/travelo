
<div id="View_helps">
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
                <li class="clsNoBorder"><a href="<?php echo admin_url('help/addhelp') ?>"><?php echo translate_admin('Add Help'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Help"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('help/hidehelp') ?>" name="managehelp" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Question'); ?></th>
            <th><?php echo translate_admin('Description'); ?></th>
            <th><?php echo translate_admin('page_refer'); ?></th>
            <th><?php echo translate_admin('Created'); ?></th>
            <th><?php echo translate_admin('Modified Date'); ?></th>
            <th><?php echo translate_admin('Status'); ?></th>
            <th><?php echo translate_admin('Edit'); ?></th>

            <?php
                $i = 1;
                if (isset($helps) and $helps->num_rows() > 0)
                {
                    foreach ($helps->result() as $help)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="helplist[]" id="helplist[]" value="<?php echo $help->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $help->question; ?></td>
                            <td><?php echo word_limiter($help->description, 7); ?></td>
                            <td><li class="clsMailId">/<?php echo $help->page_refer; ?></li></td>
                        <td><?php echo get_user_times($help->created, get_user_timezone()); ?></td>
                        <td><?php echo get_user_times($help->modified_date, get_user_timezone()); ?></td>
                        <?php
                        if ($help->status == '0')
                        {
                            $status = 'Active';
                        }
                        else
                        {
                            $status = 'In Active';
                        }
                        ?>
                        <td><?php echo $status; ?></td>
                        <td><a href="<?php echo admin_url('help/edithelp/' . $help->id) ?>">
                                <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                              <!--<a href="<?php echo admin_url('help/hidehelp/' . $help->id) ?>" onclick="return confirm('Are you sure want to Hide??');"><img src="<?php echo base_url() ?>images/disable.png" alt="Hide" title="Hide" /></a>-->
                        </td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
                else
                {
                    echo '<tr><td colspan="5">' . translate_admin('No helps Found') . '</td></tr>';
                }
            ?>
        </table>
        <br />
        <!--	<p style="text-align:left">
        <?php
            $data = array(
                'name' => 'show',
                'class' => 'Blck_Butt',
                'value' => translate_admin('Active Help'),
                'onclick' => 'admin_url(administrator/help/viewhelp)',
            );
            echo form_submit($data);
        ?></p>-->
    </form>	
</div>



