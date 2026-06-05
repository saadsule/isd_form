<style>
/* ── RIS Report Styles ── */
.ris-wrap {
    background: #f4f6f9;
    padding: 20px;
}

.ris-header-card {
    background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 60%, #3d5a80 100%);
    border-radius: 14px;
    padding: 24px 32px;
    margin-bottom: 24px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    box-shadow: 0 8px 24px rgba(29,41,120,.25);
}
.ris-header-card .ris-title {
    font-size: 22px;
    font-weight: 800;
    letter-spacing: .5px;
    margin: 0;
}
.ris-header-card .ris-subtitle {
    font-size: 13px;
    opacity: .75;
    margin-top: 4px;
}
.ris-header-card .ris-badge-count {
    text-align: center;
    border-left: 1px solid rgba(255,255,255,.2);
    padding-left: 28px;
}
.ris-header-card .ris-badge-count .num {
    font-size: 30px;
    font-weight: 700;
}
.ris-header-card .ris-badge-count .lbl {
    font-size: 11px;
    opacity: .7;
    text-transform: uppercase;
    letter-spacing: .5px;
}

/* Schedule Table */
.ris-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 6px 18px rgba(0,0,0,.07);
    overflow: hidden;
    margin-bottom: 24px;
}
.ris-card .card-header {
    background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 100%);
    padding: 14px 22px;
    border: none;
}
.ris-card .card-header h5 {
    color: #fff;
    font-weight: 700;
    font-size: 15px;
    margin: 0;
}

