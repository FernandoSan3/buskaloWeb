@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.package.management'))

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
                    {{-- @lang('labels.backend.package.management') --}}
                   Subscription Package          
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.package.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->
        <hr/>
        
        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example12" class="table table-striped table-bordered dt-responsive  question-table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.package.table.id') </th>
                            <th> @lang('labels.backend.package.table.en_name') </th>
                            <th> @lang('labels.backend.package.table.es_name') </th>
                            <th> @lang('labels.backend.package.table.price') </th>
                            <th> @lang('labels.backend.package.table.credit') </th>
                            <th> @lang('labels.backend.package.table.discount') </th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($package as $key => $package_sub)
                                <tr>
                                    <td>{{  $i }}</td>
                                    <td>{{ $package_sub->en_name }}</td>
                                    <td>{{ $package_sub->es_name }}</td>
                                    <td>{{ $package_sub->price }}</td>
                                    <td>{{ $package_sub->credit }}</td>
                                    <td>{{ $package_sub->discount }}</td>
                                    
                                    <td class="que-btn">
                                        <form action="{{ route('admin.package.destroy',$package_sub->id) }}" method="POST">
   
                                            
                                            <a class="btn btn-primary" href="{{ route('admin.package.edit',$package_sub->id) }}"><i class="fas fa-edit"></i></a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $i++;?>
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
<style>
.close:not(:disabled):not(.disabled) {
    cursor: pointer;
    margin-top: -25px;
}
</style>
@endsection
