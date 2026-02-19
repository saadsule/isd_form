<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
    .map-card {
        height: 650px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    #map {
        width: 100%;
        height: 100%;
    }

    .header-title {
        margin-bottom: 15px;
        font-weight: 600;
        color: #2c3e50;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 10px;
    }

    .popup-title {
        font-weight: 600;
        font-size: 15px;
        margin-bottom: 6px;
        color: #2c3e50;
    }

    .popup-row {
        font-size: 13px;
        margin-bottom: 4px;
    }

    .popup-label {
        font-weight: 600;
        color: #555;
    }
    
    .dashboard-card {
    min-height: 75px;
    transition: all 0.25s ease;
}

.dashboard-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.12);
}

.dashboard-card .icon-holder {
    width: 40px;
    height: 40px;
    font-size: 18px;
    flex-shrink: 0;
}

.dashboard-card h6 {
    font-size: 13px;
    font-weight: 600;
}

.dashboard-card h4 {
    font-size: 18px;
}

</style>

<div class="page-container">
    <div class="main-content">

        <div class="row g-3">

    <!-- Number of Districts -->
    <div class="col-md-4">
        <div class="card dashboard-card border-start border-3 border-primary">
            <div class="card-body d-flex align-items-center p-2">
                
                <div class="icon-holder bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                    <i class="anticon anticon-environment"></i>
                </div>

                <div class="flex-grow-1 ms-2 ml-3">
                    <h6 class="card-title mb-1">Number of Districts</h6>
                    <h4 class="fw-bold mb-0 text-end"><?= 1 ?></h4>
                </div>

            </div>
        </div>
    </div>

    <!-- Target Population -->
    <div class="col-md-4">
        <div class="card dashboard-card border-start border-3 border-success">
            <div class="card-body d-flex align-items-center p-2">

                <div class="icon-holder bg-success text-white rounded-circle d-flex align-items-center justify-content-center">
                    <i class="anticon anticon-usergroup-add"></i>
                </div>

                <div class="flex-grow-1 ms-2 ml-3">
                    <h6 class="card-title mb-1">Target Population</h6>
                    <h4 class="fw-bold mb-0 text-end"><?= number_format(142359) ?></h4>
                </div>

            </div>
        </div>
    </div>

    <!-- Health Facilities -->
    <div class="col-md-4">
        <div class="card dashboard-card border-start border-3 border-warning">
            <div class="card-body d-flex align-items-center p-2">

                <div class="icon-holder bg-warning text-white rounded-circle d-flex align-items-center justify-content-center">
                    <i class="anticon anticon-team"></i>
                </div>

                <div class="flex-grow-1 ms-2 ml-3">
                    <h6 class="card-title mb-1">Health Facilities</h6>
                    <h4 class="fw-bold mb-0 text-end"><?= 78 ?></h4>
                </div>

            </div>
        </div>
    </div>

</div>


        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm map-card">
                    <div class="card-body p-0" style="height:100%;">
                        <div id="map"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ✅ Initialize map with default North Waziristan center
    var map = L.map('map', { minZoom: 8 }).setView([33.2, 70.2], 10);

    // Add OpenStreetMap tiles
//    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//        attribution: '&copy; OpenStreetMap contributors',
//        maxZoom: 18
//    }).addTo(map);

    // Load North Waziristan boundaries from GeoJSON
    fetch('<?= base_url("assets/geojson/north_waziristan.json") ?>')
        .then(response => response.json())
        .then(geojsonData => {
            L.geoJSON(geojsonData, {
                style: {
                    color: '#ff7800',
                    weight: 2,
                    opacity: 0.6,
                    fillOpacity: 0.1
                }
            }).addTo(map);

            // Fit map to North Waziristan boundary
            var nwBounds = L.geoJSON(geojsonData).getBounds();
            map.fitBounds(nwBounds, { padding: [20, 20] });
        });

    // Facilities from PHP
    var facilities = <?= json_encode($facilities) ?>;

    facilities.forEach(function(facility){
        if(facility.latitude && facility.longitude){
            var lat = parseFloat(facility.latitude);
            var lng = parseFloat(facility.longitude);

            var marker = L.marker([lat, lng], { riseOnHover: true }).addTo(map);

            // Popup content with all fields except latitude/longitude
            var popupContent = `<div style="min-width:240px;">`;
            popupContent += `<div class="popup-title">${facility.facility_name ?? 'Facility Details'}</div>`;
            popupContent += `<hr style="margin:6px 0;">`;

            for (var key in facility) {
                if (facility[key] && key !== 'latitude' && key !== 'longitude') {
                    var label = key.replace(/_/g, ' ')
                                   .replace(/\b\w/g, l => l.toUpperCase());
                    popupContent += `<div class="popup-row"><span class="popup-label">${label}:</span> ${facility[key]}</div>`;
                }
            }

            popupContent += `</div>`;

            marker.bindPopup(popupContent);

            // Zoom on click
            marker.on('click', function(){
                map.flyTo([lat, lng], 15, { animate: true, duration: 1.2 });
            });
        }
    });

    // Fix map size after render
    setTimeout(() => map.invalidateSize(), 300);

});
</script>
