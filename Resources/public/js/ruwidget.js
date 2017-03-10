(function ($) {
    'use strict'

    function Ruwidget(element, options) {
        this.$element = $(element)
        this.options = options
        this.init()
    }

    Ruwidget.prototype.init = function () {
        if (this.options.autoload) {
            this.ajax()
        }

        this.$element.addClass(this.options.classes.widget)
    }

    Ruwidget.prototype.attachEvents = function () {
        var _this = this

        this.$element.find('form').on('submit', function (event) {
            event.preventDefault()

            var $form = $(this)

            var method = $form.prop('method'),
                data = (method === 'post' && typeof window.FormData !== 'undefined')
                    ? new FormData($form[0])
                    : $form.serialize()

            _this.ajax(data, method)
        })
    }

    Ruwidget.prototype.destroy = function () {
        this.$element.removeClass(this.options.classes.widget)
    }

    Ruwidget.prototype.options = function (options) {
        $.extend(true, this.options, options)
    }

    Ruwidget.prototype.ajax = function (data, method) {
        var _this = this

        $
            .ajax({
                url: this.options.url,
                type: method || 'get',
                cache: false,
                dataType: 'html',
                data: data,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    _this.$element
                        .height(_this.$element.height())
                        .addClass(_this.options.classes.ajaxProcess)
                }
            })
            .always(function () {
                _this.$element
                    .height('')
                    .removeClass(_this.options.classes.ajaxProcess)
            })
            .done(function (data) {
                _this.$element.html(data)
                _this.options.onAjaxSuccess(_this.$element)
                _this.attachEvents()
            })
    }

    $.fn.ruwidget = function () {
        var method, args = []

        method = arguments[0]
        args = Array.prototype.slice.call(arguments, 1)

        return this.each(function () {
            var $this = $(this),
                ruwidget = $this.data('ruwidget')

            if (typeof method === 'undefined' || typeof method === 'object') {
                if (!ruwidget) {
                    var options = $.extend(true, {}, $.fn.ruwidget.defaults, $this.data('options'), method)
                    $this.data('ruwidget', new Ruwidget(this, options))
                } else
                    throw new Error('Ruwidget is already initialized on this element.')
            } else {
                if (!ruwidget)
                    throw new Error('Ruwidget is not initialized on this element.')
                else if (method === 'destroy') {
                    ruwidget.destroy()
                    $this.data('ruwidget', null)
                }
                else if (typeof method === 'string' && typeof ruwidget[method] !== 'undefined') {
                    ruwidget[method].apply(ruwidget, args)
                } else
                    throw new Error('Wrong Ruwidget method call.')
            }
        })
    }

    $.fn.ruwidget.defaults = {
        classes: {
            widget: 'ruwidget',
            ajaxProcess: 'ruwidget-ajax-process',
            ajaxSuccess: 'ruwidget-ajax-success',
            ajaxFail: 'ruwidget-ajax-fail'
        },
        autoload: true,
        onAjaxSuccess: function () {
        }
    }

    $(document)
        .ready(function () {
            $('[data-provide="ruwidget"]').ruwidget()
        })
})(window.jQuery)
