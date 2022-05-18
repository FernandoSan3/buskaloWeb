@extends('backend.layouts.app')

@section('title', __('labels.backend.company.management') . ' | ' . __('labels.backend.company.create'))

@section('content')

{{ html()->form('POST', route('admin.company.update_coverage_area'))->attribute('enctype', 'multipart/form-data')->class('form-horizontal')->open() }}

<input type="hidden" name="user_id" value="{{$userId}}">



    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         Company Management
                        <small class="text-muted">
                        <!-- @lang('labels.backend.company.create') --> Edit Coverage Area</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

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
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> Whole Country</label>
                        <div >
                             <input type="radio" <?php if($whole_country == 'Yes'){?> checked <?php } ?> id="inWholeCountryTrue1" name="whole_country" value="1" onclick="inWholeCountryTrue()"> true

                            <input type="radio" <?php if($whole_country == 'No'){?> checked <?php } ?>  id="inWholeCountryFalse1" name="whole_country" value="0" onclick="inWholeCountryFalse()"> fasle
                        </div>
                    </div>


                    <div id="proviencesArea" class="form-group row">
                        <label class="col-md-2 form-control-label"> <label>Select Provinces</label></label>
                        <div>
                         <ul class="area-list meta-list multi-cities-list" style="padding-left: 0;">
                          <select name="proviences[]" id="multi-select-proviences" multiple="multiple">
                               <?php
                              if(!empty($mixdata))
                              {
                                  foreach ($mixdata as $key => $val)
                                  { ?>
                                      <option class="parent_city" <?php if(in_array($val['id'], $user_province_ids)){ ?> selected <?php } ?> value="{{$val['id']}}">{{$val['name']}}</option>

                                    <?php $i=1; foreach ($val['cities'] as $cdata)
                                    {
                                    ?>
                                        <option class="child_city" <?php if(in_array($cdata['city_id'], $user_city_ids)){ ?> selected <?php } ?> value="<?php echo $val['id'].','.$cdata['city_id']?>">{{$cdata['name']}}</option>

                                 <?php $i++;} }
                              }
                              ?>
                            </select>
                    </ul>
                        </div>
                    </div>






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

    $(document).ready(function() {
        var whole_country = '<?php echo $whole_country; ?>';

        if(whole_country == 'Yes'){
           // inWholeCountryTrue();
        }else {
            //inWholeCountryFalse();
        }


    });


    // $('#inWholeCountryTrue').click(function(){

    //      $('#proviencesArea').hide();
    //      $('#citiesArea').hide();
    // });


    // $('#inWholeCountryFalse').click(function(){
    //  $('#proviencesArea').show();
    //  $('#citiesArea').show();
    // });

    function inWholeCountryTrue() {

        $('#proviences').val('').trigger("change");
        $('#cities').val('').trigger("change");
        $('#proviencesArea').hide();
        $('#citiesArea').hide();

    }

    function inWholeCountryFalse() {
        $('#proviencesArea').show();
        $('#citiesArea').show();

    }
</script>
@endsection
