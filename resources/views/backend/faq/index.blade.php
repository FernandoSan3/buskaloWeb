@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('Faq Management'))

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
                    {{-- @lang('labels.backend.category.management') --}}
                    Faq Management                 
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.faq.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered dt-responsive  question-table">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.services.table.id') </th>
                            <th> @lang('Question Type') </th>
                            <th> @lang('Question') </th>
                            <th> @lang('Answer') </th>
                            <th>Status</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($faqlist as $key => $faq)
                                <tr>
                                    <td>{{$key+1 }}</td>
                                    <td>@if($faq->question_type==1)
                                        User
                                        @else
                                        Professional
                                        @endif

                                    </td>
                                    <td>{{ $faq->question }}</td>
                                    <td>{{ $faq->answer }}</td>
                                     <td>
                                        @if($faq->status==1)
                                         Active
                                         @else
                                         Inactive
                                         @endif
                                     </td>
                                    <td class="que-btn">
                                        <form action="{{ route('admin.faq.destroy',$faq->id) }}" method="POST">
   
                                            <a class="btn btn-primary" href="{{ route('admin.faq.edit',$faq->id) }}"><i class="fas fa-edit"></i></a>
   
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
