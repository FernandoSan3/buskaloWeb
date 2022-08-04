@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.company.management'))

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
                    <!-- @lang('labels.backend.contractors.management') -->
                    @lang('labels.general.actions1.ratings_reviews_management') 
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
               {{--  @include('backend.contractors.includes.header-buttons') --}}

                <div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">

                    <a href="{{ route('admin.contractors.index') }}" class="btn btn-success ml-1" data-toggle="tooltip" title="@lang('labels.general.toolbar_btn_groups')"><i class="fas fa-list"></i></a>

                </div>
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>@lang('labels.backend.review.table.id')</th>
                            <th>@lang('labels.backend.review.table.User Name')</th>
                            <th>@lang('labels.backend.review.table.Rating')</th>
                            <th>@lang('labels.backend.review.table.Price')</th>
                            <th>@lang('labels.backend.review.table.Puntuality')</th>
                            <th>@lang('labels.backend.review.table.Service')</th>
                            <th>@lang('labels.backend.review.table.Quality')</th>
                            <th>@lang('labels.backend.review.table.Amiability')</th>
                            <th>@lang('labels.backend.review.table.Review')</th>
                            <th>@lang('labels.backend.review.table.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($ratings_reviews as $key => $user)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>
                                    @foreach ($user->total_service_requests as $userq)
                                        {{ $userq->username }}
                                    @endforeach
                                </td>
                                <td>{{ $user->rating }}</td>
                                <td>{{ $user->price }}</td>
                                <td>{{ $user->puntuality }}</td>
                                <td>{{ $user->service }}</td>
                                <td>{{ $user->quality }}</td>
                                <td>{{ $user->amiability }}</td>
                                <td>{{ $user->review }}</td>
                                <td>
                                    <a href="{{ route('admin.company.edit_ratings_reviews',$user->id) }}" class="btn btn-info"><i class="fas fa-edit"></i></a>
                                    <a href="{{ route('admin.company.destroy_ratings_reviews',$user->id) }}" class="btn btn-danger"><i class="fas fa-trash-alt"></i></a>
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
