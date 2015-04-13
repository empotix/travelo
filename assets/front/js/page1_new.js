var Translations = {
    review: "review",
    reviews: "reviews",
    night: "night"
};
var CogzidelHomePage = {
    fields_to_clear_on_submit: [],
    opts: {},
    defaultSearchValue: default_value,
    loadImmediatelyAdded: false,
    onBefore: function (l, e, a) {
        if (!a.addSlide || !loadImmediately || loadImmediately.length === 0) {
            return
        }
        var b = 10;
        var k = loadImmediately.length;
        if (b > k) {
            b = k
        }
        for (var c = 0; c < b; c++) {
            var f = loadImmediately.shift();
            var j = "";
            if (f.reviews && f.reviews > 0) {
                var d = f.reviews == 1 ? Translations.review : Translations.reviews;
                j = ["<span class='ss_review'>", f.reviews, " ", d, "</span>"].join("")
            }
            var g = ["<div class='slideshow_item'>", "<a href='", f.url, "' class='image_link rounded_top_new'>", "<img src='", f.picUrl, "' width='476' height='316' />", "</a>", "<div class='slideshow_item_details rounded_bottom'>", "<img src='", f.userPicUrl, "' width='68' height='68' alt='' />", "<div class='slideshow_item_details_text rounded_more'>", "<div class='ss_details_top'>", "<a class='ss_name' href='", f.url, "'>", f.name, "</a> - <span class='ss_location'>", f.smartLocation, "</span>", "</div>", "<div class='ss_details_bottom'>", "<span class='ss_price'>", f.price, " / ", Translations.night, "</span>", j, "</div>", "</div>", "</div>", "</div>"].join("");
            a.addSlide(g)
        }
    },
    init: function (a) {
        CogzidelHomePage.opts = a || {};
        CogzidelHomePage.initLocationBar();
        CogzidelHomePage.initSubmitListener();
        CogzidelHomePage.initCalendars()
    },
    initLocationBar: function () {
        $.each($(".inner_text"), function (c, d) {
            var a = $(d).next("input");
            var b = a.val();
            a.attr("defaultValue", d.innerHTML);
            a.val(d.innerHTML);
            if (b.length !== 0) {
                a.val(b);
                a.addClass("active")
            }
            a.bind("focus", function () {
                if ($(a).val() == a.attr("defaultValue")) {
                    $(a).val("");
                    global_test_var = $(a);
                    $(a).addClass("active")
                }
                $(a).removeClass("error");
                return true
            });
            a.bind("blur", function () {
                if ($(a).val() === "") {
                    $(a).removeClass("active");
                    $(a).val(a.attr("defaultValue"))
                } else {
                    $(a).removeClass("error")
                }
            });
            CogzidelHomePage.fields_to_clear_on_submit.push(a);
            $(d).remove()
        });
        $("#location_label").show();
        if (CogzidelHomePage.opts.location) {
            $("#location").val(CogzidelHomePage.opts.location).addClass("active")
        }
    },
    initSubmitListener: function () {
        $("#search_form").submit(function () {
            if (CogzidelHomePage.checkInputs() === true) {
                if ($.browser.msie && $.browser.version == "6.0") {
                    window.location = ["/search?location=", $("#location").val(), "&checkin=", $("#checkin").val(), "&checkout=", $("#checkout").val()].join("");
                    return false
                }
                return true
            } else {
                return false
            }
        })
        
    },
    initCalendars: function () {
        var b = $.datepicker._defaults.dateFormat;
        var a = {
            minDate: 0,
            maxDate: "+2Y",
            nextText: "",
            prevText: "",
            numberOfMonths: 1,
            showButtonPanel: true,
            closeText: "Clear Dates"
        };
        var f = jQuery.extend(true, {}, a);
        var h = jQuery.extend(true, {}, a);
        var c = $("#checkin");
        var d = $("#checkout");
        try {
            $.datepicker.parseDate(b, c.val());
            $.datepicker.parseDate(b, d.val())
        } catch (g) {
            c.val(b);
            d.val(b)
        }
        jQuery("#search_form").cogzidelInputDateSpan({
            defaultsCheckin: f,
            defaultsCheckout: h
        })
        jQuery("#search_form1").cogzidelInputDateSpan({
            defaultsCheckin: f,
            defaultsCheckout: h
        })
    },
    locationIsBlank: function () {
        var a = $("#location");
								
								/*if($.trim(CogzidelHomePage.defaultSearchValue) == $.trim(a.val()))
									return true;
								else
									return false;*/
							//alert($.trim(default_value) + $.trim(a.val()));
        return (!a.val() || $.trim(default_value) === $.trim(a.val()))
    },
    checkInputs: function () {
        var a = $("#enter_location_error_message");
        if (CogzidelHomePage.locationIsBlank()) {
            if (a.is(":visible")) {
                a.pulsate(1, 300)
            } else {
                a.show()
            }
            return false
        }
        a.hide();
        return true
    }
};