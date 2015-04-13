<div id="View_Cancel_Host" class="Box">
    <div class="Box_Head1">
        <h2> <?php echo translate("Cancel Reservation"); ?> </h2>
    </div>
    <div class="Box_Content">
        <p><?php echo translate("Changes to this reservation are governed by the following Standardized policy."); ?></p>

        <ul>
            <li><?php echo translate("Your ranking in search results will be negatively affected."); ?></li>
            <!--<li><?php echo translate("An automatic review will appear on your listing indicating that a reservation was cancelled."); ?></li> -->
            <li><?php echo translate("If you cancel more than once in a six-month period, you may be subject to a cancellation fee."); ?><?php echo translate("This fee is given in"); ?><br /> <?php echo translate("the form of a discount to the guest who had their travel plans disrupted to help alleviate the cancellation."); ?></li>
        </ul>


        <form id="cancellationHost" name="cancel_host" action="<?php echo site_url('hosting/cancel_host'); ?>" method="post">
            <p><?php echo translate("Agree the cancellation policy"); ?>
                <input style="float:left; margin:0 10px 0 0;" type="checkbox" name="cancel_policy" class="required" />

            </p>

            <p><?php echo translate("Type message to guest") . "..."; ?></p>

            <p><textarea name="comment" id="comment" class="required"></textarea></p>

            <p>
                <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>" >
                <input type="hidden" name="list_id" value="<?php echo $list_id; ?>" >
                <button name="cancel" type="submit" class="button1"><span><span><?php echo translate("Cancel"); ?></span></span></button>
            </p>
        </form>

    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $("#cancellationHost").validate({
            errorElement: "p",
            errorClass: "Frm_Error_Msg",
            focusInvalid: false,
            submitHandler: function (form)
            {
                var ok = confirm("Are you sure to cancel the reservation?");
                if (!ok)
                {
                    return false;
                }

                $.post("<?php echo site_url('hosting/cancel_host'); ?>", $("#cancellationHost").serialize(),
                        function (data)
                        {
                            window.location = "<?php echo site_url('hosting/my_reservation'); ?>";
                        }
                );
            }
        });
    })

</script>