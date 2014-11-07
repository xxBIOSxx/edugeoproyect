new Class('edugeo.puntos.modules.puntoscb', {
    Implements:[Options, Events],
    Extends:edugeo.puntos.modules.punto,

    options:{
        mapStartZoom:6,
        showDirections:0,
        readMore:1
    },

    initialize:function (markers, options, sb) {
        this.sb = sb;
        this.setOptions(options);

        this.infoWindow = Map.InfoBubble;
        this.parse(markers);
        window.fireEvent('zoomToMarkers');

        $$('#new-spots span').addEvent('click', function () {
            window.fireEvent('openUniqueInfoWindow', this.get('data-id'));
        });
    },

    parse:function (locations) {
        var self = this;
        Object.each(locations, function (punto) {
            var position = new google.maps.LatLng(punto.gmlat, punto.gmlng),
                category = this.options.categories[punto.catid],
                icon = new google.maps.MarkerImage(punto.icon),
                shadow = new google.maps.MarkerImage(category.cat_shadowicon),
                markerOptions = {
                    'title':punto.title,
                    'icon':icon,
                    'shadow':shadow
                };

           
            if (!self.sb.markers[punto.puntos_id]) {
                var marker = self.sb.createMarker(position, markerOptions, punto.catid, punto.puntos_id);
                var refInfoWindow = self.createInfoWindow(marker, punto);
                marker.addEvent('click', refInfoWindow);
            }

        }, this);


    },

    createInfoWindow:function (marker, punto) {
        return (function () {
            var container = this.infoWindowToolbarActions(punto);
            var text = new Element('div', {
                style:'width: 300px;',
                html:'<h2>' + punto.name + '</h2>' + punto.description_small
            });
            container.inject(text);
            this.infoWindow.setOptions({
                'content':text,
                'position':new google.maps.LatLng(punto.latitude, punto.longitude)
            });

            this.infoWindow.open(this.sb.getMap(), marker.markerObj);

        }.bind(this));
    },

    infoWindowToolbarActions:function (punto) {
        var self = this, readmore = '', zoom = '',
            links = new Element('div', {
                id:'puntos-links'
            });

        if (this.options.readMore.toInt()) {
            readmore = new Element('span', {
                'class':'link'
            }).adopt(new Element('a', {
                href:punto.link,
                html:this.translate('COM_puntoS_READ_MORE', 'Read more')
            }));
        }

        if(this.sb.getMap().getZoom() < 10) {
            zoom = new Element('span', {
                'html' : this.translate('COM_puntoS_ZOOM','zoom'),
                'class' : 'link',
                events: {
                    click: function(){
                        self.sb.getMap().setCenter(new google.maps.LatLng(punto.gmlat, punto.gmlng));
                        self.sb.getMap().setZoom(10);
                        self.infoWindow.close();
                    }
                }
            });
        }

        return new Element('div', {
            id:'puntos-container'
        }).adopt([
            links.adopt([zoom, readmore])
        ]);
    }
});