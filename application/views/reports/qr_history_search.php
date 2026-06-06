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
        'visit'       => 'At Birth',
        'color_class' => 'v-birth',
        'due_days'    => 0,
        'due_label'   => 'At Birth',
        'vaccines'    => array('BCG', 'OPV-0', 'Hep-B'),
    ),
    array(
        'visit'       => '2nd Visit',
        'color_class' => 'v-2',
        'due_days'    => 42,
        'due_label'   => '6 Weeks',
        'vaccines'    => array('OPV-I', 'Pneumococcal-I', 'Rotavirus-I', 'Pentavalent-I'),
    ),
    array(
        'visit'       => '3rd Visit',
        'color_class' => 'v-3',
        'due_days'    => 70,
        'due_label'   => '10 Weeks',
        'vaccines'    => array('OPV-II', 'Pneumococcal-II', 'Rotavirus-II', 'Pentavalent-II'),
    ),
    array(
        'visit'       => '4th Visit',
        'color_class' => 'v-4',
        'due_days'    => 98,
        'due_label'   => '14 Weeks',
        'vaccines'    => array('OPV-III', 'Pneumococcal-III', 'IPV-I', 'Pentavalent-III'),
    ),
    array(
        'visit'       => '5th Visit',
        'color_class' => 'v-5',
        'due_days'    => 273,
        'due_label'   => '9 Months',
        'vaccines'    => array('MR-I', 'Typhoid', 'IPV-II'),
    ),
    array(
        'visit'       => '6th Visit',
        'color_class' => 'v-6',
        'due_days'    => 456,
        'due_label'   => '15 Months',
        'vaccines'    => array('MR-II'),
    ),
);

