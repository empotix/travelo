<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER . '/includes/account_header'); ?>
<div id="wishlist_container">
    <?php
        $shortlists = explode(",", $shortlist);
        $limit_chk_unique = (array_unique($shortlists));
        $limit = count($limit_chk_unique);
        for ($i = 0; $i < $limit; $i++)
        {
            $query = $this->Users_model->get_list_by_id('list', 'id', $shortlists[$i]);
            $photo = $this->Users_model->get_list_by_id('list_photo', 'list_id', $shortlists[$i]);
            if ($query)
            {

                if ($shortlists[0] != '')
                {
                    ?> <div class="css_wishlist" style="overflow: hidden;padding:10px;"> 
                        <div class="wish_image" style="width:200px; float:left;"><a href="<?php echo base_url(); ?>rooms/<?php echo $query["id"]; ?>" class="image_link" title="<?php echo $query["id"]; ?>">
                                <?php if (count($photo) > 0)
                                {
                                    ?>
                                    <img class="search_thumbnail" title=<?php echo $query["title"]; ?> src="<?php echo base_url(); ?>images/<?php echo $query["id"]; ?>/<?php echo $photo["name"]; ?>" width=180 height=162 alt=<?php echo $query["title"]; ?>>
                                <?php
                                }
                                else
                                {
                                    echo '<div class="image-placeholder"><img alt="Room_default_no_photos" height="162" src="' . base_url() . 'images/no_image.jpg" width="180" /></div>';
                                }
                                ?>
                                </div>
                                <div class="Wish_title"> <a href="<?php echo base_url(); ?>rooms/<?php echo $query["id"]; ?>" class="image_link" title="<?php echo $query["id"]; ?>"><?php echo $query["title"]; ?>
                                    </a></div>
                <?php //echo $query["user_id"];   ?> </td>
                                <div class="wish_address"><?php echo $query["address"]; ?> </div>
                                <div class="wish_address"><?php echo get_currency_symbol($query["id"]) . get_currency_value1($query["id"], $query["price"]); ?> </div>
                                <div class="wish_remove">
                                    <a title="Remove My Wishlist" href="<?php echo base_url() . 'account/remove_my_shortlist/' . $query["id"]; ?>" onclick="return confirm('Are you sure to Remove your Wish list?');">
                                        <img src="<?php echo base_url(); ?>/images/Delete.png" alt="Remove My Wishlist" title="Remove My Wishlist" /></a>
                                </div> </div>
                        <?php
                    }
                    else
                    {
                        echo '<div class="no_wishlist" style="padding:50px;text-align:center;">' . translate("No Wishlist") . '</div>';
                    }
                }
            } // For
        ?>
    </div>