<script type="text/javascript">
    function startCallback() {
        document.getElementById('message').innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback(response) {
        if (response.length > 75)
        {
            window.location.href = "<?php echo base_url() . 'administrator'; ?>";
        }
        else
        {
            document.getElementById('message').innerHTML = response;
        }
    }
    $(function () {
        $(':text').keydown(function (e) {
            if (e.shiftKey || e.ctrlKey || e.altKey) {
                e.preventDefault();
            } else {
                var key = e.keyCode;
                if (!((key == 8) || (key == 46) || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
<div id="Accomondation">
    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('payment/paymode'); ?>"><b><?php echo translate_admin('View All'); ?></b></a></li>
            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin('Edit Guest Booking commission'); ?></h3>
        </div>
    </div>

    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>

    <form action="<?php echo admin_url('payment/paymode'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">	
        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName accom_width"><?php echo translate_admin('Is Active?'); ?><span class="clsRed">*</span></td>
                <td style="padding: 0.6em 0 0.6em 4px;">
                    <select name="is_premium" class="usertype" id="is_premium">
                        <option value="0"> No </option>
                        <option value="1"> Yes </option>
                    </select> 
                </td>
            </tr>

            <?php
                if ($result->is_premium == 0)
                {
                    $show = 'none';
                }
                else
                {
                    $show = 'block';
                }
            ?>


            <table class="table" id="showhide" style="display:<?php echo $show; ?>;">
                <tr>
                    <td class="clsName accom_width"><?php echo translate_admin('Promotion Type'); ?></td>
                    <td style="padding: 0.6em 0 0.6em 4px;"> <input type="radio" <?php if ($result->is_fixed == 1) echo 'checked="checked"'; ?> name="is_fixed" onclick="javacript:showhideF(this.value);" value="1"> Fixed Pay</td>
                    <td> <input type="radio" <?php if ($result->is_fixed == 0) echo 'checked="checked"'; ?> name="is_fixed" onclick="javacript:showhideF(this.value);" value="0"> Percentage Pay</td>
                </tr>

                <?php
                    if ($result->is_fixed == 1)
                    {
                        $showF = 'block';
                        $showP = 'none';
                    }
                    else
                    {
                        $showF = 'none';
                        $showP = 'block';
                    }
                ?>	


                <tr id="fixed" style="display:<?php echo $showF; ?>;width:165%">
                    <td class="clsName accom_width1"><?php echo translate_admin('Fixed Amount'); ?><span class="clsRed">*</span>
                        <p class="recommend">(Recommend the Percentage Pay. Because of, Guest booking amount some times occurring in minus values.)</p>
                    </td>
                    <td> <input type="text" name="fixed_amount" value="<?php echo $result->fixed_amount; ?>"></td>
                </tr>		

                <tr id="percentage" style="display:<?php echo $showP; ?>;width:165%">
                    <td class="clsName accom_width1"><?php echo translate_admin('Percentage Amount'); ?><span class="clsRed">*</span></td>
                    <td style="padding: 0.6em 0 0 9px;"> <input type="text" name="percentage_amount" id="percentage_amount" value="<?php echo $result->percentage_amount; ?>">%</td>
                </tr>			
            </table>

            <tr>
                <td></td>
                <td>
                    <input type="hidden" name="payId" value="<?php echo $payId; ?>" />
                    <div class="clearfix">
                        <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update" value="<?php echo translate_admin('Update'); ?>" style="width:90px;" /></span>
                        <span style="float:left;"><div id="message"></div></span>
                    </div>
                </td>
            </tr>

        </table>	
        <?php echo form_close(); ?>

</div>

<script language="Javascript">
    jQuery("#is_premium").val('<?php echo $result->is_premium; ?>');

    function showpremium(id)
    {
        if (id == 1)
        {
            document.getElementById("showhide").style.display = "block";
        }
        else
        {
            document.getElementById("showhide").style.display = "none";
        }
    }

    function showhideF(id)
    {
        if (id == 1)
        {
            document.getElementById("fixed").style.display = "block";
            document.getElementById("percentage").style.display = "none";
        }
        else
        {
            document.getElementById("fixed").style.display = "none";
            document.getElementById("percentage").style.display = "block";
        }
    }
</script>
