<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>
<div id="dashboard_container">
    <div class="Box" id="Msg_Inbox_Big">
        <div class="Box_Head msgbg">
            <h2><?php echo translate("Inbox"); ?></h2>
        </div>
        <div class="Box_Content">
            <div id="message" style="padding:0 0 10px 0;"></div>
            <ul>
                <?php
                    if ($messages->num_rows() > 0)
                    {
                        foreach ($messages->result() as $row)
                        { //print_r($row);
                            if ($row->contact_id != 0)
                            {
                                $checkin = $this->Common_model->getTableData('contacts', array('id' => $row->contact_id))->row()->checkin;
                                $checkout = $this->Common_model->getTableData('contacts', array('id' => $row->contact_id))->row()->checkout;
                            }
                            else
                            {
                                $checkin_res = $this->Common_model->getTableData('reservation', array('id' => $row->reservation_id));
                                if ($checkin_res->num_rows() != 0)
                                {
                                    $checkin = $this->Common_model->getTableData('reservation', array('id' => $row->reservation_id))->row()->checkin;
                                    $checkin = date('m/d/y', $checkin);
                                    $checkout = $this->Common_model->getTableData('reservation', array('id' => $row->reservation_id))->row()->checkout;
                                    $checkout = date('m/d/y', $checkout);
                                    $topay = $this->Common_model->getTableData('reservation', array('id' => $row->reservation_id))->row()->topay;
                                    $currency = $this->Common_model->getTableData('reservation', array('id' => $row->reservation_id))->row()->currency;
                                }
                            }
                            ?>	
                            <li class="clearfix" <?php if ($row->is_read == 0) echo 'style="background:#FFFFD0; background-color: 3B4043; color:#00B0FF"';
                            else echo 'style="background:#FFFFD0; background-color: white; color:#00B0FF"'; ?> >
                                <div class="clsMsg_User clsFloatLeft">
                                    <a href="<?php echo site_url('users/profile') . '/' . $row->userby; ?>"><img height="50" width="50" alt="" src="<?php echo $this->Gallery->profilepic($row->userby, 2); ?>" /></a>
                                    <p><a href="<?php echo site_url('users/profile') . '/' . $row->userby; ?>"><?php echo get_user_by_id($row->userby)->username; ?></a> <br />
                                        <!--31 minutes--></p>
                                </div>
                                <div class="clsMeg_Detail clsFloatLeft">

                                    <?php
                                    if ($row->conversation_id != 0)
                                    {
                                        $message_id = $row->conversation_id;
                                        $reservation_id = $row->reservation_id;
                                    }
                                    else
                                    {
                                        $message_id = $row->reservation_id;
                                        $reservation_id = $row->reservation_id;
                                    }
                                    if ($message_id == 0)
                                        $message_id = $row->contact_id;
                                    if ($row->message_type == 6)
                                    {

                                        $subject = 'Inquiry about ' . substr(get_list_by_id($row->list_id)->title, 0, 17);
                                        if ($row->is_read == 0)
                                            echo '<strong>';
                                        echo anchor('' . $row->url . '/' . $row->conversation_id, $subject, array("onclick" => "javascript:is_read(" . $row->id . ")"));
                                        if ($row->is_read == 0)
                                            echo '</strong>';
                                    }
                                    else if ($row->message_type == 2)
                                    {

                                        $subject = 'Discuss about ' . substr(get_list_by_id($row->list_id)->title, 0, 10);
                                        if ($row->is_read == 0)
                                            echo '<strong>';
                                        echo anchor('' . $row->url . '/' . $row->conversation_id, $subject, array("onclick" => "javascript:is_read(" . $row->id . ")"));
                                        if ($row->is_read == 0)
                                            echo '</strong>';
                                    }
                                    else if ($row->message_type == 9)
                                    {
                                        if ($row->is_read == 0)
                                            echo '<strong>';
                                        echo anchor('' . 'trips/conversation/' . $row->conversation_id, $row->message, array("onclick" => "javascript:is_read(" . $row->id . ")"));
                                        if ($row->is_read == 0)
                                            echo '</strong>';
                                    }
                                    else if ($row->message_type == 1)
                                    {

                                        if ($row->is_read == 0)
                                            echo '<strong>';
                                        echo anchor('' . $row->url . '/' . $reservation_id, $row->message, array("onclick" => "javascript:is_read(" . $row->id . ")"));
                                        if ($row->is_read == 0)
                                            echo '</strong>';
                                        ?>
                                        <p><?php echo substr(get_list_by_id($row->list_id)->title, 0, 10); ?></span> <span>(<?php echo date("F j, Y", strtotime($checkin)) . ' - ' . date("F j, Y", strtotime($checkout)) ?>)</p>
                                    </div>
            <?php
            }
            else
            {

                if ($row->is_read == 0)
                    echo '<strong>';
                echo anchor('' . $row->url . '/' . $message_id, $row->message, array("onclick" => "javascript:is_read(" . $row->id . ")"));
                if ($row->is_read == 0)
                    echo '</strong>';
                ?>
                                    <p><?php echo substr(get_list_by_id($row->list_id)->title, 0, 10); ?></span> <span>(<?php echo date("F j, Y", strtotime($checkin)) . ' - ' . date("F j, Y", strtotime($checkout)) ?>)</p>
                                    </div>
                            <?php
                            }
                            if ($row->message_type != 9)
                            {
                                ?>

                                    <div class="clsMeg_Off clsFloatRight">
                                        <p> <span><?php echo $row->name; ?></span> 
                <?php
                if (isset($topay))
                {

                    //	$topay = get_currency_value2($currency,$topay);
                    ?>
                                                <br>
                                                <span><?php echo get_currency_symbol($row->list_id) . get_currency_value1($row->list_id, $topay); ?></span> 
                <?php } ?>
                                        </p>
                                    </div>
            <?php } ?>

                                <div class="clsMsg_Del clsFloatLeft">
            <?php if ($row->is_starred == 0) $class = "clsMsgDel_Unfil";
            else $class = "clsMsgDel_fil"; ?>
                                    <p class="clearfix">
                                        <span><a class="<?php echo $class; ?>" id="starred_<?php echo $row->id; ?>" href="javascript:void(0);" onclick="javascript:starred('<?php echo $row->id; ?>');"></a></span>
                                        <span><a onclick="javascript:deleted('<?php echo $row->id; ?>');" href="javascript:void(0);" id="delete_<?php echo $row->id; ?>"><?php echo translate("Delete"); ?></a></span>
                                    </p>
                                </div>
                            </li>

        <?php }
    } else
    { ?>

                        <li class="clearfix">
        <?php echo translate("Nothing to show you."); ?>
                        </li> 

    <?php } ?>

            </ul>

            <div style="clear:both"></div>
        </div>

    </div>
