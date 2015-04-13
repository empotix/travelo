
<div id="View_Pages">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>


    <div class="MainTop_Links clearfix">
        <?php /* ?>  <div class="clsNav">
              <ul>
              <li class="clsNoBorder"><a href="<?php echo admin_url('cancellation/addCancellation')?>"><?php echo translate_admin('Add Cancellation Policy'); ?></a></li>

              </ul>
              </div>
              <?php */ ?>		<div class="clsTitle">
            <h3><?php echo translate_admin("Manage Cancellation Policy"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('cancellation/deleteCancellation') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Site Name'); ?></th>
            <th><?php echo translate_admin('Cancellation Policy Name'); ?></th>
            <th><?php echo translate_admin('Action'); ?></th>

            <?php
                $i = 1;
                if (isset($cancellation) and $cancellation->num_rows() > 0)
                {
                    foreach ($cancellation->result() as $cancellations)
                    {
                        ?>

                        <tr>
                            <td></td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $cancellations->site_name; ?></td>
                            <td><?php echo $cancellations->cancellation_title; ?></td>
                            <td><a href="<?php echo admin_url('cancellation/editCancellation/' . $cancellations->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                            </td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
                else
                {
                    echo '<tr><td colspan="5">' . translate_admin('No Cancellation Policy Found') . '</td></tr>';
                }
            ?>
        </table>
        <?php /* ?><br />
              <p style="text-align:left">
              <?php
              $data = array(
              'name' => 'delete',
              'class' => 'Blck_Butt',
              'value' => translate_admin('Delete Cancellation Policy'),
              );
              echo form_submit($data);?></p><?php */ ?>
    </form>	
</div>


