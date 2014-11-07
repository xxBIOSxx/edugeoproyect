new Class('edugeo.puntos.modules.latestpuntos', {
	Extends: edugeo.puntos.modules.punto,
	Implements: [Options, Events, edugeo.puntos.helper],

	isMenuOpen: false,
	fullScreen: false,
	options: {
		mailMap: 1
	},
	xhr: {},


	initialize: function (puntos, options, sb) {
		this.parent(options, sb);
		var self = this;
		window.addEvent('puntosDispatch:once', function () {
			window.fireEvent('puntosLoadedpuntos', puntos);
			self.sb.zoomToMarkers();
		})

	},

	infoWindowToolbarActions: function (punto) {
		var self = this, readmore = '', container = '', admin = '';

		var links = new Element('div', {
			id: 'puntos-links'
		});

		readmore = new Element('span', {
			'class': 'link'
		}).adopt(new Element('a', {
				href: punto.readmore,
				html: this.translate('COM_puntoS_READ_MORE', 'Read more'),
				target: '_blank'
			}));

		admin = new Element('span', {
			'class': 'link'
		}).adopt(new Element('a', {
				href: this.options.baseUrl + 'administrator/index.php?option=com_puntos&task=punto.edit&id=' + punto.id,
				html: this.translate('JTOOLBAR_EDIT', 'Edit'),
				target: '_blank'
			}));


		container = new Element('div', {
			id: 'puntos-container'

		}).adopt([
				links.adopt([readmore, admin])
			]);

		return container;
	},

	createInfoWindow: function (marker, punto) {
		return (function () {
			var container = this.infoWindowToolbarActions(punto),
				self = this;
			new Request.JSON({
				url: self.options.rootUrl + 'index.php?option=com_puntos&view=punto&id=' + punto.id + '&format=raw',
				onRequest: function () {
					var container = new Element('div', {
						style: 'position: absolute; top: 50%',
						html: self.translate('COM_puntoS_LOADING_DATA', 'Loading data')
					});

					self.infoWindow.setOptions({
						'content': container,
						'position': new google.maps.LatLng(punto.latitude, punto.longitude)
					});
					self.infoWindow.open(self.sb.getMap(), marker.markerObj);
				},
				onSuccess: function (json) {
					var text = new Element('div', {
						style: 'width: 300px; height: 140px; overflow-y: auto;',
						html: json.description
					});
					text.adopt(self.infoWindowToolbarActions(json));
					self.infoWindow.setContent(text);
					self.infoWindow.open(self.getCurrentMapObj(), marker.markerObj);
					google.maps.event.trigger(self.infoWindow.infoWindowObj, 'content_changed');

				},
				onFailure: function (xhr) {
					var content = new Element('div', {
						html: this.translate('COM_puntoS_SOMETHING_IS_WRONG', 'Something is wrong')
							+ '<br /> Server response: ' + xhr.status + ' "' + xhr.statusText + '"'
							+ '<br /> If problem persist contact administrator'
					});
					self.infoWindow.setContent(content);
					google.maps.event.trigger(self.infoWindow, 'content_changed');
				}
			}).send();
		}.bind(this));
	},

	onpuntosLoadedpuntos: function (locations) {

		var puntos = locations.puntos, self = this;

		// make sure that our object that contains the markers dosn't get out of hand...
		if (Object.keys(this.sb.markers).length > 2000) {
			Object.each(this.sb.markers, function (marker, key) {
				marker.destroy();
				delete self.sb.markers[key];
			});
		}

		Object.each(this.sb.markers, function (marker, key) {
				diff.each(function (value) {
					if (marker.options.catid == value) {
						marker.destroy();
						delete self.sb.markers[key];
					}
				});
			}
		);


		Object.each(puntos, function (category, key) {

			Object.each(category, function (punto) {
				var position = new google.maps.LatLng(punto.latitude, punto.longitude);

				var category = this.options.categories[key];

				var icon = new google.maps.MarkerImage((punto.icon) ? punto.icon : (category.cat_icon));

				var markerOptions = {
					'title': punto.title,
					'icon': icon
				};

				var marker = this.sb.createMarker(position, markerOptions, key, punto.id);

				var refInfoWindow = this.createInfoWindow(marker, punto);

				marker.addEvent('click', refInfoWindow);
			}, this);
		}, this);

	}
});