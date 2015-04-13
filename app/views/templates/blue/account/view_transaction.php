<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>

<?php $this->load->view(THEME_FOLDER . '/includes/account_header'); ?>	
<div id="dashboard_container">
    <div class="Box" id="View_Transaction">
        <div class="Box_Head msgbg"><h2><?php echo translate("Transaction History"); ?></h2></div>
        <div class="Box_Content">
            <?php if ($result->num_rows() > 0)
                {
                    ?>
                    <table class="clsTable_View" cellspacing="0" cellpadding="4" width="100%">
                        <thead>
                            <tr>
                                <th><?php echo translate("List ID"); ?></th>
                                <th><?php echo translate("Traveller Name"); ?></th>
                                <th><?php echo translate("Host Name"); ?></th>
                                <th><?php echo translate("Check In"); ?></th>
                                <th><?php echo translate("Check Out"); ?></th>
                                <th><?php echo translate("Price"); ?></th>
                                <th><?php echo translate("Status"); ?></th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            foreach ($result->result() as $row)
                            {

                                $query = $this->Users_model->get_user_by_id($row->userby);
                                $booker_name = $query->row()->username;

                                $query1 = $this->Users_model->get_user_by_id($row->userto);
                                $hotelier_name = $query1->row()->username;
                                ?>
                                <tr>
                                    <td><?php echo $row->list_id; ?> </td>
                                    <td><?php echo $booker_name; ?> </td>
                                    <td><?php echo $hotelier_name; ?> </td>
                                    <td><?php echo get_user_times($row->checkin, get_user_timezone()); ?> </td>
                                    <td><?php echo get_user_times($row->checkout, get_user_timezone()); ?> </td>
                                    <td><?php echo get_currency_symbol($row->list_id) . get_currency_value1($row->list_id, $row->price); ?> </td>
                                    <td><?php echo $row->name; ?>
                                </tr>
        <?php } ?>

                        </tbody>
                    </table>
                    <?php
                    echo $pagination;
                }
                else
                {
                    ?>
                    <p><?php echo translate("Once you have reservations, the money that you have earned will be displayed here."); ?></p>
    <?php } ?>
        </div>
    </div>
</div>