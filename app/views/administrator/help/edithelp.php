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
<div class="Edit_help">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <?php
        //Content of a group
        if (isset($helps) and $helps->num_rows() > 0)
        {
            $help = $helps->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit help'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('help/edithelp') ?>/<?php echo $help->id; ?>">
                <table class="table" cellpadding="2" cellspacing="0">

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Help title'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="question" value="<?php echo $help->question; ?>">
                        </td>
                    </tr>
                    <?php echo form_error('question'); ?> <br />

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Page refer'); ?><span class="clsRed">*</span></td>
                        <td>
                            <select style="width:200px;" class="fixed-width" name="page_refer" value="<?php echo $help->page_refer; ?>" ><?php //echo $help->page_refer;  ?>
                                <option value="guide"<?php
                                if ($help->page_refer == "guide")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Guide'); ?></option>
                                <option value="home"<?php
                                        if ($help->page_refer == "Home")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('Home'); ?></option>
                                <option value="dashboard"<?php
                                if ($help->page_refer == "dashboard")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Dashboard'); ?></option>
                                <option value="inbox"<?php
                                        if ($help->page_refer == "inbox")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('Inbox'); ?></option>
                                <option value="hosting"<?php
                                if ($help->page_refer == "hosting")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Hosting'); ?></option>
                                <option value="account"<?php
                                        if ($help->page_refer == "account")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('Account'); ?></option>
                                <option value="policies"<?php
                                if ($help->page_refer == "policies")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Policies'); ?></option>
                                <option value="payout"<?php
                                        if ($help->page_refer == "payout")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('Payout'); ?></option>
                                <option value="edit"<?php
                                if ($help->page_refer == "edit")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Edit'); ?></option>
                                <option value="new"<?php
                                        if ($help->page_refer == "new")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('New'); ?></option>
                                <option value="change_password"<?php
                                if ($help->page_refer == "change_password")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Change_password'); ?></option>
                                <option value="recommendation"<?php
                                        if ($help->page_refer == "recommendation")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('Recommendation'); ?></option>
                                <option value="reviews"<?php
                                if ($help->page_refer == "reviews")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Reviews'); ?></option>
                                <option value="profile"<?php
                                        if ($help->page_refer == "profile")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('Profile'); ?></option>
                                <option value="my_reservation"<?php
                                if ($help->page_refer == "my_reservation")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('My_reservation'); ?></option>
                                <option value="current_trip"<?php
                                        if ($help->page_refer == "current_trip")
                                        {
                                            echo "selected";
                                        }
                                        ?>><?php echo translate_admin('Current_trip'); ?></option>
                                <option value="upcomming_trips"<?php
                                if ($help->page_refer == "upcomming_trips")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Upcoming_trips'); ?></option>
                                <option value="previous_trips"<?php
                                if ($help->page_refer == "previous_trips")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Previous_trips'); ?></option>
                                <option value="starred_items"<?php
                                if ($help->page_refer == "starred_items")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Starred_items'); ?></option>
                                <option value="transaction"<?php
                                if ($help->page_refer == "transaction")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Transaction'); ?></option>
                                <option value="mywishlist"<?php
                                if ($help->page_refer == "mywishlist")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Mywishlist'); ?></option>
                                <option value="search"<?php
                                if ($help->page_refer == "search")
                                {
                                    echo "selected";
                                }
                                ?>><?php echo translate_admin('Search'); ?></option>
                                <option value="signin"<?php
            if ($help->page_refer == "signin")
            {
                echo "selected";
            }
            ?>><?php echo translate_admin('Signin'); ?></option>
                                <option value="signup"<?php
            if ($help->page_refer == "signup")
            {
                echo "selected";
            }
            ?>><?php echo translate_admin('Signup'); ?></option>
                            </select>
                        </td>
                    </tr>
        <?php echo form_error('page_refer'); ?> <br />

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
                        <td class="clsName"><?php echo translate_admin('Help content'); ?><span class="clsRed">*</span></td>
                        <td class="clsNoborder">
                            <textarea id="elm1" name="description" rows="15" cols="80" style="width: 80%"><?php echo $help->description; ?></textarea>
        <?php echo form_error('description'); ?>

                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="help_operation" value="edit" />
                            <input type="hidden" name="id"  value="<?php echo $help->id; ?>"/>
                            <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="edithelp"/></td>
                    </tr>  

                </table>
            </form>
        <?php
    }
?>
</div>
<script language="Javascript">
    jQuery("#status").val('<?php echo $help->status; ?>');
    jQuery("#page_refer").val('<?php echo $help->page_refer; ?>');
</script>