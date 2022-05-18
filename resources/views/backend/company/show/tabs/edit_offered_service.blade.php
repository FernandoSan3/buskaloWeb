<div class="col">
    <div class="table-responsive">

        <table class="table table-hover">
               <?php

               foreach ($services_details as $value1) {
                $first =  $value1->first_name;
                $last =  $value1->last_name;
                $user_group_id =  $value1->user_group_id;
                //dd($services_details);
               
               }
                if(isset($service_ids) && count($service_ids)>0) {?>
                    @if($user_group_id==4)
                       <tr>
                        <th>Company Name</th>
                        <td><li style="margin-left: 4px;">{{$first}} {{$last}}</li></td>
                    </tr>
                    @endif
                 <?php 
                  foreach ($combinedData as $key_com => $val_com)
                       { ?>

                        @if(in_array($val_com['id'],$service_ids))
                        <ul style="list-style-type: decimal;">
                        
                      <tr>
                      <th>Service Name</th>
                      <td><li style="margin-left: 4px;">@if(in_array($val_com['id'],$service_ids)) {{$val_com['name']}} @endif</li></td>
                  </tr>
              </ul>
              @endif

               <?php $count=1;?>
             <?php $i=1; foreach ($val_com['subservices'] as $key => $sdata)
             {

                if(in_array($sdata['sub_service_id'],$sub_service_ids)){
                    ?>
                    @if($count ==1)
                    <tr>
                        <th>Sub Service Name</th>
                         <td><li style="margin-left: 24px;">@if(in_array($sdata['sub_service_id'],$sub_service_ids)) {{$sdata['name']}} @endif
                     </li></td>
                  </tr>

                <?php $count=0;?>
              @else

                 <tr>
                 <th></th>
                 <td><li style="margin-left: 24px;">@if(in_array($sdata['sub_service_id'],$sub_service_ids)) {{$sdata['name']}}
                  @endif</li></td>
                </tr> @endif
               <?php $i++;
                   } }
                    }
                }
                else{?>
                  <tr><td><center>Not Selected Any Services Yet</center></td></tr>
               <?php }
                ?>
        </table>
    </div>
</div><!--table-responsive-->
