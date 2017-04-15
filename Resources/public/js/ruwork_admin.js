'use strict';

(function ($) {
    var $document = $(document)

    var initElementPlugins = function ($context) {
        $('select', $context).select2()
        $('textarea', $context).autosize()
        $('.px-file', $context).pxFile()
        $('[data-provide="datepicker"]', $context).datepicker()
        $('[data-provide="markdown"]', $context).markdown()
        $('[data-ruwidget]', $context).each(function () {
            var $element = $(this)
            $element.frujax($.extend({
                autoload: true,
                onDone: function (jqXHR, textStatus, options, $data) {
                    this.html($data)
                }
            }, $element.data('ruwidget')))
        })
        $('[data-ruwidget-reload]', $context).click(function (event) {
            event.preventDefault()

            $(this).closest('[data-ruwidget]').frujax('fire')
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

    $document
        .ready(function () {
            $('#px-nav', $document).pxNav()
            $('#px-footer', $document).pxFooter()

            initElementPlugins($document)
        })
        .on('done.frujax.global', function (event, $element, jqXHR, textStatus, options, $data) {
            initElementPlugins($data)
        })
})(window.jQuery)
