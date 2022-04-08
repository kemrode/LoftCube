/**
 * Created by Alex on 12.08.2016.
 */
"use strict";
$( document ).ready(function() {
    var $btn_menu = $('.btn-menu-toggle');
    var $btn_menu_close = $('.mobile-menu-close');
    var $menu = $('.mobile-menu');

    var scroll_w = (function() {
        var scrollWidth,
            $div = $('<div>').css({
                overflowY: 'scroll',
                width: '50px',
                height: '50px',
                visibility: 'hidden'
            });

        $('body').append($div);
        scrollWidth = $div.get(0).offsetWidth - $div.get(0).clientWidth;
        $div.remove();

        return scrollWidth;
    })();

        $btn_menu.on('click', function(e) {
        var wind_w = window.innerWidth;

        if(wind_w < 768) {
            if(!$menu.hasClass('open')) {
                $('body').addClass('ovf--hidden').css({ paddingRight: scroll_w });

                $menu.addClass('open');
            } else {
                $('body').removeClass('ovf--hidden').removeAttr('style');

                $menu.removeClass('open');
            }
        }

        e.preventDefault();
        return false;
    });

    $btn_menu_close.on('click', function(e) {
        $('body').removeClass('ovf--hidden').removeAttr('style');

        $menu.removeClass('open');

        e.preventDefault();
        return false;
    });
});
