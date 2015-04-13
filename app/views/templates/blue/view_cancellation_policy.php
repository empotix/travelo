<?php
    $this->db->select('*');
    $this->db->from('cancellation_policy');
    $query = $this->db->get();
    $cancellationDetails = $query->result();
    $policy = $this->uri->segment(3);
?>
<script src="<?php echo js_url(); ?>/home_page.js" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function () {

        var cancel_policy = document.getElementById("policy").value;

        if (cancel_policy == 'Strict')
        {

            $("#Box_Content1").hide();
            $("#Box_Content2").hide();
            $("#Box_Content3").show();
            $("#Box_Content4").hide();
            $("#Box_Content5").hide();
            $("#f1").hide();
            $("#f0").show();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").show();
            $("#s0").hide();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").hide();
            $("#l0").show();
        }
        else if (cancel_policy == 'Moderate')
        {
            $("#Box_Content1").hide();
            $("#Box_Content2").show();
            $("#Box_Content3").hide();
            $("#Box_Content4").hide();
            $("#Box_Content5").hide();
            $("#f1").hide();
            $("#f0").show();
            $("#m1").show();
            $("#m0").hide();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").hide();
            $("#l0").show();
        }
        else if (cancel_policy == 'Super%20Strict')
        {
            $("#Box_Content1").hide();
            $("#Box_Content2").hide();
            $("#Box_Content3").hide();
            $("#Box_Content4").show();
            $("#Box_Content5").hide();
            $("#f1").hide();
            $("#f0").show();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").show();
            $("#ss0").hide();
            $("#l1").hide();
            $("#l0").show();
        }
        else if (cancel_policy == 'Long%20Term')
        {
            $("#Box_Content1").hide();
            $("#Box_Content2").hide();
            $("#Box_Content3").hide();
            $("#Box_Content4").hide();
            $("#Box_Content5").show();

            $("#f1").hide();
            $("#f0").show();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").show();
            $("#l0").hide();
        }
        else
        {
            $("#Box_Content1").show();
            $("#Box_Content2").hide();
            $("#Box_Content3").hide();
            $("#Box_Content4").hide();
            $("#Box_Content5").hide();
            $("#f1").show();
            $("#f0").hide();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").hide();
            $("#l0").show();
        }
        $("#Flexible").click(function () {
            $("#Box_Content1").show();
            $("#Box_Content2").hide();
            $("#Box_Content3").hide();
            $("#Box_Content4").hide();
            $("#Box_Content5").hide();
            $("#f1").show();
            $("#f0").hide();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").hide();
            $("#l0").show();
        });
        $("#Moderate").click(function () {
            $("#Box_Content1").hide();
            $("#Box_Content2").show();
            $("#Box_Content3").hide();
            $("#Box_Content4").hide();
            $("#Box_Content5").hide();
            $("#f1").hide();
            $("#f0").show();
            $("#m1").show();
            $("#m0").hide();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").hide();
            $("#l0").show();
        });
        $("#Strict").click(function () {
            $("#Box_Content1").hide();
            $("#Box_Content2").hide();
            $("#Box_Content3").show();
            $("#Box_Content4").hide();
            $("#Box_Content5").hide();
            $("#f1").hide();
            $("#f0").show();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").show();
            $("#s0").hide();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").hide();
            $("#l0").show();
        });
        $("#SuperStrict").click(function () {

            $("#Box_Content1").hide();
            $("#Box_Content2").hide();
            $("#Box_Content3").hide();
            $("#Box_Content4").show();
            $("#Box_Content5").hide();
            $("#f1").hide();
            $("#f0").show();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").show();
            $("#ss0").hide();
            $("#l1").hide();
            $("#l0").show();
        });
        $("#LongTerm").click(function () {

            $("#Box_Content1").hide();
            $("#Box_Content2").hide();
            $("#Box_Content3").hide();
            $("#Box_Content4").hide();
            $("#Box_Content5").show();

            $("#f1").hide();
            $("#f0").show();
            $("#m1").hide();
            $("#m0").show();
            $("#s1").hide();
            $("#s0").show();
            $("#ss1").hide();
            $("#ss0").show();
            $("#l1").show();
            $("#l0").hide();
        });

    });
