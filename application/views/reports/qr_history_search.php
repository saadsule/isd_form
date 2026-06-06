<?php
// ══════════════════════════════════════════════════════════════════════════
//  views/reports/qr_history_search.php
// ══════════════════════════════════════════════════════════════════════════

$records  = isset($records)  ? $records  : array();
$qr_code  = isset($qr_code)  ? $qr_code  : '';
$searched = isset($searched) ? $searched : false;
$total    = count($records);

function age_display_qr($row) {
    $parts = array();
    if (!empty($row['age_year'])  && $row['age_year']  > 0) $parts[] = $row['age_year']  . 'y';
    if (!empty($row['age_month']) && $row['age_month'] > 0) $parts[] = $row['age_month'] . 'm';
    if (!empty($row['age_day'])   && $row['age_day']   > 0) $parts[] = $row['age_day']   . 'd';
    return !empty($parts) ? implode(' ', $parts) : '—';
}

// ── EPI Schedule ──────────────────────────────────────────────────────────
$epi_schedule = array(
    array(
        'visit'    => 'At Birth',
        'due_days' => 0,
        'due_label'=> 'At Birth',
        'vaccines' => array('BCG', 'OPV-0', 'Hep-B'),
    ),
    array(
        'visit'    => '2nd Visit',
        'due_days' => 42,
        'due_label'=> '6 Weeks',
        'vaccines' => array('OPV-I', 'Pneumococcal-I', 'Rotavirus-I', 'Pentavalent-I'),
    ),
    array(
        'visit'    => '3rd Visit',
        'due_days' => 70,
        'due_label'=> '10 Weeks',
        'vaccines' => array('OPV-II', 'Pneumococcal-II', 'Rotavirus-II', 'Pentavalent-II'),
    ),
    array(
        'visit'    => '4th Visit',
        'due_days' => 98,
        'due_label'=> '14 Weeks',
        'vaccines' => array('OPV-III', 'Pneumococcal-III', 'IPV-I', 'Pentavalent-III'),
    ),
    array(
        'visit'    => '5th Visit',
        'due_days' => 273,
        'due_label'=> '9 Months',
        'vaccines' => array('MR-I', 'Typhoid', 'IPV-II'),
    ),
    array(
        'visit'    => '6th Visit',
        'due_days' => 456,
        'due_label'=> '15 Months',
        'vaccines' => array('MR-II'),
    ),
);

