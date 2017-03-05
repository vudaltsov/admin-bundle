'use strict';

(function (document, $) {
    $.extend($.fn.datepicker.defaults, {
        language: app.locale,
        orientation: 'top auto',
        todayBtn: 'linked',
        autoclose: true,
        zIndexOffset: 999999,
        maxViewMode: 2,
        todayHighlight: true
    })

    $.extend($.fn.markdown.defaults, {
        iconlibrary: 'fa',
        hiddenButtons: ['cmdImage'],
        language: app.locale
    })

    $.extend($.fn.select2.defaults, {
        language: app.locale
    })

    $('#px-nav').pxNav()
    $('#px-footer').pxFooter()

    $('select').select2()
    $('textarea').autosize()
})(document, window.jQuery)
