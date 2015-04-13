<script src="<?php echo js_url(); ?>/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>

<script>
    $(function () {
        $("#expirein").datepicker(
                {
                    minDate: new Date(),
                    dateFormat: "dd/mm/yy",
                    maxDate: "+2Y",
                    nextText: "",
                    prevText: "",
                    numberOfMonths: 1,
                    showButtonPanel: true
                }
        );
    });
</script>
<script>
    $(document).ready(function () {
        $("#gencode").val("");

        $("#codegen").click(function () {

            $("#gencode").val("<?php echo uniqid(); ?>");

        });
    });
</script>

<div id="Viewcoupon">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <div class="clsTitle">
        <div class="clsNav">
            <ul>
                <li class="clsNoBorder addcoupon"><a class="view_coupon_bg" href="<?php echo admin_url('coupon/view_all_coupon') ?>"><?php echo translate_admin('View Coupon'); ?></a></li>
            </ul>
        </div>
        <h3><?php echo translate_admin('Generate Coupon Code'); ?></h3>
    </div>
    <form action="<?php echo admin_url('coupon/view_coupon'); ?>" method="post">	
        <table class="table" cellpadding="2" cellspacing="0">
            <tr> 
                <td class="clsCoupon"><?php echo translate_admin("Expire In"); ?>:<span style="color:#FF0000">*</span></td>
                <td><input class="clsCoupon" id="expirein" name="expirein" type="text" size="10" value="<?php echo set_value('expirein'); ?>" readonly="readonly" />
                    <?php echo form_error('expirein'); ?>
                </td> 
            </tr>
            <tr> 
                <td class="clsCoupon"><?php echo translate_admin("Enter Coupon Price"); ?>:<span style="color:#FF0000">*</span></td>
                <td><input class="clsCoupon" id="coupon_price" name="coupon_price" type="text" size="10" value="<?php echo set_value('coupon_price'); ?>"/>
                    <?php echo form_error('coupon_price'); ?>
                </td> 
            </tr>
            <tr>
                <td class="clsCoupon"><?php echo translate_admin('Coupon Code'); ?>:<span style="color:#FF0000">*</span></td>
                <td><div><input type="text" name="gencode" id="gencode" value="<?php echo set_value('gencode'); ?>" readonly="readonly" />
                        <input class="clsCoupon" id="codegen" type="button" style="width:150px;" value="<?php echo translate_admin('Generate Code'); ?>" name="codegen" >
                        <?php echo form_error('gencode'); ?> 
                        </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><input class="clsCoupon"  type="submit" style="width:150px;" value="<?php echo translate_admin('submit'); ?>" name="submit" ></td>
                        </tr>
        </table>

    </form>
</div>