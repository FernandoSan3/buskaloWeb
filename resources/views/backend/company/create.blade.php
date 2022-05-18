@extends('backend.layouts.app')

@section('title', __('labels.backend.company.management') . ' | ' . __('labels.backend.company.create'))

@section('content')

{{ html()->form('POST', route('admin.company.store'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}



    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Company Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.questions.create') --> Create Company</small>
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
                                    ->required()
                                    ->autofocus() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        
                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.email'))->class('col-md-2 form-control-label')->for('email') }}

                            <div class="col-md-10">
                                {{ html()->email('email')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.email'))
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.password'))->class('col-md-2 form-control-label')->for('password') }}

                            <div class="col-md-10">
                                {{ html()->password('password')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.password'))
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            {{ html()->label(__('validation.attributes.backend.access.users.password_confirmation'))->class('col-md-2 form-control-label')->for('password_confirmation') }}

                            <div class="col-md-10">
                                {{ html()->password('password_confirmation')
                                    ->class('form-control')
                                    ->placeholder(__('validation.attributes.backend.access.users.password_confirmation'))
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label"> RUC Number</label>

                            <div class="col-md-10">
                                {{ html()->text('ruc_no')
                                    ->class('form-control')
                                    ->placeholder('ruc number')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->


                          <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label"> Year Of Constitution </label>

                            <div class="col-md-10">
                                {{ html()->text('year_of_constitution')
                                    ->class('form-control')
                                    ->placeholder('year of constitution')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label"> Legal Representative </label>

                            <div class="col-md-10">
                                {{ html()->text('legal_representative')
                                    ->class('form-control')
                                    ->placeholder('legal representative')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label"> Web Address</label>

                            <div class="col-md-10">
                                {{ html()->text('website_address')
                                    ->class('form-control')
                                    ->placeholder('website address')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->
                        
                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label"> Mobile Number</label>

                            <div class="col-md-10">
                                {{ html()->text('mobile_number')
                                    ->class('form-control')
                                    ->placeholder('mobile number')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Landline Number</label>
                            <div class="col-md-10">
                                {{ html()->text('landline_number')
                                    ->class('form-control')
                                    ->placeholder('landline number')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Office Number</label>
                            <div class="col-md-10">
                                {{ html()->text('office_number')
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
                                    ->class('form-control')
                                    ->placeholder('address')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Office Address</label>
                            <div class="col-md-10">
                                {{ html()->text('office_address')
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
                                    ->placeholder('dob')
                                    ->attribute('maxlength', 191)
                                    ->required() --}}
                                
                                    <input type="text" class="form_date" id="datepicker1" name="dob">                             
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Profile Picture</label>
                            <div class="col-md-10">
                                <input type="file" name="avatar_location" accept="image/jpg, image/jpeg" required="">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-md-2 form-control-label"> Profile Description</label>
                            <div class="col-md-10">
                                <textarea class="form-control" placeholder="enter description" required="" name="profile_description"></textarea>
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            
                            <label class="col-md-2 form-control-label">Facebook URL</label>

                            <div class="col-md-10">
                                {{ html()->text('facebook_url')
                                    ->class('form-control')
                                    ->placeholder('faceook url')
                                    ->attribute('maxlength', 191)
                                    ->required()
                                    ->autofocus() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                           <label class="col-md-2 form-control-label">Youtube URL</label>

                            <div class="col-md-10">
                                {{ html()->text('youtube_url')
                                    ->class('form-control')
                                    ->placeholder('youtue url')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Instagram URL</label>

                            <div class="col-md-10">
                                {{ html()->text('instagram_url')
                                    ->class('form-control')
                                    ->placeholder('instagram url')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->


                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">SnapChat URL</label>


                            <div class="col-md-10">
                                {{ html()->text('snap_chat_url')
                                    ->class('form-control')
                                    ->placeholder('snapchat url')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
                            </div><!--col-->
                        </div><!--form-group-->

                        <div class="form-group row">
                            <label class="col-md-2 form-control-label">Twitter URL</label>

                            <div class="col-md-10">
                                {{ html()->text('twitter_url')
                                    ->class('form-control')
                                    ->placeholder('twitter url')
                                    ->attribute('maxlength', 191)
                                    ->required() }}
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
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}

<script type="text/javascript">
   
</script>
@endsection
