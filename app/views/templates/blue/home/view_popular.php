<?php
    $shortlists = explode(",", $shortlist);  //shortlistsplited using explode
    $limit_chk_unique = (array_unique($shortlists));  // unique data's from shortlists
    $limit = count($limit_chk_unique);
    echo '<div class="image-placeholder-popular">
	  <ul class="popular_whole"> <li> <ul class="popular_whole_img">';
    for ($i = 0; $i < $limit; $i++)
    {
        $query = $this->Users_model->get_list_by_id('list', 'id', $shortlists[$i]); //get list table data's
        if ($query == TRUE)
        {
            $city = explode(',', $query['address']);
            $photo = $this->Users_model->get_list_by_id('list_photo', 'list_id', $shortlists[$i]); //get photo name from list_photo table
            if ($shortlists[0] != '')
            {
                if (count($photo) > 0) //condition for if empty photo
                {
                    $url = base_url() . 'images/' . $query['id'] . '/' . $photo['name'];
                    ?>
                    <li class="row wishlists-list-item">

                        <?php
                        $this->path = realpath(APPPATH . '../images');
                        $dir_url = $this->path . '/' . $query['id'];
                        if (is_dir($dir_url))
                        {
                            if (file_exists($dir_url . '/' . $photo['name']))
                            {
                                ?>
                                <?php echo '<li><a href="' . site_url() . 'rooms/' . $query['id'] . '">'; ?> <div style="position:relative;"><img src="<?php echo $url; ?>" height=183 width=275  alt=<?php echo $query["title"]; ?> >
                                <?php
                            }
                            else
                            {
                                echo '<li><a href="' . site_url() . 'rooms/' . $query['id'] . '">';
                                ?> <div style="position:relative;"><img src="<?php echo base_url() . 'images/no_image.jpg'; ?>" height=183 width=275 alt=<?php echo $query["title"]; ?> >
                                    <?php
                                }
                            }
                            else
                            {
                                echo '<li><a href="' . site_url() . 'rooms/' . $query['id'] . '">';
                                ?> <div style="position:relative;"><img src="<?php echo base_url() . 'images/no_image.jpg'; ?>" height=183 width=275 alt=<?php echo $query["title"]; ?> >
                                <?php }
                                ?>
                                    <div style="position:absolute; bottom:0; left:0;">
                                        <table width="500" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="left" valign="bottom" width="75%">
                                                    <?php
                                                    echo '<div class="pop_img_h">' . $query['title'] . '</div>';
                                                    echo '<div class="pop_img_h_place">' . $city['2'] . ',' . $query['country'] . '</div>';
                                                    ?>
                                                </td>
                                                <td align="right" valign="bottom" width="25%">
                                                    <div class="pop_img_h_dollar" style="position:absolute: right:0; bottom:0;">
                                                        <div class="pop_doll">
                                                            <?php echo '<p class="dollor_symbol">' . get_currency_symbol($query['id']) . '</p><p class="dollor_price">' . get_currency_value1($query['id'], $query['price']); ?>
                                                            <p class="per_night"><?php echo translate('per night'); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div></div></a>
                                </li>							 



                                <?php
                            }
                            else
                            {
                                ?><li class="row wishlists-list-item">
                                    <?php echo '<li><a href="' . site_url() . 'rooms/' . $query['id'] . '">'; ?> <div style="position:relative;"><img src="<?php echo base_url() . 'images/no_image.jpg'; ?> height=183 width=275   alt=<?php echo $query["title"]; ?> >
                                                                         <div style="position:absolute; bottom:0; left:0;">
                                                                         <table width="500" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="left" valign="bottom" width="75%">
                                                    <?php
                                                    echo '<div class="pop_img_h">' . $query['title'] . '</div>';
                                                    echo '<div class="pop_img_h_place">' . $city['2'] . ',' . $query['country'] . '</div>';
                                                    ?>
                                                </td>
                                                <td align="right" valign="bottom" width="25%">
                                                    <div class="pop_img_h_dollar" style="position:absolute: right:0; bottom:0;">
                                                        <div class="pop_doll">
                                                            <?php echo '<p class="dollor_symbol">' . get_currency_symbol($query['id']) . '</p><p class="dollor_price">' . get_currency_value1($query['id'], $query['price']); ?>
                                                            <p class="per_night"><?php echo translate('per night'); ?></p>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div></div></a>
                    </li>							 
                    <?php
                }
            }
        }
    }
    echo '</ul>
			</li> 
	  		 
			<li style="clear:both;"></li>
	  </ul>';
?>