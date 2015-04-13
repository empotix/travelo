<div id="ainTop_Links clearfix">

    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>




    <div class="clsTitle">
        <h3><?php echo translate_admin("Top Location"); ?></h3>

        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('toplocation/addlocation'); ?>"><?php echo translate_admin('Add location'); ?></a></li>
            </ul>
        </div>		   

        <form action="<?php echo admin_url('page/deletePage') ?>" name="managepage" method="post">
            <table class="table" cellpadding="2" cellspacing="0">
                <th></th>
                <th><?php echo translate_admin('S.No'); ?></th>
                <th><?php echo translate_admin('Country Name'); ?></th>
                <th><?php echo translate_admin('City Name'); ?></th>
                <th><?php echo translate_admin('Action'); ?></th>									

                <?php
                    if (isset($location) and $location->num_rows() > 0)
                    {
                        foreach ($location->result() as $location)
                        {
                            ?>
                            <tr>
                                <td></td>
                                <td><?php echo $location->id; ?></td>
                                <td><?php echo $location->categoryname; ?></td>
                                <td><?php echo $location->name; ?></td> 
                                <td><li><a href="<?php echo admin_url('toplocation/editlocation/' . $location->id) ?>"><img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a></li></td>
                            </tr>

                            <?php
                        }//Foreach End
                    }//If End
                ?>



            </table>
        </form>	




        <!--END OF MID WRAPPER-->
    </div>
    <!-- End of clsSettings -->