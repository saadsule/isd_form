<?php
$scorecard     = isset($scorecard)     ? $scorecard     : array();
$prev_scorecard= isset($prev_scorecard)? $prev_scorecard: array();

$total_records = array_sum(array_column($scorecard, 'ch_total'));
$total_errors  = array_sum(array_column($scorecard, 'total_errors'));
$total_ops     = count($scorecard);
$need_coaching = count(array_filter($scorecard, function($r){ return $r['accuracy'] < 90; }));

// ── Team overall accuracy ──
$team_accuracy = ($total_records > 0)
    ? round((($total_records - $total_errors) / $total_records) * 100, 1)
    : 100;

// ── Previous period totals ──
$prev_records  = array_sum(array_column($prev_scorecard, 'ch_total'));
$prev_errors   = array_sum(array_column($prev_scorecard, 'total_errors'));
$prev_accuracy = ($prev_records > 0)
    ? round((($prev_records - $prev_errors) / $prev_records) * 100, 1)
    : null;

$has_prev = ($prev_records > 0);

function sc_initials($name) {
    $parts = explode(' ', trim($name));
    $init  = '';
    foreach ($parts as $p) { if ($p) $init .= strtoupper($p[0]); }
    return substr($init, 0, 2);
}
function sc_err_color($n, $hi) {
    if ($n === 0)  return '#27ae60';
    if ($n >= $hi) return '#c0392b';
    return '#e67e22';
}
function sc_trend($curr, $prev) {
    if ($prev === null || $prev == 0) return ['', '#aaa', ''];
    $diff = $curr - $prev;
    if ($diff == 0) return ['<i class="fa fa-minus"></i>', '#aaa', 'No change'];
    if ($diff < 0)  return ['<i class="fa fa-arrow-down"></i>', '#27ae60', abs($diff) . ' less than last period'];
    return ['<i class="fa fa-arrow-up"></i>', '#c0392b', $diff . ' more than last period'];
}
function sc_acc_trend($curr, $prev) {
    if ($prev === null) return ['', '#aaa', ''];
    $diff = round($curr - $prev, 1);
    if ($diff == 0)  return ['<i class="fa fa-minus"></i>', '#aaa', 'Same as last period'];
    if ($diff > 0)   return ['<i class="fa fa-arrow-up"></i>', '#27ae60', '+' . $diff . '% vs last period'];
    return ['<i class="fa fa-arrow-down"></i>', '#c0392b', $diff . '% vs last period'];
}

// ── 9 error definitions (age_group_inconsistent removed — too slow) ──
$err_keys_global   = [
    'antigen_mismatch', 'duplicate_qr', 'pregnancy_anomaly',
    'underage_married', 'possible_duplicate',
    'age_group_mismatch', 'dob_age_mismatch', 'impossible_age_month',
    'orphan_followup'
];
$err_labels_global = [
    'Antigen Mismatch', 'Duplicate QR', 'Pregnancy Anomaly',
    'Underage Married', 'Possible Duplicate',
    'Age-Group Mismatch', 'DOB-Age Mismatch', 'Invalid Age Month',
    'Orphan Followup'
];
$err_icons_global  = [
    'fa-exclamation-triangle', 'fa-qrcode', 'fa-female',
    'fa-child', 'fa-copy',
    'fa-users', 'fa-calendar-times-o', 'fa-clock-o',
    'fa-unlink'
];
$err_links_global  = [
    'reports/age_antigens_mismatch_comprehensive/1',
    'reports/duplicate_qr_code',
    'reports/pregnancy_anomaly',
    'reports/underage_married',
    'reports/possible_duplicates',
    'reports/age_group_mismatch',
    'reports/dob_age_mismatch',
    'reports/impossible_age_month',
    'reports/orphan_followup',
];
$err_hi_global = [10, 5, 3, 1, 5, 5, 5, 2, 10];

// ── Team error totals (current) ──
$err_totals_global = array_fill(0, 9, 0);
foreach ($scorecard as $row) {
    foreach ($err_keys_global as $idx => $k) {
        $err_totals_global[$idx] += (isset($row[$k]) ? $row[$k] : 0);
    }
}

// ── Team error totals (previous period) ──
$prev_err_totals = array_fill(0, 9, 0);
foreach ($prev_scorecard as $row) {
    foreach ($err_keys_global as $idx => $k) {
        $prev_err_totals[$idx] += (isset($row[$k]) ? $row[$k] : 0);
    }
}

// Rank errors by count descending
$err_ranked = array_keys($err_totals_global);
usort($err_ranked, function($a,$b) use ($err_totals_global){
    return $err_totals_global[$b] - $err_totals_global[$a];
});
$team_top_idx   = $err_ranked[0];
$team_top_label = $err_labels_global[$team_top_idx];
$team_top_count = $err_totals_global[$team_top_idx];

