@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.childsubservices.management'))

@section('content')

@if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                   @lang('labels.backend.childsubservices.management')
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.child_sub_services.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->

       
        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example" class="table">
                        <thead>
                        <tr>
                           

                            <th> @lang('labels.backend.childsubservices.table.id')         </th>
                            <th> @lang('labels.backend.childsubservices.table.category')   </th>
                            <th> @lang('labels.backend.childsubservices.table.service')    </th>
                            <th> @lang('labels.backend.childsubservices.table.subservice') </th>   
                            <th> @lang('labels.backend.childsubservices.table.name') </th>                 
                            <th> @lang('labels.backend.childsubservices.table.price')      </th>    
                           <!--  <th>Icon</th> -->                         
                            <th> @lang('labels.backend.childsubservices.table.action')     </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($child_sub_services as $key => $child_sub_service)
                                <tr>
                                    <td>{{ $key + 1}}</td>
                                    <td>{{ $child_sub_service->category_name }}</td>
                                    <td>{{ $child_sub_service->services_name }}</td>
                                    <td>{{ $child_sub_service->sub_services_name }}</td>
                                    <td>{{ $child_sub_service->es_name }}</td>
                                    <td>{{ $child_sub_service->price }}</td>
                                    <!-- <td>
                                        <?php 
                                        $image="";
                                        $findinfolder="";
                                          if(isset($child_sub_service->image))
                                            { $image=$child_sub_service->image;
                                              $findinfolder=public_path().'/img/'.$child_sub_service->image;
                                             }
                                        if (file_exists($findinfolder) && !empty($image)) 
                                        {?>
                                        <img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}">
                                        <?php } else{ ?>
                                        <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}">
                                        <?php } ?>
                                        
                                    </td> -->
                                    <td class="que-btn">
                                        <form action="{{ route('admin.childsubservices.destroy',$child_sub_service->id) }}" method="POST">
   
                                            
                                            <a class="btn btn-primary" href="{{ route('admin.childsubservices.edit',$child_sub_service->id) }}"><i class="fas fa-edit"></i></a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                        </form>
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
<script type="text/javascript">
    $(document).ready(function() {
    $('#example').dataTable({
         "pageLength": 25

         // "bPaginate": true,
         // "bLengthChange": false,
         // "bFilter": true,
         // "bInfo": false,
         // "bAutoWidth": false
          });

       $('input').keyup( function() {
          table.draw();
    } );
});
</script>

<style>
.close:not(:disabled):not(.disabled) {
    cursor: pointer;
    margin-top: -25px;
}
</style>

@endsection
