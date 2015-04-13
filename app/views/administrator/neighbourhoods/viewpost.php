
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
                <li class="clsNoBorder"><a href="<?php echo admin_url('neighbourhoods/addpost') ?>"><?php echo translate_admin('Add Post'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Neighbourhood Place Posts"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('neighbourhoods/deletepost') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Post ID'); ?></th>
            <th><?php echo translate_admin('City'); ?></th>
            <th><?php echo translate_admin('Place'); ?></th>
            <th><?php echo translate_admin('Title'); ?></th>
            <th><?php echo translate_admin('Description'); ?></th>
            <th><?php echo translate_admin('Is Featured ?'); ?></th>
            <!--<th><?php echo translate_admin('Created'); ?></th>-->
            <th><?php echo translate_admin('Action'); ?></th>


            <?php
                $i = 1;
                if (isset($posts) and $posts->num_rows() > 0)
                {
                    foreach ($posts->result() as $post)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $post->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $post->id; ?></td>
                            <td><?php echo $post->city; ?></td>
                            <td><?php echo $post->place; ?></td>
                            <td><?php echo word_limiter($post->image_title, 4); ?></td>
                            <td><?php echo word_limiter($post->image_desc, 4); ?></td>
                            <td><?php if ($post->is_featured == 0)
                echo translate_admin('No');
            else
                echo translate_admin('Yes');
            ?></td>
                           <!-- <td><?php echo get_user_times($post->created, get_user_timezone()); ?></td>-->
                            <td><a href="<?php echo admin_url('neighbourhoods/editpost/' . $post->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                <a href="<?php echo admin_url('neighbourhoods/deletepost/' . $post->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
                            </td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
                else
                {
                    echo '<tr><td colspan="5">' . translate_admin('No Posts Found') . '</td></tr>';
                }
            ?>
        </table>
        <br />
        <p style="text-align:left">
            <?php
                $data = array(
                    'name' => 'delete',
                    'class' => 'Blck_Butt',
                    'value' => translate_admin('Delete Post'),
                );
                echo form_submit($data);
            ?></p>
    </form>	
</div>


