<?php
    $logo = $this->Common_model->getTableData('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
    $query = $this->Common_model->getTableData('settings', array('code' => 'FRONTEND_LANGUAGE'));
    $trans_lang = $query->row()->int_value;
    $default_lang = $query->row()->string_value;
    $user_lang = $this->session->userdata('locale');

    if ($user_lang == '')
    {
        $locale = $default_lang;
    }
    else
    {
        $locale = $user_lang;
    }

    $currency_code = $this->session->userdata('locale_currency');
    $new_currency = $this->Common_model->getTableData('currency', array('default' => 1))->row();
    if ($currency_code == '')
    {
        $currency_code = $new_currency->currency_code;
        $currency_symbol = $new_currency->currency_symbol;
    }
    else
    {
        $currency_code = $this->session->userdata('locale_currency');
        $currency_symbol = $this->Common_model->getTableData('currency', array('currency_code' => $currency_code))->row()->currency_symbol;
    }


    if ($this->dx_auth->is_logged_in())
    {
        if ($this->dx_auth->get_username() == "")
        {
            $query = $this->Common_model->getTableData('profiles', array('id' => $this->dx_auth->get_user_id()))->row();
            $name = $query->Fname . ' ' . $query->Lname;
        }
        else
        {
            $name = $this->dx_auth->get_username();
        }
    }
    else
    {
        $name = '';
    }
?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-3 clearfix">
                    <h5><?php echo translate("Language Settings"); ?> </h5>
                    <div class="clsFloatLeft">
                        <div id="language">
                            <div id="language_display" class="rounded_top">
                                <div class="football_img"> <img class="img_lang" src="<?php echo css_url(); ?>/images/football.png" /> </div>
                                <?php
                                    $default_language = $this->db->where('code', 'FRONTEND_LANGUAGE')->get('settings')->row()->string_value;
                                    $default_lang_name = $this->db->where('code', $default_language)->get('language')->row()->name;
                                ?>
                                <div id="language_display_currency" class="language_set"> &nbsp; <?php
                                        if ($this->session->userdata('language') == "")
                                            echo $default_lang_name;
                                        else
                                            echo $this->session->userdata('language');
                                    ?></div></div>
                            <div class="arrow_sym">  </div>
                            <div id="language_selector_container" class="single_Lang" style="display:none;">
                                <div id="language_selector">
                                    <ul id="locale2">
                                        <?php
                                            $languages_core = $this->Common_model->getTableData('language')->result();
                                            foreach ($languages_core as $language)
                                            {
                                                ?>
                                                <li class="language option" id="language_selector_<?php echo $language->code; ?>" name="<?php echo $language->code; ?>"><?php echo $language->name; ?></li> <?php } ?>						
                                    </ul>
                                </div>
                            </div>																								
                        </div></div>

                    <div class="clsFloatLeft">
                        <div id="currency">
                            <div id="currency_display" class="rounded_top_new">
                                <div id="currency_display_currency" class="language_set1"> <?php echo $currency_symbol . ' ' . $currency_code; ?></div></div>
                            <div class="arrow_sym"> </div>
                            <div id="currency_selector_container" class="single_Lang">
                                <div id="currency_selector">
                                    <ul id="locale2">
                                        <?php
                                            $currencies = $this->Common_model->getTableData('currency', array('currency_code !=' => $currency_code))->result();
                                            foreach ($currencies as $currency)
                                            {
                                                ?>		
                                                <li name="<?php echo $currency->currency_code; ?>" id="currency_selector_<?php echo $currency->currency_code; ?>" class="currency option"> <?php echo $currency->currency_symbol . ' ' . $currency->currency_code; ?> </li>
                                            <?php } ?>					
                                    </ul>
                                </div>
                            </div>																								
                        </div>		</div>

                </div>
                <div class="col-md-3">
                    <h5><?php echo translate("Discover"); ?> </h5>
                    <ul class="unstyled js-footer-links">

                        <li>
                            <a href="<?php echo site_url('info/how_it_works'); ?>"><?php echo translate("How it works"); ?></a>
                        </li>
                        <!--<li>
                        <a href="<?php echo site_url('pages/view/why_host'); ?>"><?php echo translate("Why Host"); ?></a>
                        </li>
                        <li>
                        <a href="<?php echo site_url('pages/view/social'); ?>"><?php echo translate("Social Connections"); ?></a>
                        </li>-->
                        <?php
                            $result = $this->db->where('is_footer', 1)->where('is_under', 'discover')->from('page')->get();
                            if ($result->num_rows() != 0)
                            {
                                foreach ($result->result() as $row)
                                {
                                    echo '<li>
<a href="' . site_url("pages/view/" . $row->page_url) . '">' . $row->page_name . '</a>
</li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5><?php echo translate("Company"); ?></h5>
                    <ul class="unstyled js-footer-links">
                        <li>
                            <a href="<?php echo site_url('pages/contact'); ?>"><?php echo translate("Contact us"); ?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('pages/faq'); ?>"><?php echo translate("FAQ"); ?></a>
                        </li>
                        <!--<li>
                        <a href="<?php echo site_url('pages/view/about'); ?>"><?php echo translate("About"); ?></a>
                        <li>
                        <a href="<?php echo site_url('pages/view/press'); ?>"><?php echo translate("Press"); ?></a>
                        </li>
                        <li>
                        <a href="<?php echo site_url('pages/view/policies'); ?>"><?php echo translate("Policies"); ?></a>
                        </li>
                        <li>
                        <a href="<?php echo site_url('pages/view/responsible_hosting'); ?>"><?php echo translate("Responsible Hosting"); ?></a>
                        </li>
                        <li>
                        <a href="<?php echo site_url('pages/view/terms'); ?>"><?php echo translate("Terms & Privacy"); ?></a>
                        </li>-->
                        <?php
                            $result = $this->db->where('is_footer', 1)->where('is_under', 'company')->from('page')->get();
                            if ($result->num_rows() != 0)
                            {
                                foreach ($result->result() as $row)
                                {
                                    echo '<li><a href="' . site_url("pages/view/" . $row->page_url) . '">' . $row->page_name . '</a></li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
                <?php
                    $sql = "select url from joinus";
                    $query = $this->db->query($sql);
                    $result = $query->result_array();
                    $site = array();
                    $i = 1;
                    foreach ($result as $res)
                    {
                        $site[$i] = $res['url'];
                        $i = $i + 1;
                    }
                ?>
                <div class="col-md-3">
                    <h5><?php echo translate("Join us on"); ?></h5>
                    <ul class="unstyled js-external-links">
                        <li>
                            <a target="_blank" href="<?php echo $site[1]; ?>"><?php echo translate("Twitter"); ?></a>
                        </li>
                        <li>
                            <a target="_blank" href="<?php echo $site[2]; ?>"><?php echo translate("Facebook"); ?></a>
                        </li>
                        <li>
                            <a target="_blank" href="<?php echo $site[3]; ?>"><?php echo translate("Google"); ?></a>
                        </li>
                        <li>
                            <a target="_blank" href="<?php echo $site[4]; ?>"><?php echo translate("YouTube"); ?></a>
                        </li>
                    </ul>
                    <div id="copyright">Powered by <a href="http://radsoft.co.in" target="_blank">Radsoft Solutions</a></div>
                </div>
            </div>
        </div>
    </div>
</footer>

<?php $this->load->view(THEME_FOLDER . '/includes/map'); ?>

<script type="text/javascript" src="<?php echo js_url() . '/jquery.validate.js'; ?>"></script>
<script src="<?php echo js_url(); ?>/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>

<?php
    if ($this->uri->segment(2) == 'searchbydate' || $this->uri->segment(1) == 'search')
    {
        ?>
        <script src="<?php echo js_url(); ?>/page2.js" type="text/javascript"></script>
        <?php
    }
    else if ($this->uri->segment(2) == 'editConfirm' || $this->uri->segment(1) == 'rooms')
    {
        ?>
        <script src="<?php echo js_url(); ?>/page3.js" type="text/javascript"></script>
        <?php
    }
    else
    {
        ?>
        <script src="<?php echo js_url(); ?>/home.js" type="text/javascript"></script>
        <script src="<?php echo js_url(); ?>/home_new.js" type="text/javascript"></script>
        <script src="<?php echo js_url(); ?>/page1.js" type="text/javascript"></script>
        <script src="<?php echo js_url(); ?>/page1_new.js" type="text/javascript"></script>
        <?php
        if ($this->uri->segment(1) != 'rooms' && $this->uri->segment(1) != 'calendar')
        {
            ?>
            <!-- Slider Kit scripts -->
            <script type="text/javascript" src="<?php echo js_url(); ?>/jquery.easing.1.3.min.js"></script>
            <script type="text/javascript" src="<?php echo js_url(); ?>/jquery.sliderkit.1.8.min.js"></script>
            <script type="text/javascript" src="<?php echo js_url(); ?>/sliderkit.delaycaptions.min.js"></script>
            <script type="text/javascript" src="<?php echo js_url(); ?>/jquery.leanModal.min.js"></script>
            <script type="text/javascript" src="<?php echo js_url(); ?>/responsiveslides.min.js"></script>
            <?php
        }
    }
?>

<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script type="text/javascript">
    $(document).ready(function () {
        var input = document.getElementById('searchTextField');
        autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addListener(autocomplete, 'place_changed', function () {
<?php
    if ($this->uri->segment(1) == '0')
    {
        ?>
                    $('.advanced_search').show();
        <?php
    }
    else
    {
        ?>
                    jQuery('.advanced_search_rooms').show();
    <?php } ?>
            // now call it automaitically on page load
            jQuery('#checkindate2').trigger('click');
        });
    });
</script>
</body>
</html>