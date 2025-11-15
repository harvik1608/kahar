<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\FishController;
    use App\Http\Controllers\AdminController;
    use App\Http\Controllers\VendorController;
    use App\Http\Controllers\CustomerController;
    use App\Http\Controllers\PurchaseController;
    use App\Http\Controllers\SaleController;
    use App\Http\Controllers\ReportController;

    // Route::get('/', [HomeController::class, 'index'])->name('home');
    // Route::get('/services', [HomeController::class, 'services'])->name('services');
    // Route::get('/service/{slug}', [HomeController::class, 'service'])->name('service');
    // Route::get('/resources', [HomeController::class, 'resources'])->name('resources');
    // Route::get('/projects', [HomeController::class, 'projects'])->name('projects');
    // Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
    // Route::get('/blog/{slug}', [HomeController::class, 'blog'])->name('blog');
    // Route::get('/contact-us', [HomeController::class, 'contact_us'])->name('contact-us');
    // Route::post('/submit-contactUs', [HomeController::class, 'submit_contactus'])->name('submit.contact-us');
    // Route::get('/about', [HomeController::class, 'index'])->name('about');

    Route::prefix('admin')->group(function () {
        Route::get('/', [AuthController::class, 'index'])->name('login');
        Route::get('login', [AuthController::class, 'index'])->name('admin.login');
        Route::post('check-login', [AuthController::class, 'checkLogin'])->name('admin.submit.login');
        
        Route::middleware('auth')->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('daily-report', [ReportController::class, 'daily_report'])->name('admin.daily_report');
            Route::get('load-date-wise-chart', [ReportController::class, 'load_date_wise_chart'])->name('admin.load_date_wise_chart');

            Route::get('general-settings', [DashboardController::class, 'general_settings'])->name('admin.general-settings');
            Route::post('submit-general-settings', [DashboardController::class, 'submit_general_settings'])->name('admin.submit.general-settings');

            Route::resource('admins', AdminController::class)->middleware('checkRole');
            Route::get('/load-admins', [AdminController::class, 'load'])->name('admin.admin.load')->middleware('checkRole');

            Route::resource('fishes', FishController::class)->middleware('checkRole');
            Route::get('/load-fishes', [FishController::class, 'load'])->name('admin.fish.load')->middleware('checkRole');

            Route::resource('vendors', VendorController::class);
            Route::resource('customers', CustomerController::class);

            Route::resource('purchase_entries', PurchaseController::class);
            Route::get('/add-more', [PurchaseController::class, 'add_more'])->name('admin.add_more');

            Route::resource('sale_entries', SaleController::class);
            Route::get('/fetch-vendor-fish', [SaleController::class, 'fetch_vendor_fish'])->name('admin.fetch_vendor_fish');
            Route::get('/add-more-sale', [SaleController::class, 'add_more_sale'])->name('admin.add_more_sale');

            // Route::resource('projects', ProjectController::class);
            // Route::get('/load-projects', [ProjectController::class, 'load'])->name('admin.project.load');

            // Route::resource('downloads', DownloadController::class);
            // Route::get('/load-downloads', [DownloadController::class, 'load'])->name('admin.download.load');

            // Route::resource('blogs', BlogController::class);
            // Route::get('/load-blogs', [BlogController::class, 'load'])->name('admin.blog.load');

            // Route::resource('why_chooses', WhyController::class);
            // Route::get('/load-why_chooses', [WhyController::class, 'load'])->name('admin.why_chooses.load');

            // Route::get('inquiries', [DashboardController::class, 'inquiries'])->name('admin.inquiry');
            // Route::get('/load-inquiries', [DashboardController::class, 'load_inquiries'])->name('admin.inquiry.load');

            Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
        });
    });
