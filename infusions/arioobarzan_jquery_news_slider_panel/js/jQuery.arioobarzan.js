/* ------------------------------------------------------------------------
 * Class: xparoSlider
 * Use: ImageSlider for jQuery
 * Author: Jutta Henninger (http://www.xparo.com)
 * Version: 1.0
 * Copyright (c) 2010 Jutta Henninger / Xparo
 * Dual licensed under the MIT and GPL licenses.
 * http://docs.jquery.com/License
------------------------------------------------------------------------- */

(function ($) {
    $.xparoSlider = {
        defaults: {
            interval: 5600, /* Zeitintervall fuer den Bildwechsel */
            slide: true, /* Slideshow oder keine Slideshow true/false */
            navi: true, /* Navigation anzeigen true/false */
            pages: true, /* Seiten Navigation anzeigen true/false */
            direction: "forward", /* Richtung der slides forward/backward */
            numbers: "image", /* Seitenzahl image/number */
            cssclass: "hometeaser"
        }
    };
    $.fn.extend({
        xparoSlider: function (config) {

            var config = $.extend({}, $.xparoSlider.defaults, config);
            config.imgContainer = this.attr("id");
            config.teaser_interval = "";
            config.teaser_interval = clearTimeout(config.teaser_interval);
            if (this.children().attr('class') == "") {
                this.children().addClass(config.cssclass);
            }
            if (this.children().attr('class').indexOf(" ") > -1) {
                var test = this.children().attr('class').split(" ");
                config.cssclass = test[0];
            } else {
                config.cssclass = this.children().attr('class');
            }
            (config.navi == false) ? withoutNavi(config) : withNavi(config);
            return this;
        }
    });

    function withoutNavi(config) {
        if (config.pages == true) {
            $("<div>").addClass("flashnavi").appendTo("#" + config.imgContainer);
            $("<div>").addClass("main").appendTo("#" + config.imgContainer + " .flashnavi");
            $("<div>").addClass("count").appendTo("#" + config.imgContainer + " .flashnavi .main");
            createPages(config);
        }
        if (config.slide == true) {
            setTime(config, false);
        }
    };

    function withNavi(config, fileNames) {
        $("<div>").addClass("flashnavi").appendTo("#" + config.imgContainer);
        $("<div>").addClass("main").appendTo("#" + config.imgContainer + " .flashnavi");
        $("<div>").addClass("prev").appendTo("#" + config.imgContainer + " .flashnavi .main");
        if (config.slide == false) {
            $("<div>").addClass("timer pause").attr("name", "pause").appendTo("#" + config.imgContainer + " .flashnavi .main");
        } else {
            $("<div>").addClass("timer play").attr("name", "time").appendTo("#" + config.imgContainer + " .flashnavi .main");
        }
        $("<div>").addClass("next").appendTo("#" + config.imgContainer + " .flashnavi .main");

        var settings = config;
        if (config.pages == true) {
            $("<div>").addClass("count").appendTo("#" + config.imgContainer + " .flashnavi .main");
            createPages(settings);
        }
        $("#" + config.imgContainer + " .flashnavi .main .prev").bind('click', function () { clickPreviousSlide(settings); return false; });
        $("#" + config.imgContainer + " .flashnavi .main .timer").bind('click', function () { pauseSlide(settings); return false; });
        $("#" + config.imgContainer + " .flashnavi .main .next").bind('click', function () { clickNextSlide(settings); return false; });

        if (config.slide == true) {
            setTime(config, false);
        }
    };

    /* jQuery Teaser */

    function createPages(config) {
        var elements = jQuery("#" + config.imgContainer + " ." + config.cssclass + "").children().length;
        $("<ul>").appendTo("#" + config.imgContainer + " .count");
        for (var i = 1; i <= elements; i++) {
            var page = i;
            if (config.numbers == "image") {
                if (i == 1) {
                    $("<li>").addClass("page active").appendTo("#" + config.imgContainer + " .count ul");
                } else {
                    $("<li>").addClass("page").appendTo("#" + config.imgContainer + " .count ul");
                }
            } else if (config.numbers == "number") {
                if (i == 1) {
                    $("<li>").addClass("page active").appendTo("#" + config.imgContainer + " .count ul").html(i);
                } else {
                    $("<li>").addClass("page").appendTo("#" + config.imgContainer + " .count ul").html(i)
                }
            }
            addClickHandler(i, config);
        }
    }

    function addClickHandler(item, config) {
        $("#" + config.imgContainer + " .flashnavi .count li:nth-child(" + item + ")").click(function () { toSlide(item, config); return false; ; });
    }

    function clickNextSlide(config) {
        nextSlide(config);
        if (config.slide == true) {
            setTime(config, true);
        }
    }

    function clickPreviousSlide(config) {
        var settings = config
        previousSlide(settings);
        if (config.slide == true) {
            setTime(config, true);
        }
    }

    function toSlide(item, config) {
        if (jQuery("#" + config.imgContainer + " .count li:nth-child(" + item + ")").hasClass('active')) {
            if (jQuery("#" + config.imgContainer + " .timer").attr("name") == "time") {
                setTime(config, true);
            }
        } else {
            jQuery("#" + config.imgContainer + " .count li.active").removeClass("active");
            jQuery("#" + config.imgContainer + " .count li:nth-child(" + item + ")").addClass('active');
            var $show = jQuery("#" + config.imgContainer + " ." + config.cssclass + " div.show");
            if ($show.length == 0) $show = jQuery("#" + config.imgContainer + " ." + config.cssclass + " div:last-child");
            var $next = jQuery("#" + config.imgContainer + " ." + config.cssclass + " div:nth-child(" + item + ")");
            $show.addClass('last-show');
            $next.css({ opacity: 0.0 })
            .addClass('show')
            .animate({ opacity: 1.0 }, 1000, function () {
                $show.removeClass('show last-show');
            });
            if (jQuery("#" + config.imgContainer + " .timer").attr("name") == "time") {
                setTime(config, true);
            }
        }
    }

    function nextSlide(config) {
        var $show = jQuery("#" + config.imgContainer + " ." + config.cssclass + " div.show");
        if ($show.length == 0) $show = jQuery("#" + config.imgContainer + " ." + config.cssclass + " div:last-child");
        var $next = $show.next().length ? $show.next().not(".text")
        : jQuery("#" + config.imgContainer + " ." + config.cssclass + " div:first-child:not(\".text\")");
        $show.addClass('last-show');
        jQuery("#" + config.imgContainer + " .count li.active").removeClass("active");
        jQuery("#" + config.imgContainer + " .count li:nth-child(" + ($next.prevAll().length + 1) + ")").addClass('active');
        $next.css({ opacity: 0.0 })
        .addClass('show')
        .animate({ opacity: 1.0 }, 1000, function () {
            $show.removeClass('show last-show');
        });
    }

    function previousSlide(config) {
        var $show = jQuery("#" + config.imgContainer + " ." + config.cssclass + " div.show");
        if ($show.length == 0) $show = jQuery("#" + config.imgContainer + " ." + config.cssclass + " div:first-child");

        var $next = $show.prev().length ? $show.prev().not(".text")
        : jQuery("#" + config.imgContainer + " ." + config.cssclass + " div:last-child:not(\".text\")");
        $show.addClass('last-show');
        jQuery("#" + config.imgContainer + " .count li.active").removeClass("active");
        jQuery("#" + config.imgContainer + " .count li:nth-child(" + ($next.prevAll().length + 1) + ")").addClass('active');
        $next.css({ opacity: 0.0 })
        .addClass('show')
        .animate({ opacity: 1.0 }, 1000, function () {
            $show.removeClass('show last-show');
        });
    }

    function pauseSlide(config) {
        var settings = config;
        if (config.teaser_interval != 0) {
            jQuery("#" + config.imgContainer + " .timer").attr("name", "pause");
            if ($("#" + config.imgContainer + " .timer").hasClass("play")) {
                $("#" + config.imgContainer + " .timer").addClass("pause");
                $("#" + config.imgContainer + " .timer").removeClass("play");
            }
            clearInterval(config.teaser_interval);
            config.teaser_interval = 0;
        } else {
            jQuery("#" + config.imgContainer + " .timer").attr("name", "time");
            if ($("#" + config.imgContainer + " .timer").hasClass("pause")) {
                $("#" + config.imgContainer + " .timer").addClass("play");
                $("#" + config.imgContainer + " .timer").removeClass("pause");
            }
            setTime(config, true);
        }
    }
    function setTime(config, clear) {

        config.teaser_interval = clearTimeout(config.teaser_interval);
        if (clear == true) {
            config.teaser_interval = clearTimeout(config.teaser_interval);
        }
        if (config.direction == "forward") {
            config.teaser_interval = setTimeout(function () { clickNextSlide(config); }, config.interval);
        } else if (config.direction == "backward") {
            config.teaser_interval = setTimeout(function () { clickPreviousSlide(config); }, config.interval);
        }
    }
    function clearTime() {
        config.teaser_interval = clearTimeout(config.teaser_interval);
    }
})(jQuery);