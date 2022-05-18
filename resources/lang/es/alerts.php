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
            'created' => 'Rol creado correctamente.',
            'deleted' => 'Rol eliminado correctamente.',
            'updated' => 'Rol actualizado correctamente.',
        ],

        'users' => [
            'cant_resend_confirmation' => 'La aplicación está actualmente configurada para aprobación manual de usuarios.',
            'confirmation_email' => 'Un nuevo mensaje de confirmación ha sido enviado a su correo.',
            'confirmed' => 'El usuario fue confirmado correctamente.',
            'created' => 'El usuario fue creado correctamente.',
            'deleted' => 'El usuario fue eliminado correctamente.',
            'deleted_permanently' => 'El usuario fue eliminado de forma permanente.',
            'restored' => 'El usuario fue restaurado correctamente.',
            'session_cleared' => 'La sesión del usuario se borró correctamente.',
            'social_deleted' => 'La cuenta social fue eliminada correctamente.',
            'unconfirmed' => 'El usuario fue desconfirmado correctamente',
            'updated' => 'El usuario fue actualizado correctamente.',
            'updated_password' => 'La contraseña fue actualizada correctamente.',
        ],
    ],

    'frontend' => [
        'contact' => [
            'sent' => 'Su información fue enviada correctamente. Responderemos tan pronto sea posible al e-mail que proporcionó.',
        ],

        'company' => [
                'job' =>[
                    'job_list_not_found' =>  'Lista de trabajos no encontrados.',
                    'invalid_user' =>  'Usuario inválida!',
                    'invalid_parameter' =>  'Parametro invalido',
                    'list_not_found.' =>  'Lista no encontrada.',
                    'opportunities_list_not_found' => 'Lista de oportunidades no encontrada.!',
                    'opportunities_not_found' => 'Oportunidades no encontradas.!',
                    'please_update_your_profile' => 'Actualice su perfil para los servicios que ofrece',
                    'opportunity_buy_successfully' => 'Compra de oportunidad con éxito.!',
                    'requested_opportunity' => 'La oportunidad solicitada ya está asignada a otros profesionales.',
                    'opportunity_already_accepted' => 'Oportunidad ya aceptada.!',
                    'taken_by_another' => 'Esta oportunidad ya la han aprovechado otros tres profesionales o la empresa.!',
                    'credits_not_sufficient' => 'Los créditos no son suficientes para comprar esta oportunidad, compre créditos',
                    'dont_have_this_opportunity' => "No tienes esta oportunidad Actualiza tu perfil de los servicios ofrecidos para obtener nuevas Oportunidades..",
                    'requested_opportunity_id_not_found' => 'No se encontró el ID de oportunidad solicitado.!'
                ],

                'profile' => [

                     'profile_picture_updated_successfully' =>  'La información se ha actualizado correctamente.',
                     'invalid_user' =>  'Usuario inválida!',
                     'invalid_parameter' =>  'Parametro invalido',
                     'mobile_number_already_exist' => 'El número de móvil ya existe',
                     'profile_updated_successfully' => 'Perfil actualizado con éxito.!',
                     'file_deleted_successfully' => 'Archivo eliminado con éxito.!',
                     'image_not_found' => 'Imagen no encontrada.!',
                     'you_have_already_added_review' => 'Ya ha añadido una reseña',
                     'review_submited_successfully' => 'Revisión enviada con éxito.!',
                     'something_went_wrong' => 'Algo salió mal',
                     'video_type_allowed_only' => 'Solo se permite el tipo de video (.webm,.mp4,.ogv).',
                     'video_not_uploaded' => 'Video no subido',
                     'images_type_allowed_only' => 'Tipo de imágenes solo permitido (.jpg,.png,.jpeg)',
                     'image_not_uploaded' => 'Imagen no cargada',
                     'record_file_not_uploaded' => 'archivo de registro no cargado.',
                     'file_type_not_match' => 'Tipo de archivo no coincide',
                     'certification_file_not_uploaded' => 'archivo de certificación no cargado',
                     'certification_image_file_not_uploaded' => 'archivo de imagen de certificación no cargado',
                     'certification_image_file_type_not_match' => 'imagen de certificación Tipo de archivo no coincidente',
                     'police_record_image_file_not_uploaded' => 'Archivo de imagen de registro policial no cargado',
                     'police_record_image_file_type_not_match' => 'Imagen de registro policial Tipo de archivo no coincide',
                     'gallery_image_file_not_uploaded' => 'archivo de imagen de la galería no cargado',
                     'gallery_image_file_type_not_match' => 'imagen de la galería Tipo de archivo no coincide',
                     'gallery_video_file_not_uploaded' => 'archivo de video de la galería no cargado',
                     'gallery_video_file_type_not_match' => 'video de la galería Tipo de archivo no coincide',
                ],
        ],

        'constractor' => [
               
            'profile' => [

                     'mobile_phone_number_already_exists' =>  'El número de teléfono móvil ya existe.',
                     'profile_updated_successfully' => 'Perfil actualizado con éxito.!',
                     'invalid_user' =>  'Usuario inválida!',
                     'banner_image_file_not_uploaded' => 'archivo de imagen de banner no cargado',
                     'banner_image_file_type_not_match' => 'imagen de banner Tipo de archivo no coincide',
                     'mobile_number_already_exist' => 'El número de móvil ya existe',
                     'police_record_image_file_not_uploaded' => 'Archivo de imagen de registro policial no cargado',
                     'police_record_image_file_type_not_match' => 'Imagen de registro policial Tipo de archivo no coincide',
                     'certification_image_file_not_uploaded' => 'archivo de imagen de certificación no cargado',
                     'certification_image_file_type_not_match' => 'imagen de certificación Tipo de archivo no coincidente',
                     'gallery_image_file_not_uploaded' => 'archivo de imagen de la galería no cargado',
                     'gallery_image_file_type_not_match' => 'imagen de la galería Tipo de archivo no coincide',
                     'gallery_video_file_not_uploaded' => 'archivo de video de la galería no cargado',
                     'gallery_video_file_type_not_match' => 'video de la galería Tipo de archivo no coincide',
                     'file_deleted_successfully' => 'Archivo eliminado con éxito.!',
                     'image_not_found' => 'Imagen no encontrada.!',

                     'profile_picture_updated_successfully' =>  'La información se ha actualizado correctamente.',
                     
                     'invalid_parameter' =>  'Parametro invalido',
                     'mobile_number_already_exist' => 'El número de móvil ya existe',
                     'images_type_allowed_only' => 'Tipo de imágenes solo permitido (.jpg,.png,.jpeg)',
                     'image_not_uploaded' => 'Imagen no cargada',
                     'video_type_allowed_only' => 'Solo se permite el tipo de video (.webm,.mp4,.ogv).',
                     'video_not_uploaded' => 'Video no subido',
                     'record_file_not_uploaded' => 'archivo de registro no cargado.',
                     'file_type_not_match' => 'Tipo de archivo no coincide',
                     'certification_file_not_uploaded' => 'archivo de certificación no cargado',
                   
                ],

            'job' =>[
                    'job_list_not_found' =>  'Lista de trabajos no encontrados.',
                    'invalid_user' =>  'Usuario inválida!',
                    'invalid_parameter' =>  'Parametro invalido',
                    'list_not_found.' =>  'Lista no encontrada.',
                    'opportunities_list_not_found' => 'Lista de oportunidades no encontrada.!',
                    'opportunities_not_found' => 'Oportunidades no encontradas.!',
                    'please_update_your_profile_offerd_services' => 'Actualice su perfil para los servicios que ofrece',
                    'the_requested_opportunity_is_already_assigned' => 'La oportunidad solicitada ya está asignada a otros profesionales',
                    'opportunity_already_accepted' => 'Oportunidad ya aceptada.!',
                    'successful_purchase_opportunity' => 'Has adquirido la información con éxito',
                    'taken_by_another' => 'Esta oportunidad ya ha sido aprovechada por otros tres profesionales o una empresa',
                    'credits_not_sufficient' => 'Los créditos no son suficientes para comprar esta oportunidad, compre créditos',
                    'dont_have_this_opportunity' => 'No tiene esta oportunidad Actualice su perfil de los servicios ofrecidos para obtener nuevas oportunidades',
                    'requested_opportunity_id_not_found' => 'No se encontró el ID de oportunidad solicitado.',
                    'requested_opportunity' => 'La oportunidad solicitada ya está asignada a otros profesionales',
                    'opportunity_already_accepted' => "Oportunidad ya aceptada. ¡Ahora no puedes Ignorar!",
                    'opportunity_already_ignored' => 'oportunidad ya ignorada',

                    'please_update_your_profile' => 'Actualice su perfil para los servicios que ofrece',
                    'opportunity_buy_successfully' => 'Compra de oportunidad con éxito.!',
                    'services_offered_to_obtain' => 'No tiene esta oportunidad Actualice su perfil de los servicios ofrecidos para obtener nuevas oportunidades',
            ],

            'workers' => [

                 'profile_created_successfully' =>  'Perfil creado con éxito.',
                 'invalid_user' =>  'Usuario inválida!',
            ],
        ],


        'users' => [
               
                'profile' => [
                   
                     'profile_updated_successfully' => 'Perfil actualizado con éxito.!',
                     'invalid_user' =>  'Usuario inválida!',
                     'profile_picture_updated_successfully' =>  'La información se ha actualizado correctamente.',
                    
                ],

                'send_request' => [
                   
                     'review_submited_successfully' => 'Revisión enviada con éxito.!',
                     'something_went_wrong' =>  'Algo salió mal.',
                     'hire_successfully' => 'contratar con éxito',
                    
                ],

        ],

         'auth' => [
               
                'register' => [
                   
                     'profile_created_successfully' => 'Su perfil se creó con éxito y se agregó una bonificación en su billetera.!',
                     'your_otp_is_wrong' =>  'Tu otp esta mal',
                    
                ],

                'reset_password' => [

                    'invalid_parameters' =>  'Parámetros inválidos',
                    'password_successful_update' => 'Actualización exitosa de la contraseña',
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
                   
                 'your_registration_step_has_already_been_completed' => 'Su paso de registro ya se completó.!',
                 'your_registration_step_is_now_complete' => 'Su paso de registro ahora está completo.!',
                 'image_not_uploaded' => 'Imagen no cargada',
                 'images_type_allowed_only' => 'Tipo de imágenes solo permitido (.jpg,.png,.jpeg).',
                 'video_not_uploaded' => 'Video no subido',
                 'video_type_allowed_only' => 'Solo se permite el tipo de video (.webm,.mp4,.ogv).',
                 'certification_file_not_uploaded' => 'archivo de certificación no cargado',
                 'file_type_not_match' => 'Tipo de archivo no coincide',
                 'profile_completed_succefully' => 'La solicitud ha sido enviada, la revisaremos y aprobaremos o rechazaremos.',
                 'image_not_uploaded' => 'Imagen no cargada',
                 'images_type_allowed_only' => 'Tipo de imágenes solo permitido (.jpg,.png,.jpeg).',
                 'video_not_uploaded' => 'Video no subido',
                 'video_type_allowed_only' => 'Solo se permite el tipo de video (.webm,.mp4,.ogv).',
                 'record_file_not_uploaded' => 'archivo de registro no cargado',
                 'file_type_not_match' => 'Tipo de archivo no coincide',
                 'certification_file_not_uploaded' => 'archivo de certificación no cargado',
                 'found_category' => 'Categoría encontrada',
                 'not_found_category' => 'Categoría no encontrada',
                 'found_subservice' => 'encontrado Subservicio',
                 'not_found_subservice' => 'Subservicio no encontrado',
                 'your_request_send_successfully' => 'Su solicitud se envió con éxito',
                 'something_went_wrong' => 'Algo salió mal',
                 'data_not_found' => 'Datos no encontrados.',
            ],

        'newsletter' => [
                   
             'you_have_already_subscribed' => 'ya te has suscrito.!',
             'thanks_for_subscribing' => 'Gracias por suscribirte',
            
        ],
        ],
    ],
];
