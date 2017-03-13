(function ($) {
    'use strict'

    function Ruwidget(element, options) {
        this.$element = $(element)
        this.options = options || {}
        this.init()
    }

    Ruwidget.prototype.init = function () {
        if (this.options.autoload) {
            this.load()
        }

        this.$element.addClass(this.options.classes.widget)
    }

    Ruwidget.prototype.attachEvents = function () {
        var _this = this

        this.$element.find('form').on('submit', function (event) {
            event.preventDefault()

            var $form = $(this)

            _this.ajax($form.serialize(), $form.prop('method'))
        })
    }

    Ruwidget.prototype.destroy = function () {
        this.$element.removeClass(this.options.classes.widget)
    }

    Ruwidget.prototype.options = function (options) {
        $.extend(true, this.options, options)
    }

    Ruwidget.prototype.load = function () {
        this.ajax()
    }

    Ruwidget.prototype.reload = function () {
        this.load()
    }

    Ruwidget.prototype.ajax = function (data, method) {
        var _this = this

        $
            .ajax({
                url: this.options.url,
                type: method || 'get',
                dataType: 'html',
                data: data,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    _this.$element
                        .addClass(_this.options.classes.ajaxProcess)
                        .height(_this.$element.height())
                }
            })
            .always(function () {
                _this.$element
                    .removeClass(_this.options.classes.ajaxProcess)
                    .height('')
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

            if (!ruwidget) {
                var options = $.extend(true, {}, $.fn.ruwidget.defaults, $this.data('options'))

                if (typeof method === 'object') {
                    $.extend(true, options, method)
                    method = null
                }

                $this.data('ruwidget', (ruwidget = new Ruwidget(this, options)))
            }

            if (method === 'destroy') {
                ruwidget.destroy()
                $this.data('ruwidget', null)
            } else if (typeof method === 'string' && typeof ruwidget[method] !== 'undefined') {
                ruwidget[method].apply(ruwidget, args)
            } else if (method)
                throw new Error('Wrong Ruwidget method call.')
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
