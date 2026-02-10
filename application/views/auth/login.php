<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Health MIS - Portal Login</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?= base_url('assets/images/logo/favicon.png'); ?>">

    <!-- Core CSS -->
    <link href="<?= base_url('assets/css/app.min.css'); ?>" rel="stylesheet">
</head>

<body>
    <div class="app">
        <div class="container-fluid p-0 h-100">
            <div class="row no-gutters h-100 full-height">

                <!-- LEFT IMAGE PANEL -->
                <div class="col-lg-5 d-none d-lg-flex bg" style="background-image:url('<?= base_url('assets/images/others/login-1.jpg'); ?>')">
                    <div class="d-flex h-100 p-h-40 p-v-15 flex-column justify-content-between">

                        <!-- Top Logo -->
                        <div class="d-flex justify-content-center mb-4">
                            <div style="background: white; display: inline-block; padding: 10px 30px; border-radius: 8px;">
                                <img src="<?= base_url('assets/images/logo/logo_transparent.png'); ?>" alt="Logo" style="height:50px;">
                            </div>
                        </div>

                        <!-- Middle Content -->
                        <div>
                            <h2 class="text-white m-b-20 font-weight-normal">Integrated Service Delivery (ISD) Monitoring System</h2>
                            <p class="text-white font-size-16 lh-2 w-80 opacity-08">
                                Digital platform designed to support Integrated Service Delivery (ISD) 
                                monitoring in North Waziristan by enabling secure data collection, 
                                automated data flow, validation, and interactive dashboard reporting.
                            </p>
                            <p class="text-white mt-4 opacity-08">
                                ✔ Digitized Data Collection<br>
                                ✔ Automated Dashboard & Analytics<br>
                                ✔ Data Quality & Validation<br>
                                ✔ Field Monitoring Support
                            </p>
                        </div>

                        <!-- Footer -->
                        <div class="d-flex justify-content-between">
                            <span class="text-white">© 2026 Health MIS</span>
                <!--            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-white text-link" href="#">Legal</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-white text-link" href="#">Privacy</a>
                                </li>
                            </ul>-->
                        </div>

                    </div>
                </div>

                <!-- LOGIN FORM PANEL -->
                <div class="col-lg-7 bg-white">
                    <div class="container h-100">
                        <div class="row no-gutters h-100 align-items-center">
                            <div class="col-md-8 col-lg-7 col-xl-6 mx-auto">

                                <!-- Portal Logos -->
                                <!-- Logos Section -->
                                <div class="text-center mb-4">

                                    <!-- Top KP Logo -->
                                    <div class="mb-4">
                                        <img src="<?= base_url('assets/images/logo/kp_logo.png'); ?>" alt="KP Logo" height="60">
                                    </div>

                                    <!-- Other Three Logos -->
                                    <div class="d-flex justify-content-center align-items-center gap-3">
                                        <img src="<?= base_url('assets/images/logo/pf.png'); ?>" alt="PF Logo" height="50">
                                        <img src="<?= base_url('assets/images/logo/integral_global.png'); ?>" alt="Integral Global Logo" height="50">
                                        <img src="<?= base_url('assets/images/logo/dsi_logo.png'); ?>" alt="DSI Logo" height="50">
                                    </div>

                                </div>


                                <h2 class="m-b-10">Portal Login</h2>
                                <p class="m-b-30">Enter your credentials to access the system</p>

                                <?php if($this->session->flashdata('error')): ?>
                                    <div class="alert alert-danger">
                                        <?= $this->session->flashdata('error'); ?>
                                    </div>
                                <?php endif; ?>

                                <form method="post" action="<?= base_url('auth/login'); ?>">
                                    <div class="form-group">
                                        <label class="font-weight-semibold" for="username">Username:</label>
                                        <div class="input-affix">
                                            <i class="prefix-icon anticon anticon-user"></i>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-weight-semibold" for="password">Password:</label>
                                        <!--<a class="float-right font-size-13 text-muted" href="#">Forgot Password?</a>-->
                                        <div class="input-affix m-b-10">
                                            <i class="prefix-icon anticon anticon-lock"></i>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="btn btn-primary w-100">Sign In</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Core Vendors JS -->
    <script src="<?= base_url('assets/js/vendors.min.js'); ?>"></script>

    <!-- Core JS -->
    <script src="<?= base_url('assets/js/app.min.js'); ?>"></script>
</body>

</html>
