<script type="text/javascript">
    function startCallback() {
        document.getElementById('message').innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback(response) {
        if (response.length > 50)
        {
            window.location.href = "<?php echo base_url() . 'administrator'; ?>";
        }
        else
        {
            document.getElementById('message').innerHTML = response;
        }
    }

    function startCallback2() {
        document.getElementById('message2').innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback2(response) {

        if (response.length > 50)
        {
            window.location.href = "<?php echo base_url() . 'administrator'; ?>";
        }
        else
        {
            document.getElementById('message2').innerHTML = response;
        }
    }

    function startCallback3() {
        document.getElementById('message3').innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';
        // make something useful before submit (onStart)
        $('#submit_dis').show();
        $('#image_upload').hide();
        return true;
    }

    function completeCallback3(response) {
        var res = response;
        var getSplit = res.split('#');
        document.getElementById('galleria_container').innerHTML = getSplit[0];
        document.getElementById('message3').innerHTML = getSplit[1];
        window.photos_form.reset();
        $('#submit_dis').hide();
        $('#image_upload').show();
    }

    function startCallback4() {
        document.getElementById('message4').innerHTML = '<img src="<?php echo base_url() . 'images/loading.gif' ?>">';
        // make something useful before submit (onStart)
        return true;
    }

    function completeCallback4(response) {
        document.getElementById('message4').innerHTML = response;
    }

</script>


<script type="text/javascript" src="<?php echo base_url() ?>js/webtoolkit.aim.js"></script>
<div id="View_Edit_List">

    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a id="priceA" href="javascript:showhide('4');"><b><?php echo translate_admin('Pricing'); ?></b></a></li>
                <li><a id="photoA" href="javascript:showhide('3');"><b><?php echo translate_admin('Photos'); ?></b></a></li>
                <li><a id="aminitiesA" href="javascript:showhide('2');"><b><?php echo translate_admin('Aminities'); ?></b></a></li>
                <li><a id="descriptionA" class="clsNav_Act" href="javascript:showhide('1');"><b><?php echo translate_admin('Description'); ?></b></a></li>
            </ul>
        </div>
        <div class="clsTitle">
            <h3><?php echo translate_admin('Edit Listing'); ?></h3>
        </div>
    </div>
    <div id="description">
        <form action="<?php echo admin_url('lists/managelist'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">
            <table class="table">
                <tr>
                    <td><?php echo translate_admin("Property type"); ?></td>
                    <td>
                        <select style="width:200px;" class="fixed-width" id="hosting_property_type_id" name="property_id">
                            <?php
                                if ($property_types->num_rows() != 0)
                                {
                                    foreach ($property_types->result() as $row)
                                    {
                                        ?>
                                        <option value="<?php echo $row->id; ?>" <?php if ($result->property_id == $row->id) echo 'selected=selected'; ?>> <?php echo $row->type; ?></option>
                                        <?php
                                    }
                                }
                            ?>

                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Room type"); ?></td>
                    <td>
                        <select style="width:200px;" class="fixed-width" id="hosting_room_type" name="room_type">
                            <option value="Private room" <?php if ($result->room_type == 'Private room') echo 'selected=selected'; ?>><?php echo translate_admin("Private room"); ?></option>
                            <option value="Shared room" <?php if ($result->room_type == 'Shared room') echo 'selected=selected'; ?>><?php echo translate_admin("Shared room"); ?></option>
                            <option value="Entire Home/Apt" <?php if ($result->room_type == 'Entire Home/Apt') echo 'selected=selected'; ?>><?php echo translate_admin("Entire home/apt"); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Accommodates"); ?></td>
                    <td>
                        <select style="width:200px;" class="fixed-width" id="hosting_person_capacity" name="capacity">
                            <?php for ($i = 1; $i <= 16; $i++)
                                {
                                    ?>
                                    <option value="<?php echo $i; ?>" <?php if ($result->capacity == $i) echo 'selected'; ?>><?php echo $i;
                                if ($i == 16)
                                    echo '+';
                                    ?>
                                    </option>
    <?php } ?>
                        </select>

                    </td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Bedrooms"); ?></td>
                    <td>
                        <select style="width:200px;" class="fixed-width" id="hosting_bedrooms" name="bedrooms">
                                <?php for ($i = 1; $i <= 16; $i++)
                                    {
                                        ?>
                                    <option value="<?php echo $i; ?>"<?php if ($result->bedrooms == $i) echo 'selected'; ?>><?php echo $i;
                            if ($i == 16)
                                echo '+';
                            ?> </option>
    <?php } ?>
                        </select>
                    </td>
                </tr>


                <tr>
                    <td><?php echo translate_admin("Beds"); ?></td>
                    <td>
                        <select class="fixed-width" id="hosting_beds" name="beds">
<?php for ($i = 1; $i <= 16; $i++)
    {
        ?>
                                    <option value="<?php echo $i; ?>"<?php if ($result->beds == $i) echo 'selected'; ?>><?php echo $i;
        if ($i == 16)
            echo '+';
        ?> </option>
    <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Bed type"); ?></td>
                    <td>
                        <select class="fixed-width" id="hosting_bed_type" name="hosting_bed_type">
                            <option value="Airbed"<?php if ($result->bed_type == 'Airbed') echo 'selected'; ?>><?php echo translate_admin("Airbed"); ?></option>
                            <option value="Futon"<?php if ($result->bed_type == 'Futon') echo 'selected'; ?>><?php echo translate_admin("Futon"); ?></option>
                            <option value="Pull-out Sofa"<?php if ($result->bed_type == 'Pull-out Sofa') echo 'selected'; ?>><?php echo translate_admin("Pull-out Sofa"); ?></option>
                            <option value="Couch"<?php if ($result->bed_type == 'Couch') echo 'selected'; ?>><?php echo translate_admin("Couch"); ?></option>
                            <option value="Real Bed"<?php if ($result->bed_type == 'Real Bed') echo 'selected'; ?>><?php echo translate_admin("Real Bed"); ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Bathrooms"); ?></td>
                    <td>
                        <select name="hosting_bathrooms" id="hosting_bathrooms" class="fixed-width">
                            <option selected="selected" value=""></option>
                            <option value="0"<?php if ($result->bathrooms == '0') echo 'selected'; ?>>0</option> 
                            <option value="0.5"<?php if ($result->bathrooms == '0.5') echo 'selected'; ?>>0.5 </option>
                            <option value="1"<?php if ($result->bathrooms == '1') echo 'selected'; ?>>1 </option>
                            <option value="1.5"<?php if ($result->bathrooms == '1.5') echo 'selected'; ?>>1.5 </option>
                            <option value="2"<?php if ($result->bathrooms == '2') echo 'selected'; ?>>2 </option>
                            <option value="2.5"<?php if ($result->bathrooms == '2.5') echo 'selected'; ?>>2.5 </option>
                            <option value="3"<?php if ($result->bathrooms == '3') echo 'selected'; ?>>3 </option>
                            <option value="3.5"<?php if ($result->bathrooms == '3.5') echo 'selected'; ?>>3.5 </option>
                            <option value="4"<?php if ($result->bathrooms == '4') echo 'selected'; ?>>4 </option>
                            <option value="4.5"<?php if ($result->bathrooms == '4.5') echo 'selected'; ?>>4.5 </option>
                            <option value="5"<?php if ($result->bathrooms == '5') echo 'selected'; ?>>5 </option>
                            <option value="5.5"<?php if ($result->bathrooms == '5.5') echo 'selected'; ?>>5.5 </option>
                            <option value="6"<?php if ($result->bathrooms == '6') echo 'selected'; ?>>6 </option>
                            <option value="6.5"<?php if ($result->bathrooms == '6.5') echo 'selected'; ?>>6.5 </option>
                            <option value="7"<?php if ($result->bathrooms == '7') echo 'selected'; ?>>7 </option>
                            <option value="7.5"<?php if ($result->bathrooms == '7.5') echo 'selected'; ?>>7.5 </option>
                            <option value="8"<?php if ($result->bathrooms == '8') echo 'selected'; ?>>8+ </option>
                        </select>
                    </td>
                </tr>


                <tr>
                    <td><?php echo translate_admin("Title"); ?></td>
                    <td><input type="text" size="28" name="title" value="<?php echo $result->title; ?>">
                    </td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Address"); ?></td>
                    <td><input type="text" size="28" name="address" value="<?php echo $result->address; ?>"></td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Cancellation Policy"); ?></td>
                            <?php //print_r($result); exit; ?>
                    <td>
                        <select name="cancellation_policy" id="cancellation_policy" class="fixed-width">
                            <option value="Flexible"<?php
                                if ($result->cancellation_policy == "Flexible")
                                {
                                    echo "selected";
                                }
                            ?>><?php echo translate_admin("Flexible"); ?></option>
                            <option value="Moderate" <?php
                            if ($result->cancellation_policy == "Moderate")
                            {
                                echo "selected";
                            }
                            ?>><?php echo translate_admin("Moderate"); ?></option>
                            <option value="Strict"<?php
                                    if ($result->cancellation_policy == "Strict")
                                    {
                                        echo "selected";
                                    }
                            ?>><?php echo translate_admin("Strict"); ?></option>
                            <option value="Super Strict"<?php
                                    if ($result->cancellation_policy == "Super Strict")
                                    {
                                        echo "selected";
                                    }
                            ?>><?php echo translate_admin("Super Strict"); ?></option>
                            <option value="Long Term"<?php
                                    if ($result->cancellation_policy == "Long Term")
                                    {
                                        echo "selected";
                                    }
                            ?>><?php echo translate_admin("Long Term"); ?></option> 
                        </select>
                    </td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("House Manual"); ?></td>
                    <td><textarea id="hosting_house_manual" name="manual" size="115"><?php echo $result->house_rule; ?></textarea></td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Description"); ?></td>
                    <td><textarea name="desc"><?php echo $result->desc; ?></textarea></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <div class="clearfix">
                            <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update_desc" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" /></span>
                            <span style="float:left;"><div id="message"></div></span>
                        </div>
                    </td>
                </tr>
                <input type="hidden" name="list_id" value="<?php echo $result->id; ?>">

            </table> 
        </form>
    </div>


    <div id="aminities" style="display:none;">
        <div class="clsFloatLeft" style="width:98%">
            <form action="<?php echo admin_url('lists/managelist'); ?>" method="post" onsubmit="return AIM.submit(this, {'onStart': startCallback2, 'onComplete': completeCallback2})">
                <p style="text-align:left; border-top:4px solid #E3E3E3;">&nbsp;</p>
                <div class="clearfix">
                    <?php
                        $in_arr = explode(',', $result->amenities);
                        $tCount = $amnities->num_rows();
                        $i = 1;
                        $j = 1;
                        foreach ($amnities->result() as $rows)
                        {
                            if ($i == 1)
                                echo '<ul class="amenity_column">';
                            ?>
                            <li>
                                <input type="checkbox" <?php if (in_array($j, $in_arr)) echo 'checked="checked"'; ?> name="amenities[]" id="amenity_<?php echo $j; ?>" value="<?php echo $j; ?>">
                                <label for="amenity_<?php echo $j; ?>"><?php echo $rows->name; ?> <a title="<?php echo $rows->description; ?>" class="tooltip"><img style="width:16px; height:16px;" src="<?php echo base_url(); ?>images/questionmark_hover.png" alt="Questionmark_hover"></a> </label>
                            </li>
        <?php
        if ($i == 8)
        {
            $i = 0;
            echo '</ul>';
        }
        else if ($j == $tCount)
        {
            echo '</ul>';
        } $i++;
        $j++;
    }
?>


                </div>

                <input type="hidden" name="list_id" value="<?php echo $result->id; ?>">
                <div style="clear:both"></div>


                <div class="clearfix">
                    <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update_aminities" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" /></span>
                    <span style="float:left; padding:20px 0 0 0;"><div id="message2"></div></span>
                </div>
            </form>
        </div>
        <div style="clear:both"></div>
    </div>

    <div id="photo" style="display:none; text-align:left;">
        <div class="clsFloatLeft" style="width:98%">
            <form enctype="multipart/form-data" action="<?php echo admin_url('lists/managelist'); ?>" method="post" id="photos_form" onsubmit="return AIM.submit(this, {'onStart': startCallback3, 'onComplete': completeCallback3})">
                <p style="text-align:left; border-top:4px solid #E3E3E3; padding:10px 0 10px;">
                    <?php
                        if ($list_images->num_rows() > 0)
                        {
                            ?>
                            <span> <?php echo translate_admin("Choose checkbox to delete photo and radio button for feature image"); ?> </span>
                            <?php
                        }
                    ?>
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
                                echo '<img src="' . $url . '" width="150" height="150" /><input type="radio" ' . $checked . ' name="is_main" value="' . $image->id . '" /></p>';
                                echo '</li>';
                                $i++;
                            }
                            echo '</ul>';
                            echo '</div>';
                        }
                    ?>

                </p>
                <input type="hidden" name="list_id" value="<?php echo $result->id; ?>">
                <p> <span style="margin:0 10px 0 0;"> <?php echo translate_admin("Upload new photo"); ?> </span>
                    <input id="new_photo_image" name="userfile"  size="24" type="file" />
                </p>
                <script>
                    $(document).ready(function ()
                    {
                        $('#image_upload').click(function ()
                        {
                            var ext = $('#new_photo_image').val().split('.').pop();

                            if ($('#new_photo_image').val() == '')
                            {
                                // extension is not allowed 

                            }
                            else if (!(ext && /^(jpg|png|jpeg|gif)$/.test(ext)))
                            {
                                alert('Please choose the correct file or No one file is choosed.');
                                window.photos_form.reset();
                                return false;
                            }
                        })
                    }
                    )
                </script>
                <div class="clearfix">
                    <span style="float:left; margin:0 10px 0 0;">
                        <input class="clsSubmitBt1" type="submit" name="update_photo" id="image_upload" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" />
                    </span>
                    <span style="float:left; margin:0 10px 0 0;display: none;" id="submit_dis">
                        <input class="clsSubmitBt1" type="submit" name="update_photo" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" disabled/>
                    </span>	
                    <span style="float:left; padding:20px 0 0 0;"><div id="message3"></div></span>
                </div>
            </form>
        </div>
        <div style="clear:both"></div>
    </div>
    <script type="text/javascript" src="<?php echo base_url() . 'js/jquery.validate.js'; ?>"></script>
    <script src="<?php echo js_url(); ?>/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>
    <script>

                    // When the browser is ready...
                    $(function () {
                        $.validator.addMethod('minStrict', function (value, el, param) {
                            return value > param;
                        });
                        // Setup form validation on the #register-form element
                        $("#edit_price").validate({
                            // Specify the validation rules
                            rules: {
                                nightly: {required: true, number: true, minStrict: 0},
                                weekly: {required: true, number: true, minStrict: 0},
                                monthly: {required: true, number: true, minStrict: 0},
                                extra: {required: true, number: true, minStrict: 0},
                                cleaning: {required: true, number: true, minStrict: 0},
                                security: {required: true, number: true, minStrict: 0},
                            },
                            // Specify the validation error messages
                            messages: {
                                nightly: {required: "Please enter the nightly price",
                                    number: "Please enter the number.",
                                    minStrict: "Please give the more than 0."},
                                weekly: {required: "Please enter the weekly price",
                                    number: "Please enter the number.",
                                    minStrict: "Please give the more than 0."},
                                monthly: {required: "Please enter the monthly price",
                                    number: "Please enter the number.",
                                    minStrict: "Please give the more than 0."},
                                extra: {required: "Please enter the extra price",
                                    number: "Please enter the number.",
                                    minStrict: "Please give the more than 0."},
                                cleaning: {required: "Please enter the cleaning price",
                                    number: "Please enter the number.",
                                    minStrict: "Please give the more than 0."},
                                security: {required: "Please enter the security price",
                                    number: "Please enter the number.",
                                    minStrict: "Please give the more than 0."}
                            },
                            submitHandler: function (form) {
                                form.submit();
                            }
                        });

                    });

    </script>
    <div id="price" style="display:none;">
        <form action="<?php echo admin_url('lists/managelist'); ?>" method="post" id="edit_price" onsubmit="return AIM.submit(this, {'onComplete': completeCallback4})">
            <table class="table">

                <tr>
                    <td><?php echo translate_admin("Nightly"); ?>*</td>
                    <td><input type="text" name="nightly" value="<?php echo $price->night; ?>"></td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Weekly"); ?>*</td>
                    <td><input type="text" name="weekly" value="<?php echo $price->week; ?>"></td>
                </tr>


                <tr>
                    <td><?php echo translate_admin("Monthly"); ?>*</td>
                    <td><input type="text" name="monthly" value="<?php echo $price->month; ?>"></td>
                </tr>


                <tr>
                    <td><?php echo translate_admin("Additional Guests"); ?>*</td>
                    <td>
                        <input id="hosting_price_for_extra_person_native" name="extra" size="30" type="text" value=<?php echo $price->addguests; ?> />
                        &nbsp;<?php echo translate_admin("Per night for each guest after"); ?>                 
                        <select id="hosting_guests_included" name="guests">
<?php for ($i = 1; $i <= 16; $i++)
    {
        ?>
                                    <option value="<?php echo $i; ?>"><?php echo $i;
        if ($i == 16)
            echo '+';
        ?> </option>
    <?php } ?>
                        </select>
                    </td>
                </tr>


                <tr>
                    <td><?php echo translate_admin("Cleaning Fees"); ?>*</td>
                    <td><input id="hosting_extras_price_native" name="cleaning" size="30" type="text" value="<?php echo $price->cleaning; ?>"></td>
                </tr>

                <tr>
                    <td><?php echo translate_admin("Security Fees"); ?>*</td>
                    <td><input id="hosting_security_price_native" name="security" size="30" type="text" value="<?php echo $price->security; ?>"></td>
                </tr>

                <tr>
                    <td></td>
                    <td>
                        <div class="clearfix">
                            <span style="float:left; margin:0 10px 0 0;"><input class="clsSubmitBt1" type="submit" name="update_price" value="<?php echo translate_admin("Update"); ?>" style="width:90px;" /></span>
                            <span style="float:left; padding:0 0 0 0;"><div id="message4"></div></span>
                        </div>
                    </td>
                </tr>
                <input type="hidden" name="list_id" value="<?php echo $result->id; ?>">

            </table> 
        </form>
    </div>

</div>


<!-- TinyMCE inclusion -->
<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>

<script language="Javascript">

            jQuery("#property_id").val('<?php echo $result->property_id; ?>');
            jQuery("#room_type").val('<?php echo $result->room_type; ?>');

            jQuery("#hosting_person_capacity").val('<?php echo $result->capacity; ?>');
            jQuery("#hosting_bedrooms").val('<?php echo $result->bedrooms; ?>');
            jQuery("#hosting_beds").val('<?php echo $result->beds; ?>');
            jQuery("#hosting_bed_type").val('<?php echo $result->bed_type; ?>');
            jQuery("#hosting_bathrooms").val('<?php echo $result->bathrooms; ?>');

            jQuery("#hosting_native_currency").val('<?php echo $price->currency; ?>');

            jQuery("#hosting_guests_included").val('<?php if (isset($price->guests))
        echo $price->guests;
    else
        echo '1';
?>');

            tinyMCE.init({
                mode: "textareas",
                theme: "advanced",
                plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
                // Theme options
                theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
                theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
                theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
                theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
                theme_advanced_toolbar_location: "top",
                theme_advanced_toolbar_align: "left",
                theme_advanced_statusbar_location: "bottom",
                theme_advanced_resizing: true
            });

</script>  
<!-- End of inclusion of files -->
<script type="text/javascript">
    function showhide(id)
    {
        if (id == 1)
        {
            document.getElementById("description").style.display = "block";
            document.getElementById("aminities").style.display = "none";
            document.getElementById("photo").style.display = "none";
            document.getElementById("price").style.display = "none";

            document.getElementById('descriptionA').className = 'clsNav_Act';
            document.getElementById('aminitiesA').className = '';
            document.getElementById('photoA').className = '';
            document.getElementById('priceA').className = '';
        }
        else if (id == 2)
        {
            document.getElementById("aminities").style.display = "block";
            document.getElementById("description").style.display = "none";
            document.getElementById("photo").style.display = "none";
            document.getElementById("price").style.display = "none";

            document.getElementById('descriptionA').className = '';
            document.getElementById('aminitiesA').className = 'clsNav_Act';
            document.getElementById('photoA').className = '';
            document.getElementById('priceA').className = '';
        }
        else if (id == 3)
        {
            document.getElementById("photo").style.display = "block";
            document.getElementById("description").style.display = "none";
            document.getElementById("aminities").style.display = "none";
            document.getElementById("price").style.display = "none";

            document.getElementById('descriptionA').className = '';
            document.getElementById('aminitiesA').className = '';
            document.getElementById('photoA').className = 'clsNav_Act';
            document.getElementById('priceA').className = '';
        }
        else
        {
            document.getElementById("price").style.display = "block";
            document.getElementById("description").style.display = "none";
            document.getElementById("aminities").style.display = "none";
            document.getElementById("photo").style.display = "none";

            document.getElementById('descriptionA').className = '';
            document.getElementById('aminitiesA').className = '';
            document.getElementById('photoA').className = '';
            document.getElementById('priceA').className = 'clsNav_Act';
        }
    }
</script>
