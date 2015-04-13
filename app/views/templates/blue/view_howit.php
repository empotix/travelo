<script src="<?php echo js_url(); ?>/swfobject.js" type="text/javascript"></script>
<script src="<?php echo js_url(); ?>/jwplayer.js" type="text/javascript"></script>
<div class="container_bg1 container_bg_non" id="View_HowIt" style="padding:0!important;">
    <div class="How_It_VideoBg">
        <?php if ($display_type == 0)
            {
                ?>
                <div id="mediaplayer"><?php echo translate("JW Player goes here"); ?></div>
                <?php
            }
            else
            {
                echo $embed_code;
            }
        ?>
    </div>
    <div id="How_It_Blk" class="clearfix">
        <div class="How_It1 clsFloatLeft">
            <h2><?php echo translate("Find a place"); ?></h2>
            <div class="Howit_Img"><img src="<?php echo css_url(); ?>/images/find_places.png" /></div>
            <p><a href="<?php echo site_url('search'); ?>" class="clsLink2_Bg_green"><?php echo translate("Search"); ?></a></p>
        </div>
        <div class="How_It2 clsFloatLeft">
            <h2><?php echo translate("Add your Place"); ?></h2>
            <div class="Howit_Img"><img src="<?php echo css_url(); ?>/images/add_place.png" /></div>
            <p><a href="<?php echo site_url('rooms/new'); ?>" class="clsLink3_Bg"><?php echo translate("List Your Space"); ?></a></p>
        </div>
        <div class="How_It3 clsFloatLeft">
            <h2><?php echo translate("Why Host"); ?></h2>
            <div class="Howit_Img"><img src="<?php echo css_url(); ?>/images/why_hosts.png" /></div>
            <p><a href="<?php echo site_url('pages/view') . '/why_host'; ?>" class="clsLink4_Bg"><?php echo translate("Learn More_how"); ?></a></p>
        </div>
    </div>
</div>
<script type="text/javascript">
    jwplayer("mediaplayer").setup({
        flashplayer: "<?php echo base_url(); ?>uploads/howit/player.swf",
        file: "<?php echo base_url(); ?>uploads/howit/<?php echo $media; ?>",
                height: 429,
                width: 885
            });
</script>
