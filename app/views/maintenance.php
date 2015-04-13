<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Untitled Document</title>
    </head>

    <body >

        <div style="text-align:center">
            <img src="<?php echo base_url(); ?>images/maintanance.jpg" />
            <?php
                $offline_msg = $this->db->get_where('settings', array('code' => 'OFFLINE_MESSAGE'))->row()->text_value;
                echo '<h1>' . $offline_msg . '</h1>';
            ?>
        </div>
    </body>
</html>