// ── Vaccine name normalizer ───────────────────────────────────────────────
// Maps many possible DB option_text values → canonical schedule name
// Add more mappings here if your DB uses different names
$vaccine_aliases = array(
    // BCG
    'bcg'                   => 'BCG',
    // OPV
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
    // Hep-B
    'hepb'                  => 'Hep-B',
    'hep-b'                 => 'Hep-B',
    'hep b'                 => 'Hep-B',
    'hepatitisb'            => 'Hep-B',
    'hepatitis-b'           => 'Hep-B',
    // Pentavalent — covers: PENTA I, Penta-1, Pentavalent-1, penta1, pentai etc
    'pentavalent1'          => 'Pentavalent-I',
    'pentavalent-1'         => 'Pentavalent-I',
    'pentavalenți'          => 'Pentavalent-I',
    'pentavalent i'         => 'Pentavalent-I',
    'pentavalenti'          => 'Pentavalent-I',
    'penta1'                => 'Pentavalent-I',
    'penta-1'               => 'Pentavalent-I',
    'penta i'               => 'Pentavalent-I',
    'pentai'                => 'Pentavalent-I',   // "PENTA I" stripped
    'pentavalent2'          => 'Pentavalent-II',
    'pentavalent-2'         => 'Pentavalent-II',
    'pentavalent ii'        => 'Pentavalent-II',
    'pentavalentii'         => 'Pentavalent-II',
    'penta2'                => 'Pentavalent-II',
    'penta-2'               => 'Pentavalent-II',
    'penta ii'              => 'Pentavalent-II',
    'pentaii'               => 'Pentavalent-II',  // "PENTA II" stripped
    'pentavalent3'          => 'Pentavalent-III',
    'pentavalent-3'         => 'Pentavalent-III',
    'pentavalent iii'       => 'Pentavalent-III',
    'pentavalentiii'        => 'Pentavalent-III',
    'penta3'                => 'Pentavalent-III',
    'penta-3'               => 'Pentavalent-III',
    'penta iii'             => 'Pentavalent-III',
    'pentaiii'              => 'Pentavalent-III', // "PENTA III" stripped
    // Pneumococcal / PCV — covers: PCV I, PCV-1, pcv1, pcvi etc
    'pcv1'                  => 'Pneumococcal-I',
    'pcv-1'                 => 'Pneumococcal-I',
    'pcv i'                 => 'Pneumococcal-I',
    'pcvi'                  => 'Pneumococcal-I',  // "PCV I" stripped
    'pneumococcal1'         => 'Pneumococcal-I',
    'pneumococcal-1'        => 'Pneumococcal-I',
    'pneumococcal i'        => 'Pneumococcal-I',
    'pneumococcali'         => 'Pneumococcal-I',
    'pcv2'                  => 'Pneumococcal-II',
    'pcv-2'                 => 'Pneumococcal-II',
    'pcv ii'                => 'Pneumococcal-II',
    'pcvii'                 => 'Pneumococcal-II', // "PCV II" stripped
    'pneumococcal2'         => 'Pneumococcal-II',
    'pneumococcal-2'        => 'Pneumococcal-II',
    'pneumococcal ii'       => 'Pneumococcal-II',
    'pneumococcalii'        => 'Pneumococcal-II',
    'pcv3'                  => 'Pneumococcal-III',
    'pcv-3'                 => 'Pneumococcal-III',
    'pcv iii'               => 'Pneumococcal-III',
    'pcviii'                => 'Pneumococcal-III',// "PCV III" stripped
    'pneumococcal3'         => 'Pneumococcal-III',
    'pneumococcal-3'        => 'Pneumococcal-III',
    'pneumococcal iii'      => 'Pneumococcal-III',
    'pneumococcaliii'       => 'Pneumococcal-III',
    // Rotavirus — covers: ROTA I, Rotavirus-1, rotai etc
    'rotavirus1'            => 'Rotavirus-I',
    'rotavirus-1'           => 'Rotavirus-I',
    'rotavirus i'           => 'Rotavirus-I',
    'rotavirusi'            => 'Rotavirus-I',
    'rota1'                 => 'Rotavirus-I',
    'rota-1'                => 'Rotavirus-I',
    'rota i'                => 'Rotavirus-I',
    'rotai'                 => 'Rotavirus-I',     // "ROTA I" stripped
    'rotavirus2'            => 'Rotavirus-II',
    'rotavirus-2'           => 'Rotavirus-II',
    'rotavirus ii'          => 'Rotavirus-II',
    'rotavirusii'           => 'Rotavirus-II',
    'rota2'                 => 'Rotavirus-II',
    'rota-2'                => 'Rotavirus-II',
    'rota ii'               => 'Rotavirus-II',
    'rotaii'                => 'Rotavirus-II',    // "ROTA II" stripped
    // IPV — covers: IPV I, IPV-1, ipvi etc
    'ipv1'                  => 'IPV-I',
    'ipv-1'                 => 'IPV-I',
    'ipv i'                 => 'IPV-I',
    'ipvi'                  => 'IPV-I',           // "IPV I" stripped
    'ipv2'                  => 'IPV-II',
    'ipv-2'                 => 'IPV-II',
    'ipv ii'                => 'IPV-II',
    'ipvii'                 => 'IPV-II',          // "IPV II" stripped
    // MR / Measles-Rubella — covers: MR I, MR-1, mri etc
    'mr1'                   => 'MR-I',
    'mr-1'                  => 'MR-I',
    'mr i'                  => 'MR-I',
    'mri'                   => 'MR-I',            // "MR I" stripped
    'measlesrubella1'       => 'MR-I',
    'measles rubella 1'     => 'MR-I',
    'mr2'                   => 'MR-II',
    'mr-2'                  => 'MR-II',
    'mr ii'                 => 'MR-II',
    'mrii'                  => 'MR-II',           // "MR II" stripped
    'measlesrubella2'       => 'MR-II',
    'measles rubella 2'     => 'MR-II',
    // OPV extra variants
    'opv0'                  => 'OPV-0',
    'opvi'                  => 'OPV-I',           // "OPV I" stripped (already there but confirm)
    'opvii'                 => 'OPV-II',
    'opviii'                => 'OPV-III',
    // Typhoid
    'typhoid'               => 'Typhoid',
    'typhi'                 => 'Typhoid',
    'typhoidvaccine'        => 'Typhoid',
);

