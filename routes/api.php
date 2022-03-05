<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('confirm_user_email', 'UserController@confirmEmail');
});

// general unprotected routes
Route::group(['middleware' => 'api'], function($router){
    Route::get('categories', 'CategoryController@getAll');
    Route::get('locations', 'LocationController@getAll');
    Route::get('get_min_featured_services', 'ServiceController@getMinFeatured');
    Route::get('get_min_popular_services', 'ServiceController@getMinPopular');
    Route::get('get_new_services', 'ServiceController@getNewestServices');
    Route::get('popular_locations', 'LocationController@getPopular');
    Route::get('get_service/{id}', 'ServiceController@getService');
    Route::post('increment_view_count/{id}', 'ServiceController@incrementViewCount');
    Route::get('get_services_by_locs/{id}', 'ServiceController@getServicesByLocs');
    Route::get('get_loc_by_id/{id}', 'LocationController@getLocationById');
    Route::post('search_for_services', 'ServiceController@searchForServices');
    Route::get('get_portfolio/{id}', 'PortfolioController@getPortfolio');
    Route::get('get_other_portfolios/{id}', 'PortfolioController@getOtherPortfolios');
    Route::post('send_recovery_password_email', 'PasswordRecoveryController@passwordRecoveryRequest');
    Route::post('confirm_pswd_reset_req', 'PasswordRecoveryController@ConfirmPasswordRequestToken');
    Route::post('reset_user_password', 'PasswordRecoveryController@ResetPassword');
    Route::get('get_similar_services/{id}', 'ServiceController@getSimilarServices');
    Route::get('get_popular_services', 'ServiceController@getPopularServices');
    Route::get('get_all_services', 'ServiceController@getAllServices');
    Route::get('get_top_services_for_location/{cat}/{loc}/{id}', 'ServiceController@getTopServicesForLocation');
    Route::get('clients/export/', 'TestClientController@export');
    Route::post('send_enquiry', 'EnquiryController@sendEnquiry');
    Route::post('send-notification', 'MiscController@sendNotification');
    Route::get('get_featured_testimonials', 'TestimonialController@getFeatured');
    Route::get('get_pgntd_testimonials', 'TestimonialController@getPgndtTestimonials');
    Route::get('get_pntg_services', 'ServiceController@getPgndtServices');
    Route::get('filter_services_by_cat/{id}', 'ServiceController@filterServicesByCat');
    Route::get('filter_services_by_loc/{id}', 'ServiceController@filterServicesByLoc');
    Route::get('filter_services/{cat}/{loc}', 'ServiceController@filterServices');
    Route::get('get_service_img_from_s3/{id}', 'ServiceController@getServiceImgFromS3');
    Route::get('get_service_author_img_from_s3/{id}', 'ServiceController@getServiceAuthorImgFromS3');
});

// auth user routes(protected)
Route::group(['middleware' => 'jwt.auth',  'prefix' => 'auth'], function($router){
    Route::post('logout', 'AuthController@logout');
    Route::get('auth_service', 'ServiceController@getAuthService');
    Route::post('service/create', 'ServiceController@CreateService');
    Route::post('service/add_image', 'ServiceController@AddImageToService');
    Route::post('upload_pf_images/{id}', 'PortfolioController@uploadFiles');
    Route::get('get_my_portfolio_files/{id}', 'PortfolioController@getPortfolioFiles');
    Route::delete('service/{id}', 'ServiceController@deleteService');
    Route::post('new_service_review/{id}', 'ReviewController@createReview');
    Route::post('delete_review/{id}', 'ReviewController@deleteReview');
    Route::post('update_review/{id}', 'ReviewController@updateReview');
    Route::get('get_auth_user_service', 'UserProfileController@getAuthUserService');
    Route::post('update_auth_profile', 'UserProfileController@updateUserProfile');
    Route::post('update_auth_profile_picture', 'UserProfileController@updateUserProfilePic');
    Route::post('confirm_current_pswd', 'UserProfileController@confirmCurrentPswd');
    Route::post('update_auth_profile_password', 'UserProfileController@updateAuthUserPswd');
    Route::get('get_my_service_reviews', 'UserProfileController@getMyServiceReviews');
    Route::post('update_portfolio/{id}', 'PortfolioController@updatePf');
    Route::post('del_pf_file/{id}', 'PortfolioController@delFile');
    Route::get('get_auth_testimonial', 'UserProfileController@getAuthTestimonial');
    Route::post('update_testimonial', 'UserProfileController@updateTestimonial');
    Route::get('check_auth_testimonial', 'UserProfileController@checkAuthTestimonial');
    Route::post('create_testimonial', 'UserProfileController@createTestimonial');
    Route::get('get_s3_profile_pic', 'UserProfileController@getUserProfileFromS3');
    Route::get('get_my_service_image_from_s3', 'UserProfileController@getMyServiceImgFromS3');
});

