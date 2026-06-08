<?php
$scorecard    = isset($scorecard)    ? $scorecard    : array();
$total_records= array_sum(array_column($scorecard, 'ch_total'));
$total_errors = array_sum(array_column($scorecard, 'total_errors'));
$need_coaching= count(array_filter($scorecard, function($r){ return $r['accuracy'] < 90; }));

function sc_initials($name) {
    $parts = explode(' ', trim($name));
    $init  = '';
    foreach ($parts as $p) { if ($p) $init .= strtoupper($p[0]); }
    return substr($init, 0, 2);
}
function sc_err_color($n, $hi) {
    if ($n === 0)    return '#27ae60';
    if ($n >= $hi)   return '#c0392b';
    return '#e67e22';
}
?>

<style>
.sc-wrap { font-family: inherit; }
.sc-filter { display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;
    background:#fff; border:1px solid #e3e8ef; border-radius:12px;
    padding:14px 18px; margin-bottom:16px; }
.sc-filter label { font-size:11px; color:#8a9ab0; font-weight:600;
    display:block; margin-bottom:3px; }
.sc-filter input { height:34px; border-radius:8px; border:1px solid #ced4da;
    font-size:13px; padding:0 10px; }
.sc-filter .btn { height:34px; font-size:13px; }
.sc-sum { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:18px; }
.sc-sum-card { background:#f8f9fa; border-radius:10px; padding:14px 16px; }
.sc-sum-card .sl { font-size:11px; color:#8a9ab0; font-weight:600; margin-bottom:5px; }
.sc-sum-card .sv { font-size:24px; font-weight:700; color:#2c3e50; }
.sc-sum-card .ss { font-size:11px; color:#aaa; margin-top:3px; }
.op-card { background:#fff; border:1px solid #e3e8ef; border-radius:12px;
    margin-bottom:12px; overflow:hidden; }
.op-main { display:grid; grid-template-columns:170px 1fr 130px;
    align-items:center; gap:0; padding:14px 18px; }
.op-avatar { width:38px; height:38px; border-radius:50%;
    background:#d6eaf8; display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:700; color:#1a5276; margin-bottom:6px; }
.op-name { font-size:14px; font-weight:700; color:#2c3e50; }
.op-username { font-size:11px; color:#aaa; }
.op-rec-badge { display:inline-block; font-size:11px; color:#7f8c8d;
    background:#f0f3f4; border-radius:20px; padding:2px 8px; margin-top:4px; }
.err-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:6px; padding:0 10px; }
.err-box { background:#f8f9fa; border-radius:8px; padding:8px 10px; text-align:center; }
.err-box .el { font-size:10px; color:#8a9ab0; font-weight:600;
    margin-bottom:3px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.err-box .ev { font-size:18px; font-weight:700; }
.op-right { text-align:right; padding-left:14px; }
.acc-pct { font-size:22px; font-weight:700; }
.acc-bar { width:100%; height:6px; background:#ecf0f1;
    border-radius:3px; overflow:hidden; margin:5px 0; }
.acc-fill { height:100%; border-radius:3px; }
.risk-badge { display:inline-block; padding:3px 10px; border-radius:20px; font-size:11px; font-weight:600; }
.risk-good   { background:#eaf6f0; color:#1a7a4a; }
.risk-medium { background:#fef9e7; color:#b7770d; }
.risk-poor   { background:#fdecea; color:#c0392b; }
.op-footer { border-top:1px solid #f0f3f4; padding:9px 18px;
    display:flex; align-items:center; justify-content:space-between;
    background:#fafbfc; }
.coaching-tip { font-size:12px; color:#7f8c8d; }
.coaching-tip strong { color:#e67e22; }
.detail-btn { background:none; border:none; font-size:12px; color:#2980b9;
    cursor:pointer; padding:0; }
.detail-section { display:none; border-top:1px solid #f0f3f4; padding:14px 18px; }
.detail-section.open { display:block; }
.detail-grid { display:grid; grid-template-columns:repeat(5,minmax(0,1fr)); gap:8px; }
.di { background:#f8f9fa; border-radius:8px; padding:10px 12px; }
.di .dil { font-size:10px; color:#8a9ab0; font-weight:600; margin-bottom:4px; }
.di .div2 { font-size:20px; font-weight:700; }
.di a { font-size:11px; color:#2980b9; margin-top:4px; display:block; }
.coaching-box { background:#fff8e1; border-left:3px solid #f39c12;
    border-radius:0; padding:10px 14px; margin-top:12px; font-size:12px; color:#7f8c8d; }
.coaching-box strong { color:#d35400; }
</style>

<div class="page-container"><div class="main-content sc-wrap">

<div class="page-header">
    <h2><i class="fa fa-line-chart text-primary"></i> Performance Monitoring Module</h2>
    <p class="text-muted mb-0">Capacity building — identify error patterns, target coaching, track improvement</p>
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

<!-- Summary -->
<div class="sc-sum">
    <div class="sc-sum-card">
        <div class="sl">Total Operators</div>
        <div class="sv"><?= count($scorecard) ?></div>
        <div class="ss">Data entry users</div>
    </div>
    <div class="sc-sum-card">
        <div class="sl">Total Records</div>
        <div class="sv"><?= number_format($total_records) ?></div>
        <div class="ss">Child health forms</div>
    </div>
    <div class="sc-sum-card">
        <div class="sl">Total Errors</div>
        <div class="sv" style="color:#c0392b"><?= number_format($total_errors) ?></div>
        <div class="ss">All anomaly types</div>
    </div>
    <div class="sc-sum-card">
        <div class="sl">Need Coaching</div>
        <div class="sv" style="color:#e67e22"><?= $need_coaching ?></div>
        <div class="ss">Below 90% accuracy</div>
    </div>
</div>

<!-- Operator Cards -->
<?php
$err_labels = ['Antigen Mismatch','Duplicate QR','Pregnancy Anomaly','Underage Married','Possible Duplicate'];
$err_keys   = ['antigen_mismatch','duplicate_qr','pregnancy_anomaly','underage_married','possible_duplicate'];
$err_hi     = [10, 5, 3, 1, 5];
$err_links  = [
    'reports/age_antigens_mismatch',
    'reports/duplicate_qr_code',
    'reports/pregnancy_anomaly',
    'reports/underage_married',
    'reports/possible_duplicate',
];

foreach ($scorecard as $i => $op):
    $acc = $op['accuracy'];
    if ($acc >= 95)     { $acc_color='#27ae60'; $risk_cls='risk-good';   $risk_lbl='Good ✓'; }
    elseif ($acc >= 85) { $acc_color='#e67e22'; $risk_cls='risk-medium'; $risk_lbl='Needs Coaching'; }
    else                { $acc_color='#c0392b'; $risk_cls='risk-poor';   $risk_lbl='Poor — Urgent'; }

    // Find top error for coaching tip
    $err_vals = array_map(function($k) use ($op){ return $op[$k]; }, $err_keys);
    $max_err  = max($err_vals);
    $top_idx  = array_search($max_err, $err_vals);
    $coaching = ($op['total_errors'] == 0)
        ? '<i class="fa fa-check-circle" style="color:#27ae60"></i> No errors — performing well'
        : 'Focus coaching on: <strong>' . $err_labels[$top_idx] . '</strong>';
?>
<?php
$border_color = $acc >= 95 ? '#27ae60' : ($acc >= 85 ? '#e67e22' : '#c0392b');
?>
<div class="op-card" style="border-left:3px solid <?= $border_color ?>">
    <!-- Main Row -->
    <div class="op-main">
        <!-- Left: name -->
        <div>
            <div class="op-avatar"><?= sc_initials($op['full_name']) ?></div>
            <div class="op-name"><?= htmlspecialchars($op['full_name']) ?></div>
            <div class="op-username"><?= htmlspecialchars($op['username']) ?></div>
            <div class="op-rec-badge"><?= number_format($op['ch_total']) ?> records</div>
        </div>

        <!-- Middle: error boxes -->
        <div class="err-grid">
        <?php foreach ($err_labels as $j => $lbl):
            $n   = $op[$err_keys[$j]];
            $col = sc_err_color($n, $err_hi[$j]);
        ?>
            <div class="err-box">
                <div class="el"><?= $lbl ?></div>
                <div class="ev" style="color:<?= $col ?>"><?= $n ?></div>
            </div>
        <?php endforeach; ?>
        </div>

        <!-- Right: accuracy -->
        <div class="op-right">
            <div class="acc-pct" style="color:<?= $acc_color ?>"><?= $acc ?>%</div>
            <div class="acc-bar">
                <div class="acc-fill" style="width:<?= $acc ?>%;background:<?= $acc_color ?>"></div>
            </div>
            <span class="risk-badge <?= $risk_cls ?>"><?= $risk_lbl ?></span>
            <div style="margin-top:5px;font-size:11px;color:#aaa">
                <span style="color:<?= $acc_color ?>;font-weight:700"><?= $op['total_errors'] ?></span> total errors
            </div>
        </div>
    </div>

    <!-- Footer with coaching tip and detail toggle -->
    <div class="op-footer">
        <div class="coaching-tip">
            <i class="fa fa-lightbulb-o"></i> <?= $coaching ?>
        </div>
        <button class="detail-btn" onclick="toggleDetail(<?= $i ?>)">
            <i class="fa fa-angle-down"></i> View Details
        </button>
    </div>

    <!-- Expandable Detail -->
    <div class="detail-section" id="detail-<?= $i ?>">
        <div class="detail-grid">
        <?php foreach ($err_labels as $j => $lbl):
            $n   = $op[$err_keys[$j]];
            $col = sc_err_color($n, $err_hi[$j]);
        ?>
            <div class="di">
                <div class="dil"><?= $lbl ?></div>
                <div class="div2" style="color:<?= $col ?>"><?= $n ?></div>
                <a href="<?= base_url($err_links[$j]) ?>" target="_blank">
                    View records →
                </a>
            </div>
        <?php endforeach; ?>
        </div>
        <?php if ($op['total_errors'] > 0): ?>
        <div class="coaching-box">
            <strong>Coaching action:</strong>
            Schedule 30-min session for <strong><?= htmlspecialchars($op['full_name']) ?></strong>
            focused on <strong><?= $err_labels[$top_idx] ?></strong>
            (<?= $max_err ?> errors found).
            Show them the actual error records and explain the correct entry process.
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

</div></div>

<script>
function toggleDetail(i) {
    var d = document.getElementById('detail-' + i);
    d.classList.toggle('open');
}
</script>