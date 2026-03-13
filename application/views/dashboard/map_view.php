<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<style>
.stat-card {
    border-radius: 8px;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    border: 1px solid #e8edf5;
    background: #fff;
    transition: transform 0.2s, box-shadow 0.2s;
    height: 100%;
}
.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.10);
}
.stat-icon {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 17px;
    flex-shrink: 0;
}
.stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #0f1c3f;
    line-height: 1.1;
}
.stat-label {
    font-size: 10px;
    color: #8a94a6;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    margin-top: 1px;
}
.age-group-card {
    background: #fff;
    border-radius: 8px;
    padding: 10px 14px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    border: 1px solid #e8edf5;
}
.age-group-card .age-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px dashed #f0f4f9;
    font-size: 12px;
}
.age-group-card .age-row:last-child {
    border-bottom: none;
}
.age-group-card .age-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-weight: 600;
    color: #0f1c3f;
}
.age-group-card .age-count {
    font-weight: 700;
    font-size: 14px;
}
#map {
    width: 100%;
    height: 100%;
    border-radius: 8px;
}
.map-wrap {
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    height: 420px;
}
</style>

<div class="page-container">
<div class="main-content">

<!-- Page Header -->
<div class="page-header" style="margin-bottom:12px;">
    <h2 class="header-title" style="font-size:18px; font-weight:700; color:#0f1c3f; margin:0;">
        <i class="anticon anticon-environment" style="color:#3b82f6; margin-right:8px;"></i>
        North Waziristan — Health Dashboard
    </h2>
</div>

<?php
$s          = isset($today_stats) ? $today_stats : array();
$opd_total  = isset($s['opd_total'])    ? $s['opd_total']    : 0;
$mnch_total = isset($s['mnch_total'])   ? $s['mnch_total']   : 0;
$ch_total   = isset($s['ch_total'])     ? $s['ch_total']     : 0;
$vaccinated = isset($s['vaccinated'])   ? $s['vaccinated']   : 0;
$ch_out     = isset($s['ch_outreach'])  ? $s['ch_outreach']  : 0;
$ch_fix     = isset($s['ch_fixed'])     ? $s['ch_fixed']     : 0;
$age_groups = isset($s['age_groups'])   ? $s['age_groups']   : array();
$population = isset($summary->total_population)  ? number_format($summary->total_population)  : 0;
$total_facilities = isset($summary->total_facilities) ? $summary->total_facilities : 0;
?>

<!-- Row 1: OPD, MNCH, Outreach, Fixed Site -->
<div class="row g-2 m-b-10">
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left:3px solid #3b82f6;">
            <div class="stat-icon" style="background:#eff6ff; color:#3b82f6;">
                <i class="anticon anticon-bank"></i>
            </div>
            <div>
                <div class="stat-value"><?= $total_facilities ?></div>
                <div class="stat-label">Health Facilities</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left:3px solid #8b5cf6;">
            <div class="stat-icon" style="background:#f5f3ff; color:#8b5cf6;">
                <i class="anticon anticon-team"></i>
            </div>
            <div>
                <div class="stat-value"><?= $population ?></div>
                <div class="stat-label">Target Population</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left:3px solid #10b981;">
            <div class="stat-icon" style="background:#ecfdf5; color:#10b981;">
                <i class="anticon anticon-solution"></i>
            </div>
            <div>
                <div class="stat-value"><?= $ch_total ?></div>
                <div class="stat-label">Total CH Forms</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="stat-card" style="border-left:3px solid #f97316;">
            <div class="stat-icon" style="background:#fff7ed; color:#f97316;">
                <i class="anticon anticon-safety-certificate"></i>
            </div>
            <div>
                <div class="stat-value"><?= $vaccinated ?></div>
                <div class="stat-label">Total Vaccinated</div>
            </div>
        </div>
    </div>
</div>

<!-- Row 3: Age Groups (left) + Map (right) -->
<div class="row g-2">

    <!-- Left column -->
    <div class="col-md-3">
        <div class="row g-2">

            <!-- Age Group card -->
            <!-- Age Group card -->
