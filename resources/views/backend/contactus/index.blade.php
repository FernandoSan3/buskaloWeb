@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.contactus.management'))

@section('content')
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                     @lang('labels.backend.contactus.management')
                </h4>
            </div><!--col-->

           {{-- <div class="col-sm-7 pull-right">
                @include('backend.contactus.includes.header-buttons')
            </div>--}}
        </div><!--row-->

        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>

                           {{--<th><input type="checkbox" id="chk" onchange='checkAll(this)' name="chk" value="Bike">
                            <label for="chk"> Select All</label></th>--}}
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Contact Number</th>
                            <th>View</th>
                          </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $key => $contact)
                            <tr>
                                   {{--  <td>
                         <input class="form-control" value='' type='checkbox' name='check[]' style="width: auto;">
                               </td>--}}
                                <td>{{ $key+1 }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ $contact->contact_number }}</td>
                                <td class="que-btn">
                                    <a class="btn btn-info" href="{{ route('admin.description.show',$contact->id) }}"><i class="fas fa-eye"></i></a>
                                 </td>
                                {{-- <td>

                                    <a class="btn btn-info" href="{{ route('admin.contactus.sendMail') }}">Send Mail</a>
                             </td>
                             <td>
                              <form action="{{ route('admin.contactus.sendMail') }}" method="POST">
                               <a type="submit"class="btn btn-info">Send Mail</a>
                             </form>
                         </td><td>--}}
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                 {{--   {!! $contacts->render() !!} --}}
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
