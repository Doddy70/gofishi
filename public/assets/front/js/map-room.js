"use strict";
var map, markers = [];

function initMap() {
    const featured_content = Object.values(featured_contents);
    const room_content = Object.values(room_contents.data);
    
    var mapId = $(".btn[data-bs-target='#mapModal']").is(":visible") ? "modal-main-map" : "main-map";
    var mapElement = document.getElementById(mapId);
    
    if (!mapElement) return;

    // Default center (Jakarta or similar)
    var myLatLng = { lat: -6.2, lng: 106.8 };
    
    // If user has shared location
    var userLat = parseFloat($('#userLat').val());
    var userLng = parseFloat($('#userLng').val());
    if (!isNaN(userLat) && !isNaN(userLng)) {
        myLatLng = { lat: userLat, lng: userLng };
    }

    map = new google.maps.Map(mapElement, {
        zoom: 12,
        center: myLatLng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false,
        styles: [
            {
                "featureType": "poi.business",
                "stylers": [{ "visibility": "off" }]
            },
            {
                "featureType": "transit",
                "elementType": "labels.icon",
                "stylers": [{ "visibility": "off" }]
            }
        ]
    });

    // Add User Location Marker if exists
    if (!isNaN(userLat) && !isNaN(userLng)) {
        new google.maps.Marker({
            position: { lat: userLat, lng: userLng },
            map: map,
            icon: {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 10,
                fillColor: "#4285F4",
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: "white",
            },
            title: "Lokasi Anda"
        });
    }

    const allRooms = featured_content.concat(room_content);
    const infoWindow = new google.maps.InfoWindow();

    allRooms.forEach(room => {
        if (!room.latitude || !room.longitude) return;

        const marker = new google.maps.Marker({
            position: { lat: parseFloat(room.latitude), lng: parseFloat(room.longitude) },
            map: map,
            title: room.title,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png'
            }
        });

        marker.addListener("click", () => {
            const content = `
                <div style="width: 200px;">
                    <img src="assets/img/perahu/featureImage/${room.feature_image}" style="width: 100%; border-radius: 8px; margin-bottom: 8px;">
                    <h6 style="margin: 0 0 4px 0;">${room.title}</h6>
                    <p style="font-size: 12px; color: #666; margin-bottom: 8px;">${room.address || ''}</p>
                    <a href="perahu/${room.slug}/${room.id}" style="color: #FF385C; font-weight: bold; text-decoration: none;">Lihat Detail</a>
                </div>
            `;
            infoWindow.setContent(content);
            infoWindow.open(map, marker);
        });

        markers.push(marker);
    });

    if (markers.length > 0) {
        const bounds = new google.maps.LatLngBounds();
        markers.forEach(m => bounds.extend(marker.getPosition()));
        if (!isNaN(userLat) && !isNaN(userLng)) {
            bounds.extend(new google.maps.LatLng(userLat, userLng));
        }
        map.fitBounds(bounds);
    }
}

// Make initMap global for the callback
window.initMap = initMap;