/**
 * Normalize a raw DB vaccine name to canonical schedule name
 * e.g. "OPV-1" → "OPV-I", "PCV-1" → "Pneumococcal-I"
 */
function normalize_vaccine($raw, $aliases) {
    $key = strtolower(trim(preg_replace('/\s+/', ' ', $raw)));
    // Direct alias match
    if (isset($aliases[$key])) return $aliases[$key];
    // Remove spaces/hyphens/underscores and try again
    $stripped = preg_replace('/[\s\-_]/', '', $key);
    if (isset($aliases[$stripped])) return $aliases[$stripped];
    // Return original if no match (for display)
    return $raw;
}

/**
 * Get vaccine status:
 *   given   → found in records
 *   missed  → age has passed the due date, not given
 *   future  → age has not yet reached due date
 *
 * IMPORTANT: uses actual child age in days (not today's date alone)
 */
function vaccine_status_v2($schedule_name, $normalized_given, $due_days, $child_age_days) {
    $norm_target = strtolower(preg_replace('/[\s\-_]/', '', $schedule_name));
    foreach ($normalized_given as $g) {
        $gn = strtolower(preg_replace('/[\s\-_]/', '', $g));
        if ($gn === $norm_target) return 'given';
    }
    // Not given — only mark missed if child's age >= due date
    if ($child_age_days >= $due_days) return 'missed';
    return 'future';
}
?>

<style>
/* ── Search Box ── */
.qr-search-box {
    background: #fff; border: 1px solid #e3e8ef; border-radius: 10px;
    padding: 22px 28px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,.04);
}
.qr-search-box .input-group { max-width: 520px; }
.qr-search-box input[type="text"] {
    border-radius: 8px 0 0 8px !important; border-right: none;
    font-size: 15px; padding: 10px 16px; height: 44px; border-color: #ced4da;
}
.qr-search-box input:focus { box-shadow: none; border-color: #2980b9; }
.qr-search-box .btn-search {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    color: #fff; border: none; border-radius: 0 8px 8px 0 !important;
    padding: 0 22px; font-size: 14px; font-weight: 600; height: 44px;
}
.qr-search-box .btn-search:hover { opacity: .9; }
.qr-system-note {
    background: #fffbea; border: 1px solid #f0d060; border-radius: 8px;
    padding: 10px 16px; font-size: .80rem; color: #7d6608;
    margin-top: 12px; max-width: 680px;
}

/* ── Patient Card ── */
.patient-card {
    background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%);
    border-radius: 10px; padding: 18px 24px; margin-bottom: 18px; color: #fff;
}
.patient-card .p-name  { font-size: 20px; font-weight: 700; margin-bottom: 4px; }
.patient-card .p-meta  { font-size: 12px; opacity: .8; margin-bottom: 0; }
.patient-card .p-badge {
    display: inline-block; background: rgba(255,255,255,.18); border-radius: 20px;
    padding: 3px 12px; font-size: 11px; font-weight: 600; margin-top: 7px;
}
.patient-card .p-stat  { text-align: center; border-left: 1px solid rgba(255,255,255,.2); padding-left: 20px; }
.patient-card .p-stat .num { font-size: 28px; font-weight: 700; }
.patient-card .p-stat .lbl { font-size: 11px; opacity: .75; text-transform: uppercase; letter-spacing: .5px; }

