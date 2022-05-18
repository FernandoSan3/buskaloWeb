<?php 
  if(!empty($allData) && count($allData) > 0)
  {
    foreach($allData as $allData)
    {
?>
      <div class="col-md-6">
        <div class="media opp-list p-3">
          <div class="opp-icon">
            <img src="{{ url('img/frontend/clocks.png') }}">
          </div>
          <div class="media-body">
            <h6 class="orange">{{$allData->category_name}}</h6>
            <h4>{{$allData->service_name}}{{-- Requerimiento de Instalacion --}}</h4>
            <span>{{ \Carbon\Carbon::parse($allData->created_at)->format('d M Y H:i:s A')}} 

            </span>
            <a class="link-dt" href="{{route('frontend.user.service_details',Crypt::encrypt($allData->id))}}">see info</a>
          </div>
        </div>
      </div>
<?php } } else { ?>
      <h1><center>@lang('labels.frontend.user.account.no_record_found')!</center></h1>
<?php }?>



                
              