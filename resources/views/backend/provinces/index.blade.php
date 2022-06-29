@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.provinces.management'))

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
                    @lang('labels.backend.provinces.management')                    
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.provinces.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.provinces.table.id')      </th>
                            <th> @lang('labels.backend.provinces.table.name')    </th>
                            <th> @lang('labels.backend.provinces.table.action')  </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($provinces as $key => $province)
                                <tr>
                                    <td>{{ $provinces->firstItem() + $key }}</td>
                                    <td>{{ $province->name }}</td>
                                    <td class="que-btn">
                                        <form action="{{ route('admin.provinces.destroy',$province->id) }}" method="POST">
   
                                            
                                            <a class="btn btn-primary" href="{{ route('admin.provinces.edit',$province->id) }}"><i class="fas fa-edit"></i></a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {!! $provinces->render() !!}
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
