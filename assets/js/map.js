/**
 * SOUK.IQ Leaflet Map Integration
 */

const SoukMap = {
    // Initialize a single marker map (e.g. for store page contact tab)
    initStoreMap(elementId, lat, lng, storeName) {
        const mapElement = document.getElementById(elementId);
        if (!mapElement) return;

        // Default coordinates to Baghdad if missing
        const latitude = lat ? parseFloat(lat) : 33.3152;
        const longitude = lng ? parseFloat(lng) : 44.3661;

        // Initialize map
        const map = L.map(elementId).setView([latitude, longitude], 14);

        // Add OSM tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add Marker
        L.marker([latitude, longitude]).addTo(map)
            .bindPopup(`<b>${storeName}</b>`)
            .openPopup();
    },

    // Initialize map selector (e.g. for store registration settings)
    initMapPicker(elementId, latInputSelector, lngInputSelector, initialLat, initialLng) {
        const mapElement = document.getElementById(elementId);
        if (!mapElement) return;

        const latInput = document.querySelector(latInputSelector);
        const lngInput = document.querySelector(lngInputSelector);

        const defaultLat = initialLat ? parseFloat(initialLat) : 33.3152;
        const defaultLng = initialLng ? parseFloat(initialLng) : 44.3661;

        const map = L.map(elementId).setView([defaultLat, defaultLng], 12);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Create draggable marker
        const marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);

        // Update inputs on drag end
        marker.on('dragend', function (e) {
            const position = marker.getLatLng();
            latInput.value = position.lat.toFixed(7);
            lngInput.value = position.lng.toFixed(7);
        });

        // Update marker position on map click
        map.on('click', function (e) {
            marker.setLatLng(e.latlng);
            latInput.value = e.latlng.lat.toFixed(7);
            lngInput.value = e.latlng.lng.toFixed(7);
        });
    }
};

window.SoukMap = SoukMap;