// ── Vaccine name normalizer ───────────────────────────────────────────────
$vaccine_aliases = array(
    'bcg'                   => 'BCG',
    'opv0'                  => 'OPV-0',
    'opv-0'                 => 'OPV-0',
    'opv 0'                 => 'OPV-0',
    'opvi'                  => 'OPV-I',
    'opv-1'                 => 'OPV-I',
    'opv 1'                 => 'OPV-I',
    'opv-i'                 => 'OPV-I',
    'opvii'                 => 'OPV-II',
    'opv-2'                 => 'OPV-II',
    'opv 2'                 => 'OPV-II',
    'opv-ii'                => 'OPV-II',
    'opviii'                => 'OPV-III',
    'opv-3'                 => 'OPV-III',
    'opv 3'                 => 'OPV-III',
    'opv-iii'               => 'OPV-III',
    'hepb'                  => 'Hep-B',
    'hep-b'                 => 'Hep-B',
    'hep b'                 => 'Hep-B',
    'hepatitisb'            => 'Hep-B',
    'hepatitis-b'           => 'Hep-B',
    'pentavalent1'          => 'Pentavalent-I',
    'pentavalent-1'         => 'Pentavalent-I',
    'pentavalenți'          => 'Pentavalent-I',
    'pentavalent i'         => 'Pentavalent-I',
    'pentavalenti'          => 'Pentavalent-I',
    'penta1'                => 'Pentavalent-I',
    'penta-1'               => 'Pentavalent-I',
    'penta i'               => 'Pentavalent-I',
    'pentai'                => 'Pentavalent-I',
    'pentavalent2'          => 'Pentavalent-II',
    'pentavalent-2'         => 'Pentavalent-II',
    'pentavalent ii'        => 'Pentavalent-II',
    'pentavalentii'         => 'Pentavalent-II',
    'penta2'                => 'Pentavalent-II',
    'penta-2'               => 'Pentavalent-II',
    'penta ii'              => 'Pentavalent-II',
    'pentaii'               => 'Pentavalent-II',
    'pentavalent3'          => 'Pentavalent-III',
    'pentavalent-3'         => 'Pentavalent-III',
    'pentavalent iii'       => 'Pentavalent-III',
    'pentavalentiii'        => 'Pentavalent-III',
    'penta3'                => 'Pentavalent-III',
    'penta-3'               => 'Pentavalent-III',
    'penta iii'             => 'Pentavalent-III',
    'pentaiii'              => 'Pentavalent-III',
    'pcv1'                  => 'Pneumococcal-I',
    'pcv-1'                 => 'Pneumococcal-I',
    'pcv i'                 => 'Pneumococcal-I',
    'pcvi'                  => 'Pneumococcal-I',
    'pneumococcal1'         => 'Pneumococcal-I',
    'pneumococcal-1'        => 'Pneumococcal-I',
    'pneumococcal i'        => 'Pneumococcal-I',
    'pneumococcali'         => 'Pneumococcal-I',
    'pcv2'                  => 'Pneumococcal-II',
    'pcv-2'                 => 'Pneumococcal-II',
    'pcv ii'                => 'Pneumococcal-II',
    'pcvii'                 => 'Pneumococcal-II',
    'pneumococcal2'         => 'Pneumococcal-II',
    'pneumococcal-2'        => 'Pneumococcal-II',
    'pneumococcal ii'       => 'Pneumococcal-II',
    'pneumococcalii'        => 'Pneumococcal-II',
    'pcv3'                  => 'Pneumococcal-III',
    'pcv-3'                 => 'Pneumococcal-III',
    'pcv iii'               => 'Pneumococcal-III',
    'pcviii'                => 'Pneumococcal-III',
    'pneumococcal3'         => 'Pneumococcal-III',
    'pneumococcal-3'        => 'Pneumococcal-III',
    'pneumococcal iii'      => 'Pneumococcal-III',
    'pneumococcaliii'       => 'Pneumococcal-III',
    'rotavirus1'            => 'Rotavirus-I',
    'rotavirus-1'           => 'Rotavirus-I',
    'rotavirus i'           => 'Rotavirus-I',
    'rotavirusi'            => 'Rotavirus-I',
    'rota1'                 => 'Rotavirus-I',
    'rota-1'                => 'Rotavirus-I',
    'rota i'                => 'Rotavirus-I',
    'rotai'                 => 'Rotavirus-I',
    'rotavirus2'            => 'Rotavirus-II',
    'rotavirus-2'           => 'Rotavirus-II',
    'rotavirus ii'          => 'Rotavirus-II',
    'rotavirusii'           => 'Rotavirus-II',
    'rota2'                 => 'Rotavirus-II',
    'rota-2'                => 'Rotavirus-II',
    'rota ii'               => 'Rotavirus-II',
    'rotaii'                => 'Rotavirus-II',
    'ipv1'                  => 'IPV-I',
    'ipv-1'                 => 'IPV-I',
    'ipv i'                 => 'IPV-I',
    'ipvi'                  => 'IPV-I',
    'ipv2'                  => 'IPV-II',
    'ipv-2'                 => 'IPV-II',
    'ipv ii'                => 'IPV-II',
    'ipvii'                 => 'IPV-II',
    'mr1'                   => 'MR-I',
    'mr-1'                  => 'MR-I',
    'mr i'                  => 'MR-I',
    'mri'                   => 'MR-I',
    'measlesrubella1'       => 'MR-I',
    'measles rubella 1'     => 'MR-I',
    'mr2'                   => 'MR-II',
    'mr-2'                  => 'MR-II',
    'mr ii'                 => 'MR-II',
    'mrii'                  => 'MR-II',
    'measlesrubella2'       => 'MR-II',
    'measles rubella 2'     => 'MR-II',
    'typhoid'               => 'Typhoid',
    'typhi'                 => 'Typhoid',
    'typhoidvaccine'        => 'Typhoid',
);

