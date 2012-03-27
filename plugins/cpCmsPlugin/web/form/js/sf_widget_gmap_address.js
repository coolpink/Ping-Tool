function sfGmapWidgetWidget(options){
  // this global attributes
  this.lng      = null;
  this.lat      = null;
  this.address  = null;
  this.map      = null;
  this.zoom     = 5;
  this.geocoder = null;
  this.options  = options;
 
  this.init();
}
 
sfGmapWidgetWidget.prototype = new Object();
 
sfGmapWidgetWidget.prototype.init = function() {
 
  // retrieve dom element
  this.lng      = jQuery("#" + this.options.longitude);
  this.lat      = jQuery("#" + this.options.latitude);
  this.address  = jQuery("#" + this.options.address);
  this.lookup   = jQuery("#" + this.options.lookup);
 
  // create the google geocoder object
  this.geocoder = new google.maps.Geocoder();
 
  // create the map
  var latlng = new google.maps.LatLng(this.lat.val(), this.lng.val());
  var myOptions = {
    zoom: this.zoom,
    center: latlng,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  this.map = new google.maps.Map(jQuery("#" + this.options.map).get(0), myOptions);

  // cross reference object
  this.map.sfGmapWidgetWidget = this;
  this.geocoder.sfGmapWidgetWidget = this;
  this.lookup.get(0).sfGmapWidgetWidget = this;
 
  // add the default location
  this.marker = new google.maps.Marker({
    position: latlng,
    draggable: true,
    map: this.map,
    title:"Location"
  });
 
  // bind the move action on the map
  google.maps.event.addListener(this.marker, 'dragend', function(event) {
    this.sfGmapWidgetWidget.lng.val(event.latLng.lat());
    this.sfGmapWidgetWidget.lat.val(event.latLng.lng());
  });
 
  // bind the click action on the map
  google.maps.event.addListener(this.map, "click", function(event) {
    if (event.latLng != null) {
      sfGmapWidgetWidget.activeWidget = this.sfGmapWidgetWidget;
 
      this.sfGmapWidgetWidget.geocoder.geocode(
        {'latLng': event.latLng},
        sfGmapWidgetWidget.reverseLookupCallback
      );
    }
  });
 
  // bind the click action on the lookup field
  this.lookup.bind('click', function(){
    sfGmapWidgetWidget.activeWidget = this.sfGmapWidgetWidget;
 
    this.sfGmapWidgetWidget.geocoder.geocode(
      { 'address': this.sfGmapWidgetWidget.address.val()},
      sfGmapWidgetWidget.lookupCallback
    );
 
    return false;
  })
}
 
sfGmapWidgetWidget.activeWidget = null;
sfGmapWidgetWidget.lookupCallback = function(results, status) {
  if (status == google.maps.GeocoderStatus.OK) {
    // get the widget and clear the state variable
    var widget = sfGmapWidgetWidget.activeWidget;
    sfGmapWidgetWidget.activeWidget = null;

    widget.marker.setMap(null);
    if (widget.map.getZoom() == widget.zoom) {
      widget.map.setZoom(11);
    }
    widget.map.setCenter(results[0].geometry.location);
    widget.marker = new google.maps.Marker({
      map: widget.map,
      position: results[0].geometry.location
    });

    // update values
    widget.address.val(results[0].formatted_address);
    widget.lat.val(results[0].geometry.location.lat());
    widget.lng.val(results[0].geometry.location.lng());
  } else {
    alert("Geocode was not successful for the following reason: " + status);
  }
}
 
sfGmapWidgetWidget.reverseLookupCallback = function(results, status) {
  if (status == google.maps.GeocoderStatus.OK) {
    if (results[1]) {
      // get the widget and clear the state variable
      var widget = sfGmapWidgetWidget.activeWidget;
      sfGmapWidgetWidget.activeWidget = null;

      widget.marker.setMap(null);
      if (widget.map.getZoom() == widget.zoom) {
        widget.map.setZoom(11);
      }
      widget.map.setCenter(results[0].geometry.location);
      widget.marker = new google.maps.Marker({
        map: widget.map,
        position: results[0].geometry.location
      });

      // update values
      widget.address.val(results[0].formatted_address);
      widget.lat.val(results[0].geometry.location.lat());
      widget.lng.val(results[0].geometry.location.lng());
    }
  } else {
    alert("Geocode was not successful for the following reason: " + status);
  }
}