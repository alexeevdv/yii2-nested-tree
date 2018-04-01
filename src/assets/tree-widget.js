(function ( $ ) {
    $.fn.treeWidget = function (params) {
        this.sortableLists({
            placeholderClass: 'treeWidgetPlaceholderClass',
            currElClass: 'treeWidgetCurrentElementClass',
            hintClass: 'treeWidgetHintClass',
            insertZone: 50,
            insertZonePlus: true,
            onChange: function (el) {
                var id = $(el).data('id');
                var prev = $(el).prev().data('id');
                var next = $(el).next().data('id');
                var parent = $(el).parents('li').data('id');

                $.post(params.orderAction, {
                    id: id,
                    prev: prev,
                    next: next,
                    parent: parent
                });
            },
            opener: {
                active: false
            }
        });

        this.on('mousedown', '.treeWidgetElementButtons', function (event) {
            event.stopPropagation();
        });

        return this;
    };
})(jQuery);
