(function ($) {
    'use strict'

    function Ruwidget(element, options) {
        this.$element = $(element)
        this.options = options || {}
        this.init()
    }

    Ruwidget.prototype.options = function (options) {
        $.extend(true, this.options, options)
    }

    Ruwidget.prototype.init = function () {
        if (this.options.autoload) {
            this.load()
        }

        this.$element.addClass(this.options.classes.widget)
    }

    Ruwidget.prototype.destroy = function () {
        this.$element.removeClass(this.options.classes.widget)
    }

    Ruwidget.prototype.attachEvents = function () {
        var _this = this

        this.$element.find('form').on('submit', function (event) {
            event.preventDefault()

            _this.ajaxSubmit($(this))
        })
    }

    Ruwidget.prototype.load = function () {
        this.ajax()
    }

    Ruwidget.prototype.reload = function () {
        this.load()
    }

    Ruwidget.prototype.ajaxBeforeSend = function () {
        this.$element
            .addClass(this.options.classes.ajaxPending)
            .height(this.$element.height())
        this.options.onAjaxBeforeSend.apply(null, $.merge([this.$element], arguments))
    }

    Ruwidget.prototype.ajaxAlways = function () {
        this.$element
            .removeClass(this.options.classes.ajaxPending)
        this.options.onAjaxAlways.apply(null, $.merge([this.$element], arguments))

        var _this = this

        setTimeout(function () {
            _this.$element.height('')
        }, 1)
    }

    Ruwidget.prototype.ajaxDone = function (data) {
        this.$element
            .addClass(this.options.classes.ajaxSuccess)
            .html(data)
        this.options.onAjaxDone.apply(null, $.merge([this.$element], arguments))
        this.attachEvents()
    }

    Ruwidget.prototype.ajaxFail = function () {
        this.$element
            .addClass(this.options.classes.ajaxError)
        this.options.onAjaxFail.apply(null, $.merge([this.$element], arguments))
    }

    Ruwidget.prototype.ajax = function () {
        var _this = this

        $
            .ajax({
                url: this.options.url,
                dataType: 'html',
                beforeSend: function () {
                    _this.ajaxBeforeSend.apply(_this, arguments)
                }
            })
            .always(function () {
                _this.ajaxAlways.apply(_this, arguments)
            })
            .done(function () {
                _this.ajaxDone.apply(_this, arguments)
            })
            .fail(function () {
                _this.ajaxFail.apply(_this, arguments)
            })
    }

    Ruwidget.prototype.ajaxSubmit = function ($form) {
        var _this = this

        $form
            .ajaxSubmit({
                url: this.options.url,
                dataType: 'html',
                beforeSubmit: function () {
                    _this.ajaxBeforeSend.apply(_this, arguments)
                }
            })
            .data('jqxhr')
            .always(function () {
                _this.ajaxAlways.apply(_this, arguments)
            })
            .done(function () {
                _this.ajaxDone.apply(_this, arguments)
            })
            .fail(function () {
                _this.ajaxFail.apply(_this, arguments)
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
            ajaxPending: 'ruwidget-ajax-pending',
            ajaxSuccess: 'ruwidget-ajax-success',
            ajaxError: 'ruwidget-ajax-error'
        },
        autoload: true,
        onAjaxBeforeSend: function () {
        },
        onAjaxAlways: function () {
        },
        onAjaxDone: function () {
        },
        onAjaxFail: function () {
        }
    }

    $(document)
        .ready(function () {
            $('[data-provide="ruwidget"]').ruwidget()
        })
})(window.jQuery)
