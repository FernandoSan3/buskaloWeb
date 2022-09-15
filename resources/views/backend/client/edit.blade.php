@extends('backend.layouts.app')

@section('title', __('labels.backend.client.management') . ' | ' . __('labels.backend.client.create'))

@section('content')
{{ html()->form('POST', route('admin.client.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="user_id" value="{{$user_details->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.client.management')
                        <small class="text-muted">
                        @lang('labels.backend.client.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                        <div class="form-group row">
                          <label class="col-md-2 form-control-label"> Tipo: </label>

                            <div class="col-md-10">
                            <select name="user_group_id"  class="form-control">
                                <option value="2"selected>Cliente</option>
                                <option value="3">Profesional</option>
                                <option value="4">Empresa</option>
                            </select>
                            </div>

                        </div><!--form-group-->

                        <div class="form-group row">
                          <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.name')  </label>

                            <div class="col-md-10">
                                {{ html()->text('username')
                                    ->class('form-control')
                                    ->placeholder('username')
                                    ->attribute('maxlength', 191)
                                    ->value($user_details->username)
                                    ->required()
                                    ->autofocus() }}
                            </div><!--col-->
                        </div><!--form-group-->


                        <div class="form-group row">

                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.year_of_constitution') </label>

                            <div class="col-md-10">
                                {{ html()->number('year_of_constitution')
                                    ->class('form-control')
                                    ->placeholder('year of constitution')
                                    ->value($user_details->year_of_constitution)
                                    ->attribute('maxlength', 191)
                                    }}
                            </div><!--col-->
                        </div><!--form-group-->


                        <div class="form-group row">

                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.web_address') </label>

                            <div class="col-md-10">
                                {{ html()->text('website_address')
                                    ->class('form-control')
                                     ->value($user_details->website_address)
                                    ->placeholder('website address')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->


                        <div class="form-group row">

                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.mobileno') </label>

                            <div class="col-md-10">
                                {{ html()->number('mobile_number')
                                    ->class('form-control')
                                    ->value($user_details->mobile_number)
                                    ->placeholder('mobile number')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.landlinenumber') </label>
                            <div class="col-md-10">
                                {{ html()->number('landline_number')
                                ->value($user_details->landline_number)
                                    ->class('form-control')
                                    ->placeholder('landline number')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.officenumber')</label>
                            <div class="col-md-10">
                                {{ html()->text('office_number')
                                    ->value($user_details->office_number)
                                    ->class('form-control')
                                    ->placeholder('office number')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.address') </label>
                            <div class="col-md-10">
                                {{ html()->text('address')
                                    ->value($user_details->address)
                                    ->class('form-control')
                                    ->placeholder('address')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.officeaddress') </label>
                            <div class="col-md-10">
                                {{ html()->text('office_address')
                                    ->value($user_details->office_address)
                                    ->class('form-control')
                                    ->placeholder('office address')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.other address') </label>
                            <div class="col-md-10">
                                {{ html()->text('other_address')
                                    ->value($user_details->other_address)
                                    ->class('form-control')
                                    ->placeholder('other address')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">

                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.dob') </label>

                            <div class="col-md-10">
                                {{-- html()->text('dob')
                                    ->class('form-control')
                                    ->value($user_details->dob)
                                    ->placeholder('dob')
                                    ->attribute('maxlength', 191)
                                     --}}

                                    <input type="date" class="form-control" name="dob" value="<?php if(isset($user_details->dob) && !empty($user_details->dob)){echo $user_details->dob;}?>" id="datepicker">

                            </div><!--col-->
                        </div><!--form-group-->



                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.profilepicture') </label>
                            <div class="col-md-10">
                                <input type="file" name="avatar_location" accept="image/jpg, image/jpeg" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> @lang('labels.backend.access.users.tabs.content.overview.profiledescription') </label>
                            <div class="col-md-10">
                                <textarea class="form-control" placeholder="enter description"  name="profile_description">{{ $user_details->profile_description}}</textarea>
                            </div><!--col-->
                        </div><!--form-group-->


                </div><!--col-->
            </div><!--row-->

        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.client.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
    $('#add_more').click(function(){

        var added_input = $(".ans").length;
        var input = '<div id="row_'+added_input+'" ><input type="text" name="ans[en]['+added_input+']" value="" class="ans form-control" required="" placeholder="write in English"> <input type="text" name="ans[es]['+added_input+']" required="" placeholder="write in Spanish" value="" class="form-control"> <button type="button" onclick="removeRow(this);"> Remove row</button></div>';
        $('#append_rows').append(input);
    });

    function removeRow($this) {

        $($this).parent('div').remove();
    }
</script>

<script type="text/javascript">
    $('#inWholeCountryTrue').click(function(){
     $('#proviencesArea').hide();
     $('#citiesArea').hide();
    });


    $('#inWholeCountryFalse').click(function(){
     $('#proviencesArea').show();
     $('#citiesArea').show();
    });

</script>

{{--  <script type="text/javascript">
    $(function () {
        $('#datetimepicker5').datetimepicker({
            defaultDate: "11/1/2013",
            disabledDates: [
                moment("12/25/2013"),
                new Date(2013, 11 - 1, 21),
                "11/22/2013 00:53"
            ]
        });
    });
</script> --}}
@endsection
