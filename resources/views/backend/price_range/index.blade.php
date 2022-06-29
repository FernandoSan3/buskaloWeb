@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.price_range.management'))

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
                    @lang('labels.backend.price_range.management')                    
                </h4>
            </div><!--col-->

            <div class="col-sm-7 pull-right">
                @include('backend.price_range.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->


        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table" id="example">
                        <thead>
                        <tr>
                            <th> @lang('labels.backend.price_range.table.id')          </th>
                            <th> @lang('labels.backend.price_range.table.start_price') </th>
                            <th> @lang('labels.backend.price_range.table.end_price')   </th>
                            <th> @lang('labels.backend.price_range.table.porcentage')  </th>
                            <th> @lang('labels.backend.price_range.table.action')      </th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach ($price_ranges as $key => $price)
                                <tr>
                                    <td>  {{ $price_ranges->firstItem() + $key }}</td>
                                    <td>{{ $price->start_price }}</td>
                                    <td>{{ $price->end_price }}</td>
                                    <td>{{ $price->percentage }}</td>
                                    
                                    <td class="que-btn">
                                        <form action="{{ route('admin.price_range.destroy',$price->id) }}" method="POST">
   
                                            
                                            <a class="btn btn-primary" href="{{ route('admin.price_range.edit',$price->id) }}"><i class="fas fa-edit"></i></a>
   
                                            @csrf
                                            @method('DELETE')
                              
                                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $price_ranges->render() }}
                    
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
