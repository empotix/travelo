<!-- Export CSV-->
<div id="confirm" style="background-color: #000; opacity:0.5;" onclick="document.getElementById('confirm').style.display = 'none';
        document.getElementById('confirmbox').style.display = 'none';">
</div>
<!-- Export CSV-->
<?php
    //Show Flash Message
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }

    // Show reset password message if exist
    if (isset($reset_message))
        echo $reset_message;
    // Show error
    echo validation_errors();
    $tmpl = array(
        'table_open' => '<table class="table" border="0" cellpadding="4" cellspacing="0">',
        'heading_row_start' => '<tr>',
        'heading_row_end' => '</tr>',
        'heading_cell_start' => '<th>',
        'heading_cell_end' => '</th>',
        'row_start' => '<tr>',
        'row_end' => '</tr>',
        'cell_start' => '<td>',
        'cell_end' => '</td>',
        'row_alt_start' => '<tr>',
        'row_alt_end' => '</tr>',
        'cell_alt_start' => '<td>',
        'cell_alt_end' => '</td>',
        'table_close' => '</table>'
    );

    $this->table->set_template($tmpl);
    $this->table->set_heading('', translate_admin('Username'), translate_admin('Email'), translate_admin('Role'), translate_admin('Banned'), translate_admin('Last IP'), translate_admin('Last login'), translate_admin('Created'), translate_admin('Edit'), translate_admin('Change Password')
    );
    foreach ($users as $user)
    {
        if ($this->db->where('id', $user->id)->get('profiles')->num_rows() != 0)
        {
            if ($user->role_id == 2)
            {
                $role = 'admin';
            }
            else
            {
                $role = 'user';
            }
            $banned = ($user->banned == 1) ? 'Yes' : 'No';
            $this->table->add_row(
                    form_checkbox('checkbox_' . $user->id, $user->id), $user->username, $user->email,
                    //$user->role_name, 
                    $role, $banned, $user->last_ip, get_user_times($user->last_login, get_user_timezone()), get_user_times($user->created, get_user_timezone()), anchor('administrator/members/edit/' . $user->id, translate_admin('Edit')), anchor('administrator/members/changepassword/' . $user->id, translate_admin('change passwords'))
            );
        }
    }
    echo '<div class="clsFloatRight">';
    echo '<form name="myform" action="' . site_url('administrator/members/getusers') . '" method="post">';
?>
                <!-- //echo '<b>Search Username / Email</b><input type="text" name="usersearch" id="usersearch" style="margin:0 10px; height:23px;">'; -->		
<b><?php echo translate_admin('Search Username / Email'); ?></b><input type="text" name="usersearch" id="usersearch" style="margin:0 10px; height:23px;">

        <!--  echo '<input type="submit" name="search" value="Search">'; -->
<input type="submit" name="search" value="<?php echo translate_admin('Search'); ?>">
<?php
    echo '</form>';
    echo '</div>';
    echo form_open('administrator/members');

    echo '<div class="clsUser_Buttons"><ul class="clearfix"><li>';
    echo form_submit('ban', translate_admin('Ban user'));
    echo '</li><li>';
    echo form_submit('unban', translate_admin('Unban user'));
    echo '</li><li>';
    echo form_submit('reset_pass', translate_admin('Reset password'));
    echo '</li>';
?>

<!-- Export CSV-->
<div id="confirmbox" >
    <a id="confirmclose" href="javascript:void();" onclick="document.getElementById('confirm').style.display = 'none';
            document.getElementById('confirmbox').style.display = 'none';">
        <img src="<?php echo site_url(); ?>images/fancy_close.png">
    </a>
    <center>
        <p>	<?php echo translate_admin('Click here to download as .Txt file'); ?></p>
        <p><input type="submit" name="export" value="Export as Txt file"></p>
        <p>	<?php echo translate_admin('Click here to download as .Csv file'); ?></p>
        <p><input type="submit" name="export_csv" value="Export as Csv file"></p>
    </center>

</div>
<!--	<input type="button" onclick="document.getElementById('confirm').style.display='block'; 
document.getElementById('confirmbox').style.display='block';" value="<?php echo translate_admin('Export All Users'); ?>"/>-->
<!-- Export CSV-->
<?php
    echo '</ul></div>';
    echo "<div id='usertable'>";
    echo "<div id='css_user_atleast_user'>";
    echo "<b>" . translate_admin('Note:') . "</b>" . "<span>" . translate_admin('Select_atleast_user') . "</span>";
    echo "</div>";
    echo $this->table->generate();
    echo "</div>";
    echo form_close();

    /* if(!($this->uri->segment(3,0)))
      { */
    echo $pagination;
    //}
?>
