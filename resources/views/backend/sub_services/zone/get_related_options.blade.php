<select name="related_option_id" class="form-control">
    @if($options)
    @foreach($options as $key_op => $option)
    <option value="{{$option->id}}">{{$option->es_option}} ( {{$option->en_option }} )</option>
    @endforeach
    @endif 
 </select>