
"use strict";
var map;
var geocoder;
var currentMarker = null;
var iconUrl = null;

window.initMap = function () {

  map = new google.maps.Map(document.getElementById('map'), {
    center: {
      lat: 21.4241,
      lng: 39.8173
    },
    zoom: 12
  });

  geocoder = new google.maps.Geocoder();

  var input = document.getElementById('search-address');
  var searchBox = new google.maps.places.SearchBox(input);

  map.addListener('bounds_changed', function () {
    searchBox.setBounds(map.getBounds());
  });

  // --- Map-in-modal Search Box ---
  var modalSearchBox = document.createElement('input');
  modalSearchBox.type = 'text';
  modalSearchBox.placeholder = 'Search location in map...';
  modalSearchBox.style.marginTop = '10px';
  modalSearchBox.style.marginLeft = '10px';
  modalSearchBox.style.width = '300px';
  modalSearchBox.style.padding = '10px';
  modalSearchBox.style.border = '1px solid #ccc';
  modalSearchBox.style.borderRadius = '3px';
  modalSearchBox.style.boxShadow = '0 2px 6px rgba(0,0,0,0.3)';
  modalSearchBox.style.fontFamily = 'Roboto, Arial, sans-serif';
  modalSearchBox.style.fontSize = '14px';
  modalSearchBox.style.backgroundColor = '#fff';

  map.controls[google.maps.ControlPosition.TOP_LEFT].push(modalSearchBox);

  var mapSearch = new google.maps.places.SearchBox(modalSearchBox);

  map.addListener('bounds_changed', function () {
    mapSearch.setBounds(map.getBounds());
  });

  mapSearch.addListener('places_changed', function () {
    var places = mapSearch.getPlaces();
    if (places.length === 0) return;

    if (currentMarker) {
      currentMarker.setMap(null);
    }
    var bounds = new google.maps.LatLngBounds();
    places.forEach(function (place) {
      if (!place.geometry) return;

      var latitude = place.geometry.location.lat();
      var longitude = place.geometry.location.lng();
      var latInput = document.querySelector('#latitude') || document.querySelector('input[name="latitude"]');
      var lngInput = document.querySelector('#longitude') || document.querySelector('input[name="longitude"]');
      if (latInput) latInput.value = latitude;
      if (lngInput) lngInput.value = longitude;

      currentMarker = new google.maps.Marker({
        map: map,
        title: place.name,
        position: place.geometry.location
      });

      if (place.geometry.viewport) {
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);
    map.setZoom(18);
  });
  // ------------------------------

  searchBox.addListener('places_changed', function () {
    var places = searchBox.getPlaces();
    if (places.length === 0) {
      return;
    }

    if (currentMarker) {
      currentMarker.setMap(null);
    }

    var bounds = new google.maps.LatLngBounds();
    places.forEach(function (place) {
      if (!place.geometry) {
        console.log("Returned place contains no geometry");
        return;
      }

      var latitude = place.geometry.location.lat();
      var longitude = place.geometry.location.lng();

      document.querySelector('#latitude').value = latitude;
      document.querySelector('#longitude').value = longitude;

      var icon = {
        url: place.icon,
        size: new google.maps.Size(71, 71),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(17, 34),
        scaledSize: new google.maps.Size(25, 25)
      };

      currentMarker = new google.maps.Marker({
        map: map,
        icon: icon,
        title: place.name,
        position: place.geometry.location
      });

      if (place.geometry.viewport) {
        bounds.union(place.geometry.viewport);
      } else {
        bounds.extend(place.geometry.location);
      }
    });
    map.fitBounds(bounds);

    // Set zoom level to 18 after fitting bounds
    map.setZoom(18);
  });

  // Add click event listener to the map
  google.maps.event.addListener(map, 'click', function (event) {
    var clickedLocation = event.latLng;

    var latitude = clickedLocation.lat();
    var longitude = clickedLocation.lng();

    document.querySelector('#latitude').value = latitude;
    document.querySelector('#longitude').value = longitude;
    geocodeLatLng(geocoder, map, clickedLocation);
  });
}

function geocodeLatLng(geocoder, map, latLng) {

  geocoder.geocode({
    location: latLng
  }, function (results, status) {
    if (status === 'OK') {
      if (results[0]) {
        var placeName = getPlaceName(results);
        if (placeName) {
          setMarker(latLng, placeName);
        } else {
          console.log('No place name found');
        }
      } else {
        console.log('No results found');
      }
    } else {
      console.log('Geocoder failed due to: ' + status);
    }
  });
}

function getPlaceName(results) {
  for (var i = 0; i < results.length; i++) {
    for (var j = 0; j < results[i].address_components.length; j++) {
      var types = results[i].address_components[j].types;
      if (types.indexOf('locality') !== -1 || types.indexOf('sublocality') !== -1 || types.indexOf(
        'neighborhood') !== -1) {
        return results[i].address_components[j].long_name;
      }
    }
  }
  return null;
}

function setMarker(location, title) {
  if (currentMarker) {
    currentMarker.setMap(null);
  }
  currentMarker = new google.maps.Marker({
    position: location,
    map: map,
    title: title
  });
}

$(document).ready(function () {
  $('#search-button').click(function () {
    var input = $('#search-address').val();
    $('#search-address').val('');

    var request = {
      query: input,
      fields: ['name', 'geometry']
    };

    var service = new google.maps.places.PlacesService(map);
    service.findPlaceFromQuery(request, function (results, status) {
      if (status === google.maps.places.PlacesServiceStatus.OK) {
        var bounds = new google.maps.LatLngBounds();
        results.forEach(function (place) {
          if (place.geometry.viewport) {
            bounds.union(place.geometry.viewport);
          } else {
            bounds.extend(place.geometry.location);
          }
        });
        map.fitBounds(bounds);
      } else {
        console.error('Search failed with status: ' + status);
      }
    });
  });
});

function getCurrentLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function (position) {
      var latitude = position.coords.latitude;
      var longitude = position.coords.longitude;

      var latlng = { lat: latitude, lng: longitude };

      // Update the geocode based on the latitude and longitude
      geocodeLatLng(geocoder, map, latlng);

      // Update latitude and longitude fields
      document.querySelector('#latitude').value = latitude;
      document.querySelector('#longitude').value = longitude;

      // If the marker already exists, move it, otherwise create a new one
      if (currentMarker) {
        currentMarker.setPosition(latlng);
      } else {
        // Create the marker if it doesn't exist
        var icon = {
          url: iconUrl,
          size: new google.maps.Size(71, 71),
          origin: new google.maps.Point(0, 0),
          anchor: new google.maps.Point(17, 34),
          scaledSize: new google.maps.Size(25, 25)
        };

        currentMarker = new google.maps.Marker({
          position: latlng,
          map: map,
          icon: icon,
          title: 'Custom Location'
        });
      }

      // Optionally, you can zoom the map to the current location
      map.setCenter(latlng);
      map.setZoom(18);

    }, function (error) {
      alert("Unable to retrieve your location. Error: " + error.message);
    });
  } else {
    alert("Geolocation is not supported by this browser.");
  }
}
if (typeof google !== "undefined" && google.maps) {
  if (typeof initMap === "function") {
    initMap();
  } else {
    // Retry after a slight delay
    setTimeout(() => initMap && initMap(), 100);
  }
}
