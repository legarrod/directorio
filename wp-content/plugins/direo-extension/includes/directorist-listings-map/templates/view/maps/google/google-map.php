<?php
//wp_enqueue_script('atbdp-map-view',ATBDP_PUBLIC_ASSETS . 'js/map-view.js');
$display_map_info               = get_directorist_option('display_map_info', 1);
$display_image_map              = get_directorist_option('display_image_map', 1);
$display_title_map              = get_directorist_option('display_title_map', 1);
$display_address_map            = get_directorist_option('display_address_map', 1);
$display_direction_map          = get_directorist_option('display_direction_map', 1);
$zoom                           = get_directorist_option('listing_map_zoom_level', 2);

if(empty($display_map_info)) {
    $disable_info_window = 'yes';
}elseif (empty($display_image_map || $display_title_map || $display_address_map || $display_direction_map)){
    $disable_info_window = 'yes';
}else{
    $disable_info_window = 'no';
}
$data = array(
    'plugin_url' => ATBDP_URL,
    'disable_info_window' => $disable_info_window,
    'zoom'       => !empty($zoom) ? $zoom : 2,
);
//wp_localize_script( 'atbdp-map-view', 'atbdp_map', $data );
?>
<div class="atbdp-body atbdp-map embed-responsive embed-responsive-16by9 atbdp-margin-bottom" data-url="<?php echo ATBDP_URL;?>" data-type="markerclusterer" style="height: <?php echo !empty($listings_map_height)?$listings_map_height:'';?>px;">
    <?php while( $all_listings->have_posts() ) : $all_listings->the_post();
        global $post;
        $manual_lat                     = get_post_meta($post->ID, '_manual_lat', true);
        $manual_lng                     = get_post_meta($post->ID, '_manual_lng', true);
        $listing_img                    = get_post_meta(get_the_ID(), '_listing_img', true);
        $listing_prv_img                = get_post_meta(get_the_ID(), '_listing_prv_img', true);
        $crop_width                     = get_directorist_option('crop_width', 360);
        $crop_height                    = get_directorist_option('crop_height', 300);
        $address                        = get_post_meta(get_the_ID(), '_address', true);
        $cats                           = get_the_terms(get_the_ID(), ATBDP_CATEGORY);
        if(!empty($cats)){
            $cat_icon                       = get_cat_icon($cats[0]->term_id);
        }
        $cat_icon = !empty($cat_icon) ? $cat_icon : 'fa-map-marker';
        $icon_type = substr($cat_icon, 0,2);
        $fa_or_la = ('la' == $icon_type) ? "la " : "fa ";
        if(!empty($listing_prv_img)) {

            $prv_image   = wp_get_attachment_image_src($listing_prv_img, 'large')[0];

        }
        if(!empty($listing_img[0])) {

            $default_img = atbdp_image_cropping(ATBDP_PUBLIC_ASSETS . 'images/grid.jpg', $crop_width, $crop_height, true, 100)['url'];
            $gallery_img = wp_get_attachment_image_src($listing_img[0], 'medium')[0];

        }
        ?>
        <?php if( ! empty( $manual_lat ) && ! empty( $manual_lng ) ) : ?>
            <div class="marker" data-latitude="<?php echo $manual_lat; ?>" data-longitude="<?php echo $manual_lng; ?>" data-icon="<?php echo ('none' == $cat_icon) ? 'fa fa-map-marker' : $fa_or_la . $cat_icon;?>">
                <?php if(!empty($display_map_info) && (!empty($display_image_map) || !empty($display_title_map)|| !empty($display_address_map) || !empty($display_direction_map))) { ?>
                    <div class="map-info-wrapper">
                        <input type="hidden" id="icon" value="fa fa-flag">
                        <?php if(!empty($display_image_map)) { ?>
                            <div class="map-info-img">
                                <a href="<?php the_permalink(); ?>">
                                    <?php
                                    $default_image = get_directorist_option('default_preview_image', ATBDP_PUBLIC_ASSETS . 'images/grid.jpg');
                                    if(!empty($listing_prv_img)){

                                        echo '<img src="'.esc_url($prv_image).'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                    } if(!empty($listing_img[0]) && empty($listing_prv_img)) {

                                        echo '<img src="' . esc_url($gallery_img) . '" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                    }if (empty($listing_img[0]) && empty($listing_prv_img)){

                                        echo '<img src="'.$default_image.'" alt="'.esc_html(stripslashes(get_the_title())).'">';

                                    }
                                    ?>
                                </a>
                            </div>
                        <?php } ?>
                        <?php if(!empty($display_title_map) || !empty($display_address_map) || !empty($display_direction_map)) { ?>
                            <div class="map-info-details">
                                <?php if(!empty($display_title_map)) { ?>
                                    <div class="atbdp-listings-title-block">
                                        <h3 class="atbdp-no-margin"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                <?php } ?>
                                <?php if(!empty($address)) { ?>
                                    <?php if(!empty(!empty($display_address_map))) {?>
                                        <div class="map_addr"><span class="<?php atbdp_icon_type(true); ?>-map-marker"></span> <a href="" class="map-info-link"><?php echo $address;?></a></div>
                                    <?php } ?>
                                    <?php if(!empty($display_direction_map)) {?>
                                        <div class="map_get_dir"><a href='http://www.google.com/maps?daddr=<?php echo $manual_lat; ?>,<?php echo $manual_lng; ?>' target='_blank'><?php _e('Get Direction', 'direo-extension') ?></a> <span class="<?php atbdp_icon_type(true); ?>-arrow-right"></span>
                                        </div>
                                    <?php } } ?>

                                <?php do_action( 'atbdp_after_listing_content', $post->ID, 'map' ); ?>
                            </div>
                        <?php } ?>
                        <span id="iw-close-btn"><i class="la la-times"></i></span>
                    </div>
                <?php } ?>
            </div>
        <?php endif; ?>

    <?php endwhile;
    wp_reset_postdata();
    ?>
