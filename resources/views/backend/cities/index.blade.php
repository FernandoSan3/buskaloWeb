@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.cities.management'))

@section('content')
<div class="card">
    <div class="card-body">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    @lang('labels.backend.cities.management')                    
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.cities.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example12" class="table table-striped table-bordered dt-responsive">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.cities.table.id')       </th>
                            <th> @lang('labels.backend.cities.table.province') </th>
                            <th> @lang('labels.backend.cities.table.city')     </th>
                            <th> @lang('labels.backend.cities.table.action')   </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($cities as $key => $city)
                                <tr>
                                    <td>{{ $cities->firstItem() + $key }}</td>
                                    <td>{{ $city->provinces_name }}</td>
                                    <td>{{ $city->name }}</td>
                                    <td class="que-btn">


                                        <form action="{{ route('admin.cities.destroy',$city->id) }}" method="POST">
   
                                            
                                            <a class="btn btn-primary" href="{{route('admin.cities.polygons',$city->id)}}"><i class="fas fa-draw-polygon"></i></a> 

                                            <a class="btn btn-primary" href="{{ route('admin.cities.edit',$city->id) }}"><i class="fas fa-edit"></i></a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $cities->render() !!}
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
<style>
.close:not(:disabled):not(.disabled) {
    cursor: pointer;
    margin-top: -25px;
}
</style>
@endsection
