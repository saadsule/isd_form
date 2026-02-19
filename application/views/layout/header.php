<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>ISD - Integrated Service Delivery</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url('assets/images/logo/logo-fold.png') ?>">

    <!-- page css -->

    <!-- Core css -->
    <link href="<?php echo base_url('assets/css/app.min.css" rel="stylesheet') ?>">
    
    <!-- page css -->
    <link href="<?php echo base_url('assets/vendors/datatables/dataTables.bootstrap.min.css') ?>" rel="stylesheet">
    
    <!-- page css -->
    <link href="<?php echo base_url('assets/vendors/select2/select2.css') ?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css') ?>" rel="stylesheet">
</head>

<body>
    <div class="app">
        <div class="layout">
            <!-- Header START -->
            <div class="header">
                <div class="logo logo-dark">
                    <a href="index.html">
                        <img src="<?php echo base_url('assets/images/logo/logo.png') ?>" 
                             alt="Logo" 
                             style="height:45px; width:auto;margin-top: 10px;">

                        <img class="logo-fold" 
                             src="<?php echo base_url('assets/images/logo/logo-fold.png') ?>" 
                             alt="Logo"
                             style="height:35px; width:auto;margin-top: 10px;">
                    </a>
                </div>

                <div class="logo logo-white">
                    <a href="index.html">
                        <img src="<?php echo base_url('assets/images/logo/logo-white.png') ?>" 
                             alt="Logo" 
                             style="height:45px; width:auto;margin-top: 10px;">

                        <img class="logo-fold" 
                             src="<?php echo base_url('assets/images/logo/logo-fold-white.png') ?>" 
                             alt="Logo"
                             style="height:35px; width:auto;margin-top: 10px;">
                    </a>
                </div>

                <div class="nav-wrap">
                    <ul class="nav-left">
                        <li class="desktop-toggle">
                            <a href="javascript:void(0);">
                                <i class="anticon"></i>
                            </a>
                        </li>
                        <li class="mobile-toggle">
                            <a href="javascript:void(0);">
                                <i class="anticon"></i>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav-right">
                        <li class="dropdown dropdown-animated scale-left">
                            <div class="pointer" data-toggle="dropdown">
                                <div class="avatar avatar-image  m-h-10 m-r-15">
                                    <img src="<?php echo base_url('assets/images/usericon.png') ?>"  alt="">
                                </div>
                            </div>
                            <div class="p-b-15 p-t-20 dropdown-menu pop-profile">
                                <div class="p-h-20 p-b-15 m-b-10 border-bottom">
                                    <div class="d-flex m-r-50">
                                        <div class="avatar avatar-lg avatar-image">
                                            <img src="<?php echo base_url('assets/images/usericon.png') ?>" alt="">
                                        </div>
                                        <div class="m-l-10">
                                            <p class="m-b-0 text-dark font-weight-semibold">
                                                <?= htmlspecialchars($this->session->userdata('full_name')) ?>
                                            </p>
                                            <p class="m-b-0 opacity-07">
                                                <?php
                                                $role = $this->session->userdata('role'); 
                                                if($role == 1){
                                                    echo 'Data Entry User';
                                                } elseif($role == 2){
                                                    echo 'Admin User';
                                                } else {
                                                    echo 'User';
                                                }
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <a href="<?= base_url('auth/logout') ?>" class="dropdown-item d-block p-h-15 p-v-10">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <i class="anticon opacity-04 font-size-16 anticon-logout"></i>
                                            <span class="m-l-10">Logout</span>
                                        </div>
                                        <i class="anticon font-size-10 anticon-right"></i>
                                    </div>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>    
            <!-- Header END -->