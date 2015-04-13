jQuery(function() {
    Referrals.init();
});

var Referrals = {

    // Which FB widget to use (stream|request)
    facebook_widget: 'stream',
    fb_stream_post_data: {},
    $el: {},

    init: function () {
        // Email Invite Inputs
        Cogzidel.Utils.setInnerText();
        jQuery('.gray_text').removeClass('active');
        
        Referrals.$el = {
          fb_button            : jQuery('#fb-send-dialog-button'),
					overlay              : jQuery('#overlay'),
          fb_skip_login        : jQuery('#fb-skip-login'),
          action_bar_container : jQuery('#action_bar_container'),
          notice               : jQuery('#notice'),
          close                : jQuery('span.close')
        };

        // Update "TRAVEL CREDIT" text width for centering on the ticket/coupon
        jQuery('#travel_credit_tag').css('margin-left', function() {
            return -(jQuery('#travel_credit_tag').width() / 2);
        });

        // Resize coupon amount font size
        var coupon_width = 293,
						coupon_amount_width = jQuery('#coupon_amount').width();
						
        if (coupon_amount_width > coupon_width) {
						var new_font_size = (110 * (coupon_width / coupon_amount_width) + 'px');
            jQuery('#coupon_amount').css('font-size', new_font_size);
        }
        
        jQuery('#coupon_middle').css('width',coupon_width);


        // Click Handlers
        jQuery('#add').click(function() {
            Referrals.add_email();
        });
        
        Referrals.$el.close.click(function() {
          Referrals.$el.notice.fadeOut();
        });
        
        jQuery('#fb-connect-button').click(function(event) {
            Referrals.check_invite_limit(Referrals.facebook_login);

            _gaq.push(['_trackEvent', 'referrals', 'show', 'fb_request']);
            event.preventDefault();
        });
				
				jQuery('#fb-send-dialog-button').click(function(event){
                    if (Referrals.check_invite_limit() == false) {
                        return;
                    }
				  // TODO: This is hacky and gross. Needs to be redone. Apologies to whomever has
				  // to maintain this.
					event.preventDefault();
					
					Referrals.$el.fb_button.attr('disabled','disabled');
					
					if (!Cogzidel.Utils.isUserLoggedIn) {
                        var $form = jQuery('#fb-sd-form');
                        // Not logged into Cogzidel
                        if (Cogzidel.Utils.fb_status() != 'connected') {
                            // Not logged in to FB => show login page
                            var login = $form.attr('action').replace('/authenticate', '/login');
                            $form.attr('action', login);
                        }
                        $form.submit();
					} else {
  					Referrals.$el.overlay.fadeIn('slow');
					  // logged in to Cogzidel
					  if (Cogzidel.Utils.fb_status() === 'connected') {
					    // logged into FB
				      Referrals.show_send_dialog();
					  } else {
					    // Not logged into FB
					    Referrals.facebook_sd_login();
					  }
					}
				});
				
        Referrals.$el.fb_skip_login.click(function(event) {
            if (Referrals.check_invite_limit() == false) {
                return;
            }

            if (Cogzidel.Utils.isUserLoggedIn) {
                Referrals.$el.action_bar_container.hide();
                jQuery('#arena').show();
                Referrals.show_email_friend_selector();
            } else {
                jQuery('#email-login-form').submit();
            }

            _gaq.push(['_trackEvent', 'referrals', 'show', 'email']);
            event.preventDefault();
        });
        jQuery('#skip_email').click(function(event) {
            jQuery('#arena').hide();
            jQuery('#email_wrapper').hide();
            jQuery('#fb_invite_wrapper').hide();
            Referrals.$el.action_bar_container.show();
            jQuery('#extras').show();
            event.preventDefault();
        });
        jQuery('#skip_fb').click(function(event) {
            jQuery('#arena').hide();
            jQuery('#email_wrapper').hide();
            jQuery('#fb_invite_wrapper').hide();
            Referrals.$el.action_bar_container.show();
            jQuery('#extras').show();
            event.preventDefault();
        });

        // Wait until FB is loaded, otherwise this will break
        var origFbAsyncInit = window.fbAsyncInit;
        window.fbAsyncInit = function() {
            origFbAsyncInit();

            // invite_via is set when they come from /login
            var match = document.location.search.match(/invite_via=(\w+)/);
            var tab = match && match[1];
            if (tab == 'email') {
                Referrals.show_email_friend_selector();
            } else if (tab == 'facebook') {
                Referrals.show_facebook_friend_selector();
            } else if (tab == 'sd') {
                // Check for login status to catch the case where user logs in via email, not FB
                FB.getLoginStatus(function(response) {
                    if (response.authResponse && response.perms) {
                        Referrals.show_send_dialog();
                    }
                });
            }
        };
    },
    
    facebook_sd_login : function() {
        FB.getLoginStatus(function(response) {
            Referrals.fb_sd_callback(response);
        });
    },
    
    fb_sd_callback: function(response) {
      
      FB.login(function(response) {
        if (response.authResponse) {
          if (response.perms) {
            // user is logged in and granted some permissions.
            // perms is a comma separated list of granted permissions
  	        Referrals.show_send_dialog();
          } else {
            // user is logged in, but did not grant any permissions
            Referrals.$el.overlay.fadeOut();
            Referrals.$el.fb_button.removeAttr('disabled').css('cursor','pointer');
          }
        } else {
          Referrals.$el.overlay.fadeOut();
          Referrals.$el.fb_button.removeAttr('disabled').css('cursor','pointer');
        }
      }, {scope: Cogzidel.FACEBOOK_PERMS});
    },
    
    show_send_dialog: function() {
      Referrals.$el.fb_button.attr('disabled','disabled');
      Referrals.$el.overlay.fadeIn('slow');
      
      FB.api('/me/friends?fields=' + "id,name,last_name", function(response) {
                          if (response.data.length === 0) {
                            // no facebook friends
                             // Params for the FB Send Dialog widget
                              var publish = {
                                method: 'send',
                                to: '',
                                link: Referrals.fb_send_dialog_data.link,
                                title: Referrals.fb_send_dialog_data.title,
                                subject: Referrals.fb_send_dialog_data.subject,
                                picture: Referrals.fb_send_dialog_data.picture,
                                name: Referrals.fb_send_dialog_data.name,
                                caption: Referrals.fb_send_dialog_data.caption,
                                description: Referrals.fb_send_dialog_data.description
                              };
                              
                              // Handle the response from the widget
                              FB.ui(publish, function(response) {
                                  if (response && response.post_id) {
                                    // console.log('Post was published.');
                                    Referrals.$el.notice.slideDown(500, function() {
                                      Referrals.$el.notice.children('.label').pulsate(2, 700);
                                    });
                                  } else {
                                    // console.log('Post wasn't published.');
                                  }
                                  
                                  // remove overlay and re-enable the fb invite button
                                  Referrals.$el.overlay.fadeOut();
                                  Referrals.$el.fb_button.removeAttr('disabled').css('cursor','pointer'); 
                              });
                          } else {
		                       var friends = response.data, // cache an object of all the friends
		                           ten_friend_ids = [], // need for later
		                           friend_ids = jQuery.map(friends, function(friend) { // extract the friend IDs into an array, we only need IDs
		                              return friend.id;
		                            }).join(',');
		                       
		                       // ajax request that returns an ordered list of the friends based on rank
		                       jQuery.ajax({
		                            url: '/users/rank_facebook_friends',
		                            type: 'POST',
		                            async: false, // Load synchronously to have friends sorted before widget renders
		                            data: {facebook_ids: friend_ids},
		                            success: function(resp) {
		                              var ten = resp.slice(0,10); // FB Send Dialog only lets us prepopulate with 10 FB user IDs,
		                                                          // so grab the top 10 ranked friends.
		                              
		                              ten_friend_ids = jQuery.map(ten, function(friend) { // get a comma seperated string of IDs of the top 10
		                                return friend.id;
		                              }).join(',');
		                              
		                              var publish = {
		                                method: 'send',
		                                to: ten_friend_ids,
		                                link: Referrals.fb_send_dialog_data.link,
		                                title: Referrals.fb_send_dialog_data.title,
		                                subject: Referrals.fb_send_dialog_data.subject,
		                                picture: Referrals.fb_send_dialog_data.picture,
		                                name: Referrals.fb_send_dialog_data.name,
		                                caption: Referrals.fb_send_dialog_data.caption,
		                                description: Referrals.fb_send_dialog_data.description
		                              };
		                              
		                              // Handle the response from the widget
		                              FB.ui(publish, function(response) {
		                                  if (response) {
		                                    Referrals.$el.notice.slideDown(500, function() {
		                                      Referrals.$el.notice.children('.label').pulsate(2, 700);
		                                    });
		                                  } else {
		                                    // console.log('Post wasnt published.');
		                                  }
		                                  
		                                  // remove overlay and re-enable the fb invite button
		                                  Referrals.$el.overlay.fadeOut();
		                                  Referrals.$el.fb_button.removeAttr('disabled').css('cursor','pointer'); 
		                              });
		                            }
		                        });
	                        }
		                    });
    },

    add_email : function() {
        var html = jQuery("<label class='force_hide inner_text' for='friends[]'></label><input type='text' id='frid' name='friends[]' class='gray_text ref_email' />");
        jQuery('#add').before(html);
        Cogzidel.Utils.setInnerText();
        return false;
    },

    show_facebook_friend_selector: function() {
        if (Referrals.facebook_widget == 'stream') {
            Referrals.show_facebook_stream_widget();
        } else {
            Referrals.show_facebook_friend_request_selector();
        }
    },

    show_facebook_stream_widget: function() {
        jQuery("#jfmfs-container").jfmfs({
            max_selected: 20,
            max_selected_message: "{0} of {1} selected",
            friend_fields: "id,name,last_name",
            pre_selected_friends: [],
            exclude_friends: [],
            after_friends_loaded: function(friends) {
                var friend_ids = jQuery.map(friends, function(friend) {
                    return friend.id;
                }).join(',');
																jQuery('#bottom-bar').show();
                jQuery.ajax({
                    url: '/users/rank_facebook_friends',
                    type: 'POST',
                    async: false, // Load synchronously to have friends sorted before widget renders
                    data: {facebook_ids: friend_ids},
                    success: function(response) {
                        var ranks = {};
                        jQuery.each(response, function() {
                            ranks[this.id] = this.rank;
                        });
                        jQuery.each(friends, function() {
                            this.rank = ranks[this.id];
                        });
                    }
                });
            },
            sorter: function(a, b) {
                // Sort in descending order (important friends first) by negating the rank
                var x = (typeof(a.rank) != 'undefined') ? -a.rank : 0;
                var y = (typeof(b.rank) != 'undefined') ? -b.rank : 0;

                // Sort by last_name if rank is the same
                if (x == y) {
                    x = a.last_name.toLowerCase();
                    y = b.last_name.toLowerCase();
                }
                return ((x < y) ? -1 : ((x > y) ? 1 : 0));
            }
        });
        
        var placeholder = jQuery('#fb_message').val();

        jQuery('#fb-send-button').click(function(e) {
            jQuery('#confirm_box_wrapper').show();
            var msg = jQuery("#fb_message").val();
            if (msg != placeholder) {
                jQuery('#fb_confirm_message').text(msg);
                fb_stream_post_data.message = msg;
            }
            return false;
        });

        jQuery('#cancel').click(function(e) {
            jQuery('#confirm_box_wrapper').hide();
            return false;
        });

        jQuery('#confirm').click(function(e) {
            // Post to Facebook
            var callback_url = '/referrals/fb_invite?channel=6';
            var selected_friends = jQuery("#jfmfs-container").data('jfmfs').getSelectedIds();
            var invited_friends = [];
            var failed_friends = [];

            var fb_wall_post = function() {
                var friend_id = selected_friends[0];
                var url = '/' + friend_id + '/feed';

                FB.api(url, 'post', fb_stream_post_data, function(response) {
                    if (!response || response.error) {
                        failed_friends.push(friend_id);
                        callback_url += '&failed_ids[]=' + friend_id;
                    } else {
                        invited_friends.push(friend_id);
                        callback_url += '&ids[]=' + friend_id;
                    }
                    // Remove from the queue
                    selected_friends.shift();
                    // Run again if there are elements left, otherwise ping the backend
                    if (selected_friends.length > 0) {
                        fb_wall_post();
                    } else {
                        jQuery('#confirm_box_wrapper').hide();
                        self.location.href = callback_url;
                    }
                });
            };

            if (selected_friends.length > 0) {
                fb_wall_post();
            } else {
                alert("Please select some friends first."); // TODO nicer alert
            }

            return false;
        });

        jQuery('#extras').hide();
        Referrals.$el.action_bar_container.hide();
        jQuery('#arena').show();
        jQuery('#fb_invite_wrapper').show();
    },

    show_facebook_friend_request_selector : function() {
        var fbml = '<fb:serverFbml width="760"><s' + 'cript type="text/fbml"><fb:fbml><fb:request-form action="' +
            fb_friend_selector.callback_url + '" target="_top" method="POST" invite="true" type="Cogzidel" content="' +
            fb_friend_selector.content + '"><fb:multi-friend-selector email_invite="false" showborder="false" actiontext="' +
            fb_friend_selector.actiontext + '" exclude_ids="' + fb_friend_selector.exclude_ids +
            '" /></fb:request-form></fb:fbml></script></fb:serverFbml>';
        var selector_container = document.getElementById('facebook-friend-selector');
        selector_container.innerHTML = fbml;    // jQuery breaks FBML
        FB.XFBML.parse(selector_container);

        jQuery('#extras').hide();
        Referrals.$el.action_bar_container.hide();
        jQuery('#arena').show();
        jQuery('#fb_invite_wrapper').show();
    },

    show_email_friend_selector : function() {
        jQuery('#extras').hide();
        Referrals.$el.action_bar_container.hide();
        jQuery('#fb_invite_wrapper').hide();
        jQuery('#arena').show();
        jQuery('#email_wrapper').show();
    },

    show_facebook_login : function() {
        Referrals.$el.action_bar_container.show();
    },

    facebook_login : function() {
        FB.getLoginStatus(function(response) {
            var options = {};
            // Make sure we have the permissions
            if (response.authResponse && (Referrals.facebook_widget != 'stream' || (response.perms && response.perms.match(/publish_stream/)))) {
                Referrals.fb_login_callback(response);
            } else {
                if (Referrals.facebook_widget === 'stream') {
                    options.scope = 'publish_stream';
                }
                FB.login(Referrals.fb_login_callback, options);
            }
        });
    },

    fb_login_callback : function(response) {
        // we have a valid FB session
        if (response.authResponse) {
            // User logged in to Cogzidel => show friend selector and hide login
            if (Cogzidel.Utils.isUserLoggedIn) {
                Referrals.show_facebook_friend_selector();
                // User not logged in to Cogzidel => send to authenticate action
            } else {
                jQuery('#fb-login-form').submit();
            }
        } else {
            Referrals.show_facebook_login();
        }
    },

    check_invite_limit : function(callback) {
        if (Referrals.invite_limit_reached === true) {
            alert("You can't invite more friends at this point. Please try again later.");
            return false;
        } else {
            if (callback != undefined) {
                callback();
            }
            return true;
        }
    }
};