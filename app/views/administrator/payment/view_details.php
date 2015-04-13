<div id="View_Details">

    <div class="clsTitle">
        <h3><?php echo translate_admin('Reservation Details'); ?></h3>
    </div>
    <form action="<?php echo admin_url('payment/toPay'); ?>" method="post" name="admin_paypal">
        <table class="table" cellpadding="2" cellspacing="0">
            <tr>
                <td class="clsName"><?php echo translate_admin('List Name'); ?></td>
                <td><?php echo get_list_by_id($result->list_id)->title; ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Host Name'); ?></td>
                <td>
                    <?php echo $hotelier_name; ?>
                    <input type="hidden" name="userto" value="<?php echo $result->userto; ?>" />
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Traveller Name'); ?></td>
                <td>
                    <?php echo $booker_name; ?>
                    <input type="hidden" name="userby" value="<?php echo $result->userby; ?>" />
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Checkin'); ?></td>
                <td><?php echo date("F j, Y", $result->checkin); ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Checkout'); ?></td>
                <td><?php echo date("F j, Y", $result->checkout); ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Using Travel Cretids?'); ?></td>
                <td><?php if ($result->credit_type == 2)
                        echo 'Yes';
                    else
                        echo 'No';
                    ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Total Price'); ?></td>
                <td><?php echo $result->price; ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Admin Commision'); ?></td>
                <td><?php echo $result->admin_commission; ?></td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('To Pay'); ?></td>
                <td>
<?php echo $result->topay; ?>
                    <input type="hidden" name="to_pay" value="<?php echo $result->topay; ?>" />
                    <input type="hidden" name="currency" value="<?php echo $result->currency; ?>" />
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <div class="clearfix">
                        <span style="float:left; margin:0 10px 0 0;">
                            <input type="hidden" name="list_id" value="<?php echo $result->list_id; ?>" />
                            <input type="hidden" name="reservation_id" value="<?php echo $result->id; ?>" />
                            <?php
                                $host_payout_id = get_userPayout($result->userto);
                                $guest_payout_id = get_userPayout($result->userby);
                                if ($result->is_payed != 1)
                                {
                                    if ($result->status != 1 && $result->status != 2 && $result->status != 4 && $result->status != 5 && $result->status != 6)
                                    {
                                        if (isset($host_payout_id->email))
                                        {
                                            ?>
                                            <input type="hidden" name="biz_id" value="<?php echo $host_payout_id->email; ?>" />
                                            <input class="clsSubmitBt1" type="submit" name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Host'); ?>" style="width:190px;" />
            <?php
            }
            else
            {
                ?>
                                            <p> The <b><?php echo $hotelier_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title; ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
                                            <textarea class="text_area" cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
                                            <br />
                                            <?php if (form_error('comment'))
                                            {
                                                ?>
                                                <?php echo form_error('comment'); ?>
                                            <?php } ?>

                                            <p>	<input class="clsSubmitBt1" type="submit" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>
                                            <?php
                                        }
                                    }
                                    else if ($result->status == 2 || $result->status == 4 || $result->status == 5 || $result->status == 6)
                                    {
                                        if (isset($guest_payout_id->email))
                                        {
                                            ?>
                                            <input type="hidden" name="biz_id" value="<?php echo $guest_payout_id->email; ?>" />
                                            <input class="clsSubmitBt1" type="submit" name="payviapaypal" value="<?php echo translate_admin('Pay Using PayPal to Traveller'); ?>" style="width:210px;" />
                <?php
            }
            else
            {
                ?>
                                            <p> The <b><?php echo $booker_name; ?></b> of "<?php echo get_list_by_id($result->list_id)->title; ?>" is still not set the Payout Preferences. So please notify to an user to set the Payout Preferences.</p>
                                            <textarea class="text_area" cols="60" rows="2" style="width:400px; height:80px" name="comment"></textarea>	
                                            <br />
                                            <?php if (form_error('comment'))
                                            {
                                                ?>
                                                <?php echo form_error('comment'); ?>
                                            <?php } ?>

                                            <p>	<input class="clsSubmitBt1" type="submit" name="send" value="<?php echo translate_admin('Send Message'); ?>" style="width:130px;" />	</p>

                                            <?php
                                        }
                                    }
                                    else
                                    {
                                        ?>
                                        <p><b><?php echo translate_admin('Waiting for host approval or reservation request expired or declined.'); ?></b></p>
                                        <?php
                                    }
                                }
                                else
                                {
                                    ?>
                                    <p><b><?php echo translate_admin('Already amount payed to Host/Traveller.'); ?></b></p>
        <?php
    }
?>
                        </span></div>
                </td>
            </tr>
        </table>
    </form>

</div>