 <?php

use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/ 
Route::post('getAllCategoryList', 'Frontend\ApiController@getAllCategoryList');
Route::post('getAllServiceList', 'Frontend\ApiController@getAllServiceList');
Route::post('getAllSubServiceList', 'Frontend\ApiController@getAllSubServiceList');
Route::post('getAllChildSubServiceList', 'Frontend\ApiController@getAllChildSubServiceList');
Route::post('signIn', 'Frontend\ApiController@signIn');
Route::post('signUp', 'Frontend\ApiController@signUp');
Route::post('signOut', 'Frontend\ApiController@signOut');
Route::post('verifyOtpCode', 'Frontend\ApiController@verifyOtpCode');
Route::post('forgotPassword', 'Frontend\ApiController@forgotPassword');
Route::post('updatesql', 'Frontend\ApiController@updateSql');

Route::post('updateForgotPassword', 'Frontend\ApiController@updateForgotPassword');
Route::post('updatePassword', 'Frontend\ApiController@updatePassword');
Route::post('updateProfile', 'Frontend\ApiController@updateProfile');
Route::post('createWorkerProfile', 'Frontend\ApiController@createWorkerProfile');
Route::post('getDocumentTypes', 'Frontend\ApiController@getDocumentTypes');
Route::post('sendApplicationRequest', 'Frontend\ApiController@sendApplicationRequest');
Route::post('verifyServiceRequestOTP', 'Frontend\ApiController@verifyServiceRequestOTP');
Route::post('serviceVerifyNotification', 'Frontend\ApiController@serviceVerifyNotification');
Route::post('createFolder', 'Frontend\ApiController@createFolder');
Route::post('folderList', 'Frontend\ApiController@folderList');
Route::post('getProfileById', 'Frontend\ApiController@getProfileById');
Route::post('getPaymentMethods', 'Frontend\ApiController@getPaymentMethods');
Route::post('getNewOpportunities', 'Frontend\ApiController@getNewOpportunities');
Route::post('buyOpportunity', 'Frontend\ApiController@buyOpportunity');
Route::post('ignoreOpportunity', 'Frontend\ApiController@ignoreOpportunity');
Route::post('readOpportunity', 'Frontend\ApiController@readOpportunity');

Route::post('jobList', 'Frontend\ApiController@jobList');
Route::post('manageRequestStatus', 'Frontend\ApiController@manageRequestStatus');
Route::post('jobDetail', 'Frontend\ApiController@jobDetail');
Route::post('getProvincesList', 'Frontend\ApiController@getProvincesList');
Route::post('getCitiesList', 'Frontend\ApiController@getCitiesList');
Route::post('getQuestionnaireByTypeId', 'Frontend\ApiController@getQuestionnaireByTypeId');
Route::post('getQuestionnaireByOptionId', 'Frontend\ApiController@getQuestionnaireByOptionId');
Route::post('sendMessageToUsers', 'Frontend\ApiController@sendMessageToUsers');
Route::post('getMessageListByUserId', 'Frontend\ApiController@getMessageListByUserId');
Route::post('markMessageToStarred', 'Frontend\ApiController@markMessageToStarred');
Route::post('getStarredMessageListUserId', 'Frontend\ApiController@getStarredMessageListUserId');
Route::post('deleteMessage', 'Frontend\ApiController@deleteMessage');
Route::post('deleteAllMessages', 'Frontend\ApiController@deleteAllMessages');
Route::post('getAllChatByUserId', 'Frontend\ApiController@getAllChatByUserId');
Route::post('ratingReviews', 'Frontend\ApiController@ratingReviews');
Route::post('getRequestList', 'Frontend\ApiController@getRequestList');
Route::post('getRequestDetail', 'Frontend\ApiController@getRequestDetail');
Route::post('moveToFolder', 'Frontend\ApiController@moveToFolder');
Route::post('profileComparisonDetail', 'Frontend\ApiController@profileComparisonDetail');
Route::post('getAllHomePageServiceList', 'Frontend\ApiController@getAllHomePageServiceList');
Route::post('searchingApi', 'Frontend\ApiController@searchingApi');
Route::post('getAllArea', 'Frontend\ApiController@getAllArea');
Route::post('getAllServiceSubService', 'Frontend\ApiController@getAllServiceSubService');
Route::post('readMessage', 'Frontend\ApiController@readMessage');
Route::post('proReadMessage', 'Frontend\ApiController@proReadMessage');
Route::post('unreadMessageCountByUserId', 'Frontend\ApiController@unreadMessageCountByUserId');
Route::post('unreadMessageCountByProId', 'Frontend\ApiController@unreadMessageCountByProId');
Route::post('deleteGalleryVideo', 'Frontend\ApiController@deleteGalleryVideo');
Route::post('deleteCertAndPoliceRecordDoc', 'Frontend\ApiController@deleteCertAndPoliceRecordDoc');
Route::post('deleteCertAndPoliceRecordImage', 'Frontend\ApiController@deleteCertAndPoliceRecordImage');
Route::post('deleteGalleryImage', 'Frontend\ApiController@deleteGalleryImage');

Route::post('getRequestListByFolderId', 'Frontend\ApiController@getRequestListByFolderId');
Route::post('getAssignedContractorAndCompaniesProfileDetailsById', 'Frontend\ApiController@getAssignedContractorAndCompaniesProfileDetailsById');

Route::post('getReviewListByUserId', 'Frontend\ApiController@getReviewListByUserId');

Route::post('GetReviewListByProUserId', 'Frontend\ApiController@GetReviewListByProUserId');

Route::post('GetSubscriptionPackage', 'Frontend\ApiController@GetSubscriptionPackage');
Route::post('HireProOrCompany', 'Frontend\ApiController@HireProOrCompany');
Route::post('readJobList', 'Frontend\ApiController@readJobList');
Route::post('termAndCondition', 'Frontend\ApiController@termAndCondition');
Route::post('sendRatingReviewRequest', 'Frontend\ApiController@sendRatingReviewRequest');
Route::post('receivedPaymentByCreditCard', 'Frontend\ApiController@receivedPaymentByCreditCard');
Route::post('paymentRequestBuyPro', 'Frontend\ApiController@paymentRequestBuyPro');
Route::post('questionType', 'Frontend\ApiController@questionType');
Route::post('multipleQuestion', 'Frontend\ApiController@multipleQuestion');
Route::post('checkNextQuestion','Frontend\ApiController@checkNextQuestion');
Route::post('socialLogin','Frontend\ApiController@socialLogin');
Route::post('sendVerifyApprovelRequest','Frontend\ApiController@sendVerifyApprovelRequest');
Route::post('refundRequest','Frontend\ApiController@refundRequest');
Route::post('refundFaq','Frontend\ApiController@refundFaq');
Route::post('requestProfileUpdate','Frontend\ApiController@requestProfileUpdate');

Route::post('deleteAccount','Frontend\ApiController@deleteAccount');

Route::get('notification/{id}/{email}/{deviceId}/{device}', 'Frontend\ApiController@getNotificaton');