<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- End of stylesheet inclusion -->
<?php
    $this->load->view(THEME_FOLDER . '/includes/dash_header');
    $this->load->view(THEME_FOLDER . '/includes/hosting_header');
?>
<div id="dashboard_container">    
    <div class="Box" id="View_Policies">
        <div class="Box_Head msgbg"><h2><?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate("Refund Policies"); ?></h2></div>
        <div class="Box_Content">
            <ul>
                <li>
                    <?php echo translate("If a guest is transferred to a different location without his or her approval, an appropriate refund will be issued for the reservation."); ?>
                </li>
                <li>
                    <?php echo translate("In the event that a property lacks certain utilities for any portion of a stay"); ?>,&nbsp; <?php echo $this->dx_auth->get_site_title(); ?>&nbsp;<?php echo translate("has the authority to withhold payment to the host and will dictate the amount to be refunded for the reservation. The refund will directly correspond to the severity of the malfunction, its duration, and how it was addressed."); ?>
                </li>
                <li>
                    <?php echo translate("The requisite utilities are electricity, heating, and plumbing (including hot and cold water). Sinks, toilets, & showers must all be fully functioning, as should the oven & stove."); ?>
                </li>
                <li>
                    <?php echo $this->dx_auth->get_site_title(); ?> <?php echo translate("will also issue a partial refund if any appliances that are guaranteed in the listing description (examples: air conditioning, wireless internet, cable television, washer/dryer) are malfunctioning; if any windows, doors, or locks are broken; or if any bedding and towels are not freshly cleaned."); ?>
                </li>
                <li>
                    <?php echo translate("Any construction in a building, especially of a significant nature, needs to be communicated to a guest prior to the guest's arrival."); ?>
                </li>
                <li>
                    <?php echo translate("Please keep in mind that as a host on"); ?>&nbsp; <?php echo $this->dx_auth->get_site_title(); ?>,&nbsp; <?php echo translate("your listing must be a transparent representation of the property provided. Pictures on a listing must be kept up-to-date and should reflect any changes to the furnishings. The Description and Amenities sections should accurately reflect exactly what is provided at the accommodations."); ?>
                </li>
                <li>
                    <?php echo translate("Finally, if there are any vermin in the apartment, a refund is warranted!"); ?>
                </li>
                <li>
                    <?php echo translate("Thank you for helping us maintain a proper level of standards."); ?>
                </li>
            </ul>
        </div>
    </div>
</div>