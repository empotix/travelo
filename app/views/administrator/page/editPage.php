<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "textareas",
        theme: "advanced",
        editor_deselector: "mceNoEditor",
        plugins: "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",
        // Theme options
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect",
        theme_advanced_toolbar_location: "top",
        theme_advanced_toolbar_align: "left",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true
    });
</script>
<div class="Edit_Page">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <?php
        //Content of a group
        if (isset($pages) and $pages->num_rows() > 0)
        {
            $page = $pages->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit Page'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('page/editPage') ?>/<?php echo $page->id; ?>">
                <table class="table" cellpadding="2" cellspacing="0">

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Page Title'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="page_title" value="<?php echo $page->page_title; ?>">
                        </td>
                    </tr>
                    <?php echo form_error('page_title'); ?> <br />

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Page Name'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="page_name" value="<?php echo $page->page_name; ?>">
                        </td>
                    </tr>
                    <?php echo form_error('page_name'); ?> <br />

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Is link in footer') . '?'; ?></td>
                        <td>
                            <select name="is_footer" id="is_footer" >
                                <option value="0"> No </option>
                                <option value="1"> Yes </option>
                            </select> 
                        </td>
                    </tr>
                    <tr id='is_under_tr' style="display: none">
                        <td class="clsName"><?php echo translate_admin('Is link under the') . '?'; ?></td>
                        <td>
                            <select name="is_under" id="is_under" >
                                <option value="discover"> Discover </option>
                                <option value="company"> Company </option>
                            </select> 
                        </td>
                    </tr>
                    <tr>
                        <td class="clsName"><?php echo translate_admin('Page Content'); ?><span class="clsRed">*</span></td>
                        <td class="clsNoborder">
                            <textarea id="elm1" name="page_content" rows="15" cols="80" style="width: 80%"><?php echo $page->page_content; ?></textarea>
                            <?php echo form_error('page_content'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="page_operation" value="edit" />
                            <input type="hidden" name="id"  value="<?php echo $page->id; ?>"/>
                            <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="editPage"/></td>
                    </tr>  

                </table>
            </form>
            <?php
        }
    ?>
</div>

<script language="Javascript">
    jQuery("#is_footer").val('<?php echo $page->is_footer; ?>');
    if (<?php echo $page->is_footer; ?> == '1')
    {
        $('#is_under_tr').show();
    }
</script>
<script language="Javascript">
    jQuery("#is_under").val('<?php echo $page->is_under; ?>');
</script>
<script language="Javascript">
    $(document).ready(function () {
        $('#is_footer').change(function () {
            var footer = $("#is_footer option:selected").val();
            if (footer == '0')
            {
                $('#is_under_tr').hide();
            }
            else
            {
                $('#is_under_tr').show();
            }
        });
    });
</script>
