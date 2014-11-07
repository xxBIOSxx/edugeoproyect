new Class('compojoom.hotspots.modules.categories', {
	lazyLoading: false,
	
	initialize: function() {
		
		document.id('select-icon').addEvent('change', function() {
			this.editIconForm();
		}.bind(this));
	},
	
	editIconForm: function() {
		var selected = document.id('select-icon').get('value');

		if (selected == "new") {
			this._resetElements();
			document.id('iconupload').setStyle('display','block');
		}

		if (selected == "delete"){
			this._resetElements();
			document.id('deleteicon').value = '1';
			document.id('deleteicon-text').setStyle('display','block');
		}

		if (selected == "sample") {
			this._resetElements();
			
			document.id('select-sample-image').setStyle('display','block');
			
			this.loadImages();
		}
	},
	
	_resetElements: function() {
		document.id('deleteicon-text').setStyle('display','none');
		document.id('select-sample-image').setStyle('display','none');
		document.id('iconupload').setStyle('display','none');
		document.id('deleteicon').value = '0';
	},

	loadImages: function() {
		if(this.lazyLoading === false) {
			new LazyLoad({
				range: 50, 
				container: 'select-sample-image'
			});
			this.lazyLoading = true;
		}
		
		var self = this;
		document.id('select-sample-image').getElements('div').addEvent('click', function() {
			self.addicon(this.getElement('span').get('data-id'),this.getElement('img').get('src'), this.getElement('img').get('title'));
		})
	},
	

	
	addicon: function(icon, path, title) {
		document.id('wsampleicon').value = '/sample/'+icon;
		
		alert('Sample Icon ' + title + ' selected');
		
		var categoryIcon = document.id('category-icon').getElement('img');
		if(categoryIcon) {
			categoryIcon.set('src', path);
		} else {
			var image = new Element('img', {
				src: path
			});
			document.id('category-icon').set('html', '');
			image.inject(document.id('category-icon'));
		}
		document.id('category-icon').removeClass('validation-failed');
	},

});

