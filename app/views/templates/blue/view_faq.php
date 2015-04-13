<link href="<?php echo css_url(); ?>/accordion_toggle.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo js_url(); ?>/combine.php"></script>
<script type="text/javascript">

    //
    //  In my case I want to load them onload, this is how you do it!
    // 
    Event.observe(window, 'load', loadAccordions, false);

    //
    //	Set up all accordions
    //
    function loadAccordions() {
        var topAccordion = new accordion('horizontal_container', {
            classNames: {
                toggle: 'horizontal_accordion_toggle',
                toggleActive: 'horizontal_accordion_toggle_active',
                content: 'horizontal_accordion_content'
            },
            defaultSize: {
                width: 525
            },
            direction: 'horizontal'
        });

        var bottomAccordion = new accordion('vertical_container');

        var nestedVerticalAccordion = new accordion('vertical_nested_container', {
            classNames: {
                toggle: 'vertical_accordion_toggle',
                toggleActive: 'vertical_accordion_toggle_active',
                content: 'vertical_accordion_content'
            }
        });

        // Open first one
        bottomAccordion.activate($$('#vertical_container .accordion_toggle')[0]);

        // Open second one
        topAccordion.activate($$('#horizontal_container .horizontal_accordion_toggle')[2]);
    }

</script>
<div class="container_bg_faq inner_pad_top" id="View_Faq">
    <div class="Box">
        <div class="Box_Head">
            <h2><?php echo translate("Frequently Asked Questions"); ?></h2>
        </div>
        <div id="vertical_container">
            <?php
                $i = 1;
                if (isset($faqs) and $faqs->num_rows() > 0)
                {
                    foreach ($faqs->result() as $faq)
                    {
                        if ($faq->status == 1)
                        {
                            ?>
                            <h1 class="accordion_toggle"><?php echo $faq->question; ?> </h1>
                            <div style="height: 0px; display: none;" class="accordion_content">
                                <?php echo $faq->faq_content; ?>
                                <div id="horizontal_container"></div>
                            </div>
                            <?php
                        }
                        else
                        {
                            echo '';
                        }
                    }//Foreach End
                }//If End
                else
                {
                    echo '<p>' . translate('') . '</p>';
                }
            ?>
        </div>

    </div>
</div>
<script type="text/javascript">
    var verticalAccordions = $$('.accordion_toggle');
    verticalAccordions.each(function (accordion) {
        $(accordion.next(0)).setStyle({
            height: '0px'
        });
    });

</script>
<script type="text/javascript" src="<?php echo js_url(); ?>/urchin.js"></script>
<script type="text/javascript">_uacct = "UA-624845-2";
    urchinTracker();</script>
