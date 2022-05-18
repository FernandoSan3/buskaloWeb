<select name="sub_services_id" class="form-control">
    @if($subservices)
    @foreach($subservices as $key => $subservice)
    <option value="{{$subservice->id}}">{{$subservice->es_name}} ( {{$subservice->en_name }} )</option>
    @endforeach
    @endif 
 </select>