// Job aid actions — 9 checks
$job_aid_actions = [
    'Antigen Mismatch'   => 'Prepare job aid: Age group vs antigen selection chart. Review correct vaccine-age mapping during team session.',
    'Duplicate QR'       => 'Prepare job aid: QR code verification checklist. Train team to search existing records before new entry.',
    'Pregnancy Anomaly'  => 'Prepare job aid: Pregnancy eligibility rules (gender, age, marital status). Review all validation steps.',
    'Underage Married'   => 'Prepare job aid: Age validation rules for marital status fields. Clarify minimum age thresholds.',
    'Possible Duplicate' => 'Prepare job aid: Duplicate detection steps — verify QR + name + date before submitting any record.',
    'Age-Group Mismatch' => 'Prepare job aid: Age in years vs correct age group mapping table. Operator must verify age group matches the entered years before saving.',
    'DOB-Age Mismatch'   => 'Prepare job aid: Date of birth vs age calculation check. If DOB is entered, age fields must match. Train operators to use the auto-calculate feature.',
    'Invalid Age Month'  => 'Prepare job aid: Age months must be 0-11 only. A value of 12 or more means the year was not incremented correctly.',
    'Orphan Followup'    => 'Prepare job aid: Followup visit recorded but no New registration exists for this QR code. Operators must ensure patient has a New record before recording any Followup visit.',
];

// Team accuracy color/label
if ($team_accuracy >= 97)     { $ta_color = '#27ae60'; $ta_label = 'Excellent'; }
elseif ($team_accuracy >= 93) { $ta_color = '#27ae60'; $ta_label = 'Good'; }
elseif ($team_accuracy >= 88) { $ta_color = '#e67e22'; $ta_label = 'Needs Improvement'; }
else                          { $ta_color = '#c0392b'; $ta_label = 'Poor'; }

list($acc_arrow, $acc_arrow_color, $acc_arrow_text) = sc_acc_trend($team_accuracy, $prev_accuracy);
list($err_arrow, $err_arrow_color, $err_arrow_text) = sc_trend($total_errors, $prev_errors);
?>

<style>
.pmm-wrap { font-family: inherit; }

