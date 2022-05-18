<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\SendRequestsController;

use App\Http\Controllers\Frontend\Contractor\ContractorProfileController;
use App\Http\Controllers\Frontend\Contractor\WorkersController;
use App\Http\Controllers\Frontend\Contractor\JobsController;
use App\Http\Controllers\Frontend\Contractor\MessageController;
use App\Http\Controllers\Frontend\Contractor\ServicesController;
use App\Http\Controllers\Frontend\Contractor\CreditsController;

use App\Http\Controllers\Frontend\Company\CompanyProfileController;
use App\Http\Controllers\Frontend\ForgotPasswordResetController;

/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

Route::get('reg_type_new', [HomeController::class, 'userSelectionNew'])->name('reg_type_new');

Route::get('redirect_register', [HomeController::class, 'redirectRegister'])->name('redirect_register');

Route::post('reg_type', [HomeController::class, 'userSelection'])->name('reg_type');

Route::get('/', [HomeController::class, 'index'])->name('index');

Route::get('home_page', [HomeController::class, 'homePage'])->name('home_page');

Route::get('first_screen', [HomeController::class, 'firstScreen'])->name('first_screen');

Route::get('second_screen', [HomeController::class, 'secondScreen'])->name('second_screen');

Route::get('redirect_contractor/{userid}', [HomeController::class, 'redirectContractor'])->name('redirect_contractor');

Route::get('redirect_company/{userid}', [HomeController::class, 'redirectCompany'])->name('redirect_company');

Route::post('profile_completion', [HomeController::class, 'companyProfileCompletion'])->name('profile_completion');

Route::post('contractor_profile_completion', [HomeController::class, 'contractorProfileCompletion'])->name('contractor_profile_completion');

Route::get('/request_success', [HomeController::class, 'RequestSuccess'])->name('request_success');




Route::post('autoCompleteSearch', [HomeController::class, 'autoCompleteSearch'])->name('autoCompleteSearch');

Route::post('step_one', [HomeController::class, 'stepOne'])->name('step_one');

Route::get('/category_step/{category_id}/{city_id}/{selected_type}', [HomeController::class, 'categoryStep'])->name('category_step');

Route::get('/ajax_get_subservice', [HomeController::class, 'ajaxGetSubservice'])->name('ajax_get_subservice');

Route::get('/ajax_get_childservice', [HomeController::class, 'ajaxGetChildservice'])->name('ajax_get_childservice');

Route::get('/ajax_get_questions', [HomeController::class, 'ajaxGetQuestions'])->name('ajax_get_questions');

Route::get('/ajax_get_questions', [HomeController::class, 'ajaxGetQuestions'])->name('ajax_get_questions');

Route::get('/ajax_get_next_questions', [HomeController::class, 'ajaxGetNextQuestions'])->name('ajax_get_next_questions');


















// Route::post('step_one', [HomeController::class, 'stepOne'])->name('step_one');
// Route::get('/category_step/{category_id}/{city_id}', [HomeController::class, 'categoryStep'])->name('category_step');

// Route::get('/ajax_get_subservice', [HomeController::class, 'ajaxGetSubservice'])->name('ajax_get_subservice');
// Route::get('/ajax_get_childservice', [HomeController::class, 'ajaxGetChildservice'])->name('ajax_get_childservice');
 Route::post('store_service_request', [HomeController::class, 'storeServiceRequest'])->name('store_service_request');

Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('forgot_password/{emailId}/{token}', [ForgotPasswordResetController::class, 'index'])->name('forgot_password');
Route::post('forgot_password/reset', [ForgotPasswordResetController::class, 'resetPassword'])->name('forgot_password.reset');

Route::get('/service_online', [HomeController::class, 'serviceOnline'])->name('service_online');

Route::get('/send_otp_mail', [HomeController::class, 'sendOtpMail'])->name('send_otp_mail');
Route::get('/get_question', [HomeController::class, 'getQuestion'])->name('get_question');


/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the password is expired
 */
