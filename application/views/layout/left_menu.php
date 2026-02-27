<div class="side-nav">
    <div class="side-nav-inner">
        <ul class="side-nav-menu scrollable">

            <?php $role = $this->session->userdata('role'); ?>

            <?php if($role != 3): ?>
            <!-- Home -->
            <li class="nav-item <?= ($this->router->fetch_class() == 'welcome') ? 'active' : '' ?>">
                <a href="<?= base_url('welcome/index'); ?>">
                    <span class="icon-holder">
                        <i class="anticon anticon-home"></i>
                    </span>
                    <span class="title">Home</span>
                </a>
            </li>
            <?php endif; ?>

            <?php if($role == 3): ?>
            <li class="nav-item dropdown 
                <?= ($this->uri->segment(2) == 'add' || $this->uri->segment(2) == 'user_list') ? 'open' : '' ?>">                
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                        <i class="anticon anticon-user"></i>
                    </span>
                    <span class="title">Users</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?= ($this->uri->segment(2) == 'add') ? 'active' : '' ?>">
                        <a href="<?= base_url('users/add'); ?>">
                            <span>Add User</span>
                        </a>
                    </li>

                    <li class="<?= ($this->uri->segment(2) == 'user_list') ? 'active' : '' ?>">
                        <a href="<?= base_url('users/user_list'); ?>">
                            <span>User List</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>
            
            <!-- Dashboard (exclude role 1) -->
            <?php if(!in_array($role, [1,3])): ?>
            <li class="nav-item dropdown 
                <?= ($this->uri->segment(2) == 'map_view' || $this->uri->segment(2) == 'export_health_data' || $this->uri->segment(2) == 'outreach' || $this->uri->segment(2) == 'fixedsite' || $this->uri->segment(2) == 'child_health') ? 'open' : '' ?>">                
                <a class="dropdown-toggle" href="javascript:void(0);">
                    <span class="icon-holder">
                        <i class="anticon anticon-dashboard"></i>
                    </span>
                    <span class="title">Analytics</span>
                    <span class="arrow">
                        <i class="arrow-icon"></i>
                    </span>
                </a>

                <ul class="dropdown-menu">
                    <li class="<?= ($this->uri->segment(2) == 'map_view') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard/map_view'); ?>">
                            <span>Target Population</span>
                        </a>
                    </li>

                    <li class="<?= ($this->uri->segment(2) == 'outreach') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard/outreach'); ?>">
                            <span>Outreach</span>
                        </a>
                    </li>
                    
                    <li class="<?= ($this->uri->segment(2) == 'fixedsite') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard/fixedsite'); ?>">
                            <span>Fixed Site</span>
                        </a>
                    </li>
                    
                    <li class="<?= ($this->uri->segment(2) == 'child_health') ? 'active' : '' ?>">
                        <a href="<?= base_url('dashboard/child_health'); ?>">
                            <span>Child Health</span>
                        </a>
                    </li>
                    
                    <li class="<?= ($this->uri->segment(2) == 'export_health_data') ? 'active' : '' ?>">
                        <a href="<?= base_url('reports/export_health_data'); ?>">
                            <span>Export Raw Data</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <!-- Data Entry & View Data (role 1 & role 3) -->
            <?php if(in_array($role, [1])): ?>
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

            <?php if(in_array($role, [1,2])): ?>
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
            <?php if(in_array($role, [2,4])): ?>
                <li class="nav-item dropdown 
                    <?= ($this->uri->segment(2) == 'data_entry_status' || $this->uri->segment(2) == 'uc_wise_report') ? 'open' : '' ?>">

                    <a class="dropdown-toggle" href="javascript:void(0);">
                        <span class="icon-holder">
                            <i class="anticon anticon-file-text"></i>
                        </span>
                        <span class="title">Reporting Stats</span>
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