.ris-table {
    width: 100%;
    border-collapse: collapse;
    margin: 0;
}
.ris-table thead tr {
    background: linear-gradient(135deg, #1a1f4e 0%, #2d3585 100%);
}
.ris-table thead th {
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    padding: 12px 16px;
    border: none;
    text-align: center;
}
.ris-table thead th:first-child { text-align: left; }

.ris-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #edf0f4;
    font-size: 13px;
    vertical-align: middle;
}
.ris-table tbody tr:last-child td { border-bottom: none; }
.ris-table tbody tr:hover { background: #f8f9ff; }

/* Visit label cell */
.visit-label {
    font-weight: 700;
    font-size: 13px;
    color: #fff;
    padding: 6px 14px;
    border-radius: 20px;
    display: inline-block;
    white-space: nowrap;
}
.v-birth  { background: #e84393; }
.v-2      { background: #f07c1a; }
.v-3      { background: #29a8e0; }
.v-4      { background: #7b5ea7; }
.v-5      { background: #27ae60; }
.v-6      { background: #8e44ad; }

/* Age badge */
.age-badge {
    background: #eef2ff;
    color: #2d3585;
    font-weight: 700;
    font-size: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    display: inline-block;
    border: 1px solid #c5cdf8;
}

/* Vaccine pills */
.vacc-group {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    justify-content: center;
}
.vacc-pill {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}
.pill-pink   { background: #fde8f3; color: #b5006e; border: 1px solid #f5b8dc; }
.pill-orange { background: #fef0e0; color: #c05c00; border: 1px solid #fad0a0; }
.pill-blue   { background: #e0f3fc; color: #0d6e9a; border: 1px solid #a8d8f0; }
.pill-purple { background: #f0eafc; color: #5a2d91; border: 1px solid #d0b8f5; }
.pill-green  { background: #e5f7ed; color: #1a6b3c; border: 1px solid #a8dfc0; }
.pill-violet { background: #ede8fb; color: #4a1d8a; border: 1px solid #c5b3f0; }

/* Note card */
.ris-note {
    background: #fffbea;
    border: 1px solid #f0d060;
    border-radius: 10px;
    padding: 12px 18px;
    font-size: 12px;
    color: #7d6608;
    margin-bottom: 24px;
}
.ris-note i { margin-right: 6px; }

@media print {
    .no-print { display: none !important; }
    .ris-wrap { background: #fff !important; padding: 0 !important; }
    .ris-card { box-shadow: none !important; border: 1px solid #ddd !important; }
}
</style>

<div class="page-container">
<div class="main-content ris-wrap">

    <!-- HEADER -->
    <div class="ris-header-card">
        <div>
            <div class="ris-title">
                <i class="fa fa-syringe"></i> &nbsp;Routine Immunisation Schedule
            </div>
            <div class="ris-subtitle">Standard EPI schedule — Vaccines by visit and age</div>
        </div>
        <div class="ris-badge-count">
            <div class="num">6</div>
            <div class="lbl">Visits</div>
        </div>
    </div>

    <!-- NOTE -->
    <div class="ris-note">
        <i class="fa fa-info-circle"></i>
        <strong>Note:</strong> This schedule is based on the Expanded Programme on Immunization (EPI).
        All vaccines should be administered at the recommended age for optimal protection.
    </div>

    <!-- SCHEDULE TABLE -->
    <div class="ris-card">
        <div class="card-header">
            <h5><i class="fa fa-calendar-check-o"></i> &nbsp;Vaccination Schedule by Visit</h5>
        </div>
        <div class="table-responsive">
            <table class="ris-table">
                <thead>
                    <tr>
                        <th style="width:130px; text-align:left;">Visit</th>
                        <th style="width:110px;">Age</th>
                        <th>Vaccines to be Given</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- At Birth -->
                    <tr>
                        <td><span class="visit-label v-birth">At Birth</span></td>
                        <td class="text-center"><span class="age-badge">At Birth</span></td>
                        <td>
                            <div class="vacc-group">
                                <span class="vacc-pill pill-pink">BCG</span>
                                <span class="vacc-pill pill-pink">OPV-0</span>
                                <span class="vacc-pill pill-pink">Hep-B</span>
                            </div>
                        </td>
                    </tr>
                    <!-- 2nd Visit -->
                    <tr>
                        <td><span class="visit-label v-2">2nd Visit</span></td>
                        <td class="text-center"><span class="age-badge">6 Weeks</span></td>
                        <td>
                            <div class="vacc-group">
                                <span class="vacc-pill pill-orange">OPV-I</span>
                                <span class="vacc-pill pill-orange">Pneumococcal-I</span>
                                <span class="vacc-pill pill-orange">Rotavirus-I</span>
                                <span class="vacc-pill pill-orange">Pentavalent-I</span>
                            </div>
                        </td>
                    </tr>
                    <!-- 3rd Visit -->
                    <tr>
                        <td><span class="visit-label v-3">3rd Visit</span></td>
                        <td class="text-center"><span class="age-badge">10 Weeks</span></td>
                        <td>
                            <div class="vacc-group">
                                <span class="vacc-pill pill-blue">OPV-II</span>
                                <span class="vacc-pill pill-blue">Pneumococcal-II</span>
                                <span class="vacc-pill pill-blue">Rotavirus-II</span>
                                <span class="vacc-pill pill-blue">Pentavalent-II</span>
                            </div>
                        </td>
                    </tr>
                    <!-- 4th Visit -->
                    <tr>
                        <td><span class="visit-label v-4">4th Visit</span></td>
                        <td class="text-center"><span class="age-badge">14 Weeks</span></td>
                        <td>
                            <div class="vacc-group">
                                <span class="vacc-pill pill-purple">OPV-III</span>
                                <span class="vacc-pill pill-purple">Pneumococcal-III</span>
                                <span class="vacc-pill pill-purple">IPV-I</span>
                                <span class="vacc-pill pill-purple">Pentavalent-III</span>
                            </div>
                        </td>
                    </tr>
                    <!-- 5th Visit -->
                    <tr>
                        <td><span class="visit-label v-5">5th Visit</span></td>
                        <td class="text-center"><span class="age-badge">9 Months</span></td>
                        <td>
                            <div class="vacc-group">
                                <span class="vacc-pill pill-green">MR-I</span>
                                <span class="vacc-pill pill-green">Typhoid</span>
                                <span class="vacc-pill pill-green">IPV-II</span>
                            </div>
                        </td>
                    </tr>
                    <!-- 6th Visit -->
                    <tr>
                        <td><span class="visit-label v-6">6th Visit</span></td>
                        <td class="text-center"><span class="age-badge">15 Months</span></td>
                        <td>
                            <div class="vacc-group">
                                <span class="vacc-pill pill-violet">MR-II</span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="row">
        <div class="col-md-4">
            <div class="card" style="border:none; border-radius:12px; box-shadow:0 4px 14px rgba(0,0,0,.07);">
                <div class="card-body text-center" style="padding:20px;">
                    <div style="font-size:28px; font-weight:800; color:#e84393;">3</div>
                    <div style="font-size:12px; color:#666; text-transform:uppercase; letter-spacing:.5px; margin-top:4px;">Vaccines at Birth</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border:none; border-radius:12px; box-shadow:0 4px 14px rgba(0,0,0,.07);">
                <div class="card-body text-center" style="padding:20px;">
                    <div style="font-size:28px; font-weight:800; color:#2d3585;">14</div>
                    <div style="font-size:12px; color:#666; text-transform:uppercase; letter-spacing:.5px; margin-top:4px;">Total Vaccine Doses</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card" style="border:none; border-radius:12px; box-shadow:0 4px 14px rgba(0,0,0,.07);">
                <div class="card-body text-center" style="padding:20px;">
                    <div style="font-size:28px; font-weight:800; color:#27ae60;">15 Mo</div>
                    <div style="font-size:12px; color:#666; text-transform:uppercase; letter-spacing:.5px; margin-top:4px;">Full Coverage Age</div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>