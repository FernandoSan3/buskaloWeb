@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.questions.management'))

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
                    @lang('labels.backend.questions.management')
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.questions.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->

       

        <div class="row mt-4">
            <div class="col">
                <div class="">
                    <table id="example" class="table" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                          <th style="display:none"> @lang('labels.backend.questions.table.id')</th>
                            <th> @lang('labels.backend.questions.table.id')</th>
                            <th> Category</th>
                            <th> Service</th>
                            <th> Sub Service</th>
                            <th> Child-subservice</th>
                            <th> Title</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $key => $question)
                                <tr>
                                    <td style="display:none">{{ $key+1 }}</td>
                                    <td>{{ $question->id }}</td>
                                    <td>{{ $question->category_name }}</td>
                                    <td>{{ $question->services_name }}</td>
                                    <td>{{ $question->sub_services_name }}</td>
                                    <td>{{ $question->child_subservice_name }}</td>
                                    <td>{{ $question->es_title }}</td>
                                    <td class="que-btn">
                                        <form action="{{ route('admin.questions.destroy',$question->id) }}" method="POST">
   
                                            <a class="btn btn-info" href="{{ route('admin.questions.show',$question->id) }}"><i class="fas fa-eye"></i></a>

                                            <a class="btn btn-primary" href="{{ route('admin.questions.edit',$question->id) }}"><i class="fas fa-edit"></i></a>
   
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

<style type="text/css">
    table.question-table td {
  word-break: break-word;
}
</style>
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
