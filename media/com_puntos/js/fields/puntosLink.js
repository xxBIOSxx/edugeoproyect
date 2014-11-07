var puntosLink = new Class({
    Implements: [Options],

    options: {
        linkToId: 'jform_params_link_to_id',
        fieldId: 'jform_params_link_to'
    },

    initialize: function(options) {
        this.setOptions(options);

        document.id(this.options.fieldId).addEvent('change', function() {
            this.watch();
        }.bind(this));

    },

    watch: function() {
        var selected = document.id(this.options.fieldId).get('value');
        if (selected == 0) {
            document.id(this.options.linkToId).getParent('div').setStyle('display', 'none');
        } else {
            document.id(this.options.linkToId).getParent('div').setStyle('display','block');
        }
    }
});