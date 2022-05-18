<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Alert Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain alert messages for various scenarios
    | during CRUD operations. You are free to modify these language lines
    | according to your application's requirements.
    |
    */

    'backend' => [
        'roles' => [
            'created' => 'The role was successfully created.',
            'deleted' => 'The role was successfully deleted.',
            'updated' => 'The role was successfully updated.',
        ],

        'users' => [
            'cant_resend_confirmation' => 'The application is currently set to manually approve users.',
            'confirmation_email' => 'A new confirmation e-mail has been sent to the address on file.',
            'confirmed' => 'The user was successfully confirmed.',
            'created' => 'The user was successfully created.',
            'deleted' => 'The user was successfully deleted.',
            'deleted_permanently' => 'The user was deleted permanently.',
            'restored' => 'The user was successfully restored.',
            'session_cleared' => "The user's session was successfully cleared.",
            'social_deleted' => 'Social Account Successfully Removed',
            'unconfirmed' => 'The user was successfully un-confirmed',
            'updated' => 'The user was successfully updated.',
            'updated_password' => "The user's password was successfully updated.",
        ],
    ],

    'frontend' => [
        'contact' => [
            'sent' => 'Your information was successfully sent. We will respond back to the e-mail provided as soon as we can.',
        ],

        'company' => [
                'job' =>[
                    'job_list_not_found' =>  'job List Not Found.',
                    'invalid_user' =>  'Invalid user!',
                    'invalid_parameter' =>  'Invalid parameter',
                    'list_not_found.' =>  'List not found.',
                    'opportunities_list_not_found' => 'Opportunities List Not Found.!',
                    'opportunities_not_found' => 'Opportunities Not Found.!',
                    'please_update_your_profile' => 'Please update your profile for your offerd services',
                    'opportunity_buy_successfully' => 'Opportunity Buy Successfully.!',
                    'requested_opportunity' => 'Requested opportunity is already Assigned another professionals.',
                    'opportunity_already_accepted' => 'Opportunity Already Accepted.!',
                    'taken_by_another' => 'This Opportunity Already Taken By Another Three professionals OR Company.!',
                    'credits_not_sufficient' => 'Credits not sufficient to buy this opportunity , please purchase credits',
                    'dont_have_this_opportunity' => "You don't have this opportunity.Please update your profile offerd services to get new Opportunities.",
                    'requested_opportunity_id_not_found' => 'Requested opportunity id not found.!'
                ],

                'profile' => [

                     'profile_picture_updated_successfully' =>  'Profile Picture Updated Successfully.',
                     'invalid_user' =>  'Invalid user!',
                     'invalid_parameter' =>  'Invalid parameter',
                     'mobile_number_already_exist' => 'Mobile Number Already Exist',
                     'profile_updated_successfully' => 'Profile Updated Successfully.!',
                     'file_deleted_successfully' => 'File deleted successfully.!',
                     'image_not_found' => 'Image not found.!',
                     'you_have_already_added_review' => 'You have Already Added Review',
                     'review_submited_successfully' => 'Review Submited Successfully.!',
                     'something_went_wrong' => 'Something Went Wrong',
                     'video_type_allowed_only' => 'Video Type Allowed Only (.webm,.mp4,.ogv).',
                     'video_not_uploaded' => 'Video not uploaded',
                     'images_type_allowed_only' => 'Images Type Allowed Only (.jpg,.png,.jpeg)',
                     'image_not_uploaded' => 'Image not uploaded',
                     'record_file_not_uploaded' => 'record file not uploaded.',
                     'file_type_not_match' => 'File Type Not Match',
                     'certification_file_not_uploaded' => 'certification file not uploaded',
                     'certification_image_file_not_uploaded' => 'certification image file not uploaded',
                     'certification_image_file_type_not_match' => 'certification image File Type Not Match',
                     'police_record_image_file_not_uploaded' => 'Police Record image file not uploaded',
                     'police_record_image_file_type_not_match' => 'Police Record image File Type Not Match',
                     'gallery_image_file_not_uploaded' => 'gallery image file not uploaded',
                     'gallery_image_file_type_not_match' => 'gallery image File Type Not Match',
                     'gallery_video_file_not_uploaded' => 'gallery video file not uploaded',
                     'gallery_video_file_type_not_match' => 'gallery video File Type Not Match',

                ],

                'services' => [

                     'services_not_found' =>  'Services not found.',
                     'invalid_user' =>  'Invalid user!',
                ],
        ],

        'constractor' => [
               
                'profile' => [

                     'mobile_phone_number_already_exists' =>  'Mobile phone number already exists.',
                     'profile_updated_successfully' => 'Profile Updated Successfully.!',
                     'invalid_user' =>  'Invalid user!',
                     'banner_image_file_not_uploaded' => 'banner image file not uploaded',
                     'banner_image_file_type_not_match' => 'banner image File Type Not Match',
                     'mobile_number_already_exist' => 'Mobile Number Already Exist',
                     'police_record_image_file_not_uploaded' => 'Police Record image file not uploaded',
                     'police_record_image_file_type_not_match' => 'Police Record image File Type Not Match',
                     'certification_image_file_not_uploaded' => 'certification image file not uploaded',
                     'certification_image_file_type_not_match' => 'certification image File Type Not Match',
                     'gallery_image_file_not_uploaded' => 'gallery image file not uploaded',
                     'gallery_image_file_type_not_match' => 'gallery image File Type Not Match',
                     'gallery_video_file_not_uploaded' => 'gallery video file not uploaded',
                     'gallery_video_file_type_not_match' => 'gallery video File Type Not Match',
                     'file_deleted_successfully' => 'File deleted successfully.!',
                     'image_not_found' => 'Image not found.!',

                     'profile_picture_updated_successfully' =>  'Profile Picture Updated Successfully.',
                     
                     'invalid_parameter' =>  'Invalid parameter',
                     'mobile_number_already_exist' => 'Mobile Number Already Exist',
                     'images_type_allowed_only' => 'Images Type Allowed Only (.jpg,.png,.jpeg)',
                     'image_not_uploaded' => 'Image not uploaded',
                     'video_type_allowed_only' => 'Video Type Allowed Only (.webm,.mp4,.ogv).',
                     'video_not_uploaded' => 'Video not uploaded',
                     'record_file_not_uploaded' => 'record file not uploaded.',
                     'file_type_not_match' => 'File Type Not Match',
                     'certification_file_not_uploaded' => 'certification file not uploaded',
                   
                ],

            'job' =>[
                'job_list_not_found' =>  'job List Not Found.',
                'invalid_user' =>  'Invalid user!',
                'invalid_parameter' =>  'Invalid parameter',
                'list_not_found.' =>  'List not found.',
                'opportunities_list_not_found' => 'Opportunities List Not Found.!',
                'opportunities_not_found' => 'Opportunities Not Found.!',
                'please_update_your_profile_offerd_services' => 'Please update your profile for your offerd services',
                'the_requested_opportunity_is_already_assigned' => 'The requested opportunity is already assigned to other professionals',
                'opportunity_already_accepted' => 'Opportunity Already Accepted.!',
                'successful_purchase_opportunity' => 'Successful purchase opportunity',
                'taken_by_another' => 'This opportunity has already been taken advantage of by three other professionals or a company',
                'credits_not_sufficient' => 'Credits not sufficient to buy this opportunity, please purchase credits',
                'dont_have_this_opportunity' => 'You do not have this opportunity Update your profile of the services offered to obtain new opportunities',
                'requested_opportunity_id_not_found' => 'The requested opportunity ID was not found',
                'requested_opportunity' => 'The requested opportunity is already assigned to other professionals',
                'opportunity_already_accepted' => "Opportunity already accepted. ¡Now you can't Ignore!",
                'opportunity_already_ignored' => 'Opportunity already ignored',

                'please_update_your_profile' => 'Please update your profile for your offerd services',
                'opportunity_buy_successfully' => 'Opportunity Buy Successfully.!',
                'services_offered_to_obtain' => 'You do not have this opportunity Update your profile of the services offered to obtain new opportunities',
            ],

            'services' => [

                 'services_not_found' =>  'Services not found.',
                 'invalid_user' =>  'Invalid user!',
            ],

            'workers' => [

                 'profile_created_successfully' =>  'Profile Created Successfully.',
                 'invalid_user' =>  'Invalid user!',
            ],

        ],

        'users' => [
               
                'profile' => [
                   
                     'profile_updated_successfully' => 'Profile Updated Successfully.!',
                     'invalid_user' =>  'Invalid user!',
                     'profile_picture_updated_successfully' =>  'Profile Picture Updated Successfully.',
                    
                ],

                'send_request' => [
                   
                     'review_submited_successfully' => 'Review Submited Successfully.!',
                     'something_went_wrong' =>  'Something Went Wrong.',
                     'hire_successfully' => 'hire Successfully',
                     
                ],
        ],

        'auth' => [
               
                'register' => [
                   
                     'profile_created_successfully' => 'Your profile created succesfully and bonus added in your wallet.!',
                     'your_otp_is_wrong' =>  'Your otp is wrong',
                    
                ],

                'reset_password' => [

                    'invalid_parameters' =>  'Invalid Parameters',
                    'password_successful_update' => 'Password successful Update',
                ],

        ],

        'home' => [
               
                'forgot_password_reset' => [
                   
                     'please_request_for_another_token' => 'Please Request for another token, this token is already used.',
                     'your_password_updated_successfully' =>  'Your Password Updated successfully',
                     'password_and_confirm_password_does_not_similar' => 'Password and confirm password does not similar',
                     'email_id_not_exist_in_our_database' => 'Email-id not exist in our database',
                     'invalid_token' => 'Invalid Token',
                     'invalid_parameter' => 'Invalid parameter',
                    
                ],

                'home' => [
                   
                     'your_registration_step_has_already_been_completed' => 'Your registration step has already been completed.!',
                     'your_registration_step_is_now_complete' => 'Your registration step is now complete.!',
                     'image_not_uploaded' => 'Image not uploaded',
                     'images_type_allowed_only' => 'Images Type Allowed Only (.jpg,.png,.jpeg).',
                     'video_not_uploaded' => 'Video not uploaded',
                     'video_type_allowed_only' => 'Video Type Allowed Only (.webm,.mp4,.ogv).',
                     'certification_file_not_uploaded' => 'certification file not uploaded',
                     'file_type_not_match' => 'File Type Not Match',
                     //'profile_completed_succefully' => "The Application has been sent, we'll check and approve or decline.",
                     'profile_completed_succefully'=>'Revisa tu correo para validar tu cuenta',
                     'image_not_uploaded' => 'Image not uploaded',
                     'images_type_allowed_only' => 'Images Type Allowed Only (.jpg,.png,.jpeg).',
                     'video_not_uploaded' => 'Video not uploaded',
                     'video_type_allowed_only' => 'Video Type Allowed Only (.webm,.mp4,.ogv).',
                     'record_file_not_uploaded' => 'record file not uploaded',
                     'file_type_not_match' => 'File Type Not Match',
                     'certification_file_not_uploaded' => 'certification file not uploaded',
                     'found_category' => 'found Category',
                     'not_found_category' => 'not FoundCategory',
                     'found_subservice' => 'found Subservice',
                     'not_found_subservice' => 'not Found Subservice',
                     'your_request_send_successfully' => 'Your Request send Successfully',
                     'something_went_wrong' => 'Something went wrong',
                     'data_not_found' => 'Data Not found.',
                ],

                'newsletter' => [
                   
                     'you_have_already_subscribed' => 'you have already subscribed.!',
                     'thanks_for_subscribing' => 'Thanks for subscribing',
                    
                ],

        ],
    ],
];
