(function (j, o, r) {
    var q = "hashchange",
        l = document,
        n, m = j.event.special,
        k = l.documentMode,
        p = "on" + q in o && (k === r || k > 7);

    function s(a) {
        a = a || location.href;
        return "#" + a.replace(/^[^#]*#?(.*)$/, "$1")
    }
    j.fn[q] = function (a) {
        return a ? this.bind(q, a) : this.trigger(q)
    };
    j.fn[q].delay = 50;
    m[q] = j.extend(m[q], {
        setup: function () {
            if (p) {
                return false
            }
            j(n.start)
        },
        teardown: function () {
            if (p) {
                return false
            }
            j(n.stop)
        }
    });
    n = (function () {
        var d = {},
            e, a = s(),
            c = function (h) {
                return h
            },
            b = c,
            f = c;
        d.start = function () {
            e || g()
        };
        d.stop = function () {
            e && clearTimeout(e);
            e = r
        };

        function g() {
            var h = s(),
                i = f(a);
            if (h !== a) {
                b(a = h, i);
                j(o).trigger(q)
            } else {
                if (i !== a) {
                    location.href = location.href.replace(/#.*/, "") + i
                }
            }
            e = setTimeout(g, j.fn[q].delay)
        }
        j.browser.msie && !p && (function () {
            var i, h;
            d.start = function () {
                if (!i) {
                    h = j.fn[q].src;
                    h = h && h + s();
                    i = j('<iframe tabindex="-1" title="empty"/>').hide().one("load", function () {
                        h || b(s());
                        g()
                    }).attr("src", h || "javascript:0").insertAfter("body")[0].contentWindow;
                    l.onpropertychange = function () {
                        try {
                            if (event.propertyName === "title") {
                                i.document.title = l.title
                            }
                        } catch (t) {}
                    }
                }
            };
            d.stop = c;
            f = function () {
                return s(i.location.href)
            };
            b = function (w, z) {
                var x = i.document,
                    y = j.fn[q].domain;
                if (w !== z) {
                    x.title = l.title;
                    x.open();
                    y && x.write('<script>document.domain="' + y + '"<\/script>');
                    x.close();
                    i.location.hash = w
                }
            }
        })();
        return d
    })()
})(jQuery, this);

var Translations = {
    clear_dates: "Clear Dates",
    entire_place: "Entire Place",
    friend: "friend",
    friends: "friends",
    loading: "Loading",
    neighborhoods: "Neighborhoods",
    private_room: "Private Room",
    review: "review",
    reviews: "reviews",
    superhost: "superhost",
    shared_room: "Shared Room",
    today: "Today",
    you_are_here: "You are Here",
    a_friend: "a friend",
    distance_away: "away",
    instant_book: "Instant Book",
    social_connections: "Social Connections",
    show_more: "Show More...",
    learn_more: "Learn More",
    amenities: "Amenities",
    room_type: "Room Type",
    price: "Price",
    keywords: "Keywords",
    property_type: "Property Type",
    bedrooms: "Bedrooms",
    bathrooms: "Bathrooms",
    beds: "Beds",
    languages: "Languages",
    collection: "Collection",
    host: "Host",
    group: "Group",
    connections: "Connections",
    night_writer: "NightWriter",
    redo_search_in_map_tip: '"Redo search in map" must be checked to see new results as you move the map',
    zoom_in_to_see_more_properties: "Zoom in to see more properties",
    your_search_was_too_specific: "Your search was a little too specific.",
    we_suggest_unchecking_a_couple_filters: "We suggest unchecking a couple filters, zooming out, or searching for a different city."
};

function clean_up_and_submit_search_request() {
	$("#search_type_list").trigger("click");
    CogzidelSearch.loadNewResults();
    return false
}
function display_search_type(b, e) {
    var f, d;
    $("#map_message").hide();
    e = (typeof (e) === "undefined") ? true : e;
    var g = CogzidelSearch.currentViewType;
    if (g === b.replace("search_type_", "")) {
        return false
    }
    $(".search_type_option").removeClass("search_type_option_active");
    $("#" + b).addClass("search_type_option_active");
    CogzidelSearch.changing_display_type = true;
    if (b === "search_type_photo") {
        SS.initOnce();
        CogzidelSearch.currentViewType = "photo";
        $("#Search_Main").removeClass("list_view map_view");
        $('#list_view_loading').show();
        $('#small_map_loading').show();
        setTimeout(function()
        {
        $('#list_view_loading').hide();
        $('#small_map_loading').hide();
        	$("#Search_Main").addClass("photo_view");
        },100 );
        d = "small"
                    $("#search_type_photo").trigger("click");

        CogzidelSearch.loadNewResults();
        $("#search_filters_wrapper").appendTo("#Search_Main");
        $("#map_wrapper").prependTo("#search_filters");
        $("#map_options").prependTo("#search_filters")
    	return false
    } else {
        if (b === "search_type_list") {
            CogzidelSearch.currentViewType = "list";
            $("#Search_Main").removeClass("map_view photo_view").addClass("list_view");
            d = "x_small"
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults();
            $("#search_filters_wrapper").appendTo("#Search_Main");
        	$("#map_wrapper").prependTo("#search_filters");
        	$("#map_options").prependTo("#search_filters");
        	AMM.zoom_control();
    		return false
        } else {
            if (b === "search_type_map") {
                SS.initOnce();
                CogzidelSearch.currentViewType = "map";
                CogzidelSearch.hideBannerForRemainderOfSession = true;
                var c = AMM.map.getCenter();
                var h = AMM.map.getZoom();
                if (h < 13) {
                    h = h + 2
                }
                $("#search_type_map").trigger("click");
                CogzidelSearch.loadNewResults();
                $("#cc_attribution_link").addClass("force_hide");
                $("#Search_Main").removeClass("list_view photo_view").addClass("map_view condensed_header_view");
                google.maps.event.addListenerOnce(AMM.map, "resize", function () {
                    AMM.map.setCenter(c);
                    AMM.map.setZoom(h)
                });
                google.maps.event.trigger(AMM.map, "resize");
                jQuery("#results_filters").insertAfter("#standby_action_area");
                jQuery("#results_save").insertAfter("#applied_filters");
                jQuery("#map_wrapper").appendTo("#Search_Main");
                jQuery("#map_options").prependTo("#search_filters");
                jQuery("#map_wrapper").append(jQuery("#search_filters_wrapper")).append(jQuery("#search_filters_toggle"));
                AMM.clearOverlays();
                jQuery.each(CogzidelSearch.resultsJson.properties, function (k, j) {
                    AMM.queue.push(j.id)
                });
                AMM.showOverlays()
                AMM.initMapOnce("search_map");
            }
        }
    }
    if (b === "search_type_list" || b === "search_type_wishlist") {
				
        if (e) {
										
            AMM.closeInfoWindow();
            f = CogzidelSearch.thumbnailStyles[d];
            $(".search_thumbnail").each(function (k, j) {											//ramesh here																																		
                /*j.src = j.src.replace(CogzidelSearch.thumbnailRegex, "/" + d + ".jpg");*/
				j.src = j.src;
                j.height = f[0];
                j.width = f[1]
            })
        }
        var i = AMM.map.getCenter();
        var a = AMM.map.getZoom();
        if (a > 10) {
            a = a - 2
        }
        google.maps.event.addListenerOnce(AMM.map, "resize", function () {
            AMM.map.setCenter(i);
            AMM.map.setZoom(a)
        });
        if (g === "map") {
            google.maps.event.trigger(AMM.map, "resize");
            $("#results_filters").insertAfter("#results_header");
            $("#results_save").appendTo("#results_header");
            if (e && !redoSearchInMapIsChecked()) {
            	$("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            }
        }
        $("#search_filters_wrapper").appendTo("#Search_Main");
        $("#map_wrapper").prependTo("#search_filters");
        $("#map_options").prependTo("#search_filters")
    }
    if (g === "map" && (b === "list" || b === "photo") || (g !== "map" && b !== "map")) {
        CogzidelSearch.loadNewResultsWithNoResponse()
    }
    if (g === "map") {
        $("#map_message").width(507);
        $("#search_filters_toggle").addClass("search_filters_toggle_off").removeClass("search_filters_toggle_on");
        $("#search_filters").show()
    } else {
        if ($("#search_filters").is(":visible")) {
            $("#search_filters_toggle").addClass("search_filters_toggle_on").removeClass("search_filters_toggle_off")
        } else {
            $("#search_filters_toggle").addClass("search_filters_toggle_off").removeClass("search_filters_toggle_on")
        }
    }
    CogzidelSearch.$.trigger("finishedrendering");
    CogzidelSearch.changing_display_type = false;
    return false
}
function reset_params_to_defaults() {
    CogzidelSearch.newSearch = true;
    CogzidelSearch.locationHasChanged = false;
    CogzidelSearch.results_changed_by_map_action = false;
    $("#page").val("1")
}
function redoSearchInMapIsChecked() {
    return $("#redo_search_in_map").is(":checked")
}
function showLoadingOverlays() {
    clearTimeout(CogzidelSearch.loadingMessageTimeout);
    CogzidelSearch.loadingMessageTimeout = setTimeout(function () {
        if (window.google && window.google.maps) {
            $("#small_map_loading").show()
        }
        $("#list_view_loading, #map_view_loading").show()
    }, 250)
}
function hideLoadingOverlays() {
    clearTimeout(CogzidelSearch.loadingMessageTimeout);
    $("#results_header, #results_filters, #results, #results_footer").removeClass("search_grayed");
    $("#small_map_loading, #list_view_loading, #map_view_loading").hide()
}
function clearResultsList() {
    $("#results").empty()
}
function setBannerImage(b) {
    if (b.url === undefined) {
        $("#Search_Main").addClass("condensed_header_view")
    } else {
        var a = new Image();
        a.src = b.url;
        if (a.complete) {
            bannerImageLoadComplete(b);
            a.onload = function () {}
        } else {
            a.onload = function () {
                bannerImageLoadComplete(b);
                a.onload = function () {}
            }
        }
    }
    setAirtvVideo(b)
}
function setAirtvVideo(b) {
    $("#airtv_promo").remove();
    if (b.airtv_url !== undefined && CogzidelSearch.resultsJson.show_airtv_in_search_results && CogzidelSearch.resultsJson.show_airtv_in_search_results === "true") {
        var a = {};
        a.airtv_url = b.airtv_url;
        a.airtv_headline = b.airtv_headline || "Check Out AirTV!";
        a.airtv_description = b.airtv_description || "A video from nearby!";
        $("#results").before($("#list_view_airtv_template").jqote(a, "*"));
        initAirtvSearchVideoLightBox("#airtv_promo", b.airtv_url, b.airtv_headline)
    }
}
function initAirtvSearchVideoLightBox(b, c, a) {
    if (jQuery("#video_lightbox_content").length === 0) {
        jQuery("body").append('<div id="video_lightbox_content"></div>')
    }
    jQuery(b).colorbox({
        inline: true,
        href: "#video_lightbox_content",
        onLoad: function () {
            var d = ['<object id="video" width="764" height="458"><param name="movie" value="', c, '"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="', c, '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="764" height="458"></embed></object>'].join("");
            jQuery("#video_lightbox_content").html(d)
        },
        onComplete: function () {
            jQuery("#cboxTitle").html(a)
        },
        onCleanup: function () {
            jQuery("#video_lightbox_content").html("");
            jQuery("#cboxTitle").html("")
        }
    })
}
function bannerImageLoadComplete(b) {
    jQuery("#search_header").css("background-image", ["url(", b.url, ")"].join(""));
    if (b.height) {
        jQuery("#search_header").css("height", b.height)
    } else {
        jQuery("#search_header").css("height", 150)
    }
    jQuery("#Search_Main").removeClass("condensed_header_view");
    var a = b.attribution_text || "CC licensed photo from Flickr";
    var c = b.attribution_url || "http://www.flickr.com";
    jQuery("#cc_attribution_link").html(a).attr("href", c).show();
    CogzidelSearch.$.trigger("finishedrendering")
}
function render_results_oncomplete(a) {
    var b;
    if (a.banner_info && CogzidelSearch.hideBannerForRemainderOfSession === false) {
        setBannerImage(a.banner_info)
    } else {
        jQuery("#cc_attribution_link").hide()
    }
    if (!(a.banner_info && a.banner_info.airtv_url)) {
        Connections.init()
    }
    AMM.centerLat = false;
    AMM.centerLng = false;
    AMM.geocodePrecision = false;
    if (a.center_lat && a.center_lng) {
        AMM.centerLat = a.center_lat;
        AMM.centerLng = a.center_lng;
        if (a.geocode_precision) {
            AMM.geocodePrecision = a.geocode_precision
        }
    }
    AMM.drawCenterMarker();
    reset_params_to_defaults();
    setTimeout(function () {
        AMM.turnMapListenersOn()
    }, 1000);
    CogzidelSearch.markViewedPageLinks();
    CogzidelSearch.trackSearch();
    CogzidelSearch.activeAjaxRequest = null;
    CogzidelSearch.initialLoadComplete = true;
    CogzidelSearch.$.trigger("finishedrendering")
}
function render_results(k, e) {
    var b, h, l, g, d;
    var i, f, c;
    var a = false;

    function j(o, n, m) {
        return f.jqote({
            badge_type: o,
            badge_text: n,
            badge_name: m
        }, "*")
    }
    AMM.turnMapListenersOff();
    clearResultsList();
    if (window.google && window.google.maps && (arguments.length == 1 || e.ea === undefined)) {
        b = new google.maps.LatLngBounds()
    } else {
        b = e
    }
    $(".results_count").html(k.results_count_html);
    $("#results_count_top").html(k.results_count_top_html);
    $("#results_pagination").html(k.results_pagination_html);
    $("#sort").val(k.sort);
    AMM.clearQueue();
    l = false;
    if (CogzidelSearch.forcedViewType !== false && (CogzidelSearch.initialLoadComplete === false || CogzidelSearch.searchHasBeenModified() === false)) {
        l = CogzidelSearch.forcedViewType
    } else {
        if (k.view_type) {
            l = k.view_type
        }
    }
    if (l !== false) {
        display_search_type("search_type_" + CogzidelSearch.viewTypes[l], false);
        CogzidelSearch.currentViewType = CogzidelSearch.viewTypes[l];
        CogzidelSearch.params.search_view = l
    }
    if (k.present_standby_option && k.present_standby_option === true && k.standby_url) {
        $("#standby_link").attr("href", k.standby_url);
        CogzidelSearch.presentStandbyOption()
    } else {
        $("#standby_link").attr("href", base_url+"messaging/standby");
        CogzidelSearch.hideStandbyOption()
    }
    h = document.createDocumentFragment();
    f = $("#badge_template");
    c = $("#list_view_item_template");
    if (CogzidelSearch.currentViewType === "list") {
        d = "x_small"
    } else {
        d = "small"
    }
    g = CogzidelSearch.thumbnailStyles[d];
    $.each(k.properties, function (p, o) {
        var n, m, q;
        var r;
								var ramesh_url = o.hosting_thumbnail_url;
        if (window.google && window.google.maps) {
            q = new google.maps.LatLng(o.lat, o.lng);
            AMM.add(q, o);
            if (b !== e) {
                b.extend(q)
            }
        }
        if (SS) {
            if (o.picture_ids) {
                SS.addHostingAndIds(o.id, o.picture_ids);
                if (SS.pictureArrays && SS.pictureArrays[o.id][0] !== undefined) {
                    o.smallThumbnail = SS.fullImageUrl(SS.pictureArrays[o.id][0])
                } else {
                    SS.pictureArrays[o.id] = []
                }
            }
        }
        if (CogzidelSearch.currentViewType === "list" || CogzidelSearch.currentViewType === "photo") {
            m = {
                hosting_name: o.name,
                user_name: o.user_name,
                user_id: o.user_id,
                hosting_id: o.id,
                result_number: (p + 1),
                address: o.address,
                price: o.price,
                symbol: o.symbol,
                views: o.page_viewed,
                staggered: o.staggered,
                short_listed: o.short_listed,
                /*hosting_thumbnail_url: o.hosting_thumbnail_url.replace(CogzidelSearch.thumbnailRegex, "/" + d + ".jpg"),*/
																hosting_thumbnail_url:ramesh_url,
                hosting_thumbnail_width: g[1],
                hosting_thumbnail_height: g[0],
                connections: o.relationships || [],
                hasVideo: o.has_video,
                isNewHosting: (o.is_new_hosting && o.is_new_hosting === true),
                hasInstantBook: (o.instant_book && o.instant_book === true),
                distance: o.distance
            };
            
            if (o.user_thumbnail_url && o.user_thumbnail_url.charAt(0) !== "/") {
                m.user_thumbnail_url = o.user_thumbnail_url
            }
            if (((o.price > 999) && CogzidelSearch.currencySymbolRight !== "") || o.staggered) {
                a = true
            }
            r = $(c.jqote(m, "*"));
            n = "";
            if (o.review_count > 0) {
                n += j("reviews", o.review_count, (o.review_count == 1 ? Translations.review : Translations.reviews))
            }
            if (o.recommendation_count > 0) {
                n += j("friends", o.recommendation_count, (o.recommendation_count == 1 ? Translations.friend : Translations.friends))
            }
            if (o.user_is_superhost > 0) {
                n += j("superhost", "", Translations.superhost)
            }
            if (n) {
                r.find("ul.reputation").append(n)
            }
            h.appendChild(r[0])
        }
    });
    $("#results").toggleClass("mini_prices", a).append(h.cloneNode(true));
    i = $("#map_message");
    if (CogzidelSearch.currentViewType === "map") {
        if ((k.properties && k.properties.length == CogzidelSearch.params.per_page) || !redoSearchInMapIsChecked()) {
            if (redoSearchInMapIsChecked()) {
                i.html(['<span class="zoom_in_to_see_more_properties">', Translations.zoom_in_to_see_more_properties, "</span>"].join(""))
            } else {
                i.html(["<h3>", Translations.zoom_in_to_see_more_properties, "</h3>", '<span id="redo_search_in_map_tip">', Translations.redo_search_in_map_tip, "</span>"].join(""))
            }
            i.removeClass("tall_message").addClass("short_message").show()
        } else {
            if (!(k.properties) || k.properties.length === 0) {
                i.html(["<h3>", Translations.your_search_was_too_specific, "</h3>", "<p>", Translations.we_suggest_unchecking_a_couple_filters, "</p>"].join(""));
                i.removeClass("short_message").addClass("tall_message").show()
            } else {
                i.hide()
            }
        }
    } else {
        i.hide()
    }
    AMM.currentBounds = b;
    AMM.clearOverlays(true);
    AMM.showOverlays();
    if ((k.properties && k.properties.length > 0) && (CogzidelSearch.results_changed_by_map_action === false || CogzidelSearch.changing_display_type === true) && (!redoSearchInMapIsChecked() || CogzidelSearch.locationHasChanged)) {
        AMM.fitBounds(b)
    }
    if (k.properties && k.properties.length > 0) {
        $("#results_footer").show()
    } else {
        $("#results_footer").hide();
        CogzidelSearch.showBlankState()
    }
    hideLoadingOverlays();
    return true
}

function killActiveAjaxRequest() {
    if (CogzidelSearch.activeAjaxRequest) {
        CogzidelSearch.activeAjaxRequest.abort();
        CogzidelSearch.activeAjaxRequest = null;
        hideLoadingOverlays()
    }
}

var MapIcons = {
    centerPoint: false,
    numbered: [],
    numberedHover: [],
    numberedStarred: [],
    numberedStarredHover: [],
    numberedVisited: [],
    numberedVisitedHover: [],
    numberedVisitedStarred: [],
    numberedVisitedStarredHover: [],
    small: false,
    smallHover: false,
    smallStarred: false,
    smallStarredHover: false,
    smallVisited: false,
    smallVisitedHover: false,
    smallVisitedStarred: false,
    smallVisitedStarredHover: false,
    shadowStandard: false,
    shadowSmall: false,
    shadowCenterPoint: false,
    init: function () {
        MapIcons.centerPoint = new google.maps.MarkerImage(base_url+"images/map_icons/icon_center_point.png", new google.maps.Size(15, 36), new google.maps.Point(0, 0));
        MapIcons.small = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(0, 0));
        MapIcons.smallHover = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(9, 0));
        MapIcons.smallStarred = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(0, 9));
        MapIcons.smallStarredHover = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(9, 9));
        MapIcons.smallVisited = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(18, 0));
        MapIcons.smallVisitedHover = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(27, 0));
        MapIcons.smallVisitedStarred = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(18, 9));
        MapIcons.smallVisitedStarredHover = new google.maps.MarkerImage(base_url+"images/map_icons/small_pins.png", new google.maps.Size(9, 9), new google.maps.Point(27, 9));
        for (var a = 0; a < 20; a++) {
            MapIcons.numbered[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(0, (a * 34)));
            MapIcons.numberedHover[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(44, (a * 34)));
            MapIcons.numberedStarred[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(22, (a * 34)));
            MapIcons.numberedStarredHover[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(66, (a * 34)));
            MapIcons.numberedVisited[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(88, (a * 34)));
            MapIcons.numberedVisitedHover[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(132, (a * 34)));
            MapIcons.numberedVisitedStarred[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(110, (a * 34)));
            MapIcons.numberedVisitedStarredHover[a + 1] = new google.maps.MarkerImage(base_url+"images/map_icons/map_pins_sprite_001.png", new google.maps.Size(22, 34), new google.maps.Point(154, (a * 34)))
        }
        MapIcons.shadowCenterPoint = new google.maps.MarkerImage(base_url+"images/map_icons/icon_center_point_shadow.png", new google.maps.Size(35, 27), new google.maps.Point(0, 0), new google.maps.Point(4, 27));
        MapIcons.shadowSmall = new google.maps.MarkerImage(base_url+"images/map_icons/icon_small_dot_shadow.png", new google.maps.Size(11, 11), new google.maps.Point(0, 0), new google.maps.Point(5, 9));
        MapIcons.shadowStandard = new google.maps.MarkerImage(base_url+"images/map_icons/default_shadow.png", new google.maps.Size(33, 26), new google.maps.Point(0, 0), new google.maps.Point(5, 23))
    }
};