<div class="col-12">
    <div class="age-group-card">
        <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#64748b; margin-bottom:8px;">
            <i class="anticon anticon-usergroup-add" style="margin-right:5px;"></i>
            CH Forms — Age Breakdown
        </div>
        <?php
        $age_config = array(
            '<1 Year'   => '#f59e0b',
            '1-2 Year'  => '#10b981',
            '2-5 Year'  => '#8b5cf6',
            '5-15 Year' => '#3b82f6',
            '15-49 Year'=> '#e05c7a',
        );
        foreach ($age_config as $age => $color):
            $count = isset($age_groups[$age]) ? $age_groups[$age] : 0;
        ?>
        <div class="age-row">
            <div class="age-badge">
                <span style="width:8px; height:8px; border-radius:50%; background:<?= $color ?>; display:inline-block; flex-shrink:0;"></span>
                <?= $age ?>
            </div>
            <div class="age-count" style="color:<?= $color ?>;"><?= $count ?></div>
        </div>
        <?php endforeach; ?>
        <div class="age-row" style="margin-top:6px; border-top:2px solid #f0f4f9; padding-top:8px; border-bottom:none;">
            <div class="age-badge" style="font-weight:700; color:#0f1c3f;">
                <span style="width:8px; height:8px; border-radius:50%; background:#0f1c3f; display:inline-block;"></span>
                Total
            </div>
            <div class="age-count" style="color:#0f1c3f;"><?= $ch_total ?></div>
        </div>
    </div>
</div>

            <!-- Vaccinated -->
<div class="col-12 mt-1">
    <div class="stat-card" style="border-left:3px solid #f59e0b; padding:7px 10px;">
        <div class="stat-icon" style="background:#fffbeb; color:#f59e0b; width:32px; height:32px; font-size:14px;">
            <i class="anticon anticon-home"></i>
        </div>
        <div>
            <div class="stat-value" style="font-size:16px;"><?= $ch_fix ?></div>
            <div class="stat-label">CH Fixed Site</div>
        </div>
    </div>
</div>

<!-- Total CH -->
<div class="col-12 mt-1">
    <div class="stat-card" style="border-left:3px solid #06b6d4; padding:7px 10px;">
        <div class="stat-icon" style="background:#ecfeff; color:#06b6d4; width:32px; height:32px; font-size:14px;">
            <i class="anticon anticon-car"></i>
        </div>
        <div>
            <div class="stat-value" style="font-size:16px;"><?= $ch_out ?></div>
            <div class="stat-label">CH Outreach</div>
        </div>
    </div>
</div>

<!-- Population -->
<div class="col-12 mt-1">
    <div class="stat-card" style="border-left:3px solid #0ea5e9; padding:7px 10px;">
        <div class="stat-icon" style="background:#f0f9ff; color:#0ea5e9; width:32px; height:32px; font-size:14px;">
            <i class="anticon anticon-heart"></i>
        </div>
        <div>
            <div class="stat-value" style="font-size:16px;"><?= $mnch_total ?></div>
            <div class="stat-label">Total MNCH Forms</div>
        </div>
    </div>
</div>

<!-- Facilities -->
<div class="col-12 mt-1">
    <div class="stat-card" style="border-left:3px solid #64748b; padding:7px 10px;">
        <div class="stat-icon" style="background:#f8fafc; color:#64748b; width:32px; height:32px; font-size:14px;">
            <i class="anticon anticon-medicine-box"></i>
        </div>
        <div>
            <div class="stat-value" style="font-size:16px;"><?= $opd_total ?></div>
            <div class="stat-label">Total OPD Forms</div>
        </div>
    </div>
</div>

        </div>
    </div>

    <!-- Map -->
    <div class="col-md-9">
        <div class="map-wrap">
            <div id="map"></div>
        </div>
    </div>

