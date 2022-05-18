@extends('backend.layouts.app')

@section('title', __('labels.backend.access.users.management') . ' | ' . __('labels.backend.access.users.edit'))

@section('breadcrumb-links')
    @include('backend.auth.user.includes.breadcrumb-links')
@endsection

@section('content')

{{ html()->form('PATCH', route('admin.auth.user.update_user', $user_details->id))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}
<input type="hidden" name="user_id" value="{{$user_details->id}}">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        @lang('labels.backend.access.users.management')
                        <small class="text-muted">@lang('labels.backend.access.users.edit')</small>
                    </h4>
                </div><!--col-->
            </div><!--col-->
        </div><!--row-->

        <hr>

        <div class="row mt-4">
            <div class="col">
                <div class="form-group row">
                  <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.table.username')</label>

                    <div class="col-md-10">
                        {{ html()->text('username')
                            ->class('form-control')
                            ->placeholder('username')
                            ->attribute('maxlength', 191)
                            ->value($user_details->username)
                           
                            ->autofocus() }}
                    </div><!--col-->
                </div><!--form-group-->

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.mobileno')</label>

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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.landlinenumber')</label>
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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.officenumber')</label>
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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.address')</label>
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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.officeaddress')</label>
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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.other address')</label>
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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.dob')</label>

                    <div class="col-md-10">

                     <input type="date" class="form-control" name="dob" value="<?php if(isset($user_details->dob) && !empty($user_details->dob)){echo $user_details->dob;}?>" id="datepicker">

                       {{-- {{ html()->text('dob')
                            ->class('form-control')
                            ->value($user_details->dob)
                            ->placeholder('dob')
                            ->attribute('maxlength', 191)
                             }}--}}

                    </div><!--col-->
                </div><!--form-group-->

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.profilepicture')</label>
                    <div class="col-md-10">
                        <input type="file" name="avatar_location" accept="image/jpg, image/jpeg" >
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.profiledescription')</label>
                    <div class="col-md-10">
                        <textarea class="form-control" placeholder="enter description"  name="profile_description">{{ $user_details->profile_description}}</textarea>
                    </div><!--col-->
                </div><!--form-group-->

                <div class="form-group row">
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.facebookurl')</label>

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
                   <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.youtubeurl')</label>

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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.instagramurl')</label>

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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.snapChaturl')</label>

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
                    <label class="col-md-2 form-control-label">@lang('labels.backend.access.users.tabs.content.overview.twitterurl')</label>

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

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.auth.user.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.update')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

@endsection