</div>
<script>
        // Define Marker Shapes
        var MAP_PIN = 'M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z';

        var inherits = function(childCtor, parentCtor) {
            /** @constructor */
            function tempCtor() {}
            tempCtor.prototype = parentCtor.prototype;
            childCtor.superClass_ = parentCtor.prototype;
            childCtor.prototype = new tempCtor();
            childCtor.prototype.constructor = childCtor;
        };
        function Marker(options){
            google.maps.Marker.apply(this, arguments);

            if (options.map_icon_label) {
                this.MarkerLabel = new MarkerLabel({
                    map: this.map,
                    marker: this,
                    text: options.map_icon_label
                });
                this.MarkerLabel.bindTo('position', this, 'position');
            }
        }
        // Apply the inheritance
        inherits(Marker, google.maps.Marker);
        // Custom Marker SetMap
        Marker.prototype.setMap = function() {
            google.maps.Marker.prototype.setMap.apply(this, arguments);
            (this.MarkerLabel) && this.MarkerLabel.setMap.apply(this.MarkerLabel, arguments);
        };
        // Marker Label Overlay
        var MarkerLabel = function(options) {
            var self = this;
            this.setValues(options);

            // Create the label container
            this.div = document.createElement('div');
            this.div.className = 'map-icon-label';

            // Trigger the marker click handler if clicking on the label
            google.maps.event.addDomListener(this.div, 'click', function(e){
                (e.stopPropagation) && e.stopPropagation();
                google.maps.event.trigger(self.marker, 'click');
            });
        };
        // Create MarkerLabel Object
        MarkerLabel.prototype = new google.maps.OverlayView;
        // Marker Label onAdd
        MarkerLabel.prototype.onAdd = function() {
            var pane = this.getPanes().overlayImage.appendChild(this.div);
            var self = this;
            this.listeners = [
                google.maps.event.addListener(this, 'position_changed', function() { self.draw(); }),
                google.maps.event.addListener(this, 'text_changed', function() { self.draw(); }),
                google.maps.event.addListener(this, 'zindex_changed', function() { self.draw(); })
            ];
        };
        // Marker Label onRemove
        MarkerLabel.prototype.onRemove = function() {
            this.div.parentNode.removeChild(this.div);
            for (var i = 0, I = this.listeners.length; i < I; ++i) {
                google.maps.event.removeListener(this.listeners[i]);
            }
        };
        // Implement draw
        MarkerLabel.prototype.draw = function() {
            var projection = this.getProjection();
            var position = projection.fromLatLngToDivPixel(this.get('position'));
            var div = this.div;
            this.div.innerHTML = this.get('text').toString();
            div.style.zIndex = this.get('zIndex'); // Allow label to overlay marker
            div.style.position = 'absolute';
            div.style.display = 'block';
            div.style.left = (position.x - (div.offsetWidth / 2)) + 'px';
            div.style.top = (position.y - div.offsetHeight) + 'px';
        };
        (function ($) {
//map view
            /**
             *  Render a Google Map onto the selected jQuery element.
             *
             *  @since    5.0.0
             */
            var at_icon = [];
            function atbdp_rander_map( $el ) {
                $el.addClass( 'atbdp-map-loaded' );
                // var
                var $markers = $el.find('.marker');
                // vars
                var args = {
                    zoom	    : parseInt( <?php echo !empty($zoom) ? $zoom : 1;?> ),
                    center	    : new google.maps.LatLng( 0, 0 ),
                    mapTypeId   : google.maps.MapTypeId.ROADMAP,
                    zoomControl : true,
                    scrollwheel : false
                };
                // create map
                var map = new google.maps.Map( $el[0], args );
                // add a markers reference
                map.markers = [];
                // set map type
                map.type = $el.data('type');
                map.url = $el.data('url');
                var map_infowindow = new google.maps.InfoWindow();
                // add markers
                $markers.each(function() {
                    atbdp_add_marker( $( this ), map, map_infowindow );
                });
                // center map
                atbdp_center_map( map );
                // update map when contact details fields are updated in the custom post type 'acadp_listings'
                var mcOptions = {
                    //imagePath: map.url+'public/assets/images/m',
                    cssClass: "marker-cluster-shape",
                };
                if( map.type === 'markerclusterer' ) {
                    var markerCluster = new MarkerClusterer( map, map.markers, mcOptions );
                }
            }
            /**
             *  Add a marker to the selected Google Map.
             *
             *  @since    1.0.0
             */
            function atbdp_add_marker( $marker, map, map_infowindow ) {
                // var
                var latlng = new google.maps.LatLng( $marker.data( 'latitude' ), $marker.data( 'longitude' ) );
                // check to see if any of the existing markers match the latlng of the new marker
                if( map.markers.length ) {
                    for( var i = 0; i < map.markers.length; i++ ) {
                        var existing_marker = map.markers[i];
                        var pos = existing_marker.getPosition();
                        // if a marker already exists in the same position as this marker
                        if( latlng.equals( pos ) ) {
                            // update the position of the coincident marker by applying a small multipler to its coordinates
                            var latitude  = latlng.lat() + ( Math.random() - .5 ) / 1500; // * (Math.random() * (max - min) + min);
                            var longitude = latlng.lng() + ( Math.random() - .5 ) / 1500; // * (Math.random() * (max - min) + min);
                            latlng = new google.maps.LatLng( latitude, longitude );
                        }
                    }
                }
                var icon = $marker.data( 'icon' );
                //console.log(icon);
                var marker = new Marker({
                    position  : latlng,
                    map		  : map,
                    icon: {
                        path: MAP_PIN,
                        fillColor: 'transparent',
                        fillOpacity: 1,
                        strokeColor: '',
                        strokeWeight: 0
                    },
                    map_icon_label: '<div class="atbd_map_shape"><i class="'+icon+'"></i></div>'
                });
                // add to array
                map.markers.push( marker );
                // if marker contains HTML, add it to an infoWindow
                if( $marker.html() ) {
                    //map info window close button
                    google.maps.event.addListener(map_infowindow, 'domready', function() {
                        var closeBtn = $('#iw-close-btn').get();
                        google.maps.event.addDomListener(closeBtn[0], 'click', function() {
                            map_infowindow.close();
                        });
                    });

                    // show info window when marker is clicked
                    google.maps.event.addListener(marker, 'click', function() {
                        <?php if('no' == $disable_info_window) {?>
                        map_infowindow.setContent( $marker.html() );
                        map_infowindow.open( map, marker );
                        <?php } ?>
                    });
                }
            }
            /**
             *  Center the map, showing all markers attached to this map.
             *
             *  @since    1.0.0
             */
            function atbdp_center_map( map ) {
                // vars
                var bounds = new google.maps.LatLngBounds();
                // loop through all markers and create bounds
                $.each( map.markers, function( i, marker ){
                    var latlng = new google.maps.LatLng( marker.position.lat(), marker.position.lng() );
                    bounds.extend( latlng );
                });
                // only 1 marker?
                if( map.markers.length !== 1 ) {
                    // set center of map
                    map.setCenter( bounds.getCenter() );
                    map.setZoom( parseInt( <?php echo !empty($zoom) ? $zoom : 1;?> ) );
                } else {
                    // fit to bounds
                    map.fitBounds( bounds );
                }
            }
// render map in the custom post
            $( '.atbdp-map' ).each(function() {
                atbdp_rander_map( $( this ) );
            });
            window.addEventListener("load", () => {
                var abc = document.querySelectorAll('div');
                abc.forEach(function (el, index) {
                    if(el.innerText === "atgm_marker"){
                        //console.log(at_icon)
                        el.innerText = " ";
                        el.innerHTML = `<i class="la ${at_icon} atbd_map_marker_icon"></i>`;
                    }
                    //${$marker.data('icon')}
                });
                document.querySelectorAll('div').forEach((el1, index) => {
                    if(el1.style.backgroundImage.split("/").pop() === 'm1.png")'){
                        el1.addEventListener('click', () => {
                            setInterval(() => {
                                var abc = document.querySelectorAll('div');
                                abc.forEach(function (el, index) {
                                    if(el.innerText === "atgm_marker"){
                                        el.innerText = " ";
                                        el.innerHTML = `<i class="la ${at_icon} atbd_map_marker_icon"></i>`;
                                    }
                                })
                            }, 100)
                        });
                    }
                });
            })
        })(jQuery);
    </script>