var CogzidelSearch = {
    thumbnailRegex: /\/[^\/]*\.jpg$/,
    thumbnailStyles: {
        x_small: [74, 114],
        small: [144, 216]
    },
    hideBannerForRemainderOfSession: false,
    forcedViewType: false,
    code: false,
    eventId: false,
    hostId: false,
    hostName: "",
    forceHideHost: false,
    groupId: false,
    groupName: "",
    forceHideGroup: false,
    isViewingStarred: false,
    collectionId: false,
    collectionName: "",
    forceHideCollection: false,
    viewTypes: {
        "1": "list",
        "2": "photo",
        "3": "map"
    },
    activeAjaxRequest: null,
    loadingMessageTimeout: false,
    newSearch: false,
    currentViewType: "list",
    results_changed_by_map_action: false,
    changing_display_type: false,
    shareLightbox: false,
    params: {},
    //currencySymbolLeft: '',
    currencySymbolRight: "",
    initialLoadComplete: false,
    resultsJson: false,
    locationHasChanged: false,
    viewedIds: [],
    updateFacebookBannerText: function () {
        if (CogzidelSearch.params.location) {
            jQuery("#connect_banner .general").hide();
            jQuery("#connect_banner .specific").find("span.place").text(CogzidelSearch.params.location).end().show()
        }
    },
    initFacebookBannerTooltip: function () {
        var a = jQuery("#what_does_this_do_tooltip");
        if (!a.length) {
            return
        }
        var c = jQuery("#connect_banner a.what_does_this_do"),
            b = a.css("width").split("px")[0];
        a.appendTo("body");
        c.hover(function () {
            var d = c.width(),
                f = c.offset(),
                e = f.left + d / 2 - b / 2;
            a.css({
                left: e + "px",
                top: (f.top + 23) + "px"
            }).fadeIn("fast")
        }, function () {
            a.fadeOut("fast")
        })
    },
    init: function (a) {
        CogzidelSearch.viewedIds = CogzidelSearch.getViewedPage3Ids();
        a = a || {};
        if (a.min_bathrooms) {
            $("#min_bathrooms").val(a.min_bathrooms)
        }
        if (a.min_bedrooms) {
            $("#min_bedrooms").val(a.min_bedrooms)
        }
        if (a.min_beds) {
            $("#min_beds").val(a.min_beds)
        }
        if (a.min_bed_type) {
            $("#min_bed_type").val(a.min_bed_type)
        }
        if (a.page) {
            $("#page").val(a.page);
            CogzidelSearch.params.page = a.page
        }
        if (a.sort) {
            $("#sort").val(a.sort);
            CogzidelSearch.params.sort = a.sort
        }
         if (a.property_type) {
            $("#property_type").val(a.property_type);
            CogzidelSearch.params.property_type = a.property_type
        }
        if (a.neighborhoods) {
            CogzidelSearch.params.neighborhoods = a.neighborhoods
        }
        if (a.hosting_amenities) {
            CogzidelSearch.params.hosting_amenities = a.hosting_amenities
        }
        if (a.room_types) {
            CogzidelSearch.params.room_types = a.room_types
        }
        if (a.property_type_id) {
            CogzidelSearch.params.property_type_id = a.property_type_id
        }
        if (a.connected) {
            CogzidelSearch.params.connected = "true"
        }
        if (a.night_writer) {
            CogzidelSearch.params.night_writer = "true"
        }
        if (a.guests) {
            $("#number_of_guests").val(a.guests);
            CogzidelSearch.params.guests = a.guests
        }
        if (a.price_min) {
            CogzidelSearch.params.price_min = a.price_min
        }
        if (a.price_max) {
            CogzidelSearch.params.price_max = a.price_max
        }
        $("#search_type_toggle").each(function () {
            $(this).delegate(".search_type_option", "click", function () {
                display_search_type(this.id)
            });
            $(this).delegate(".search_type_option", "hover", function (r) {
                $(this).toggleClass("search_type_option_hover", (r.type === "mouseenter"))
            })
        });
        $("#reinstate_collections").live("click", function () {
            SearchFilters.reinstateCollections();
            $(this).remove()
        });
        $("#reinstate_user").live("click", function () {
            SearchFilters.reinstateHost();
            $(this).remove()
        });
        $("#reinstate_group").live("click", function () {
            SearchFilters.reinstateGroup();
            $(this).remove()
        });
        $("#share_results_link").colorbox({
            inline: true,
            width: 500,
            href: "#share_lightbox",
            onComplete: function () {
                var r = $("#share_url").val([Translations.loading, "..."].join(""));
                CogzidelSearch.setParamsFromDom();
                CogzidelSearch.activeAjaxRequest = $.getJSON(base_url+"search/create", CogzidelSearch.params, function (s) {
                    r.val([base_url+"search?code=", s.search.code].join("")).select()
                })
            }
        });
        $("#keywords").live("keyup", function (t) {
            var s = (t.keyCode ? t.keyCode : t.which);
            if (s == 13) {
                var r = jQuery("#keywords");
                if (r.attr("defaultValue") !== r.val()) {
                	$("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                }
            }
        });
        $("#redo_search_in_map_link_on").live("click", function (r) {
            jQuery("#redo_search_in_map").attr("checked", true);
            if (AMM.redoSearchPromptTimeout) {
                clearTimeout(AMM.redoSearchPromptTimeout);
                AMM.redoSearchPromptTimeout = false
            }
            jQuery("#first_time_map_question").fadeOut(500);
            AMM.closeInfoWindow();
            CogzidelSearch.results_changed_by_map_action = true;
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults();
            return false
        });
        jQuery("#redo_search_in_map_link_off").live("click", function (r) {
            if (AMM.redoSearchPromptTimeout) {
                clearTimeout(AMM.redoSearchPromptTimeout);
                AMM.redoSearchPromptTimeout = false
            }
            jQuery("#first_time_map_question").fadeOut(500);
            return false
        });
        jQuery("#share_url").live("focus", function () {
            jQuery(this).select()
        });
        jQuery(".pagination a").live("click", function () {
            var s = jQuery(this);
            var r = s.html();
            if (s.attr("rel") == "next") {
                r = parseInt(jQuery("div.pagination span.current").html(), 10) + 1
            } else {
                if (s.attr("rel") == "prev") {
                    r = parseInt(jQuery("div.pagination span.current").html(), 10) - 1
                } else {
                    r = parseInt(r, 10)
                }
            }
            if (isNaN(r) || r < 1) {
                r = 1
            }
            jQuery("#page").val(r);
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults();
            return false
        });
        var m, j;
        var l = jQuery("#search_filters");
        var c = jQuery("#search_body");
        var p = l.position().top;
        var k = l.height();

        function n() {
            var s = jQuery(window).scrollTop();
            var r = l.position().top;
            if ((s >= p) && (k < j)) {
                if (!l.hasClass("fixed")) {
                    l.addClass("fixed")
                }
                if (((k + r) >= m) && s >= r) {
                    l.css({
                        position: "absolute",
                        top: m - k + 1 + "px"
                    })
                } else {
                    if (l.css("position") === "absolute") {
                        l.css({
                            position: "",
                            top: "0"
                        })
                    }
                }
            } else {
                d()
            }
        }
        function d() {
            if (l.hasClass("fixed")) {
                l.removeClass("fixed")
            }
            if (l.css("position") === "absolute") {
                l.css({
                    position: "",
                    top: "0"
                })
            }
        }
        function f() {
            k = l.height();
            n();
            n()
        }
        CogzidelSearch.$.bind("finishedrendering", function () {
            p = c.position().top;
            k = l.height();
            j = c.height();
            m = p + j;
            if ((j > k) && CogzidelSearch.currentViewType !== "map") {
                jQuery(window).scroll(n).scroll();
                CogzidelSearch.$.bind("filtertoggle", f)
            } else {
                jQuery(window).unbind("scroll", n);
                CogzidelSearch.$.unbind("filtertoggle", f);
                d()
            }
        });
        jQuery("#search_filters_toggle").live("click", function () {
            var r = jQuery(this);
            if (r.hasClass("search_filters_toggle_off")) {
				jQuery("#Mab_Big_Main").width(699);
                jQuery("#map_message").width(507);
                jQuery("#search_map").width(679)
            } else {
				jQuery("#Mab_Big_Main").width(958);
                jQuery("#search_map").width(940);
                jQuery("#map_message").width(752)
            }
            r.toggleClass("search_filters_toggle_on search_filters_toggle_off");
            jQuery("#search_filters").toggle();
            google.maps.event.trigger(AMM.map, "resize")
        });
        $("#results_filters").delegate(".filter_x_container", "click", function () {
            SearchFilters.appliedFilterXCallback(this)
        });
        $.each($(".inner_text"), function (t, u) {
            var r = $(u).next("input");
            var s = r.val();
            r.attr("defaultValue", u.innerHTML);
            r.val(u.innerHTML);
            if (s.length > 0) {
                r.val(s);
                r.addClass("active")
            }
            r.bind("focus", function () {
                if ($(r).val() == r.attr("defaultValue")) {
                    $(r).val("");
                    $(r).addClass("active")
                }
                $(r).removeClass("error");
                return true
            });
            r.bind("blur", function () {
                if ($(r).val() === "") {
                    $(r).removeClass("active");
                    $(r).val(r.attr("defaultValue"))
                } else {
                    $(r).removeClass("error")
                }
            });
            $(u).remove()
        });
        $("#location_label").show();
        if (a.location) {
            $("#location").val(a.location).addClass("active")
        }
        if (CogzidelSearch.initialLoadComplete === false) {
            var b = $.datepicker._defaults.dateFormat;
            var i = {
                minDate: 0,
                maxDate: "+2Y",
                nextText: "",
                prevText: "",
                numberOfMonths: 1,
                closeText: Translations.clear_dates,
                currentText: Translations.today,
                showButtonPanel: true
            };
            var q = jQuery.extend(true, {}, i);
            var e = jQuery.extend(true, {}, i);
            if (typeof a.checkin !== "undefined" && typeof a.checkout !== "undefined" && a.checkin !== b && a.checkout !== b) {
                jQuery("#checkin").val(a.checkin);
                jQuery("#checkout").val(a.checkout);
                q = jQuery.extend(q, {
                    defaultDate: a.checkin
                });
                e = jQuery.extend(e, {
                    defaultdate: a.checkout
                })
            } else {
                jQuery("#checkin").val(b);
                jQuery("#checkout").val(b);
                jQuery("#search_inputs").css("background-color", "#ffe75f")
            }
            jQuery("#search_form").cogzidelInputDateSpan({
                defaultsCheckin: q,
                defaultsCheckout: e,
                onSuccess: function () {
                	$("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                }
            });
            jQuery("ul.collapsable_filters li input:checkbox, ul#lightbox_filters input:checkbox").live("click", function () {
                var t = false;
                var u = jQuery(this).attr("id");
                var r = jQuery(this).attr("name");
                var s = jQuery(this).attr("value");
                if (u.indexOf("lightbox") === -1) {
                    u = ["#lightbox_", u].join("");
                    t = true
                } else {
                    u = ["#", u.replace("lightbox_", "")].join("")
                }
                if (jQuery(u)) {
                    if (jQuery(this).is(":checked")) {
                        jQuery(['input:checkbox[name="', r, '"][value="', s, '"]'].join("")).attr("checked", true)
                    } else {
                        jQuery(['input:checkbox[name="', r, '"][value="', s, '"]'].join("")).attr("checked", false)
                    }
                }
                if (t === true) {
               	$("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                }
            });
            jQuery("a.show_more_link").live("click", function (r) {
            	$("#search_type_list").trigger("click");
                SearchFilters.openFiltersLightbox();
                var s = jQuery(this).closest(".search_filter").attr("id").replace("_container", "");
                SearchFilters.selectLightboxTab(s)
            });
            jQuery("#lightbox_search_button").live("click", function () {
            	$.colorbox.close()
            	$("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery(".filters_lightbox_nav_element").live("click", function () {
            	$("#search_type_list").trigger("click");
                var r = jQuery(this).attr("id").replace("lightbox_nav_", "");
                SearchFilters.selectLightboxTab(r)
            });
            jQuery("#sort").change(function () {
            	$("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
             jQuery("#property_type").change(function () {
             	$("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("#min_bedrooms").change(function () {
            	$("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("#number_of_guests").change(function () {
            	$("#search_type_list").trigger("click");
                CogzidelSearch.loadNewResults()
            });
            jQuery("a.filter_header, a.filter_toggle").live("click", function () {
                jQuery(this).closest(".search_filter").toggleClass("closed open");
                CogzidelSearch.$.trigger("filtertoggle")
            });
            jQuery(".search_result").live("mouseenter", function (r) {
                CogzidelSearch.hoverListResult((r.currentTarget.id).split("_")[1])
            });
            jQuery(".search_result").live("mouseleave", function (r) {
                CogzidelSearch.unHoverListResult((r.currentTarget.id).split("_")[1])
            });
            jQuery("#slider-range").slider({
                range: true,
                min: SearchFilters.minPrice,
                max: SearchFilters.maxPrice,
                step: 5,
                values: [(a.price_min ? parseInt(a.price_min, 10) : SearchFilters.minPrice), (a.price_max ? parseInt(a.price_max, 10) : SearchFilters.maxPrice)],
                slide: function (r, s) {
                    SearchFilters.applyPriceSliderChanges(s)
                },
                change: function (r) {
                    if (r && r.originalEvent && r.originalEvent.type === "mouseup") {
                    	$("#search_type_list").trigger("click");
                        CogzidelSearch.loadNewResults()
                    }
                }
            });
            SearchFilters.applyPriceSliderChanges();
            if (window.google && window.google.maps && a.search_by_map && a.ne_lng && a.ne_lat && a.sw_lng && a.sw_lat) {
                AMM.initMapOnce("search_map");
                var h = {
                    sw_lat: a.sw_lat,
                    sw_lng: a.sw_lng,
                    ne_lat: a.ne_lat,
                    ne_lng: a.ne_lng
                };
                CogzidelSearch.params.forceBounds = h;
                var o = new google.maps.LatLng(h.sw_lat, h.sw_lng);
                var g = new google.maps.LatLng(h.ne_lat, h.ne_lng);
                AMM.fitBounds(new google.maps.LatLngBounds(o, g));
                jQuery("#redo_search_in_map").attr("checked", true);
                CogzidelSearch.params = a
            }
            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults(true);
            CogzidelSearch.params = a;
            jQuery("#redo_search_in_map").bind("change", function () {
                if (AMM.redoSearchPromptTimeout) {
                    clearTimeout(AMM.redoSearchPromptTimeout);
                    AMM.redoSearchPromptTimeout = false;
                    jQuery("#first_time_map_question").fadeOut(250)
                }
                if (redoSearchInMapIsChecked()) {
                    AMM.closeInfoWindow();
                    CogzidelSearch.results_changed_by_map_action = true;
                    $("#search_type_list").trigger("click");
                    CogzidelSearch.loadNewResults()
                } else {
                    AMM.turnMapListenersOff()
                }
            })
        }
        CogzidelSearch.initFacebookBannerTooltip()
    },
    performNewSearch: function () {
        return (CogzidelSearch.newSearch || CogzidelSearch.initialLoadComplete)
    },
    searchHasBeenModified: function () {
        try {
            var c = window.location.hash;
            if (c) {
                var a = (((c).split("#")[1]).split("&")[0]).split("modified=")[1];
                if (a === "true") {
                    return true
                }
            }
        } catch (b) {}
        return false
    },
    setParamsFromDom: function () {
        var k;
        var e = CogzidelSearch.params;
        CogzidelSearch.params = {};
        if (CogzidelSearch.initialLoadComplete === false && CogzidelSearch.code && (CogzidelSearch.searchHasBeenModified() === false)) {
            CogzidelSearch.params.code = CogzidelSearch.code
        }
        if (CogzidelSearch.eventId && (CogzidelSearch.searchHasBeenModified() === false)) {
            CogzidelSearch.params.event_id = CogzidelSearch.eventId
        }
        if (CogzidelSearch.performNewSearch()) {
            CogzidelSearch.params.new_search = true
        }
        AMM.new_bounds = AMM.mapLoaded ? (AMM.map.getBounds() || false) : false;
        switch (CogzidelSearch.currentViewType) {
        case "list":
            CogzidelSearch.params.search_view = 1;
            break;
        case "photo":
            CogzidelSearch.params.search_view = 2;
            break;
        case "map":
            CogzidelSearch.params.search_view = 3;
            break;
        default:
            CogzidelSearch.params.search_view = 1
        }
        CogzidelSearch.params.min_bedrooms = $("#min_bedrooms").val() || "0";
        CogzidelSearch.params.min_bathrooms = $("#min_bathrooms").val() || "0";
        CogzidelSearch.params.min_beds = $("#min_beds").val() || "0";
        CogzidelSearch.params.min_bed_type = $("#min_bed_type").val() || "0";
        CogzidelSearch.params.page = $("#page").val() || "1";
        var b = jQuery("#location");
        var n = b.val();
        if (n !== b.attr("defaultValue")) {
            CogzidelSearch.params.location = n || ""
        }
        if (!e || !(e.location) || (e.location != CogzidelSearch.params.location)) {
            CogzidelSearch.locationHasChanged = true;
            CogzidelSearch.hideBannerForRemainderOfSession = false
        }
        if (CogzidelSearch.includeCollectionParam()) {
            CogzidelSearch.params.collection_id = CogzidelSearch.collectionId
        } else {
            SearchFilters.clearCollections(false)
        }
        if (CogzidelSearch.includeHostParam()) {
            CogzidelSearch.params.host_id = CogzidelSearch.hostId
        } else {
            SearchFilters.clearHost()
        }
        if (CogzidelSearch.includeGroupParam()) {
            CogzidelSearch.params.group_id = CogzidelSearch.groupId
        } else {
            SearchFilters.clearGroup()
        }
        var f = $("#checkin").val();
        var i = $("#checkout").val();
        if (f != "mm/dd/yyyy") {
            CogzidelSearch.params.checkin = jQuery("#checkin").val() || ""
        }
        if (i != "mm/dd/yyyy") {
            CogzidelSearch.params.checkout = jQuery("#checkout").val() || ""
        }
        CogzidelSearch.params.guests = jQuery("#number_of_guests").val() || "1";
        CogzidelSearch.params.sort = jQuery("#sort").val() || "1";
        CogzidelSearch.params.property_type = jQuery("#property_type").val() || "1";
        CogzidelSearch.params.room_types = [];
        jQuery("input[name='room_types']").each(function (o, p) {
            if (jQuery(p).is(":checked")) {
                CogzidelSearch.params.room_types.push(jQuery(p).val())
            }
        });
        CogzidelSearch.params.property_type_id = [];
        jQuery("input[name='property_type_id']").each(function (o, p) {
            if (jQuery(p).is(":checked")) {
                CogzidelSearch.params.property_type_id.push(jQuery(p).val())
            }
        });
        CogzidelSearch.params.hosting_amenities = [];
        jQuery("input[name='amenities']").each(function (o, p) {
            if (jQuery(p).is(":checked")) {
                CogzidelSearch.params.hosting_amenities.push(jQuery(p).val())
            }
        });
        if (CogzidelSearch.isViewingStarred) {
            CogzidelSearch.params.starred = true
        }
        if (jQuery("input[name='connected']").is(":checked")) {
            CogzidelSearch.params.connected = true
        }
        if (jQuery("input[name='night_writer']").is(":checked")) {
            CogzidelSearch.params.night_writer = true
        }
        CogzidelSearch.params.languages = [];
        jQuery("input[name='languages']").each(function (o, p) {
            if (jQuery(p).is(":checked")) {
                CogzidelSearch.params.languages.push(jQuery(p).val())
            }
        });
        CogzidelSearch.params.neighborhoods = [];
        if (CogzidelSearch.initialLoadComplete === false) {
            k = ["neighborhoods", "room_types", "min_bedrooms", "price_min", "price_max", "guests", "property_type_id", "connected"];
            jQuery(k).each(function (o, p) {
                if (e[p]) {
                    CogzidelSearch.params[p] = e[p]
                }
            })
        }
        if (!CogzidelSearch.locationHasChanged) {
            CogzidelSearch.params.sort = jQuery("#sort").val();
            jQuery("input[name='neighborhoods']").each(function (o, p) {
                if (jQuery(p).is(":checked")) {
                    CogzidelSearch.params.neighborhoods.push(jQuery(p).val())
                }
            })
        }
        CogzidelSearch.params.hosting_amenities = CogzidelSearch.params.hosting_amenities.unique();
        CogzidelSearch.params.neighborhoods = CogzidelSearch.params.neighborhoods.unique();
        CogzidelSearch.params.room_types = CogzidelSearch.params.room_types.unique();
        var h = jQuery("#keywords");
        if (h.attr("defaultValue") !== h.val()) {
            CogzidelSearch.params.keywords = h.val()
        }
        var j = $("#slider-range");
        var m = j.slider("option", "min");
        var c = j.slider("option", "max");
        var a = j.slider("values", 0);
        var g = j.slider("values", 1);
        if (c !== g || m !== a) {
            CogzidelSearch.params.price_min = a;
            CogzidelSearch.params.price_max = g
        }
        if (redoSearchInMapIsChecked()) {
            if (AMM.new_bounds && (!(CogzidelSearch.locationHasChanged) || CogzidelSearch.results_changed_by_map_action)) {
                CogzidelSearch.params.sw_lat = AMM.new_bounds.getSouthWest().lat();
                CogzidelSearch.params.sw_lng = AMM.new_bounds.getSouthWest().lng();
                CogzidelSearch.params.ne_lat = AMM.new_bounds.getNorthEast().lat();
                CogzidelSearch.params.ne_lng = AMM.new_bounds.getNorthEast().lng();
                CogzidelSearch.params.search_by_map = true
            } else {
                if (e && e.forceBounds) {
                    CogzidelSearch.params.sw_lat = e.forceBounds.sw_lat;
                    CogzidelSearch.params.sw_lng = e.forceBounds.sw_lng;
                    CogzidelSearch.params.ne_lat = e.forceBounds.ne_lat;
                    CogzidelSearch.params.ne_lng = e.forceBounds.ne_lng;
                    var l = new google.maps.LatLng(e.forceBounds.sw_lat, e.forceBounds.sw_lng);
                    var d = new google.maps.LatLng(e.forceBounds.ne_lat, e.forceBounds.ne_lng);
                    AMM.new_bounds = new google.maps.LatLngBounds(l, d);
                    AMM.fitBounds(AMM.new_bounds);
                    CogzidelSearch.params.search_by_map = true
                }
            }
        }
        if (CogzidelSearch.currentViewType === "photo") {
            CogzidelSearch.params.per_page = 20
        } else {
            if (CogzidelSearch.currentViewType === "list") {
                CogzidelSearch.params.per_page = 20
            } else {
                if (CogzidelSearch.currentViewType === "map") {
                    CogzidelSearch.params.per_page = 40
                }
            }
        }
        return CogzidelSearch.params
    },
    markUrlAsModified: function () {
        try {
            window.location.hash = "modified=true"
        } catch (a) {}
    },
    loadNewResultsWithNoResponse: function () {
        CogzidelSearch.setParamsFromDom();
        CogzidelSearch.params.suppress_response = true;
        CogzidelSearch.markUrlAsModified();
        CogzidelSearch.activeAjaxRequest = jQuery.getJSON(base_url+"search/ajax_get_results", CogzidelSearch.params, function (a) {
            CogzidelSearch.params.suppress_response = false
        })
    },
    loadNewResultsCallback: function (b) {
        if (!b) {
            CogzidelSearch.resultsJson = false;
            hideLoadingOverlays();
            CogzidelSearch.trackSearch();
            return false
        }
        CogzidelSearch.resultsJson = b;
        if (render_results(b, AMM.new_bounds)) {
            if (b.params) {
                SearchFilters.update(b.params)
            } else {
                if (b.facets) {
                    SearchFilters.update(b.facets)
                }
            }
            if (SearchFilters.per_month !== b.per_month) {
                SearchFilters.per_month = b.per_month;
                var c = SearchFilters.per_month === true && (CogzidelSearch.params.price_min < SearchFilters.minPriceMonthly || CogzidelSearch.params.price_max < SearchFilters.minPriceMonthly);
                var a = SearchFilters.per_month !== true && (CogzidelSearch.params.price_min > SearchFilters.maxPrice || CogzidelSearch.params.price_max > SearchFilters.maxPrice);
                var d = c || a;
                if (d) {
                    jQuery("#applied_filter_price").remove();
                    if (jQuery("#applied_filters").html() === "") {
                        jQuery("#results_filters").hide()
                    }
                    SearchFilters.setPriceSliderLimits(SearchFilters.per_month, true)
                } else {
                    SearchFilters.setPriceSliderLimits(SearchFilters.per_month, false)
                }
            }
            render_results_oncomplete(b)
        }
    },
    loadNewResults: function (d) {
        if (CogzidelSearch.initialLoadComplete === true) {
            CogzidelSearch.markUrlAsModified()
        }
        var c = d || false;
        AMM.initMapOnce("search_map");
        if (CogzidelSearch.results_changed_by_map_action === true && !redoSearchInMapIsChecked() && c === false) {
            reset_params_to_defaults();
            setTimeout(function () {
                AMM.turnMapListenersOn()
            }, 1000);
            return true
        }
        killActiveAjaxRequest();
       // SearchFilters.closeFiltersLightbox();
        var a = jQuery("#search_header").is(":visible");
        var b = jQuery(window).scrollTop();
        if ((a === true && b > 275) || (a === false && b > 129)) {
            jQuery("html, body").animate({
                scrollTop: jQuery("#Selsearch_params").offset().top
            }, "fast")
        }
        showLoadingOverlays();
        CogzidelSearch.setParamsFromDom();
			
        CogzidelSearch.activeAjaxRequest = jQuery.getJSON(base_url+"search/ajax_get_results", CogzidelSearch.params, CogzidelSearch.loadNewResultsCallback);
        if (CogzidelSearch.isViewingStarred) {
            jQuery("#share_results_link").hide()
        } else {
            jQuery("#share_results_link").show()
        }
        return true
    },
    hoverListResult: function (c) {
        var b;
        var d = ["#room_", c].join("");
        var a = AMM.markers[c];
        jQuery(d).addClass("hover");
        if (SS.initialized === true) {
            SS.show(d, c)
        }
        if (a) {
            if (a.numbered_pin !== false) {
                if (jQuery.inArray(c.toString(), CogzidelSearch.viewedIds) !== -1) {
                    b = MapIcons.numberedVisitedHover[a.numbered_pin + 1]
                } else {
                    b = MapIcons.numberedHover[a.numbered_pin + 1]
                }
                a.marker.setIcon(b)
            }
        }
    },
    unHoverListResult: function (c) {
        var b;
        var a = AMM.markers[c];
        jQuery(["#room_", c].join("")).removeClass("hover");
        if (SS.initialized === true) {
            SS.reset()
        }
        if (a) {
            if (a.numbered_pin !== false) {
                if (jQuery.inArray(c.toString(), CogzidelSearch.viewedIds) !== -1) {
                    b = MapIcons.numberedVisited[a.numbered_pin + 1]
                } else {
                    b = MapIcons.numbered[a.numbered_pin + 1]
                }
                a.marker.setIcon(b)
            }
        }
    },
    is_map_search: function () {
        return CogzidelSearch.results_changed_by_map_action && !(CogzidelSearch.changing_display_type)
    },
    showBlankState: function () {
        jQuery("#results").html(jQuery("#blank_state_content").html())
    },
    markViewedPageLinks: function () {
        if (CogzidelSearch.viewedIds === false || CogzidelSearch.viewedIds.size === 0) {
            return
        }
        jQuery("#results .search_result").each(function (a, b) {
            b = jQuery(b);
            var c = b.attr("id").replace("room_", "");
            if (jQuery.inArray(c, CogzidelSearch.viewedIds) !== -1) {
                b.addClass("visited")
            }
        })
    },
    getViewedPage3Ids: function () {
        var b = jQuery.cookie("viewed_page3_ids");
        if (b !== null) {
            var a = b.split(",");
            a = a.unique();
            return a
        }
        return []
    },
    trackSearch: function () {
        TrackingPixel.track()
    },
    presentStandbyOption: function () {
        $("#standby_action_area").show()
    },
    hideStandbyOption: function () {
        $("#standby_action_area").hide()
    },
    includeCollectionParam: function () {
        return (CogzidelSearch.collectionId !== false) && (CogzidelSearch.forceHideCollection === false) && (CogzidelSearch.searchHasBeenModified() === false || (!(CogzidelSearch.params.location) || CogzidelSearch.params.location === ""))
    },
    includeHostParam: function () {
        return (CogzidelSearch.hostId !== false) && (CogzidelSearch.forceHideHost === false) && (CogzidelSearch.searchHasBeenModified() === false || (!(CogzidelSearch.params.location) || CogzidelSearch.params.location === ""))
    },
    includeGroupParam: function () {
        return (CogzidelSearch.groupId !== false) && (CogzidelSearch.forceHideGroup === false) && (CogzidelSearch.searchHasBeenModified() === false || (!(CogzidelSearch.params.location) || CogzidelSearch.params.location === ""))
    },
    $: jQuery(this)
};
var TrackingPixel = {
    params: {
        uuid: "",
        user: "",
        af: "",
        c: "",
        pg: "",
        checkin: "",
        ngt: "",
        gc: "1",
        bc: "",
        lat: "",
        lng: ""
    },
    imgId: "#page2_v3_tp",
    BASE_URL: "http://pluto.cogzidel.com/t/t.php?",
    updateParamsFromDom: function () {
        if (AMM.map) {
            var g = AMM.map.getCenter();
            TrackingPixel.params.lat = g.lat();
            TrackingPixel.params.lng = g.lng()
        }
        var e = jQuery("#checkin").val();
        var d = jQuery("#checkout").val();
        if (e == "mm/dd/yyyy" || d == "mm/dd/yyyy") {
            TrackingPixel.params.checkin = "";
            TrackingPixel.params.checkout = ""
        } else {
            TrackingPixel.params.checkin = e;
            var c = new Date(e);
            var f = new Date(d);
            var b = 86400000;
            var a = Math.abs(c.getTime() - f.getTime());
            var h = Math.round(a / b);
            TrackingPixel.params.ngt = h || ""
        }
        TrackingPixel.params.gc = jQuery("#number_of_guests").val() || "1"
    },
    serializedParams: function () {
        return jQuery.param(TrackingPixel.params)
    },
    updateImgSrc: function () {
        var b = jQuery(TrackingPixel.imgId);
        if (b) {
            var a = [TrackingPixel.BASE_URL, TrackingPixel.serializedParams()].join("");
            b.attr("src", a)
        }
    },
    track: function () {
        TrackingPixel.updateParamsFromDom();
        TrackingPixel.updateImgSrc()
    }
};
var AMM = {
    map: "",
    isFirstMapInteraction: true,
    redoSearchPromptTimeout: false,
    overlay: false,
    mapLoaded: false,
    new_bounds: false,
    currentBounds: false,
    currentHighestZIndex: 0,
    activeInfoWindow: null,
    activeInfoWindowMarker: false,
    queue: [],
    activeHostingIds: [],
    markers: {},
    defaultMapOptions: {},
    centerLat: false,
    centerLng: false,
    centerMarker: false,
    geocodePrecision: false,
    initMapOnce: function (a) {
        if (AMM.mapLoaded === false) {
            if (window.google && window.google.maps) {
                $("#map_options").show();
                $("#map_wrapper").show();
                AMM.defaultMapOptions = {
                    zoom: 6,
                    center: new google.maps.LatLng(40.7442, -73.9861),
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    disableDefaultUI: true,
                    navigationControl: true,
                    navigationControlOptions: {
                        position: google.maps.ControlPosition.LEFT
                    },
                    scaleControl: true,
                    scrollwheel: false
                };
                AMM.map = new google.maps.Map(document.getElementById(a), AMM.defaultMapOptions);
                AMM.overlay = new google.maps.OverlayView();
                //var mc = new MarkerClusterer(AMM.map);
                AMM.overlay.draw = function () {};
                AMM.overlay.setMap(AMM.map);
                MapIcons.init();
                AMM.mapLoaded = true
            } else {
                $("#map_options").hide();
                $("#map_wrapper").hide()
            }
        }
    },
    add: function (a, b) {
        if (!AMM.markers[b.id]) {
            AMM.markers[b.id] = {
                location: a,
                details: b,
                active: false
            }
        }
        AMM.queue.push(b.id)
    },
    drawCenterMarker: function () {
        AMM.clearCenterMarker();
        if (AMM.mapLoaded && AMM.centerLat && AMM.centerLng) {
            var b = 1;
            if (AMM.geocodePrecision) {
                if (AMM.geocodePrecision == "address") {
                    b = 100
                }
            }
            var d = new google.maps.LatLng(AMM.centerLat, AMM.centerLng);
            var a = new google.maps.Marker({
                position: d,
                map: AMM.map,
                icon: MapIcons.centerPoint,
                shadow: MapIcons.shadowCenterPoint,
                title: Translations.you_are_here,
                zIndex: b
            });
            AMM.centerMarker = a;
            var c = AMM.currentBounds;
            if (c === false) {
                c = new google.maps.LatLngBounds()
            }
            c.extend(d)
        }
    },
    clearCenterMarker: function () {
        if (AMM.centerMarker !== false) {
            AMM.centerMarker.setMap(null);
            AMM.centerMarker = false
        }
    },
    clearOverlays: function (a) {
        if (AMM.markers) {
            jQuery.each(AMM.markers, function (b, c) {
                if (jQuery.inArray(parseInt(b, 10), AMM.queue) === -1 || a === true) {
                    AMM.removeOverlay(b)
                }
            })
        }
    },
    openInfoWindow: function (b, a, d) {
        var c = AMM.activeInfoWindow;
        AMM.activeInfoWindowMarker = a;
        if (c) {
            c.setContent(b);
            c.open(AMM.map, a)
        } else {
            c = AMM.activeInfoWindow = new google.maps.InfoWindow({
                content: b,
                maxWidth: 241
            });
            google.maps.event.addListenerOnce(c, "closeclick", function () {
                c = AMM.activeInfoWindow = AMM.activeInfoWindowMarker = null
            });
            c.open(AMM.map, a)
        }
        if (SS.initialized === true) {
            SS.reset();
            google.maps.event.addListenerOnce(c, "domready", function () {
                if (typeof SS.pictureArrays[d] !== "undefined") {
                    jQuery(".map_info_window").find("img").attr("src", SS.fullImageUrl(SS.pictureArrays[d][0]))
                }
                SS.show(".map_info_window", d)
            })
        }
    },
    closeInfoWindow: function () {
        if (AMM.activeInfoWindow) {
            google.maps.event.clearInstanceListeners(AMM.activeInfoWindow);
            AMM.activeInfoWindow.close();
            AMM.activeInfoWindow = AMM.activeInfoWindowMarker = null;
            if (SS.initialized === true) {
                SS.hide()
            }
            return true
        } else {
            return false
        }
    },
    removeOverlay: function (b) {
        var a = AMM.markers[b];
        if (a.active === true) {
            if (a.infoWindow) {
                google.maps.event.clearInstanceListeners(a.infoWindow);
                a.infoWindow = null
            }
            google.maps.event.clearInstanceListeners(a.marker);
            a.marker.setMap(null);
            a.marker = null;
            a.active = false
        }
    },
    showOverlays: function () {
        var a = 20;
        var b = AMM.queue.length;
        jQuery.each(AMM.queue, function (e, h) {
            var c, l, f, k;
            var j = AMM.markers[h];
            if (j && !j.active) {
                c = j.details;
                k = jQuery.inArray(h.toString(), CogzidelSearch.viewedIds) !== -1;
                if (e < a) {
                    if (k) {
                        l = MapIcons.numberedVisited[e + 1]
                    } else {
                        l = MapIcons.numbered[e + 1]
                    }
                    j.numbered_pin = e;
                    f = new google.maps.Marker({
                        position: j.location,
                        map: AMM.map,
                        icon: l,
                        shadow: MapIcons.shadowStandard,
                        title: [(e + 1), ". ", c.name].join(""),
                        zIndex: (b - e)
                    })
                } else {
                    if (k) {
                        l = MapIcons.smallVisited
                    } else {
                        l = MapIcons.small
                    }
                    j.numbered_pin = false;
                    f = new google.maps.Marker({
                        position: j.location,
                        map: AMM.map,
                        icon: l,
                        shadow: MapIcons.shadowSmall,
                        title: c.name,
                        zIndex: (b - e)
                    })
                }           
                if (CogzidelSearch.currentViewType === "map") {
                    var g = (c.review_count === 1 ? Translations.review : Translations.reviews);
                    var d = ['<div class="map_info_window">', '<a class="map_info_window_link_image" href="'+base_url+'rooms/', h, '" />', '<img width="210" height="140" class="map_info_window_thumbnail" src="', c.smallThumbnail, '" />', "</a>", '<p class="map_info_window_details">', '<a class="map_info_window_link" href="'+base_url+'rooms/', h, '" />', c.name, "</a>", '<span class="map_info_window_review_count">', c.review_count, " ", g, "</span>", '<span class="map_info_window_price">', c.symbol, c.price, CogzidelSearch.currencySymbolRight, "</span>", "</p>", "</div>"].join("");
                    google.maps.event.addListener(f, "click", function (i) {
                        AMM.openInfoWindow(d, f, h)
                    })
                } else {
                    google.maps.event.addListener(f, "mouseover", function () {
                        CogzidelSearch.hoverListResult(h)
                    });
                    google.maps.event.addListener(f, "mouseout", function () {
                        CogzidelSearch.unHoverListResult(h)
                    });
                    google.maps.event.addListener(f, "click", function () {
                        CogzidelSearch.viewedIds.push(h.toString());
                        var i = MapIcons.numberedVisitedHover[j.numbered_pin + 1];
                        j.marker.setIcon(i);
                        window.location = [base_url+"rooms/", h].join("")
                    })
                }
                j.marker = f;
                j.active = true
            }
        });
        AMM.clearQueue()
    },
    clearQueue: function () {
        AMM.queue = []
    },
    turnMapListenersOn: function () {
        AMM.listenForMapChanges()
    },
    turnMapListenersOff: function () {
        if (AMM.mapLoaded) {
            google.maps.event.clearListeners(AMM.map, "idle")
        }
    },
    listenForMapChanges: function () {
        if (AMM.mapLoaded) {
            google.maps.event.addListener(AMM.map, "idle", function () {
                AMM.mapListenerCallback()
            })
        }
    },
    fitBounds: function (a) {
        if (AMM.mapLoaded) {
            AMM.map.fitBounds(a)
        }
    },
    mapListenerCallback: function () {
        if (AMM.isFirstMapInteraction === true) {
            AMM.isFirstMapInteraction = false;
            var a = jQuery("#first_time_map_question");
            if (!redoSearchInMapIsChecked()) {
                AMM.redoSearchPromptTimeout = setTimeout(function () {
                    a.fadeOut(2000)
                }, 14000);
                a.show();
                return false
            }
        }
        if (AMM.activeInfoWindow && AMM.activeInfoWindowMarker) {
            var i = AMM.overlay.getProjection().fromLatLngToContainerPixel(AMM.activeInfoWindowMarker.getPosition());
            var j = i.x;
            var g = i.y;
            var c = 82;
            var l = jQuery("#search_map");
            var e = l.width();
            var d = l.height();
            var k = 260;
            var h = 250;
            var b = k / 2;
            var f = h / 2;
            if (redoSearchInMapIsChecked()) {
                if ((j < f) || (g < b) || (j > (e - (f) + (c / 2))) || (g > (d + (b * 1.33)))) {
                    AMM.closeInfoWindow()
                }
            } else {
                if (j < 0 || g < 0 || (j > (e + c)) || g > (d + k)) {
                    AMM.closeInfoWindow()
                }
            }
        }
        if (!AMM.activeInfoWindow) {
            CogzidelSearch.results_changed_by_map_action = true;
//            $("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults()
        }
    }
};
var Connections = {
    COOKIE_NAME: "cogzidel_connect_banner",
    COOKIE_HIDE_VALUE: "hide",
    init: function () {
        var b = $("#connect_banner");
        var c = $("#airtv_promo");
        var a = jQuery.cookie(Connections.COOKIE_NAME);
        if (c.length < 1 && b.length > 0 && a !== Connections.COOKIE_HIDE_VALUE) {
            $("#connect_banner_close").click(Connections.closeClickHandler);
            $("#fb-connect-banner-button").click(Connections.connectButtonClickHandler);
            b.show()
        }
    },
    closeClickHandler: function (a) {
        $.cookie(Connections.COOKIE_NAME, Connections.COOKIE_HIDE_VALUE, {
            expires: 90,
            path: "/"
        });
        $b.remove();
        $b = null;
        Connections.trackEvent("hidePage2Banner");
        a.preventDefault()
    },
    connectButtonClickHandler: function () {
        var b = "loading";
        var a = $(this).addClass(b);
        Cogzidel.FBConnect.startLoginFlow();
        Cogzidel.FBConnect.one("connect_success", function () {
            Connections.trackEvent("page2FbConnect")
        });
        Cogzidel.FBConnect.one("connect_cancel", function () {
            Connections.trackEvent("page2FbCancel");
            a.removeClass(b)
        });
        Cogzidel.FBConnect.one("bail", function () {
            Connections.trackEvent("page2FbBail");
            a.removeClass(b)
        });
        Cogzidel.FBConnect.one("complete", function () {
            Connections.trackEvent("page2FbComplete")
        });
        return false
    },
    trackEvent: function (a) {
        _gaq.push(["_trackEvent", "SocialConnections", a])
    }
};
var SearchFilters = {
    defaults: {
        callbackFunction: "CogzidelSearch.loadNewResults",
        maxFilters: 4
    },
    has_photo: [],
    host_has_photo: [],
    languages: [],
    property_type_id: [],
    top_neighborhoods: [],
    neighborhoods: [],
    top_amenities: [],
    amenities: [],
    min_bedrooms: [],
    min_beds: [],
    min_bathrooms: [],
    min_bed_type: [],
    group_ids: [],
    room_types: [
        [0, [Translations.private_room, 0]],
        [1, [Translations.shared_room, 0]],
        [2, [Translations.entire_place, 0]]
    ],
    minPrice: 10,
    maxPrice: 10000,
    minPriceMonthly: 150,
    maxPriceMonthly: 5000,
    per_month: false,
    filtersLightbox: false,
    applyPriceSliderChanges: function (a) {
        var b = jQuery("#slider-range").slider("option", "max");
        if (a !== undefined) {
            jQuery("#slider_user_min").html([a.values[0], CogzidelSearch.currencySymbolRight].join(""));
            jQuery("#slider_user_max").html([a.values[1], ((a.values[1] === b) ? "+ " : ""), CogzidelSearch.currencySymbolRight].join(""))
        } else {
            jQuery("#slider_user_min").html([jQuery("#slider-range").slider("values")[0], CogzidelSearch.currencySymbolRight].join(""));
            jQuery("#slider_user_max").html([jQuery("#slider-range").slider("values")[1], ((jQuery("#slider-range").slider("values")[1] === b) ? "+ " : ""), CogzidelSearch.currencySymbolRight].join(""))
        }
    },
    setPriceSliderLimits: function (b, d) {
        var c, a, e;
        if (b === true) {
            c = SearchFilters.minPriceMonthly;
            a = SearchFilters.maxPriceMonthly
        } else {
            c = SearchFilters.minPrice;
            a = SearchFilters.maxPrice
        }
        jQuery("#slider-range").slider("option", "min", c);
        jQuery("#slider-range").slider("option", "max", a);
        e = "+ ";
        if (d === false && CogzidelSearch.params.price_min && CogzidelSearch.params.price_max) {
            c = CogzidelSearch.params.price_min;
            a = CogzidelSearch.params.price_max;
            e = ""
        }
        jQuery("#slider-range").slider("values", 0, c);
        jQuery("#slider-range").slider("values", 1, a);
        jQuery("#slider_user_min").html([c, CogzidelSearch.currencySymbolRight].join(""));
        jQuery("#slider_user_max").html([a, e, CogzidelSearch.currencySymbolRight].join(""))
    },
    update: function (a) {
        SearchFilters.setFacets(a);
        SearchFilters.render()
    },
    setFacets: function (a) {
        SearchFilters.connected = a.connected || [];
        SearchFilters.room_types = a.room_type || [];
        SearchFilters.top_neighborhoods = a.top_neighborhoods || [];
        SearchFilters.neighborhoods = a.neighborhood_facet || [];
        SearchFilters.top_amenities = a.top_amenities || [];
        SearchFilters.amenities = a.hosting_amenity_ids || [];
        SearchFilters.has_photo = a.has_photo || [];
        SearchFilters.host_has_photo = a.host_has_photo || [];
        SearchFilters.languages = a.languages || [];
        SearchFilters.property_type_id = a.property_type_id || [];
        SearchFilters.group_ids = a.group_ids || [];
        SearchFilters.night_writer = a.night_writer || []
    },
    render: function (a) {
        SearchFilters.renderSocialConnections();
        SearchFilters.renderNightWriter();
        SearchFilters.renderRoomTypes();
        SearchFilters.renderAmenities();
        SearchFilters.renderNeighborhoods();
        SearchFilters.renderGenericLightboxFacet("property_type_id");
        SearchFilters.renderGenericLightboxFacet("languages");
        SearchFilters.renderGenericLightboxFacet("group_ids");
        SearchFilters.renderAppliedFilters();
        return true
    },
    APPLIED_FILTER_NAMES: {
        neighborhoods: Translations.neighborhoods,
        hosting_amenities: Translations.amenities,
        room_types: Translations.room_type,
        price: Translations.price,
        keywords: Translations.keywords,
        property_type_id: Translations.property_type,
        min_bedrooms: Translations.bedrooms,
        min_bathrooms: Translations.bathrooms,
        min_beds: Translations.beds,
        min_bed_type: Translations.bed_type,
        languages: Translations.languages,
        collection: Translations.collection,
        starred: "Starred Items",
        host: Translations.host,
        group: Translations.group,
        connections: Translations.connections,
        night_writer: Translations.night_writer
    },
    renderAppliedFilters: function () {
        var a;
        jQuery("#applied_filters").empty();
        if (CogzidelSearch.params.neighborhoods && CogzidelSearch.params.neighborhoods.length > 0) {
            SearchFilters.renderOneAppliedFilter("neighborhoods", SearchFilters.APPLIED_FILTER_NAMES.neighborhoods)
        }
        if (CogzidelSearch.params.price_max || CogzidelSearch.params.price_min) {
            SearchFilters.renderOneAppliedFilter("price", SearchFilters.APPLIED_FILTER_NAMES.price)
        }
        if (CogzidelSearch.params.hosting_amenities && CogzidelSearch.params.hosting_amenities.length > 0) {
            SearchFilters.renderOneAppliedFilter("hosting_amenities", SearchFilters.APPLIED_FILTER_NAMES.hosting_amenities)
        }
        if (CogzidelSearch.params.room_types && CogzidelSearch.params.room_types.length > 0) {
            SearchFilters.renderOneAppliedFilter("room_types", SearchFilters.APPLIED_FILTER_NAMES.room_types)
        }
        if (CogzidelSearch.params.keywords && CogzidelSearch.params.keywords.length > 0) {
            SearchFilters.renderOneAppliedFilter("keywords", SearchFilters.APPLIED_FILTER_NAMES.keywords)
        }
        if (CogzidelSearch.params.property_type_id && CogzidelSearch.params.property_type_id.length > 0) {
            SearchFilters.renderOneAppliedFilter("property_type_id", SearchFilters.APPLIED_FILTER_NAMES.property_type_id)
        }
        if (CogzidelSearch.params.min_bedrooms && CogzidelSearch.params.min_bedrooms > 0) {
            SearchFilters.renderOneAppliedFilter("min_bedrooms", SearchFilters.APPLIED_FILTER_NAMES.min_bedrooms)
        }
        if (CogzidelSearch.params.min_beds && CogzidelSearch.params.min_beds > 0) {
            SearchFilters.renderOneAppliedFilter("min_beds", SearchFilters.APPLIED_FILTER_NAMES.min_beds)
        }
        if (CogzidelSearch.params.min_beds && CogzidelSearch.params.min_beds > 0) {
            SearchFilters.renderOneAppliedFilter("min_bed_type", SearchFilters.APPLIED_FILTER_NAMES.min_bed_type)
        }
        if (CogzidelSearch.params.min_bathrooms && CogzidelSearch.params.min_bathrooms > 0) {
            SearchFilters.renderOneAppliedFilter("min_bathrooms", SearchFilters.APPLIED_FILTER_NAMES.min_bathrooms)
        }
        if (CogzidelSearch.params.languages && CogzidelSearch.params.languages.length > 0) {
            SearchFilters.renderOneAppliedFilter("languages", SearchFilters.APPLIED_FILTER_NAMES.languages)
        }
        if (CogzidelSearch.params.connected) {
            SearchFilters.renderOneAppliedFilter("connections", SearchFilters.APPLIED_FILTER_NAMES.connections)
        }
        if (CogzidelSearch.params.night_writer) {
            SearchFilters.renderOneAppliedFilter("night_writer", SearchFilters.APPLIED_FILTER_NAMES.night_writer)
        }
        if (CogzidelSearch.includeCollectionParam() === true) {
            a = CogzidelSearch.isViewingStarred ? SearchFilters.APPLIED_FILTER_NAMES.starred : SearchFilters.APPLIED_FILTER_NAMES.collection;
            if (CogzidelSearch.collectionName && CogzidelSearch.collectionName !== "") {
                a = [a, CogzidelSearch.collectionName].join(": ")
            }
            SearchFilters.renderOneAppliedFilter("collections", a)
        }
        if (CogzidelSearch.isViewingStarred) {
            a = SearchFilters.APPLIED_FILTER_NAMES.starred;
            SearchFilters.renderOneAppliedFilter("starred", a)
        }
        if (CogzidelSearch.includeHostParam()) {
            var c = SearchFilters.APPLIED_FILTER_NAMES.host;
            if (CogzidelSearch.hostName && CogzidelSearch.hostName !== "") {
                c = [c, CogzidelSearch.hostName].join(": ")
            }
            SearchFilters.renderOneAppliedFilter("host", c)
        }
        if (CogzidelSearch.includeGroupParam()) {
            var b = SearchFilters.APPLIED_FILTER_NAMES.group;
            if (CogzidelSearch.groupName && CogzidelSearch.groupName !== "") {
                b = [b, CogzidelSearch.groupName].join(": ")
            }
            SearchFilters.renderOneAppliedFilter("group", b)
        }
        if (jQuery("#applied_filters").html() === "") {
            jQuery("#results_filters").hide()
        } else {
            jQuery("#results_filters").show()
        }
    },
    appliedFilterXCallback: function (a) {
        var c = true;
        var d = $(a).closest("li");
        var b = $(d).attr("id").replace("applied_filter_", "");
        switch (b) {
        case "neighborhoods":
            SearchFilters.clearNeighborhoods();
            break;
        case "price":
            SearchFilters.clearPrice();
            break;
        case "hosting_amenities":
            SearchFilters.clearAmenities();
            break;
        case "room_types":
            SearchFilters.clearRoomTypes();
            break;
        case "keywords":
            SearchFilters.clearKeywords();
            break;
        case "property_type_id":
            SearchFilters.clearPropertyTypes();
            break;
        case "min_bedrooms":
            SearchFilters.clearMinBedrooms();
            break;
        case "min_bathrooms":
            SearchFilters.clearMinBathrooms();
            break;
        case "min_beds":
            SearchFilters.clearMinBeds();
            break;
        case "min_bed_type":
            SearchFilters.clearMinBedType();
            break;
        case "languages":
            SearchFilters.clearLanguages();
            break;
        case "collections":
            SearchFilters.clearCollections();
            break;
        case "starred":
            SearchFilters.clearStarred();
            break;
        case "host":
            SearchFilters.clearHost();
            break;
        case "group":
            SearchFilters.clearGroup();
            break;
        case "connections":
            SearchFilters.clearConnections();
            break;
        case "night_writer":
            SearchFilters.clearNightWriter();
            break;
        default:
        }
        d.remove();
        if (c === true) {
        	$("#search_type_list").trigger("click");
            CogzidelSearch.loadNewResults()
        }
    },
    clearStarred: function () {
        CogzidelSearch.isViewingStarred = false
    },
    clearCollections: function (a) {
        a = a || true;
        if (CogzidelSearch.collectionId !== false && jQuery.trim(CogzidelSearch.collectionName).length !== 0) {
            setTimeout(function () {
                if (jQuery("#reinstate_collections").length === 0) {
                    jQuery(["<a class='rounded_more reinstate_button' id='reinstate_collections'>Back to the  \"", CogzidelSearch.collectionName, '" Collection</a>'].join("")).insertBefore("#Search_Main")
                }
            }, 1000)
        }
        CogzidelSearch.forceHideCollection = a
    },
    reinstateCollections: function () {
        CogzidelSearch.forceHideCollection = false;
        jQuery("#location").val("");
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    clearHost: function () {
        if (CogzidelSearch.hostId !== false && CogzidelSearch.hostName !== "") {
            setTimeout(function () {
                if (jQuery("#reinstate_user").length === 0) {
                    jQuery(["<a class='rounded_more reinstate_button' id='reinstate_user'>Back to properties from ", CogzidelSearch.hostName, "</a>"].join("")).insertBefore("#Search_Main")
                }
            }, 1000)
        }
        CogzidelSearch.forceHideHost = true
    },
    reinstateHost: function () {
        CogzidelSearch.forceHideHost = false;
        jQuery("#location").val("");
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    clearGroup: function () {
        if (CogzidelSearch.groupId !== false && CogzidelSearch.groupName !== "") {
            setTimeout(function () {
                if (jQuery("#reinstate_group").length === 0) {
                    jQuery(["<a class='rounded_more reinstate_button' id='reinstate_group'>Back to properties from ", CogzidelSearch.groupName, "</a>"].join("")).insertBefore("#Search_Main")
                }
            }, 1000)
        }
        CogzidelSearch.forceHideGroup = true
    },
    reinstateGroup: function () {
        CogzidelSearch.forceHideGroup = false;
        jQuery("#location").val("");
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    clearNeighborhoods: function () {
        jQuery("input[name='neighborhoods']").each(function (a, b) {
            jQuery(b).attr("checked", false)
        })
    },
    clearAmenities: function () {
        jQuery("input[name='amenities']").each(function (a, b) {
            $(b).removeAttr("checked")
        })
    },
    clearLanguages: function () {
        $("input[name='languages']").each(function (a, b) {
            $(b).removeAttr("checked")
        })
    },
    clearConnections: function () {
        $("input[name='connected']").removeAttr("checked")
    },
    clearNightWriter: function () {
        $("input[name='night_writer']").removeAttr("checked")
    },
    clearKeywords: function () {
        var a = $("#keywords");
        delete CogzidelSearch.params.keywords;
        a.val(a.attr("defaultValue"))
    },
    clearRoomTypes: function () {
        $("input[name='room_types']").each(function (a, b) {
            $(b).removeAttr("checked")
        })
    },
    clearPropertyTypes: function () {
        $("input[name='property_type_id']").each(function (a, b) {
            $(b).removeAttr("checked")
        })
    },
    clearMinBedrooms: function () {
        jQuery("#min_bedrooms").val("")
    },
    clearMinBathrooms: function () {
        jQuery("#min_bathrooms").val("")
    },
    clearMinBeds: function () {
        jQuery("#min_beds").val("")
    },
    clearMinBedType: function () {
        jQuery("#min_bed_type").val("")
    },
    clearPrice: function () {
        SearchFilters.setPriceSliderLimits(SearchFilters.per_month, true);
        SearchFilters.applyPriceSliderChanges()
    },
    renderOneAppliedFilter: function (a, b) {
        $("#applied_filters").append($("#applied_filters_template").jqote({
            filter_id: a,
            filter_display_name: b
        }, "*"))
    },
    renderNightWriter: function () {
        var a = "#night_writer_container .search_filter_content";
        $(a).empty();
        SearchFilters.buildCheckbox({
            elementId: "night_writer",
            elementName: "night_writer",
            htmlValue: "night_writer",
            label: Translations.night_writer,
            forceActive: true,
            appendToElementSelector: a,
            checked: CogzidelSearch.params.night_writer
        })
    },
    renderSocialConnections: function () {
        var b = SearchFilters.connected && SearchFilters.connected[0];
        var a = "#social_connections_container .search_filter_content";
        $(a).empty();
        if (b) {
            SearchFilters.buildCheckbox({
                elementId: "connected",
                elementName: "connected",
                htmlValue: "connected",
                label: Translations.social_connections,
                facetCount: b[1],
                forceActive: true,
                appendToElementSelector: a,
                checked: CogzidelSearch.params.connected
            })
        }
        $(a).append('<li><a href="/social/" target="_blank">' + Translations.learn_more + "!</a></li>")
    },
    renderRoomTypes: function () {
        jQuery("#room_type_container ul.search_filter_content").empty();
        jQuery("#lightbox_filter_content_room_type").empty();
        jQuery.each(SearchFilters.room_types, function (a, c) {
            var b;
            if (CogzidelSearch.params.room_types && jQuery.inArray(c[0], CogzidelSearch.params.room_types) > -1) {
                b = true
            } else {
                b = false
            }
            SearchFilters.buildCheckbox({
                elementId: ["room_type_", a].join(""),
                elementName: "room_types",
                htmlValue: c[0],
                label: c[1][0],
                facetCount: c[1][1],
                forceActive: true,
                appendToElementSelector: "#room_type_container ul.search_filter_content",
                checked: b
            });
            SearchFilters.buildCheckbox({
                elementId: ["lightbox_room_type_", a].join(""),
                elementName: "room_types",
                htmlValue: c[0],
                label: c[1][0],
                forceActive: true,
                facetCount: c[1][1],
                appendToElementSelector: "#lightbox_filter_content_room_type",
                checked: b
            })
        });
        SearchFilters.appendShowMoreLink("#room_type_container ul.search_filter_content")
    },
    renderAmenities: function () {
        var a = 0;
        var b;
        jQuery("#amenities_container ul.search_filter_content").empty();
        jQuery("#lightbox_container_amenities ul.search_filter_content").empty();
        if (parseInt(SearchFilters.top_amenities.length, 10) > 0) {
            jQuery.each(SearchFilters.top_amenities, function (c, d) {
                if (CogzidelSearch.params && CogzidelSearch.params.hosting_amenities && jQuery.inArray(d[0].toString(), CogzidelSearch.params.hosting_amenities) > -1) {
                    b = true
                } else {
                    b = false
                }
                if (c < SearchFilters.defaults.maxFilters) {
                    SearchFilters.buildCheckbox({
                        elementId: "amenity_" + c,
                        elementName: "amenities",
                        htmlValue: d[0],
                        label: d[1][0],
                        facetCount: d[1][1],
                        checked: b,
                        appendToElementSelector: "#amenities_container ul.search_filter_content"
                    })
                }
                a++
            })
        }
        if (parseInt(SearchFilters.amenities.length, 10) > 0 && parseInt(SearchFilters.amenities.length, 10) > a) {
            jQuery.each(SearchFilters.amenities, function (c, d) {
                if (CogzidelSearch.params && CogzidelSearch.params.hosting_amenities && jQuery.inArray(d[0].toString(), CogzidelSearch.params.hosting_amenities) > -1) {
                    b = true
                } else {
                    b = false
                }
                if (a === 0) {
                    SearchFilters.buildCheckbox({
                        elementId: "amenity_" + a,
                        elementName: "amenities",
                        htmlValue: d[0],
                        label: d[1][0],
                        facetCount: d[1][1],
                        checked: b,
                        appendToElementSelector: "#amenities_container ul.search_filter_content"
                    })
                }
                SearchFilters.buildCheckbox({
                    elementId: "lightbox_amenity_" + a,
                    elementName: "amenities",
                    htmlValue: d[0],
                    label: d[1][0],
                    facetCount: d[1][1],
                    checked: b,
                    appendToElementSelector: "#lightbox_container_amenities ul.search_filter_content"
                });
                a++
            });
            if (SearchFilters.amenities.length > SearchFilters.defaults.maxFilters) {
                SearchFilters.appendShowMoreLink("#amenities_container ul.search_filter_content")
            }
        } else {
            jQuery("#amenities_container").hide()
        }
        if (a > 0) {
            jQuery("#amenities_container").show()
        }
        return true
    },
    renderNeighborhoods: function () {
        var a = 0;
        var b;
        var c = true;
        jQuery("#neighborhood_container ul.search_filter_content").empty();
        jQuery("#lightbox_container_neighborhood ul.search_filter_content").empty();
        if (SearchFilters.top_neighborhoods && parseInt(SearchFilters.top_neighborhoods.length, 10) > 0) {
            jQuery.each(SearchFilters.top_neighborhoods, function (d, e) {
                c = true;
                if (e[0].indexOf("'") > -1) {
                    c = false
                }
                if (CogzidelSearch.params && CogzidelSearch.params.neighborhoods && jQuery.inArray(e[0], CogzidelSearch.params.neighborhoods) > -1) {
                    b = true
                } else {
                    b = false
                }
                if (d < SearchFilters.defaults.maxFilters && c) {
                    SearchFilters.buildCheckbox({
                        elementId: "neighborhood_" + d,
                        elementName: "neighborhoods",
                        htmlValue: e[0],
                        label: e[1][0],
                        facetCount: e[1][1],
                        checked: b,
                        appendToElementSelector: "#neighborhood_container ul.search_filter_content"
                    })
                }
                a++
            })
        }
        if (SearchFilters.neighborhoods && parseInt(SearchFilters.neighborhoods.length, 10) > 0 && parseInt(SearchFilters.neighborhoods.length, 10) > a) {
            jQuery.each(SearchFilters.neighborhoods, function (d, e) {
                c = true;
                if (e[0].indexOf("'") > -1) {
                    c = false
                }
                if (CogzidelSearch.params && CogzidelSearch.params.neighborhoods && jQuery.inArray(e[0], CogzidelSearch.params.neighborhoods) > -1) {
                    b = true
                } else {
                    b = false
                }
                if (a === 0 && c) {
                    SearchFilters.buildCheckbox({
                        elementId: "neighborhood_" + a,
                        elementName: "neighborhoods",
                        htmlValue: e[0],
                        label: e[1][0],
                        facetCount: e[1][1],
                        checked: b,
                        appendToElementSelector: "#neighborhood_container ul.search_filter_content"
                    })
                }
                if (c) {
                    SearchFilters.buildCheckbox({
                        elementId: "lightbox_neighborhood_" + a,
                        elementName: "neighborhoods",
                        htmlValue: e[0],
                        label: e[1][0],
                        facetCount: e[1][1],
                        checked: b,
                        appendToElementSelector: "#lightbox_container_neighborhood ul.search_filter_content"
                    })
                }
                a++
            });
            if (SearchFilters.neighborhoods.length > SearchFilters.defaults.maxFilters) {
                SearchFilters.appendShowMoreLink("#neighborhood_container ul.search_filter_content")
            }
        } else {
            jQuery("#neighborhood_container").hide()
        }
        if (a > 0) {
            jQuery("#neighborhood_container").show()
        }
        return true
    },
    renderGenericLightboxFacet: function (a) {
        var b;
        jQuery(["#lightbox_filter_content_", a].join("")).empty();
        jQuery.each(SearchFilters[a], function (c, d) {
            if (CogzidelSearch.params && CogzidelSearch.params[a] && CogzidelSearch.params[a] !== undefined && (jQuery.inArray(d[0].toString(), CogzidelSearch.params[a]) > -1)) {
                b = true
            } else {
                b = false
            }
            SearchFilters.buildCheckbox({
                elementId: ["lightbox_", a, "_", c].join(""),
                elementName: a,
                htmlValue: d[0],
                label: d[1][0],
                forceActive: true,
                facetCount: d[1][1],
                appendToElementSelector: ["#lightbox_filter_content_", a].join(""),
                checked: b
            })
        })
    },
    buildCheckbox: function (k) {
        k = k || {};
        var j = !! k.checked,
            l = k.elementName || "",
            d = k.elementId || "",
            b = k.appendToElementSelector || "",
            h = k.label || "",
            a = k.htmlValue.toString() || "",
            g = k.facetCount,
            f = k.forceActive,
            i = k.onChangecallbackFunction || SearchFilters.defaults.callbackFunction,
            c = g > 0 || j || f;
        var e = ["<li class='clearfix'>", (g > 0 ? ["<span class='facet_count'>", g, "</span>"].join("") : ""), "<input type='checkbox' id='", d, "' name='", l, "' value='", a, "'", (c ? "" : " disabled='disabled'"), (j ? " checked='checked'" : ""), " /> <label ", (c ? "" : ' class="disabled" '), "for='", d, "'>", h, "</label>", "</li>"].join("");
        if (b) {
            $(b).append(e)
        }
        return false
    },
    appendShowMoreLink: function (a) {
        return $(a).append("<li><a href='javascript:void(0);' class='show_more_link'>" + Translations.show_more + "</a></li>")
    },
    openFiltersLightbox: function () {
        $.colorbox({
            inline: true,
            height: 480,
            width: 600,
            href: "#filters_lightbox"
        })
    },
    closeFiltersLightbox: function () {
    	SearchFilters.clearRoomTypes()
    	SearchFilters.clearPropertyTypes()
    	SearchFilters.clearAmenities()
    	SearchFilters.clearMinBedrooms()
    	SearchFilters.clearMinBathrooms()
    	SearchFilters.clearMinBeds()
        $.colorbox.close()
        $("#search_type_list").trigger("click");
        CogzidelSearch.loadNewResults()
    },
    selectLightboxTab: function (a) {
        var b = a || "room_type";
        $(".filters_lightbox_nav_element").removeClass("active");
        $(".lightbox_filter_container").hide();
        $("#lightbox_nav_" + b).addClass("active");
        $("#lightbox_container_" + b).show()
    }
};
var SS = {
    initialized: false,
    SHORT_TIMEOUT: 50,
    LONG_TIMEOUT: 675,
    FADE_DURATION: 250,
    containerEl: jQuery("#page2_inline_slideshow"),
    imgEl: jQuery("#page2_inline_slideshow_img"),
    isFirstHover: true,
    cloudFrontUrl: base_url+"images/",
    hoverTimeout: false,
    pictureArrays: {},
    currentUrls: [],
    currentParentDivId: undefined,
    currentHostingId: undefined,
    currentPosition: 0,
    addHostingAndIds: function (b, a) {
        if (SS.pictureArrays[b] === undefined) {
            SS.pictureArrays[b] = a
        }
    },
    fullImageUrl: function (b) {
        var a = [SS.cloudFrontUrl, b].join("");
        return a
    },
    initOnce: function () {
        if (SS.initialized === false) {
            SS.init()
        }
    },
    init: function () {
        jQuery("#page2_inline_slideshow, .map_info_window_link_image").live("hover", function () {
            if (SS.hoverTimeout) {
                clearTimeout(SS.hoverTimeout)
            }
            if (SS.isFirstHover === true) {
                SS.hoverTimeout = setTimeout(function () {
                    SS.next()
                }, SS.SHORT_TIMEOUT);
                SS.isFirstHover = false
            } else {
                SS.hoverTimeout = setTimeout(function () {
                    SS.next()
                }, SS.LONG_TIMEOUT)
            }
        }, function () {
            SS.reset()
        });
        SS.initialized = true
    },
    next: function () {
        var b, a;
        if (SS.totalPicturesSize() <= 1) {
            return
        }
        b = SS.fullImageUrl(SS.pictureArrays[SS.currentHostingId][0]);
        SS.pictureArrays[SS.currentHostingId].push(SS.pictureArrays[SS.currentHostingId].shift());
        a = SS.fullImageUrl(SS.pictureArrays[SS.currentHostingId][0]);
        SS.imgEl.attr("src", b);
        SS.imgEl.show();
        SS.preloadImage(a, (function (c) {
            return function () {
                if (c === SS.currentHostingId) {
                    if (CogzidelSearch.currentViewType === "map") {
                        jQuery(".map_info_window").find("img").attr("src", a)
                    } else {
                        jQuery(SS.currentParentDivId).find(".search_thumbnail").attr("src", a)
                    }
                    SS.imgEl.fadeOut(SS.FADE_DURATION, function () {
                        if (SS.hoverTimeout === null) {
                            SS.imgEl.attr("src", CogzidelSearch.PIXEL_PATH);
                            SS.hoverTimeout = setTimeout(function () {
                                SS.next()
                            }, SS.LONG_TIMEOUT)
                        }
                    })
                }
            }
        })(SS.currentHostingId));
        SS.hoverTimeout = null
    },
    show: function (a, b) {
        SS.currentParentDivId = a;
        SS.currentHostingId = b;
        SS.attachToParent();
        SS.containerEl.show()
    },
    hide: function () {
        SS.containerEl.hide()
    },
    attachToParent: function () {
        if (SS.currentParentDivId) {
            if (CogzidelSearch.currentViewType == "map") {
                SS.containerEl.appendTo(SS.currentParentDivId);
                SS.containerEl.attr("href", jQuery(SS.currentParentDivId).find("a.image_link").attr("href"))
            } else {
                SS.containerEl.appendTo(SS.currentParentDivId);
                SS.containerEl.attr("href", jQuery(SS.currentParentDivId).find("a.image_link").attr("href"))
            }
        }
    },
    reset: function () {
        SS.hide();
        SS.imgEl.hide();
        clearTimeout(SS.hoverTimeout);
        SS.hoverTimeout = false;
        SS.isFirstHover = true
    },
    totalPicturesSize: function () {
        return SS.pictureArrays[SS.currentHostingId].length
    },
    preloadImage: function (b, c) {
        var a = new Image();
        a.src = b;
        if (a.complete) {
            if (SS.hoverTimeout !== false) {
                c()
            }
            a.onload = function () {}
        } else {
            a.onload = function () {
                if (SS.hoverTimeout !== false) {
                    c()
                }
                a.onload = function () {}
            }
        }
    }
};
CogzidelSearch.$.bind("finishedrendering", function () {
    CogzidelSearch.updateFacebookBannerText()
});
(function (c, f) {
    var b = "has-mini-profile",
        g = "mini-profile-container",
        d = "/users/mini_profile/%USER_ID%",
        e = 400;
    f.miniprofile = function () {
        f("body").delegate("." + b, "hover", function (h) {
            if (h.type === "mouseenter" || h.type === "mouseover") {
                new a(f(this), h)
            }
        })
    };
    var a = function (h, i) {
            this.$t = h;
            this.userId = h.data("user_id");
            this.pageX = i.pageX;
            this.pageY = i.pageY;
            this.padding = 10;
            this.buffer = 16;
            this.guid = new Date().getTime();
            this.init()
        };
    a.fn = a.prototype;
    a.fn.init = function () {
        var h = this;
        if (!this.$t.data("mini-profile-attached")) {
            this.$t.data("mini-profile-attached", true);
            f(document).bind("mousemove.mini_profile" + this.guid, function (i) {
                h.pageX = i.pageX;
                h.pageY = i.pageY;
                if (!h.isInHitbox()) {
                    h.kill()
                }
            });
            setTimeout(function () {
                if (h.isInHitbox()) {
                    h.show()
                } else {
                    h.kill()
                }
            }, 500)
        }
    };
    a.fn.isInHitbox = function () {
        if (!this.tCoords) {
            var i = this.$t.offset();
            this.tCoords = {
                top: i.top,
                left: i.left,
                width: this.$t.width(),
                height: this.$t.height()
            }
        }
        if (this.inCoords(this.tCoords)) {
            return true
        }
        if (this.$el) {
            if (!this.elCoords || this.elCoords.width !== this.$el.width()) {
                var h = this.$el.offset();
                this.elCoords = {
                    top: h.top,
                    left: h.left,
                    width: this.$el.width(),
                    height: this.$el.height()
                }
            }
            if (this.inCoords(this.elCoords)) {
                return true
            }
            if (this.inBufferTriangle()) {
                return true
            }
        }
        return false
    };
    a.fn.inCoords = function (h) {
        h = this.pad(h);
        return (this.pageX >= h.left && this.pageX <= h.left + h.width && this.pageY >= h.top && this.pageY <= h.top + h.height)
    };
    a.fn.pad = function (h) {
        return f.extend({}, h, {
            top: h.top - this.padding,
            left: h.left - this.padding,
            width: h.width + 2 * this.padding,
            height: h.height + 2 * this.padding
        })
    };
    a.fn.inBufferTriangle = function () {
        if (!this.triCoords) {
            this.triCoords = {
                top: this.tCoords.top,
                left: this.tCoords.left + this.tCoords.width,
                height: this.tCoords.height,
                width: this.elCoords.width - this.tCoords.width,
                sideTop: this.elCoords.top < this.tCoords.top
            }
        }
        if (!this.inCoords(this.triCoords, false)) {
            return false
        }
        var k = this.pad(this.triCoords),
            h = k.height / (k.width - this.padding * 2),
            j = this.pageX - k.left - this.padding * 2,
            i = this.pageY - k.top;
        if (i >= h * j) {
            return this.triCoords.sideTop
        } else {
            return !this.triCoords.sideTop
        }
        return true
    };
    a.fn.show = function () {
        this.build();
        this.position();
        this.$el.css("visibility", "visible").hide().fadeIn(100);
        this.loadContent()
    };
    a.fn.build = function () {
        this.$el = f('<div><div class="arrow"></div></div>').attr("id", g).addClass("loading").appendTo("body")
    };
    a.fn.position = function (i) {
        var k = this.$t.offset(),
            h = this.$el.height(),
            j = i || k.top - (h + this.buffer);
        if (this.showBelow(j)) {
            j += h + this.buffer * 2 + this.$t.height();
            this.$el.addClass("below")
        }
        this.$el.css({
            left: k.left + "px",
            top: j + "px"
        });
        return j
    };
    a.fn.kill = function () {
        if (this.$el) {
            this.$el.fadeOut(100, function () {
                f(this).remove()
            })
        }
        f(document).unbind("mousemove.mini_profile" + this.guid);
        this.$t.data("mini-profile-attached", null)
    };
    a.fn.loadContent = function () {
        var j = this,
            o, n, m, l = this.$el.offset(),
            k = this.$el.height(),
            h = this.$el.width(),
            i;
        f.get(this.getUrl(), function (p) {
            o = f(p).css("visibility", "hidden").appendTo("body");
            n = o.width();
            m = o.height();
            o.hide().css("visibility", "visible").appendTo(j.$el);
            i = l.top + (k - m) * (j.showBelow(l.top) ? 0 : 1);
            if (j.showBelow(i)) {
                i = j.$t.offset().top + j.$t.height() + j.buffer;
                j.$el.css("top", i + "px");
                j.$el.addClass("below")
            }
            j.$el.animate({
                top: i,
                width: n,
                height: m
            }, 200, function () {
                o.fadeIn(200);
                j.$el.removeClass("loading")
            })
        })
    };
    a.fn.showBelow = function (h) {
        return (h <= f(document).scrollTop())
    };
    a.fn.getUrl = function () {
        return d.replace("%USER_ID%", this.userId)
    };
    c.MiniProfile = a
})(Cogzidel, jQuery);
