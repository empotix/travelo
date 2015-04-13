<div class="View_Managemetas">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>




    <div class="clsTitle">
        <h3><?php echo translate_admin("Manage Metas"); ?></h3> 
    </div>

    <form action="<?php echo admin_url('page/deletePage') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Url'); ?></th>
            <th><?php echo translate_admin('Name'); ?></th>
            <th><?php echo translate_admin('Title'); ?></th>
            <th><?php echo translate_admin('Description'); ?></th> 	
            <th><?php echo translate_admin('Keyword'); ?></th>
            <th><?php echo translate_admin('Action'); ?></th>	



            <?php
                if (isset($meta) and $meta->num_rows() > 0)
                {
                    foreach ($meta->result() as $meta)
                    {
                        ?>
                        <tr>
                            <td></td>
                            <td><?php echo $meta->id; ?></td>
                            <td><?php echo $meta->url; ?></td>
                            <td><?php echo $meta->name; ?></td>
                            <td><?php echo $meta->title; ?></td>
                            <td><?php echo $meta->meta_description; ?></td>
                            <td><?php echo $meta->meta_keyword; ?></td>
                            <td><li><a href="<?php echo admin_url('managemetas/editmetas/' . $meta->id) ?>"><img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a></li></td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
            ?>



        </table>
    </form>	

</div>