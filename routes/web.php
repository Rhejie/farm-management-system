<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Auth::check()) {
        return view('home');
    }
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(['middleware' => 'auth'], function () {
    require_once base_path('routes/moduleRoutes/employee.php');
    require_once base_path('routes/moduleRoutes/warehouse.php');
    require_once base_path('routes/moduleRoutes/area.php');
    require_once base_path('routes/moduleRoutes/attendance.php');
    require_once base_path('routes/moduleRoutes/deploy.php');
    require_once base_path('routes/moduleRoutes/qrcode.php');
    require_once base_path('routes/moduleRoutes/deploy-employee.php');
    require_once base_path('routes/moduleRoutes/finance.php');
    require_once base_path('routes/moduleRoutes/harvest.php');
    require_once base_path('routes/moduleRoutes/team.php');
    require_once base_path('routes/moduleRoutes/task.php');
    require_once base_path('routes/moduleRoutes/month.php');
    require_once base_path('routes/moduleRoutes/dashboard.php');
    require_once base_path('routes/moduleRoutes/banana.php');
    require_once base_path('routes/moduleRoutes/daily-operation.php');

    Route::get('/scanner', function () {

        return view('scanner.index');

    });
});

