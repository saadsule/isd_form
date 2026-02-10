<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h1 class="header-title">Child Health Form</h1>
</div>

<form method="post" action="<?= base_url('forms/save_child_health') ?>">

<div class="card">
<div class="card-body">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

    <img src="<?= base_url('assets/images/logo/kp_logo.png') ?>" style="max-height:60px;">

    <img src="<?= base_url('assets/images/logo/integral_global.png') ?>" style="max-height:60px;">
    <img src="<?= base_url('assets/images/logo/dsi_logo.png') ?>" style="max-height:60px;">
    <img src="<?= base_url('assets/images/logo/pf.png') ?>" style="max-height:60px;">
</div>


<!-- BASIC INFO -->
<div class="card mb-4">
    <div class="card-body">
        
        <div class="text-right">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="visit_type" value="Fixed Site" required="">
                <label class="form-check-label">Fixed Site</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="visit_type" value="Outreach" required="">
                <label class="form-check-label">Outreach</label>
            </div>
        </div>

        <h4 class="mb-4">Basic Information</h4>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Date <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="date" name="form_date" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">QR Code <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="qr_code" class="form-control" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Client Type <span style="color:red;">*</span></label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="client_type" value="New" required>
                    <label class="form-check-label">New Client</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="client_type" value="Followup" required>
                    <label class="form-check-label">Follow-up</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">District <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="district" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">UC <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="uc" class="form-control" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">HF/Village <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="village" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">Vaccinator <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="vaccinator_name" class="form-control" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Patient Name <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="patient_name" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">Father/Husband <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="guardian_name" class="form-control" required>
            </div>
        </div>

    </div>
</div>



<!-- DEMOGRAPHICS -->
<div class="card mb-4">
    <div class="card-body">

        <h4 class="mb-4">Demographics</h4>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Date of Birth <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="date" name="dob" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">Age <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-row">
                    <div class="col">
                        <input type="number" name="age_year" class="form-control" placeholder="Years" required>
                    </div>
                    <div class="col">
                        <input type="number" name="age_month" class="form-control" placeholder="Months" required>
                    </div>
                    <div class="col">
                        <input type="number" name="age_day" class="form-control" placeholder="Days" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Age Group <span style="color:red;">*</span></label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="age_group[]" value="<1 Year" required>
                    <label class="form-check-label">&lt;1 Year</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="age_group[]" value="1-2 Year" required>
                    <label class="form-check-label">1-2 Year</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="age_group[]" value="2-5 Year" required>
                    <label class="form-check-label">2-5 Year</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="age_group[]" value="15-49 Year" required>
                    <label class="form-check-label">15-49 Year</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Gender <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Male" required>
                    <label class="form-check-label">Male</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="gender" value="Female" required>
                    <label class="form-check-label">Female</label>
                </div>
            </div>

            <label class="col-sm-2 col-form-label">Marital Status <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="marital_status" value="Married" required>
                    <label class="form-check-label">Married</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="marital_status" value="Unmarried" required>
                    <label class="form-check-label">Un-Married</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Pregnancy <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pregnancy_status" value="Pregnant" required>
                    <label class="form-check-label">Pregnant</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="pregnancy_status" value="Non-Pregnant" required>
                    <label class="form-check-label">Non-Pregnant</label>
                </div>
            </div>

            <label class="col-sm-2 col-form-label">Disability <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="disability" value="Yes" required>
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="disability" value="No" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Play & Learning Kit <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="play_learning_kit" value="Yes" required>
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="play_learning_kit" value="No" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>

            <label class="col-sm-2 col-form-label">Nutrition Package <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="nutrition_package" value="Yes" required>
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="nutrition_package" value="No" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- DYNAMIC QUESTIONS -->
<?php
$sections = [];
foreach($questions as $q){
    $sections[$q->q_section][] = $q;
}
?>

<?php foreach($sections as $section_name => $section_questions): ?>

<div class="card mb-4">
    <div class="card-body">

        <h4 class="mb-4"><?= htmlspecialchars($section_name) ?></h4>

        <?php foreach($section_questions as $q): ?>

        <div class="form-group row">
            <label class="col-sm-5 col-form-label">
                <?= htmlspecialchars($q->q_num.' '.$q->q_text) ?>
            </label>

            <div class="col-sm-7">

                <?php $options = isset($q->options) ? $q->options : array(); ?>

                <?php if($q->q_type === 'text'): ?>

                    <?php if(!empty($options)): ?>
                        <div class="form-row">
                            <?php foreach($options as $opt): ?>
                                <div class="col-auto mb-2">
                                    <small><?= htmlspecialchars($opt->option_text) ?></small>
                                    <input type="text"
                                           name="question[<?= $q->question_id ?>][<?= $opt->option_id ?>]"
                                           class="form-control">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <input type="text"
                               name="question[<?= $q->question_id ?>][0]"
                               class="form-control">
                    <?php endif; ?>

                <?php elseif($q->q_type === 'checkbox' && count($options) > 1): ?>
                    <!-- Multiple checkboxes in horizontal layout -->
                    <div class="d-flex flex-wrap gap-3">
                        <?php foreach($options as $opt): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input"
                                       type="checkbox"
                                       name="question[<?= $q->question_id ?>][]"
                                       value="<?= $opt->option_id ?>"
                                       id="q<?= $q->question_id ?>_<?= $opt->option_id ?>">
                                <label class="form-check-label" for="q<?= $q->question_id ?>_<?= $opt->option_id ?>">
                                    <?= htmlspecialchars($opt->option_text) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                <?php elseif($q->q_type === 'radio'): ?>
                    <!-- Radios as usual -->
                    <?php foreach($options as $opt): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input"
                                   type="radio"
                                   name="question[<?= $q->question_id ?>]"
                                   value="<?= $opt->option_id ?>"
                                   id="q<?= $q->question_id ?>_<?= $opt->option_id ?>">
                            <label class="form-check-label" for="q<?= $q->question_id ?>_<?= $opt->option_id ?>">
                                <?= htmlspecialchars($opt->option_text) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                <?php elseif($q->q_type === 'checkbox'): ?>
                    <!-- Single checkbox fallback -->
                    <?php foreach($options as $opt): ?>
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="question[<?= $q->question_id ?>][]"
                                   value="<?= $opt->option_id ?>"
                                   id="q<?= $q->question_id ?>_<?= $opt->option_id ?>">
                            <label class="form-check-label" for="q<?= $q->question_id ?>_<?= $opt->option_id ?>">
                                <?= htmlspecialchars($opt->option_text) ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            </div>
        </div>

        <?php endforeach; ?>

    </div>
</div>

<?php endforeach; ?>

<div class="text-center mb-5">
<button type="submit" class="btn btn-success btn-lg">
<i class="anticon anticon-save"></i> Submit Form
</button>
</div>

</div>
</div>

</form>

</div>
