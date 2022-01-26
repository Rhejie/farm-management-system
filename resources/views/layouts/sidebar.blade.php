<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="/img/job.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">E Work Finder</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="/img/adminlogo.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{auth()->user()->userType->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="/company" class="nav-link {{ Route::is('company.index') ? 'active' : '' }}">
                    <i  class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                    Dashboard

                    </p>
                </a>
            </li>
            <li class="nav-item  {{ Route::is('company.create.job') ? 'menu-is-opening menu-open' : '' }}
            {{ Route::is('company.jobs') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('company.create.job') ? 'active' : '' }} {{ Route::is('company.jobs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-copy"></i>
                <p>
                    Job
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="/company/job/create" class="nav-link {{ Route::is('company.create.job') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Create</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/company/jobs" class="nav-link {{ Route::is('company.jobs') ? 'active' : '' }}">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Jobs List</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/company/applicants" class="nav-link {{ Route::is('company.applicants') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon"></i>
                <p>Applicants</p>
                </a>
            </li>
            <li class="nav-header">Employees</li>
            <li class="nav-item">
                <a href="/company/profile" class="nav-link {{ Route::is('company.profile') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user-plus"></i>
                <p>Add Employee</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/employees#/employees" class="nav-link {{ Route::is('employee.index') ? 'active' : '' }}">
                <i class="nav-icon fas fa-users"></i>
                <p>Employees</p>
                </a>
            </li>
            <li class="nav-header">SETTINGS</li>
            <li class="nav-item">
                <a href="/company/profile" class="nav-link {{ Route::is('company.profile') ? 'active' : '' }}">
                <i class="nav-icon fas fa-user"></i>
                <p>Profile</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="iframe.html" class="nav-link">
                    <i class="nav-icon fas fa-power-off"></i>
                    <p>Logout</p>
                </a>
            </li>
        </ul>
        </nav>
    <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
