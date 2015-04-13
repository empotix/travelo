
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
                <li class="clsNoBorder"><a href="<?php echo admin_url('neighbourhoods/addcategory') ?>"><?php echo translate_admin('Add Category'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Categories"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('neighbourhoods/deletecategory') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Category'); ?></th>
            <th><?php echo translate_admin('Created'); ?></th>
            <th><?php echo translate_admin('Action'); ?></th>									

            <?php
                $i = 1;
                if (isset($categories) and $categories->num_rows() > 0)
                {
                    foreach ($categories->result() as $category)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $category->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $category->category; ?></td>
                            <td><?php echo get_user_times($category->created, get_user_timezone()); ?></td>
                            <td><a href="<?php echo admin_url('neighbourhoods/editcategory/' . $category->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                <a href="<?php echo admin_url('neighbourhoods/deletecategory/' . $category->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
                            </td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
                else
                {
                    echo '<tr><td colspan="5">' . translate_admin('No Category Found') . '</td></tr>';
                }
            ?>
        </table>
        <br />
        <p style="text-align:left">
            <?php
                $data = array(
                    'name' => 'delete',
                    'class' => 'Blck_Butt',
                    'value' => translate_admin('Delete Category'),
                );
                echo form_submit($data);
            ?></p>
    </form>	
</div>