/* ── Capacity Banner ── */
.pmm-banner {
    display:grid; grid-template-columns:repeat(3,1fr); gap:0;
    background:#fff; border:1px solid #dde3ea; border-radius:12px;
    padding:16px 0; margin-bottom:16px;
    box-shadow:0 1px 4px rgba(0,0,0,0.06);
}
.pmm-banner-item {
    display:flex; align-items:flex-start; gap:12px;
    padding:0 20px; border-right:1px solid #e3e8ef;
}
.pmm-banner-item:last-child { border-right:none; }
.pmm-banner-icon {
    width:36px; height:36px; border-radius:8px;
    background:#eaf4fb; display:flex; align-items:center; justify-content:center;
    font-size:16px; color:#2980b9; flex-shrink:0;
}
.pmm-banner-title { font-size:12px; font-weight:700; color:#2c3e50; margin-bottom:3px; }
.pmm-banner-desc  { font-size:11px; color:#7f8c8d; line-height:1.5; }

/* ── Summary Cards ── */
.sc-sum { display:grid; grid-template-columns:repeat(5,1fr); gap:12px; margin-bottom:16px; }
.sc-sum-card {
    background:#fff; border:1px solid #dde3ea; border-radius:10px;
    padding:16px 18px; box-shadow:0 1px 4px rgba(0,0,0,0.06);
}
.sc-sum-card.highlight {
    border-color:#2980b9; border-width:2px;
    background:linear-gradient(135deg,#eaf4fb 0%,#fff 100%);
}
.sc-sum-card .sl {
    font-size:11px; color:#8a9ab0; font-weight:700;
    text-transform:uppercase; letter-spacing:.04em; margin-bottom:6px;
}
.sc-sum-card .sv { font-size:26px; font-weight:700; color:#2c3e50; line-height:1; }
.sc-sum-card .ss { font-size:11px; color:#aaa; margin-top:5px; }
.sc-sum-card .si { font-size:11px; color:#7f8c8d; margin-top:4px; }
.trend-chip {
    display:inline-flex; align-items:center; gap:4px;
    font-size:11px; font-weight:600; margin-top:6px;
    padding:2px 8px; border-radius:20px; background:#f0f3f4;
}

/* ── Team Accuracy Hero ── */
.ta-hero {
    background:#fff; border:1px solid #dde3ea; border-radius:12px;
    padding:18px 22px; margin-bottom:16px;
    box-shadow:0 1px 4px rgba(0,0,0,0.06);
    display:flex; align-items:center; justify-content:space-between; gap:20px;
    flex-wrap:wrap;
}
.ta-left { display:flex; align-items:center; gap:18px; }
.ta-big  { font-size:52px; font-weight:700; line-height:1; }
.ta-label-wrap .ta-title { font-size:13px; font-weight:700; color:#2c3e50; margin-bottom:3px; }
.ta-label-wrap .ta-sub   { font-size:12px; color:#7f8c8d; }
.ta-badge {
    display:inline-block; padding:5px 14px; border-radius:20px;
    font-size:12px; font-weight:700; margin-top:6px;
}
.ta-good { background:#eaf6f0; color:#1a7a4a; }
.ta-warn { background:#fef9e7; color:#b7770d; }
.ta-poor { background:#fdecea; color:#c0392b; }
.ta-trend-box {
    background:#f8f9fa; border:1px solid #e3e8ef; border-radius:10px;
    padding:12px 18px; text-align:center; min-width:160px;
}
.ta-trend-box .tt-label { font-size:11px; color:#8a9ab0; font-weight:700;
    text-transform:uppercase; letter-spacing:.04em; margin-bottom:4px; }
.ta-trend-box .tt-val { font-size:22px; font-weight:700; }
.ta-trend-box .tt-sub { font-size:11px; margin-top:4px; font-weight:600; }
.ta-vs { font-size:20px; color:#aaa; font-weight:300; padding:0 4px; }

/* ── Month-over-Month ── */
.mom-panel {
    background:#fff; border:1px solid #dde3ea; border-radius:12px;
    padding:16px 18px; margin-bottom:16px;
    box-shadow:0 1px 4px rgba(0,0,0,0.06);
}
.mom-header {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:14px; flex-wrap:wrap; gap:8px;
}
.mom-title { font-size:13px; font-weight:700; color:#2c3e50; }
.mom-sub   { font-size:11px; color:#7f8c8d; margin-top:2px; }
.mom-no-data {
    text-align:center; padding:20px; color:#aaa; font-size:13px;
    background:#f8f9fa; border-radius:8px;
}
/* Row 1: 5 cards, Row 2: 4 cards (9 total) */
.mom-grid      { display:grid; grid-template-columns:repeat(5,1fr); gap:10px; }
.mom-grid-row2 { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-top:10px; }
.mom-card {
    background:#f8f9fa; border:1px solid #e3e8ef;
    border-radius:10px; padding:12px 10px;
}
.mom-card-label { font-size:10px; color:#8a9ab0; font-weight:700;
    text-transform:uppercase; letter-spacing:.04em; margin-bottom:8px;
    white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.mom-vals { display:flex; align-items:flex-end; gap:6px; margin-bottom:6px; }
.mom-curr { font-size:20px; font-weight:700; }
.mom-prev { font-size:12px; color:#aaa; font-weight:600; padding-bottom:2px; }
.mom-arrow { font-size:11px; font-weight:700; display:flex; align-items:center; gap:4px; }
.mom-bar-wrap { display:flex; gap:4px; align-items:flex-end; height:32px; margin-top:8px; }
.mom-bar { flex:1; border-radius:3px 3px 0 0; min-height:3px; }
.mom-bar-label { font-size:9px; color:#aaa; text-align:center; margin-top:3px; }
.improved  { color:#27ae60; }
.worsened  { color:#c0392b; }
.no-change { color:#aaa; }

/* ── Team Error Ranking Panel ── */
.team-panel {
    background:#fff; border:1px solid #dde3ea; border-radius:12px;
    padding:16px 18px; margin-bottom:16px;
    box-shadow:0 1px 4px rgba(0,0,0,0.06);
}
.team-panel-header {
    display:flex; align-items:center; justify-content:space-between;
    margin-bottom:14px; flex-wrap:wrap; gap:8px;
}
.team-panel-title { font-size:13px; font-weight:700; color:#2c3e50; }
.team-panel-sub   { font-size:11px; color:#7f8c8d; }
.team-focus-badge {
    background:#fff3cd; border:1px solid #f9ca7a; border-radius:20px;
    padding:3px 12px; font-size:11px; font-weight:700; color:#7f6000;
}
.err-rank-row {
    display:grid; grid-template-columns:190px 1fr 60px 60px 110px;
    align-items:center; gap:10px; padding:8px 0;
    border-bottom:1px solid #f0f3f4;
}
.err-rank-row:last-child { border-bottom:none; }
.err-rank-label { font-size:12px; color:#2c3e50; font-weight:600;
    display:flex; align-items:center; gap:6px; }
.err-rank-label i { font-size:11px; color:#8a9ab0; }
.err-rank-bar-wrap { height:8px; background:#f0f3f4; border-radius:4px; overflow:hidden; }
.err-rank-bar-fill { height:100%; border-radius:4px; }
.err-rank-count { font-size:13px; font-weight:700; text-align:right; }
.err-rank-trend { font-size:12px; font-weight:700; text-align:center; }
.err-rank-link  { font-size:11px; color:#2980b9; text-align:right; }
.err-rank-link a { color:#2980b9; text-decoration:none; }
.err-rank-link a:hover { text-decoration:underline; }

/* ── Team Coaching Action ── */
.team-action-box {
    background:#eaf4fb; border:1px solid #aed6f1; border-radius:10px;
    padding:13px 16px; margin-bottom:16px;
    display:flex; gap:12px; align-items:flex-start;
}
.team-action-icon  { font-size:20px; color:#2980b9; flex-shrink:0; margin-top:1px; }
.team-action-title { font-size:12px; font-weight:700; color:#1a5276; margin-bottom:3px; }
.team-action-text  { font-size:12px; color:#1a5276; line-height:1.6; }

/* ── Filter ── */
.sc-filter {
    display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;
    background:#fff; border:1px solid #dde3ea; border-radius:12px;
    padding:14px 18px; margin-bottom:16px;
    box-shadow:0 1px 4px rgba(0,0,0,0.06);
}
.sc-filter label { font-size:11px; color:#8a9ab0; font-weight:700;
    display:block; margin-bottom:3px; text-transform:uppercase; letter-spacing:.04em; }
.sc-filter input { height:34px; border-radius:8px; border:1px solid #ced4da;
    font-size:13px; padding:0 10px; }
.sc-filter .btn { height:34px; font-size:13px; }

/* ── Section Label ── */
.pmm-section-label {
    font-size:11px; font-weight:700; color:#8a9ab0;
    text-transform:uppercase; letter-spacing:.06em;
    margin-bottom:10px; padding-left:2px;
}

/* ── Operator Cards ── */
.op-card {
    background:#fff; border:1px solid #dde3ea; border-radius:12px;
    margin-bottom:10px; overflow:hidden;
    box-shadow:0 1px 4px rgba(0,0,0,0.06);
}
.op-card:hover { box-shadow:0 3px 10px rgba(0,0,0,0.09); }
.op-main {
    display:grid; grid-template-columns:175px 1fr 150px;
    align-items:center; gap:0; padding:14px 18px;
}
.op-avatar {
    width:38px; height:38px; border-radius:50%;
    background:#d6eaf8; display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:700; color:#1a5276; margin-bottom:6px;
}
.op-name     { font-size:14px; font-weight:700; color:#2c3e50; }
.op-username { font-size:11px; color:#aaa; }
.op-rec-badge {
    display:inline-block; font-size:11px; color:#7f8c8d;
    background:#f0f3f4; border-radius:20px; padding:2px 9px; margin-top:5px;
}

/* ── Error grid: row1=5cols, row2=4cols for 9 errors ── */
.err-grid-wrap { padding:0 10px; }
.err-grid-5 {
    display:grid; grid-template-columns:repeat(5,1fr);
    gap:5px; margin-bottom:5px;
}
.err-grid-4 {
    display:grid; grid-template-columns:repeat(4,1fr);
    gap:5px; margin-bottom:5px;
}
.err-box {
    background:#f8f9fa; border:1px solid #eaecef;
    border-radius:8px; padding:7px 5px; text-align:center;
}
.err-box .el {
    font-size:9px; color:#8a9ab0; font-weight:600;
    margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;
}
.err-box .ev { font-size:16px; font-weight:700; }

.op-right {
    text-align:right; padding-left:16px;
    border-left:1px solid #f0f3f4;
}
.acc-pct { font-size:24px; font-weight:700; }
.acc-bar { width:100%; height:7px; background:#ecf0f1; border-radius:4px; overflow:hidden; margin:6px 0; }
.acc-fill { height:100%; border-radius:4px; }
.risk-badge { display:inline-block; padding:4px 10px; border-radius:20px; font-size:11px; font-weight:700; }
.risk-good   { background:#eaf6f0; color:#1a7a4a; }
.risk-medium { background:#fef9e7; color:#b7770d; }
.risk-poor   { background:#fdecea; color:#c0392b; }

/* ── Card Footer ── */
.op-footer {
    border-top:1px solid #f0f3f4; padding:9px 18px;
    display:flex; align-items:center; justify-content:space-between;
    background:#fafbfc;
}
.coaching-tip { font-size:12px; color:#7f8c8d; display:flex; align-items:center; gap:6px; }
.coaching-tip strong { color:#e67e22; }
.detail-btn {
    background:none; border:1px solid #d6e4f0; border-radius:6px;
    font-size:12px; color:#2980b9; cursor:pointer; padding:4px 10px;
}
.detail-btn:hover { background:#eaf4fb; }

/* ── Expandable Detail ── */
.detail-section { display:none; border-top:1px solid #f0f3f4; padding:14px 18px; background:#fafcfe; }
.detail-section.open { display:block; }
/* 3 columns x 3 rows for 9 detail cards */
.detail-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:8px; }
.di {
    background:#fff; border:1px solid #e3e8ef;
    border-radius:8px; padding:10px 12px;
}
.di .dil { font-size:10px; color:#8a9ab0; font-weight:700;
    text-transform:uppercase; letter-spacing:.04em; margin-bottom:5px; }
.di .div2 { font-size:20px; font-weight:700; }
.di a { font-size:11px; color:#2980b9; margin-top:5px; display:block; text-decoration:none; }
.di a:hover { text-decoration:underline; }
.di .di-nill { font-size:11px; color:#bbb; margin-top:5px; }
.detail-coaching-note {
    background:#fff8e1; border:1px solid #f9ca7a; border-radius:8px;
    padding:11px 14px; margin-top:12px; font-size:12px; color:#7f6000; line-height:1.6;
}
.detail-coaching-note strong { color:#d35400; }
</style>

<div class="page-container"><div class="main-content pmm-wrap">

<div class="page-header">
    <h2><i class="fa fa-line-chart text-primary"></i> Performance Monitoring Module</h2>
    <p class="text-muted mb-0">Capacity building — identify team error patterns, target coaching, track monthly improvement</p>
</div>

<!-- Capacity Building Banner -->
<div class="pmm-banner">
    <div class="pmm-banner-item">
        <div class="pmm-banner-icon"><i class="fa fa-search"></i></div>
        <div>
            <div class="pmm-banner-title">Identify gaps</div>
            <div class="pmm-banner-desc">Recurring error patterns identified across the team each month using real data</div>
        </div>
    </div>
    <div class="pmm-banner-item">
        <div class="pmm-banner-icon"><i class="fa fa-users"></i></div>
        <div>
            <div class="pmm-banner-title">Targeted team coaching</div>
            <div class="pmm-banner-desc">No general training — focused team sessions on specific error types with job aid checklist</div>
        </div>
    </div>
    <div class="pmm-banner-item">
        <div class="pmm-banner-icon"><i class="fa fa-signal" style="color:#2980b9;font-size:16px;"></i></div>
        <div>
            <div class="pmm-banner-title">Track progress</div>
            <div class="pmm-banner-desc">Monthly review with real numbers ensures continuous, evidence-based team improvement</div>
        </div>
    </div>
</div>

<!-- Filter -->
<form method="GET" action="<?= current_url() ?>">
<div class="sc-filter">
    <div>
        <label>From Date</label>
        <input type="date" name="start" value="<?= htmlspecialchars($start) ?>">
    </div>
    <div>
        <label>To Date</label>
        <input type="date" name="end" value="<?= htmlspecialchars($end) ?>">
    </div>
    <button type="submit" class="btn btn-dark">
        <i class="fa fa-search"></i> Apply
    </button>
    <a href="<?= current_url() ?>" class="btn btn-outline-secondary">Reset</a>
</div>
</form>

<!-- ══════════════════════════════════════════════════
     TEAM OVERALL ACCURACY HERO
════════════════════════════════════════════════════ -->
<div class="ta-hero">
    <div class="ta-left">
        <div class="ta-big" style="color:<?= $ta_color ?>"><?= $team_accuracy ?>%</div>
        <div class="ta-label-wrap">
            <div class="ta-title">Team Overall Accuracy</div>
            <div class="ta-sub"><?= number_format($total_records) ?> records &nbsp;·&nbsp; <?= number_format($total_errors) ?> errors &nbsp;·&nbsp; <?= $total_ops ?> operators</div>
            <span class="ta-badge <?= $team_accuracy >= 93 ? 'ta-good' : ($team_accuracy >= 88 ? 'ta-warn' : 'ta-poor') ?>">
                <?= $ta_label ?>
            </span>
            <?php if ($acc_arrow_text): ?>
            <div style="margin-top:6px;font-size:12px;font-weight:600;color:<?= $acc_arrow_color ?>">
                <?= $acc_arrow ?> <?= $acc_arrow_text ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($has_prev): ?>
    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
        <div class="ta-trend-box">
            <div class="tt-label">Previous Period</div>
            <div class="tt-val" style="color:#7f8c8d"><?= $prev_accuracy ?>%</div>
            <div class="tt-sub" style="color:#aaa"><?= number_format($prev_records) ?> records</div>
        </div>
        <div class="ta-vs">→</div>
        <div class="ta-trend-box" style="border-color:<?= $ta_color ?>">
            <div class="tt-label">This Period</div>
            <div class="tt-val" style="color:<?= $ta_color ?>"><?= $team_accuracy ?>%</div>
            <div class="tt-sub" style="color:<?= $acc_arrow_color ?>"><?= $acc_arrow ?> <?= $acc_arrow_text ?></div>
        </div>
    </div>
    <?php else: ?>
    <div style="text-align:center;color:#aaa;font-size:12px;padding:10px 20px;background:#f8f9fa;border-radius:10px;">
        <i class="fa fa-info-circle"></i><br>No previous period data yet.<br>
        <span style="font-size:11px;">Apply a date filter to compare periods.</span>
    </div>
    <?php endif; ?>
</div>

<!-- Summary Cards -->
<div class="sc-sum">
    <div class="sc-sum-card">
        <div class="sl">Total Operators</div>
        <div class="sv"><?= $total_ops ?></div>
        <div class="ss">Data entry users</div>
    </div>
    <div class="sc-sum-card">
        <div class="sl">Total Records</div>
        <div class="sv"><?= number_format($total_records) ?></div>
        <div class="ss">Child health forms</div>
        <?php if ($has_prev && $prev_records > 0):
            $rdiff = $total_records - $prev_records;
        ?>
        <div class="trend-chip" style="color:<?= $rdiff >= 0 ? '#27ae60' : '#e67e22' ?>">
            <i class="fa <?= $rdiff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' ?>"></i>
            <?= abs($rdiff) ?> vs last period
        </div>
        <?php endif; ?>
    </div>
    <div class="sc-sum-card">
        <div class="sl">Total Errors</div>
        <div class="sv" style="color:#c0392b"><?= number_format($total_errors) ?></div>
        <div class="ss">All 9 anomaly types</div>
        <?php if ($has_prev && $prev_errors > 0):
            list($earr,$ecol,$etxt) = sc_trend($total_errors, $prev_errors);
        ?>
        <div class="trend-chip" style="color:<?= $ecol ?>">
            <?= $earr ?> <?= $etxt ?>
        </div>
        <?php endif; ?>
    </div>
    <div class="sc-sum-card">
        <div class="sl">Need Coaching</div>
        <div class="sv" style="color:#e67e22"><?= $need_coaching ?></div>
        <div class="si"><?= $need_coaching ?> of <?= $total_ops ?> operators below 90% accuracy</div>
    </div>
    <div class="sc-sum-card highlight">
        <div class="sl">Team Accuracy</div>
        <div class="sv" style="color:<?= $ta_color ?>"><?= $team_accuracy ?>%</div>
        <div class="ss" style="color:<?= $ta_color ?>;font-weight:700"><?= $ta_label ?></div>
        <?php if ($acc_arrow_text): ?>
        <div class="trend-chip" style="color:<?= $acc_arrow_color ?>">
            <?= $acc_arrow ?> <?= $acc_arrow_text ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- ══════════════════════════════════════════════════
     MONTH-OVER-MONTH ERROR TREND — row1:5, row2:4
════════════════════════════════════════════════════ -->
<div class="mom-panel">
    <div class="mom-header">
        <div>
            <div class="mom-title"><i class="fa fa-exchange"></i> Month-over-Month Error Trend</div>
            <div class="mom-sub">Current period vs previous period — green means improvement (9 error types)</div>
        </div>
        <?php if ($has_prev): ?>
        <span style="font-size:12px;color:#7f8c8d;background:#f0f3f4;padding:4px 12px;border-radius:20px;">
            Prev: <?= number_format($prev_errors) ?> errors &nbsp;→&nbsp; Now: <?= number_format($total_errors) ?> errors
        </span>
        <?php endif; ?>
    </div>

    <?php if (!$has_prev): ?>
    <div class="mom-no-data">
        <i class="fa fa-clock-o" style="font-size:24px;display:block;margin-bottom:8px;"></i>
        No previous period data available yet.<br>
        <span style="font-size:11px;">Select a date range and the system will automatically compare with the equivalent previous period.</span>
    </div>
    <?php else:
        $max_bar = 0;
        foreach ($err_keys_global as $idx => $k) {
            $max_bar = max($max_bar, $err_totals_global[$idx], $prev_err_totals[$idx]);
        }
        if ($max_bar == 0) $max_bar = 1;

        function render_mom_card($idx, $err_labels_global, $err_totals_global, $prev_err_totals, $max_bar) {
            $curr_n = $err_totals_global[$idx];
            $prev_n = $prev_err_totals[$idx];
            $diff   = $curr_n - $prev_n;
            if      ($diff < 0)  { $arrow = '<i class="fa fa-arrow-down"></i>'; $cls = 'improved';  $diff_txt = abs($diff).' less'; }
            elseif  ($diff > 0)  { $arrow = '<i class="fa fa-arrow-up"></i>';   $cls = 'worsened';  $diff_txt = $diff.' more'; }
            else                 { $arrow = '<i class="fa fa-minus"></i>';       $cls = 'no-change'; $diff_txt = 'No change'; }
            $curr_h   = max(4, round(($curr_n / $max_bar) * 40));
            $prev_h   = max(4, round(($prev_n / $max_bar) * 40));
            $curr_col = ($curr_n == 0) ? '#27ae60' : ($diff <= 0 ? '#27ae60' : '#c0392b');
            $prev_col = '#bdc3c7';
            echo '
            <div class="mom-card">
                <div class="mom-card-label">'.$err_labels_global[$idx].'</div>
                <div class="mom-vals">
                    <div class="mom-curr" style="color:'.$curr_col.'">'.$curr_n.'</div>
                    <div class="mom-prev">/ '.$prev_n.'</div>
                </div>
                <div class="mom-arrow '.$cls.'">'.$arrow.' '.$diff_txt.'</div>
                <div class="mom-bar-wrap">
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                        <div class="mom-bar" style="height:'.$prev_h.'px;background:'.$prev_col.';width:100%"></div>
                        <div class="mom-bar-label">Prev</div>
                    </div>
                    <div style="flex:1;display:flex;flex-direction:column;align-items:center;">
                        <div class="mom-bar" style="height:'.$curr_h.'px;background:'.$curr_col.';width:100%"></div>
                        <div class="mom-bar-label">Now</div>
                    </div>
                </div>
            </div>';
        }
    ?>
    <!-- Row 1: first 5 error types (0-4) -->
    <div class="mom-grid">
    <?php for ($idx = 0; $idx < 5; $idx++): render_mom_card($idx, $err_labels_global, $err_totals_global, $prev_err_totals, $max_bar); endfor; ?>
    </div>
    <!-- Row 2: last 4 error types (5-8) -->
    <div class="mom-grid-row2">
    <?php for ($idx = 5; $idx < 9; $idx++): render_mom_card($idx, $err_labels_global, $err_totals_global, $prev_err_totals, $max_bar); endfor; ?>
    </div>
    <?php endif; ?>
</div>

<!-- ══════════════════════════════════════════════════
     TEAM ERROR RANKING PANEL
════════════════════════════════════════════════════ -->
<?php if ($total_errors > 0):
    $max_team_err = max($err_totals_global);
    if ($max_team_err == 0) $max_team_err = 1;
?>
<div class="team-panel">
    <div class="team-panel-header">
        <div>
            <div class="team-panel-title"><i class="fa fa-bar-chart"></i> Team Error Breakdown — This Period</div>
            <div class="team-panel-sub">Ranked by frequency · Use this to plan the next team coaching session</div>
        </div>
        <span class="team-focus-badge"><i class="fa fa-star"></i> Focus: <?= $team_top_label ?> (<?= $team_top_count ?>)</span>
    </div>

    <div style="display:grid;grid-template-columns:190px 1fr 60px 60px 110px;gap:10px;padding:0 0 6px;border-bottom:2px solid #e3e8ef;font-size:10px;color:#8a9ab0;font-weight:700;text-transform:uppercase;letter-spacing:.04em;">
        <div>Error Type</div>
        <div>Frequency</div>
        <div style="text-align:right">Count</div>
        <div style="text-align:center">Trend</div>
        <div style="text-align:right">Records</div>
    </div>

    <?php foreach ($err_ranked as $rank => $idx):
        $cnt      = $err_totals_global[$idx];
        $prev_cnt = $prev_err_totals[$idx];
        $pct      = round(($cnt / $max_team_err) * 100);
        if      ($rank === 0 && $cnt > 0) { $bar_color='#c0392b'; $cnt_color='#c0392b'; }
        elseif  ($rank === 1 && $cnt > 0) { $bar_color='#e67e22'; $cnt_color='#e67e22'; }
        elseif  ($cnt > 0)                { $bar_color='#3498db'; $cnt_color='#3498db'; }
        else                              { $bar_color='#27ae60'; $cnt_color='#27ae60'; }

        if ($has_prev) {
            $ediff = $cnt - $prev_cnt;
            if      ($ediff < 0)  { $earr2 = '<i class="fa fa-arrow-down"></i>'; $ecls2 = 'improved'; }
            elseif  ($ediff > 0)  { $earr2 = '<i class="fa fa-arrow-up"></i>';   $ecls2 = 'worsened'; }
            else                  { $earr2 = '<i class="fa fa-minus"></i>';       $ecls2 = 'no-change'; }
        } else {
            $earr2 = '—'; $ecls2 = 'no-change';
        }
    ?>
    <div class="err-rank-row">
        <div class="err-rank-label">
            <i class="fa <?= $err_icons_global[$idx] ?>"></i>
            <?= $err_labels_global[$idx] ?>
        </div>
        <div class="err-rank-bar-wrap">
            <div class="err-rank-bar-fill" style="width:<?= $pct ?>%;background:<?= $bar_color ?>"></div>
        </div>
        <div class="err-rank-count" style="color:<?= $cnt_color ?>"><?= $cnt ?></div>
        <div class="err-rank-trend <?= $ecls2 ?>"><?= $earr2 ?></div>
        <div class="err-rank-link">
            <?php if ($cnt > 0): ?>
            <a href="<?= base_url($err_links_global[$idx]) ?>" target="_blank">View records →</a>
            <?php else: ?>
            <span style="color:#bbb">No errors</span>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Team Coaching Action -->
<div class="team-action-box">
    <div class="team-action-icon"><i class="fa fa-graduation-cap"></i></div>
    <div>
        <div class="team-action-title">Recommended team coaching action for this period</div>
        <div class="team-action-text">
            Top error across the team: <strong><?= $team_top_label ?></strong>
            (<?= $team_top_count ?> occurrences across <?= $total_ops ?> operators).<br>
            <?= $job_aid_actions[$team_top_label] ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ══════════════════════════════════════════════════
     INDIVIDUAL OPERATOR RECORDS
════════════════════════════════════════════════════ -->
<div class="pmm-section-label">Individual operator records — 9 error checks</div>

<?php foreach ($scorecard as $i => $op):
    $acc = $op['accuracy'];
    if ($acc >= 95)     { $acc_color='#27ae60'; $risk_cls='risk-good';   $risk_lbl='Good ✓'; }
    elseif ($acc >= 85) { $acc_color='#e67e22'; $risk_cls='risk-medium'; $risk_lbl='Needs Coaching'; }
    else                { $acc_color='#c0392b'; $risk_cls='risk-poor';   $risk_lbl='Poor — Urgent'; }

    $border_color = $acc >= 95 ? '#27ae60' : ($acc >= 85 ? '#e67e22' : '#c0392b');

    $err_vals = [];
    foreach ($err_keys_global as $k) {
        $err_vals[] = isset($op[$k]) ? (int)$op[$k] : 0;
    }
    $max_err = max($err_vals);
    $top_idx = array_search($max_err, $err_vals);

    $coaching = ($op['total_errors'] == 0)
        ? '<i class="fa fa-check-circle" style="color:#27ae60"></i> No errors — performing well'
        : 'Top error type: <strong>' . $err_labels_global[$top_idx] . '</strong>';

    // Row 1: first 5 errors, Row 2: last 4 errors
    $err_row1 = array_slice($err_keys_global,   0, 5);
    $err_row2 = array_slice($err_keys_global,   5, 4);
    $lbl_row1 = array_slice($err_labels_global, 0, 5);
    $lbl_row2 = array_slice($err_labels_global, 5, 4);
    $hi_row1  = array_slice($err_hi_global,     0, 5);
    $hi_row2  = array_slice($err_hi_global,     5, 4);
?>
<div class="op-card" style="border-left:4px solid <?= $border_color ?>">
    <div class="op-main">

        <!-- Name / Avatar -->
        <div>
            <div class="op-avatar"><?= sc_initials($op['full_name']) ?></div>
            <div class="op-name"><?= htmlspecialchars($op['full_name']) ?></div>
            <div class="op-username"><?= htmlspecialchars($op['username']) ?></div>
            <div class="op-rec-badge"><?= number_format($op['ch_total']) ?> records</div>
        </div>

        <!-- Error boxes: row1=5, row2=4 -->
        <div class="err-grid-wrap">
            <div class="err-grid-5">
            <?php foreach ($err_row1 as $j => $k):
                $n   = isset($op[$k]) ? (int)$op[$k] : 0;
                $col = sc_err_color($n, $hi_row1[$j]);
            ?>
                <div class="err-box">
                    <div class="el"><?= $lbl_row1[$j] ?></div>
                    <div class="ev" style="color:<?= $col ?>"><?= $n ?></div>
                </div>
            <?php endforeach; ?>
            </div>
            <div class="err-grid-4">
            <?php foreach ($err_row2 as $j => $k):
                $n   = isset($op[$k]) ? (int)$op[$k] : 0;
                $col = sc_err_color($n, $hi_row2[$j]);
            ?>
                <div class="err-box">
                    <div class="el"><?= $lbl_row2[$j] ?></div>
                    <div class="ev" style="color:<?= $col ?>"><?= $n ?></div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>

        <!-- Accuracy -->
        <div class="op-right">
            <div class="acc-pct" style="color:<?= $acc_color ?>"><?= $acc ?>%</div>
            <div class="acc-bar">
                <div class="acc-fill" style="width:<?= min($acc,100) ?>%;background:<?= $acc_color ?>"></div>
            </div>
            <span class="risk-badge <?= $risk_cls ?>"><?= $risk_lbl ?></span>
            <div style="margin-top:6px;font-size:11px;color:#aaa">
                <span style="color:<?= $acc_color ?>;font-weight:700"><?= $op['total_errors'] ?></span> total errors
            </div>
        </div>
    </div>

    <div class="op-footer">
        <div class="coaching-tip">
            <i class="fa fa-lightbulb-o"></i> <?= $coaching ?>
        </div>
        <button class="detail-btn" onclick="toggleDetail(<?= $i ?>)">
            <i class="fa fa-angle-down" id="chevron-<?= $i ?>"></i> View Details
        </button>
    </div>

    <!-- Expandable detail: 3x3 grid for 9 errors -->
    <div class="detail-section" id="detail-<?= $i ?>">
        <div class="detail-grid">
        <?php foreach ($err_keys_global as $j => $k):
            $n   = isset($op[$k]) ? (int)$op[$k] : 0;
            $col = sc_err_color($n, $err_hi_global[$j]);
        ?>
            <div class="di">
                <div class="dil"><?= $err_labels_global[$j] ?></div>
                <div class="div2" style="color:<?= $col ?>"><?= $n ?></div>
                <?php if ($n > 0): ?>
                <a href="<?= base_url($err_links_global[$j]) ?>" target="_blank">View records →</a>
                <?php else: ?>
                <span class="di-nill">No errors</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        </div>

        <?php if ($op['total_errors'] > 0): ?>
        <div class="detail-coaching-note">
            <strong><i class="fa fa-info-circle"></i> Note for team session:</strong>
            This record set shows <strong><?= $err_labels_global[$top_idx] ?></strong> as the top error type
            (<?= $max_err ?> occurrences). <?= $job_aid_actions[$err_labels_global[$top_idx]] ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

</div></div>

<script>
function toggleDetail(i) {
    var d  = document.getElementById('detail-' + i);
    var ch = document.getElementById('chevron-' + i);
    d.classList.toggle('open');
    ch.className = d.classList.contains('open') ? 'fa fa-angle-up' : 'fa fa-angle-down';
}
</script>