</script>
<input type="hidden" id="policy" value="<?php echo $this->uri->segment(3); ?>">
<div class="container_bg" id="View_Canellation_Policy">
    <div class="cancel_head_content">
        <h3><?php echo translate("Cancellation Policies"); ?></h3>
        <p><?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate("allows_hosts_to_choose"); ?>. </p>
    </div>
    <div class="Box">
        <div class="Box_Head">
            <ul id="Flexible">
                <li  id="f1" class="clsBg_None select"><a href="#"><?php echo translate("Flexible"); ?></a></li>
                <li  id="f0" class=""><a href="#"><?php echo translate("Flexible"); ?></a></li>
            </ul>
            <ul id="Moderate">
                <li id="m1" class="clsBg_None select"><a href="#"><?php echo translate("Moderate"); ?></a></li>
                <li id="m0" class=""><a href="#"><?php echo translate("Moderate"); ?></a></li>
            </ul>
            <ul id="Strict">
                <li id="s1" class="clsBg_None select"><a href="#"><?php echo translate("Strict"); ?></a></li>
                <li id="s0" class=""><a href="#"><?php echo translate("Strict"); ?></a></li>
            </ul>
            <ul id="SuperStrict">
                <li id="ss1" class="clsBg_None select"><a href="#"><?php echo translate("Super Strict"); ?></a></li>
                <li id="ss0" class=""><a href="#"><?php echo translate("Super Strict"); ?></a></li>
            </ul>
            <ul id="LongTerm">
                <li id="l1" class="clsBg_None select"><a href="#"><?php echo translate("Long Term"); ?></a></li>
                <li id="l0" class=""><a href="#"><?php echo translate("Long Term"); ?></a></li>
            </ul>
        </div>

        <?php foreach ($cancellationDetails as $cancellation)
            {
                ?>

                <div  id="Box_Content<?php echo $cancellation->id ?>" class="Box_Content">
                    <h4><?php echo $cancellation->cancellation_title; ?></h4>
                    <ul>
                        <li> <?php echo $cancellation->cancellation_content; ?></li>
                    </ul>
                    <?php if ($cancellation->id == 1)
                    {
                        ?>
                        <p style="text-align:center; margin:10px 0;"><img src="<?php echo css_url() . "/images"; ?>/cancel_policy_img.png" alt=""  />  </p>
                        <div id="cancel_policy_3Blk" class="clearfix">
                            <div class="cancel_policy_1 clsFloatLeft">
                                <p><?php echo translate("Must_be_made"); ?>.</p>
                            </div>
                            <div class="cancel_policy_2 clsFloatLeft">
                                <p><?php echo translate("If_guest"); ?>.</p>
                            </div>
                            <div class="cancel_policy_3 clsFloatLeft">
                                <p><?php echo translate("If_guest_2"); ?> .</p>
                            </div>
                            <div class="clear"></div>
                        </div>
                    <?php
                    }
                    else if ($cancellation->id == 2)
                    {
                        ?>

                        <p style="text-align:center; margin:10px 0;"><img src="<?php echo css_url() . "/images"; ?>/Moderate.png" alt=""  />  </p>
                        <div id="cancel_policy_3Blk" class="clearfix">
                            <div class="cancel_policy_1 clsFloatLeft">
                                <p><?php echo translate("For_full_refund"); ?> .</p>
                            </div>
                            <div class="cancel_policy_2 clsFloatLeft">
                                <p><?php echo translate("Half_refund"); ?>.</p>
                            </div>
                            <div class="cancel_policy_3 clsFloatLeft">
                                <p><?php echo translate("Half_refund_2"); ?>.</p>
                            </div>
                            <div class="clear"></div>
                        </div>
        <?php
        }
        else if ($cancellation->id == 3)
        {
            ?>

                        <p style="text-align:center; margin:10px 0;"><img src="<?php echo css_url() . "/images"; ?>/Strict.png" alt=""  />  </p>
                        <div id="cancel_policy_3Blk" class="clearfix">
                            <div class="cancel_policy_1 clsFloatLeft">
                                <p><?php echo translate("Half_refund_3"); ?>.</p>
                            </div>
                            <div class="cancel_policy_2 clsFloatLeft">
                                <p><?php echo translate("One_third_refund"); ?>.</p>
                            </div>
                            <div class="cancel_policy_3 clsFloatLeft">
                                <p><?php echo translate("One_third_2"); ?>.</p>
                            </div>
                            <div class="clear"></div>
                        </div>
        <?php
        }
        else if ($cancellation->id == 4)
        {
            ?>

                        <p style="text-align:center; margin:10px 0;"><img src="<?php echo css_url() . "/images"; ?>/super Strict.png" alt=""  />  </p>
                        <div id="cancel_policy_3Blk" class="clearfix">
                            <div class="cancel_policy_1 clsFloatLeft">
                                <p><?php echo translate("One_thrid_3"); ?>.</p>
                            </div>
                            <div class="cancel_policy_2 clsFloatLeft">
                                <p><?php echo translate("No_refund_last"); ?>.</p>
                            </div>
                            <div class="cancel_policy_3 clsFloatLeft">
                                <p><?php echo translate("If_arrives"); ?> .</p>
                            </div>
                            <div class="clear"></div>
                        </div>
        <?php
        }
        else
        {
            ?>
                        <p style="text-align:center; margin:10px 0;"><img src="<?php echo css_url() . "/images"; ?>/Long Term.png" alt=""  />  </p>
                        <div id="cancel_policy_3Blk" class="clearfix">

                            <div class="cancel_policy_1 clsFloatLeft">

                            </div>
                            <div class="cancel_policy_2 clsFloatLeft">
                                <p><?php echo translate("If the guest books"); ?>.</p>
                            </div>
                            <div class="cancel_policy_3 clsFloatLeft">
                                <p><?php echo translate("If guest books long term"); ?>.</p>
                            </div>
                            <div class="clear"></div>
                        </div>
        <?php } ?>
                </div>
    <?php } ?>
    </div>
</div>