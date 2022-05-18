<?php

if (! function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (! function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on authentication/authorization status.
     *
     * @return string
     */
    function home_route()
    {   
        
        if (auth()->check()) {
            if (auth()->user()->can('view backend')) {
                return 'admin.dashboard';
            }

            return 'frontend.user.dashboard';
        }

        return 'frontend.index';
    }
}

if (! function_exists('service_request_count')) {
    
    function service_request_count()
    {   

        $requestCount = array();
        
        $requestCount['allRequestCount'] = DB::table('service_request')
        ->join('category AS C1','service_request.category_id','C1.id')
        ->join('services as S1','service_request.service_id','S1.id')
        ->join('sub_services as S2','service_request.sub_service_id','S2.id')
        ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')
        ->where('service_request.user_id',auth()->user()->id)
        ->whereRaw("(service_request.deleted_at IS null )")
        ->count();
            
        $requestCount['pendingRequestCount'] = DB::table('service_request')
        ->join('category AS C1','service_request.category_id','C1.id')
        ->join('services as S1','service_request.service_id','S1.id')
        ->join('sub_services as S2','service_request.sub_service_id','S2.id')
        ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')          
        ->where('service_request.status','0')
        ->where('service_request.user_id',auth()->user()->id)
        ->whereRaw("(service_request.deleted_at IS null )")
        ->count();

        $requestCount['acceptedRequestCount'] = DB::table('service_request')
        ->join('category AS C1','service_request.category_id','C1.id')
        ->join('services as S1','service_request.service_id','S1.id')
        ->join('sub_services as S2','service_request.sub_service_id','S2.id')
        ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')          
        ->where('service_request.status','1')
        ->where('service_request.user_id',auth()->user()->id)
        ->whereRaw("(service_request.deleted_at IS null )")
        ->count();

         $requestCount['inprogressRequestCount'] = DB::table('service_request')
        ->join('category AS C1','service_request.category_id','C1.id')
        ->join('services as S1','service_request.service_id','S1.id')
        ->join('sub_services as S2','service_request.sub_service_id','S2.id')
        ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')          
        ->where('service_request.status','2')
        ->where('service_request.user_id',auth()->user()->id)
        ->whereRaw("(service_request.deleted_at IS null )")
        ->count();

         $requestCount['rejectedRequestCount'] = DB::table('service_request')
        ->join('category AS C1','service_request.category_id','C1.id')
        ->join('services as S1','service_request.service_id','S1.id')
        ->join('sub_services as S2','service_request.sub_service_id','S2.id')
        ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')          
        ->where('service_request.status','3')
        ->where('service_request.user_id',auth()->user()->id)
        ->whereRaw("(service_request.deleted_at IS null )")
        ->count();


        $requestCount['completedRequestCount'] = DB::table('service_request')
        ->join('category AS C1','service_request.category_id','C1.id')
        ->join('services as S1','service_request.service_id','S1.id')
        ->join('sub_services as S2','service_request.sub_service_id','S2.id')
        ->leftjoin('child_sub_services as S3','service_request.child_sub_service_id','S3.id')          
        ->where('service_request.status','4')
        ->where('service_request.user_id',auth()->user()->id)
        ->whereRaw("(service_request.deleted_at IS null )")
        ->count(); 

        return $requestCount;
        
    }
}


