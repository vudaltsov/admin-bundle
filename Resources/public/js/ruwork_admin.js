'use strict';

(function (document, $) {
    $('#px-nav').pxNav()
    $('#px-footer').pxFooter()

    $.fn.select2.defaults.set('language', app.locale)
    $('select').select2()

    $('.datepicker-control').datepicker({
        format: 'yyyy-mm-dd',
        startView: 0,
        maxViewMode: 2,
        todayBtn: 'linked',
        language: app.locale,
        daysOfWeekHighlighted: '0,6',
        orientation: 'bottom auto',
        autoclose: true,
        todayHighlight: true,
        zIndexOffset: 999999
    })
})(document, window.jQuery)
