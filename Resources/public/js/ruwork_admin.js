'use strict';

(function (document, $) {
    /**
     * on Document ready
     */
    $(function () {
        $('#px-nav').pxNav()
        $(document).trigger('load.content', [$(document)])
    })

    /**
     * on Ajax success
     */
    $(document).ajaxSuccess(function () {
        $(document).trigger('load.content', [$(document)])
    })

    /**
     * on Content load
     */
    $(document).on('load.content', function (event, $context) {
        /**
         * Auto closing elements
         */
        $context.find('[data-close-after]').each(function (i, el) {
            var $el = $(el)

            setTimeout(function () {
                $el.slideUp('slow', function () {
                    $el.remove()
                })
            }, $el.data('close-after'))
        })

        /**
         * Form File
         */
        $context.find('.custom-file').pxFile()

        /**
         * Form Markdown
         */
        $context.find('.form-control-markdown').markdown({
            iconlibrary: 'fa',
            hiddenButtons: ['cmdImage'],
            language: 'ru'
        })

        /**
         * Form Select
         */
        $context.find('select').select2()

        /**
         * Form Date Range
         */
        $context.find('.input-daterange, .form-control-date').datepicker({
            format: 'dd.mm.yyyy',
            startView: 0,
            maxViewMode: 2,
            todayBtn: 'linked',
            language: 'ru',
            daysOfWeekHighlighted: '0,6',
            orientation: 'bottom auto',
            autoclose: true,
            todayHighlight: true
        })

        /**
         * Form Range
         */
        $context.find('.form-control-range').ionRangeSlider()

        /**
         * Form MinMax
         */
        $context.find('.form-control-min-max').each(function (i, el) {
            var $el = $(el),
                $minInput = $($el.data('min-input')),
                $maxInput = $($el.data('max-input'))

            $el.ionRangeSlider({
                onStart: function (data) {
                    setTimeout(function () {
                        $el.trigger('started.form.minmax', [data])
                    }, 0)
                },
                onChange: function (data) {
                    $el.trigger('changed.form.minmax', [data])
                },
                onFinish: function (data) {
                    $minInput.val(data.from)
                    $maxInput.val(data.to)
                    $el.trigger('finished.form.minmax', [data])
                }
            })
        })

        /**
         * Form Collection
         */
        $context.find('.form-collection-add').click(function (e) {
            e.preventDefault()

            var $this = $(this),
                $collection = $($this.data('collection')),
                increment = $collection.data('increment'),
                prototypeName = $this.data('prototype-name'),
                prototype = $this.data('prototype'),
                re = new RegExp(prototypeName, 'g'),
                $content = $(prototype.replace(re, increment))

            $collection.append($content)
            $(document).trigger('load.content', [$content])
            $collection.data('increment', increment + 1)
            $collection.trigger('changed.form.collection')
        })

        $context.find('.form-collection-remove').click(function (e) {
            e.preventDefault()

            var $this = $(this),
                $collection = $($this.data('collection')),
                increment = $collection.data('increment'),
                $target = $this.parentsUntil($collection).last()

            $target.remove()
            $collection.trigger('changed.form.collection')
        })
    })
})(document, window.jQuery)
