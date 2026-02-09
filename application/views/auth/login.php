<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Health MIS Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

body{
    height:100vh;
    background:linear-gradient(120deg,#e8f2ff,#f6fbff);
    display:flex;
    justify-content:center;
    align-items:center;
    font-family: 'Segoe UI', sans-serif;
}

/* Login Card */
.login-container{
    width:900px;
    background:white;
    border-radius:18px;
    box-shadow:0 20px 60px rgba(0,0,0,.08);
    overflow:hidden;
}

/* Left Panel */
.left-panel{
    background:linear-gradient(135deg,#2c7be5,#1559c1);
    color:white;
    padding:60px 40px;
}

.left-panel h2{
    font-weight:700;
}

.left-panel p{
    opacity:.9;
}

/* Logos */
.logo-row {
    display: flex;                /* Flex layout */
    justify-content: center;      /* Center logos horizontally */
    align-items: center;          /* Center logos vertically */
    gap: 20px;                    /* Space between logos */
    margin-bottom: 20px;
}

.logo-row img {
    height: 65px;                 /* Logo height */
    object-fit: contain;
}


/* Form Side */
.form-section{
    padding:50px;
}

.form-control{
    height:50px;
    border-radius:10px;
}

.btn-login{
    height:50px;
    border-radius:10px;
    font-weight:600;
    font-size:18px;
}

.portal-title{
    font-weight:700;
    color:#2c7be5;
    text-align: center;
}

@media(max-width:768px){
    .left-panel{
        display:none;
    }

    .login-container{
        width:95%;
    }
}

</style>
</head>

<body>

<div class="login-container row g-0">

    <!-- LEFT DESIGN PANEL -->
    <div class="col-md-6 left-panel">

        <h2>Integrated Service Delivery (ISD) Monitoring System</h2>

        <p>
            Digital platform designed to support Integrated Service Delivery (ISD) 
            monitoring in North Waziristan by enabling secure data collection, 
            automated data flow, validation, and interactive dashboard reporting.
        </p>

        <p class="mt-4">
            ✔ Digitized Data Collection  
            <br>✔ Automated Dashboard & Analytics  
            <br>✔ Data Quality & Validation  
            <br>✔ Field Monitoring Support  
        </p>

    </div>


    <!-- LOGIN FORM -->
    <div class="col-md-6 form-section">

        <div class="logo-row">
            <!-- Replace with your logos -->
            <img src="<?= base_url('assets/images/logo/kp_logo.png'); ?>">
            <img src="<?= base_url('assets/images/logo/pf.png'); ?>">
        </div>
        
        <h3 class="portal-title mb-4">Portal Login</h3>

        <?php if($this->session->flashdata('error')): ?>
            <div class="alert alert-danger">
                <?= $this->session->flashdata('error'); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('auth/login'); ?>">

            <div class="mb-3">
                <label class="mb-2">Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>

            <div class="mb-4">
                <label class="mb-2">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button class="btn btn-primary w-100 btn-login">
                Login
            </button>

        </form>

    </div>

</div>

</body>
</html>
