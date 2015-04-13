
<div id="View_Contact" class="container_bg">
    <!-- BEGIN STATIC LAYOUT -->

    <div class="Box">
        <div class="Box_Head">
            <h2><?php echo translate("Contact us"); ?></h2>
        </div>
        <div class="Box_Content">

            <div class="clearfix" id="Contact_content">

                <div class="clsFloatLeft" id="Contact_Left">
                    <p><label><?php echo translate("Phone Support"); ?></label><?php if (isset($row->phone) && $row->phone != '')
        echo $row->phone;
    else
        echo "-";
?></p>
                    <p><label><?php echo translate("Email Support"); ?></label><?php if (isset($row->email) && $row->email != '')
                            echo $row->email;
                        else
                            echo "-"
                            ?></p>
                    <p><label><?php echo translate("Meet us at"); ?></label><?php if (isset($row->name) && $row->name != '')
                        echo $row->name;
                    else
                        echo "-"
                        ?></p>
<?php if (isset($row->street)) echo '<p><label>&nbsp;</label>' . $row->street . '</p>'; ?>
<?php if (isset($row->city)) echo '<p><label>&nbsp;</label>' . $row->city . '</p>'; ?>
                    <p><label>&nbsp;</label><?php if (isset($row->state)) echo $row->state; ?>&nbsp;-&nbsp;<?php if (isset($row->pincode) && $row->pincode != '0')
        echo $row->pincode;
    else
        "-";
?></p>
<?php if (isset($row->country)) echo '<p><label>&nbsp;</label>' . $row->country . '</p>'; ?>
                </div>
                <div class="clsFloatRight" id="Contact_Right">

                    <!-- Feedback Form start -->


                    <form action="<?php echo site_url('pages/contact'); ?>" id="submit_message_form" method="post">                

                        <p>
                            <label class="inner_text" for="name"><?php echo translate("Name"); ?><sup>*</sup></label><input id="name" name="name" placeholder="Name" type="text" value="<?php echo set_value('name'); ?>" />
<?php echo form_error('name'); ?>
                        </p>

                        <p>
                            <label class="inner_text" for="email"><?php echo translate("Email Address"); ?><sup>*</sup></label><input id="email" name="email" placeholder="Email Address" type="text" value="<?php echo set_value('email'); ?>" />
<?php echo form_error('email'); ?>
                        </p>

                        <p>
                            <label class="inner_text" for="message"><?php echo translate("Feedback"); ?><sup>*</sup></label>
                            <textarea id="message" name="message" value="Feedback" rows="4"><?php echo set_value('message'); ?></textarea>
<?php echo form_error('message'); ?>
                        </p>

                        <p>
                            <label>&nbsp;</label>
                            <button id="message_submit" name="commit" class="button1" type="submit"><span><span><?php echo translate("Send"); ?></span></span></button>
                        </p>
                    </form>



                    <!-- End of feedback form  -->

                </div>
                <div style="clear:both"></div>
            </div>
        </div>

    </div>
    <!-- END STATIC LAYOUT -->
</div>