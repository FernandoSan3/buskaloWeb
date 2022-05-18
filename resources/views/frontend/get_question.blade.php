<?php if(isset($question) && !empty($question)) { 
  foreach ($question as $key => $value) {

 
  ?>

<div class="tab-pane" id="step2">
  <div class="pro-heading">
    <h3 class="modal-title" id="question_step1" >@lang('labels.frontend.contact.question_title')</h3>
    <input type="hidden" name="question[0][question_id]" value="">
  </div>
  
  <div class="row">
    <div class="col-md-8">
      <div class="pro-info">
        <div class="meta-list">

         <?php if($value->question_type == 'radio'){ ?>
          <?php  foreach ($value->option as $ke => $val) { ?>
          <label class="cust-radio">{{$val->en_option}}
            <input type="radio" checked="checked" value="{{$val->id}}" class="question_option{{$key+1}}" name="question[{{$key}}][option_id]">
            <span class="checkmark"></span>
          </label>
          <?php } } elseif($value->question_type == 'checkbox') {
                foreach ($value->option as $ke => $val) {

           ?>
            <label class="cust-radio">{{$val->en_option}}
              <input type="checkbox" checked="checked" value="{{$val->id}}" class="question_option{{$key+1}}" name="question[{{$key}}][{{ $ke }}][option_id]">
              <span class="checkmark"></span>
            </label>
          <?php } } elseif($value->question_type == 'textbox') { ?>
            <label class="cust-radio">                        
              <textarea name="question[{{$key}}][option_id]" placeholder="@lang('labels.frontend.contact.enter_here')"></textarea>
            </label>
          <?php } else { ?>
            <label class="date_custom"> 
              <input type="text" placeholder="@lang('labels.frontend.contact.please_select')" class="form-control <?php if($value->question_type == 'date'){echo 'form_date'; } else { echo 'form_datetime';}?> " id="datepicker">
            </label>
          <?php } ?>
        </div>
      </div>

      <div style="overflow:auto;">
        <div  class="form-btn">
         <button type="button" class="btn next-btn"  onclick="prevbtnn(this)" >@lang('labels.frontend.contact.previous')</button>
          <button type="button" class="btn pre-btn"  onclick="nextbtnn(this)"  >@lang('labels.frontend.contact.next')</button>
        </div>
      </div>
    </div>           
   

  </div>

</div>

<?php } } ?>
          

    
