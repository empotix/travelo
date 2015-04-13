
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
                <li class="clsNoBorder"><a href="<?php echo admin_url('neighbourhoods/addcity_place') ?>"><?php echo translate_admin('Add City Place'); ?></a></li>

            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Neighbourhoods"); ?></h3>
        </div>
    </div>


    <form action="<?php echo admin_url('neighbourhoods/deletecity_place') ?>" name="managepage" method="post">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('City'); ?></th>
            <th><?php echo translate_admin('Place'); ?></th>
            <th><?php echo translate_admin('Quote'); ?></th>
            <th><?php echo translate_admin('Short Description'); ?></th>
            <th><?php echo translate_admin('Long Description'); ?></th>
            <th><?php echo translate_admin('Category'); ?></th>
            <th><?php echo translate_admin('Is Featured') . '?'; ?></th>
            <th><?php echo translate_admin('Created'); ?></th>
            <th><?php echo translate_admin('Action'); ?></th>

            <?php
                $i = 1;
                if (isset($places) and $places->num_rows() > 0)
                {
                    foreach ($places->result() as $place)
                    {
                        ?>

                        <tr>
                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $place->id; ?>"  /> </td>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $place->city_name; ?></td>
                            <td><?php echo $place->place_name; ?></td>
                            <td><?php echo word_limiter($place->quote, 4); ?></td>
                            <td><?php echo word_limiter($place->short_desc, 4); ?></td>
                            <td><?php echo word_limiter($place->long_desc, 4); ?></td>
                            <td>
                                <?php
                                $result = $this->db->where('place', $place->place_name)->get('neigh_place_category');

                                if ($result->num_rows() != 0)
                                {
                                    foreach ($result->result() as $row)
                                    {
                                        echo $this->db->where('id', $row->category_id)->get('neigh_category')->row()->category . ',';
                                    }
                                }
                                ?>
                            </td>
                            <td><?php if ($place->is_featured == 0)
                        echo translate_admin('No');
                    else
                        echo translate_admin('Yes');
                    ?></td>
                            <td><?php echo get_user_times($place->created, get_user_timezone()); ?></td>
                            <td><a href="<?php echo admin_url('neighbourhoods/editcity_place/' . $place->id) ?>">
                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                <a href="<?php echo admin_url('neighbourhoods/deletecity_place/' . $place->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
                            </td>
                        </tr>

                        <?php
                    }//Foreach End
                }//If End
                else
                {
                    echo '<tr><td colspan="5">' . translate_admin('No Neighbourhood Places Found') . '</td></tr>';
                }
            ?>
        </table>
        <br />
        <p style="text-align:left">
            <?php
                $data = array(
                    'name' => 'delete',
                    'class' => 'Blck_Butt',
                    'value' => translate_admin('Delete Place'),
                );
                echo form_submit($data);
            ?></p>
    </form>	
</div>


