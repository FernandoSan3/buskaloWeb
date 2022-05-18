<select name="child_sub_services_id" id="child_sub_services_id" class="form-control"  >
    @if(count($childsubservices)>0)
    @foreach($childsubservices as $key => $childsubservice)
    <option value="{{$childsubservice->id}}">{{$childsubservice->es_name}}</option>
    @endforeach
    @else
    <option value="">No Option Available </option>
    @endif 
 </select>  

 <script type="text/javascript">
 	

 </script>