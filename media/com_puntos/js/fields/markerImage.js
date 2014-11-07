var markerImage = new Class({
    Implements: [Options],
    lazyLoading: false,
    lazyLoadingShadow: false,

    options: {
        select: 'marker-image',
        imageContainer: 'sample-image',
        fieldId: 'jform_params_markerImage_id',
        currentIcon: 'current-icon'
    },

    initialize: function(options) {
        this.setOptions(options);

        document.id(this.options.select).addEvent('change', function() {
            this.editIconForm();
        }.bind(this));

    },

    editIconForm: function() {
        var selected = document.id(this.options.select).get('value');

        if (selected == 0) {
            this._resetElements();
        }

        if (selected == 1) {
            document.id(this.options.imageContainer).setStyle('display','block');
            this.loadImages();
        }
    },

    _resetElements: function() {
        document.id(this.options.fieldId).set('value', '');
        document.id(this.options.currentIcon).set('html', '');
        document.id(this.options.imageContainer).setStyle('display','none');
    },

    loadImages: function() {
        if(this.lazyLoading === false) {
            new LazyLoad({
                range: 50,
                container: this.options.imageContainer
            });
            this.lazyLoading = true;
        }

        var self = this;
        document.id(this.options.imageContainer).getElements('div').addEvent('click', function() {
            self.addicon(this.getElement('span').get('data-id'),this.getElement('img').get('src'), this.getElement('img').get('title'));
        })
    },

    addicon: function(icon, path, title) {
        document.id(this.options.fieldId).value = '/sample/'+icon;

        var categoryIcon = document.id(this.options.currentIcon).getElement('img');
        if(categoryIcon) {
            categoryIcon.set('src', path);
        } else {
            var image = new Element('img', {
                src: path
            });
            document.id(this.options.currentIcon).set('html', '');
            image.inject(document.id(this.options.currentIcon));
        }
    }


});