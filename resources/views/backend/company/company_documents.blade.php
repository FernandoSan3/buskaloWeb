@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.questions.management'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    <!-- @lang('labels.backend.contractors.management') -->
                    Contractor Management
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.contractors.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Is Verified</th>
                            <th>Document</th><!-- 
                            <th>@lang('labels.general.actions')</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($all_documents as $key => $document)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td><?php  if($document->is_verified) {echo "Yes"; }else { echo "No" ;} ?></td>
                                <td>
                                    <?php 
                                        $image="";
                                        $findinfolder="";
                                          if(isset($document->doc_name))
                                            { $image=$document->doc_name;
                                              $findinfolder=public_path().'/img/'.$document->doc_name;
                                             }
                                        if (file_exists($findinfolder) && !empty($image)) 
                                        { if($document->doc_type == 'pdf') { ?>
                                            <a href="{{asset('img/')}}/{{$image}}"><img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/pdf_icon.png')}}"></a>
                                        <?php } else { ?>

                                            <a href="{{asset('img/')}}/{{$image}}"><img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}"></a>
                                        <?php    }

                                            } else{ ?>
                                        }
                                        <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}">
                                        <?php } ?>

                                </td>                            

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                   
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
