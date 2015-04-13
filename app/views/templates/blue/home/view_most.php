<?php
    if ($mosts->num_rows() != 0)
    {
        foreach ($mosts->result() as $row)
        {
            $profpic = $this->Gallery->profilepic($row->user_id, 1);

            $overall = getreviewoflist($row->user_id, $row->id);
            ?>

            <div class="clsSub_Most_View_Blk clearfix">
                <div class="Sub_Most_View_Lft clsFloatLeft">
                    <a href="<?php echo site_url() . "rooms/" . $row->id; ?>">
                        <img src="<?php echo getListImage($row->id); ?>" alt="<?php echo $row->title; ?>" title="<?php echo $row->title; ?>" height="77" width="116" />
                    </a>
                    <p><?php echo $row->review; ?> review</p>
                </div>
                <div class="Sub_Most_View_Rgt clsFloatRight">
                    <p><a title="<?php echo $row->title; ?>" href="<?php echo site_url() . "rooms/" . $row->id ?>"><?php echo substr($row->title, 0, 25); ?></a></p>
                    <p> <?php echo get_currency_symbol($row->id) . get_currency_value1($row->id, $row->price) . ' per night'; ?> </p>
                    <p><img src="<?php echo $profpic; ?>" height='30' width="30" alt="Thump" /></p>
                </div>
            </div>
        <?php
        }
    }
    else
    {
        ?>

        <p> <?php echo translate("Sorry! No records found."); ?> </p>
        <br>

    <?php } ?>
<div class="clear"></div>