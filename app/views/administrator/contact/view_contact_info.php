<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>

<script type="text/javascript">
    function startCallback() {
        $("#message").html('<img src="<?php echo base_url() . 'images/loading.gif' ?>">');
        // make something useful before submit (onStart)
        return true;
    }

    /*function completeCallback(response) {
     $('#message').show();
     $("#message").html(response);
     $("#message").delay(1800).fadeOut('slow');
     }*/
</script>

<div id="View_Contact_Info">
    <div class="clsTitle">
        <h3><?php echo translate_admin('Manage Contact Info'); ?></h3>
    </div>

    <form action="<?php echo admin_url('contact'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">	

        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName"><?php echo translate_admin('Phone'); ?>*</td>
                <td> <input type="text" size="45" name="phone" value="<?php if (isset($row->phone)) echo $row->phone; ?>">
                    <?php echo form_error('phone'); ?></td>
            </tr>		

            <tr>
                <td class="clsName"><?php echo translate_admin('E-Mail'); ?>*</td>
                <td> <input type="text" size="45" name="email" value="<?php if (isset($row->email)) echo $row->email; ?>">
                    <?php echo form_error('email'); ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Name / Company Name'); ?>*</td>
                <td> <input type="text" size="45" name="name" value="<?php if (isset($row->name)) echo $row->name; ?>">
                    <?php echo form_error('name'); ?></td>
            </tr>		

            <tr>
                <td class="clsName"><?php echo translate_admin('Street Address'); ?>*</td>
                <td> <input type="text" size="45" name="street" value="<?php if (isset($row->street)) echo $row->street; ?>">
                    <?php echo form_error('street'); ?></td>
            </tr>	

            <tr>
                <td class="clsName"><?php echo translate_admin('City'); ?>*</td>
                <td> <input type="text" size="45" name="city" value="<?php if (isset($row->city)) echo $row->city; ?>">
                    <?php echo form_error('city'); ?></td>
            </tr>		

            <tr>
                <td class="clsName"><?php echo translate_admin('State'); ?>*</td>
                <td> <input type="text" size="45" name="state" value="<?php if (isset($row->state)) echo $row->state; ?>">
                    <?php echo form_error('state'); ?></td>
            </tr>	

            <tr>
                <td class="clsName"><?php echo translate_admin('Country'); ?>*</td>
                <td> <input type="text" size="45" name="country" value="<?php if (isset($row->country)) echo $row->country; ?>"></td>
                <?php echo form_error('country'); ?>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Pincode'); ?>*</td>
                <td> <input type="text" size="45" name="pincode" value="<?php if (($row->pincode != '0')) echo $row->pincode; ?>"></td>
                <?php echo form_error('pincode'); ?>
            </tr>						

            <tr>
                <td></td>
                <td>
                    <div class="clearfix">
                        <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" /></span>
                        <span style="float:left;"><div id="message"></div></span>
                    </div>
                </td>
            </tr>

        </table>

        <?php echo form_close(); ?>

</div>
