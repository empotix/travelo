<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>

<div class="clsTitle">
    <h3><?php echo translate_admin('Add Property type'); ?></h3>
</div>


<form action="<?php echo admin_url('property_type/addproperties'); ?>" method="post">
    <table class="table">

        <tr>
            <td class="contentwidths"><?php echo translate_admin("Additional Property type"); ?> <span style="color:#FF0000">*</span></td>
            <td class="contentwidth"><input type="text" name="addproperty" value=""></td>
            <td><?php echo translate_admin("Add Additional Property Type"); ?></td>
        </tr>


        <tr>
            <td></td>
            <td>

                <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update_price" value="<?php echo translate_admin("Add"); ?>" style="width:90px;" /></span>


            </td>
        </tr>


    </table> 
</form>

