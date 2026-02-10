<div class="page-container">
<div class="main-content">

<div class="page-header">
    <h1 class="header-title">OPD MNCH Form</h1>
</div>

<form method="post" action="<?php echo base_url('forms/save_opd_mnch'); ?>">

<div class="card">
<div class="card-body">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <img src="<?php echo base_url('assets/images/logo/kp_logo.png'); ?>" style="max-height:65px;">
    
    <img src="<?= base_url('assets/images/logo/integral_global.png') ?>" style="max-height:60px;">
    <img src="<?= base_url('assets/images/logo/dsi_logo.png') ?>" style="max-height:60px;">
    <img src="<?= base_url('assets/images/logo/pf.png') ?>" style="max-height:60px;">
</div>



<!-- BASIC INFO -->
<div class="card mb-4">
    <div class="card-body">

        <div class="text-right">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="visit_type" value="OPD" required="">
                <label class="form-check-label">OPD</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="visit_type" value="MNCH" required="">
                <label class="form-check-label">MNCH</label>
            </div>
        </div>
        
        <h4 class="mb-4">Basic Information</h4>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Date <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="date" name="form_date" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">ANC Card# <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="anc_card_no" class="form-control" required>
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
            <label class="col-sm-2 col-form-label">Village <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="village" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">LHV Name <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="lhv_name" class="form-control" required>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Patient Name <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="patient_name" class="form-control" required>
            </div>

            <label class="col-sm-2 col-form-label">Guardian Name <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <input type="text" name="guardian_name" class="form-control" required>
            </div>
        </div>

    </div>
</div>




<!-- STATUS -->
<div class="card mb-4">
    <div class="card-body">

        <h4 class="mb-4">Patient Status</h4>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Age Group <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="age_group" value="<1" required>
                    <label class="form-check-label">&lt;1</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="age_group" value="1-5" required>
                    <label class="form-check-label">1-5</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="age_group" value="15-49" required>
                    <label class="form-check-label">15-49</label>
                </div>
            </div>

            <label class="col-sm-2 col-form-label">Marital Status <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="marital_status" value="Married" required>
                    <label class="form-check-label">Married</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="marital_status" value="Unmarried" required>
                    <label class="form-check-label">Unmarried</label>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-2 col-form-label">Pregnancy <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pregnancy_status" value="Pregnant" required>
                    <label class="form-check-label">Pregnant</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pregnancy_status" value="Non-Pregnant" required>
                    <label class="form-check-label">Non-Pregnant</label>
                </div>
            </div>

            <label class="col-sm-2 col-form-label">Disability <span style="color:red;">*</span></label>
            <div class="col-sm-4">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="disability" value="Yes" required>
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="disability" value="No" required>
                    <label class="form-check-label">No</label>
                </div>
            </div>
        </div>

    </div>
</div>




<!-- ================= DYNAMIC QUESTIONS ================= -->

<?php
$sections = array();

foreach($questions as $q){
    $sections[$q->q_section][] = $q;
}
?>

<?php foreach($sections as $section_name => $section_questions): ?>

<div class="card mb-4">
    <div class="card-body">

        <h4 class="mb-4">
            <?php echo htmlspecialchars($section_name); ?>
        </h4>

        <?php foreach($section_questions as $q): ?>

        <div class="form-group row">

            <label class="col-sm-5 col-form-label">
                <?php echo htmlspecialchars($q->q_num.' '.$q->q_text); ?>
            </label>

            <div class="col-sm-7">

                <?php
                $options = isset($q->options) ? $q->options : array();
                ?>

                <?php if($q->q_type == 'text'): ?>

                    <?php if(!empty($options)): ?>

                        <div class="form-row">
                            <?php foreach($options as $opt): ?>
                                <div class="col-auto mb-2">
                                    <small><?php echo htmlspecialchars($opt->option_text); ?></small>
                                    <input type="text"
                                           name="question[<?php echo $q->question_id; ?>][<?php echo $opt->option_id; ?>]"
                                           class="form-control">
                                </div>
                            <?php endforeach; ?>
                        </div>

                    <?php else: ?>

                        <input type="text"
                               name="question[<?php echo $q->question_id; ?>][0]"
                               class="form-control">

                    <?php endif; ?>

                <?php else: ?>

                    <?php if($q->q_type == 'checkbox' && count($options) > 1): ?>
                        <!-- Multiple checkboxes horizontally -->
                        <div class="d-flex flex-wrap gap-3">
                            <?php foreach($options as $opt): ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="question[<?php echo $q->question_id; ?>][]"
                                           value="<?php echo $opt->option_id; ?>"
                                           id="q<?php echo $q->question_id; ?>_<?php echo $opt->option_id; ?>">
                                    <label class="form-check-label" for="q<?php echo $q->question_id; ?>_<?php echo $opt->option_id; ?>">
                                        <?php echo htmlspecialchars($opt->option_text); ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <!-- Single checkbox or radio -->
                        <?php foreach($options as $opt): ?>
                            <div class="form-check">
                                <input class="form-check-input"
                                       type="<?php echo $q->q_type; ?>"
                                       name="question[<?php echo $q->question_id; ?>]<?php if($q->q_type=='checkbox') echo '[]'; ?>"
                                       value="<?php echo $opt->option_id; ?>"
                                       id="q<?php echo $q->question_id; ?>_<?php echo $opt->option_id; ?>">
                                <label class="form-check-label" for="q<?php echo $q->question_id; ?>_<?php echo $opt->option_id; ?>">
                                    <?php echo htmlspecialchars($opt->option_text); ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

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
</div>
