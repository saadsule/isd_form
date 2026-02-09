<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= isset($question) ? 'Edit Question' : 'Add Question' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .option-item {
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 10px;
            position: relative;
        }
        .remove-option {
            position: absolute;
            top: 5px;
            right: 5px;
        }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="card shadow-sm">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0"><?= isset($question) ? 'Edit Question' : 'Add Question' ?></h4>
        </div>
        <div class="card-body">
            <form method="post">
                <div class="mb-3">
                    <label>Form Type</label>
                    <input type="text" name="form_type" class="form-control" value="<?= isset($question->form_type) ? $question->form_type : '' ?>" >
                </div>

                <div class="mb-3">
                    <label>Section</label>
                    <input type="text" name="q_section" class="form-control" value="<?= isset($question->q_section) ? $question->q_section : '' ?>" >
                </div>

                <div class="mb-3">
                    <label>Question Number</label>
                    <input type="text" name="q_num" class="form-control" value="<?= isset($question->q_num) ? $question->q_num : '' ?>" >
                </div>

                <div class="mb-3">
                    <label>Question Text</label>
                    <textarea name="q_text" class="form-control" ><?= isset($question->q_text) ? $question->q_text : '' ?></textarea>
                </div>

                <div class="mb-3">
                    <label>Question Order</label>
                    <input type="number" name="q_order" class="form-control" value="<?= isset($question->q_order) ? $question->q_order : 1 ?>" >
                </div>

                <div class="mb-3">
                    <label>Question Type</label>
                    <select name="q_type" class="form-select" >
                        <option value="text" <?= (isset($question) && $question->q_type=='text')?'selected':'' ?>>Text</option>
                        <option value="radio" <?= (isset($question) && $question->q_type=='radio')?'selected':'' ?>>Single Choice</option>
                        <option value="checkbox" <?= (isset($question) && $question->q_type=='checkbox')?'selected':'' ?>>Multiple Choice</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Options (for choice questions)</label>
                    <div id="options-wrapper">
                        <?php
                        if(isset($question->options) && !empty($question->options)){
                            foreach($question->options as $opt){ ?>
                                <div class="option-item">
                                    <input type="hidden" name="options[<?= $opt->option_id ?>][option_id]" value="<?= $opt->option_id ?>">
                                    <input type="text" name="options[<?= $opt->option_id ?>][text]" class="form-control mb-1" value="<?= isset($opt->option_text) ? $opt->option_text : '' ?>" placeholder="Option text">
                                    <input type="number" name="options[<?= $opt->option_id ?>][order]" class="form-control mb-1" value="<?= isset($opt->option_order) ? $opt->option_order : '' ?>" placeholder="Option order">
                                    <button type="button" class="btn btn-danger btn-sm remove-option">Remove</button>
                                </div>
                        <?php }} ?>
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-option">Add Option</button>
                </div>

                <button class="btn btn-success"><?= isset($question) ? 'Update' : 'Add' ?> Question</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('add-option').addEventListener('click', function(){
    let wrapper = document.getElementById('options-wrapper');
    let index = Date.now();
    let html = `
        <div class="option-item">
            <input type="text" name="options[${index}][text]" class="form-control mb-1" placeholder="Option text">
            <input type="number" name="options[${index}][order]" class="form-control mb-1" placeholder="Option order">
            <button type="button" class="btn btn-danger btn-sm remove-option">Remove</button>
        </div>`;
    wrapper.insertAdjacentHTML('beforeend', html);
});

document.addEventListener('click', function(e){
    if(e.target && e.target.classList.contains('remove-option')){
        e.target.closest('.option-item').remove();
    }
});
</script>
</body>
</html>
