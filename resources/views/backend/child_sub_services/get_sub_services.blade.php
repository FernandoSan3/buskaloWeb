@if(count($subservices)>0)
 <select name="sub_services_id" required="" id="sub_services_id" class="form-control">
    @if($subservices)
    @foreach($subservices as $key => $subservice)
    <option value="{{$subservice->id}}">{{$subservice->es_name}}</option>
    @endforeach
    @endif 
 </select>
 @else
  <select name="sub_services_id" required="required" id="sub_services_id" class="form-control">
      <option value=""> Select Sub service</option>
  </select>
 @endif


 