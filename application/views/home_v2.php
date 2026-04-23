<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

.reporting-wrap { font-family: 'Inter', sans-serif; color: #111827; }

/* Header */
.reporting-wrap .page-header {
    display: flex;
    align-items: baseline;
    gap: 12px;
    padding-bottom: 18px;
    border-bottom: 1px solid #e5e7eb;
    margin-bottom: 24px;
}
.reporting-wrap .header-title {
    font-size: 20px; font-weight: 700;
    color: #111827; letter-spacing: -0.3px; margin: 0;
}
.reporting-wrap .header-title + small { font-size: 12px; color: #9ca3af; }

/* Section labels */
.r-lbl {
    font-size: 10.5px; font-weight: 600;
    letter-spacing: 0.08em; text-transform: uppercase;
    color: #6b7280; margin: 0 0 12px;
    display: flex; align-items: center; gap: 8px;
}
.r-lbl::after {
    content: ''; flex: 1; height: 1px; background: #e5e7eb;
}

/* Cards */
.r-card {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    transition: box-shadow 0.18s ease, transform 0.18s ease;
}
.r-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.09); transform: translateY(-2px); }
.r-card .card-body { padding: 20px; }

/* Left accent */
.ra-blue   { border-left: 3px solid #2563eb; }
.ra-green  { border-left: 3px solid #059669; }
.ra-amber  { border-left: 3px solid #d97706; }
.ra-cyan   { border-left: 3px solid #0891b2; }
.ra-violet { border-left: 3px solid #7c3aed; }
.ra-teal   { border-left: 3px solid #0d9488; }
.ra-rose   { border-left: 3px solid #e11d48; }

/* Icon bubbles */
.r-icon {
    border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.r-icon.lg { width: 48px; height: 48px; font-size: 20px; }
.r-icon.sm { width: 40px; height: 40px; font-size: 17px; }
.ri-blue   { background: #eff6ff; color: #2563eb; }
.ri-green  { background: #ecfdf5; color: #059669; }
.ri-amber  { background: #fffbeb; color: #d97706; }
.ri-cyan   { background: #ecfeff; color: #0891b2; }
.ri-violet { background: #f5f3ff; color: #7c3aed; }
.ri-teal   { background: #f0fdfa; color: #0d9488; }
.ri-rose   { background: #fff1f2; color: #e11d48; }

/* KPI row */
.r-kpi { display: flex; align-items: center; gap: 14px; }
.r-kpi-label {
    font-size: 10.5px; font-weight: 600;
    letter-spacing: 0.06em; text-transform: uppercase;
    color: #6b7280; margin-bottom: 4px;
}
.r-kpi-value {
    font-size: 30px; font-weight: 800;
    color: #111827; letter-spacing: -1px; line-height: 1;
}

/* Snap row */
.r-snap { display: flex; align-items: center; gap: 12px; }
.r-snap-label {
    font-size: 10px; font-weight: 600;
    letter-spacing: 0.06em; text-transform: uppercase;
    color: #9ca3af; margin-bottom: 2px;
}
.r-snap-value {
    font-size: 26px; font-weight: 800;
    color: #111827; line-height: 1; letter-spacing: -0.5px;
}
.r-snap-sub { font-size: 10px; color: #9ca3af; margin-top: 1px; }

/* Inner card title */
.r-card-title {
    font-size: 11px; font-weight: 700;
    letter-spacing: 0.08em; text-transform: uppercase;
    color: #374151; margin-bottom: 18px;
}

/* Role badge */
.r-role-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 600;
    margin-bottom: 16px;
}
.r-role-badge.personal { background: #f5f3ff; color: #7c3aed; }
.r-role-badge.overview { background: #ecfdf5; color: #059669; }

/* Progress */
.r-prog-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; }
.r-prog-name { display: flex; align-items: center; gap: 7px; font-size: 13px; color: #374151; font-weight: 500; }
.r-prog-dot  { width: 7px; height: 7px; border-radius: 50%; }
.r-prog-num  { font-size: 13px; font-weight: 700; color: #111827; }
.r-prog-track { height: 5px; background: #f3f4f6; border-radius: 99px; margin-bottom: 14px; overflow: hidden; }
.r-prog-fill  { height: 100%; border-radius: 99px; }
.r-prog-footer { display: flex; justify-content: space-between; margin-top: 4px; }
.r-pill { padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }

/* Contribution grid */
.r-cg {
    display: flex;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 18px;
}
.r-cg-cell { flex: 1; padding: 14px 12px; text-align: center; }
.r-cg-cell + .r-cg-cell { border-left: 1px solid #e5e7eb; }
.r-cg-label { font-size: 9.5px; font-weight: 600; letter-spacing: 0.09em; text-transform: uppercase; color: #9ca3af; display: block; margin-bottom: 5px; }
.r-cg-val   { font-size: 22px; font-weight: 800; color: #111827; letter-spacing: -0.5px; }

.r-share-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.r-share-row span:first-child { font-size: 12px; color: #6b7280; }
.r-share-row span:last-child  { font-size: 12px; font-weight: 700; color: #111827; }

/* Leaderboard */
.r-lb-row { display: flex; align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px solid #f3f4f6; }
.r-lb-row:last-child { border-bottom: none; }
.r-lb-rank { font-size: 11px; font-weight: 700; color: #9ca3af; min-width: 22px; }
.r-lb-rank.r1 { color: #d97706; }
.r-lb-rank.r2 { color: #6b7280; }
.r-lb-rank.r3 { color: #92400e; }
.r-lb-name    { font-size: 13px; font-weight: 500; color: #374151; min-width: 150px; }
.r-lb-track   { flex: 1; height: 5px; background: #f3f4f6; border-radius: 99px; overflow: hidden; }
.r-lb-fill    { height: 100%; border-radius: 99px; background: #7c3aed; }
.r-lb-num     { font-size: 13px; font-weight: 700; color: #111827; min-width: 28px; text-align: right; }

/* Trend */
.r-trend-wrap { display: flex; align-items: flex-end; gap: 8px; height: 80px; }
.trend-bar-col { display: flex; flex-direction: column; align-items: center; gap: 4px; flex: 1; }
.trend-bar-block { width: 100%; border-radius: 3px 3px 0 0; min-height: 4px; }
.r-tv  { font-size: 10px; color: #9ca3af; }
.r-td  { font-size: 10px; color: #9ca3af; }
.r-td.today { color: #2563eb; font-weight: 700; }
</style>

<div class="page-container">
<div class="main-content reporting-wrap">

    <div class="page-header mb-4">
        <h2 class="header-title">Reporting Overview</h2>
        <small><?= date('l, d F Y') ?></small>
    </div>

    <!-- ── Overall Digitization ─────────────────────────────── -->
    <p class="r-lbl">Overall Digitization</p>
    <div class="row g-3 mb-4">

        <div class="col-md-4">
            <div class="r-card ra-blue">
                <div class="card-body">
                    <div class="r-kpi">
                        <div class="r-icon lg ri-blue"><i class="anticon anticon-file-text"></i></div>
                        <div>
                            <div class="r-kpi-label">Total Forms Digitized</div>
                            <div class="r-kpi-value"><?= $total_forms ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="r-card ra-green">
                <div class="card-body">
                    <div class="r-kpi">
                        <div class="r-icon lg ri-green"><i class="anticon anticon-user"></i></div>
                        <div>
                            <div class="r-kpi-label">Child Health Forms</div>
                            <div class="r-kpi-value"><?= $child_health_total ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="r-card ra-amber">
                <div class="card-body">
                    <div class="r-kpi">
                        <div class="r-icon lg ri-amber"><i class="anticon anticon-team"></i></div>
                        <div>
                            <div class="r-kpi-label">OPD / MNCH Forms</div>
                            <div class="r-kpi-value"><?= $opd_total ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ── Activity Snapshot ────────────────────────────────── -->
    <p class="r-lbl">Activity Snapshot</p>
    <div class="row g-3 mb-4">

        <div class="col-md-3">
            <div class="r-card ra-cyan">
                <div class="card-body">
                    <div class="r-snap">
                        <div class="r-icon sm ri-cyan"><i class="anticon anticon-calendar"></i></div>
                        <div>
                            <div class="r-snap-label">Today</div>
                            <div class="r-snap-value"><?= $today_total ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="r-card ra-violet">
                <div class="card-body">
                    <div class="r-snap">
                        <div class="r-icon sm ri-violet"><i class="anticon anticon-bar-chart"></i></div>
                        <div>
                            <div class="r-snap-label">Last 7 Days</div>
                            <div class="r-snap-value"><?= $last_7_days_total ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="r-card ra-teal">
                <div class="card-body">
                    <div class="r-snap">
                        <div class="r-icon sm ri-teal"><i class="anticon anticon-calendar"></i></div>
                        <div>
                            <div class="r-snap-label">This Month</div>
                            <div class="r-snap-value"><?= $this_month_total ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="r-card ra-rose">
                <div class="card-body">
                    <div class="r-snap">
                        <div class="r-icon sm ri-rose"><i class="anticon anticon-rise"></i></div>
                        <div>
                            <div class="r-snap-label">Daily Average</div>
                            <div class="r-snap-value"><?= $daily_avg ?></div>
                            <div class="r-snap-sub">last 30 days</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- ── Form Type & Contribution ─────────────────────────── -->
    <p class="r-lbl">Form Type &amp; <?= $contrib_label ?></p>
    <div class="row g-3 mb-4">

        <!-- Form Type Breakdown -->
        <div class="col-md-6">
            <div class="r-card h-100">
                <div class="card-body">
                    <div class="r-card-title">Form Type Breakdown</div>
                    <?php
                        $ch_pct  = $total_forms > 0 ? round($child_health_total / $total_forms * 100) : 0;
                        $opd_pct = 100 - $ch_pct;
                    ?>
                    <div class="r-prog-row">
                        <span class="r-prog-name"><span class="r-prog-dot" style="background:#2563eb;"></span>Child Health</span>
                        <span class="r-prog-num"><?= $child_health_total ?></span>
                    </div>
                    <div class="r-prog-track">
                        <div class="r-prog-fill" style="width:<?= $ch_pct ?>%; background:#2563eb;"></div>
                    </div>
                    <div class="r-prog-row">
                        <span class="r-prog-name"><span class="r-prog-dot" style="background:#d97706;"></span>OPD / MNCH</span>
                        <span class="r-prog-num"><?= $opd_total ?></span>
                    </div>
                    <div class="r-prog-track">
                        <div class="r-prog-fill" style="width:<?= $opd_pct ?>%; background:#d97706;"></div>
                    </div>
                    <div class="r-prog-footer">
                        <span class="r-pill" style="background:#eff6ff; color:#2563eb;"><?= $ch_pct ?>% CH</span>
                        <span class="r-pill" style="background:#fffbeb; color:#d97706;"><?= $opd_pct ?>% OPD</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contribution / Summary -->
        <div class="col-md-6">
            <div class="r-card h-100">
                <div class="card-body">
                    <div class="r-card-title"><?= $contrib_label ?></div>

                    <!-- Role badge -->
                    <?php if ($is_personal_view): ?>
                        <div class="r-role-badge personal">
                            <i class="anticon anticon-user"></i> Your personal stats
                        </div>
                    <?php else: ?>
                        <div class="r-role-badge overview">
                            <i class="anticon anticon-team"></i> All operators — full data
                        </div>
                    <?php endif; ?>

                    <!-- Stats grid -->
                    <div class="r-cg">
                        <div class="r-cg-cell">
                            <span class="r-cg-label">All Time</span>
                            <div class="r-cg-val"><?= $my_total_forms ?></div>
                        </div>
                        <div class="r-cg-cell">
                            <span class="r-cg-label">Today</span>
                            <div class="r-cg-val"><?= $my_today_total ?></div>
                        </div>
                        <div class="r-cg-cell">
                            <span class="r-cg-label">Last 7 Days</span>
                            <div class="r-cg-val"><?= $my_last_7_days ?></div>
                        </div>
                    </div>

                    <?php $share = $total_forms > 0 ? round($my_total_forms / $total_forms * 100) : 0; ?>
                    <div class="r-share-row">
                        <span><?= $is_personal_view ? 'My share of total digitized' : 'Coverage of total records' ?></span>
                        <span><?= $share ?>%</span>
                    </div>
                    <div class="r-prog-track">
                        <div class="r-prog-fill" style="width:<?= $share ?>%; background:#7c3aed;"></div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- ── Top Operators Today ──────────────────────────────── -->
    <p class="r-lbl">Top Operators — Today</p>
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="r-card">
                <div class="card-body">
                    <div class="r-card-title">Leaderboard</div>
                    <?php if (!empty($top_operators)): ?>
                        <?php $max_c = max(1, $top_operators[0]['count']); ?>
                        <?php foreach ($top_operators as $i => $op): ?>
                        <div class="r-lb-row">
                            <span class="r-lb-rank <?= $i < 3 ? 'r'.($i+1) : '' ?>"><?= str_pad($i+1,2,'0',STR_PAD_LEFT) ?></span>
                            <span class="r-lb-name"><?= htmlspecialchars($op['name']) ?></span>
                            <div class="r-lb-track">
                                <div class="r-lb-fill" style="width:<?= round($op['count']/$max_c*100) ?>%;"></div>
                            </div>
                            <span class="r-lb-num"><?= $op['count'] ?></span>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted text-center mb-0" style="font-size:13px; padding:16px 0;">No entries recorded today yet.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- ── 7-Day Trend ──────────────────────────────────────── -->
    <p class="r-lbl">Last 7 Days — Daily Trend</p>
    <div class="row g-3">
        <div class="col-md-12">
            <div class="r-card">
                <div class="card-body">
                    <div class="r-card-title">Daily Trend</div>
                    <?php
                        $max_d = 1;
                        foreach ($last_7_days_trend as $d) { if ($d['count'] > $max_d) $max_d = $d['count']; }
                    ?>
                    <div class="r-trend-wrap">
                        <?php foreach ($last_7_days_trend as $d):
                            $bh = max(5, round($d['count'] / $max_d * 60));
                            $it = ($d['date'] === date('Y-m-d'));
                        ?>
                        <div class="trend-bar-col">
                            <span class="r-tv"><?= $d['count'] ?></span>
                            <div class="trend-bar-block" style="height:<?= $bh ?>px; background:<?= $it ? '#2563eb' : '#bfdbfe' ?>;"></div>
                            <span class="r-td <?= $it ? 'today' : '' ?>"><?= date('D', strtotime($d['date'])) ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>