</div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    var map = L.map('map', { minZoom: 8, zoomControl: true }).setView([33.2, 70.2], 10);

    // Original OSM tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Inject styles
    var style = document.createElement('style');
    style.innerHTML = `
        @keyframes pulseRing {
            0%   { transform: translate(-50%,-50%) scale(1);   opacity: 0.6; }
            100% { transform: translate(-50%,-50%) scale(2.4); opacity: 0; }
        }
        @keyframes markerDrop {
            0%   { opacity:0; transform: translateY(-16px) scale(0.8); }
            65%  { transform: translateY(3px) scale(1.05); }
            100% { opacity:1; transform: translateY(0) scale(1); }
        }
        .nw-marker { animation: markerDrop 0.45s ease both; }
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.18);
            border: none;
            padding: 0;
            overflow: hidden;
        }
        .custom-popup .leaflet-popup-content { margin: 0; }
        .custom-popup .leaflet-popup-tip-container { display:none; }
        .popup-header {
            background: linear-gradient(135deg, #0a1628 0%, #1d4e89 100%);
            padding: 12px 16px;
        }
        .popup-header-title {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }
        .popup-header-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.55);
            margin-top: 2px;
        }
        .popup-body { padding: 10px 14px 12px; background:#fff; }
        .popup-row {
            display: flex;
            gap: 8px;
            font-size: 12px;
            padding: 4px 0;
            border-bottom: 1px solid #f1f5f9;
            color: #475569;
        }
        .popup-row:last-child { border-bottom: none; }
        .popup-row-label { font-weight: 600; color: #1e3a5f; min-width: 85px; }
    `;
    document.head.appendChild(style);

    // Health kit marker — teardrop shape with cross inside
    function makeMarker(index) {
        var colors = ['#1a73c8','#1a73c8','#1a73c8','#1a73c8','#1a73c8'];
        var color  = colors[index % colors.length];
        var pulse  = index < 4;

        return L.divIcon({
            className: '',
            html: `
                <div class="nw-marker" style="position:relative; width:32px; height:42px; animation-delay:${index * 0.1}s;">
                    ${pulse ? `
                    <div style="
                        position:absolute; top:14px; left:50%;
                        transform:translate(-50%,-50%);
                        width:24px; height:24px; border-radius:50%;
                        background:${color}44;
                        animation: pulseRing 2s ease-out infinite;
                        animation-delay:${index * 0.3}s;
                    "></div>` : ''}
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 42" width="32" height="42">
                        <defs>
                            <filter id="shadow${index}" x="-30%" y="-20%" width="160%" height="160%">
                                <feDropShadow dx="0" dy="3" stdDeviation="3" flood-color="${color}" flood-opacity="0.4"/>
                            </filter>
                        </defs>
                        <!-- Teardrop body -->
                        <path d="M16 2 C8.268 2 2 8.268 2 16 C2 26 16 40 16 40 C16 40 30 26 30 16 C30 8.268 23.732 2 16 2 Z"
                              fill="${color}" filter="url(#shadow${index})"/>
                        <!-- Inner white circle -->
                        <circle cx="16" cy="16" r="9" fill="white" opacity="0.95"/>
                        <!-- Health cross -->
                        <rect x="14.5" y="10" width="3" height="12" rx="1.5" fill="${color}"/>
                        <rect x="10"   y="14.5" width="12" height="3" rx="1.5" fill="${color}"/>
                    </svg>
                </div>
            `,
            iconSize:    [32, 42],
            iconAnchor:  [16, 40],
            popupAnchor: [0, -42]
        });
    }

    // GeoJSON boundary
    fetch('<?= base_url("assets/geojson/north_waziristan.json") ?>')
        .then(function(r){ return r.json(); })
        .then(function(geojsonData) {
            // Outer glow
            L.geoJSON(geojsonData, {
                style: {
                    color: '#ff7800',
                    weight: 10,
                    opacity: 0.08,
                    fill: false
                }
            }).addTo(map);
            // Main boundary
            L.geoJSON(geojsonData, {
                style: {
                    color: '#ff7800',
                    weight: 2.5,
                    opacity: 0.75,
                    fillColor: '#ff9a44',
                    fillOpacity: 0.07
                }
            }).addTo(map);

            var nwBounds = L.geoJSON(geojsonData).getBounds();
            map.fitBounds(nwBounds, { padding: [20, 20] });
        });

    var facilities = <?= json_encode($facilities) ?>;

    facilities.forEach(function(facility, index) {
        if (!facility.latitude || !facility.longitude) return;

        var lat = parseFloat(facility.latitude);
        var lng = parseFloat(facility.longitude);

        setTimeout(function() {
            var marker = L.marker([lat, lng], {
                icon: makeMarker(index),
                riseOnHover: true
            }).addTo(map);

            // Build popup
            var name = facility.facility_name || 'Health Facility';
            var uc   = facility.uc_name       || '';
            var skip = ['latitude','longitude','facility_name','uc_name'];

            var rows = '';
            for (var key in facility) {
                if (facility[key] && skip.indexOf(key) === -1) {
                    var label = key.replace(/_/g,' ')
                                   .replace(/\b\w/g, function(l){ return l.toUpperCase(); });
                    rows += `<div class="popup-row">
                                <span class="popup-row-label">${label}</span>
                                <span>${facility[key]}</span>
                             </div>`;
                }
            }

            var popupHTML = `
                <div style="min-width:230px; font-family:sans-serif;">
                    <div class="popup-header">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="
                                width:30px; height:30px;
                                background:rgba(255,255,255,0.15);
                                border-radius:8px;
                                display:flex; align-items:center; justify-content:center;
                                flex-shrink:0;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24">
                                    <rect x="10.5" y="5" width="3" height="14" rx="1.5" fill="white"/>
                                    <rect x="5"    y="10.5" width="14" height="3" rx="1.5" fill="white"/>
                                </svg>
                            </div>
                            <div>
                                <div class="popup-header-title">${name}</div>
                                ${uc ? `<div class="popup-header-sub">${uc}</div>` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="popup-body">${rows}</div>
                </div>`;

            marker.bindPopup(popupHTML, {
                className: 'custom-popup',
                maxWidth:  280
            });

            marker.on('mouseover', function(){ this.openPopup(); });
            marker.on('click', function(){
                map.flyTo([lat, lng], 15, { animate:true, duration:1.0 });
            });

        }, index * 130);
    });

    setTimeout(function(){ map.invalidateSize(); }, 300);
});
</script>