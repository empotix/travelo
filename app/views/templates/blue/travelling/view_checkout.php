<div id="View_Checkin" class="Box">
    <div class="Box_Head1">
        <h2> <?php echo translate("Checkout"); ?> </h2>
    </div>
    <div class="Box_Content">
        <form id="checkin" name="checkin-trips" action="<?php echo site_url('travelling/checkout'); ?>" method="post">
            <p> <?php echo translate("Are you sure to checkout?"); ?> </p>
            <p> 
                <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>" >
                <button name="checkout" type="submit" class="button1"><span><span><?php echo translate("Checkout"); ?></span></span></button>
            </p>
        </form>
    </div>
</div>