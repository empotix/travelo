<script type="text/javascript">

//SuckerTree Vertical Menu 1.1 (Nov 8th, 06)
//By Dynamic Drive: http://www.dynamicdrive.com/style/

    var menuids = ["suckertree1"] //Enter id(s) of SuckerTree UL menus, separated by commas

    function buildsubmenus() {
        for (var i = 0; i < menuids.length; i++) {
            var ultags = document.getElementById(menuids[i]).getElementsByTagName("ul")
            for (var t = 0; t < ultags.length; t++) {
                ultags[t].parentNode.getElementsByTagName("a")[0].className = "subfolderstyle"
                if (ultags[t].parentNode.parentNode.id == menuids[i]) //if this is a first level submenu
                    ultags[t].style.left = ultags[t].parentNode.offsetWidth + "px" //dynamically position first level submenus to be width of main menu item
                else //else if this is a sub level submenu (ul)
                    ultags[t].style.left = ultags[t - 1].getElementsByTagName("a")[0].offsetWidth + "px" //position menu to the right of menu item that activated it
                ultags[t].parentNode.onmouseover = function () {
                    this.getElementsByTagName("ul")[0].style.display = "block"
                }
                ultags[t].parentNode.onmouseout = function () {
                    this.getElementsByTagName("ul")[0].style.display = "none"
                }
            }
            for (var t = ultags.length - 1; t > -1; t--) { //loop through all sub menus again, and use "display:none" to hide menus (to prevent possible page scrollbars
                ultags[t].style.visibility = "visible"
                ultags[t].style.display = "none"
            }
        }
    }

    if (window.addEventListener)
        window.addEventListener("load", buildsubmenus, false)
    else if (window.attachEvent)
        window.attachEvent("onload", buildsubmenus)