Route::group([['middleware' => 'jwt.auth', 'PortfolioCount'],  'prefix' => 'auth'], function($router){
    Route::post('portfolio/{id}', 'PortfolioController@createPortFolio');
});

//admin unprotected routes
Route::group(['middleware' => 'api', 'prefix' => 'auth-admin'], function ($router) {
    Route::post('login', 'AdminAuthController@login');
    // Route::get('all_superusers', 'AdminController@getAllSuperUsers');
});

// admin protected routes
Route::group(['prefix' => 'auth-admin', 'middleware' => ['assign.guard:admin-api', 'jwt.auth']], function($router){
    Route::get('all_superusers', 'AdminController@getAllSuperUsers');
    Route::post('super_user/{id}/update', 'AdminController@updateSuperUser');
    Route::post('super_user/{id}/delete', 'AdminController@deleteSuperUser');
    Route::get('get_super_user/{id}', 'AdminController@getSuperUser');
    Route::post('change_superuser_status/{id}', 'AdminController@toggleSuperUserStatus');
    Route::post('change_superuser_password/{id}', 'AdminController@updateSuperUserPassword');
    Route::post('super_user_create', 'AdminController@createSuperUser');
    Route::get('get_paginated_users', 'AdminController@getPgntdUsers');
    Route::post('update_user_status/{id}', 'AdminController@updateUserStatus');
    Route::get('admin_get_user/{id}', 'AdminController@adminGetUser');
    Route::post('admin_update_user/{id}', 'AdminController@updateUser');
    Route::post('admin_del_user/{id}', 'AdminController@adminDelUser');
    Route::post('update_user_password/{id}', 'AdminController@updateUserPassword');
    Route::post('admin_create_user', 'AdminController@adminCreateUser');
    Route::post('admin_search_for_users', 'AdminController@searchForUsers');
    Route::get('get_paginated_services', 'AdminController@getPgntdServices');
    Route::post('admin_del_service/{id}', 'AdminController@adminDelService');
    Route::get('get_service/{id}', 'AdminController@adminGetService');
    Route::post('toggle_review_approal/{id}', 'AdminController@toggleServiceIsApproved');
    Route::get('admin_get_service_reviews/{id}', 'AdminController@getServiceReviews');
    Route::post('admin_service_update/{id}', 'AdminController@adminUpdateService');
    Route::post('toggle_is_approved/{id}', 'AdminController@toggleServiceApprovalStatus');
    Route::post('toggle_is_premium/{id}', 'AdminController@toggleServicePremiumStatus');
    Route::post('toggle_is_featured/{id}', 'AdminController@toggleServiceIsFeatured');
    Route::post('update_service_image/{id}', 'AdminController@updateServiceImage');
    Route::post('delete_service_image/{id}', 'AdminController@deleteServiceImage');
    Route::post('admin_search_for_services', 'AdminController@adminSearchForService');
    Route::get('get_pgntd_portfolios', 'AdminController@getPgntdPortfolios');
    Route::post('admin_delete_portfolio/{id}', 'AdminController@adminDelPortfolio');
    Route::post('admin_search_for_portfolios', 'AdminController@adminSearchForPortfolio');
    Route::get('admin_get_portfolio/{id}', 'AdminController@getPortfolio');
    Route::get('admin_get_portfolio_files/{id}', 'AdminController@getPortfolioFiles');
    Route::post('admin_update_portfolio/{id}', 'AdminController@updatePortfolio');
    Route::post('toggle_portfolio_approved_status/{id}', 'AdminController@togglePortfolioIsApproved');
    Route::post('admin_delete_portfolio/{id}', 'AdminController@adminDeletePortfolio');
    Route::post('admin_del_portfolio_file/{id}', 'AdminController@adminDeletePortfolioFile');
    Route::get('get_pgntd_reviews', 'AdminController@adminGetPgntdReviews');
    Route::post('admin_delete_review/{id}', 'AdminController@delReview');
    Route::get('admin_get_review/{id}', 'AdminController@getReview');
    Route::post('toggle_review_is_approved/{id}', 'AdminController@toggleReviewIsApproved');
    Route::post('admin_search_for_reviews', 'AdminController@adminSearchForReviews');
    Route::get('get_pgntd_categories', 'AdminController@getPgntdCategories');
    Route::post('update_category/{id}', 'AdminController@updateCategory');
    Route::post('add_new_cat', 'AdminController@addNewCategory');
    Route::post('admin_search_for_cats', 'AdminController@searchForCategories');
    Route::post('admin_delete_cat/{id}', 'AdminController@delCat');
    Route::get('get_pgntd_locations', 'AdminController@getPgntdLocations');
    Route::post('update_location/{id}', 'AdminController@updateLocation');
    Route::post('admin_delete_loc/{id}', 'AdminController@delLocation');
    Route::post('admin_search_for_locs', 'AdminController@adminSearchForLocations');
    Route::post('toggle_loc_popularity/{id}', 'AdminController@toggleLocPopularity');
    Route::get('get_test_clients', 'AdminController@getTestClients');
    Route::post('clients/import', 'TestClientController@import');
    Route::post('locations/import', 'AdminController@importLocations');
    Route::post('categories/import', 'AdminController@importCategories');
    Route::post('update_admin_profile', 'AdminController@updateAdminProfile');
    Route::post('confirm_current_pswd', 'AdminController@confirmCurrentPassword');
    Route::post('update_admin_profile_password', 'AdminController@updateAdminPswd');
    Route::post('update_admin_profile_picture', 'AdminController@updateAdminProfilePic');
    Route::get('get_all_users', 'AdminController@getAllUsers');
    Route::get('get_all_services', 'AdminController@getAllServices');
    Route::get('get_all_portfolios', 'AdminController@getAllPortfolios');
    Route::get('get_all_reviews', 'AdminController@getAllReviews');
    Route::post('logout', 'AdminAuthController@logout');
    Route::get('get_last_weeks_services', 'AdminController@getWeeksDataForServices');
    Route::get('get_last_weeks_users', 'AdminController@getWeeksDataForUsers');
    Route::get('get_pgntd_pswd_reqs', 'AdminController@getPgntdPswdRequests');
    Route::post('del_password_req/{id}', 'AdminController@delPasswordReq');
    Route::post('admin_search_for_pswd_resets', 'AdminController@searchForpswdResetReqs');
    Route::get('get_new_users_alert', 'AdminController@adminNewUserAlert');
    Route::get('all_enquiries', 'AdminController@getPgntdEnquiries');
    Route::post('admin_del_enquiry/{id}', 'AdminController@delEnquiry');
    Route::get('get_unread_enquiry_count', 'AdminController@getUnreadEnquiries');
    Route::post('admin_search_for_enqs', 'AdminController@searchForEnqs');
    Route::get('get_enquiry/{id}', 'AdminController@getEnquiry');
    Route::post('enquiry_was_read/{id}', 'AdminController@enquiryWasRead');
    Route::get('get_popular_services', 'AdminController@getPopularServices');
    Route::get('get_latest_services', 'AdminController@getLatestServices');
    Route::get('all_testimonials', 'AdminController@getPgntdTestimonials');
    Route::post('admin_del_testimonial/{id}', 'AdminController@delTestimonial');
    Route::post('admin_search_for_tests', 'AdminController@searchForTestimonials');
    Route::get('get_testimonial/{id}', 'AdminController@getTestimonial');
    Route::post('change_testimonial_confirm_status/{id}', 'AdminController@toggleTestimonialConfirm');
    Route::post('change_testimonial_feature_status/{id}', 'AdminController@toggleTestimonialFeature');
    Route::post('update_testimonial/{id}', 'AdminController@updateTestimonial');
    Route::get('get_testimonial_count', 'AdminController@getTestimonialCount');
    Route::post('admin_create_bulk_locations', 'AdminController@createBulkLocations');
    Route::post('admin_create_bulk_categories', 'AdminController@createBulkCategories');

    // Route::get('get_admin_notifications', 'AdminController@getNotifs');
    // Route::post('clear_new_users_notification', 'AdminController@resetNewUsersNotif');
    // Route::post('clear_new_services_notification', 'AdminController@resetNewServicesNotif');
    // Route::post('clear_new_enquiries_notification', 'AdminController@resetNewEnquiryNotif');
    // Route::post('admin_delete_review/{id}', 'AdminController@deleteReview');
});

// model export routes for admin
Route::group(['middleware' => 'api'], function($router){
    Route::get('locations/export', 'ExportController@exportLocations');
    Route::get('categories/export', 'ExportController@exportCategories');
    Route::get('services/export', 'ExportController@exportServices');
    Route::get('service/{id}/export', 'ExportController@exportService');
    Route::get('users/export', 'ExportController@exportUsers');
});



