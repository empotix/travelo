<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>

<?php $this->load->view(THEME_FOLDER . '/includes/account_header'); ?>	
<div id="dashboard_container">	
    <div class="Box" id="View_Notification">
        <div class="Box_Head msgbg"><h2><?php echo translate("Email Settings"); ?></h2></div>
        <div class="Box_Content">
            <?php echo form_open('account'); ?>
            <!--EMAIL SETTING-->
            <h3><?php echo translate("Send me emails when"); ?>:</h3>
            <div class="Notification_Content">
                <h4><?php echo translate("We promise not to spam, and you can disable these at any time."); ?></h4>
                <ul>
                    <li><input type="checkbox" value="1" name="periodic_offers" id="offers"  <?php if (isset($periodic_offers) && $periodic_offers == 1) echo 'checked="checked"'; ?>> <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate("has periodic offers and deals on"); ?> <a target="_blank" href="<?php echo site_url('pages/view') . '/really_cool_destinations'; ?>"><?php echo translate("Really cool destinations."); ?></a></li>
                    <li><input type="checkbox" value="1" name="company_news" id="news"  <?php if (isset($company_news) && $company_news == 1) echo 'checked="checked"'; ?>> <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate("has"); ?> <a target="_blank" href="<?php echo site_url('pages/view') . '/fun_company_news'; ?>"><?php echo translate("fun company news"); ?></a>, <?php echo translate("as well as periodic emails."); ?></li>
                </ul>
            </div>
            <h3><?php echo translate("Remind me when"); ?>:</h3>
            <div class="Notification_Content">
                <h4><?php echo translate("Enabling these emails ensures the best possible experience for both hosts and guests."); ?></h4>
                <ul>
                    <li><input type="checkbox" value="1" name="upcoming_reservation" id="upcoming_reservation" <?php if (isset($upcoming_reservation) && $upcoming_reservation == 1) echo 'checked="checked"'; ?>><?php echo translate("I have an upcoming reservation."); ?></li>
                    <li><input type="checkbox" value="1" name="new_review" id="new_review"  <?php if (isset($new_review) && $new_review == 1) echo 'checked="checked"'; ?>> <?php echo translate("I have received a new review."); ?></li>
                    <li><input type="checkbox" value="1" name="leave_review" id="leave_review"  <?php if (isset($leave_review) && $leave_review == 1) echo 'checked="checked"'; ?>><?php echo translate("I need to leave a review for one of my guests."); ?> </li>

                    <li><input type="checkbox" value="1" name="rank_search" id="pdate_calendar" <?php if (isset($rank_search) && $rank_search == 1) echo 'checked="checked"'; ?>><?php echo translate("I can improve my ranking in the search results by updating my calendar."); ?></li>
                </ul> 
                <p>
                    <button type="submit" class="btn green gotomsg" name="commit"><span><span><?php echo translate("Save Email Settings"); ?></span></span></button>
                </p>
            </div>
            <!--EMAIL SETTING-->
        </div>
    </div>
</div>
