<?php $this->load->view('administrator/includes/header'); ?>

<?php

    echo '<div id="wrapper"><div id="content">';
    if ($this->dx_auth->is_admin()):
        $this->load->view('administrator/includes/sidebar');
    endif;
    echo '<div id="main">';
    $this->load->view($message_element);
    echo '</div></div></div>';
?>

<?php

    $this->load->view('administrator/includes/footer.php');
?>