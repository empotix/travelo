<div id="Add_GateWay">

    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('payment/manage_gateway'); ?>"><?php echo translate_admin('View All'); ?></a></li>
            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin('Add Payment Gateway'); ?></h3>
        </div>
    </div>
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>

    <?php echo form_open('administrator/payment'); ?>


    <table class="table" cellpadding="2" cellspacing="0">
        <tr>
            <td class="clsName"><?php echo translate_admin('Select The Pay Gateway'); ?><span class="clsRed">*</span></td>
            <td>
                <select name="name_gate" class="usertype" id="name_gate" onchange="javascript:showhide(this.value);">
                    <option value=""> <?php echo translate_admin('Select'); ?> </option>
                    <?php foreach ($result as $row)
                        {
                            ?>
                            <option value="<?php echo $row->id; ?>"> <?php echo $row->payment_name; ?> </option>
    <?php } ?>
                </select> 
                <span style="color:#FF0000"><?php echo form_error('name_gate'); ?></span>
            </td>
        </tr>

        <tr>
            <td class="clsName"><?php echo translate_admin('Is Active?'); ?><span class="clsRed">*</span></td>
            <td>
                <select name="is_active" id="is_active" >
                    <option value="0"> No </option>
                    <option value="1"> Yes </option>
                </select> 
            </td>
        </tr>

        <table id="payment_express" class="table">
            <tr>
                <td class="clsName"><?php echo translate_admin('Payment Express Username'); ?><span class="clsRed">*</span>

                </td>
                <td>
                    <input type="text" size="70" name="pe_user" value="" id="pe_user">
                    <span style="color:#FF0000"><?php echo form_error('pe_user'); ?></span>		
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Payment Express Password'); ?><span class="clsRed">*</span>

                </td>
                <td>
                    <input type="text" size="70" name="pe_password" value="">
                    <span style="color:#FF0000"><?php echo form_error('pe_password'); ?></span>		
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Payment Express Key'); ?><span class="clsRed">*</span>

                </td>
                <td>
                    <input type="text" size="70" name="pe_key" value="">
                    <span style="color:#FF0000"><?php echo form_error('pe_key'); ?></span>
                </td>
            </tr>
        </table>

        <table id="paypal" class="table" style="display:none;">
            <tr>
                <td class="clsName"><?php echo translate_admin('Paypal Address Id'); ?><span class="clsRed">*</span>

                </td>
                <td>
                    <input type="text" size="70" name="paypal_id" value="">
                    <span style="color:#FF0000"><?php echo form_error('paypal_id'); ?></span>
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Is Paypal Live?'); ?><span class="clsRed">*</span></td>
                <td>
                    <select name="paypal_url" id="paypal_url" >
                        <option value="yes"> Yes (Paypal Live)</option>
                        <option value="no"> No (Paypal Sandbox) </option>
                    </select>
                </td>
            </tr>
        </table>

        <table id="2checkout" class="table" style="display:none;">
            <tr>
                <td class="clsName"><?php echo translate_admin('Vendor ID'); ?><span class="clsRed">*</span>

                </td>
                <td>
                    <input type="text" size="70" name="ventor_id" value="">
                    <span style="color:#FF0000"><?php echo form_error('ventor_id'); ?></span>
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Security(2Checkout Username)'); ?><span class="clsRed">*</span>

                </td>
                <td>
                    <input type="text" size="70" name="security" value="">
                    <span style="color:#FF0000"><?php echo form_error('security'); ?></span>
                </td>
            </tr>
        </table>

        <tr>
            <td></td>
            <td>
                <input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addGateway" id="addGateway" type="submit" >
            </td>
        </tr>

    </table>	
<?php echo form_close(); ?>

</div>

<script language="Javascript">
    function showhide(id)
    {
        if (id == 1)
        {
            document.getElementById("payment_express").style.display = "inline-table";
            document.getElementById("paypal").style.display = "none";
            document.getElementById("2checkout").style.display = "none";
        }
        else if (id == 2)
        {
            document.getElementById("payment_express").style.display = "none";
            document.getElementById("paypal").style.display = "inline-table";
            document.getElementById("2checkout").style.display = "none";
        }
        else if (id == 3)
        {
            document.getElementById("2checkout").style.display = "inline-table";
            document.getElementById("payment_express").style.display = "none";
            document.getElementById("paypal").style.display = "none";
        }
    }

</script>
