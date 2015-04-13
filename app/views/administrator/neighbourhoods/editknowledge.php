<script>
    /*$(function()
     {
     
     $("form").submit(function() {
     $(this).submit(function() {
     return false;
     });
     return true;
     });
     
     });*/
</script>
<script type="text/javascript">
    function user_type_fun(value)
    {
        if (value == 'Host')
        {
            var post_id = $('#post_id :selected').text();
            $.ajax({
                type: 'GET',
                data: "post_id=" + post_id + "user_id=" +<?php echo $knowledges->row()->user_id; ?>,
                url: "<?php echo base_url() . 'administrator/neighbourhoods/get_room_id' ?>",
                success: function ($data) {
                    if ($data != 'Empty')
                    {
                        $('#room_id').replaceWith($data);
                    }
                    else
                    {
                        alert("Sorry! This User Don't have the List for this place.");
                        $('#room_id_tr').hide();
                        $('#rooms_title_tr').hide();
                        $('#user_type').val('Guest');
                    }
                },
                error: function ($responseObj) {
                    alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                }
            });
            $('#room_id_tr').show();
            $('#rooms_title_tr').show();

        }
        else
        {
            $('#room_id_tr').hide();
            $('#rooms_title_tr').hide();
        }
    }
    function get_title(id) {
        $.ajax({
            type: 'GET',
            data: "id=" + id,
            url: "<?php echo base_url() . 'administrator/neighbourhoods/rooms_title_drop' ?>",
            success: function ($data) {
                $('#rooms_title').html($data);

            },
            error: function ($responseObj) {
                alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
            }
        });
    }

    function get_room_id(post_id)
    {
        if ($('#user_type :selected').text() == 'Host')
        {
            $.ajax({
                type: 'GET',
                data: "post_id=" + post_id + "user_id=" +<?php echo $knowledges->row()->user_id; ?>,
                url: "<?php echo base_url() . 'administrator/neighbourhoods/get_room_id' ?>",
                success: function ($data) {
                    if ($data != 'Empty')
                    {
                        $('#room_id').replaceWith($data);
                    }
                    else
                    {
                        alert("Sorry! This User Don't have the List for this place.");
                        $('#room_id_tr').hide();
                        $('#rooms_title_tr').hide();
                        $('#user_type').val('Guest');
                    }
                },
                error: function ($responseObj) {
                    alert("Something went wrong while processing your request.\n\nError => "
                            + $responseObj.responseText);
                }
            });
        }
    }

    $.ajax({
        type: 'GET',
        data: "id=" +<?php echo $knowledges->row()->id; ?>,
        url: "<?php echo base_url() . 'administrator/neighbourhoods/check_user_type' ?>",
        success: function ($data) {
            if ($data == 'Host')
            {
                $('#room_id_tr').show();
                $('#rooms_title_tr').show();

            }
        }
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
        if (isset($knowledges) and $knowledges->num_rows() > 0)
        {
            $knowledge = $knowledges->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit Knowledge'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('neighbourhoods/editknowledge') ?>/<?php echo $knowledge->id; ?>" enctype="multipart/form-data">
                <table class="table" cellpadding="2" cellspacing="0">

                    <tr>
                        <td><label><?php echo translate_admin('Knowledge'); ?><span class="clsRed">*</span></label></td>
                        <td>
                            <textarea class="clsTextBox" name="knowledge" id="knowledge" style="height: 162px; width: 282px;" ><?php echo $knowledge->knowledge; ?></textarea>

                            <?php echo form_error('knowledge'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td><label><?php echo translate_admin('Post ID'); ?><span class="clsRed"></span></label></td>
                        <td id="post_id">
                            <select name='post_id' id="post_id" style="width:292px" onchange="get_room_id(this.value)">
                                <?php
                                if ($posts->num_rows() != 0)
                                {
                                    foreach ($posts->result() as $row)
                                    {
                                        $selected = $knowledge->post_id;
                                        ?>
                                        <option <?php if ($selected == $row->id) echo translate_admin('selected'); ?>> <?php echo $row->id; ?></option>
                                        <?php
                                    }
                                }
                                ?>	
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><label><?php echo translate_admin('User Type'); ?><span class="clsRed"></span></label></td>
                        <td>
                            <select name='user_type' id="user_type" style="width:292px" onChange='user_type_fun(this.value)'>
                                <option id="guest" <?php if ($knowledge->user_type == 'Guest') echo 'selected'; ?>><?php echo translate_admin('Guest'); ?></option>
                                <option id="host" <?php if ($knowledge->user_type == 'Host') echo 'selected'; ?>><?php echo translate_admin('Host'); ?></option>
                            </select>
                        </td>
                    </tr>

                    <tr id="room_id_tr" style="display: none">
                        <td><label><?php echo translate_admin('Room ID'); ?><span class="clsRed"></span></label></td>
                        <td>
                            <select name='room_id' id="room_id" style="width:292px" onChange='get_title(this.value)'>
                                <?php
                                if ($rooms->num_rows() != 0)
                                {
                                    foreach ($rooms->result() as $row)
                                    {
                                        $selected = $knowledge->room_id;
                                        ?>
                                        <option <?php if ($selected == $row->id) echo translate_admin('selected'); ?>> <?php echo $row->id; ?></option>
                                        <?php
                                    }
                                }
                                ?>	
                            </select>
                        </td>
                    </tr>
                    <tr id="rooms_title_tr" style="display: none">
                        <td><label><?php echo translate_admin('Room Title'); ?><span class="clsRed"></span></label></td>
                        <td id="rooms_title">
                            <input type="text" name='room_title' id="rooms_title" value="<?php echo $knowledge->room_title; ?>" style="width:292px" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="clsName"><?php echo translate_admin('Is Shown') . '?'; ?></td>
                        <td>
                            <select name="is_shown" id="is_shown">
                                <option value="0"<?php
                                if ($knowledge->shown == "0")
                                {
                                    echo "selected";
                                }
                                ?>> <?php echo translate_admin('No'); ?> </option>
                                <option value="1"<?php
                                        if ($knowledge->shown == "1")
                                        {
                                            echo "selected";
                                        }
                                        ?>> <?php echo translate_admin('Yes'); ?> </option>
                            </select>  
                        </td>
                    </tr>

                    <tr>
                        <td><input type="hidden" name="id"  value="<?php echo $knowledge->id; ?>"/></td>
                        <td><input  name="submit" type="submit" value="Submit"></td>
                    </tr>  

                </table>
            </form>
        <?php
    }
?>
</div>
