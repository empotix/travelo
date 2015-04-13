<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>


<div id="Add_Email_Template">

    <div class="clsTitle">
        <h3><?php echo translate_admin("Add Neighbourhood City"); ?></h3>
    </div> 
    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('neighbourhoods/viewcity'); ?>"><?php echo translate_admin('Manage Cities'); ?></a></li>
            </ul>
        </div>
        <div class="clsTitle">
            <?php /* ?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */ ?>
        </div>
    </div>
    <div>
        <form method="post" action="<?php echo admin_url('neighbourhoods/addcity') ?>" enctype="multipart/form-data">					
            <table width="700" class="table">

                <tr>
                    <td><label><?php echo translate_admin('City'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="30" type="text" name="city" id="city" value=""/>
                        <?php echo form_error('city'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('City Description'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <textarea class="clsTextBox" name="city_desc" id="city_desc" value="" style="height: 162px; width: 282px;" ></textarea>
                        <?php echo form_error('city_desc'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Known For'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <textarea class="clsTextBox" name="known" id="known" value="" style="height: 162px; width: 282px;" ></textarea>
                        <?php echo form_error('known'); ?>
                        <span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Ex: Hollywood,boardwalks."); ?></span>
                    </td>
                </tr>

                <tr>
                    <td><label><?php echo translate_admin('Get around with'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="30" type="text" name="around" id="around" value=""/>
                        <?php echo form_error('around'); ?>
                        <span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate_admin("Ex: Car,Public Transit"); ?></span>
                    </td>
                </tr>

                <tr>
                    <td class="clsName"><?php echo translate_admin('City Image'); ?><span class="clsRed">*</span></td>
                    <td>
                        <input id="city_image" name="city_image"  size="24" type="file" />
                        <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 1425x500</span>
                    </td>
                </tr>
                <tr>
                    <td class="clsName"><?php echo translate_admin('Is Home') . '?'; ?></td>
                    <td>
                        <select name="is_home" id="is_home" >
                            <option value="0"> <?php echo translate_admin('No'); ?> </option>
                            <option value="1"> <?php echo translate_admin('Yes'); ?> </option>
                        </select> 
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
                    </td>
                </tr>

            </table>
        </form>	
    </div>






