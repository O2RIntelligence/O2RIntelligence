<header class="main-header">

    <!-- Logo -->
    <a href="http://localhost/o2r/public/admin" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>O2R</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>O2R</b> Reporting admin</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <ul class="nav navbar-nav hidden-sm visible-lg-block">

        </ul>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li>
                    <a href="javascript:void(0);" class="container-refresh">
                        <i class="fa fa-refresh"></i>
                    </a>
                </li>

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="https://picsum.photos/200" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">Administrator</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="https://picsum.photos/200" class="img-circle" alt="User Image">

                            <p>
                                Administrator
                                <small>Member since admin 2021-10-20 16:50:22</small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{url('/google-ads/dashboard')}}" class="btn btn-default btn-flat">Setting</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{url('/google-ads/dashboard')}}" class="btn btn-default btn-flat">Logout</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->



            </ul>
        </div>
    </nav>
</header>