
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
                <li class="clsNoBorder"><a href="<?php echo admin_url('neighbourhoods/addcity') ?>"><?php echo translate_admin('Add City'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Neighbourhood Cities"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('neighbourhoods/deletecity') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('City'); ?></th>
            <th><?php echo translate_admin('Description'); ?></th>
            <th><?php echo translate_admin('Get Around With'); ?></th>
            <th><?php echo translate_admin('Known For'); ?></th>
            <th><?php echo translate_admin('Is Home') . '?'; ?></th>
            <th><?php echo translate_admin('Created'); ?></th>
            <th><?php echo translate_admin('Action'); ?></th>

            <?php
                $i = 1;
                if (isset($cities) and $cities->num_rows() > 0)
                {
                    foreach ($cities->result() as $city)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $city->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $city->city_name; ?></td>
                            <td><?php echo word_limiter($city->city_desc, 4); ?></td>
                            <td><?php echo word_limiter($city->around, 4); ?></td>
                            <td><?php echo word_limiter($city->known, 4); ?></td>
                            <td><?php if ($city->is_home == 0)
                echo translate_admin('No');
            else
                echo translate_admin('Yes');
            ?></td>
                            <td><?php echo get_user_times($city->created, get_user_timezone()); ?></td>
                            <td><a href="<?php echo admin_url('neighbourhoods/editcity/' . $city->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                <a href="<?php echo admin_url('neighbourhoods/deletecity/' . $city->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
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
                    'value' => translate_admin('Delete City'),
                );
                echo form_submit($data);
            ?></p>
    </form>	
</div>


