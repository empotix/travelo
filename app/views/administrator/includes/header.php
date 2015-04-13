<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <META HTTP-EQUIV="Expires" CONTENT="0">
            <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
                <META HTTP-EQUIV="Cache-Control" CONTENT="no-cache">
                    <title><?php echo translate_admin("Admin Section"); ?></title>
                    <script type="text/javascript" src="<?php echo base_url() ?>js/common.js"></script>
                    <script type="text/javascript" src="<?php echo base_url() ?>js/webtoolkit.aim.js"></script>
                    <script type="text/javascript" src="<?php echo base_url() ?>js/script.js"></script>
                    <script type="text/javascript" src="<?php echo base_url() ?>js/datetimepicker.js"></script>
                    <script type="text/javascript" src="<?php echo base_url() ?>js/countries-2.0-min.js"></script>
                    <link rel="stylesheet" type="text/css" href="<?php echo css_url(); ?>/admin.css" />
                    </head>
                    <body>
                        <!--LAYOUT-->
                        <!--HEADER-->
                        <div class="clsContainer">
                            <!--HEADER-->
                            <div id="header" class="clearfix">
                                <div id="selLeftHeader" class="clsFloatLeft">
                                    <?php
                                        $logo = $this->db->get_where('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
                                        $query = $this->Common_model->getTableData('settings', array('code' => 'BACKEND_LANGUAGE'))->row();
                                    ?>
                                    <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                                        <tr><td><h1 class="logo"> <a href="<?php echo site_url('administrator'); ?>"><img src="<?php echo base_url() . 'logo/' . $logo; ?>" title="<?php echo $this->dx_auth->get_site_title(); ?>"/></a></h1>
                                            </td>
                                            <tr>
                                                </table>
                                                </div>
                                                <div id="Head_GT_Timer">
                                                    <?php if ($query->int_value == 2)
                                                        {
                                                            ?>
                                                            <div id="google_trans" class="clsFloatLeft">
                                                                <!-- Begin TranslateThis Button -->
                                                                <div id="translate-this"><a href="http://translateth.is/" class="translate-this-button"></a></div>
                                                                <!-- End TranslateThis Button -->
                                                            </div>
    <?php } ?>
                                                    <div id="show_date_time" class="clsFloatLeft"></div>
                                                    <div style="clear:both"></div>
                                                </div>
                                                <div id="selRightHeader" class="clsFloatRight">
                                                    <ul id="mainnav">
                                                        <li><a href="<?php echo site_url('administrator'); ?>"><?php echo translate_admin("Admin Home"); ?></a></li>
                                                        <li><a href="<?php echo base_url(); ?>"><?php echo translate_admin("Site Home"); ?></a></li>
                                                        <?php if ($this->dx_auth->is_admin())
                                                            {
                                                                ?> 
                                                                <li><a href="<?php echo site_url('users/logout'); ?>"> <?php echo translate_admin("Logout"); ?> </a></li> 
    <?php } ?>
                                                    </ul>
                                                </div>
                                                </div>
                                                <!--END OF HEADER-->