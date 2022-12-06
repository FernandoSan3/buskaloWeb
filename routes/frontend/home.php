<?php

use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\User\AccountController;
use App\Http\Controllers\Frontend\User\DashboardController;
use App\Http\Controllers\Frontend\User\ProfileController;
use App\Http\Controllers\Frontend\User\SendRequestsController;

use App\Http\Controllers\Frontend\User\ChatsController;
use App\Http\Controllers\Frontend\User\MessagesController;


use App\Http\Controllers\Frontend\Contractor\ContractorProfileController;
use App\Http\Controllers\Frontend\Contractor\WorkersController;
use App\Http\Controllers\Frontend\Contractor\JobsController;
use App\Http\Controllers\Frontend\Contractor\MessageController;
use App\Http\Controllers\Frontend\Contractor\ServicesController;
use App\Http\Controllers\Frontend\Contractor\CreditsController;

use App\Http\Controllers\Frontend\Contractor\ContractorChatsController;
use App\Http\Controllers\Frontend\Contractor\ContractorMessagesController;

use App\Http\Controllers\Frontend\Company\CompanyJobsController;
use App\Http\Controllers\Frontend\Company\CompanyCreditsController;
use App\Http\Controllers\Frontend\Company\CompanyServicesController;
use App\Http\Controllers\Frontend\Company\CompanyMessageController;
use App\Http\Controllers\Frontend\Company\CompanyProfileController;
use App\Http\Controllers\Frontend\ForgotPasswordResetController;
use App\Http\Controllers\Frontend\NewsletterController;
use App\Http\Controllers\Frontend\SubscriptionController;
/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */

Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribeNewsletter'])->name('newsletter.subscribe');

Route::get('reg_type_new', [HomeController::class, 'userSelectionNew'])->name('reg_type_new');
Route::get('serviceNotification',[HomeController::class, 'serviceNotification'])->name('serviceNotification');

Route::get('redirect_register', [HomeController::class, 'redirectRegister'])->name('redirect_register');

Route::get('reg_type', [HomeController::class, 'userSelection'])->name('reg_type');

Route::get('profesional/register', [HomeController::class, 'index'])->name('index');

Route::get('/', [HomeController::class, 'homePage'])->name('home_page');
Route::get('approvel', [HomeController::class, 'confrimApprovel'])->name('approvel_page');
Route::get('faq/{slug}', [HomeController::class, 'faqPage'])->name('faq');


Route::get('first_screen', [HomeController::class, 'firstScreen'])->name('first_screen');
Route::get('services_online', [HomeController::class, 'servicesOnline'])->name('services');
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

//Route::get('/ajax_get_questions', [HomeController::class, 'ajaxGetQuestions'])->name('ajax_get_questions');

Route::get('/ajax_get_next_questions', [HomeController::class, 'ajaxGetNextQuestions'])->name('ajax_get_next_questions');
Route::get('/ajax_get_next_questions_multipal', [HomeController::class, 'ajaxGetNextQuestionsMultiCheck'])->name('ajax_get_next_questions_multipal');
Route::get('/ajax_get_next_questions_option', [HomeController::class, 'ajaxGetNextQuestionsOptions'])->name('ajax_get_next_questions_option');


Route::get('/check_mobile_availability', [HomeController::class, 'checkMobileAvailability'])->name('check_mobile_availability');

Route::post('/insert_contractor_profile', [HomeController::class, 'insertContractorProfile'])->name('insert_contractor_profile');

Route::post('/insert_company_profile', [HomeController::class, 'insertCompanyProfile'])->name('insert_company_profile');

Route::post('insert_constractor_banner', [ContractorProfileController::class, 'insertConstractorBanner'])->name('insert_constractor_banner');


Route::post('/insert_user_profile', [HomeController::class, 'insertUserProfile'])->name('insert_user_profile');

// Route::post('step_one', [HomeController::class, 'stepOne'])->name('step_one');
// Route::get('/category_step/{category_id}/{city_id}', [HomeController::class, 'categoryStep'])->name('category_step');

// Route::get('/ajax_get_subservice', [HomeController::class, 'ajaxGetSubservice'])->name('ajax_get_subservice');
// Route::get('/ajax_get_childservice', [HomeController::class, 'ajaxGetChildservice'])->name('ajax_get_childservice');
 Route::post('store_service_request', [HomeController::class, 'storeServiceRequest'])->name('store_service_request');