Route::group(['middleware' => ['auth', 'password_expires']], function () {

    Route::group(['namespace' => 'User', 'as' => 'user.'], function ()
    {
        // User Dashboard Specific
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

       Route::post('profile/update_info', [ProfileController::class, 'updateBasicInfo'])->name('profile.update_info');

       Route::get('all-requests', [SendRequestsController::class, 'index'])->name('all-requests');

       Route::get('all-pending-requests', [SendRequestsController::class, 'allPendingRequests'])->name('all-pending-requests');

       Route::get('all-accepted-requests', [SendRequestsController::class, 'allAcceptedRequests'])->name('all-accepted-requests');

       Route::get('all-rejected-requests', [SendRequestsController::class, 'allRejectedRequest'])->name('all-rejected-requests');

       Route::get('all-inprogress-requests', [SendRequestsController::class, 'allInprogressRequests'])->name('all-inprogress-requests');

       Route::get('all-completed-requests', [SendRequestsController::class, 'allCompletedRequests'])->name('all-completed-requests');

       Route::get('service_details/{service_request_id}', [SendRequestsController::class, 'serviceDetails'])->name('service_details');

       Route::get('profile_comprasion/{service_request_id}', [SendRequestsController::class, 'profileComprasion'])->name('profile_comprasion');

       Route::get('searchRequest', [SendRequestsController::class, 'searchRequest'])->name('searchRequest');

       Route::get('review', [SendRequestsController::class, 'review'])->name('review');

       Route::post('store_review', [SendRequestsController::class, 'storeReview'])->name('store_review');

       Route::get('user_chat', [SendRequestsController::class, 'userChat'])->name('user_chat');


        // User Account Specific
        Route::get('account', [AccountController::class, 'index'])->name('account');

        // User Profile Specific
        Route::patch('profile/update', [ProfileController::class, 'update'])->name('profile.update');

        Route::post('update_user_profile_picture', [ProfileController::class, 'updateUserProfilePicture'])->name('update_user_profile_picture');

        Route::get('user_profile', [ProfileController::class, 'userProfile'])->name('user_profile');

        Route::post('update_user_profile', [ProfileController::class, 'updateUserProfile'])->name('update_user_profile');
    });



    Route::group(['namespace' => 'Contractor', 'as' => 'contractor.'], function ()
    {
        // User Dashboard Specific
        Route::get('co-dashoard', [ContractorProfileController::class, 'index'])->name('co-dashoard');
        Route::post('profile/update', [ContractorProfileController::class, 'updateBasicInfo'])->name('profile.update');

        Route::get('my-profile', [ContractorProfileController::class, 'myProfile'])->name('my-profile');

        Route::get('test-page', [ContractorProfileController::class, 'testPage'])->name('test-page');


         Route::post('my-profile/update_info', [ContractorProfileController::class, 'updateBasicInfo'])->name('my-profile.update_info');

         Route::post('my-profile/update_profile_picture', [ContractorProfileController::class, 'updateProfilePicture'])->name('my-profile.update_profile_picture');

        Route::get('documents', [ContractorProfileController::class, 'myDocuments'])->name('documents');

        Route::get('workers', [WorkersController::class, 'index'])->name('workers');
        Route::post('workers/create', [WorkersController::class, 'createWorker'])->name('workers.create');


        Route::get('jobs', [JobsController::class, 'index'])->name('jobs');

        Route::get('opportunities', [JobsController::class, 'opportunities'])->name('opportunities');

         Route::get('opportunity_details/{opportunity_id}', [JobsController::class, 'opportunityDetails'])->name('opportunity_details');

        Route::get('message', [MessageController::class, 'index'])->name('message');
        Route::get('services', [ServicesController::class, 'index'])->name('services');

        Route::get('credits', [CreditsController::class, 'index'])->name('credits');


        Route::post('my-profile/update_other_info', [ContractorProfileController::class, 'updateOtherInfo'])->name('my-profile.update_other_info');


    });

    Route::group(['namespace' => 'company', 'as' => 'company.'], function ()
    {
        // User Dashboard Specific
        Route::get('profile', [CompanyProfileController::class, 'index'])->name('company_profile');

        Route::get('company_profile', [CompanyProfileController::class, 'companyProfile'])->name('company-profile');

        Route::post('company_profile/update_info', [CompanyProfileController::class, 'updateBasicInfo'])->name('company_profile.update_basicinfo');

        Route::post('company_profile/update_other_info', [CompanyProfileController::class, 'updateOtherInfo'])->name('company_profile.update_other_info');

        Route::post('company_profile/update_profile_picture', [CompanyProfileController::class, 'updateProfilePicture'])->name('company_profile.update_profile_picture');

        Route::get('company_profile/rating_review', [CompanyProfileController::class, 'ratingReview'])->name('company_profile.rating_review');

        Route::post('company_profile/rating_review', [CompanyProfileController::class, 'storeReviewByCompany'])->name('company_profile.store_review_by_company');






        // Route::post('profile/update', [ContractorProfileController::class, 'updateBasic'])->name('profile.update');
        // Route::get('my-profile', [ContractorProfileController::class, 'myProfile'])->name('my-profile');
        // Route::get('documents', [ContractorProfileController::class, 'myDocuments'])->name('documents');

        // Route::get('workers', [WorkersController::class, 'index'])->name('workers');
        // Route::post('workers/create', [WorkersController::class, 'createWorker'])->name('workers.create');


        // Route::get('jobs', [JobsController::class, 'index'])->name('jobs');
        // Route::get('opportunities', [JobsController::class, 'opportunities'])->name('opportunities');


        // Route::get('message', [MessageController::class, 'index'])->name('message');
        // Route::get('services', [ServicesController::class, 'index'])->name('services');

        // Route::get('credits', [CreditsController::class, 'index'])->name('credits');






    });




});
