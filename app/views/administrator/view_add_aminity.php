<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>

<div class="clsTitle">
    <h3><?php echo translate_admin('Add Amenity'); ?></h3>
</div>


<form action="<?php echo admin_url('lists/addaminities'); ?>" method="post">
    <table class="table">

        <tr>
            <td class="contentwidth"><?php echo translate_admin("Additional Amenity"); ?><span class="clsRed">*</span></td>
            <td class="contentwidth"><input type="text" name="addaminitie" value=""></td>
            <td><?php echo translate_admin("Add Additional Amenity Name"); ?></td>
        </tr>

        <tr>
            <td><?php echo translate_admin("Amenity Description"); ?><span class="clsRed">*</span></td>
            <td><input type="text" name="desc_aminitie" value=""></td>
            <td><?php echo translate_admin("Amenity Description"); ?></td>
        </tr>


        <tr>
            <td></td>
            <td>

                <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update_price" value="<?php echo translate_admin("Add"); ?>" style="width:90px;" /></span>


            </td>
        </tr>


    </table> 
</form>
