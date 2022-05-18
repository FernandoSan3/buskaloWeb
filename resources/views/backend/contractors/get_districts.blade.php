<select class="form-control" name="district_id" required="" id="districts">
    @if($districts)
    @foreach($districts as $k => $district)
    <option value="{{$district->id}}">{{$district->name}}</option>
    @endforeach
    @endif 
 </select>

