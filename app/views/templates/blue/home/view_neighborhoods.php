
<script>
    $(function () {
        // Slideshow 4
        $("#slider4").responsiveSlides({
            auto: true,
            pager: false,
            nav: true,
            speed: 500,
            namespace: "callbacks",
            before: function () {
                $('.events').append("<li>before event fired.</li>");
            },
            after: function () {
                $('.events').append("<li>after event fired.</li>");
            }
        });

    });
</script>

<?php echo '
 <div class="callbacks_container">
<div style="position:relative;">
		<div style="position:absolute; top:40px; left:375px; z-index:989;">
        		<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top"><h1 class="neighborhoods">'; ?><?php echo translate("Neighborhoods"); ?><?php echo '</h1></td>
  </tr>
  <tr>
    <td><div class="center_nei">
<ul class="nei_country">'; ?>
<?php
    if ($cities->num_rows() != 0)
    {
        foreach ($cities->result() as $city)
        {
            ?>
            <li><ul class="nei_country_line">

                        <!--<a href="<?php echo site_url() . "rooms/" . $row->id; ?>"></a>-->
                    <li>
                        <a href='<?php echo site_url() . "neighbourhoods/city/" . $city->id; ?>'>
                            <?php echo $city->city_name; ?></a>
                        <?php
                        $city_created = $this->db->where('city_name', $city->city_name)->get('neigh_city')->row()->created;
                        $month = 60 * 60 * 24 * 30; // month in seconds
                        if (time() - $city_created < $month)
                        {
                            // within the last 30 days ...
                            echo '<span>' . translate("New") . '</span>';
                        }
                        ?></li>
                </ul></li>

        <?php } ?>
        <?php
    }
    else
    {
        ?>
        <?php //echo '<h1 class="neighborhoods" style="font-size:10px">'.translate("No Neighbourhood Places").'</h1>';  ?>
    <?php } ?>
</ul>
</div>
</td>
</tr>
</table></div></div>
<ul class="rslides" id="slider4">
    <?php
        foreach ($lists->result() as $row)
        {
            $url = base_url() . 'images/' . $row->list_id . '/' . $row->name;
            $profile_pic = $this->Gallery->profilepic($row->user_id, 2);
            $city = explode(',', $row->address);
            ?>
            <li>
                <img src="<?php echo $url; ?>" alt="" style="height:764px!important;">
                <div class="caption">
                    <div class="room_head">
                        <strong>
                            <span> <a href="<?php echo base_url() . 'search?location=' . $city['2']; ?>"><?php echo $city['2'] . ','; ?></a> </span>
                            <span> <a href="<?php echo base_url() . 'search?location=' . $row->country; ?>"><?php echo $row->country; ?></a> </span>

                        </strong>
                    </div>
                </div>
            </li>
        <?php } ?>
</ul>
</div>

