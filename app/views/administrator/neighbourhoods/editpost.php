<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>
<script type="text/javascript">
    function get_cities(city) {
        $.ajax({
            type: 'GET',
            data: "city=" + city,
            url: "<?php echo base_url() . 'administrator/neighbourhoods/place_drop' ?>",
            success: function ($data) {

                $('#place').html($data);

            },
            error: function ($responseObj) {
                alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
            }
        });
    }
    function visitors(value)
    {
        if (value != 'none')
        {
            $('#vis_name').show();
            $('#vis_review').show();
        }
        else
        {
            $('#vis_name').hide();
            $('#vis_review').hide();
        }
    }
    $(document).ready(function ()
    {
        if ($('#visitor').val() == 'none')
        {
            $('#vis_name').hide();
            $('#vis_review').hide();
        }
        else
        {
            $('#vis_name').show();
            $('#vis_review').show();
        }
    })
</script>

<!-- <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
       <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
       <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script> -->
       <!-- <script type="text/javascript">
       $(document).ready(function() {
           $(function() {
               
               $( "#visitor_name" ).autocomplete({
             
                           source: function(request, response) {
                       $.ajax({ url: "<?php echo base_url() . 'administrator/neighbourhoods/visiter_name'; ?>",
                       data: 'val='+$("#visitor_name").val(),
                       dataType: "json",
                       type: "GET",
                       success: function(data){
                               response(data);
                      //alert(data);
                       }
                   });
               },
               minLength: 2
               });
           });
       });
       </script>-->
<script>
    $(document).ready(function ()
    {
        $('#form').submit(function ()
        {
            var city = $('#city').val();
            var place = $('#place').val();
            if (city == 'none')
            {
                alert('Please choose city');
                return false;
            }
            if (place == 'none')
            {
                alert('Please choose place');
                return false;
            }
        })
    })
</script>
<?php
    //Content of a group
    if (isset($posts) and $posts->num_rows() > 0)
    {
        $post = $posts->row();
        ?>
        <?php $city_id = $this->db->where('city_name', $post->city)->get('neigh_city')->row()->id;
        ?>
        <div id="Add_Email_Template">

            <div class="clsTitle">
                <h3><?php echo translate_admin("Edit Neighbourhood Place Posts"); ?></h3>
            </div> 
            <div class="MainTop_Links clearfix">
                <div class="clsNav">
                    <ul>
                        <li><a href="<?php echo admin_url('neighbourhoods/viewpost'); ?>"><?php echo translate_admin('Manage Posts'); ?></a></li>
                    </ul>
                </div>
                <div class="clsTitle">
                    <?php /* ?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */ ?>
                </div>
            </div>
            <div>
                <form method="post" id="form" action="<?php echo admin_url('neighbourhoods/editpost') ?>/<?php echo $post->id; ?>" enctype="multipart/form-data">					
                    <table width="700" class="table">
                        <tr>
                            <td><label><?php echo translate_admin('City'); ?><span class="clsRed"></span></label></td>
                            <td>

                                <select name='city' id="city" style="width:292px" onChange='get_cities(this.value)'>
                                    <?php
                                    foreach ($cities->result() as $row)
                                    {
                                        if ($row->city_name == $post->city)
                                        {
                                            $s = "selected='selected'";
                                        }
                                        else
                                        {
                                            $s = "";
                                        }
                                        echo '<option value="' . $row->city_name . '"' . $s . '>' . $row->city_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>		
                        <tr>
                            <td><label><?php echo translate_admin('Place'); ?><span class="clsRed"></span></label></td>
                            <td id="place">
                                <select name='place' id="place" style="width:292px">
                                    <?php
                                    foreach ($cities->result() as $row)
                                    {
                                        if ($row->city_name == $post->city)
                                        {
                                            $city = $row->city_name;
                                        }
                                    }
                                    $result = $this->db->select('place_name')->where('city_name', $city)->get('neigh_city_place');
                                    $place_id = $this->db->where('city_name', $city)->get('neigh_city_place')->row()->id;
                                    foreach ($result->result() as $row)
                                    {
                                        echo "<option value='" . $row->place_name . "'>" . $row->place_name . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label><?php echo translate_admin('Title'); ?><span class="clsRed"></span></label></td>
                            <td>
                                <input class="clsTextBox" size="30" type="text" name="image_title" id="image_title" value="<?php echo $post->image_title; ?>"/>
                                <?php echo form_error('image_title'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td><label><?php echo translate_admin('Description'); ?><span class="clsRed"></span></label></td>
                            <td>
                                <textarea class="clsTextBox" name="image_desc" id="image_desc" value="" style="height: 162px; width: 282px;" >
                                    <?php echo $post->image_desc; ?>
                                </textarea>
                                <?php echo form_error('image_desc'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Banner Image-1'); ?><span class="clsRed"></span></td>
                            <td>
                                <input id="big_image1" name="big_image1"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 995x663</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->big_image1; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Small Image'); ?>-1<span class="clsRed"></span></td>
                            <td>
                                <input id="small_image1" name="small_image1"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 485x304</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->small_image1; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Small Image'); ?>-2<span class="clsRed"></span></td>
                            <td>
                                <input id="small_image2" name="small_image2"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 483x304</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->small_image2; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Small Image'); ?>-3<span class="clsRed"></span></td>
                            <td>
                                <input id="small_image3" name="small_image3"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 315x286</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->small_image3; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Small Image'); ?>-4<span class="clsRed"></span></td>
                            <td>
                                <input id="small_image4" name="small_image4"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 315x286</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->small_image4; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Small Image'); ?>-5<span class="clsRed"></span></td>
                            <td>
                                <input id="small_image5" name="small_image5"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 315x286</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->small_image5; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Banner Image'); ?>-2<span class="clsRed"></span></td>
                            <td>
                                <input id="big_image2" name="big_image2"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 485x304</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->big_image2; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Banner Image'); ?>-3<span class="clsRed"></span></td>
                            <td>
                                <input id="big_image3" name="big_image3"  size="24" type="file"/>
                                <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 483x304</span>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img width="300" height="183" src="<?php echo base_url() . 'images/neighbourhoods/' . $city_id . '/' . $place_id . '/' . $post->big_image3; ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Is Featured'); ?>?</td>
                            <td>
                                <select name="is_home" id="is_home" >
                                    <option value="0"<?php
                                    if ($post->is_featured == "0")
                                    {
                                        echo "selected";
                                    }
                                    ?>> <?php echo translate_admin('No'); ?> </option>
                                    <option value="1"<?php
                                            if ($post->is_featured == "1")
                                            {
                                                echo "selected";
                                            }
                                            ?>> <?php echo translate_admin('Yes'); ?> </option>
                                </select> 
                            </td>
                        </tr> 
                        <tr>
                            <td><input type="hidden" name="id"  value="<?php echo $post->id; ?>"/></td>
                            <td>
                                <input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
                            </td>
                        </tr>

                    </table>
                </form>	
    <?php } ?>
    </div>






