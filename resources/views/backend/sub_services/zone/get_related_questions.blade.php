<select name="related_question_id" id="related_question_id" class="form-control">
    @if($related_questions)
    @foreach($related_questions as $key_qu => $related_question)
    <option value="{{$related_question->id}}">{{$related_question->es_title}} ( {{$related_question->en_title }} )</option>
    @endforeach
   `
    @endif 
 </select>

 <script type="text/javascript">
 	$('#related_question_id').change(function (){
         var question_id =  $('#related_question_id').val();
         getRelatedOptions(question_id);
    });
 </script>