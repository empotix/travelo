<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>

<div id="Add_Email_Template">

    <div class="clsTitle">
        <h3><?php echo translate_admin("Add New Top Location"); ?></h3>
    </div> 
    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('toplocation'); ?>"><?php echo translate_admin('View locations'); ?></a></li>
            </ul>
        </div>
        <div class="clsTitle">
            <?php /* ?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */ ?>
        </div>
    </div>
    <div>
        <form method="post" action="<?php echo admin_url('toplocation/addlocation') ?>">					
            <table width="700" class="table">

                <tr>
                    <td><label><?php echo translate_admin('City Name'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="50" maxlength="50" type="text" name="name" id="city" value=""/>
                        <?php echo form_error('name'); ?>
                    </td>

                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Country Name'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="50" type="text" maxlength="50" name="location" id="city" value=""/>
                        <?php echo form_error('location'); ?>
                    </td>
                </tr>


                <tr>
                    <td></td>
                    <td>
                        <input  name="submit" type="submit" value="Submit">
                    </td>
                </tr>

            </table>
        </form>	
    </div>






