<div id="Edit_Location">
    <div class="clsTitle">
        <h3><?php echo translate_admin('Edit Users'); ?></h3></div>
    <?php
        $user_id = $this->uri->segment(4, 0);
        $query = $this->db->get_where('profiles', array('id' => $user_id));
        $q = array();
        $q = $query->result();


        $email_id = $this->db->get_where('users', array('id' => $user_id))->row()->email;
        $username = $this->db->get_where('users', array('id' => $user_id))->row()->username;
    ?>
    <form action="<?php echo admin_url('members/edit/' . $user_id); ?>" method="post" name="user_edit">
        <table class="table" cellpadding="2" cellspacing="0">
            <tr>
                <td class="label"><?php echo translate_admin("Name:"); ?></td>
                <td>
                    <input class="name_input" style="margin:0 10px 0 0;" id="user_first_name" name="Fname" size="30" type="text" value="<?php if ($q[0]->Fname)
        echo $q[0]->Fname;
    else
        echo '""';
    ?>" />
                    <input class="name_input" id="user_last_name" name="Lname" size="30" type="text" value="<?php if ($q[0]->Lname)
                               echo $q[0]->Lname;
                           else
                               echo '""';
    ?>" /></td>
            </tr>

            <tr>
                <td class="label">
<?php echo translate_admin("Username:"); ?></td>
                <td>
                    <input class="private_lock" id="user_email" name="username" size="30" type="text" value="<?php echo $username; ?>" readonly="readonly"/>
                </td>
            </tr>

            <tr>
                <td class="label">
<?php echo translate_admin("Email:"); ?></td>
                <td>
                    <input class="private_lock" id="user_email" name="email" size="30" type="text" value="<?php echo $email_id; ?>" readonly="readonly"/>
                </td>
            </tr>
            <tr>
                <td class="label"><?php echo translate_admin("Where You Live:"); ?></td>
                <td><input id="user_profile_info_current_city" name="live" value="<?php if ($q[0]->live)
                               echo $q[0]->live;
                           else
                               echo '';
?>" size="30" type="text" /><br /><span style="color:#9c9c9c; text-style:italic; font-size:11px;">e.g. Paris, FR / Brooklyn, NY / Chicago, IL</span><br /></td>
            </tr>

            <tr>
                <td class="label"><?php echo translate_admin("Work:"); ?></td>
                <td>
                    <input id="user_profile_info_employer" name="work" size="30" type="text" value="<?php if ($q[0]->live)
                               echo $q[0]->work;
                           else
                               echo '""';
?>" />
                </td>
            </tr>

            <tr>
                <td class="label" valign="top"><?php echo translate_admin("Phone Number:"); ?></td>
                <td>

                    <input autocomplete="off" class="private_lock" id="user_phone" name="phnum" size="30" type="text" value=<?php if ($q[0]->phnum)
                               echo $q[0]->phnum;
                           else
                               echo '""';
?> />
                    <input id="user_phone_country" name="phcountry" type="hidden" />

                </td>
            </tr>

            <tr>
                <td class="label" valign="top"><?php echo translate_admin("Time Zone"); ?></td>
                <td> <?php echo timezone_menu(get_user_timezone($this->dx_auth->get_user_id())); ?>  </td>
            </tr>

            <tr>
                <td style="vertical-align:top;"><?php echo translate_admin("Describe Yourself"); ?> :</td>
                <td><textarea cols="40" id="user_profile_info_about" name="desc" rows="20" style="width:250px;height:200px;"><?php if ($q[0]->describe) echo $q[0]->describe; ?></textarea></td>
            </tr>                
        </table>
        <p style="margin-left: 222px;">
            <button type="submit" class="btn pink gotomsg"  name="commit"><span><span><?php echo translate_admin("Update"); ?></span></span></button>
        </p>

    </form>		
</div>					