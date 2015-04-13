<div class="Edit_Page">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <?php
        //Content of a group
        if (isset($aminity) and $aminity->num_rows() > 0)
        {
            $aminity = $aminity->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit Amenity'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('lists/editaminity') ?>/<?php echo $aminity->id; ?>">
                <table class="table" cellpadding="2" cellspacing="0">

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Amenity Name'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="name" id="name" value="<?php echo $aminity->name; ?>">
                        </td>
                    </tr>
                    <tr>
                        <td class="clsName"><?php echo translate_admin('Amenity Description'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="desc" id="desc" value="<?php echo $aminity->description; ?>">
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <input type="hidden" name="id"  value="<?php echo $aminity->id; ?>"/></td><td>
                            <input  name="submit" type="submit" value="Submit"></td>
                    </tr>  

                </table>
            </form>
            <?php
        }
    ?>
</div>
