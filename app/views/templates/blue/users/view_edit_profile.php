<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />
<style type="text/css">
    .help{color:#FF0000;}
</style>

<!-- End of stylesheet inclusion -->
<?php $this->load->view(THEME_FOLDER . '/includes/dash_header'); ?>
<?php
    $this->load->view(THEME_FOLDER . '/includes/profile_header');

    $this->load->library('twconnect');
    $twitter_id = $this->twconnect->tw_user_id;
?>

<div id="dashboard_container">
    <div id="View_Edit_Profile" class="Box">
        <div class="Box_Head msgbg">
            <h2><?php echo translate("About You"); ?> 
                <span class="Box_Head_Right" id="show_date_time"></span>
            </h2>
        </div>
        <div class="Box_Content clearfix">
            <div id="Edit_Pro_Left" class="clsFloatLeft">


                <h2><?php echo translate("Upload photo"); ?></h2>
                <div id="user_pic" onclick="show_ajax_image_box();"> 
                    <img width="230" src="<?php
                        if ($this->session->userdata('image_url') != '')
                        {
                            echo $this->session->userdata('image_url');
                        }
                        else
                        {

                            echo $this->Gallery->profilepic($this->dx_auth->get_user_id(), 2);
                        }
                    ?>"  /> 


                    <form action="<?php echo site_url('users/photo/' . $user_id); ?>" name="user_photo" id="user_photo" method="post" enctype="multipart/form-data">                 		
                        <p>
                            <input id="upload123" name="upload123" type="file" style="width:220px;" />
                            <input id="upload" name="upload" value="Hello" type="hidden" />
                        </p>
                        <?php echo form_error('upload123'); ?>
                        <p><button type="submit" id="upload_image_submit_button" class="btn blue gotomsg"  name="commit"><span><span><?php echo translate("Upload photo"); ?></span></span></button></p>
                    </form>

                </div> 
            </div>

            <div id="Edit_Pro_Right" class="clsFloatRight">
                <form action="<?php echo site_url('users/edit/' . $user_id); ?>" method="post" name="user_edit">              	
                    <table>
                        <tr>
                            <td class="label"><?php echo translate("First Name:"); ?></td>
                            <td>
                                <input class="name_input" style="margin:0 10px 0 0;" id="user_first_name" name="Fname" size="30" type="text" value="<?php if (isset($profile->Fname))
                            echo $profile->Fname;
                        else
                            echo '""';
                        ?>" /></td>

                        </tr>
                        <tr>
                            <td class="label"><?php echo translate("Last Name:"); ?></td>
                            <td><input class="name_input" id="user_last_name" name="Lname" size="30" type="text" value="<?php if (isset($profile->Lname))
                            echo $profile->Lname;
                        else
                            echo '""';
                        ?>" /></td>
                        </tr>
                        <tr>
                            <td class="label">
<?php echo translate("Email:"); ?> <sup>*</sup></td>
                            <td>
                                <input class="private_lock" id="user_email" name="email" size="30" type="text" value="<?php echo $email_id; ?>" onCopy="return false" onDrag="return false" onDrop="return false" onPaste="return false" autocomplete=off  />
                                       <?php echo form_error('email'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"><?php echo translate("Where You Live:"); ?></td>
                            <td><input id="user_profile_info_current_city" name="live" value="<?php if (isset($profile->live))
                                           echo $profile->live;
                                       else
                                           echo '';
                                       ?>" size="30" type="text" /><br />
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo translate("Ex_live"); ?></span><br /></td>
                        </tr>

                        <tr>
                            <td class="label"><?php echo translate("Work:"); ?></td>
                            <td>
                                <input id="user_profile_info_employer" name="work" size="30" type="text" value="<?php if (isset($profile->live))
                                           echo $profile->work;
                                       else
                                           echo '""';
                                       ?>" />
                            </td>
                        </tr>

                        <tr>
                            <td class="label" valign="top"><?php echo translate("Phone Number:"); ?></td>
                            <td>

                                <input autocomplete="off" class="private_lock" id="user_phone" name="phnum" size="30" type="text" value="<?php if (isset($profile->phnum))
                                           echo $profile->phnum;
                                       else
                                           echo '""';
                                       ?>" />
                                    <?php echo form_error('phnum'); ?><br>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px;"><?php echo "e.g. 9174611232"; ?></span>


                            </td>
                        </tr>

                        <tr>
                            <td class="label" valign="top"><?php echo translate("Time Zone"); ?></td>
                            <td> <?php echo timezone_menu(get_user_timezone($this->dx_auth->get_user_id())); ?>  </td>
                        </tr>

                        <tr>
                            <td style="vertical-align:top;"><?php echo translate("Describe Yourself"); ?> :</td>
                            <td><textarea cols="40" id="user_profile_info_about" name="desc" rows="20" style="width:250px;height:200px;">
<?php if (isset($profile->describe)) echo strip_tags(str_replace('[removed]', '', $profile->describe)); ?></textarea></td>
                        </tr>                
                    </table>
                    <p>
                        <button type="submit" class="btn green gotomsg"  name="commit"><span><span><?php echo translate("Save Changes"); ?></span></span></button>
                        or <?php echo anchor('home', translate("Cancel")); ?>&nbsp;&nbsp;&nbsp;</p>
                </form>
            </div>
            <div style="clear:both;"></div>


        </div>

    </div>
</div>
<!-- End of the page scripts -->

<script type="text/javascript">
// Current Server Time script (SSI or PHP)- By JavaScriptKit.com (http://www.javascriptkit.com)
// For this and over 400+ free scripts, visit JavaScript Kit- http://www.javascriptkit.com/
// This notice must stay intact for use.

//Depending on whether your page supports SSI (.shtml) or PHP (.php), UNCOMMENT the line below your page supports and COMMENT the one it does not:
//Default is that SSI method is uncommented, and PHP is commented:

    var currenttime = '<?php echo date("F d, Y H:i:s", get_user_time(local_to_gmt(), get_user_timezone())); ?>' //PHP method of getting server date

///////////Stop editting here/////////////////////////////////

    var montharray = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December")
    var serverdate = new Date(currenttime)

    function padlength(what) {
        var output = (what.toString().length == 1) ? "0" + what : what
        return output
    }

    function displaytime() {
        serverdate.setSeconds(serverdate.getSeconds() + 1)
        var datestring = montharray[serverdate.getMonth()] + " " + padlength(serverdate.getDate()) + ", " + serverdate.getFullYear()
        var timestring = padlength(serverdate.getHours()) + ":" + padlength(serverdate.getMinutes()) + ":" + padlength(serverdate.getSeconds())
        document.getElementById("show_date_time").innerHTML = "<b>" + datestring + "</b>" + "&nbsp;<b>" + timestring + "</b>";
    }

    window.onload = function () {
        setInterval("displaytime()", 1000)
    }


    /*  time out for link   */

    $('#user_photo').submit(function () {
        setTimeout(function () {
            $('#upload123').val('');
        }, 1000);
    });

    /*  time out for link   */

</script>