</script>
<div id="sideBar" class="clsFloatLeft">
    <div class="suckerdiv">
        <ul id="suckertree1">
            <li><a href="<?php echo admin_url('backend'); ?>"><?php echo translate_admin('Dashboard'); ?></a></li>
            <li><a href="javascript:void(0);"><?php echo translate_admin('Site Settings'); ?></a>
                <ul style="z-index: 999;">
                    <li><a href="<?php echo admin_url('settings'); ?>"><?php echo translate_admin('Global Settings'); ?></a></li>
                    <li><a href="javascript:void(0);"><?php echo translate_admin('Language Settings'); ?></a>
                        <ul style="z-index: 999;">
                            <li><a href="<?php echo admin_url('settings/lang_front'); ?>"><?php echo translate_admin('Front-end Settings'); ?></a></li>
                            <li><a href="<?php echo admin_url('settings/lang_back'); ?>"><?php echo translate_admin('Back-end Settings'); ?></a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo admin_url('settings/manage_meta'); ?>"><?php echo translate_admin('Manage Meta'); ?></a></li>
                    <li><a href="<?php echo admin_url('settings/change_password'); ?>"><?php echo translate_admin('Change Password'); ?></a></li>
                    <li><a href="<?php echo admin_url('settings/how_it_works'); ?>"><?php echo translate_admin('How It Works'); ?></a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0);"><?php echo translate_admin('E-Mail Settings'); ?></a>
                <ul style="z-index: 999;">
                    <li><a href="<?php echo admin_url('email'); ?>"><?php echo translate_admin('E-Mail Template'); ?></a></li>
                    <li><a href="<?php echo admin_url('email/settings'); ?>"><?php echo translate_admin('E-Mail Settings'); ?></a></li>
                    <li><a href="<?php echo admin_url('email/mass_email'); ?>"><?php echo translate_admin('Mass E-Mail Campaigns'); ?></a></li>
                </ul>
            </li>
            <li><a href="<?php echo admin_url('members'); ?>"><?php echo translate_admin('Member Management'); ?></a></li>
            <li><a href="<?php echo admin_url('lists'); ?>"><?php echo translate_admin('User Listing Management'); ?></a></li>

            <li><a href="javascript:void(0);"><?php echo translate_admin('Manage Amenities'); ?></a>
                <ul style="z-index: 999;">
                    <li><a href="<?php echo admin_url('lists/view_aminity'); ?>"><?php echo translate_admin('Add Amenity'); ?></a></li>
                    <li><a href="<?php echo admin_url('lists/view_all'); ?>"><?php echo translate_admin('View Amenities'); ?></a></li>
                </ul>
            </li><?php ?>


            <li class="<?php if ($this->uri->segment(2) == 'property_type') echo "selected"; ?>"><a href="javascript:void(0);"><?php echo translate_admin('Manage Property types'); ?></a>
                <ul style="z-index: 999;">
                    <li><a href="<?php echo admin_url('property_type/view_property'); ?>"><?php echo translate_admin('Add Property type'); ?></a></li>
                    <li><a href="<?php echo admin_url('property_type/view_all_property'); ?>"><?php echo translate_admin('View Property types'); ?></a></li>
                </ul>
            </li>  
            <li><a href="javascript:void(0);"><?php echo translate_admin('Neighbourhoods'); ?></a>
                <ul style="z-index: 999;">
                    <li><a href="<?php echo admin_url('neighbourhoods/addcity'); ?>"><?php echo translate_admin('Add Cities'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/viewcity'); ?>"><?php echo translate_admin('View Cities'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/addcity_place'); ?>"><?php echo translate_admin('Add Places'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/viewcity_place'); ?>"><?php echo translate_admin('View Places'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/addcategory'); ?>"><?php echo translate_admin('Add Categories'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/viewcategory'); ?>"><?php echo translate_admin('View Categories'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/addpost'); ?>"><?php echo translate_admin('Add Posts'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/viewpost'); ?>"><?php echo translate_admin('View Posts'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/addphotographer'); ?>"><?php echo translate_admin('Add Photographers'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/viewphotographer'); ?>"><?php echo translate_admin('View Photographers'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/addtag'); ?>"><?php echo translate_admin('Add Tags'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/viewtag'); ?>"><?php echo translate_admin('View Tags'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/addknowledge'); ?>"><?php echo translate_admin('Add Local Knowledges'); ?></a></li>
                    <li><a href="<?php echo admin_url('neighbourhoods/viewknowledge'); ?>"><?php echo translate_admin('View Local Knowledges'); ?></a></li>
                </ul>
            </li>  
            <li><a href="<?php echo admin_url('payment/finance'); ?>"><?php echo translate_admin('Finance'); ?></a></li>
            <li><a href="javascript:void(0);"><?php echo translate_admin('Payment Settings'); ?></a>
                <ul style="z-index: 999;">

                    <li><a href="javascript:void(0);"><?php echo translate_admin('Payment Gateway'); ?></a>
                        <ul>
                             <!--<li><a href="<?php echo admin_url('payment'); ?>"><?php echo translate_admin('Add Pay Gateway'); ?></a></li>-->
                            <li><a href="<?php echo admin_url('payment/manage_gateway'); ?>"><?php echo translate_admin('Manage Pay Gateway'); ?></a></li>
                        </ul>
                    </li>
                    <li><a href="<?php echo admin_url('payment/paymode'); ?>"><?php echo translate_admin('Commission Setup'); ?></a></li>
                </ul>
            </li>
            <li><a href="<?php echo admin_url('social/fb_settings'); ?>"><?php echo translate_admin('Facebook Connect'); ?></a></li>
            <li><a href="<?php echo admin_url('social/twitter_settings'); ?>"><?php echo translate_admin('Twitter Connect'); ?></a></li> 
            <li><a href="<?php echo admin_url('social/google_settings'); ?>"><?php echo translate_admin('Google Maps'); ?></a></li>
            <!-- <li><a href="<?php echo admin_url('toplocation'); ?>"><?php echo translate_admin('Top Location'); ?></a></li> -->
            <li><a href="<?php echo admin_url('managemetas'); ?>"><?php echo translate_admin('Manage Meta'); ?></a></li>

            <li><a href="<?php echo admin_url('page/viewPages'); ?>"><?php echo translate_admin('Manage Static Pages'); ?></a></li>
            <li class="<?php if ($this->uri->segment(2) == 'coupon' && $this->uri->segment(3) != 'plans') echo "selected"; ?>"><a href="javascript:void(0);"><?php echo translate_admin('Coupon Control System'); ?></a> 
                <ul style="z-index: 999;">
                    <li><a href="<?php echo admin_url('coupon/view_coupon'); ?>"><?php echo translate_admin('Add Coupon Codes'); ?></a></li>
                    <li><a href="<?php echo admin_url('coupon/view_all_coupon'); ?>"><?php echo translate_admin('View Coupon Codes'); ?></a></li>
                </ul>
            </li>

        <!--<li><a href="javascript:void(0);"><?php echo translate_admin('Manage_Neighborhoods'); ?></a>
                <ul style="z-index: 999;">
                                         <li><a href="<?php echo admin_url('email/addplace'); ?>"><?php echo translate_admin('Add_Places'); ?></a></li>
                 <li><a href="<?php echo admin_url('email/viewplace'); ?>"><?php echo translate_admin('View_places'); ?></a></li>
                 
                </ul>
</li>-->
        </li><li><a href="<?php echo admin_url('help/viewhelp'); ?>"><?php echo translate_admin('Help'); ?></a></li>

    <?php /* ?><li><a href="<?php echo admin_url('social/news_letter'); ?>"><?php echo translate_admin('News Letter'); ?></a></li> <?php */ ?>
    <li><a href="<?php echo admin_url('admin_key/viewAdmin_key'); ?>"><?php echo translate_admin('Admin Key'); ?></a></li>
    <li><a href="<?php echo admin_url('faq/viewFaqs'); ?>"><?php echo translate_admin('FAQ System'); ?></a></li>  
    <li><a href="<?php echo admin_url('contact'); ?>"><?php echo translate_admin('Manage Contact'); ?></a></li>
    <li><a href="<?php echo admin_url('joinus/viewJoinus'); ?>"><?php echo translate_admin('Join us on'); ?></a></li>
    <li><a href="<?php echo admin_url('cancellation/viewcancellation'); ?>"><?php echo translate_admin('Manage Cancellation Policy'); ?></a></li>

</ul>
</div> 
</div>
