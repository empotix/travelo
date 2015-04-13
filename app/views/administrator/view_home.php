<div id="Dashboard">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            //echo $msg;
        }
    ?>

    <h2><?php echo translate_admin('Dashboard'); ?></h2>
    <h3><?php echo translate_admin('Latest Activity'); ?></h3>
    <?php
        $no_user = $this->db->get('users')->num_rows();
        $no_list = $this->db->get('list')->num_rows();
        $totalreservation = $this->db->get('reservation')->num_rows();
    ?>
    <div class="selQuickStatus clearfix">
        <div class="selQuickStatusleft clsFloatLeft">
            <p><img src="<?php echo base_url() . 'images/chat.gif'; ?>" height="40" width="45" alt="img" /></p>
        </div>
        <div class="selQuickStatusRight clsFloatRight">
            <ul class="clearfix">
                <li class="clsMember clear">
                    <table width="300">
                        <tr>
                            <td width="40%"><?php echo translate_admin('Total Users'); ?></td>
                            <td width="20%">:
                                <?php if (isset($no_user))  ?><a href="<?php echo admin_url('members'); ?>"> <?php echo $no_user; ?></a>  </td>
                            <td width="50%"><a href="<?php echo admin_url('members'); ?>"><?php echo translate_admin('Members'); ?></a></td>
                        </tr>
                    </table>
                </li>
                <li class="clsClosedprojects">
                    <table width="300">
                        <tr>
                            <td width="40%"><?php echo translate_admin('Total List'); ?></td>
                            <td width="20%">:
                                <?php if (isset($no_list))  ?><a href="<?php echo admin_url('lists'); ?>"> <?php echo $no_list; ?></a> </td>
                            <td width="50%"><a href="<?php echo admin_url('lists'); ?>"> <?php echo translate_admin('Lists'); ?></a></td>
                        </tr>
                    </table>
                </li>
                <li class="clsClosedprojects">
                    <table width="300">
                        <tr>
                            <td width="40%"><?php echo translate_admin('Total Reservation'); ?> </td>
                            <td width="20%">:
                                <?php if (isset($totalreservation))  ?> <a href="<?php echo admin_url('payment/finance'); ?>"> <?php echo $totalreservation; ?></a> </td>
                            <td width="50%"><a href="<?php echo admin_url('payment/finance'); ?>"><?php echo translate_admin('Total Reservation'); ?></a></td>
                        </tr>
                    </table>
                </li>
                <li class="clsClosedprojects">
                    <table width="300">
                        <tr>
                            <td width="40%"><?php echo translate_admin('Today Users'); ?></td>
                            <td width="20%">:
                                <?php if (isset($todayuser))
                                        echo $todayuser;
                                    else
                                        echo '0';
                                ?></td>
                            <td></td>
                        </tr>
                    </table>
                </li>
                <li class="clsClosedprojects">
                    <table width="300">
                        <tr>
                            <td width="40%"><?php echo translate_admin('Today Lists'); ?></td width="10%">
                            <td>:
<?php if (isset($today_userlist))
        echo $today_userlist;
    else
        echo '0';
?></td>
                            <td></td>
                        </tr>
                    </table>
                </li>
                <li class="clsClosedprojects">
                    <table width="300">
                        <tr>
                            <td width="40%"><?php echo translate_admin('Today Reservation'); ?></td>
                            <td width="20%">:
<?php if (isset($today_reservation))
        echo $today_reservation;
    else
        echo '0';
?></td>
                            <td></td>
                        </tr>
                    </table>
                </li>
            </ul>
        </div>
    </div>
    <h2><?php echo translate_admin('Version'); ?></h2>
    <ul>
        <li><a href="#"><?php echo translate_admin('Installed Version'); ?> - 3.0.3</a></li>
    </ul>
</div>
