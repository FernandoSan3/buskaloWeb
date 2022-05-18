  <select name="services_id" id="services_id" required="required" class="form-control" onChange="getSubServices(this.value);">
    @if($services)
      <option value="">Select Services</option>
    @foreach($services as $key => $service)
    <option value="{{$service->id}}">{{$service->es_name}}</option>
    @endforeach
    @endif 
 </select>   

 