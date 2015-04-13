<!-- Required css stylesheets -->
<link href="<?php echo css_url() . '/dashboard.css'; ?>" media="screen" rel="stylesheet" type="text/css" />

<!-- End of stylesheet inclusion -->
<?php
    $this->load->view(THEME_FOLDER . '/includes/dash_header');
    $this->load->view(THEME_FOLDER . '/includes/hosting_header');
?>

<div id="dashboard_container">
    <div class="Box" id="Mange_Lisiting">
        <div class="Box_Content clearfix">
            <!-- sort-header dropdown-->
            <div class="sort-header clsFloatLeft sort_width">
                <span class="action_button " id="listings-filter">
                    <div class="display-filter">
                        <span class="icon none always"> <?php echo translate("Show :"); ?></span> 
                        <span class="icon none"><?php echo translate("all listings"); ?><span class="expand"></span></span>
                        <span class="icon active"><?php echo translate("active listings"); ?><span class="expand"></span></span>
                        <span class="icon inactive"><?php echo translate("hidden listings"); ?><span class="expand"></span></span>
                    </div>
                    <div class="toggle-filter">
                        <div><a href="<?php echo base_url(); ?>hosting/sort_by_status?f=all" class="icon none"><?php echo translate("Show all listings"); ?></a></div>
                        <div><a href="<?php echo base_url(); ?>hosting/sort_by_status?f=active" class="icon active"><?php echo translate("Show active"); ?></a></div>
                        <div><a href="<?php echo base_url(); ?>hosting/sort_by_status?f=hide" class="icon inactive"><?php echo translate("Show hidden"); ?></a></div>  
                    </div>
                </span>
            </div>  

            <div class="clsFloatLeft">    
            </div>	
            <br/>   <br/>  
            <!-- sort-header dropdown-->
            <div id="listings-container">
                <ul class="listings">
                    <?php if ($this->dx_auth->is_logged_in()): ?>
                            <?php
                            $id = $this->dx_auth->get_user_id();
                            $query = '';
                            if (!empty($sort))
                            {
                                if ($sort == "active")
                                {
                                    echo 'Result >> Active Listings';
                                    $this->db->where('is_enable', 1);
                                }

                                if ($sort == "hide")
                                {
                                    echo 'Result >> Hidden Listings';
                                    $this->db->where('is_enable', 0);
                                }
                            }

                            $query = $this->db->select('list.*,lys_status.calendar as calendar_status,lys_status.overview as overview_status,lys_status.price as price_status,lys_status.photo as photo_status,lys_status.address as address_status,lys_status.listing as listing_status')->join('lys_status', "lys_status.id=list.id")->where("list.user_id", $id)->get('list');

                            if ($query->num_rows > 0)
                            {
                                ?>
                                <?php
                                foreach ($query->result() as $row)
                                {
                                    $url = getListImage($row->id);

                                    echo '<li class="listing"><div class="thumbnail">';
                                    echo '<a class="image_link" href="' . base_url() . 'rooms/' . $row->id . '" linkindex="98"><img title="' . $row->title . '" src="' . $url . '" class="search_thumbnail"></a> </div>';
                                    echo '<div class="listing-info"><h3>';
                                    echo anchor('rooms/' . $row->id, $row->title);
                                    echo '</h3>';
                                    echo '<span class="actions"><span class="action_button">';
                                    echo anchor('rooms/lys_next/edit/' . $row->id, translate("Edit Listing"), array('class' => 'icon edit'));
                                    echo '</span><span class="action_button">';
                                    echo anchor('rooms/' . $row->id, translate("View Listing"), array('class' => 'icon view'));
                                    echo '</span><span class="action_button">';
                                    echo anchor('calendar/single/' . $row->id, translate("View Calendar"), array('class' => 'icon calendar'));
                                    echo '</span><span class="action_button" onclick="return delete_list();">';
                                    echo anchor('rooms/deletelisting/' . $row->id, translate("Delete Listing"), array('class' => 'icon delete'));

                                    echo '</span><span class="actions"><span class="action_button">';
                                    echo anchor('statistics/view_statistics_graph/' . $row->id, translate("View statistics"), array('class' => 'icon edit'));

                                    echo '</span><span style="clear:both"></span></div>';

                                    $total_status = $row->address_status + $row->overview_status + $row->price_status + $row->photo_status + $row->calendar_status + $row->listing_status;
                                    ?>
                                    <?php
                                    //echo $total_status;exit;
                                    if ($row->is_enable == 0)
                                    {
                                        if ($total_status != 6 || $total_status == 6)
                                        {
                                            
                                        }
                                        else
                                        {
                                            ?>
                                            <div class="Chang_To"> <?php echo translate("Change To"); ?> : 
                                                <?php
                                            }
                                        }
                                        else
                                        {
                                            ?>
                                            <div class="Chang_To"> <?php echo translate("Change To"); ?> : 
                                            <?php }
                                            ?>
                                            <?php
                                            if ($row->is_enable == 0)
                                            {
                                                if ($total_status != 6)
                                                {
                                                    $final_status = 6 - $total_status;
                                                    echo '<a class="btn blue" style="padding:3px;float: right;Text-decoration: none;" href="' . base_url() . 'rooms/lys_next/edit/' . $row->id . '">' . $final_status . ' steps to list >></a>';
                                                }
                                                elseif ($total_status == 6)
                                                {
                                                    echo '<a class="btn yellow" style="padding:3px;float: right;Text-decoration: none;" href="' . base_url() . 'rooms/lys_next/edit/' . $row->id . '">List now</a>';
                                                }
                                                else
                                                {
                                                    ?>
                                                    <a href="<?php echo base_url() . 'hosting/change_status?stat=1&rid=' . $row->id ?>"><?php echo translate("Active"); ?></a>
                                                <?php
                                                }
                                            }
                                            else
                                            {
                                                ?> 
                                                <a href="<?php echo base_url() . 'hosting/change_status?stat=0&rid=' . $row->id ?>"><?php echo translate("Hide"); ?></a>
                                            <?php } ?><?php
                                            if ($row->is_enable == 0)
                                            {
                                                if ($total_status != 6 || $total_status == 6)
                                                {
                                                    
                                                }
                                                else
                                                {
                                                    echo '</div>';
                                                }
                                            }
                                            else
                                            {
                                                echo '</div>';
                                            }
                                            ?>

                                            <?php
                                            echo '<div class="clear"></div></li>';
                                        }
                                    }
                                    else
                                    {
                                        echo "<p style='font-size:18px'>" . translate("You don't have any listings!") . "</p>
                            <br/> " . translate("Listing your space on") . " " . $this->dx_auth->get_site_title() . " " . translate("is an easy way to  monetize any extra space you have.") . "
                            <br/>" . translate("You'll also get to meet interesting travelers from around the world!") . "
                            <br/>
                            <br>";
                                        echo anchor('rooms/new', translate("Post a new listing"), array('class' => 'clsLink2_Bg'));
                                    }
                                    ?>
    <?php endif; ?>
                            </ul>
                            <div style="clear:both"></div>
                        </div>
                    </div>
            </div>
        </div>
        <!-- Footer Scripts -->
        <script>
            $(".active_list").click(function (event) {
                event.preventDefault();
                $(this).hide();
                $(".hide_list").show();

            });
            $(".hide_list").click(function (event) {
                event.preventDefault();
                $(this).hide();
                $(".active_list").show();
            });
        </script>


        <script type="text/x-jqote-template" id="availability_button_template">
            <![CDATA[
            <span class="clearfix current-availability icon <*= this.status *>">
            <span class="label"><*= this.label *></span>
            <span class="expand"></span>
            </span>
            <div class="toggle-info" style="display: none;">
            <div class="instructions"><*= this.instructions *></div>
            <div class="toggle-action-container">
            <a href="<*= this.url *>" class="toggle-action icon <*= this.next_status *>"><*= this.toggle_label *></a>
            </div>
            </div>
            ]]>
        </script>


        <script type="text/javascript">
            //
            // We can probably toss all of this code into a plugin at some point
            //

            var spinnerImage = new Image();
            spinnerImage.src = "/images/spinner.gif";

            VisibilityFilter = function (el, options) {
                if (el)
                    this.init(el, options);
            }

            jQuery.extend(VisibilityFilter.prototype, {
                name: 'visibilityFilter',
                init: function (el, options) {
                    this.element = $(el);
                    $.data(el, this.name, this);

                    var $this = this.element;
                    var _ref = this;

                    jQuery('#listings-filter .display-filter').click(function () {
                        _ref.togglePanel();
                    });

                    jQuery('#listings-filter .toggle-filter a').click(function () {
                        var $link = jQuery(this);

                        if ($link.hasClass('active'))
                            _ref.setPanelState('active');
                        else if ($link.hasClass('inactive'))
                            _ref.setPanelState('inactive');
                        else
                            _ref.setPanelState();

                        _ref.showSpinner();
                        _ref.hidePanel();
                    });

                    var outsideClickHandler = function (eventObject) {
                        eventObject.data.hidePanel();
                    };

                    // attach and detach handlers to make it possible to close the widget by clicking
                    // outside of the element
                    this.element.hover(
                            function () {
                                jQuery(document).unbind('click', outsideClickHandler);
                            },
                            function () {
                                jQuery(document).bind('click', _ref, outsideClickHandler);
                            }
                    );
                },
                hidePanel: function () {
                    this.element.removeClass('expand');
                },
                togglePanel: function () {
                    this.element.toggleClass('expand');
                },
                showPanel: function () {
                    this.element.addClass('expand');
                },
                setPanelState: function (state, showSpinner) {
                    if (!!showSpinner)
                        this.showSpinner();

                    this.element.removeClass('none inactive active');
                    this.element.addClass(state);
                },
                showSpinner: function () {
                    this.element.find('.display-filter span.icon:visible').not('.always').addClass('widget-spinner');
                },
                hideSpinner: function () {
                    this.element.find('.display-filter span.widget-spinner').not('.always').removeClass('widget-spinner');
                }


            });

            jQuery.fn.visibilityFilter = function (options) {
                // get the arguments 
                var args = $.makeArray(arguments),
                        after = args.slice(1);

                return this.each(function () {
                    // see if we have an instance
                    var instance = $.data(this, 'visibilityFilter');

                    if (instance) {
                        // call a method on the instance
                        if (typeof options === "string") {
                            instance[options].apply(instance, after);
                        }
                        else if (instance.update) {
                            // call update on the instance
                            instance.update.apply(instance, args);
                        }
                    }
                    else {
                        // create the plugin
                        new VisibilityFilter(this, options);
                    }
                });
            }

            jQuery(document).ready(function () {
                jQuery('#post-listing-new').click(function () {
                    document.location = "http://www.cogzidel.com/rooms/new";
                });

                jQuery('#listings-filter').visibilityFilter();
            });
            var buttonContent = {
                active: {
                    label: "Active",
                    instructions: "Hide your listing to remove it from search results:",
                    toggle_label: "Hide"
                },
                inactive: {
                    label: "Hidden",
                    instructions: "Activate your listing to have it show up in search results:",
                    toggle_label: "Activate"
                }
            };

            jQuery(document).ready(function () {
                jQuery('div.set-availability').availabilityWidget(buttonContent);
            });

            function delete_list()
            {
                var answer = confirm("Are you sure to delete?")
                if (answer) {
                    document.messages.submit();
                }
                return false;
            }

        </script>
