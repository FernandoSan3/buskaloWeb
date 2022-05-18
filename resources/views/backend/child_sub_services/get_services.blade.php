<select name="services_id" id="services_id" class="form-control">
    @if($services)
    @foreach($services as $key => $service)
    <option value="{{$service->id}}">{{$service->es_name}}</option>
    @endforeach
    @endif 
 </select>   

 