<select name="sub_services_id" id="sub_services_id" class="form-control">
    @if($subservices)
    @foreach($subservices as $key => $subservice)
    <option value="{{$subservice->id}}">{{$subservice->es_name}} ( {{$subservice->en_name }} )</option>
    @endforeach
    @endif 
 </select>

 <script type="text/javascript">
 	$('#sub_services_id').change(function(){
       
       var services_id = $('#services_id').val();
       var sub_services_id = $('#sub_services_id').val();
       var is_related = $('input[name=is_related]:checked', '#question-form').val();
                
        if(is_related == 'Yes') {
            alert('asdasdas');
            $("#no_related").prop("checked", true);
            $("#related_question_div").hide();
            $("#related_option_div").hide();
        } 
            //getRelatedQuestions(services_id,sub_services_id)
    });

 </script>