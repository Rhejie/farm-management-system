<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
    <img src="/img/job.jpg" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Farm Management</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
        {{-- <img src="/img/adminlogo.png" class="img-circle elevation-2" alt="User Image"> --}}
        </div>
        <div class="info">
        <a href="#" class="d-block">{{auth()->user()->userType->name }}</a>
        </div>
    </div>
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
            with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="/home" class="nav-link {{ Route::is('home') ? 'active' : '' }}">
                    <i  class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                    Dashboard

                    </p>
                </a>
            </li>
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Human Resource Staff' || auth()->user()->userType->name == 'Leadman')
            <li class="nav-header">ATTENDANCE</li>
            <li class="nav-item">
                <a href="/attendance#/attendance" class="nav-link {{ Route::is('attendance.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-clock"></i>
                    <p>Attendance</p>
                </a>
            </li>
            @endif
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Human Resource Staff')
            <li class="nav-item">
                <a href="/overtime#/overtime-list" class="nav-link {{ Route::is('overtime.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>Overtime</p>
                </a>
            </li>
            <li class="nav-header">EMPLOYEES</li>
            <li class="nav-item">
                <a href="/employees#/employees" class="nav-link {{ Route::is('employee.index') ? 'active' : '' }}">
                <i class="fas fa-users nav-icon"></i>
                <p>Employees</p>
                </a>
            </li>
            @endif
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Finance Staff')
            <li class="nav-header">FINANCE</li>
            <li class="nav-item  {{ Route::is('payroll.index') ? 'menu-is-opening menu-open' : '' }}
            {{ Route::is('company.jobs') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('payroll.index') ? 'active' : '' }} {{ Route::is('company.jobs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-address-card"></i>
                <p>
                    Payroll
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="/payroll#/payrolls" class="nav-link">
                    <i class="far fa-copy nav-icon"></i>
                    <p>Payroll</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/payroll#/generate" class="nav-link">
                    <i class="far fa-copy nav-icon"></i>
                    <p>Generate</p>
                    </a>
                </li>
                </ul>
            </li>
            @endif
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Warehouse Staff')
            <li class="nav-header">WAREHOUSE</li>
            <li class="nav-item">
                <a href="/stocks#/stocks" class="nav-link {{ Route::is('warehouse.stock') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Stocks</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/items#/items" class="nav-link {{ Route::is('warehouse.item') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p>Items</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/categories#/categories" class="nav-link {{ Route::is('warehouse.category') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-code-branch"></i>
                    <p>Category</p>
                </a>
            </li>
            @endif
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Warehouse Staff' || auth()->user()->userType->name == 'Leadman')
            <li class="nav-header">OPERATIONS</li>
                @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Leadman')
                <li class="nav-item">
                    <a href="/deploy-employee#/deploy-employees" class="nav-link {{ Route::is('deploy-employee.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-friends"></i>
                        <p>Deploy Team</p>
                    </a>
                </li>
                @endif
                @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Warehouse Staff')
                <li class="nav-item">
                    <a href="/deploy#/deploy" class="nav-link {{ Route::is('deploy.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box-open"></i>
                        <p>Deploy Stocks</p>
                    </a>
                </li>
                @endif
            @endif
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Leadman')
            <li class="nav-header">PRODUCTION</li>
            <li class="nav-item">
                <a href="/banana#/yield-report" class="nav-link {{ Route::is('banana.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-database"></i>
                    <p>Banana Yield Report</p>
                </a>
            </li>
            <li class="nav-item  {{ Route::is('month.index') ? 'menu-is-opening menu-open' : '' }}
            {{ Route::is('company.jobs') ? 'menu-is-opening menu-open' : '' }}">
                <a href="#" class="nav-link {{ Route::is('month.index') ? 'active' : '' }} {{ Route::is('company.jobs') ? 'active' : '' }}">
                <i class="nav-icon fas fa-address-card"></i>
                <p>
                    Half Month Report
                    <i class="fas fa-angle-left right"></i>
                </p>
                </a>
                <ul class="nav nav-treeview">
                <li class="nav-item">
                    <a href="/half-month#/reports" class="nav-link">
                    <i class="far fa-copy nav-icon"></i>
                    <p>Reports</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/half-month#/hgenerate" class="nav-link">
                    <i class="far fa-copy nav-icon"></i>
                    <p>Generate</p>
                    </a>
                </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="/daily-operation#/operations" class="nav-link {{ Route::is('daily-operation.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-paper-plane"></i>
                    <p>Daily Operation</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/task#/tasks" class="nav-link {{ Route::is('task.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-check-square"></i>
                    <p>Task</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/harvest#/harvest" class="nav-link {{ Route::is('harvest.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-cart-arrow-down"></i>
                    <p>Harvest</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/team#/teams" class="nav-link {{ Route::is('team.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-friends"></i>
                    <p>Team</p>
                </a>
            </li>
            @endif
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Human Resource Staff')
            <li class="nav-header">QR CODE</li>
            <li class="nav-item">
                <a href="/qr-code#/codes" class="nav-link {{ Route::is('qr-code.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-qrcode"></i>
                    <p>QR Codes</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/scanner" target="_blank" class="nav-link">
                    <i class="nav-icon fas fa-qrcode"></i>
                    <p>Scanner</p>
                </a>
            </li>
            @endif
            @if(auth()->user()->userType->name == 'Administrator' || auth()->user()->userType->name == 'Leadman')
            <li class="nav-header">Area</li>
            <li class="nav-item">
                <a href="/areas#/areas" class="nav-link {{ Route::is('area.index') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-map-marker-alt"></i>
                    <p>Areas</p>
                </a>
            </li>
            @endif
            <li class="nav-header">SETTINGS</li>
            <li class="nav-item">
                <a href="{{ route('logout') }}"
                onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();" class="nav-link">
                <i class="nav-icon fas fa-power-off"></i>
                <p>Logout</p>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
