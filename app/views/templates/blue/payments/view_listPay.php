<!-- Included Style Sheets -->
<link href="<?php echo css_url() . '/checkout.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<script src="<?php echo js_url(); ?>/prototype.js" type="text/javascript"></script>
<!-- End of style sheet inclusion -->
<script src="<?php echo js_url(); ?>/tooltips_good.js" type="text/javascript"></script>
<script src="<?php echo js_url(); ?>/tabber-minimized.js" type="text/javascript"></script>
<style type="text/css">
    span.optional_usd {
        display:none !important;
    }
</style>
<div id="book_it" class="container_bg"> 

    <?php if ($full_cretids == 'off')
        {
            $id = $this->session->userdata('Lid'); ?>
            <?php echo form_open('listpay/payment/' . $id, array("id" => "PaymentsForm")); ?>
            <?php
            $value = '';


            if ($result[0]->is_enabled == 1)
            {
                $showP = '';
                if ($value == '')
                {
                    $value = 'paypal';
                }
            }
            else
            {
                $showP = ' payment_method_hidden';
            }
            ?>
            <div id="payment_options" class="Box bookit_Box">
                <div class="Box_Head bookhead">
                    <h2><?php echo translate("Payment options"); ?> <span id="country_name" style="display:none;"></span></h2>
                </div>
                <div class="payment_tabs">

                    <div id="payment_method_paypal" class="payment_method_content<?php echo $showP; ?>" title="">
                        <input type="hidden" value="USD" name="payment_method_paypal_currency" id="payment_method_paypal_currency">
                        <h2><?php echo translate("PayPal"); ?></h2>

                        <div class="currency_alert"><?php echo translate("This payment transacts in"); ?> <?php echo get_currency_symbol1() . get_currency_code(); ?>. <?php echo translate("Your total charge is"); ?> <?php echo get_currency_symbol1() . get_currency_value($amt); ?>.</div>


                        <p class="payment_method_explanation paypal_explanation"> <span style="font-weight:bold;"><?php echo translate("Instructions"); ?>:</span> <br>
        <?php echo translate("After clicking 'Create List' you will be redirected to PayPal to complete payment."); ?> <span style="font-weight:bold;"> <?php echo translate("You must complete the process or the transaction will not occur."); ?> </span> </p>
                        <div class="clear"></div>
                    </div>


                </div>
                <div class="book_it_section" style="padding-bottom:10px;">
                    <p>
                        <input type="button" name="book_it_button" id="p4_book_it_button" class="btn large blue" value="<?php echo translate("Create List"); ?>"  />
                    </p>

                </div>
            </div>


            <input type="hidden" value="<?php echo $value; ?>" name="payment_method" id="payment_method">


        <?php echo form_close(); ?>
    <?php }
    else
    { ?>
            <div id="policies" class="Box">
                <div class="Box_Head bookhead">
                    <h1><?php echo translate("4. Policies"); ?></h1>
                </div>
                <div class="Box_Content"> 
                    <ul class="dashed_table">
                        <li class="top"> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_crossed_out"></span><?php echo translate("Cancellation"); ?></span> </span> <span class="data"> <span class="inner"> <a href="<?php echo site_url('pages/cancellation_policy'); ?>" target="_blank"><?php echo translate("Flexible"); ?></a> </span> </span> </li>
                        <li class="bottom"> <span class="label"> <span class="inner"><span class="checkout_icon" id="icon_house"></span><?php echo translate("House Rules"); ?></span> </span> <span class="data"> <span class="inner"> <a href="#" onclick="show_super_lightbox('house_rules_data');
                                return false;" target="_blank"><?php echo translate("Read Policy"); ?></a> <a href="#" onclick="return false;"></a> </span> </span> </li>
                    </ul>
                    <div class="clear"></div>

                </div>
            </div>
            <p id="book_it_fine_print" style='width:592px; overflow:hidden;'>
                <input type="checkbox" id="agrees_to_terms" name="agrees_to_terms"/>
                <span><?php echo translate("I agree to the cancellation policy and house rules."); ?></span></p>
        <?php echo form_open('referrals/booking/' . $this->uri->segment(3)); ?>
            <input type="hidden" value=<?php echo $checkin; ?> name="checkin" />
            <input type="hidden" value=<?php echo $checkout; ?> name="checkout" />
            <input type="hidden" value=<?php echo $guests; ?> name="number_of_guests" />
            <p>
                <input type="button" name="book_it_button" id="p4_book_it_button" class="Butt_Normal" value="<?php echo translate("Create List"); ?>"  />
            </p>
        <?php echo form_close(); ?>
    <?php } ?>
    <!-- /Form -->