function normalize_vaccine($raw, $aliases) {
    $key = strtolower(trim(preg_replace('/\s+/', ' ', $raw)));
    if (isset($aliases[$key])) return $aliases[$key];
    $stripped = preg_replace('/[\s\-_]/', '', $key);
    if (isset($aliases[$stripped])) return $aliases[$stripped];
    return $raw;
}

function vaccine_status_v2($schedule_name, $normalized_given, $due_days, $child_age_days) {
    $norm_target = strtolower(preg_replace('/[\s\-_]/', '', $schedule_name));
    foreach ($normalized_given as $g) {
        $gn = strtolower(preg_replace('/[\s\-_]/', '', $g));
        if ($gn === $norm_target) return 'given';
    }
    if ($child_age_days >= $due_days) return 'missed';
    return 'future';
}
?>

<style>
/* ═══════════════════════════════════════════════
   SEARCH BOX
═══════════════════════════════════════════════ */
.qr-search-box {
    background: #fff;
    border: 1px solid #e3e8ef;
    border-radius: 10px;
    padding: 22px 28px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.qr-search-box .input-group { max-width: 520px; }
.qr-search-box input[type="text"] {
    border-radius: 8px 0 0 8px !important;
    border-right: none;
    font-size: 15px;
    padding: 10px 16px;
    height: 44px;
    border-color: #ced4da;
}
.qr-search-box input:focus { box-shadow: none; border-color: #2980b9; }
.qr-search-box .btn-search {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    color: #fff;
    border: none;
    border-radius: 0 8px 8px 0 !important;
    padding: 0 22px;
    font-size: 14px;
    font-weight: 600;
    height: 44px;
}
.qr-search-box .btn-search:hover { opacity: .9; }
.qr-system-note {
    background: #fffbea;
    border: 1px solid #f0d060;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: .80rem;
    color: #7d6608;
    margin-top: 12px;
    max-width: 680px;
}

/* ═══════════════════════════════════════════════
   PATIENT PROFILE CARD
═══════════════════════════════════════════════ */
.patient-card {
    background: #fff;
    border: 1px solid #e3e8ef;
    border-radius: 10px;
    padding: 20px 24px;
    margin-bottom: 18px;
    display: flex;
    gap: 18px;
    align-items: center;
    flex-wrap: wrap;
}
/* Avatar circle */
.pc-avatar {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    flex-shrink: 0;
    background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    letter-spacing: 1px;
}
/* Main info */
.pc-main { flex: 1; min-width: 200px; }
.pc-main .p-name  { font-size: 20px; font-weight: 700; color: #2c3e50; margin-bottom: 5px; }
.pc-main .p-meta  {
    font-size: 12px;
    color: #7a8a9a;
    display: flex;
    flex-wrap: wrap;
    gap: 4px 14px;
    margin-bottom: 8px;
}
.pc-main .p-meta span { display: inline-flex; align-items: center; gap: 4px; }
.pc-badges { display: flex; flex-wrap: wrap; gap: 6px; }
.p-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #f4f6f9;
    border: 1px solid #e3e8ef;
    border-radius: 6px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    color: #5a6a7a;
}
/* Stats column */
.pc-stats {
    display: flex;
    flex-direction: column;
    gap: 10px;
    border-left: 1px solid #e3e8ef;
    padding-left: 20px;
    min-width: 100px;
}
.pc-stat-item { text-align: center; }
.pc-stat-item .num { font-size: 28px; font-weight: 700; color: #2c3e50; line-height: 1; }
.pc-stat-item .lbl { font-size: 10px; text-transform: uppercase; letter-spacing: .6px; color: #8a9ab0; margin-top: 2px; }
/* QR column */
.pc-qr {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 7px;
    border-left: 1px solid #e3e8ef;
    padding-left: 18px;
    min-width: 96px;
}
.pc-qr img,
.pc-qr-fallback {
    width: 80px;
    height: 80px;
    border: 1px solid #dde3ee;
    border-radius: 6px;
    object-fit: contain;
}
.pc-qr-fallback {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f9fafb;
}
.pc-qr small {
    font-size: 10px;
    color: #8a9ab0;
    text-align: center;
    word-break: break-all;
    max-width: 84px;
}

/* ═══════════════════════════════════════════════
   VISIT HISTORY TABLE
═══════════════════════════════════════════════ */
/* Uniform visit tag — same color for registration & follow-up */
.visit-tag {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #eef2ff;
    color: #2d3585;
    border: 1px solid #c5cdf8;
    border-radius: 4px;
    padding: 4px 10px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}

.qr-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.qr-table thead th {
    color: #fff !important;
    font-size: .66rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 10px 8px;
    border: none !important;
    white-space: nowrap;
    vertical-align: middle;
}
.qr-table tbody td {
    font-size: .79rem;
    vertical-align: middle;
    padding: 8px;
    border-color: #edf0f4 !important;
}
.qr-table tbody tr:hover { background: #f5f8fc; }

/* Remove old left-border row colors */
.visit-row-reg,
.visit-row-fu { border-left: none !important; }

.vacc-pill-sm {
    display: inline-block;
    margin: 1px 2px;
    padding: 1px 7px;
    border-radius: 4px;
    font-size: .63rem;
    font-weight: 600;
    background: #e8f4fd;
    color: #1a5276;
    white-space: nowrap;
}
.vacc-none { color: #ccc; font-size: .75rem; }
.qr-empty  { text-align: center; padding: 60px 20px; color: #8a9ab0; }
.qr-empty i { font-size: 48px; margin-bottom: 16px; display: block; }

/* ═══════════════════════════════════════════════
   EPI / VACCINATION CARD SECTION
═══════════════════════════════════════════════ */
.vacc-schedule-section { margin-top: 28px; }

.vacc-schedule-section .sec-header {
    background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 100%);
    border-radius: 12px 12px 0 0;
    padding: 16px 22px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.vacc-schedule-section .sec-header h5 {
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    margin: 0;
}
.vacc-schedule-section .sec-header small {
    color: rgba(255,255,255,.75);
    font-size: 12px;
}
.vacc-schedule-section .sec-body {
    background: #fff;
    border: 1px solid #e3e8ef;
    border-top: none;
    border-radius: 0 0 12px 12px;
    overflow: hidden;
}

/* Legend */
.vac-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 14px;
    padding: 12px 20px;
    border-bottom: 1px solid #edf0f4;
    background: #fafbfc;
}
.vac-legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #555; }
.vac-legend-dot  { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }

/* Schedule table */
.sch-table { width: 100%; border-collapse: collapse; }
.sch-table thead tr { background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 100%); }
.sch-table thead th {
    color: #fff !important;
    font-size: .66rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 10px 14px;
    border: none !important;
    white-space: nowrap;
    vertical-align: middle;
}
.sch-table tbody td {
    padding: 13px 14px;
    border-bottom: 1px solid #edf0f4;
    vertical-align: middle;
}
.sch-table tbody tr:last-child td { border-bottom: none; }
.sch-table tbody tr:hover { background: #f8f9ff; }

/* Visit label — single uniform color, sharp corners */
.visit-label-badge {
    font-weight: 700;
    font-size: 12px;
    color: #fff;
    padding: 5px 13px;
    border-radius: 4px;      /* sharp, not pill */
    display: inline-block;
    white-space: nowrap;
    background: #1a1f4e;     /* one color for all visits */
}

/* Due-age chips */
.age-chip {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
}
/* Current age visit = green */
.age-chip-current {
    background: #eaf6f0;
    color: #1a7a4a;
    border: 1px solid #a8dfc0;
}
/* All other visits = gray */
.age-chip-gray {
    background: #f4f6f9;
    color: #8a9ab0;
    border: 1px solid #e3e8ef;
}

/* Visit status tags */
.vl-status-tag {
    display: inline-block;
    font-size: 10px;
    font-weight: 700;
    padding: 3px 9px;
    border-radius: 4px;
}
.vl-done    { background: #eaf6f0; color: #1a7a4a; }
.vl-partial { background: #fef9e0; color: #9a6e00; }
.vl-missed  { background: #fdf2f2; color: #922b21; }
.vl-future  { background: #f0f2f5; color: #7a8a9a; }

/* Vaccine pills in schedule */
.sch-pills { display: flex; flex-wrap: wrap; gap: 7px; }
.sch-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 5px 14px;
    border-radius: 4px;      /* sharp corners */
    font-size: 13px;
    font-weight: 700;
    white-space: nowrap;
    border: 1.5px solid transparent;
}
.pill-given  { background: #eaf6f0; color: #1a6b3c; border-color: #a8dfc0; }
.pill-missed { background: #fdf2f2; color: #922b21; border-color: #f5b8b8; }
.pill-future { background: #f0f2f5; color: #7a8a9a; border-color: #d8dde4; }

/* Bottom note */
.vacc-note {
    padding: 12px 20px;
    border-top: 1px solid #edf0f4;
    background: #fafbfc;
    font-size: 11px;
    color: #7a8a9a;
    display: flex;
    align-items: flex-start;
    gap: 7px;
}
.vacc-note i { margin-top: 1px; flex-shrink: 0; }

@media print { .no-print { display: none !important; } }
</style>

<div class="page-container">
<div class="main-content">

<!-- PAGE HEADER -->
<div class="page-header">
    <h2><i class="fa fa-qrcode text-primary"></i> QR Code History Search</h2>
    <p class="text-muted mb-0">Enter a QR code to view complete visit history for that child</p>
    <a href="javascript:history.back();" class="btn btn-sm mt-2"
       style="background:#0f1c3f; color:#fff; border-radius:7px; padding:6px 16px; font-weight:600;">
        <i class="fa fa-arrow-left mr-1"></i> Back
    </a>
</div>

<!-- SEARCH BOX -->
<div class="qr-search-box">
    <form method="GET" action="<?= current_url() ?>">
        <label style="font-weight:600; font-size:14px; margin-bottom:10px; display:block; color:#2c3e50;">
            <i class="fa fa-search"></i> Search by QR Code
        </label>
        <div class="input-group">
            <input type="text" name="qr_code" class="form-control"
                   placeholder="Enter QR Code e.g. CHD-00123"
                   value="<?= htmlspecialchars($qr_code) ?>"
                   autocomplete="off" autofocus />
            <div class="input-group-append">
                <button type="submit" class="btn btn-search">
                    <i class="fa fa-search"></i> Search
                </button>
            </div>
        </div>
        <?php if ($searched && $total === 0): ?>
            <small class="text-danger mt-2 d-block">
                <i class="fa fa-exclamation-circle"></i>
                No records found for "<strong><?= htmlspecialchars($qr_code) ?></strong>"
            </small>
        <?php endif; ?>
    </form>
    <div class="qr-system-note">
        <i class="fa fa-info-circle"></i>
        <strong>Note:</strong>
        This report considers a single QR code as a unique child regardless of any technical
        mistake made during digitization.
    </div>
</div>

<?php if ($searched && $total > 0):
    $first       = $records[0];
    $fu_count    = $total - 1;
    $dob_display = !empty($first['dob']) ? date('d M Y', strtotime($first['dob'])) : '—';
    $age_display = age_display_qr($first);

    // ── Avatar initials (first 2 chars of name) ──────────────────────────
    $initials = strtoupper(substr(trim($first['patient_name']), 0, 2));

    // ── Calculate child age in days ──────────────────────────────────────
    $child_age_days = 0;
    if (!empty($first['age_year']) || !empty($first['age_month']) || !empty($first['age_day'])) {
        $child_age_days = (int)($first['age_year']  * 365)
                        + (int)($first['age_month'] * 30)
                        + (int)($first['age_day']);
    } elseif (!empty($first['dob']) && $first['dob'] !== '0000-00-00' && !empty($first['form_date'])) {
        $child_age_days = (int) floor(
            (strtotime($first['form_date']) - strtotime($first['dob'])) / 86400
        );
    } elseif (!empty($first['dob']) && $first['dob'] !== '0000-00-00') {
        $child_age_days = (int) floor((time() - strtotime($first['dob'])) / 86400);
    }

    // ── Collect all vaccines given across all visits ──────────────────────
    $all_given_raw = array();
    foreach ($records as $rec) {
        if (!empty($rec['vaccinations'])) {
            foreach ($rec['vaccinations'] as $v) {
                $all_given_raw[] = $v;
            }
        }
    }
    $all_given_normalized = array();
    foreach ($all_given_raw as $raw) {
        $all_given_normalized[] = normalize_vaccine($raw, $vaccine_aliases);
    }

    // ── Find the "current" visit index — closest due_days to child_age_days ──
    $current_visit_idx = 0;
    $min_diff = PHP_INT_MAX;
    foreach ($epi_schedule as $idx => $vd) {
        $diff = abs($child_age_days - $vd['due_days']);
        if ($diff < $min_diff) {
            $min_diff          = $diff;
            $current_visit_idx = $idx;
        }
    }
?>

<!-- ══ PATIENT PROFILE CARD ══ -->
<div class="patient-card">

    <!-- Avatar -->
    <div class="pc-avatar"><?= htmlspecialchars($initials) ?></div>

    <!-- Main info -->
    <div class="pc-main">
        <div class="p-name"><?= htmlspecialchars($first['patient_name']) ?></div>
        <div class="p-meta">
            <span><i class="fa fa-user-o"></i> Guardian: <?= htmlspecialchars($first['guardian_name'] ?: '—') ?></span>
            <span><i class="fa fa-birthday-cake"></i> DOB: <?= $dob_display ?></span>
            <span><i class="fa fa-child"></i> Age: <?= $age_display ?> (<?= $child_age_days ?> days)</span>
            <span><i class="fa fa-venus-mars"></i> <?= ucfirst($first['gender'] ?: '—') ?></span>
        </div>
        <div class="pc-badges">
            <span class="p-badge"><i class="fa fa-qrcode"></i> <?= htmlspecialchars($first['qr_code']) ?></span>
            <span class="p-badge"><i class="fa fa-map-marker"></i> <?= htmlspecialchars($first['uc_name'] ?: '—') ?></span>
            <span class="p-badge"><i class="fa fa-home"></i> <?= htmlspecialchars($first['village'] ?: '—') ?></span>
        </div>
    </div>

    <!-- Visit stats -->
    <div class="pc-stats">
        <div class="pc-stat-item">
            <div class="num"><?= $total ?></div>
            <div class="lbl">Total Visits</div>
        </div>
        <div class="pc-stat-item">
            <div class="num"><?= $fu_count ?></div>
            <div class="lbl">Follow Ups</div>
        </div>
    </div>

    <!-- QR Code image -->
    <div class="pc-qr">
        <?php if (!empty($first['qr_code'])): ?>
            <img
                src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode($first['qr_code']) ?>"
                alt="QR Code"
                style="width:80px; height:80px; border:1px solid #dde3ee; border-radius:6px;">
        <?php endif; ?>
        <small><?= htmlspecialchars($first['qr_code']) ?></small>
    </div>

</div><!-- /.patient-card -->


<!-- ══ VISIT HISTORY TABLE ══ -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fa fa-history text-muted"></i>
            Visit History
            <span class="badge badge-default ml-2"><?= $total ?> visits</span>
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 qr-table">
                <thead>
                    <tr>
                        <th width="30">#</th>
                        <th width="130">Visit Type</th>
                        <th width="100">Client Type</th>
                        <th width="105">Date</th>
                        <th width="85">Age / Group</th>
                        <th>Vaccinator</th>
                        <th width="95">UC</th>
                        <th width="85">Village</th>
                        <th>Vaccinations Given</th>
                        <th width="55" class="text-center">View</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($records as $i => $row):
                    $is_reg = ($row['visit_number'] == 1);
                    $given  = isset($row['vaccinations']) ? $row['vaccinations'] : array();
                ?>
                <tr>
                    <td class="text-muted"><?= $row['visit_number'] ?></td>
                    <td>
                        <?php if ($is_reg): ?>
                            <span class="visit-tag"><i class="fa fa-plus-circle"></i> Registration</span>
                        <?php else: ?>
                            <span class="visit-tag"><i class="fa fa-refresh"></i> Follow Up <?= ($row['visit_number'] - 1) ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars(!empty($row['client_type']) ? $row['client_type'] : '—') ?></td>
                    <td>
                        <strong><?= !empty($row['form_date']) ? date('d M Y', strtotime($row['form_date'])) : '—' ?></strong><br>
                        <small class="text-muted" style="font-size:.65rem;">
                            <?= !empty($row['created_at']) ? date('d M, h:i A', strtotime($row['created_at'])) : '' ?>
                        </small>
                    </td>
                    <td>
                        <span style="font-size:.78rem;"><?= age_display_qr($row) ?></span><br>
                        <span class="badge badge-light" style="font-size:.65rem;">
                            <?= htmlspecialchars(!empty($row['age_group']) ? $row['age_group'] : '—') ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars(!empty($row['vaccinator_name']) ? $row['vaccinator_name'] : '—') ?></td>
                    <td><?= htmlspecialchars(!empty($row['uc_name'])         ? $row['uc_name']         : '—') ?></td>
                    <td><?= htmlspecialchars(!empty($row['village'])         ? $row['village']         : '—') ?></td>
                    <td>
                        <?php if (!empty($given)): ?>
                            <?php foreach ($given as $v): ?>
                                <span class="vacc-pill-sm"><?= htmlspecialchars($v) ?></span>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <span class="vacc-none">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-center">
                        <a href="<?= base_url('forms/view_child_health/' . $row['master_id']) ?>"
                           class="btn btn-sm btn-primary" title="View full form">
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- ══ VACCINATION CARD ══ -->
<div class="vacc-schedule-section">

    <div class="sec-header">
        <h5><i class="fa fa-id-card-o"></i> &nbsp;Vaccination Card of <?= htmlspecialchars($first['patient_name']) ?></h5>
        <small>
            Child age at registration: <?= $age_display ?> (<?= $child_age_days ?> days)
            &nbsp;|&nbsp; Schedule status based on registration age
        </small>
    </div>

    <div class="sec-body">

        <!-- Legend -->
        <div class="vac-legend">
            <div class="vac-legend-item">
                <span class="vac-legend-dot" style="background:#27ae60;"></span>
                Given
            </div>
            <div class="vac-legend-item">
                <span class="vac-legend-dot" style="background:#e74c3c;"></span>
                Missed (age passed, not given)
            </div>
            <div class="vac-legend-item">
                <span class="vac-legend-dot" style="background:#b0bec5;"></span>
                Upcoming (not yet due)
            </div>
            <div class="vac-legend-item">
                <span style="display:inline-block;width:12px;height:12px;border-radius:3px;
                             background:#eaf6f0;border:1px solid #a8dfc0;"></span>
                Current age visit
            </div>
        </div>

        <!-- Schedule Table -->
        <div class="table-responsive">
        <table class="sch-table">
            <thead>
                <tr>
                    <th style="width:130px;">Visit</th>
                    <th style="width:130px;">Due Age</th>
                    <th style="width:100px;" class="text-center">Status</th>
                    <th>Vaccines</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($epi_schedule as $visit_idx => $visit_def):

                // Per-visit counts
                $v_given = 0; $v_missed = 0; $v_future = 0;
                foreach ($visit_def['vaccines'] as $vacc_name) {
                    $st = vaccine_status_v2($vacc_name, $all_given_normalized, $visit_def['due_days'], $child_age_days);
                    if ($st === 'given')  $v_given++;
                    if ($st === 'missed') $v_missed++;
                    if ($st === 'future') $v_future++;
                }
                $v_total = count($visit_def['vaccines']);

                // Status tag
                if ($v_given === $v_total) {
                    $status_tag = '<span class="vl-status-tag vl-done"><i class="fa fa-check-circle"></i> Complete</span>';
                } elseif ($v_future === $v_total) {
                    $status_tag = '<span class="vl-status-tag vl-future"><i class="fa fa-clock-o"></i> Upcoming</span>';
                } elseif ($v_missed > 0 && $v_given === 0) {
                    $status_tag = '<span class="vl-status-tag vl-missed"><i class="fa fa-times-circle"></i> Missed</span>';
                } elseif ($v_missed > 0) {
                    $status_tag = '<span class="vl-status-tag vl-partial"><i class="fa fa-exclamation-circle"></i> Partial</span>';
                } else {
                    $status_tag = '<span class="vl-status-tag vl-partial"><i class="fa fa-dot-circle-o"></i> Partial</span>';
                }

                // Is this the current age visit?
                $is_current_visit = ($visit_idx === $current_visit_idx);
                $age_chip_class   = $is_current_visit ? 'age-chip age-chip-current' : 'age-chip age-chip-gray';
            ?>
            <tr <?= $is_current_visit ? 'style="background:#f6fdf8;"' : '' ?>>
                <td>
                    <span class="visit-label-badge">
                        <?= htmlspecialchars($visit_def['visit']) ?>
                    </span>
                </td>
                <td>
                    <span class="<?= $age_chip_class ?>">
                        <?= htmlspecialchars($visit_def['due_label']) ?>
                    </span>
                </td>
                <td class="text-center">
                    <?= $status_tag ?>
                </td>
                <td>
                    <div class="sch-pills">
                        <?php foreach ($visit_def['vaccines'] as $vacc_name):
                            $st   = vaccine_status_v2($vacc_name, $all_given_normalized, $visit_def['due_days'], $child_age_days);
                            $icon = $st === 'given' ? '✓' : ($st === 'missed' ? '✗' : '○');
                        ?>
                            <span class="sch-pill pill-<?= $st ?>">
                                <?= $icon ?> <?= htmlspecialchars($vacc_name) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <!-- Bottom note -->
        <div class="vacc-note">
            <i class="fa fa-info-circle"></i>
            <span>
                <strong>Note:</strong> The highlighted row (green due-age chip) indicates the
                visit closest to this child's age at registration. All schedule statuses
                are calculated based on the age recorded at registration, not today's date.
            </span>
        </div>

    </div><!-- /.sec-body -->
</div><!-- /.vacc-schedule-section -->

<?php elseif (!$searched): ?>
<div class="card">
    <div class="card-body qr-empty">
        <i class="fa fa-qrcode text-muted"></i>
        <h5 class="text-muted">Enter a QR code above to view visit history</h5>
        <p class="text-muted" style="font-size:13px;">
            All registration and follow-up visits for that child will appear here
        </p>
    </div>
</div>
<?php endif; ?>

</div><!-- /.main-content -->