@extends('backend.layouts.app')

@section('title', __('labels.backend.company.management') . ' | ' . __('labels.backend.company.create'))

@section('content')
{{ html()->form('POST', route('admin.company.update'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="user_id" value="{{$user_details->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.company.management')
                        <small class="text-muted">
                        @lang('labels.backend.company.update')</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">


                        <div class="form-group row">
                          <label class="col-md-2 form-control-label">Username</label>

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

                            <label class="col-md-2 form-control-label"> Year Of Constitution </label>

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

                            <label class="col-md-2 form-control-label"> Legal Representative </label>

                            <div class="col-md-10">
                                {{ html()->text('legal_representative')
                                    ->class('form-control')
                                     ->value($user_details->legal_representative)
                                    ->placeholder('legal representative')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">

                            <label class="col-md-2 form-control-label"> Web Address</label>

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

                            <label class="col-md-2 form-control-label"> Mobile Number</label>

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
                            <label class="col-md-2 form-control-label"> Landline Number</label>
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
                            <label class="col-md-2 form-control-label"> Office Number</label>
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
                            <label class="col-md-2 form-control-label"> Address</label>
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
                            <label class="col-md-2 form-control-label">Office Address</label>
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
                            <label class="col-md-2 form-control-label">Other Address</label>
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

                            <label class="col-md-2 form-control-label"> Dob</label>

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
                            <label class="col-md-2 form-control-label"> Profile Picture</label>
                            <div class="col-md-10">
                                <input type="file" name="avatar_location" accept="image/jpg, image/jpeg" >
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Profile Description</label>
                            <div class="col-md-10">
                                <textarea class="form-control" placeholder="enter description"  name="profile_description">{{ $user_details->profile_description}}</textarea>
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">

                            <label class="col-md-2 form-control-label">Facebook URL</label>

                            <div class="col-md-10">
                                {{ html()->text('facebook_url')
                                    ->class('form-control')
                                    ->value($user_details->facebook_url)
                                    ->placeholder('faceook url')
                                    ->attribute('maxlength', 191)
                                    ->autofocus() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                           <label class="col-md-2 form-control-label">Youtube URL</label>

                            <div class="col-md-10">
                                {{ html()->text('youtube_url')
                                    ->class('form-control')
                                    ->value($user_details->youtube_url)
                                    ->placeholder('youtue url')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Instagram URL</label>

                            <div class="col-md-10">
                                {{ html()->text('instagram_url')
                                    ->class('form-control')
                                    ->value($user_details->instagram_url)
                                    ->placeholder('instagram url')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->


                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">SnapChat URL</label>

                            <div class="col-md-10">
                                {{ html()->text('snap_chat_url')
                                    ->class('form-control')
                                    ->value($user_details->snap_chat_url)
                                    ->placeholder('snapchat url')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Twitter URL</label>

                            <div class="col-md-10">
                                {{ html()->text('twitter_url')
                                    ->class('form-control')
                                    ->value($user_details->twitter_url)
                                    ->placeholder('twitter url')
                                    ->attribute('maxlength', 191)
                                     }}
                            </div><!--col-->
                        </div><!--form-group-->


                </div><!--col-->
            </div><!--row-->

        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.company.index'), __('buttons.general.cancel')) }}
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
