<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar" style="height: auto;">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="https://picsum.photos/200" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>Administrator</p>
                <!-- Status -->
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="header">Menu</li>
            <li>
                <a href="{{url('admin')}}">
                    <i class="fa fa-bar-chart"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-briefcase"></i>
                    <span>Reports</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li>
                        <a href="{{url('admin/reports/income')}}">
                            <i class="fa fa-dollar"></i>
                            <span>Income</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/reports/overall')}}">
                            <i class="fa fa-area-chart"></i>
                            <span>Global</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/reports/sources')}}">
                            <i class="fa fa-exchange"></i>
                            <span>Media Sources</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/reports')}}">
                            <i class="fa fa-cogs"></i>
                            <span>Custom Reporting</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa fa-line-chart"></i>
                    <span>Google Ads</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="{{url('admin/google-ads/dashboard')}}">
                            <i class="fa fa-bars"></i>
                            <span>Overview</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/google-ads/activity-report')}}">
                            <i class="fa fa-dollar"></i>
                            <span>Activity Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/google-ads/financial-report')}}">
                            <i class="fa fa-area-chart"></i>
                            <span>Financial Report</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/google-ads/account-setting')}}">
                            <i class="fa fa-dollar"></i>
                            <span>Account Setting</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/google-ads/general-variable')}}">
                            <i class="fa fa-area-chart"></i>
                            <span>General Variables</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-tasks"></i>
                    <span>Admin</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="">
                        <a href="{{url('admin/auth/users')}}">
                            <i class="fa fa-users"></i>
                            <span>Users</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/auth/roles')}}">
                            <i class="fa fa-user"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/auth/permissions')}}">
                            <i class="fa fa-ban"></i>
                            <span>Permission</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/auth/menu')}}">
                            <i class="fa fa-bars"></i>
                            <span>Menu</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('admin/auth/logs')}}">
                            <i class="fa fa-history"></i>
                            <span>Operation log</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{url('admin/config')}}">
                    <i class="fa fa-toggle-on"></i>
                    <span>Config</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>