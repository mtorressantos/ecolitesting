(function($) {
    'use strict';

    var _toBuild = [];

    MK.component.AdvancedGMaps = function(el) {
        var $this = $(el),
            container = document.getElementById( 'mk-theme-container' ),
            data = $this.data( 'advancedgmaps-config' ),
            apikey = data.options.apikey ? ('key='+data.options.apikey+'&') : false,
            map = null,
            bounds = null,
            infoWindow = null,
            position = null;

        var build = function() {
            data.options.scrollwheel = false;
            data.options.mapTypeId = google.maps.MapTypeId[data.options.mapTypeId];
            data.options.styles = data.style;

            bounds = new google.maps.LatLngBounds();
            map = new google.maps.Map(el, data.options);
            infoWindow = new google.maps.InfoWindow();

            map.setOptions({
                panControl : data.options.panControl,
                draggable:  data.options.draggable,
                zoomControl:  data.options.zoomControl,
                mapTypeControl:  data.options.scaleControl,
                scaleControl:  data.options.mapTypeControl,
            });

            var marker, i;

            map.setTilt(45);

            for (i = 0; i < data.places.length; i++) {
                if(data.places[i].latitude && data.places[i].longitude) {
                    position = new google.maps.LatLng(data.places[i].latitude, data.places[i].longitude);

                    bounds.extend(position);

                    marker = new google.maps.Marker({
                        position: position,
                        map: map,
                        title: data.places[i].address,
                        icon: (data.places[i].marker) ? data.places[i].marker : data.icon
                    });


                    google.maps.event.addListener(marker, 'click', (function(marker, i) {
                        return function() { 
                            if(data.places[i].address && data.places[i].address.length > 1) {
                                infoWindow.setContent('<div class="info_content"><p>'+ data.places[i].address +'</p></div>');
                                infoWindow.open(map, marker);
                            } else {
                                infoWindow.setContent('');
                                infoWindow.close();
                            }
                        };
                    })(marker, i));

                    /**
                     * If there is only one marker, map.fitBounds will zoom-in too much.
                     * Only run map.fitBounds if the markers are more than 1. Use setCenter
                     * instead if the the marker is only 1.
                     */
                    if ( i > 0 ) {
                        map.fitBounds( bounds );
                    } else {
                        // Set latitude and longtitude as float.
                        var latLang = {
                            lat: parseFloat( data.places[i].latitude ),
                            lng: parseFloat( data.places[i].longitude )
                        };
                        map.setCenter( latLang );
                        // Need to setZoom here as we didn't trigger bounds_changed event.
                        map.setZoom( data.options.zoom );
                    }
                }
            }

            var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
                this.setZoom(data.options.zoom);
                google.maps.event.removeListener(boundsListener);
            });


            var update = function() {
                google.maps.event.trigger(map, "resize");
                map.setCenter(position);
            };
            update();


            var bindEvents = function() {
                $( window ).on( 'resize', update );
                window.addResizeListener( container, update );
            };
            bindEvents();
        };


        var initAll = function() {
            for( var i = 0, l = _toBuild.length; i < l; i++ ) {
                _toBuild[i]();
            }
        };

        MK.api.advancedgmaps = MK.api.advancedgmaps || function() {
            initAll();
        };

        return {
            init : function() {
                _toBuild.push( build );
                MK.core.loadDependencies(['https://maps.googleapis.com/maps/api/js?'+apikey+'callback=MK.api.advancedgmaps']);
            }
        };

    };

})(jQuery);