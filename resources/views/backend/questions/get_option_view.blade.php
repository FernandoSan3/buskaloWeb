<div style="margin-top: 22px !important;" id="row_{{ $added_input }}">                                
    <input type="text" name="ans[en][{{ $added_input }}]"  placeholder="write option in English" value="" class="ans form-control">   
    <input type="text" name="ans[es][{{ $added_input }}]"  placeholder="write option in Spanish" value="" class="form-control">
    
     <div class="row">
        <div class="col-md-4">
            {{-- <select name="ans[price][{{ $added_input }}]" id="" class="form-control"  >
                @if($price_ranges)
                @foreach($price_ranges as $ke => $range)
                <option value="{{$range->id}}">{{$range->start_price}} - {{$range->end_price }} </option>
                @endforeach
                @endif 
            </select> --}}
            <input type="text" name="ans[price][{{ $added_input }}]"  placeholder="Amount" value="" class="form-control"> 
        </div>
        <div class="col-md-4"><input type="text" name="ans[factor][{{ $added_input }}]"   placeholder="Percentage %" value="" class="form-control"> </div>

         <!-- <div class="col-md-4"><input type="text" name="ans[quantity][{{ $added_input }}]"   placeholder="quantity" value="" class="form-control"> </div> -->
    </div>
    
    

    <button type="button" onclick="removeRow(this);"> Remove row</button>

</div>


