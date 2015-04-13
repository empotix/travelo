<link href="<?php echo css_url(); ?>/edit_listing.css" media="screen" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url() ?>js/webtoolkit.aim.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/script.js"></script>
<script type="text/javascript">

    function startCallback() {

        if ($("#userfile").val() == '')
        {
            alert('There was no photo selected');
            return false;
        }

        document.getElementById('message').innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';
        // make something useful before submit (onStart)
        return true;
    }

    function startCallback2() {

        document.getElementById('message').innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback(response) {
        var res = response;
        document.getElementById('galleria_container').innerHTML = res;
        $("#message").show();
        $("#message").html('<p style="color:#009933"><strong><em> List Photo`s Updated successfully </em></strong></p>');
        $("#message").delay(1000).fadeOut('slow');
        location.reload();
    }
</script>
<!--Required Data from db  -->
<div class="container_bg" id="View_Edit_List">
    <div id="View_Edit_Heading">
        <div class="heading_content clearfix">
            <div class="edit_listing_photo">
                <?php $url = getListImage($room_id); ?>
                <img alt="Host_pic" height="65" src="<?php echo $url; ?>" /> </div>
            <div class="listing_info">
                <h3><?php echo anchor('rooms/' . $room_id, $list->title, array('id' => "listing_title_banner")) ?></h3>
                <?php echo anchor('rooms/' . $room_id, translate('View Listing'), array('class' => "clsLink2_Bg")) ?> <span id="availability-error-message"></span> </div>
            <div class="edit_view_all_list">
                <?php echo anchor('hosting', translate('View All Listing'), array('class' => 'btn large blue')); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <div class="clearfix" id="View_Edit_Content">
        <div class="View_Edit_Nav"> 
            <div class="nav-container">
                <?php $this->load->view(THEME_FOLDER . '/includes/editList_header.php'); ?>
            </div>
        </div>
        <div class="View_Edit_Main_Content">
            <div id="notification-area"></div>
            <div class="Box editlist_Box">
                <div class="Box_Head1">
                    <ul class="subnav clearfix" id="photos">
                        <li id="upload-tab" class="selected"><a href="javascript:void(0);" onclick="javascript:showhide(1);"><?php echo translate("Upload"); ?></a></li>
                        <li id="edit-tab" class="clsBg_None"><a href="javascript:void(0);" onclick="javascript:showhide(2);"><?php echo translate("Edit"); ?></a></li>
                    </ul>
                </div>
                <div id="notification-area"></div>
                <div id="message"></div>
                <div id="dashboard-content">
                    <div id="new_photo">
                        <div class="Box_Content">
                            <form id="uploadlistings" enctype="multipart/form-data" action="<?php echo site_url('rooms/edit_photo/' . $room_id); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">
                                <p><label id="upload_photo" for="new_photo"><?php echo translate("Upload photo"); ?></label>
                                    <input name="userfile[]" id="userfile"  size="24" type="file" multiple="multiple" onchange="return checkSize(2097152)" />&nbsp;&nbsp;
                                    <button name="update_photo" id="update_photo" class="pink btn gotomsg" type="submit"><span><span><?php echo translate("Upload"); ?></span></span></button></p>
                            </form>
                            <div id="upload_feedback"></div>
                            <p><?php echo translate("You can upload a maximum of 100 photos to your listing."); ?></p>
                            <p><?php echo translate("Suggested Size is 640x425 pixels, 2MB or less."); ?> <a href="<?php echo site_url('pages/view') . '/photo_tips'; ?>"><?php echo translate("Photo tips."); ?></a></p>
                        </div>
                    </div>
                    <div id="photo_list" style="display:none;">
                        <p style="text-align:left;  font-size:15px; font-weight:bold; padding:0 10px 10px 10px;"><span> <?php echo translate_admin("Choose checkbox to delete photo and radio button for feature image"); ?> </span></p>

                        <form enctype="multipart/form-data" action="<?php echo site_url('rooms/edit_photo/' . $room_id); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback2, 'onComplete': completeCallback})">
                            <?php
                                echo '<div id="galleria_container">';
                                if (count($list_images) > 0)
                                {
                                    echo '<ul class="clearfix">';
                                    $i = 1;
                                    foreach ($list_images->result() as $image)
                                    {
                                        if ($image->is_featured == 1)
                                            $checked = 'checked="checked"';
                                        else
                                            $checked = '';

                                        $url = base_url() . 'images/' . $image->list_id . '/' . $image->name;
                                        echo '<li>';
                                        echo '<p><label><input type="checkbox" name="image[]" value="' . $image->id . '" /></label>';
                                        echo '<img src="' . $url . '" /><input type="radio" ' . $checked . ' name="is_main" value="' . $image->id . '" /></p>';
                                        echo '</li>';
                                        $i++;
                                    }
                                    echo '</ul><div class="clear"></div>';
                                    echo '</div>';
                                }
                            ?>
                            <div class="clear"></div>
                            <p style="padding:5px 0 10px 10px;"><button type="submit" class="btn blue gotomsg" name="update_photo"><span><span><?php echo translate("Update"); ?></span></span></button></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function showhide(id)
    {
        if (id == 1)
        {
            document.getElementById("new_photo").style.display = "block";
            document.getElementById("photo_list").style.display = "none";

            document.getElementById('upload-tab').className = 'selected';
            document.getElementById('edit-tab').className = '';
        }
        else
        {
            document.getElementById("photo_list").style.display = "block";
            document.getElementById("new_photo").style.display = "none";

            document.getElementById('edit-tab').className = 'selected';
            document.getElementById('upload-tab').className = '';
        }
    }


    /*  time out for link   */

    $('#uploadlistings').submit(function () {
        setTimeout(function () {
            $('#userfile').val('');
        }, 1500);
    });

    /*  time out for link   */
    function checkSize(max_img_size)
    {
        var input = document.getElementById("userfile");
        // check for browser support (may need to be modified)
        for (var i = 0; i < 100; i++)
        {
            if (input.files[i].size > max_img_size)
            {
                alert("The file must be " + (max_img_size / 1024 / 1024) + "MB or less");
                document.getElementById("userfile").value = "";
                window.uploadlistings.reset();
                return false;
            }
        }

        return true;
    }
</script>