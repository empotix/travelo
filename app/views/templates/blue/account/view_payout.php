<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>
<?php $this->load->view(THEME_FOLDER . '/includes/account_header'); ?>
<div id="dashboard_container">
    <div class="Box" id="View_Payout">
        <div class="Box_Head msgbg">
            <h2><?php echo translate("Payout Method"); ?></h2>
        </div>
        <div class="Box_Content">
            <?php if ($result->num_rows() > 0)
                {
                    ?>
                    <table id="payout_methods" class="clsTable_View" cellpadding="5" cellspacing="0" width="100%">
                        <tbody>
                            <tr>
                                <th class="name"><?php echo translate("Status"); ?></th>
                                <th class="rank"><?php echo translate("Method"); ?></th>
                                <th class="rank"><?php echo translate("Details"); ?></th>
                            </tr>
                            <?php foreach ($result->result() as $row)
                            {
                                ?>
                                <tr>
                                    <?php if ($row->is_default == 1)
                                    {
                                        ?>
                                        <td style="width:130px;"><?php echo translate("Verified"); ?><b><?php echo '(' . translate('Default') . ')'; ?></b></td>
                                    <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <td style="width:130px;"><?php echo translate("Verified"); ?></td>
                                <?php } ?>
                                    <td style="width:100px;"><?php echo $row->payout_type; ?></td>
                                    <td><?php echo $row->email . '(' . $row->currency . ')'; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        else
                        {
                            echo '<p> No payout selected. </p>';
                        }
                    ?>
                </tbody>
            </table>
            <div style="" id="payout_new_initial"> <a class="clsLink2_Bg" onclick="$('#payout_new_initial').hide();
                    $('#payout_country_select').show();
                    $('#change_default').hide();
                    return false;" href="javascript:void(0);"><?php echo translate("Add Payout Method"); ?></a>&nbsp;&nbsp;&nbsp;<a class="clsLink2_Bg" onclick="$('#change_default_show_link').hide();
                            $('#change_default').show();
                            $('#change_default_hide_link').show();
                            return(false);" href="javascript:void(0);" id="change_default_show_link"><?php echo translate("Change Default"); ?></a> <a class="clsLink2_Bg" style="display:none;" onclick="$('#change_default_hide_link').hide();
                                    $('#change_default_show_link').show();
                                    $('#change_default').hide();
                                    return(false);" href="javascript:void(0);" id="change_default_hide_link"><?php echo translate("Don't Change Default"); ?></a> </div>
            <!--Payment Method-->
            <div style="display:none;" id="payout_country_select">
                <form  method="post" action="<?php echo base_url() . 'account/payoutMethod'; ?>">
                        <?php echo translate("Our ability to pay you depends on your country of residence:"); ?>
                    <select name="country" id="country">
                    <?php foreach ($countries as $country)
                        {
                            ?>
                                <option value="<?php echo $country->country_symbol; ?>"><?php echo $country->country_name; ?></option>
                                 <?php } ?>
                    </select>&nbsp;&nbsp;
                    <button style="margin-top: -3px;" type="button" id="next1" class="btn blue gotomsg"  name="commit"><span><span><?php echo translate("Next"); ?></span></span></button>
<?php echo translate("or"); ?>
                    &nbsp;<a onclick="$('#payout_country_select').hide();
                            $('#payout_new_initial').show();
                            return false;" href="javascript:void(0);"><?php echo translate("Cancel"); ?></a>
                </form>
            </div>
            <!--Payment Method-->
            <!--Change Default-->
            <div id="change_default" style="display:none;">
                <form action="<?php echo site_url('account/setDefault'); ?>" method="post">
                    <p>
                        <select name="default_email" >
                            <option value="None"> None </option>
<?php foreach ($defaults->result() as $default_mail)
    {
        ?>
                                    <option value="<?php echo $default_mail->id; ?>"><?php echo $default_mail->email; ?></option>
    <?php } ?>
                        </select>&nbsp;&nbsp;
                        <button type="submit" class="btn blue gotomsg" name="SetDefault"><span><span><?php echo translate("Set Default"); ?></span></span></button>
                    </p>
                </form>
            </div>
            <!--Change Default-->
            <div style="display: none;" id="payout_new_select"> </div>
        </div>
    </div>
</div>
<script type="text/javascript">

    $(document).ready(function () {

        $("#next1").click(function () {

            var country = $("#country").val();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url('account/payoutMethod'); ?>",
                async: true,
                data: "country=" + country,
                success: function (data)
                {
                    $("#payout_new_select").html(data);
                    $('#payout_country_select').hide();
                    $('#payout_new_select').show();
                }
            });

        })

    });
</script>