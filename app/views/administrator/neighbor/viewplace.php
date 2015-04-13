<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Test</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

        <SCRIPT LANGUAGE="JavaScript">
            $(document).ready(function () {
                // Handler for .ready() called.
                $(".Blck_Butt").click(function () {
                    var confirmation = confirm('Are you sure you wants to delete ?');
                    if (confirmation == true) {
                        return true;
                    } else {
                        return false;
                    }
                });
            });
            function show()
            {
                var country = document.getElementById('country').value;
                var dataString = "country=" + country;
                var combo = document.getElementById("state");

                b_url = "<?php echo base_url() . 'administrator/email/selectbox' ?>";
                $.ajax({
                    type: "GET",
                    url: b_url,
                    data: dataString,
                    success: function (data) {


                        combo.options.length = 0;
                        var option = document.createElement("option");
                        option.text = "select";
                        option.value = "";
                        try {
                            combo.add(option, null);
                        } catch (error) {
                            combo.add(option);
                        }



                        var split = data.split(',');



                        var i;
                        for (i = 0; i < split.length - 1; i++)
                        {
                            var option = document.createElement("option");


                            option.text = split[i];
                            option.value = split[i];
                            try {
                                combo.add(option, null);
                            } catch (error) {
                                combo.add(option);
                            }

                        }





                    }

                });

            }




            function show1()
            {
                var state = document.getElementById('state').value;
                var dataString = "state=" + state;
                var combo = document.getElementById("city");

                b_url = "<?php echo base_url() . 'administrator/email/selectbox1' ?>";
                $.ajax({
                    type: "GET",
                    url: b_url,
                    data: dataString,
                    success: function (data) {


                        combo.options.length = 0;
                        var option = document.createElement("option");
                        option.text = "select";
                        option.value = "";
                        try {
                            combo.add(option, null);
                        } catch (error) {
                            combo.add(option);
                        }



                        var split = data.split(',');



                        var i;
                        for (i = 0; i < split.length - 1; i++)
                        {
                            var option = document.createElement("option");


                            option.text = split[i];
                            option.value = split[i];
                            try {
                                combo.add(option, null);
                            } catch (error) {
                                combo.add(option);
                            }

                        }





                    }

                });

            }


            function show2()
            {
                setTimeout('document.test.submit()', 5000);
                //document.getElementById('filter').submit();


            }



        </script>

    </head>

    <body>
        <div id="View_Pages">
            <?php
                //Show Flash Message
                if ($msg = $this->session->flashdata('flash_message'))
                {
                    echo $msg;
                }
            ?>


            <div class="MainTop_Links clearfix">
                <div class="clsNav">
                    <ul>
                        <li class="clsNoBorder"><a href="<?php echo admin_url('email/addplace') ?>"><?php echo translate_admin('Add_Places'); ?></a></li>

                    </ul>
                </div>
                <div class="clsTitle">
                    <h3><?php echo translate_admin("View_Neighborhood_Places"); ?></h3>
                </div>
            </div>
            <div>
                <form name="test" id="filter" action="" method="post">
                    <select name="country" id="country" onchange="show()">
                        <option value="">Select Country</option>
                        <?php
                            $country = $this->db->query("SELECT distinct(country) FROM `neighbor_city`");
                            $results = $country->result_array();
                            foreach ($results as $country)
                            {
                                ?><option value="<?php echo $country['country']; ?>"><?php echo $country['country']; ?></option>		<?php
                            }
                        ?>
                    </select>
                    <select name="state" id="state" onchange="show1()">
                        <option value=""><?php echo translate_admin('Select_state'); ?></option>
                        <?php
                        ?>
                    </select>
                    <select name="city" id="city" >
                        <option value=""><?php echo translate_admin('Select_City'); ?></option>
                        <?php
                        ?>
                    </select>	
                    <input type="submit" id="submit" name="submit" value="Filter"/></form> 
            </div>

            <br />

            <?php
                if (!isset($_POST['submit']))
                {
                    ?>

                    <form action="<?php echo admin_url('email/deleteplace') ?>" name="managepage" method="post">
                        <table class="table" cellpadding="2" cellspacing="0">
                            <th></th>
                            <th><?php echo translate_admin('S.No'); ?></th>
                            <th><?php echo translate_admin('Country'); ?></th>
                            <th><?php echo translate_admin('State'); ?></th>
                            <th><?php echo translate_admin('City'); ?></th>
                            <th><?php echo translate_admin('areas'); ?></th>
                            <th><?php echo translate_admin('Action'); ?></th>

                            <?php
                            $i = 1;
                            if (isset($area) and $area->num_rows() > 0)
                            {
                                foreach ($area->result() as $page)
                                {

                                    $train = $this->db->query("SELECT * FROM `neighbor_city` WHERE `id` = '" . $page->city_id . "'");
                                    $results = $train->result_array();
                                    if ($train->num_rows() != 0)
                                    {
                                        ?>

                                        <tr>
                                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $page->id; ?>"  /> </td>
                                            <td><?php echo $i++; ?></td>

                                            <?php
                                            foreach ($results as $arrival)
                                            {
                                                $country = $arrival['Country'];
                                                $state = $arrival['State'];
                                                $city = $arrival['City'];
                                            }
                                            ?>

                                            <td><?php echo $country; ?></td>
                                            <td><?php echo $state; ?></td>
                                            <td><?php echo $city; ?></td>
                                            <td><?php echo $page->area; ?></td>
                                            <td><a href="<?php echo admin_url('email/editplace/' . $page->id) ?>">
                                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                                <a href="<?php echo admin_url('email/deleteplace/' . $page->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo base_url() ?>images/Delete.png" alt="Delete" title="Delete" /></a>
                                            </td>

                                        </tr><?php } ?>

                                    <?php
                                }//Foreach End
                            }//If End
                            else
                            {
                                echo '<tr><td colspan="5">' . translate_admin('No places Found') . '</td></tr>';
                            }
                            ?>
                        </table>
                        <br />
                        <p style="text-align:left">
                            <?php
                            $data = array(
                                'name' => 'delete',
                                'class' => 'Blck_Butt',
                                'value' => translate_admin('Delete List'),
                            );
                            echo form_submit($data);
                            ?></p>
                    </form>	
                </div>


                <?php
            }
            else
            {

                if ($_POST['city'] != "" && $_POST['country'] != "" && $_POST['state'] != "")
                {
                    $trains = $this->db->query("SELECT * FROM `neighbor_city` WHERE `city` = '" . $_POST['city'] . "'");
                    $result = $trains->result_array();
                    foreach ($result as $arrival)
                    {
                        $cityid = $arrival['id'];
                    }
                    ?>

                    <form action="<?php echo admin_url('email/deleteplace') ?>" name="managepage" method="post">
                        <table class="table" cellpadding="2" cellspacing="0">
                            <th></th>
                            <th><?php echo translate_admin('S.No'); ?></th>
                            <th><?php echo translate_admin('country'); ?></th>
                            <th><?php echo translate_admin('state'); ?></th>
                            <th><?php echo translate_admin('city'); ?></th>
                            <th><?php echo translate_admin('areas'); ?></th>
                            <th><?php echo translate_admin('Action'); ?></th>

                            <?php
                            $i = 1;
                            if (isset($area) and $area->num_rows() > 0)
                            {
                                foreach ($area->result() as $page)
                                {

                                    if ($page->city_id == $cityid)
                                    {
                                        ?>

                                        <tr>
                                            <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $page->id; ?>"  /> </td>
                                            <td><?php echo $i++; ?></td>

                                            <?php
                                            $train = $this->db->query("SELECT * FROM `neighbor_city` WHERE `id` = '" . $page->city_id . "'");
                                            $results = $train->result_array();
                                            foreach ($results as $arrival)
                                            {
                                                $country = $arrival['Country'];
                                                $state = $arrival['State'];
                                                $city = $arrival['City'];
                                            }
                                            ?>

                                            <td><?php echo $country; ?></td>
                                            <td><?php echo $state; ?></td>
                                            <td><?php echo $city; ?></td>
                                            <td><?php echo $page->area; ?></td>
                                            <td><a href="<?php echo admin_url('email/editplace/' . $page->id) ?>">
                                                    <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                                <a href="<?php echo admin_url('email/deleteplace/' . $page->id) ?>" onclick="return confirm('Are you sure want to delete??');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>
                                            </td>
                                        </tr>

                                        <?php
                                    } //if end vishnu
                                }//Foreach End
                            }//If End
                            else
                            {
                                echo '<tr><td colspan="5">' . translate_admin('No Places Found') . '</td></tr>';
                            }
                            ?>
                        </table>
                        <br />
                        <p style="text-align:left">
                            <?php
                            $data = array(
                                'name' => 'delete',
                                'class' => 'Blck_Butt',
                                'value' => translate_admin('Delete List'),
                            );
                            echo form_submit($data);
                            ?></p>
                    </form>	
                    </div>



                    <?php
                }
                else
                {
                    ?>

                    <form action="<?php echo admin_url('email/deleteplace') ?>" name="managepage" method="post">
                        <table class="table" cellpadding="2" cellspacing="0">
                            <th></th>
                            <th><?php echo translate_admin('S.No'); ?></th>
                            <th><?php echo translate_admin('country'); ?></th>
                            <th><?php echo translate_admin('state'); ?></th>
                            <th><?php echo translate_admin('city'); ?></th>
                            <th><?php echo translate_admin('areas'); ?></th>
                            <th><?php echo translate_admin('Action'); ?></th>

                            <?php
                            $i = 1;
                            if (isset($area) and $area->num_rows() > 0)
                            {
                                foreach ($area->result() as $page)
                                {
                                    ?>

                                    <tr>
                                        <td><input type="checkbox" class="clsNoborder" name="pagelist[]" id="pagelist[]" value="<?php echo $page->id; ?>"  /> </td>
                                        <td><?php echo $i++; ?></td>

                                        <?php
                                        $train = $this->db->query("SELECT * FROM `neighbor_city` WHERE `id` = '" . $page->city_id . "'");
                                        $results = $train->result_array();
                                        foreach ($results as $arrival)
                                        {
                                            $country = $arrival['Country'];
                                            $state = $arrival['State'];
                                            $city = $arrival['City'];
                                        }
                                        ?>

                                        <td><?php echo $country; ?></td>
                                        <td><?php echo $state; ?></td>
                                        <td><?php echo $city; ?></td>
                                        <td><?php echo $page->area; ?></td>
                                        <td><a href="<?php echo admin_url('email/editplace/' . $page->id) ?>">
                                                <img src="<?php echo base_url() ?>images/edit-new.png" alt="Edit" title="Edit" /></a>
                                            <a href="<?php echo admin_url('email/deleteplace/' . $page->id) ?>" onclick="return confirm('Are you sure want to delete?');"><img src="<?php echo image_url('delete-new.png'); ?>" alt="Delete" title="Delete" /></a>
                                        </td>
                                    </tr>

                                    <?php
                                }//Foreach End
                            }//If End
                            else
                            {
                                echo '<tr><td colspan="5">' . translate_admin('No Places Found') . '</td></tr>';
                            }
                            ?>
                        </table>
                        <br />
                        <p style="text-align:left">
                            <?php
                            $data = array(
                                'name' => 'delete',
                                'class' => 'Blck_Butt',
                                'value' => translate_admin('Delete List'),
                            );
                            echo form_submit($data);
                            ?></p>
                    </form>	
                    </div>
                    <script>alert("Please select all category");</script>
                    <?php
                }
            }
        ?>


    </body>
</html>