</div>  
<script type="text/javascript">

    function starred(id)
    {

        var className = $('#starred_' + id).attr('class');

        if (className == 'clsMsgDel_Unfil')
        {
            $("#starred_" + id).removeClass("clsMsgDel_fil").addClass("clsMsgDel_fil");
            var to_change = 1;
        }
        else
        {
            $("#starred_" + id).removeClass("clsMsgDel_fil").addClass("clsMsgDel_Unfil");
            var to_change = 0;
        }

        $.ajax({
            type: "POST",
            url: "<?php echo site_url('message/starred'); ?>",
            async: true,
            data: "message_id=" + id + "&to_change=" + to_change,
            success: function (data)
            {
                $("#message").html(data);
                $("#message").show();
                $("#message").delay(1000).fadeOut('slow');
            }
        });
    }

    function deleted(id)
    {
        var ok = confirm("Are you sure to delete the message?");
        if (!ok)
        {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('message/delete'); ?>",
            async: true,
            data: "message_id=" + id,
            success: function (data)
            {
                $("#message").html("<?php echo translate('Message deleted successfully.'); ?>");
                $("#message").show();
                $("#message").delay(1000).fadeOut('slow');

                $("#messages_list").html(data);
                location.reload();
            }
        });
    }

    function is_read(id)
    {
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('message/is_read'); ?>",
            async: true,
            data: "message_id=" + id
        });
    }

</script>