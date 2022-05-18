<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>Username</th>
                <td>{{$service_requests->username}}</td>
            </tr>
              <tr>
                <th>Category Name</th>
                <td>{{$service_requests->es_category_name}}</td>
            </tr>
             <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Service</th>
                <td>{{ $service_requests->es_service_name }}</td>
            </tr>
             <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Sub Service Name</th>
                <td style="white-space: break-spaces;">{{ $service_requests->es_subservice_name }}</td>
            </tr>
              <tr>
                <th> Child Sub Service Name</th>
                <td>{{ $service_requests->es_child_subservice_name }}</td>
                </tr>
            <tr>
                <th>location</th>
                <td>{{$service_requests->location}}</td>
            </tr>
               <?php
                if(isset($service_requests->en_category_name) && !empty($service_requests->en_category_name)){

              ?>

            <?php } ?>
            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Mobile Number</th>
                <td>{{ $service_requests->mobile_number }}</td>
            </tr>



            <!-- <tr>
                <th> Sub Service(En)</th>
                <td>{{ $service_requests->en_subservice_name }}</td>
            </tr>

            <tr>
                <th> Sub Service(Es)</th>
                <td>{{ $service_requests->es_subservice_name }}</td>
            </tr> -->


            <?php
                if(isset($service_requests->question_detail) && !empty($service_requests->question_detail)){
                    foreach ($service_requests->question_detail as $key => $value) {
            ?>
                <tr>
                    <th>Question Title {{$key+1}} </th>
                    <td>{{ $value->es_question_title }}</td>
                </tr>

                <tr>
                    <th>Answere Option {{$key+1}} </th>
                    <td>{{ $value->es_option_name }}</td>
                </tr>
            <?php
                    }
                }
            ?>



       </table>
    </div>
</div><!--table-responsive-->
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-5">
                <h4 class="card-title mb-0">
                    Service Buyers Name
                </h4>
            </div>
        </div>
  <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                        <th> @lang('labels.backend.questions.table.id')</th>
                        <th>Service Buyers Name</th>
                        <th>Amount</th>
                        <th>Transaction Id</th>
                        <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            if(isset($user_details) && count($user_details)>0) {
                                  $count = 0;
                               foreach ($user_details as $key => $value) {
                                ?>
                          <?php if($count == 3) break; ?>
                           <tr>
                           <td>{{$key+1}}</td>
                            <td>{{ $value->username }}</td>
                            <td>{{ $value->amount }} Credit</td>
                            <td>{{ $value->tranx_id }}</td>
                            <td>
                                @if($value->job_status==1)
                                   New
                                @elseif($value->job_status==2)
                                   Pending
                                @elseif($value->job_status==3)
                                    Accepted
                                @elseif($value->job_status==4)
                                     Rejected
                                @elseif($value->job_status==5)
                                    Completed
                                @endif
                            </td>
                            </tr>
                            <?php $count++; ?>
                            <?php
                                 } } else {
                              ?>
                                 <tr><td colspan="6"><center>No Buyer Found</center></td></tr>
                                  <?php }
                                ?>

                        </tbody>
                    </table>
                </div>
            </div><!--col-->
         </div>
       </div>
    </div>
