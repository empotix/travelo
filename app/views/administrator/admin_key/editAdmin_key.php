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
        if (isset($Admin_key) and $Admin_key->num_rows() > 0)
        {
            $Admin_key = $Admin_key->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit Page'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('admin_key/editAdmin_key') ?>/<?php echo $Admin_key->id; ?>">
                <table class="table" cellpadding="2" cellspacing="0">

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Page_key'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="Admin_key" value="<?php echo $Admin_key->page_key; ?>">
                        </td>
                    </tr>
                    <?php echo form_error('Admin_key'); ?> <br />

                    <td class="clsName"><?php echo translate_admin('Status') . '?'; ?></td>
                    <td>
                        <select name="is_footer" id="is_footer" >
                            <option value="0"> Active </option>
                            <option value="1"> In Active </option>
                        </select> 
                    </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="page_operation" value="edit" />
                            <input type="hidden" name="id"  value="<?php echo $Admin_key->id; ?>"/>
                            <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="editAdmin_key"/></td>
                    </tr>  

                </table>
            </form>
            <?php
        }
    ?>
</div>

<script language="Javascript">
    jQuery("#is_footer").val('<?php echo $Admin_key->status; ?>');
</script>