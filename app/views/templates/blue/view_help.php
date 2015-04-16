<link href="<?php echo css_url() . '/common.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<link href="<?php echo css_url() . '/help.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<div class="need_top_part">
    <div>
        <b><?php echo translate('Help Center'); ?></b>
        <a href="<?php echo base_url() . 'home/help/' . $page_refer; ?>"><?php echo translate('Home'); ?></a>
        <a href="<?php echo base_url() . 'home/help/2'; ?>"><?php echo translate('Guide'); ?></a>
        <a href="<?php echo base_url() . 'home/help/3'; ?>"><?php echo translate('Dashboard'); ?></a> 
        <a href="<?php echo base_url() . 'home/help/7'; ?>"><?php echo translate('Account'); ?></a> 
    </div>
    <div style="clear:both;"></div>

    <div class="container">
        <div class="search_help col-md-6 col-md-offset-3"><input type="text" class="help_searchbox" placeholder="Search the help center" style="width: 100%"/></div>
    </div>
    <div class="need_top_part_b_whole">
        <div class="container">
            <div class="need_top_part_breadcrumb"><?php echo translate('Help Center'); ?> > <span><?php echo $page_refer; ?></span> </div>
        </div>
    </div>
</div>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<!--<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>-->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(function () {

            $(".help_searchbox").autocomplete({
                source: function (request, response) {
                    $.ajax({url: "<?php echo base_url() . 'home/help_autocomplete'; ?>",
                        data: 'val=' + $(".help_searchbox").val(),
                        dataType: "json",
                        type: "GET",
                        success: function (data) {
                            response(data);
                            //alert(data);
                        }
                    });
                },
                select: function (event, ui)
                {
                    //alert(ui.item.value); 
                    $.ajax({url: "<?php echo base_url() . 'home/help_id'; ?>",
                        data: 'val=' + ui.item.value,
                        //dataType: "json",
                        type: "GET",
                        success: function (data) {
                            //response(data);
                            //alert(data);
                            window.location.href = '<?php echo base_url() . 'home/help/'; ?>' + data;
                        }
                    });
                },
                minLength: 2
            });
        });
    });
</script>
<style>
    ul.ui-autocomplete li.ui-menu-item
    {
        text-align:left;

    }
</style>

<div class="container">
    <div class="row">
        <div class="middle_part_whole">
            <div style="color: #333;" class="clearfix">
                <div class="col-md-4">
                    <ul class="need_back">
                        <li><?php echo translate('Questions'); ?></li>


                        <?php
                            if ($result->num_rows() != 0)
                            {
                                foreach ($result->result() as $row)
                                {
                                    $stat = $row->status;

                                    $help_question = $row->question;
                                    $help_id = $row->id;
                                    //if($row->page_refer != 'guide')
                                    //{
                                    ?>
                                    <li><a href="<?php echo base_url() . 'home/help/' . $row->id; ?>" class="unselect"> <?php echo "$help_question"; ?> <?php //}     ?></a></li>

                                    <?php
                                }
                            }
                            else
                            {
                                ?>
                                <li><a href="<?php echo base_url() . 'home/help/' . $row->id; ?>" class="unselect"> <?php echo "$question"; ?> </a></li>

                            <?php }
                        ?>

                    </ul>
                </div>
                <div class="col-md-8">
                    <h2><?php
                            if ($question)
                            {
                                echo $question;
                            }
                            else
                            {
                                echo translate('No Helps');
                            }
                        ?></h2>
                    <div class="steps"> <div class="steps_l">

                            <p> <?php echo $description; ?> </p>

                        </div>

                        <div class="clear"> <?php
                                if (!$description)
                                {

                                    echo translate('No Results Found');
                                }
                            ?>
                        </div>  
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
