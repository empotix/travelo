<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "textareas",
        theme: "advanced",
        elements: "elm1",
        plugins: "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        theme_advanced_toolbar_align: "left",
        theme_advanced_toolbar_location: "top",
        skin: "o2k7",
        skin_variant: "silver",
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
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
        if (isset($cancellations) and $cancellations->num_rows() > 0)
        {
            $cancellation = $cancellations->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit Cancellation Policy'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('cancellation/editCancellation') ?>/<?php echo $cancellation->id; ?>">
                <table class="table" cellpadding="2" cellspacing="0">

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Site Name'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="site_name" value="<?php echo $cancellation->site_name; ?>">
                        </td>
                    </tr>
                    <?php echo form_error('site_name'); ?> <br />

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Cancellation Policy Title'); ?><span class="clsRed">*</span></td>
                        <td>
                            <input class="" type="text" name="cancellation_title" value="<?php echo $cancellation->cancellation_title; ?>">
                        </td>
                    </tr>
                    <?php echo form_error('page_name'); ?> <br />

                    <tr>
                        <td class="clsName"><?php echo translate_admin('Cancellation Policy Content'); ?><span class="clsRed">*</span></td>
                        <td class="clsNoborder">
                            <textarea id="elm1" name="cancellation_content" rows="15" cols="80" style="width: 80%"><?php echo $cancellation->cancellation_content; ?></textarea>
                            <?php echo form_error('Cancellation_content'); ?>
                        </td>
                    </tr>

                    <tr>
                        <td></td>
                        <td>
                            <input type="hidden" name="cancellation_operation" value="edit" />
                            <input type="hidden" name="id"  value="<?php echo $cancellation->id; ?>"/>
                            <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>"  name="editCancellation"/></td>
                    </tr>  

                </table>
            </form>
            <?php
        }
    ?>
</div>

<script language="Javascript">
    jQuery("#is_footer").val('<?php echo $cancellation->is_footer; ?>');
</script>