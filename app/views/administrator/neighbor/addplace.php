<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>


<div id="Add_Email_Template">

    <div class="clsTitle">
        <h3><?php echo translate_admin("Add_Neighborhood_Places"); ?></h3>
    </div> 
    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('email/viewplace'); ?>"><?php echo translate_admin('View_places'); ?></a></li>
            </ul>
        </div>
        <div class="clsTitle">
            <?php /* ?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */ ?>
        </div>
    </div>
    <div>
        <form method="post" action="<?php echo admin_url('email/addplace') ?>">					
            <table width="700" class="table">

                <tr>
                    <td><label><?php echo translate_admin('Select_country'); ?><span class="clsRed">*</span></label></td>
                    <td>

                        <select onchange="print_state('state', this.selectedIndex);" id="country" name ="country"></select>					
                    </td>

                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Select_state'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <select name ="state" id ="state">
                            <option value="Badakhshan">Badakhshan</option>
                            <option value="Badghis">Badghis</option>
                            <option value="Baghlan">Baghlan</option>
                            <option value="Balkh">Balkh</option>
                            <option value="Bamian">Bamian</option>
                            <option value="Farah">Farah</option>
                            <option value="Faryab">Faryab</option>
                            <option value="Ghazni">Ghazni</option>
                            <option value="Ghowr">Ghowr</option>
                            <option value="Helmand">Helmand</option>
                            <option value="Herat">Herat</option>
                            <option value="Jowzjan">Jowzjan</option>
                            <option value="Kabol">Kabol</option>
                            <option value="Kandahar">Kandahar</option>	
                            <option value="Kapisa">Kapisa</option>
                            <option value="Konar">Konar</option>
                            <option value="Kondoz">Kondoz</option>
                            <option value="Laghman">Laghman</option>
                            <option value="Lowgar">Lowgar</option>
                            <option value="Nangarhar">Nangarhar</option>
                            <option value="Nimruz">Nimruz</option>
                            <option value="Oruzgan">Oruzgan</option>
                            <option value="Paktia">Paktia</option>
                            <option value="Paktika">Paktika</option>
                            <option value="Parvan">Parvan</option>
                            <option value="Samangan">Samangan</option>
                            <option value="Sar-e Pol">Sar-e Pol</option>
                            <option value="Takhar">Takhar</option>
                            <option value="Vardak">Vardak</option>
                            <option value="Zabol">Zabol</option>

                        </select>
                        <script language="javascript">print_country("country");</script>


                    </td>
                </tr>

                <tr>
                    <td><label><?php echo translate_admin('City'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="70" type="text" name="city" id="city" value=""/>
                        <?php echo form_error('city'); ?>
                    </td>
                </tr>

                <tr>
                    <td><label><?php echo translate_admin('areass'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="70" type="text" name="area" id="area" value=""/>
                        <?php echo form_error('area'); ?>
                    </td>

                </tr>

                <tr>
                    <td></td>
                    <td>
                        <input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
                    </td>
                </tr>

            </table>
        </form>	
    </div>