Route::get('contact', [ContactController::class, 'index'])->name('contact');
Route::post('contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::get('forgot_password/{emailId}/{token}', [ForgotPasswordResetController::class, 'index'])->name('forgot_password');
Route::post('forgot_password/reset', [ForgotPasswordResetController::class, 'resetPassword'])->name('forgot_password.reset');

Route::get('/service_online', [HomeController::class, 'serviceOnline'])->name('service_online');

Route::get('/send_otp_mail', [HomeController::class, 'sendOtpMail'])->name('send_otp_mail');
Route::get('/get_question', [HomeController::class, 'getQuestion'])->name('get_question');

Route::post('upload_crop_image', [HomeController::class, 'uploadCropImage'])->name('upload_crop_image');

Route::get('characteristics-conditions', [HomeController::class, 'Characteristics_conditions'])->name('characteristics_conditions');

Route::get('work-with-us', [HomeController::class, 'work_with_us'])->name('work_with_us');

Route::get('about-us', [HomeController::class, 'about_us'])->name('about_us');

Route::get('how-does-it-work/{slug}', [HomeController::class, 'how_does_it_work'])->name('how_does_it_work');

Route::get('review-payment-security-policies', [HomeController::class, 'review_payment_security_policies'])->name('review_payment_security_policies');

Route::get('payments/{id}/{slug}/{userIid}', [HomeController::class, 'paymentApp'])->name('paymentapp');
Route::post('payments/store',[HomeController::class,'paymentStore'])->name('store');
Route::get('service/payment', [HomeController::class, 'servicePaymnt'])->name('servicePaymnt');
Route::post('service/payment',[HomeController::class,'servicePaymentStore'])->name('service.payment');


/*
 * These frontend controllers require the user to be logged in
 * All route names are prefixed with 'frontend.'
 * These routes can not be hit if the password is expired
 */
Route::group(['middleware' => ['auth', 'password_expires']], function () {
  Route::get('user_refund', [HomeController::class, 'userRefundget']);
  Route::get('professional_refund', [HomeController::class, 'proRefundget']);
  Route::post('refund/request', [HomeController::class, 'refundRequest']);

Route::get('user/rating_review',[HomeController::class,'proOrCompanyRating'])->name('rating_review');

 Route::post('user/rating_review/store', [HomeController::class, 'storeReviewByCompany'])->name('user.rating_review.store');

 Route::get('service/payment/web', [HomeController::class, 'servicePaymntWeb'])->name('servicePaymnt.web');
Route::post('service/payment/web',[HomeController::class,'servicePaymentStoreWeb'])->name('service.payment.web');

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

        Route::get('user_profile', [ProfileController::class, 'userProfile'])->name('user_profile');

        Route::post('update_user_profile', [ProfileController::class, 'updateUserProfile'])->name('update_user_profile');

        Route::get('projects', [SendRequestsController::class, 'projects'])->name('projects');

        Route::get('usr/chats', 'ChatsController@index')->name('usr.chats');

        Route::get('usr/load-latest-messages', 'MessagesController@getLoadLatestMessages')->name('usr.load-latest-messages');

        Route::post('/senduserchat', 'MessagesController@postSendMessage')->name('senduserchat');
        Route::post('/send', 'MessagesController@postSendMessage');

        Route::get('usr/fetch-old-messages', 'MessagesController@getOldMessages')->name('usr.fetch-old-messages');

        Route::get('detail/{userid}/{serviceid}', [SendRequestsController::class, 'proInformation'])->name('detail');
        Route::post('hireproorcompany', [SendRequestsController::class, 'HireProOrCompany']);
        Route::get('manageRequestStatus', [SendRequestsController::class, 'manageRequestStatus']);

    });


   Route::group(['namespace' => 'Contractor', 'as' => 'contractor.'], function () 
    {
        // User Dashboard Specific
        Route::get('co-dashoard', [ContractorProfileController::class, 'index'])->name('co-dashoard');
        Route::post('profile/update', [ContractorProfileController::class, 'updateBasicInfo'])->name('profile.update');
        Route::get('my-profile', [ContractorProfileController::class, 'myProfile'])->name('my-profile');

        Route::post('/loadmore', [ContractorProfileController::class, 'load_data'])->name('loadmore');
       
        Route::get('mi-perfil', [ContractorProfileController::class, 'miPerfil'])->name('mi-perfil');

        Route::get('test-page', [ContractorProfileController::class, 'testPage'])->name('test-page');


        Route::post('my-profile/update_info', [ContractorProfileController::class, 'updateBasicInfo'])->name('my-profile.update_info');

        Route::post('my-profile/update_profile_picture', [ContractorProfileController::class, 'updateProfilePicture'])->name('my-profile.update_profile_picture');

        Route::get('documents', [ContractorProfileController::class, 'myDocuments'])->name('documents');

        Route::get('workers', [WorkersController::class, 'index'])->name('workers');

        Route::post('workers/create', [WorkersController::class, 'createWorker'])->name('workers.create');

        Route::get('jobs', [JobsController::class, 'index'])->name('jobs');

        Route::get('jobs_details/{job_id}', [JobsController::class, 'jobDetail'])->name('jobs_details');

        Route::get('opportunities', [JobsController::class, 'opportunities'])->name('opportunities');
        Route::get('purchage_terms', [JobsController::class, 'purchageTerms'])->name('purchage_terms');

         Route::get('opportunity_details/{opportunity_id}', [JobsController::class, 'opportunityDetails'])->name('opportunity_details');

        Route::get('buy_opportunity/{opportunity_id}', [JobsController::class, 'buyOpportunity'])->name('buy_opportunity');

        Route::get('ignore_opportunity/{opportunity_id}', [JobsController::class, 'ignoreOpportunity'])->name('ignore_opportunity');

        Route::get('services', [ServicesController::class, 'index'])->name('services');

        Route::get('credits', [CreditsController::class, 'index'])->name('credits');
        Route::post('my-profile/update_other_info', [ContractorProfileController::class, 'updateOtherInfo'])->name('my-profile.update_other_info');

        Route::post('my-profile/update_certificate_image', [ContractorProfileController::class, 'updateCertificateImage'])->name('my-profile.update_certificate_image');

        Route::post('my-profile/update_policerec_image', [ContractorProfileController::class, 'updatePoliceRecordImage'])->name('my-profile.update_policerec_image');

        Route::post('/my-profile/update_banner', [ContractorProfileController::class, 'updateBanner'])->name('my-profile.update_banner');

        Route::post('my-profile/update_photovideos_image', [ContractorProfileController::class, 'updatePhotoVideosImage'])->name('my-profile.update_photovideos_image');

        Route::post('my-profile/delete_photovideos_image', [ContractorProfileController::class, 'deletePhotoVideosImage'])->name('my-profile.delete_photovideos_image');

        Route::post('my-profile/delete_certificate_image', [ContractorProfileController::class, 'deleteCertificateImage'])->name('my-profile.delete_certificate_image');

        Route::post('my-profile/delete_police_image', [ContractorProfileController::class, 'deletePoliceImage'])->name('my-profile.delete_police_image');

         Route::get('contr/chats', 'ContractorChatsController@index')->name('contr.chats');
         Route::post('contr/chats-box', 'ContractorChatsController@chats_box')->name('contr.chats-box');

        Route::get('contr/load-latest-messages', 'ContractorMessagesController@getLoadLatestMessages')->name('contr.load-latest-messages');

        Route::post('contr/send', 'ContractorMessagesController@postContSendMessage')->name('contr.send');
 
        Route::get('contr/fetch-old-messages', 'ContractorMessagesController@getOldMessages')->name('contr.fetch-old-messages');
         Route::get('rating_review', [ContractorProfileController::class, 'ratingReview'])->name('rating_review');

          Route::post('payment/request/web', [JobsController::class, 'paymentRequest'])->name('payment.request');
          Route::post('payment/request', [JobsController::class, 'paymentRequestStore'])->name('payment.request');

    });

    Route::group(['namespace' => 'company', 'as' => 'company.'], function ()
    {
        // User Dashboard Specific
        //Route::get('contr/chats', 'ContractorChatsController@index')->name('contr.chats');
         //Route::post('contr/chats-box', 'ContractorChatsController@chats_box')->name('contr.chats-box');
        Route::post('/my-profile/update_banner/company', [CompanyProfileController::class, 'updateBannerCompany'])->name('my-profile.update_banner.company');
        Route::post('company_profile/insert_constractor_banner', [CompanyProfileController::class, 'insertConstractorBanner'])->name('company_profile.insert_constractor_banner');
          Route::get('company_profile/ignore_opportunity/{opportunity_id}', [CompanyJobsController::class, 'ignoreOpportunity'])->name('company_profile.ignore_opportunity');
          Route::get('company_profile/my-profile', [CompanyProfileController::class, 'companyProfile'])->name('company_profile.my-profile');
          Route::get('company_profile/chat', [ContractorChatsController::class, 'companyChat'])->name('company_profile.chat');
        // Route::get('profile', [CompanyProfileController::class, 'index'])->name('company_profile');
        //   Route::get('profile', [CompanyProfileController::class, 'index'])->name('company_profile');
        Route::get('company_profile/mi-perfil', [CompanyProfileController::class, 'miPerfil'])->name('company_profile.mi-perfil');

        Route::post('company_profile/update_info', [CompanyProfileController::class, 'updateBasicInfo'])->name('company_profile.update_basicinfo');

        Route::post('company_profile/update_other_info', [CompanyProfileController::class, 'updateOtherInfo'])->name('company_profile.update_other_info');

        Route::post('company_profile/update_profile_picture', [CompanyProfileController::class, 'updateProfilePicture'])->name('company_profile.update_profile_picture');

        Route::get('company_profile/rating_review', [CompanyProfileController::class, 'ratingReview'])->name('company_profile.rating_review');

        Route::post('company_profile/rating_review', [CompanyProfileController::class, 'storeReviewByCompany'])->name('company_profile.store_review_by_company');

        Route::post('company_profile/delete_certificate_image', [CompanyProfileController::class, 'deleteCertificateImage'])->name('company_profile.delete_certificate_image');

        Route::post('company_profile/update_certificate_image', [CompanyProfileController::class, 'updateCertificateImage'])->name('company_profile.update_certificate_image');

        Route::post('company_profile/delete_police_image', [CompanyProfileController::class, 'deletePoliceImage'])->name('company_profile.delete_police_image');

        Route::post('company_profile/update_policerec_image', [CompanyProfileController::class, 'updatePoliceRecordImage'])->name('company_profile.update_policerec_image');

        Route::post('company_profile/delete_photovideos_image', [CompanyProfileController::class, 'deletePhotoVideosImage'])->name('company_profile.delete_photovideos_image');

        Route::post('company_profile/update_photovideos_image', [CompanyProfileController::class, 'updatePhotoVideosImage'])->name('company_profile.update_photovideos_image');

        Route::get('company_profile/jobs', [CompanyJobsController::class, 'index'])->name('company_profile.jobs');

        Route::get('company_profile/opportunities', [CompanyJobsController::class, 'opportunities'])->name('company_profile.opportunities');

         Route::get('company_profile/opportunity_details/{opportunity_id}', [CompanyJobsController::class, 'opportunityDetails'])->name('company_profile.opportunity_details');

         Route::get('company_profile/buy_opportunity/{opportunity_id}', [CompanyJobsController::class, 'buyOpportunity'])->name('company_profile.buy_opportunity');

        Route::get('company_profile/jobs_details/{job_id}', [CompanyJobsController::class, 'jobDetail'])->name('company_profile.jobs_details');

        Route::get('company_profile/message', [CompanyMessageController::class, 'index'])->name('company_profile.message');

        Route::get('company_profile/services', [CompanyServicesController::class, 'index'])->name('company_profile.services');

        Route::get('company_profile/credits', [CompanyCreditsController::class, 'index'])->name('company_profile.credits');

        Route::get('company_profile/documents', [CompanyProfileController::class, 'myDocuments'])->name('company_profile.documents');
        Route::post('company_profile/payment/request/web', [CompanyProfileController::class, 'paymentRequest'])->name('payment.request');
        Route::post('company_profile/payment/request', [CompanyProfileController::class, 'paymentRequestStore'])->name('payment.request');

    });


    Route::group(['namespace' => 'subscription', 'as' => 'subscription.'], function ()
    {
        Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription');

    });

    Route::group(['namespace' => '  mail', 'as' => 'mail.'], function ()
    {
        Route::get('mail', [HomeController::class, 'mail'])->name('mail');

    });

    Route::group(['namespace' => 'payment', 'as' => 'payment.'], function ()
    {
        Route::get('payment/{id}/{slug}/{userIid}', [SubscriptionController::class, 'payment'])->name('payment');
        Route::post('payment/store',[SubscriptionController::class,'paymentStore'])->name('store');

    });


});
