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
        if (isset($property) and $property->num_rows() > 0)
        {
            $property = $property->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit property type'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('property_type/editproperty') ?>/<?php echo $property->id; ?>">
                <table class="table" cellpadding="2" cellspacing="0">

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Property type'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="type" id="type" value="<?php echo $property->type; ?>">
                            <?php echo form_error('type'); ?>
                        </td>
                    </tr>


                    <tr>
                        <td>
                            <input type="hidden" name="id"  value="<?php echo $property->id; ?>"/></td><td>
                            <input  name="submit" type="submit" value="Submit"></td>
                    </tr>  

                </table>
            </form>
            <?php
        }
    ?>
</div>

