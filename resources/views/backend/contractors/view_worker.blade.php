

@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.view'))



@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
               <h4 class="card-title mb-0">
                     Contractor Management
                    <small class="text-muted">
                    View Worker</small>
                </h4>
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4 mb-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <tr>
                            <th>User Name</th>
                            <td>{{ $worker_details->username}}</td>
                        </tr>

                        <tr>
                            <th>Email</th>
                            <td>{{ $worker_details->email}}</td>
                        </tr>

                        <tr>
                            <th>Mobile Number</th>
                            <td>{{ $worker_details->mobile_number}}</td>
                        </tr>

                        <tr>
                            <th>Address </th>
                            <td>{{ $worker_details->address}}</td>
                        </tr>

                        <tr>
                            <th>Profile</th>
                            <td>
                                    <?php 
                                        if(isset($worker_details->profile_pic) && !empty($worker_details->profile_pic)) 
                                        {   
                                            $image="";
                                            $findinfolder="";

                                            if(isset($worker_details->profile_pic))
                                            {  
                                                $image=$worker_details->profile_pic;
                                                $findinfolder=public_path().'/img/'.$worker_details->profile_pic;

                                                if (file_exists($findinfolder) && !empty($image)) 
                                                {
                                                       
                                        ?>
                                                    <a href="{{asset('img/')}}/{{$image}}"><img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}"></a>
                                    <?php 
                                                          
                                                } else {
                                    ?>
                                                    <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}">
                                    <?php
                                                }
                                            }
                                           
                                        }
                                    ?>

                                        
                                        
                                        
                                </td>
                        </tr>

                        <tr>
                            <th>Documents</th>

                            <td>
                                    <?php 
                                        if(isset($worker_details->document) && !empty($worker_details->document)) 
                                        {
                                            
                                            foreach ($worker_details->document as $key => $document) 
                                            {
                                                
                                            
                                                $image="";
                                                $findinfolder="";

                                                if(isset($document->doc_name))
                                                {  
                                                    $image=$document->doc_name;
                                                    $findinfolder=public_path().'/img/'.$document->doc_name;

                                                    if (file_exists($findinfolder) && !empty($image)) 
                                                    {
                                                        if($document->doc_type == 'pdf') 
                                                        {
                                        ?>
                                                           <a href="{{asset('img/')}}/{{$image}}"><img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/pdf_icon.png')}}"></a>

                                    <?php
                                                        } else {
                                    ?>
                                                            <a href="{{asset('img/')}}/{{$image}}"><img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}"></a>
                                    <?php 
                                                       }    
                                                    } else {
                                    ?>
                                                        <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}">
                                    <?php
                                                    }
                                                }
                                            }
                                        }
                                    ?>

                                        
                                        
                                        
                                </td>
                        </tr>

                        

                        
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->

    <div class="card-footer">
        <div class="row">
            <div class="col">
                
            </div><!--col-->
        </div><!--row-->
    </div><!--card-footer-->
</div><!--card-->
@endsection
