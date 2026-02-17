<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">

            <?php $role = $this->session->userdata('role'); ?>

            <!-- Home (all roles) -->
            <li class="nav-item <?= ($this->router->fetch_class() == 'welcome') ? 'active' : '' ?>">
                <a href="<?= base_url('welcome/index'); ?>">
                    <span class="icon-holder">
                        <i class="anticon anticon-home"></i>
                    </span>
                    <span class="title">Home</span>
                </a>
            </li>

            <!-- Dashboard (exclude role 1) -->
            <?php if($role != 1): ?>
            <li class="nav-item <?= ($this->router->fetch_class() == 'dashboard') ? 'active' : '' ?>">
                <a href="<?= base_url('dashboard'); ?>">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Data Entry & View Data (role 1 & role 3) -->
            <?php if(in_array($role, [1,3])): ?>
                <!-- Data Entry -->
                <li class="nav-item dropdown 
                    <?= ($this->uri->segment(2) == 'opd_mnch' || $this->uri->segment(2) == 'child_health') ? 'open' : '' ?>">

                    <a class="dropdown-toggle" href="javascript:void(0);">
                        <span class="icon-holder">
                            <i class="anticon anticon-edit"></i>
                        </span>
                        <span class="title">Data Entry</span>
                        <span class="arrow">
                            <i class="arrow-icon"></i>
                        </span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="<?= ($this->uri->segment(2) == 'opd_mnch') ? 'active' : '' ?>">
                            <a href="<?= base_url('forms/opd_mnch'); ?>">
                                <span>OPD/MNCH - New Form</span>
                            </a>
                        </li>

                        <li class="<?= ($this->uri->segment(2) == 'child_health') ? 'active' : '' ?>">
                            <a href="<?= base_url('forms/child_health'); ?>">
                                <span>Child Health - New Form</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <?php if(in_array($role, [1,2,3])): ?>
                <!-- View Data -->
                <li class="nav-item dropdown 
                    <?= ($this->uri->segment(2) == 'opd_report' || $this->uri->segment(2) == 'child_health_report') ? 'open' : '' ?>">

                    <a class="dropdown-toggle" href="javascript:void(0);">
                        <span class="icon-holder">
                            <i class="anticon anticon-folder-open"></i>
                        </span>
                        <span class="title">View Data</span>
                        <span class="arrow">
                            <i class="arrow-icon"></i>
                        </span>
                    </a>

                    <ul class="dropdown-menu">
                        <li class="<?= ($this->uri->segment(2) == 'opd_report') ? 'active' : '' ?>">
                            <a href="<?= base_url('forms/opd_report'); ?>">
                                <span>View OPD/MNCH Data</span>
                            </a>
                        </li>

                        <li class="<?= ($this->uri->segment(2) == 'child_health_report') ? 'active' : '' ?>">
                            <a href="<?= base_url('forms/child_health_report'); ?>">
                                <span>View Child Health Data</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Reports -->
            <?php if(in_array($role, [2,3,4])): ?>
                <li class="nav-item dropdown 
                    <?= ($this->uri->segment(2) == 'data_entry_status' || $this->uri->segment(2) == 'uc_wise_report') ? 'open' : '' ?>">

                    <a class="dropdown-toggle" href="javascript:void(0);">
                        <span class="icon-holder">
                            <i class="anticon anticon-file-text"></i>
                        </span>
                        <span class="title">Reports</span>
                        <span class="arrow">
                            <i class="arrow-icon"></i>
                        </span>
                    </a>

                    <ul class="dropdown-menu">
                        <?php if($role != 4): ?>
                            <!-- role 2 & 3 see all reports -->
<!--                            <li class="<?= ($this->uri->segment(2) == 'data_entry_status') ? 'active' : '' ?>">
                                <a href="<?= base_url('reports/data_entry_status'); ?>">
                                    <span>Data Entry Status</span>
                                </a>
                            </li>-->
                            
                            <!-- role 2 & 3 see all reports -->
                            <li class="<?= ($this->uri->segment(2) == 'date_wise_progress') ? 'active' : '' ?>">
                                <a href="<?= base_url('reports/date_wise_progress'); ?>">
                                    <span>Date Wise Progress</span>
                                </a>
                            </li>
                            
                        <?php endif; ?>

                        <!-- Date Wise Report visible to role 2,3,4 -->
                        <li class="<?= ($this->uri->segment(2) == 'date_wise_form_progress') ? 'active' : '' ?>">
                            <a href="<?= base_url('reports/date_wise_form_progress'); ?>">
                                <span>Date Wise Form Progress</span>
                            </a>
                        </li>   
                            
                        <!-- UC Wise Report visible to role 2,3,4 -->
                        <li class="<?= ($this->uri->segment(2) == 'uc_wise_report') ? 'active' : '' ?>">
                            <a href="<?= base_url('reports/uc_wise_report'); ?>">
                                <span>UC Wise Report</span>
                            </a>
                        </li>
                    </ul>
                </li>
            <?php endif; ?>

            <!-- Questions (only role 3) -->
            <?php if($role == 3): ?>
                <li class="nav-item">
                    <a href="<?= base_url('questions'); ?>">
                        <span class="icon-holder">
                            <i class="anticon anticon-question-circle"></i>
                        </span>
                        <span class="title">Questions</span>
                    </a>
                </li>
            <?php endif; ?>

            <hr>

            <!-- Logout -->
            <li class="nav-item logout-item">
                <a href="<?= base_url('auth/logout') ?>">
                    <span class="icon-holder">
                        <i class="anticon anticon-logout"></i>
                    </span>
                    <span class="title">Logout</span>
                </a>
            </li>

        </ul>
    </div>
</div>