/* ── Visit History Table ── */
.visit-row-reg { border-left: 4px solid #27ae60 !important; }
.visit-row-fu  { border-left: 4px solid #2980b9 !important; }
.visit-badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: .68rem; font-weight: 700; white-space: nowrap; }
.badge-reg { background: #eaf6f0; color: #1a7a4a; }
.badge-fu  { background: #e8f4fd; color: #1a5276; }

.qr-table thead tr { background: linear-gradient(135deg, #2c3e50 0%, #3d5a80 100%); }
.qr-table thead th {
    color: #fff !important; font-size: .66rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px; padding: 10px 8px;
    border: none !important; white-space: nowrap; vertical-align: middle;
}
.qr-table tbody td { font-size: .79rem; vertical-align: middle; padding: 8px; border-color: #edf0f4 !important; }
.qr-table tbody tr:hover { background: #f5f8fc; }
.vacc-pill-sm {
    display: inline-block; margin: 1px 2px; padding: 1px 7px;
    border-radius: 10px; font-size: .63rem; font-weight: 600;
    background: #e8f4fd; color: #1a5276; white-space: nowrap;
}
.vacc-none { color: #ccc; font-size: .75rem; }
.qr-empty { text-align: center; padding: 60px 20px; color: #8a9ab0; }
.qr-empty i { font-size: 48px; margin-bottom: 16px; display: block; }

/* ══ EPI Vaccination Status Section ══ */
.vacc-schedule-section { margin-top: 28px; }

.vacc-schedule-section .sec-header {
    background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 100%);
    border-radius: 12px 12px 0 0;
    padding: 16px 22px;
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;
}
.vacc-schedule-section .sec-header h5 {
    color: #fff; font-weight: 700; font-size: 15px; margin: 0;
}
.vacc-schedule-section .sec-body {
    background: #fff;
    border: 1px solid #e3e8ef; border-top: none;
    border-radius: 0 0 12px 12px;
    overflow: hidden;
}

/* Progress */
.vacc-progress-wrap {
    background: #f4f6f9; padding: 14px 20px;
    display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    border-bottom: 1px solid #edf0f4;
}
.vacc-progress-bar-outer {
    flex: 1; min-width: 140px;
    background: #e3e8ef; border-radius: 20px; height: 10px; overflow: hidden;
}
.vacc-progress-bar-inner {
    height: 10px; border-radius: 20px;
    background: linear-gradient(90deg, #27ae60, #2ecc71);
}
.vacc-stat-box {
    display: inline-flex; align-items: center; gap: 5px;
    background: #fff; border: 1px solid #e3e8ef; border-radius: 8px;
    padding: 5px 12px; font-size: 12px; font-weight: 600;
}
.vacc-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }

/* Legend */
.vac-legend {
    display: flex; flex-wrap: wrap; gap: 14px;
    padding: 12px 20px; border-bottom: 1px solid #edf0f4;
    background: #fafbfc;
}
.vac-legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; color: #555; }
.vac-legend-dot  { width: 12px; height: 12px; border-radius: 50%; display: inline-block; }

/* ── Schedule Table (same style as visit history table) ── */
.sch-table { width: 100%; border-collapse: collapse; }
.sch-table thead tr { background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 100%); }
.sch-table thead th {
    color: #fff !important; font-size: .66rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    padding: 10px 14px; border: none !important;
    white-space: nowrap; vertical-align: middle;
}
.sch-table tbody td {
    padding: 13px 14px; border-bottom: 1px solid #edf0f4;
    vertical-align: middle;
}
.sch-table tbody tr:last-child td { border-bottom: none; }
.sch-table tbody tr:hover { background: #f8f9ff; }

/* Visit label badge */
.visit-label-badge {
    font-weight: 700; font-size: 12px; color: #fff;
    padding: 5px 13px; border-radius: 20px;
    display: inline-block; white-space: nowrap;
}
.v-birth  { background: #e84393; }
.v-2      { background: #f07c1a; }
.v-3      { background: #29a8e0; }
.v-4      { background: #7b5ea7; }
.v-5      { background: #27ae60; }
.v-6      { background: #8e44ad; }

/* Age chip */
.age-chip {
    display: inline-block; padding: 3px 10px; border-radius: 20px;
    font-size: 11px; font-weight: 600;
    background: #eef2ff; color: #2d3585; border: 1px solid #c5cdf8;
    white-space: nowrap;
}

/* Visit status tag */
.vl-status-tag {
    display: inline-block; margin-top: 5px;
    font-size: 10px; font-weight: 700;
    padding: 2px 9px; border-radius: 20px;
}
.vl-done    { background: #eaf6f0; color: #1a7a4a; }
.vl-partial { background: #fef9e0; color: #9a6e00; }
.vl-missed  { background: #fdf2f2; color: #922b21; }
.vl-future  { background: #f0f2f5; color: #7a8a9a; }

/* Vaccine pills */
.sch-pills { display: flex; flex-wrap: wrap; gap: 7px; }
.sch-pill {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 14px; border-radius: 20px;
    font-size: 13px; font-weight: 700; white-space: nowrap;
    border: 1.5px solid transparent;
}
.pill-given  { background: #eaf6f0; color: #1a6b3c; border-color: #a8dfc0; }
.pill-missed { background: #fdf2f2; color: #922b21; border-color: #f5b8b8; }
.pill-future { background: #f0f2f5; color: #7a8a9a; border-color: #d8dde4; }

@media print { .no-print { display: none !important; } }
</style>

<div class="page-container">
<div class="main-content">

<!-- HEADER -->
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

    // ── Calculate child age in days ──────────────────────────────────────
    // Use stored age fields (age at registration) — NOT today's date from DOB.
    // Reason: DOB-to-today gives CURRENT age, but schedule status should reflect
    // the age recorded at registration time (what the health worker entered).
    // If age fields are empty, fall back to DOB-to-registration-date.
    $child_age_days = 0;
    if (!empty($first['age_year']) || !empty($first['age_month']) || !empty($first['age_day'])) {
        // Use stored age fields — most accurate for schedule matching
        $child_age_days = (int)($first['age_year']  * 365)
                        + (int)($first['age_month'] * 30)
                        + (int)($first['age_day']);
    } elseif (!empty($first['dob']) && $first['dob'] !== '0000-00-00' && !empty($first['form_date'])) {
        // Fallback: DOB to registration date (not today)
        $child_age_days = (int) floor(
            (strtotime($first['form_date']) - strtotime($first['dob'])) / 86400
        );
    } elseif (!empty($first['dob']) && $first['dob'] !== '0000-00-00') {
        // Last resort: DOB to today
        $child_age_days = (int) floor((time() - strtotime($first['dob'])) / 86400);
    }

    // ── Collect ALL vaccines given across ALL visits ─────────────────────
    $all_given_raw = array();
    foreach ($records as $rec) {
        if (!empty($rec['vaccinations'])) {
            foreach ($rec['vaccinations'] as $v) {
                $all_given_raw[] = $v;
            }
        }
    }

    // Normalize all given vaccine names using alias map
    $all_given_normalized = array();
    foreach ($all_given_raw as $raw) {
        $all_given_normalized[] = normalize_vaccine($raw, $vaccine_aliases);
    }

    // ── Stats for progress bar ───────────────────────────────────────────
    $total_vaccines = 0;
    $given_count    = 0;
    $missed_count   = 0;
    $future_count   = 0;
    foreach ($epi_schedule as $visit_def) {
        foreach ($visit_def['vaccines'] as $vacc_name) {
            $total_vaccines++;
            $st = vaccine_status_v2($vacc_name, $all_given_normalized, $visit_def['due_days'], $child_age_days);
            if ($st === 'given')  $given_count++;
            if ($st === 'missed') $missed_count++;
            if ($st === 'future') $future_count++;
        }
    }
    $progress_pct = $total_vaccines > 0 ? round(($given_count / $total_vaccines) * 100) : 0;
?>

<!-- PATIENT SUMMARY CARD -->
<div class="patient-card">
    <div class="row align-items-center">
        <div class="col-md-8">
            <div class="p-name"><?= htmlspecialchars($first['patient_name']) ?></div>
            <div class="p-meta">
                <i class="fa fa-user-o"></i> Guardian: <?= htmlspecialchars($first['guardian_name'] ?: '—') ?>
                &nbsp;|&nbsp;
                <i class="fa fa-birthday-cake"></i> DOB: <?= $dob_display ?>
                &nbsp;|&nbsp;
                <i class="fa fa-child"></i> Age: <?= $age_display ?> (<?= $child_age_days ?> days)
                &nbsp;|&nbsp;
                <i class="fa fa-venus-mars"></i> <?= ucfirst($first['gender'] ?: '—') ?>
            </div>
            <div>
                <span class="p-badge"><i class="fa fa-qrcode"></i> <?= htmlspecialchars($first['qr_code']) ?></span>
                <span class="p-badge ml-2"><i class="fa fa-map-marker"></i> <?= htmlspecialchars($first['uc_name'] ?: '—') ?></span>
                <span class="p-badge ml-2"><i class="fa fa-home"></i> <?= htmlspecialchars($first['village'] ?: '—') ?></span>
            </div>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-6 p-stat">
                    <div class="num"><?= $total ?></div>
                    <div class="lbl">Total Visits</div>
                </div>
                <div class="col-6 p-stat">
                    <div class="num"><?= $fu_count ?></div>
                    <div class="lbl">Follow Ups</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- VISIT HISTORY TABLE -->
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
                        <th width="100">Visit Type</th>
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
                    $is_reg    = ($row['visit_number'] == 1);
                    $row_class = $is_reg ? 'visit-row-reg' : 'visit-row-fu';
                    $given     = isset($row['vaccinations']) ? $row['vaccinations'] : array();
                ?>
                <tr class="<?= $row_class ?>">
                    <td class="text-muted"><?= $row['visit_number'] ?></td>
                    <td>
                        <?php if ($is_reg): ?>
                            <span class="visit-badge badge-reg"><i class="fa fa-plus-circle"></i> Registration</span>
                        <?php else: ?>
                            <span class="visit-badge badge-fu"><i class="fa fa-refresh"></i> Follow Up <?= ($row['visit_number'] - 1) ?></span>
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


<!-- ══ EPI VACCINATION STATUS ══ -->
<div class="vacc-schedule-section">

    <div class="sec-header">
        <h5><i class="fa fa-syringe"></i> &nbsp;EPI Vaccination Status</h5>
        <small style="color:rgba(255,255,255,.75); font-size:12px;">
            Child age at registration: <?= $age_display ?> (<?= $child_age_days ?> days) &nbsp;|&nbsp; Schedule status based on registration age
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
        </div>

        <!-- Schedule Table — same structure as visit history -->
        <div class="table-responsive">
        <table class="sch-table">
            <thead>
                <tr>
                    <th style="width:130px;">Visit</th>
                    <th style="width:100px;">Due Age</th>
                    <th style="width:90px;" class="text-center">Status</th>
                    <th>Vaccines</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($epi_schedule as $visit_def):

                // Per-visit stats
                $v_given = 0; $v_missed = 0; $v_future = 0;
                foreach ($visit_def['vaccines'] as $vacc_name) {
                    $st = vaccine_status_v2($vacc_name, $all_given_normalized, $visit_def['due_days'], $child_age_days);
                    if ($st === 'given')  $v_given++;
                    if ($st === 'missed') $v_missed++;
                    if ($st === 'future') $v_future++;
                }
                $v_total = count($visit_def['vaccines']);

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
            ?>
            <tr>
                <td>
                    <span class="visit-label-badge <?= $visit_def['color_class'] ?>">
                        <?= $visit_def['visit'] ?>
                    </span>
                </td>
                <td>
                    <span class="age-chip"><?= $visit_def['due_label'] ?></span>
                </td>
                <td class="text-center">
                    <?= $status_tag ?>
                </td>
                <td>
                    <div class="sch-pills">
                        <?php foreach ($visit_def['vaccines'] as $vacc_name):
                            $st = vaccine_status_v2($vacc_name, $all_given_normalized, $visit_def['due_days'], $child_age_days);
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

</div>