</div>
<!-- #book_it -->
<!-- .narrow_page_bg -->

<div id="house_rules_data" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('house_rules_data');
        return false;">
        <?php echo "[X] Close"; ?>
    </a>
    <h3><?php echo translate("House Rules"); ?></h3>
    <h4><?php echo $manual; ?></h4>
</div>
<div id="security_deposit_data" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('security_deposit_data');
        return false;">
<?php echo "[X] Close"; ?>
    </a>
    <p style="margin:10px 0;"> <?php echo translate("If the host reports a problem, we will capture the entire authorized amount while we gather additional information from both parties. If a compromise is reached, we will refund the agreed upon amount. Although it is primarily up to the host to determine the extent of the damage, We tracks every claim
that is made, and if a host develops a trend of claiming damages in order to keep the security deposit, the host may removed from " . $this->dx_auth->get_site_title() . "."); ?> </p>
</div>
<div id="pricing_explanation" class="super_lightbox"> <a class="hide_super_lightbox" href="javascript:void(0);" onclick="hide_super_lightbox('pricing_explanation');
        return false;">
<?php echo "[X] Close"; ?>
    </a>
    <h3><?php echo translate("Subtotal Breakdown"); ?></h3>
    <p style="margin: 10px 0 0 0;"> 
<?php echo translate("A variety of factors contribute to how the subtotal is calculated. Below is a detailed explanation."); ?> </p>
</div>
<div id="transparent_bg_overlay"></div>
<script type="text/javascript">
    var alreadySubmitted = false;
    function clean_up_and_book_it() {

        if (alreadySubmitted === false) {

            //$('p4_book_it_button').disabled = 'disabled';
            $('p4_book_it_button').setStyle({cursor: 'progress'});
            $('book_it_click_message').show();

            for (x = 0; x < fields_to_clear_on_submit.size(); x++) {
                f = fields_to_clear_on_submit[x];
                if (f.defaultValue == f.value) {
                    f.value = '';
                }
            }

            $('book_it_form').submit();

            alreadySubmitted = true;
        } else {
            //this is a double submit
            return false;
        }

    }
    function update_payment_options(country_code) {
        $('ajax_method_spinner').show();

        //alert("update_payment_options");

        while (global_tabber_obj.tabs.size() > 1) {
            removeLastTab();
        }

        $('payment_method').value = payment_method_value;

        $('credit_card_country').value = country_code;
    }

    function apply_error_class_to_ajax_fields_with_errors() {
        error_count = ajax_fields_with_errors.size();
        for (i = 0; i < error_count; i++) {
            if ($(ajax_fields_with_errors[i])) {
                $(ajax_fields_with_errors[i]).addClassName('error');
            }
        }

    }

    function update_labels_if_js() {
        move_all_labels_to_adjacent_inputs();
        remove_if_js();
    }

    function select_active_tab() {
        if ($('payment_method_tab_link_' + payment_method_value)) {
            $('payment_method_tab_link_' + payment_method_value).onclick();
        }
        else {
            //default to showing CC instead of nothing
            $('payment_method_tab_link_<?php echo $value; ?>').onclick();
        }
    }

    function update_p4_book_it_button(method) {
        $('p4_book_it_button').value = "Create List using" + " " + method;
    }

    function move_all_labels_to_adjacent_inputs() {
        $$('label.inner_text').each(function (e) {
            move_label_to_input(e);
        });
    }

    function move_label_to_input(e) {
        input_field = e.next('input');

        user_value = input_field.value;
        input_field.defaultValue = e.innerHTML;
        input_field.value = e.innerHTML;

        //if(input_field.value.length == 0){
        if (user_value.length == 0) {
            //input_field.defaultValue = e.innerHTML;
        } else {
            input_field.value = user_value;
            input_field.addClassName('active');
        }

        input_field.observe('focus', function () {
            if (this.value == this.defaultValue) {
                this.value = '';
                this.addClassName('active');
            }
            this.removeClassName('error');
        });
        input_field.observe('blur', function () {
            if (this.value == '') {
                this.value = this.defaultValue;
                this.removeClassName('active');
            } else {
                this.removeClassName('error');
            }
        });

        fields_to_clear_on_submit.push(input_field);
        e.remove();
    }

    function remove_if_js() {
        $$('.remove_if_js').each(function (e) {
            e.remove();
        });
    }

    jQuery('document').ready(function () {
        //  $('p4_book_it_button').disabled = 'disabled';

        fields_to_clear_on_submit = new Array();

        //these are fields that need to be given class="error" after a new payment method is loaded via ajax
        ajax_fields_with_errors = new Array();

        move_all_labels_to_adjacent_inputs();
        remove_if_js();

        payment_options_tabber_obj = tabberAutomatic();
<?php if ($result[0]->is_enabled == 1)
    { ?>
                addTab('Paypal', 'payment_method_paypal', 'paypal');
    <?php } ?>

        //This will show CC right away
        select_active_tab();

        $('payment_method').value = payment_method_value;
        jQuery('#existing_cc').change();

        //update_payment_options('IN');


        jQuery('#coupon_code').blur(function () {
            jQuery('#coupon_code').removeClass("coupon_focus");
        }).focus(function () {
            jQuery('#coupon_code').addClass("coupon_focus")
        });

        jQuery('input').live('keypress', function (e) {
            if (e.keyCode == 13) {
                if (jQuery('#coupon_code').hasClass('coupon_focus')) {
                    $('submit_context').value = 'apply_coupon';
                    clean_up_and_book_it();
                } else {
                    $('submit_context').value = 'book_it_button';
                    clean_up_and_book_it();
                }
                return false;
            }
        });
        //alert('hi'); return false;				
        jQuery('#p4_book_it_button').click(function () {

        });

        jQuery('#p4_book_it_button').click(function () {

            var flag = 0;
            if (jQuery('#payment_method').val() == 'cc')
            {
                if (jQuery('#firstName').val() == "")
                {
                    flag = 0;
                    jQuery('#EfirstName').show();
                }
                else
                {
                    jQuery('#EfirstName').hide();
                    flag = 1;
                }

                if (jQuery('#lastName').val() == "")
                {
                    flag = 0;
                    jQuery('#ElastName').show();
                }
                else
                {
                    jQuery('#ElastName').hide();
                    flag = 1;
                }

                if (jQuery('#creditCardNumber').val() == "")
                {
                    flag = 0;
                    jQuery('#EcreditCardNumber').show();
                }
                else
                {
                    jQuery('#EcreditCardNumber').hide();
                    flag = 1;
                }

                if (jQuery('#cvv2Number').val() == "")
                {
                    flag = 0;
                    jQuery('#Ecvv2Number').show();
                }
                else
                {
                    jQuery('#Ecvv2Number').hide();
                    flag = 1;
                }

                if (jQuery('#address1').val() == "")
                {
                    flag = 0;
                    jQuery('#Eaddress1').show();
                }
                else
                {
                    jQuery('#Eaddress1').hide();
                    flag = 1;
                }

                if (jQuery('#city').val() == "")
                {
                    flag = 0;
                    jQuery('#Ecity').show();
                }
                else
                {
                    jQuery('#Ecity').hide();
                    flag = 1;
                }

                if (jQuery('#state').val() == "")
                {
                    flag = 0;
                    jQuery('#Estate').show();
                }
                else
                {
                    jQuery('#Estate').hide();
                    flag = 1;
                }

                if (jQuery('#zip').val() == "")
                {
                    flag = 0;
                    jQuery('#Ezip').show();
                }
                else
                {
                    jQuery('#Ezip').hide();
                    flag = 1;
                }
            }
            else
            {
                flag = 1;
            }

            if (flag == 1)
            {
                jQuery('#PaymentsForm').submit();
            }

        });

    });

    var payment_method_value = '<?php echo $value; ?>';



</script>