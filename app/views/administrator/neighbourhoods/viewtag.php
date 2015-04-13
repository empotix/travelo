
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
                <li class="clsNoBorder"><a href="<?php echo admin_url('neighbourhoods/addtag') ?>"><?php echo translate_admin('Add Tag'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Neighbourhoods Tag"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('neighbourhoods/deletetag') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Tag'); ?></th>
            <th><?php echo translate_admin('City'); ?></th>
            <th><?php echo translate_admin('Place'); ?></th>
            <th><?php echo translate_admin('Username'); ?></th>
            <th><?php echo translate_admin('Is Shown'); ?>?</th>
            <th><?php echo translate_admin('Action'); ?></th>

            <?php
                $i = 1;
                if (isset($tags) and $tags->num_rows() > 0)
                {
                    foreach ($tags->result() as $tag)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $tag->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $tag->tag; ?></td>
                            <td><?php echo $tag->city; ?></td>
                            <td><?php echo $tag->place; ?></td>
                            <td><?php echo $this->Neighbourhoods_model->username($tag->user_id); ?></td>
                            <td><?php if ($tag->shown == 0)
                echo translate_admin('No');
            else
                echo translate_admin('Yes');
            ?></td>
                            <td><a href="<?php echo admin_url('neighbourhoods/edittag/' . $tag->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                <a href="<?php echo admin_url('neighbourhoods/deletetag/' . $tag->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
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
                    'value' => translate_admin('Delete Tag'),
                );
                echo form_submit($data);
            ?></p>
    </form>	
</div>


