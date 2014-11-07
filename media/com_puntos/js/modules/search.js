new Class('compojoom.hotspots.modules.search',{
	Implements: [Options, Events],
	options: {
		
	},
	
	initialize: function(options) {
		this.setOptions(options);
//		document.id('search-address').addEvent('submit', function(e) {
//			e.stop();
//			var location = document.id('search-address-input').get('value');
//			window.fireEvent('hotspotsSearchAddress', location);
//		});

		if(this.options.showMenu.toInt()) {
			var element = document.id('quick-search');
			var input = element.getElement('input');
			new OverText(input, {
				wrap:true
			});
			
			element.addEvent('submit', function(e) {
				e.stop();
				var searchWord = element.getElement('input').get('value').trim();
				console.log(searchWord);
				input.blur();
				window.fireEvent('route', ['search', searchWord]);
			});
		}
	}
});