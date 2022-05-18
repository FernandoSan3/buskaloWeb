@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.newsletter.management'))

@section('content')
{{ html()->form('POST', route('admin.newsletter.sendmailtoall'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                     @lang('labels.backend.newsletter.management')
                </h4>
            </div><!--col-->
            <div class="col text-right">
                {{ form_submit(__('labels.backend.newsletter.Send Mail To All')) }}
            </div>
            {{--<div class="col-sm-7 pull-right">
                @include('backend.newsletter.includes.header-buttons')
            </div>--}}
        </div><!--row-->
        
        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="example">
                        <thead>
                          <tr>
                           <th><input type="checkbox" id="chk" onchange='checkAll(this)' name="chk[]">
                            <label for="chk"> Select All</label></th>
                            <th>Id</th>
                            <th>Email</th>
                            <th>user Type</th>
                            <th>Send Mail</th>
                          </tr>
                        </thead>
                        <tbody>
                        @if(isset($newsletter_detail) && !empty($newsletter_detail))
                        @foreach($newsletter_detail as $key => $newsletter)
                            <tr>
                                <td>
                                   <input class="form-control" type='checkbox'  value="<?php echo $newsletter->email;?>"  name="check[]" style="width: auto;">
                                </td>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $newsletter->email }}</td>
                                <td>{{ $newsletter->user_type }}</td>
                                <td>
                                <a class="btn btn-info" href="{{ route('admin.newsletter.show',$newsletter->id) }}">Send Mail</a>
                                </td>
                            </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>
                    {{-- {!! $contacts->render() !!} --}}
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
<script type="text/javascript" language="javascript">// <![CDATA[
    function checkAll(ele) {
        var checkboxes = document.getElementsByTagName('input');
        if (ele.checked) {
             for (var i = 0; i < checkboxes.length; i++) {
                 if (checkboxes[i].type == 'checkbox') {
                     checkboxes[i].checked = true;
                 }
             }
        } else {
             for (var i = 0; i < checkboxes.length; i++) {
                 console.log(i)
                 if (checkboxes[i].type == 'checkbox') {
                     checkboxes[i].checked = false;
                 }
             }
        }
    }
</script>
@endsection
