
<div id="View_Pages">
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
                <li class="clsNoBorder"><a href="<?php echo admin_url('neighbourhoods/addknowledge') ?>"><?php echo translate_admin('Add Local Knowledge'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Neighbourhoods Local Knowledge"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('neighbourhoods/deleteknowledge') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Post ID'); ?></th>
            <th><?php echo translate_admin('Knowledge'); ?></th>
            <th><?php echo translate_admin('City'); ?></th>
            <th><?php echo translate_admin('Place'); ?></th>
            <th><?php echo translate_admin('Username'); ?></th>
            <th><?php echo translate_admin('User Type'); ?></th>
            <th><?php echo translate_admin('Is Shown'); ?>?</th>
            <th><?php echo translate_admin('Action'); ?></th>

            <?php
                $i = 1;
                if (isset($knowledges) and $knowledges->num_rows() > 0)
                {
                    foreach ($knowledges->result() as $knowledge)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $knowledge->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $knowledge->post_id; ?></td>
                            <td><?php echo word_limiter($knowledge->knowledge, 4); ?></td>
                            <td><?php echo $knowledge->city; ?></td>
                            <td><?php echo $knowledge->place; ?></td>
                            <td><?php echo $this->Neighbourhoods_model->username($knowledge->user_id); ?></td>
                            <td><?php echo $knowledge->user_type; ?></td>
                            <td><?php if ($knowledge->shown == 0)
                echo translate_admin('No');
            else
                echo translate_admin('Yes');
            ?></td>
                            <td><a href="<?php echo admin_url('neighbourhoods/editknowledge/' . $knowledge->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                <a href="<?php echo admin_url('neighbourhoods/deleteknowledge/' . $knowledge->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
                            </td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
                else
                {
                    echo '<tr><td colspan="5">' . translate_admin('No Neighbourhoods Found') . '</td></tr>';
                }
            ?>
        </table>
        <br />
        <p style="text-align:left">
            <?php
                $data = array(
                    'name' => 'delete',
                    'class' => 'Blck_Butt',
                    'value' => translate_admin('Delete Knowledge'),
                );
                echo form_submit($data);
            ?></p>
    </form>	
</div>


