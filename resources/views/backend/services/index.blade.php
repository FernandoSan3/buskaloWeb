@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.services.management'))

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
                    @lang('labels.backend.services.management')                    
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.services.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered dt-responsive  question-table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.services.table.id')       </th>
                            <th> @lang('labels.backend.services.table.category') </th>
                            <th> @lang('labels.backend.services.table.service')  </th>
                           <!--  <th> Price  </th> -->
                            <th> @lang('labels.backend.services.table.icon')     </th>
                            <th> @lang('labels.backend.services.table.action')   </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $key => $service)
                                <tr>
                                    <td>{{ $key + 1}}</td>
                                    <td>{{ $service->category_name }}</td>
                                    <td>{{ $service->es_name }}</td>
                                    <!-- <td>{{ $service->price }}</td> -->
                                    <td>
                                        <?php 
                                        $image="";
                                        $findinfolder="";
                                          if(isset($service->image))
                                            { $image=$service->image;
                                              $findinfolder=public_path().'/img/'.$service->image;
                                             }
                                        if (file_exists($findinfolder) && !empty($image)) 
                                        {?>
                                        <img class="" style="height: 50px;width: 70px;" src="{{asset('img/')}}/{{$image}}">
                                        <?php } else{ ?>
                                        <img class="" style="height: 50px;width: 70px;" src="{{asset('img/frontend/no-image-available.jpg')}}">
                                        <?php } ?>
                                        
                                    </td>
                                    <td class="que-btn">
                                        <form action="{{ route('admin.services.destroy',$service->id) }}" method="POST">
   
                                            <a class="btn btn-primary" href="{{ route('admin.services.edit',$service->id) }}"><i class="fas fa-edit"></i></a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
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
