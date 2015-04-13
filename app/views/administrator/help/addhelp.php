<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "textareas",
        theme: "advanced",
        elements: "elm1",
        plugins: "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        theme_advanced_toolbar_align: "left",
        theme_advanced_toolbar_location: "top",
        skin: "o2k7",
        skin_variant: "silver",
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true

    });
</script>

<div class="View_Addhelp">

    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <div class="clsTitle"><h3><?php echo translate_admin('Add help'); ?></h3></div>
    <form method="post" action="<?php echo admin_url('help/addhelp') ?>">
        <table class="table" cellpadding="2" cellspacing="0">

           <!-- <tr>
               <td class="clsName"><?php echo translate_admin('help Name'); ?><span class="clsRed">*</span></td>
               <td class="clsMailID"><input type="text" name="help_name" value="<?php echo set_value('help_name'); ?>"><?php echo form_error('help_name'); ?></td>
           </tr>-->

            <tr>
                <td class="clsName"><?php echo translate_admin('Help title'); ?><span class="clsRed">*</span></td>
                <td><input type="text" name="question" value="<?php echo set_value('question'); ?>"> <?php echo form_error('question'); ?> </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Page refer'); ?><span class="clsRed">*</span></td>
               <!-- <td><input type="text" name="page_refer" value="<?php echo set_value('page_refer'); ?>"><?php echo form_error('page_refer'); ?></td>-->
                <td>
                    <select style="width:200px;" class="fixed-width" id="page-refer" name="page_refer" value="<?php echo set_value('page_refer'); ?>">
                        <option value="guide"><?php echo translate_admin('Guide'); ?></option>
                        <option value="home"><?php echo translate_admin('Home'); ?></option>
                        <option value="dashboard"><?php echo translate_admin('Dashboard'); ?></option>
                        <option value="inbox"><?php echo translate_admin('Inbox'); ?></option>
                        <option value="hosting"><?php echo translate_admin('Hosting'); ?></option>
                        <option value="account"><?php echo translate_admin('Account'); ?></option>
                        <option value="payout"><?php echo translate_admin('Payout'); ?></option>
                        <option value="policies"><?php echo translate_admin('Policies'); ?></option>
                        <option value="edit"><?php echo translate_admin('Edit'); ?></option>
                        <option value="new"><?php echo translate_admin('New'); ?></option>
                        <option value="change_password"><?php echo translate_admin('Change_password'); ?></option>
                        <option value="recommendation"><?php echo translate_admin('Recommendation'); ?></option>
                        <option value="reviews"><?php echo translate_admin('Reviews'); ?></option>
                        <option value="profile"><?php echo translate_admin('Profile'); ?></option>
                        <option value="my_reservation"><?php echo translate_admin('My_reservation'); ?></option>
                        <option value="current_trip"><?php echo translate_admin('Current_trip'); ?></option>
                        <option value="upcomming_trips"><?php echo translate_admin('Upcoming_trips'); ?></option>
                        <option value="previous_trips"><?php echo translate_admin('Previous_trips'); ?></option>
                        <option value="starred_items"><?php echo translate_admin('Starred_items'); ?></option>
                        <option value="transaction"><?php echo translate_admin('Transaction'); ?></option>
                        <option value="mywishlist"><?php echo translate_admin('Mywishlist'); ?></option>
                        <option value="search"><?php echo translate_admin('Search'); ?></option>
                        <option value="signin"><?php echo translate_admin('Signin'); ?></option>
                        <option value="signup"><?php echo translate_admin('Signup'); ?></option>
                    </select>
                </td>
            </tr>

            <tr>

                <td class="clsName"><?php echo translate_admin('Status'); ?></td>
                <td>
                    <select style="width:200px;" class="fixed-width" id="status" name="status" value="<?php echo set_value('status'); ?>">
                        <option value="0">Active</option>
                        <option value="1">In Active</option>
                    </select> 
                </td>
            </tr>

            <tr>
                <td class="clsName"> 
                    <?php echo translate_admin('Help content'); ?><span class="clsRed">*</span></td><td>
                    <textarea id="elm1" name="description" rows="15" cols="80" style="width: 80%"></textarea>
                    <?php echo form_error('description'); ?>
                </td>
            </tr>

            <tr>
                <td></td>    
                <td>
                    <input type="hidden" name="help_operation" value="add"  />
                    <input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addhelp" type="submit">
                </td>
            </tr>

        </table>
    </form>
</div>
