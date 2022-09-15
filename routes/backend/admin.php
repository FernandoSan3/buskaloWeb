<?php
    
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\ServicesController;
use App\Http\Controllers\Backend\SubservicesController;
use App\Http\Controllers\Backend\QuestionsController;
use App\Http\Controllers\Backend\ContractorsController;
use App\Http\Controllers\Backend\CitiesController;
use App\Http\Controllers\Backend\ProvincesController;
use App\Http\Controllers\Backend\DistrictsController;
use App\Http\Controllers\Backend\ServiceRequestController;
use App\Http\Controllers\Backend\PriceRangeController;
use App\Http\Controllers\Backend\ZoneController;
use App\Http\Controllers\Backend\ChildSubservicesController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CompanyController;
use App\Http\Controllers\Backend\SiteSettingController;
use App\Http\Controllers\Backend\ContactUsController;
use App\Http\Controllers\Backend\PolygonController;
use App\Http\Controllers\Backend\ReviewController;
use App\Http\Controllers\Backend\NewsletterController;
use App\Http\Controllers\Backend\AreaController;
use App\Http\Controllers\Backend\WorkController;
use App\Http\Controllers\Backend\AboutController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Backend\TermAndConditionController;
use App\Http\Controllers\Backend\WorkWithUsController;
use App\Http\Controllers\Backend\EmailTemplateController;
use App\Http\Controllers\Backend\PackageController;
use App\Http\Controllers\Backend\SecurityPolicyController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\ClientController;

// All route names are prefixed with 'admin.'.
Route::redirect('/', '/admin/dashboard', 301);
Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('payment', [DashboardController::class, 'paymentHistory'])->name('payment');
Route::get('faq', [DashboardController::class, 'faqRequest'])->name('faqs');
Route::get('faq/create', [DashboardController::class, 'faqCreate'])->name('faq.create');
Route::post('faq/store', [DashboardController::class, 'faqStore'])->name('faq.store');
Route::get('faq/edit/{id}', [DashboardController::class, 'faqEdit'])->name('faq.edit');
Route::post('faq/update', [DashboardController::class, 'faqUpdate'])->name('faq.update');
Route::delete('faq/delete/{id}', [DashboardController::class, 'faqDelete'])->name('faq.destroy');

Route::get('refund',[DashboardController::class, 'refundRequest'])->name('refund');
Route::get('refund/accept/{id}',[DashboardController::class, 'refundRequestAccept'])->name('refund.accept');
Route::get('refund/reject/{id}',[DashboardController::class, 'refundRequestReject'])->name('refund.reject');

Route::get('customer/payment', [DashboardController::class, 'customerPayment'])->name('customer.payment');
Route::get('customer/payment/update/{id}', [DashboardController::class, 'customerPaymentUpdate']);

