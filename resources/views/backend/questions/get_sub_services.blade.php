<select name="sub_services_id" id="sub_services_id" class="form-control" onChange="getChildSubservices(this.value);">
    @if(count($subservices)>0)
    @foreach($subservices as $key => $subservice)
    <option value="{{$subservice->id}}">{{$subservice->es_name}} </option>
    @endforeach
    @else
    <option value="">No Option Available </option>
    @endif 
 </select>

 <script type="text/javascript">
 	

 </script>