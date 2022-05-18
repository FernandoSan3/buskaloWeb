     
 <select name="services_id" id="services_id" class="form-control" onChange="getSubservices(this.value);">
    @if(count($services)>0)
    @foreach($services as $ke => $service)
    <option value="{{$service->id}}">{{$service->es_name}} </option>
    @endforeach
    @else
    <option value="">No Option Available </option>
    @endif  
 </select>   
  

 <script type="text/javascript">
 	

 </script>