Route::get('creditpackage', [ContractorsController::class, 'creditPackagePrice'])->name('creditpackage');
Route::group(['namespace' => 'Category'], function ()
{
	Route::get('category', [CategoryController::class, 'index'])->name('category.index');
	Route::get('category/create', [CategoryController::class, 'create'])->name('category.create');
	Route::post('category', [CategoryController::class, 'store'])->name('category.store');

	Route::get('category/edit/{id}', [CategoryController::class, 'edit'])->name('category.edit');
	Route::get('category/show/{id}', [CategoryController::class, 'show'])->name('category.show');
	Route::post('category/update', [CategoryController::class, 'update'])->name('category.update');
	Route::delete('category/destroy/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
	Route::get('category/status/{id}', [CategoryController::class, 'status'])->name('category.status');
	Route::get('category/removeImage', [CategoryController::class, 'removeImageCat'])->name('subservices.removeImage');


});   
Route::group(['namespace' => 'Banner'], function (){
	Route::get('banner',[BannerController::class, 'index'])->name('banner.index');
	Route::get('banner/create',[BannerController::class, 'create'])->name('banner.create');
	Route::post('banner',[BannerController::class, 'store'])->name('banner.store');

	Route::get('banner/edit/{id}',[BannerController::class, 'edit'])->name('banner.edit');
	Route::get('banner/destroy/{id}',[BannerController::class, 'destroy'])->name('banner.destroy');
	Route::post('banner/update',[BannerController::class, 'update'])->name('banner.update');
	Route::get('banner/removeImage', [BannerController::class, 'removeImage'])->name('banner.removeimage');
});

Route::group(['namespace' => 'Services'], function ()
{
	Route::get('services', [ServicesController::class, 'index'])->name('services.index');
	Route::get('services/create', [ServicesController::class, 'create'])->name('services.create');
	Route::post('services', [ServicesController::class, 'store'])->name('services.store');

	Route::get('services/edit/{id}', [ServicesController::class, 'edit'])->name('services.edit');
	Route::get('services/show/{id}', [ServicesController::class, 'show'])->name('services.show');
	Route::post('services/update', [ServicesController::class, 'update'])->name('services.update');
	Route::delete('services/destroy/{id}', [ServicesController::class, 'destroy'])->name('services.destroy');
	Route::get('services/removeImage', [ServicesController::class, 'removeImage'])->name('services.removeimage');


});

Route::group(['namespace' => 'Subservices'], function ()
{
	Route::get('subservices', [SubservicesController::class, 'index'])->name('subservices.index');
	Route::get('subservices/create', [SubservicesController::class, 'create'])->name('subservices.create');
	Route::post('subservices', [SubservicesController::class, 'store'])->name('subservices.store');

	Route::get('subservices/edit/{id}', [SubservicesController::class, 'edit'])->name('subservices.edit');
	Route::get('subservices/show/{id}', [SubservicesController::class, 'show'])->name('subservices.show');
	Route::post('subservices/update', [SubservicesController::class, 'update'])->name('subservices.update');
	Route::delete('subservices/destroy/{id}', [SubservicesController::class, 'destroy'])->name('subservices.destroy');

	Route::get('subservices/getServices', [SubservicesController::class, 'getServices'])->name('subservices.getServices');
	Route::get('subservices/removeImage', [SubservicesController::class, 'removeImageSub'])->name('subservices.removeImage');

});

Route::group(['namespace' => 'Questions'], function ()
{


	Route::get('questions/getOptionView', [QuestionsController::class, 'getOptionView'])->name('questions.getOptionView');

	Route::get('questions/getRelatedQuestions', [QuestionsController::class, 'getRelatedQuestions'])->name('questions.getRelatedQuestions');

	Route::get('questions/getRelatedOptions', [QuestionsController::class, 'getRelatedOptions'])->name('questions.getRelatedOptions');

	Route::get('questions', [QuestionsController::class, 'index'])->name('questions.index');
	Route::get('questions/create', [QuestionsController::class, 'create'])->name('questions.create');
	Route::post('questions', [QuestionsController::class, 'store'])->name('questions.store');

	Route::get('questions/edit/{id}', [QuestionsController::class, 'edit'])->name('questions.edit');
	Route::get('questions/show/{id}', [QuestionsController::class, 'show'])->name('questions.show');
	Route::post('questions/update', [QuestionsController::class, 'update'])->name('questions.update');
	Route::delete('questions/destroy/{id}', [QuestionsController::class, 'destroy'])->name('questions.destroy');

	Route::get('questions/getServices', [QuestionsController::class, 'getServices'])->name('questions.getServices');

	Route::get('questions/getSubservices', [QuestionsController::class, 'getSubservices'])->name('questions.getSubservices');

	Route::get('questions/getChildSubservices', [QuestionsController::class, 'getChildSubservices'])->name('questions.getChildSubservices');

});



Route::group(['namespace' => 'Contractors'], function ()
{
    Route::get('contractors/creditpackage/{id}', [ContractorsController::class, 'creditPackage'])->name('contractors.creditpackage');
    Route::post('contractors/creditpackage/store', [ContractorsController::class, 'creditPackageStore'])->name('contractors.creditpackage.store');
	Route::get('contractors', [ContractorsController::class, 'index'])->name('contractors.index');

	Route::get('contractors1', [ContractorsController::class, 'index1'])->name('contractors1.index');

	Route::get('contractors/create', [ContractorsController::class, 'create'])->name('contractors.create');

	Route::post('contractors', [ContractorsController::class, 'store'])->name('contractors.store');

	Route::get('contractors/create_worker/{id}', [ContractorsController::class, 'createWorker'])->name('contractors.create_worker');
	Route::post('contractors/store_worker', [ContractorsController::class, 'storeWorker'])->name('contractors.store_worker');

	Route::get('contractors/all_workers/{id}', [ContractorsController::class, 'allWorkers'])->name('contractors.all_workers');

	Route::get('contractors/view_worker/{id}', [ContractorsController::class, 'viewWorker'])->name('contractors.view_worker');

	Route::get('contractors/get_districts', [ContractorsController::class, 'get_districts'])->name('questions.get_districts');


    Route::get('contractors/edit/{id}', [ContractorsController::class, 'edit'])->name('contractors.edit');

	Route::get('contractors/show/{id}', [ContractorsController::class, 'show'])->name('contractors.show');

	Route::post('contractors/update', [ContractorsController::class, 'update'])->name('contractors.update');

	Route::get('contractors/edit_worker/{id}', [ContractorsController::class, 'editWorker'])->name('contractors.edit_worker');
	Route::post('contractors/update_worker', [ContractorsController::class, 'updateWorker'])->name('contractors.update_worker');

	Route::get('contractors/show_services_offered/{id}', [ContractorsController::class, 'showServicesOffered'])->name('contractors.show_services_offered');

	Route::get('contractors/edit_services_offered/{id}', [ContractorsController::class, 'editServicesOffered'])->name('contractors.edit_services_offered');


	Route::get('contractors/add_services_offered/{id}', [ContractorsController::class, 'addServicesOffered'])->name('contractors.add_services_offered');

	Route::post('contractors/store_services_offered', [ContractorsController::class, 'storeServicesOffered'])->name('contractors.store_services_offered');

	Route::get('contractors/edit_services_offered/{id}', [ContractorsController::class, 'editServicesOffered'])->name('contractors.edit_services_offered');

   Route::post('contractors/update_services_offered', [ContractorsController::class, 'updateServicesOffered'])->name('contractors.update_services_offered');

	Route::get('contractors/all_contractor_documents/{user_id}', [ContractorsController::class, 'allContractorDocuments'])->name('contractors.all_contractor_documents');

	Route::get('contractors/add_contractor_documents/{user_id}', [ContractorsController::class, 'addContractorDocuments'])->name('contractors.add_contractor_documents');



	Route::post('contractors/store_contractor_documents', [ContractorsController::class, 'storeContractorDocuments'])->name('contractors.store_contractor_documents');



	Route::delete('contractors/destroy_worker/{id}', [ContractorsController::class, 'destroyWorker'])->name('contractors.destroy_worker');

	 Route::get('contractors/destroy/{id}', [ContractorsController::class, 'destroy'])->name('contractors.destroy');


	Route::get('contractors/edit_coverage_area/{id}', [ContractorsController::class, 'editCoverageArea'])->name('contractors.edit_coverage_area');

	Route::get('contractors/ratings_reviews/{id}', [ContractorsController::class, 'ratings_reviews'])->name('contractors.ratings_reviews');
	
	Route::post('contractors/update_ratings_reviews', [ContractorsController::class, 'update_ratings_reviews'])->name('contractors.update_ratings_reviews');
	
	Route::get('contractors/edit_ratings_reviews/{id}', [ContractorsController::class, 'edit_ratings_reviews'])->name('contractors.edit_ratings_reviews');

	Route::get('contractors/destroy_ratings_reviews/{id}', [ContractorsController::class, 'destroy_ratings_reviews'])->name('contractors.destroy_ratings_reviews');

	Route::post('contractors/update_coverage_area', [ContractorsController::class, 'updateCoverageArea'])->name('contractors.update_coverage_area');

	Route::get('contractors/edit_payment_method/{id}', [ContractorsController::class, 'editPaymentMethod'])->name('contractors.edit_payment_method');

	Route::post('contractors/update_payment_method', [ContractorsController::class, 'updatePaymentMethods'])->name('contractors.update_payment_method');

	Route::get('contractors/add_contractor_certificate/{user_id}', [ContractorsController::class, 'addContractorCertificate'])->name('contractors.add_contractor_certificate');

	Route::post('contractors/store_contractor_certificate', [ContractorsController::class, 'storeContractorCertificate'])->name('contractors.store_contractor_certificate');

	Route::get('contractors/all_contractor_certificates/{user_id}', [ContractorsController::class, 'allContractorCertificates'])->name('contractors.all_contractor_certificates');


	Route::get('contractors/add_contractor_police_records/{user_id}', [ContractorsController::class, 'addContractorPoliceRecords'])->name('contractors.add_contractor_police_records');

	Route::post('contractors/store_contractor_police_records', [ContractorsController::class, 'storeContractorPoliceRecords'])->name('contractors.store_contractor_police_records');

	Route::get('contractors/all_contractor_police_records/{user_id}', [ContractorsController::class, 'allContractorPoliceRecords'])->name('contractors.all_contractor_police_records');


	Route::get('contractors/add_contractor_gallery/{user_id}', [ContractorsController::class, 'addContractorGallery'])->name('contractors.add_contractor_gallery');


	Route::post('contractors/store_contractor_gallery', [ContractorsController::class, 'storeContractorGallery'])->name('contractors.store_contractor_gallery');

	Route::get('contractors/all_contractor_images_gallery/{user_id}', [ContractorsController::class, 'allContractorImagesGallery'])->name('contractors.all_contractor_images_gallery');

	Route::get('contractors/all_contractor_videos_gallery/{user_id}', [ContractorsController::class, 'allContractorVideosGallery'])->name('contractors.all_contractor_videos_gallery');

	Route::get('contractors/verify_certificate/{certificate_id}', [ContractorsController::class, 'verifyCertificate'])->name('contractors.verify_certificate');

	Route::get('contractors/delete_certificate/{certificate_id}', [ContractorsController::class, 'deleteCertificate'])->name('contractors.delete_certificate');

	Route::get('contractors/delete_gallery_image/{certificate_id}', [ContractorsController::class, 'deleteGalleryImage'])->name('contractors.delete_gallery_image');

	Route::get('contractors/delete_gallery_video/{certificate_id}', [ContractorsController::class, 'deleteGalleryVideo'])->name('contractors.delete_gallery_video');


     Route::get('contractors/show_service_request/{id}', [ContractorsController::class, 'showServiceRequest'])->name('contractors.show_service_request');

	 Route::get('contractors/service_request_contractor/{id}', [ContractorsController::class, 'serviceRequests'])->name('contractors.serviceRequests');

	 Route::get('contractors/all_requests_by_status/{status}/{user_id}', [ContractorsController::class, 'allRequestsByStatus'])->name('contractors.all_requests_by_status');

     Route::get('contractors/deleted', [ContractorsController::class, 'getDeleted'])->name('contractors.deleted');

     Route::get('contractors/restore/{id}', [ContractorsController::class, 'restore'])->name('contractors.restore');

     Route::get('contractors/delete/{id}', [ContractorsController::class, 'delete'])->name('contractors.delete-permanently');

	Route::get('aplicacions', [ContractorsController::class, 'aplicacions'])->name('aplicacions');
    Route::get('aplicacions1', [ContractorsController::class, 'aplicacions1'])->name('aplicacions1.index');

    Route::get('aplicacions2', [ContractorsController::class, 'aplicacions2'])->name('aplicacions2.index');

	Route::get('aplicacions/accept/{id}', [ContractorsController::class, 'aplicacionsAccept'])->name('aplicacions.accept');
    Route::get('aplicacions/decline/{id}', [ContractorsController::class, 'aplicacionsDecline'])->name('aplicacions.decline');
    Route::get('contractors/payment/{id}', [ContractorsController::class, 'paymentInfo'])->name('contractors.payment');

});

Route::group(['namespace' => 'newsletter'], function ()
{
	Route::get('newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');

	Route::get('sendmail/{id}', [NewsletterController::class, 'show'])->name('newsletter.show');

	Route::post('sendmailsingle', [NewsletterController::class, 'SendmailtToSubscribers'])->name('newsletter.SendmailtToSubscribers');

	Route::post('sendmailtoall', [NewsletterController::class, 'SendmailtToAllshow'])->name('newsletter.sendmailtoall');

	Route::post('postsendmailtoall', [NewsletterController::class, 'postSendMailall'])->name('newsletter.postSendMailall');

});


 Route::get('contactus/sendMail', 'ContactUsController@sendMailToUsers')->name('contactus.sendMail.show');

 Route::post('contactus/sendMail', 'ContactUsController@sendMailToUsers')->name('contactus.sendMail');

 Route::get('sendbasicemail','ContactUsController@basic_email');




Route::group(['namespace' => 'Cities'], function ()
{
	Route::get('cities', [CitiesController::class, 'index'])->name('cities.index');
	Route::get('cities/create', [CitiesController::class, 'create'])->name('cities.create');
	Route::post('cities', [CitiesController::class, 'store'])->name('cities.store');

	Route::get('cities/edit/{id}', [CitiesController::class, 'edit'])->name('cities.edit');
	Route::post('cities/update', [CitiesController::class, 'update'])->name('cities.update');
	Route::delete('cities/destroy/{id}', [CitiesController::class, 'destroy'])->name('cities.destroy');

	Route::get('cities/polygons/{city_id}', [CitiesController::class, 'polygons'])->name('cities.polygons');

	Route::get('cities/create_polygon/{city_id}', [CitiesController::class, 'createPolygon'])->name('cities.create_polygon');

	Route::post('cities/store_polygon', [CitiesController::class, 'storePolygon'])->name('cities.store_polygon');

	Route::get('cities/polygons_by_area/{city_id}/{area_type}', [CitiesController::class, 'polygonsByAreaType'])->name('cities.polygons_by_area');

	Route::get('cities/add_more_polygon/{zone_id}', [CitiesController::class, 'addMorePolygon'])->name('cities.add_more_polygon');

	Route::post('cities/update_zone', [CitiesController::class, 'updateZone'])->name('cities.update_zone');

	Route::get('cities/remove/{zone_id}', [CitiesController::class, 'remove'])->name('cities.remove');

	Route::post('cities/remove_polygon', [CitiesController::class, 'removePolygon'])->name('cities.remove_polygon');

	Route::get('cities/all_zone_by_city/{city_id}', [CitiesController::class, 'allZoneByCity'])->name('cities.all_zone_by_city');

	Route::get('cities/all_zones', [CitiesController::class, 'allZones'])->name('cities.all_zones');

	Route::delete('cities/destroy_zone/{id}', [CitiesController::class, 'destroyZone'])->name('cities.destroy_zone');


});

Route::group(['namespace' => 'Provinces'], function ()
{
	Route::get('provinces', [ProvincesController::class, 'index'])->name('provinces.index');

	Route::get('provinces/create', [ProvincesController::class, 'create'])->name('provinces.create');
	Route::post('provinces', [ProvincesController::class, 'store'])->name('provinces.store');

	Route::get('provinces/edit/{id}', [ProvincesController::class, 'edit'])->name('provinces.edit');
	Route::post('provinces/update', [ProvincesController::class, 'update'])->name('provinces.update');
	Route::delete('provinces/destroy/{id}', [ProvincesController::class, 'destroy'])->name('provinces.destroy');

});


Route::group(['namespace' => 'Site_Setting'], function ()
{
	Route::get('sitesetting', [SiteSettingController::class, 'index'])->name('site_setting.index');

   Route::post('sitesetting', [SiteSettingController::class, 'updatesitesetting'])->name('site_setting.updatesitesetting');

});


Route::group(['namespace' => 'area'], function ()
{
	Route::get('area_management', [AreaController::class, 'index'])->name('area.index');

   Route::post('area_management', [AreaController::class, 'updateAreapricePercent'])->name('area.update_areatype_percent');

});


Route::group(['namespace' => 'Contactus'], function ()
{
	Route::get('contactus', [ContactUsController::class, 'index'])->name('contactus.index');

	Route::get('contactus/description/{id}', [ContactUsController::class, 'show'])->name('description.show');

	Route::get('contactusdggt', [ContactUsController::class, 'map'])->name('contactus.map');

});


Route::group(['namespace' => 'service_request'], function ()
{

	Route::get('service_request', [ServiceRequestController::class, 'index'])->name('service_request.index');
    Route::get('service_request/forward/{id}', [ServiceRequestController::class, 'forward'])->name('service_request.forward');
    Route::get('service_request/show/{id}', [ServiceRequestController::class, 'show'])->name('service_request.show');
    Route::DELETE('service_request/delete/{id}', [ServiceRequestController::class, 'destroy'])->name('service_request.destroy');

    Route::get('all_requests_by_status/{status}', [ServiceRequestController::class, 'allRequestsByStatus'])->name('service_request.all_requests_by_status');

});

Route::group(['namespace' => 'Districts'], function ()
{
	Route::get('districts', [DistrictsController::class, 'index'])->name('districts.index');
	Route::get('districts/create', [DistrictsController::class, 'create'])->name('districts.create');
	Route::post('districts', [DistrictsController::class, 'store'])->name('districts.store');

	Route::get('districts/edit/{id}', [DistrictsController::class, 'edit'])->name('districts.edit');
	Route::post('districts/update', [DistrictsController::class, 'update'])->name('districts.update');
	Route::delete('districts/destroy/{id}', [DistrictsController::class, 'destroy'])->name('districts.destroy');

});

Route::group(['namespace' => 'price_range'], function ()
{
	Route::get('price_range', [PriceRangeController::class, 'index'])->name('price_range.index');
	Route::get('price_range/create', [PriceRangeController::class, 'create'])->name('price_range.create');
	Route::post('price_range', [PriceRangeController::class, 'store'])->name('price_range.store');

	Route::get('price_range/edit/{id}', [PriceRangeController::class, 'edit'])->name('price_range.edit');
	Route::post('price_range/update', [PriceRangeController::class, 'update'])->name('price_range.update');

	Route::delete('price_range/destroy/{id}', [PriceRangeController::class, 'destroy'])->name('price_range.destroy');

});

Route::group(['namespace' => 'Zone'], function ()
{


	Route::get('zone', [ZoneController::class, 'index'])->name('zone.index');
	Route::get('zone/create', [ZoneController::class, 'create'])->name('zone.create');
	Route::post('zone', [ZoneController::class, 'store'])->name('zone.store');

	Route::get('zone/edit/{id}', [ZoneController::class, 'edit'])->name('zone.edit');

	Route::post('zone/update', [ZoneController::class, 'update'])->name('zone.update');
	Route::get('zone/remove/{id}', [ZoneController::class, 'remove'])->name('zone.remove');
	Route::post('zone/remove_polygon', [ZoneController::class, 'removePolygon'])->name('zone.remove_polygon');

	Route::delete('zone/destroy/{id}', [ZoneController::class, 'destroy'])->name('zone.destroy');



});

Route::group(['namespace' => 'Polygon'], function ()
{
    Route::get('polygon', [PolygonController::class, 'index'])->name('polygon.index');
	Route::get('polygon/create', [PolygonController::class, 'create'])->name('polygon.create');
	Route::post('polygon', [PolygonController::class, 'store'])->name('polygon.store');

	Route::get('polygon/edit/{id}', [PolygonController::class, 'edit'])->name('polygon.edit');
	Route::post('polygon/update', [PolygonController::class, 'update'])->name('polygon.update');


});


Route::group(['namespace' => 'Childsubservices'], function ()
{
	Route::get('childsubservices', [ChildSubservicesController::class, 'index'])->name('childsubservices.index');
	Route::get('childsubservices/create', [ChildSubservicesController::class, 'create'])->name('childsubservices.create');
	Route::post('childsubservices', [ChildSubservicesController::class, 'store'])->name('childsubservices.store');

	Route::get('childsubservices/edit/{id}', [ChildSubservicesController::class, 'edit'])->name('childsubservices.edit');
	Route::get('childsubservices/show/{id}', [ChildSubservicesController::class, 'show'])->name('childsubservices.show');
	Route::post('childsubservices/update', [ChildSubservicesController::class, 'update'])->name('childsubservices.update');
	Route::delete('childsubservices/destroy/{id}', [ChildSubservicesController::class, 'destroy'])->name('childsubservices.destroy');

	Route::get('childsubservices/getServices', [ChildSubservicesController::class, 'getServices'])->name('childsubservices.getServices');

	Route::get('childsubservices/getSubServices', [ChildSubservicesController::class, 'getSubServices'])->name('childsubservices.getSubServices');


});

Route::group(['namespace' => 'Client'], function (){
	Route::get('client', [ClientController::class, 'index'])->name('client.index');
	Route::get('client/edit/{id}', [ClientController::class, 'edit'])->name('client.edit');
	Route::get('client/show/{id}', [ClientController::class, 'show'])->name('client.show');
	Route::get('client/destroy/{id}', [ClientController::class, 'destroy'])->name('client.destroy');
	Route::post('client/update', [ClientController::class, 'update'])->name('client.update');
	Route::get('client/deleted', [ClientController::class, 'getDeleted'])->name('client.deleted');
	Route::get('client/restore/{id}', [ClientController::class, 'restore'])->name('client.restore');
	Route::get('client/delete/{id}', [ClientController::class, 'delete'])->name('client.delete-permanently');

});


Route::group(['namespace' => 'Company'], function ()
{
    Route::get('company/creditpackage/{id}', [CompanyController::class, 'creditPackage'])->name('company.creditpackage');
    Route::post('company/creditpackage/store', [CompanyController::class, 'creditPackageStore'])->name('company.creditpackage.store');
    
	Route::get('company', [CompanyController::class, 'index'])->name('company.index');
	// Route::get('contractors1', [ContractorsController::class, 'index1'])->name('contractors1.index');
	Route::get('company1', [CompanyController::class, 'index1'])->name('company1.index');

	Route::get('company/create', [CompanyController::class, 'create'])->name('company.create');
	Route::post('company', [CompanyController::class, 'store'])->name('company.store');

	Route::get('company/create_worker/{id}', [CompanyController::class, 'createWorker'])->name('company.create_worker');
	Route::post('company/store_worker', [CompanyController::class, 'storeWorker'])->name('company.store_worker');

	Route::get('company/all_workers/{id}', [CompanyController::class, 'allWorkers'])->name('company.all_workers');

	Route::get('company/view_worker/{id}', [CompanyController::class, 'viewWorker'])->name('company.view_worker');

	Route::get('company/get_districts', [CompanyController::class, 'get_districts'])->name('questions.get_districts');

	Route::get('company/show/{id}', [CompanyController::class, 'show'])->name('company.show');

	Route::get('company/edit/{id}', [CompanyController::class, 'edit'])->name('company.edit');
	Route::post('company/update', [CompanyController::class, 'update'])->name('company.update');

	Route::get('company/edit_worker/{id}', [CompanyController::class, 'editWorker'])->name('company.edit_worker');
	Route::post('company/update_worker', [CompanyController::class, 'updateWorker'])->name('company.update_worker');


	Route::get('company/add_services_offered/{id}', [CompanyController::class, 'addServicesOffered'])->name('company.add_services_offered');
	Route::post('company/store_services_offered', [CompanyController::class, 'storeServicesOffered'])->name('company.store_services_offered');


	Route::delete('company/destroy_worker/{id}', [CompanyController::class, 'destroyWorker'])->name('company.destroy_worker');

	Route::get('company/destroy/{id}', [CompanyController::class, 'destroy'])->name('company.destroy');

	Route::get('company/edit_coverage_area/{id}', [CompanyController::class, 'editCoverageArea'])->name('company.edit_coverage_area');

	Route::get('company/ratings_reviews/{id}', [CompanyController::class, 'ratings_reviews'])->name('company.ratings_reviews');

	Route::post('company/update_ratings_reviews', [CompanyController::class, 'update_ratings_reviews'])->name('company.update_ratings_reviews');
	
	Route::get('company/edit_ratings_reviews/{id}', [CompanyController::class, 'edit_ratings_reviews'])->name('company.edit_ratings_reviews');

	Route::get('company/destroy_ratings_reviews/{id}', [CompanyController::class, 'destroy_ratings_reviews'])->name('company.destroy_ratings_reviews');

	Route::post('company/update_coverage_area', [CompanyController::class, 'updateCoverageArea'])->name('company.update_coverage_area');

	Route::get('company/edit_payment_method/{id}', [CompanyController::class, 'editPaymentMethod'])->name('company.edit_payment_method');

	Route::post('company/update_payment_method', [CompanyController::class, 'updatePaymentMethods'])->name('company.update_payment_method');

	Route::get('company/add_company_certificate/{user_id}', [CompanyController::class, 'addCompanyCertificate'])->name('company.add_company_certificate');

	Route::post('company/store_company_certificate', [CompanyController::class, 'storeCompanyCertificate'])->name('company.store_company_certificate');

	Route::get('company/all_company_certificates/{user_id}', [CompanyController::class, 'allCompanyCertificates'])->name('company.all_company_certificates');


	Route::get('company/add_company_police_records/{user_id}', [CompanyController::class, 'addCompanyPoliceRecords'])->name('company.add_company_police_records');

	Route::post('company/store_company_police_records', [CompanyController::class, 'storeCompanyPoliceRecords'])->name('company.store_company_police_records');

	Route::get('company/all_company_police_records/{user_id}', [CompanyController::class, 'allCompanyPoliceRecords'])->name('company.all_company_police_records');


	Route::get('company/add_company_gallery/{user_id}', [CompanyController::class, 'addCompanyGallery'])->name('company.add_company_gallery');


	Route::post('company/store_company_gallery', [CompanyController::class, 'storeCompanyGallery'])->name('company.store_company_gallery');

	Route::get('company/all_company_images_gallery/{user_id}', [CompanyController::class, 'allCompanyImagesGallery'])->name('company.all_company_images_gallery');

	Route::get('company/all_company_videos_gallery/{user_id}', [CompanyController::class, 'allCompanyVideosGallery'])->name('company.all_company_videos_gallery');

	Route::get('company/verify_certificate/{certificate_id}', [CompanyController::class, 'verifyCertificate'])->name('company.verify_certificate');

	Route::get('company/delete_certificate/{certificate_id}', [CompanyController::class, 'deleteCertificate'])->name('company.delete_certificate');

	Route::get('company/delete_gallery_image/{certificate_id}', [CompanyController::class, 'deleteGalleryImage'])->name('company.delete_gallery_image');

	Route::get('company/delete_gallery_video/{certificate_id}', [CompanyController::class, 'deleteGalleryVideo'])->name('company.delete_gallery_video');

	Route::get('company/service_request_company/{id}', [CompanyController::class, 'serviceRequests'])->name('company.serviceRequests');

	 Route::get('company/show_service_request/{id}', [CompanyController::class, 'showServiceRequest'])->name('company.show_service_request');

	 Route::get('company/all_requests_by_status/{status}/{user_id}', [CompanyController::class, 'allRequestsByStatus'])->name('company.all_requests_by_status');

	 Route::get('company/show_services_offered/{id}', [CompanyController::class, 'showServicesOffered'])->name('company.show_services_offered');

	 Route::get('company/edit_services_offered/{id}', [CompanyController::class, 'editServicesOffered'])->name('company.edit_services_offered');

    Route::post('company/update_services_offered', [CompanyController::class, 'updateServicesOffered'])->name('company.update_services_offered');

    Route::get('company/deleted', [CompanyController::class, 'getDeleted'])->name('company.deleted');

     Route::get('company/restore/{id}', [CompanyController::class, 'restore'])->name('company.restore');

     Route::get('company/delete/{id}', [CompanyController::class, 'delete'])->name('company.delete-permanently');
    Route::get('company/payment/{id}', [CompanyController::class, 'paymentInfo'])->name('company.payment');


}); 
Route::get('work', [WorkController::class, 'index'])->name('index');
Route::group(['namespace' => 'Review'], function ()
{

	Route::get('review', [ReviewController::class, 'index'])->name('review.index');
	Route::get('review/show/{id}', [ReviewController::class, 'show'])->name('review.show');
    Route::get('review/status/{id}', [ReviewController::class, 'status'])->name('review.status');


});

Route::group(['namespace' => 'Other'], function ()
{

	Route::get('work', [WorkController::class, 'index'])->name('work.index');
	Route::post('work', [WorkController::class, 'updatework'])->name('work.updatework');

	Route::get('about_us', [AboutController::class, 'index'])->name('about_us.index');
	Route::post('about_us', [AboutController::class, 'updateabout'])->name('about_us.updateabout');
	Route::get('news', [NewsController::class, 'index'])->name('news.index');
	Route::post('news', [NewsController::class, 'updatenews'])->name('news.updatenews');

	Route::get('terms_and_condition', [TermAndConditionController::class, 'index'])->name('terms_and_condition.index');

	Route::post('terms_and_condition', [TermAndConditionController::class, 'updateTermAndCondition'])->name('terms_and_condition.updateTermAndCondition');

	Route::get('work_with_us', [WorkWithUsController::class, 'index'])->name('work_with_us.index');

	Route::post('work_with_us', [WorkWithUsController::class, 'updateWorkWithUs'])->name('work_with_us.updateWorkWithUs');
});

Route::group(['namespace' => 'emai_template'], function ()
{
	Route::get('email_template', [EmailTemplateController::class, 'index'])->name('email_template.index');
	Route::get('email_template/create', [EmailTemplateController::class, 'create'])->name('email_template.create');
	Route::post('email_template/store', [EmailTemplateController::class, 'store'])->name('email_template.store');
});

Route::group(['namespace' => 'package'], function ()
{
	Route::get('package', [PackageController::class, 'index'])->name('package.index');
	Route::get('package/create', [PackageController::class, 'create'])->name('package.create');
	Route::post('package/store', [PackageController::class, 'store'])->name('package.store');
	Route::get('package/destroy/{id}', [PackageController::class, 'destroy'])->name('package.destroy');
	Route::get('package/edit/{id}', [PackageController::class, 'edit'])->name('package.edit');
	Route::post('package/update', [PackageController::class, 'update'])->name('package.update');
	Route::delete('package/destroy/{id}', [PackageController::class, 'destroy'])->name('package.destroy');
});


Route::group(['namespace' => 'security_policy'], function ()
{
	Route::get('security-policy', [SecurityPolicyController::class, 'index'])->name('security-policy.index');
	Route::post('security-policy', [SecurityPolicyController::class, 'updatepolicies'])->name('security-policy.updatepolicies');

});