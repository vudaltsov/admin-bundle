'use strict';

(function ($) {
    var $document = $(document)

    var initElementPlugins = function ($context) {
        $('select', $context).select2()
        $('textarea', $context).autosize()
        $('.px-file', $context).pxFile()
        $('[data-provide="datepicker"]', $context).datepicker()
        $('[data-provide="markdown"]', $context).markdown()
        $('[data-provide="ruwidget"]', $context).ruwidget()
        $('[data-ruwidget="reload"]', $context).click(function (event) {
            event.preventDefault()

            $(this).closest('[data-provide="ruwidget"]').ruwidget('reload')
        })
    }

    $.extend($.fn.datepicker.defaults, {
        language: app.locale,
        orientation: 'top auto',
        todayBtn: 'linked',
        autoclose: true,
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

    $.extend($.fn.ruwidget.defaults, {
        onAjaxDone: function ($widget) {
            initElementPlugins($widget)
        }
    })

    $document.ready(function () {
        $('#px-nav', $document).pxNav()
        $('#px-footer', $document).pxFooter()
        initElementPlugins($document)
    })
})(window.jQuery)
