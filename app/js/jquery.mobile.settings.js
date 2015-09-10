/**
 * Set some default jQuery Mobile settings for the current application.
 */

$(document).bind("mobileinit", function() {
    // Set the default transition
    $.extend($.mobile, {
        defaultPageTransition: 'slide'
    });

    // Set the theme
    //noinspection JSPotentiallyInvalidConstructorUsage
    $.mobile.page.prototype.options.backBtnTheme = 'b';
    //noinspection JSPotentiallyInvalidConstructorUsage
    $.mobile.page.prototype.options.headerTheme = 'b';
    //noinspection JSPotentiallyInvalidConstructorUsage
    $.mobile.page.prototype.options.footerTheme = 'b';
    //noinspection JSPotentiallyInvalidConstructorUsage
    $.mobile.page.prototype.options.contentTheme = 'b';
    //noinspection JSPotentiallyInvalidConstructorUsage
    $.mobile.page.prototype.options.theme = 'b';
    //noinspection JSPotentiallyInvalidConstructorUsage
    $.mobile.listview.prototype.options.filterTheme = 'b';
});