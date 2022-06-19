(function($) {
    "use strict";

    // ______________ Page Loading
    $(window).on("load", function(e) {
        $("#global-loader").fadeOut("slow");
    })

    $('.fc-month-button').addClass('fc-state-active');
    $('.fc-agendaWeek-button').removeClass('fc-state-active');
    // ______________Cover Image
    $(".cover-image").each(function() {
        var attr = $(this).attr('data-image-src');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).css('background', 'url(' + attr + ') center center');
        }
    });

    $('.table-subheader').click(function() {
        $(this).nextUntil('tr.table-subheader').slideToggle(100);
    });

    // ______________ Horizonatl
    $(document).ready(function() {
        $("a[data-theme]").click(function() {
            $("head link#theme").attr("href", $(this).data("theme"));
            $(this).toggleClass('active').siblings().removeClass('active');
        });
        $("a[data-bs-effect]").click(function() {
            $("head link#effect").attr("href", $(this).data("effect"));
            $(this).toggleClass('active').siblings().removeClass('active');
        });
    });


    // ______________Full screen
    $("#fullscreen-button").on("click", function toggleFullScreen() {
        if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
            if (document.documentElement.requestFullScreen) {
                document.documentElement.requestFullScreen();
            } else if (document.documentElement.mozRequestFullScreen) {
                document.documentElement.mozRequestFullScreen();
            } else if (document.documentElement.webkitRequestFullScreen) {
                document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (document.documentElement.msRequestFullscreen) {
                document.documentElement.msRequestFullscreen();
            }
        } else {
            if (document.cancelFullScreen) {
                document.cancelFullScreen();
            } else if (document.mozCancelFullScreen) {
                document.mozCancelFullScreen();
            } else if (document.webkitCancelFullScreen) {
                document.webkitCancelFullScreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }
        }
    })

    // ______________Active Class
    $(document).ready(function() {
        $(".horizontalMenu-list li a").each(function() {
            var pageUrl = window.location.href.split(/[?#]/)[0];
            if (this.href == pageUrl) {
                $(this).addClass("active");
                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().prev().addClass("active"); // add active class to an anchor
                $(this).parent().parent().prev().click(); // click the item to make it drop
            }
        });
        $(".horizontal-megamenu li a").each(function() {
            var pageUrl = window.location.href.split(/[?#]/)[0];
            if (this.href == pageUrl) {
                $(this).addClass("active");
                $(this).parent().addClass("active");
                $(this).parent().parent().parent().parent().parent().parent().parent().prev().addClass("active");
                $(this).parent().parent().prev().click(); // click the item to make it drop
            }
        });
        $(".horizontalMenu-list .sub-menu .sub-menu li a").each(function() {
            var pageUrl = window.location.href.split(/[?#]/)[0];
            if (this.href == pageUrl) {
                $(this).addClass("active");
                $(this).parent().addClass("active"); // add active to li of the current link
                $(this).parent().parent().parent().parent().prev().addClass("active"); // add active class to an anchor
                $(this).parent().parent().prev().click(); // click the item to make it drop
            }
        });
    });


    // __________MODAL
    // showing modal with effect
    $('.modal-effect').on('click', function(e) {
        e.preventDefault();
        var effect = $(this).attr('data-bs-effect');
        $('#modaldemo8').addClass(effect);
    });
    // hide modal with effect
    $('#modaldemo8').on('hidden.bs.modal', function(e) {
        $(this).removeClass(function(index, className) {
            return (className.match(/(^|\s)effect-\S+/g) || []).join(' ');
        });
    });

    // ______________Back to top Button
    $(window).on("scroll", function(e) {
        if ($(this).scrollTop() > 0) {
            $('body').addClass('side-shadow');
            $('#back-to-top').fadeIn('slow');
        } else {
            $('body').removeClass('side-shadow');
            $('#back-to-top').fadeOut('slow');
        }
    });
    $("#back-to-top").on("click", function(e) {
        $("html, body").animate({
            scrollTop: 0
        }, 0);
        return false;
    });

    // ______________ StarRating
    var ratingOptions = {
        selectors: {
            starsSelector: '.rating-stars',
            starSelector: '.rating-star',
            starActiveClass: 'is--active',
            starHoverClass: 'is--hover',
            starNoHoverClass: 'is--no-hover',
            targetFormElementSelector: '.rating-value'
        }
    };
    $(".rating-stars").ratingStars(ratingOptions);

    // ______________ Chart-circle
    if ($('.chart-circle').length) {
        $('.chart-circle').each(function() {
            let $this = $(this);

            $this.circleProgress({
                fill: {
                    color: $this.attr('data-color')
                },
                size: $this.height(),
                startAngle: -Math.PI / 4 * 2,
                emptyFill: '#e5e9f2',
                lineCap: 'round'
            });
        });
    }
    // ______________ Chart-circle
    if ($('.chart-circle-primary').length) {
        $('.chart-circle-primary').each(function() {
            let $this = $(this);

            $this.circleProgress({
                fill: {
                    color: $this.attr('data-color')
                },
                size: $this.height(),
                startAngle: -Math.PI / 4 * 2,
                emptyFill: 'rgba(68, 84, 195, 0.4)',
                lineCap: 'round'
            });
        });
    }

    // ______________ Chart-circle
    if ($('.chart-circle-secondary').length) {
        $('.chart-circle-secondary').each(function() {
            let $this = $(this);

            $this.circleProgress({
                fill: {
                    color: $this.attr('data-color')
                },
                size: $this.height(),
                startAngle: -Math.PI / 4 * 2,
                emptyFill: 'rgba(247, 45, 102, 0.4)',
                lineCap: 'round'
            });
        });
    }

    // ______________ Chart-circle
    if ($('.chart-circle-success').length) {
        $('.chart-circle-success').each(function() {
            let $this = $(this);

            $this.circleProgress({
                fill: {
                    color: $this.attr('data-color')
                },
                size: $this.height(),
                startAngle: -Math.PI / 4 * 2,
                emptyFill: 'rgba(45, 206, 137, 0.5)',
                lineCap: 'round'
            });
        });
    }

    // ______________ Chart-circle
    if ($('.chart-circle-warning').length) {
        $('.chart-circle-warning').each(function() {
            let $this = $(this);

            $this.circleProgress({
                fill: {
                    color: $this.attr('data-color')
                },
                size: $this.height(),
                startAngle: -Math.PI / 4 * 2,
                emptyFill: '#e5e9f2',
                lineCap: 'round'
            });
        });
    }

    // ______________ Global Search
    $(document).on("click", "[data-bs-toggle='search']", function(e) {
        var body = $("body");

        if (body.hasClass('search-gone')) {
            body.addClass('search-gone');
            body.removeClass('search-show');
        } else {
            body.removeClass('search-gone');
            body.addClass('search-show');
        }
    });
    var toggleSidebar = function() {
        var w = $(window);
        if (w.outerWidth() <= 1024) {
            $("body").addClass("sidebar-gone");
            $(document).off("click", "body").on("click", "body", function(e) {
                if ($(e.target).hasClass('sidebar-show') || $(e.target).hasClass('search-show')) {
                    $("body").removeClass("sidebar-show");
                    $("body").addClass("sidebar-gone");
                    $("body").removeClass("search-show");
                }
            });
        } else {
            $("body").removeClass("sidebar-gone");
        }
    }
    toggleSidebar();
    $(window).resize(toggleSidebar);

    const DIV_CARD = 'div.card';
    // ______________ Tooltip
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // ______________ Popover
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
        html: true
    })

    // ______________ Card Remove
    $(document).on('click', '[data-bs-toggle="card-remove"]', function(e) {
        let $card = $(this).closest(DIV_CARD);
        $card.remove();
        e.preventDefault();
        return false;
    });

    // ______________ Card Collapse
    $(document).on('click', '[data-bs-toggle="card-collapse"]', function(e) {
        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-collapsed');
        e.preventDefault();
        return false;
    });

    // ______________ Card Fullscreen
    $(document).on('click', '[data-bs-toggle="card-fullscreen"]', function(e) {
        let $card = $(this).closest(DIV_CARD);
        $card.toggleClass('card-fullscreen').removeClass('card-collapsed');
        e.preventDefault();
        return false;
    });

    // sparkline1
    $(".sparkline_bar").sparkline([2, 4, 3, 4, 5, 4, 5, 4, 3, 4], {
        height: 20,
        type: 'bar',
        colorMap: {
            '7': '#a1a1a1'
        },
        barColor: '#ff5b51'
    });

    // sparkline2
    $(".sparkline_bar1").sparkline([3, 4, 3, 4, 5, 4, 5, 6, 4, 6, ], {
        height: 20,
        type: 'bar',
        colorMap: {
            '7': '#c34444'
        },
        barColor: '#44c386'
    });

    // sparkline3
    $(".sparkline_bar2").sparkline([3, 4, 3, 4, 5, 4, 5, 6, 4, 6, ], {
        height: 20,
        type: 'bar',
        colorMap: {
            '7': '#a1a1a1'
        },
        barColor: '#4454c3'
    });

    // ______________ SWITCHER-toggle ______________//
    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch10', function() {
        if (this.checked) {
            $('body').addClass('default-sidebar');
            $('body').removeClass('color-sidebar');
            $('body').removeClass('dark-sidebar');
            localStorage.setItem("default-sidebar", "True");
        } else {
            $('body').removeClass('default-sidebar');
            localStorage.setItem("default-sidebar", "false");
        }
    });
    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch12', function() {
        if (this.checked) {
            $('body').addClass('dark-sidebar');
            $('body').removeClass('color-sidebar');
            $('body').removeClass('default-sidebar');
            localStorage.setItem("dark-sidebar", "True");
        } else {
            $('body').removeClass('dark-sidebar');
            localStorage.setItem("dark-sidebar", "false");
        }
    });
    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch11', function() {
        if (this.checked) {
            $('body').addClass('color-sidebar');
            $('body').removeClass('default-sidebar');
            $('body').removeClass('dark-sidebar');
            localStorage.setItem("color-sidebar", "True");
        } else {
            $('body').removeClass('color-sidebar');
            localStorage.setItem("color-sidebar", "false");
        }
    });

    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch3', function() {
        if (this.checked) {
            $('body').addClass('card-radius');
            localStorage.setItem("card-radius", "True");
        } else {
            $('body').removeClass('card-radius');
            localStorage.setItem("card-radius", "false");
        }
    });

    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch4', function() {
        if (this.checked) {
            $('body').addClass('card-shadow');
            localStorage.setItem("card-shadow", "True");
        } else {
            $('body').removeClass('card-shadow');
            localStorage.setItem("card-shadow", "false");
        }
    });

    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch5', function() {
        if (this.checked) {
            $('body').addClass('default-body');
            $('body').removeClass('light-dark-body');
            $('body').removeClass('white-body');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("default-body", "True");
        } else {
            $('body').removeClass('default-body');
            localStorage.setItem("default-body", "false");
        }
    });

    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch6', function() {
        if (this.checked) {
            $('body').addClass('white-body');
            $('body').removeClass('default-body');
            $('body').removeClass('light-dark-body');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("white-body", "True");
        } else {
            $('body').removeClass('white-body');
            localStorage.setItem("white-body", "false");
        }
    });

    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch7', function() {
        if (this.checked) {
            $('body').addClass('light-dark-body');
            $('body').removeClass('default-body');
            $('body').removeClass('white-body');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("light-dark-body", "True");
        } else {
            $('body').removeClass('light-dark-body');
            localStorage.setItem("light-dark-body", "false");
        }
    });

    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch8', function() {
        if (this.checked) {
            $('body').addClass('light-mode');
            $('body').removeClass('dark-mode');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("light-mode", "True");
        } else {
            $('body').removeClass('light-mode');
            localStorage.setItem("light-mode", "false");
        }
    });
    $(document).on("click", '#myonoffswitch9', function() {
        if (this.checked) {
            $('body').addClass('dark-mode');
            $('body').removeClass('light-mode');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("dark-mode", "True");
        } else {
            $('body').removeClass('dark-mode');
            localStorage.setItem("dark-mode", "false");
        }
    });

    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch13', function() {
        if (this.checked) {
            $('body').addClass('default-horizontal');
            $('body').removeClass('color-horizontal');
            $('body').removeClass('dark-horizontal');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("default-horizontal", "True");
        } else {
            $('body').removeClass('default-horizontal');
            localStorage.setItem("default-horizontal", "false");
        }
    });
    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch14', function() {
        if (this.checked) {
            $('body').addClass('dark-horizontal');
            $('body').removeClass('color-horizontal');
            $('body').removeClass('default-horizontal');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("dark-horizontal", "True");
        } else {
            $('body').removeClass('dark-horizontal');
            localStorage.setItem("dark-horizontal", "false");
        }
    });
    /*Theme Layouts*/
    $(document).on("click", '#myonoffswitch15', function() {
        if (this.checked) {
            $('body').addClass('color-horizontal');
            $('body').removeClass('default-horizontal');
            $('body').removeClass('dark-horizontal');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("color-horizontal", "True");
        } else {
            $('body').removeClass('color-horizontal');
            localStorage.setItem("color-horizontal", "false");
        }
    });

    /*Left-menu-style1*/
    $(document).on("click", '#myonoffswitch16', function() {
        if (this.checked) {
            $('body').addClass('default-leftmenu');
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("default-leftmenu", "True");
        } else {
            $('body').removeClass('default-leftmenu');
            localStorage.setItem("default-leftmenu", "false");
        }
    });
    $(document).on("click", '#myonoffswitch17', function() {
        if (this.checked) {
            $('body').addClass('style1-leftmenu');
            $('body').removeClass('default-leftmenu');
            localStorage.setItem("default-leftmenu", "True");
        } else {
            $('body').removeClass('style1-leftmenu');
            localStorage.setItem("style1-leftmenu", "false");
        }
    });

    /*-- LTR Horizontal Versions --*/
    $('#myonoffswitch20').click(function() {
        if (this.checked) {
            $('body').addClass('default-horizontal');
            $('body').removeClass('centerlogo-horizontal');
            localStorage.setItem("default-horizontal", "True");
        } else {
            $('body').removeClass('default-horizontal');
            localStorage.setItem("default-horizontal", "false");
        }
    });
    $('#myonoffswitch21').click(function() {
        if (this.checked) {
            $('body').addClass('centerlogo-horizontal');
            $('body').removeClass('default-horizontal');
            localStorage.setItem("centerlogo-horizontal", "True");
        } else {
            $('body').removeClass('centerlogo-horizontal');
            localStorage.setItem("centerlogo-horizontal", "false");
        }
    });

    /*-- width styles ---*/
    $('#myonoffswitch18').click(function() {
        if (this.checked) {
            $('body').addClass('default');
            $('body').removeClass('boxed');
            localStorage.setItem("default", "True");
        } else {
            $('body').removeClass('default');
            localStorage.setItem("default", "false");
        }
    });
    $('#myonoffswitch19').click(function() {
        if (this.checked) {
            $('body').addClass('boxed');
            $('body').removeClass('default');
            localStorage.setItem("boxed", "True");
        } else {
            $('body').removeClass('boxed');
            localStorage.setItem("boxed", "false");
        }
    });

    /*LTR Left-menu-versions*/


    $('#myonoffswitch22').click(function() {
        if (this.checked) {
            $('body').addClass('default-leftmenu');
            // $('body').addClass('default-sidebar');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("default-leftmenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css/sidemenu.css"))
        } else {
            $('body').removeClass('default-leftmenu');
            localStorage.setItem("default-leftmenu", "false");
        }
    });
    $('#myonoffswitch23').click(function() {
        if (this.checked) {
            $('body').addClass('closed-leftmenu');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-sidebar');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("closed-leftmenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css/closed-sidemenu.css"))
        } else {
            $('body').removeClass('closed-leftmenu');
            localStorage.setItem("closed-leftmenu", "false");
            (document.getElementById("theme").removeAttribute("href", "../../assets/css/closed-sidemenu.css"))
        }
    });
    $('#myonoffswitch24').click(function() {
        if (this.checked) {
            $('body').addClass('hover-submenu');
            $('body').addClass('sidenav-toggled');
            // $('body').addClass('default-sidebar');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("hover-submenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css/sidemenu3.css"))
            $("link#sidemenu").attr("src", $(this));
            (document.getElementById("sidemenu").setAttribute("src", "../../assets/plugins/sidemenu/sidemenu1.js"))
        } else {
            $('body').removeClass('hover-submenu');
            localStorage.setItem("hover-submenu", "false");
        }
    });
    $('#myonoffswitch30').click(function() {
        if (this.checked) {
            $('body').addClass('hover-submenu1');
            $('body').addClass('sidenav-toggled');
            // $('body').addClass('default-sidebar');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("hover-submenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css/sidemenu4.css"))
            $("link#sidemenu").attr("src", $(this));
            (document.getElementById("sidemenu").setAttribute("src", "../../assets/plugins/sidemenu/sidemenu1.js"))
        } else {
            $('body').removeClass('hover-submenu1');
            localStorage.setItem("hover-submenu1", "false");
        }
    });

    $('#myonoffswitch25').click(function() {
        if (this.checked) {
            $('body').addClass('icon-overlay');
            // $('body').addClass('default-sidebar');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-text');
            localStorage.setItem("icon-overlay", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css/sidemenu.css"))
            $("link#sidemenu").attr("src", $(this));
            (document.getElementById("sidemenu").setAttribute("src", "../../assets/plugins/sidemenu/sidemenu1.js"))
        } else {
            $('body').removeClass('icon-overlay');
            localStorage.setItem("icon-overlay", "false");
            (document.getElementById("theme").removeAttribute("href", "../../assets/css/sidemenu.css"))
        }
    });

    /*RTL Left-menu-versions*/


    $('#myonoffswitch26').click(function() {
        if (this.checked) {
            $('body').addClass('default-leftmenu');
            // $('body').addClass('default-sidebar');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("default-leftmenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css-rtl/sidemenu.css"))
        } else {
            $('body').removeClass('default-leftmenu');
            localStorage.setItem("default-leftmenu", "false");
        }
    });
    $('#myonoffswitch27').click(function() {
        if (this.checked) {
            $('body').addClass('closed-leftmenu');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-sidebar');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("closed-leftmenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css-rtl/closed-sidemenu.css"))
        } else {
            $('body').removeClass('closed-leftmenu');
            localStorage.setItem("closed-leftmenu", "false");
            (document.getElementById("theme").removeAttribute("href", "../../assets/css-rtl/closed-sidemenu.css"))
        }
    });
    $('#myonoffswitch28').click(function() {
        if (this.checked) {
            $('body').addClass('hover-submenu');
            $('body').addClass('sidenav-toggled');
            // $('body').addClass('default-sidebar');
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("hover-submenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css-rtl/sidemenu3.css"))
            $("link#sidemenu").attr("src", $(this));
            (document.getElementById("sidemenu").setAttribute("src", "../../assets/plugins/sidemenu/sidemenu1.js"))
        } else {
            $('body').removeClass('hover-submenu');
            localStorage.setItem("hover-submenu", "false");
            (document.getElementById("theme").removeAttribute("href", "../../assets/css-rtl/sidemenu3.css"))
        }
    });

    $('#myonoffswitch29').click(function() {
        if (this.checked) {
            $('body').addClass('icon-overlay');
            // $('body').addClass('default-sidebar')
            $('body').removeClass('hover-submenu1');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('icon-text');
            localStorage.setItem("icon-overlay", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css-rtl/sidemenu.css"))
            $("link#sidemenu").attr("src", $(this));
            (document.getElementById("sidemenu").setAttribute("src", "../../assets/plugins/sidemenu/sidemenu1.js"))
        } else {
            $('body').removeClass('icon-overlay');
            localStorage.setItem("icon-overlay", "false");
            (document.getElementById("theme").removeAttribute("href", "../../assets/css-rtl/sidemenu.css"))
        }
    });
    $('#myonoffswitch31').click(function() {
        if (this.checked) {
            $('body').addClass('hover-submenu1');
            $('body').addClass('sidenav-toggled');
            // $('body').addClass('default-sidebar');
            $('body').removeClass('hover-submenu');
            $('body').removeClass('default-leftmenu');
            $('body').removeClass('closed-leftmenu');
            $('body').removeClass('icon-overlay');
            $('body').removeClass('icon-text');
            localStorage.setItem("hover-submenu", "True");
            $("head link#theme").attr("href", $(this));
            (document.getElementById("theme").setAttribute("href", "../../assets/css-rtl/sidemenu4.css"))
            $("link#sidemenu").attr("src", $(this));
            (document.getElementById("sidemenu").setAttribute("src", "../../assets/plugins/sidemenu/sidemenu1.js"))
        } else {
            $('body').removeClass('hover-submenu1');
            localStorage.setItem("hover-submenu1", "false");
            (document.getElementById("theme").removeAttribute("href", "../../assets/css-rtl/sidemenu4.css"))
        }
    });

    // ______________ SWITCHER-toggle ______________//

    //$('body').addClass('default-sidebar');//

    //$('body').addClass('dark-sidebar');//

    //$('body').addClass('color-sidebar');//

    //$('body').addClass('card-radius');//

    //$('body').addClass('card-shadow');//

    //$('body').addClass('default-body');//

    //$('body').addClass('light-dark-body');//

    //$('body').addClass('white-body');//

    //$('body').addClass('light-mode');//

    //$('body').addClass('dark-mode');//

    //$('body').addClass('default-horizontal');//

    //$('body').addClass('color-horizontal');//

    //$('body').addClass('dark-horizontal');//

})(jQuery);