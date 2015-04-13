<script src="<?php echo base_url() . 'js/jquery.validate.min.js'; ?>"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#form").validate({
            debug: false,
            rules: {
                email: {
                    required: true,
                    email: true
                }
            },
            messages: {
                email:
                        {
                            required: "You must enter the email-id",
                            email: "Please enter the correct email-id"

                        }
            },
        });
    });
</script>
<style>
    label.error { width: 250px; display: inline; color: red; margin-left: 10px;}
</style>
<script type="text/javascript">
    $(document).ready(function () {

        var curr = "<?php echo get_currency_code(); ?>";

        $('#currency_type').val(curr);




    });
</script>

<div id="paypal_payout">
    <h3><?php echo $payout_name; ?> <?php echo translate("Information"); ?></h3>

    <form method="post" id="form" action="<?php echo site_url('account/payout'); ?>">        
        <input type="hidden" value="<?php echo $country; ?>" name="country" id="country">
        <input type="hidden" value="<?php echo $payout_type; ?>" name="payout_type" id="email">

        <?php if ($payout_type == 2)
            {
                ?>

                <table cellspacing="5">
                    <tbody>

                        <tr>
                            <td> <?php echo translate("To what e-mail should we send the money?"); ?></td>
                            <td>
                                <input type="text" value="" size="30" name="email" id="email">
                                <br>
                                <span style="font-size:0.85em;color:#8b8b8b;"><?php echo translate("This email address must be associated with a valid Paypal account."); ?></span><br>
                                <a target="_blank" style="font-size:0.85em;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_registration-run"><?php echo translate("Don't have a paypal account?"); ?></a>
                            </td>
                        </tr>

                        <tr>
                            <td><?php echo translate("In what currency would you like to be paid?"); ?></td>
                            <td>
                                <select id="currency_type" name="currency">
                                    <option value="USD">USD</option>
                                    <option value="GBP">GBP</option>
                                    <option value="EUR">EUR</option>
                                    <option value="AUD">AUD</option>
                                    <option value="SGD">SGD</option>
                                    <option value="SEK">SEK</option>
                                    <option value="DKK">DKK</option>
                                    <option value="MXN">MXN</option>
                                    <option value="BRL">BRL</option>
                                    <option value="MYR">MYR</option>
                                    <option value="PHP">PHP</option>
                                    <option value="CHF">CHF</option>
                                </select>
                            </td>
                        </tr>

                    </tbody>
                </table>

                            <?php } ?>
        <p><button type="submit" class="btn blue gotomsg" name="commit" id="next2"><span><span><?php echo translate("Save"); ?></span></span></button>
            <?php echo translate("or"); ?>
            &nbsp;<a onclick="$('#payout_new_select').hide();
                    $('#payout_new_initial').show();
                    return false;" href="#"><?php echo translate("Cancel"); ?></a></p>
    </form>
</div>