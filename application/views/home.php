<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ISD Forms Dashboard</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">

    <div class="text-center mb-4">
        <h2 class="fw-bold">Integrated Service Delivery (ISD) Forms Dashboard</h2>
        <p class="text-muted">Select a form to proceed</p>
    </div>

    <div class="row justify-content-center">

        <!-- Child Health Form Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title fw-semibold">Child Health Form</h5>
                    <p class="card-text text-muted">
                        Record and manage child health information
                    </p>
                    <a href="<?= base_url('forms/child_health') ?>" class="btn btn-primary">
                        Open Form
                    </a>
                </div>
            </div>
        </div>

        <!-- OPD & MNCH Form Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-body text-center">
                    <h5 class="card-title fw-semibold">OPD & MNCH Form</h5>
                    <p class="card-text text-muted">
                        OPD and MNCH patient visit information
                    </p>
                    <a href="<?= base_url('forms/opd_mnch') ?>" class="btn btn-success">
                        Open Form
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
