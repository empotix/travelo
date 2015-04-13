<div id="View_Login">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
            redirect_admin('backend');
        }
    ?>

    <!--CONTENT-->
    <div class="clslog_container">
        <h2><?php echo translate_admin("Member Area"); ?> - <?php echo translate_admin("Login"); ?> </h2>
        <div class="form_error"></div>
        <div class="clslog_form">
            <form method="post" action="<?php echo site_url('administrator/login'); ?>">
                <p>
                    <label><?php echo translate_admin("Username"); ?> <span class="clsRed">*</span></label>
                    <input class="focus" type="text" name="usernameli" value="<?php echo set_value('usernameli'); ?>"/>
                </p>
                <div class="adminerror">
                    <?php if (form_error('usernameli'))
                        {
                            ?>
                            <?php echo form_error('usernameli'); ?>
    <?php } ?>
                </div>
                <p>
                    <label><?php echo translate_admin("Password"); ?><span class="clsRed">*</span></label>
                    <input class="focus" type="password" name="passwordli" value=""/>
                </p>

                <div class="adminerror">
                    <?php if (form_error('passwordli'))
                        {
                            ?>
        <?php echo form_error('passwordli'); ?>
    <?php } ?>
                </div>
                <p>
                    <label>&nbsp;</label>
                    <button name="loginAdmin" class="btn pink gotomsg" type="submit"><span><span><?php echo translate_admin("Submit"); ?></span></span></button>
                    <button name="reset" class="btn pink gotomsg" type="reset"><span><span><?php echo translate_admin("Reset"); ?></span></span></button>
                </p>
            </form>
        </div>
        <p><?php echo translate_admin("Use a valid username and password to gain access to the Administrator Back-end"); ?>.</p>
        <p><a href="<?php echo base_url(); ?>"><?php echo translate_admin("Return to site Home Page"); ?></a></p>
        <div class="clsLog_Bg"></div>
        <div class="clear"></div>
    </div>
    <!--END OF CONTENT-->
</div>