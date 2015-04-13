<script src="<?php echo js_url(); ?>/jquery.js" type="text/javascript"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.8.3.js"></script>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>-

<?php
    //Content of a group
    if (isset($coupon) and $coupon->num_rows() > 0)
    {
        $coupon = $coupon->row();
        ?>
        <?php $xdate = $coupon->expirein; ?>
        <script>
            $(function () {
                $("#expirein").datepicker(
                        {
                            minDate: "<?php echo $xdate; ?>",
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

        <div class="Edit_Coupon_Page">
            <?php
            //Show Flash Message
            if ($msg = $this->session->flashdata('flash_message'))
            {
                echo $msg;
            }
            ?>

            <div class="clsTitle">
                <h3><?php echo translate_admin('Edit Coupon'); ?></h3>
            </div>
            <form action="<?php echo admin_url('coupon/edit_coupon'); ?>/<?php echo $coupon->id; ?>" method="post">	
                <table class="table" cellpadding="2" cellspacing="0">
                    <tr> 
                        <td class="clsCoupon"><?php echo translate_admin("Expire In"); ?><span style="color:#FF0000">*</span></td>
                        <td><input class="clsCoupon" id="expirein" name="expirein" type="text" size="10" value="<?php echo $coupon->expirein; ?>" readonly="readonly" />
                            <?php echo form_error('expirein'); ?>
                        </td> 
                    </tr>
                    <tr> 
                        <td class="clsCoupon"><?php echo translate_admin("Enter Coupon Price"); ?><span style="color:#FF0000">*</span></td>
                        <td><input class="clsCoupon" id="coupon_price" name="coupon_price" type="text" size="10" value="<?php echo $coupon->coupon_price; ?>"/>
                            <?php echo form_error('coupon_price'); ?>
                        </td> 
                    </tr>
                    <tr>
                        <td class="clsCoupon"><?php echo translate_admin('Coupon Code:'); ?></td>
                        <td><?php echo $coupon->couponcode; ?></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td> <input class="clsCoupon updatecop" id="codegen" type="submit" style="width:150px;" value="<?php echo translate_admin('Update'); ?>" name="submit" ></td>
                    </tr>
                </table>
            </form>
        <?php } ?>
</div>