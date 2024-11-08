<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Backend\MonitoringController;
use Illuminate\Support\Facades\Cache;
use App\Events\WebsiteCheckStatusUpdated;
use App\Http\Controllers\Backend\VulnerabilityScanController;

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
    return view('frontend/landing/welcome');
})->name('/');

Auth::routes();
// Route::get('/monitor/websites/status', [MonitoringController::class, 'index']);
Route::group([
    'namespace' => '\App\Http\Controllers\Backend',
    'prefix' => 'backend',
    'as' => 'backend.',
    'middleware' => 'auth',
], function () {

    Route::get('home', 'HomeController@index')->name('home');
    Route::get('monitoring', 'MonitoringController@index')->name('monitoring');
    Route::get('monitoring/latest', [MonitoringController::class, 'latest'])->name('monitoring.latest');
    // Route::get('monitoring', [MonitoringController::class, 'index'])->name('backend.monitoring.index');
    // Route::get('monitoring/check', [MonitoringController::class, 'checkWebsites'])->name('monitoring.check');
    Route::get('monitoring/infected', [MonitoringController::class, 'checkInfectedWebsites'])->name('monitoring.infected');
    Route::get('monitoring/export', [MonitoringController::class, 'exportReport'])->name('monitoring.export');
    Route::get('monitoring/check-status', [MonitoringController::class, 'getJobStatus'])->name('monitoring.check-status');
    Route::get('monitoring/check-slot', [MonitoringController::class, 'checkSlot'])->name('monitoring.check-slot');

    Route::get('monitoring/check', [MonitoringController::class, 'checkWebsites'])->name('monitoring.check');
Route::get('monitoring/status', [MonitoringController::class, 'getJobStatus'])->name('monitoring.getJobStatus');
Route::get('monitoring/results', [MonitoringController::class, 'getResults'])->name('monitoring.getResults');

Route::get('/scan_progress', function () {
    $totalWebsites = \App\Models\Website::count();
    $processedWebsites = Cache::get('processed_websites', 0);

    return response()->json([
        'processed' => $processedWebsites,
        'total' => $totalWebsites,
    ]);
})->name('scan_progress');

Route::get('/scan', [VulnerabilityScanController::class, 'showForm'])->name('scan.form');
Route::post('/scan', [VulnerabilityScanController::class, 'scanWebsite']);
/**
     * Change Password Modul Routes
     * route('backend.change-password.*')
     */
    Route::group(['as' => 'change-password.'], function () {
        Route::get('change-password', 'ChangePasswordController@index')->name('index');
        Route::post('change-password/change', 'ChangePasswordController@change')->name('change');
    });
    Route::get('monitoring/test-broadcast', function () {
        // Fetch the latest job status from the cache
        $status = Cache::get('website_check_status', 'Not Checked');

        // Broadcast the status
        broadcast(new WebsiteCheckStatusUpdated($status));

        return response()->json(['status' => $status]);
    });
    /**
     * Profile Modul Routes
     * route('backend.profile.*')
     */
    Route::group(['as' => 'profile.'], function () {
        Route::get('profile', 'ProfileController@index')->name('index');
        Route::post('profile/update', 'ProfileController@update')->name('update');
    });

    /**
     * User Modul Routes
     * route('backend.user.*')
     */
    Route::group(['as' => 'user.'], function () {
        Route::get('user', 'UserController@index')->name('index');
        Route::post('user/toggle', 'UserController@toggle')->name('toggle');
        Route::get('user/create', 'UserController@create')->name('create');
        Route::post('user/store', 'UserController@store')->name('store');
        Route::get('user/edit/{id}', 'UserController@edit')->name('edit');
        Route::post('user/update/{id}', 'UserController@update')->name('update');
        Route::post('user/delete', 'UserController@delete')->name('deleteuser');
    });

//    Route::group(['as' => 'person.'], function () {
//        Route::get('user', 'PersonController@index')->name('index');
//        Route::post('user/toggle', 'UserController@toggle')->name('toggle');
//        Route::get('user/create', 'UserController@create')->name('create');
//        Route::post('user/store', 'UserController@store')->name('store');
//        Route::get('user/edit/{id}', 'UserController@edit')->name('edit');
//        Route::post('user/update/{id}', 'UserController@update')->name('update');
//        Route::post('user/delete', 'UserController@delete')->name('deleteuser');
//    });

    /**
     * Menus Modul Routes
     * route('backend.keluarga.*')
     */
//    Route::group(['as' => 'keluarga.'], function () {
//        Route::get('keluarga/{id}', 'KeluargaController@index')->name('index');
//        Route::post('keluarga/toggle', 'KeluargaController@toggle')->name('toggle');
//        Route::get('keluarga/create/{id}', 'KeluargaController@create')->name('create');
//        Route::post('keluarga/store', 'KeluargaController@store')->name('store');
//        Route::get('keluarga/edit/{id}', 'KeluargaController@edit')->name('edit');
//        Route::post('keluarga/update/{id}', 'KeluargaController@update')->name('update');
//        Route::post('keluarga/delete', 'KeluargaController@delete')->name('deletekeluarga');
//    });

    /**
     * Visitor Modul Routes
     * route('backend.visitor.*')
     */
    Route::group(['as' => 'visitor.'], function () {
        Route::get('visitor', 'VisitorController@index')->name('index');
    });

    /**
     * Roles Modul Routes
     * route('backend.role.*')
     */
    Route::group(['as' => 'role.'], function () {
        Route::get('role', 'RoleController@index')->name('index');
        Route::post('role/toggle', 'RoleController@toggle')->name('toggle');
        Route::get('role/create', 'RoleController@create')->name('create');
        Route::post('role/store', 'RoleController@store')->name('store');
        Route::get('role/edit/{id}', 'RoleController@edit')->name('edit');
        Route::post('role/update/{id}', 'RoleController@update')->name('update');
        Route::post('role/delete','RoleController@delete')->name('deleterole');
    });

    /**
     * Menus Modul Routes
     * route('backend.menu.*')
     */
    Route::group(['as' => 'menu.'], function () {
        Route::get('menu', 'MenuController@index')->name('index');
        Route::post('menu/toggle', 'MenuController@toggle')->name('toggle');
        Route::get('menu/create', 'MenuController@create')->name('create');
        Route::post('menu/store', 'MenuController@store')->name('store');
        Route::get('menu/edit/{id}', 'MenuController@edit')->name('edit');
        Route::post('menu/update/{id}', 'MenuController@update')->name('update');
        Route::post('menu/delete', 'MenuController@delete')->name('delete');
    });

    /**
     * Access Control Modul Routes
     * route('backend.accesscontrol.*')
     */
    Route::group(['as' => 'accesscontrol.'], function () {
        Route::get('accesscontrol', 'AccessControlController@index')->name('index');
        Route::post('accesscontrol/search', 'AccessControlController@search')->name('search');
        Route::post('accesscontrol/update', 'AccessControlController@update')->name('update');
    });

    /**
     * Company Modul Routes
     * route('backend..*')
     */
    Route::group(['as' => 'company.'], function () {
        Route::get('company', 'CompanyController@index')->name('index');
        Route::post('company/toggle', 'CompanyController@toggle')->name('toggle');
        Route::get('company/create', 'CompanyController@create')->name('create');
        Route::post('company/store', 'CompanyController@store')->name('store');
        Route::get('company/edit/{id}', 'CompanyController@edit')->name('edit');
        Route::post('company/update/{id}', 'CompanyController@update')->name('update');
    });

    /**
     * Unit / Department Modul Routes
     * route('backend.department.*')
     */
//    Route::group(['as' => 'department.'], function () {
//        Route::get('department', 'DepartmentController@index')->name('index');
//        Route::post('department/toggle', 'DepartmentController@toggle')->name('toggle');
//        Route::get('department/create', 'DepartmentController@create')->name('create');
//        Route::post('department/store', 'DepartmentController@store')->name('store');
//        Route::get('department/edit/{id}', 'DepartmentController@edit')->name('edit');
//        Route::post('department/update/{id}', 'DepartmentController@update')->name('update');
//    });


    /**
     * Setting Modul Routes
     * route('backend.berita.*')
     */
    Route::group(['as' => 'berita.'], function () {
        Route::get('berita', 'BeritaController@index')->name('index');
        Route::get('berita/edit/{id}', 'BeritaController@edit')->name('edit');
        Route::post('berita/update/{id}', 'BeritaController@update')->name('update');
        Route::get('berita/create', 'BeritaController@create')->name('create');
        Route::post('berita/store', 'BeritaController@store')->name('store');
        Route::post('berita/delete','BeritaController@delete')->name('deleteberita');
    });

    /**
     * Setting Modul Routes
     * route('backend.berita.*')
     */
//    Route::group(['as' => 'kuota.'], function () {
//        Route::get('kuota', 'KuotaController@index')->name('index');
//        Route::get('kuota/edit', 'KuotaController@edit')->name('edit');
//        Route::post('kuota/update', 'KuotaController@update')->name('update');
//        Route::get('kuota/create', 'KuotaController@create')->name('create');
//        Route::post('kuota/jadwal', 'KuotaController@jadwal')->name('jadwal');
//        Route::post('kuota/store', 'KuotaController@store')->name('store');
//    });

    /**
     * Setting Modul Routes
     * route('backend.setting.*')
     */
    Route::group(['as' => 'setting.'], function () {
        Route::get('setting', 'SettingController@index')->name('setting');
        Route::post('setting/edit', 'SettingController@edit')->name('settingedit');
    });



    Route::group(['as' => 'master.'], function () {
        Route::get('master/desa', 'MasterDesaController@index')->name('desalist');
        Route::get('master/desa/create', 'MasterDesaController@createView')->name('desacreateview');
        Route::post('master/desa/create', 'MasterDesaController@create')->name('desacreate');
        Route::get('master/desa/edit', 'MasterDesaController@editView')->name('desaeditview');
        Route::post('master/desa/edit', 'MasterDesaController@edit')->name('desaedit');
        Route::post('master/desa/delete', 'MasterDesaController@delete')->name('desadelete');

        Route::get('master/bimtek', 'MasterBimtekController@index')->name('bimteklist');
        Route::get('master/bimtek/create', 'MasterBimtekController@createView')->name('bimtekcreateview');
        Route::post('master/bimtek/create', 'MasterBimtekController@create')->name('bimtekcreate');
        Route::get('master/bimtek/edit', 'MasterBimtekController@editView')->name('bimtekeditview');
        Route::post('master/bimtek/edit', 'MasterBimtekController@edit')->name('bimtekedit');
        Route::post('master/bimtek/delete', 'MasterBimtekController@delete')->name('bimtekdelete');
    });

});
