<div id="View_Checkin" class="Box">
    <div class="Box_Head1">
        <h2> <?php echo translate("Checkin"); ?> </h2>
    </div>
    <div class="Box_Content">
        <form id="checkin" name="checkin-trips" action="<?php echo site_url('travelling/checkin'); ?>" method="post">
            <p> <?php echo translate("Are you sure to checkin?"); ?> </p>
            <p> 
                <input type="hidden" name="reservation_id" value="<?php echo $reservation_id; ?>" >
                <button name="checkin" type="submit" class="button1"><span><span><?php echo translate("Checkin"); ?></span></span></button>
            </p>
        </form>
    </div>
</div>