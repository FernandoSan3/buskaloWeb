@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.banner.management'))

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
                    @lang('labels.backend.banner.management')
                    <!-- Categorías Principales                     -->
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.banner.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered dt-responsive  question-table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.banner.table.id')      </th>
                            <th> @lang('labels.backend.banner.table.category') </th>
                            <th> @lang('labels.backend.banner.table.image')   </th>
                            <th> @lang('labels.backend.banner.table.action')  </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($banners as $key => $banner)
                                <tr>
                                    <td>{{$key+1 }}</td>
                                    {{-- <td>{{ $key+1 }}</td> --}}
                                    <td>{{ $banner->es_name }}</td>
                                    <td>
                                        <?php 
                                        $image="";
                                        $findinfolder="";
                                          if(isset($banner->banner_name))
                                            { $image=$banner->banner_name;
                                              $findinfolder=public_path().'/bannerimage/'.$banner->banner_name;
                                             }
                                        if (file_exists($findinfolder) && !empty($image)) 
                                        {?>

                                        <a href="{{asset('bannerimage/')}}/{{$image}}" target="_blank"><img style="height: 50px;width: 70px;" src="{{asset('bannerimage/')}}/{{$image}}"></a>

                                        <!-- <img class="" style="height: 60px;width: 80px;" src="{{asset('bannerimage/')}}/{{$image}}"> -->
                                        <?php } else{ ?>
                                            <a href="{{asset('bannerimage/frontend/no-image-available.jpg')}}" target="_blank"><img style="height: 50px;width: 70px;" src="{{asset('bannerimage/frontend/no-image-available.jpg')}}"></a>

                                        <!-- <img class="" style="height: 60px;width: 80px;" src="{{asset('bannerimage/frontend/no-image-available.jpg')}}"> -->
                                        <?php } ?>
                                        
                                    </td>

                                    <td class="que-btn">
                                        <form action="{{ route('admin.banner.destroy',$banner->id) }}" method="DELETE">
   
                                            <a class="btn btn-primary" href="{{ route('admin.banner.edit',$banner->id) }}"><i class="fas fa-edit"></i></a>
   
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
