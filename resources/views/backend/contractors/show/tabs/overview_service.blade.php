<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>Username(ES)</th>
                <td>{{$show_service->username}}</td>
            </tr>
              <tr>
                <th>Category Name</th>
                <td>{{$show_service->en_category_name}}</td>
            </tr>
             <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Service</th>
                <td>{{ $show_service->es_service_name }}</td>
            </tr>
              <?php
                if(isset($show_service->es_subservice_name) && !empty($show_service->es_subservice_name)){

              ?>

             <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Sub Service Name</th>
                <td style="white-space: break-spaces;">{{ $show_service->es_subservice_name }}</td>
            </tr>
            <?php } ?>

             <?php
                if(isset($show_service->es_child_subservice_name) && !empty($show_service->es_child_subservice_name)){

              ?>

              <tr>
                <th> Child Sub Service Name</th>
                <td>{{ $show_service->es_child_subservice_name }}</td>
                </tr>

                <?php } ?>
            <tr>
                <th>location</th>
                <td>{{$show_service->location}}</td>
            </tr>
               <?php
                if(isset($show_service->en_category_name) && !empty($show_service->en_category_name)){

              ?>

            <?php } ?>
            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Mobile Number</th>
                <td>{{ $show_service->mobile_number }}</td>
            </tr>



            <!-- <tr>
                <th> Sub Service(En)</th>
                <td>{{ $show_service->en_subservice_name }}</td>
            </tr>

            <tr>
                <th> Sub Service(Es)</th>
                <td>{{ $show_service->es_subservice_name }}</td>
            </tr> -->


            <?php
                if(isset($show_service->question_detail) && !empty($show_service->question_detail)){
                    foreach ($show_service->question_detail as $key => $value) {
            ?>
                <tr>
                    <th>Question Title {{$key+1}} (En) </th>
                    <td>{{ $value->en_question_title }}</td>
                </tr>

                <tr>
                    <th>Answere Option {{$key+1}} (En) </th>
                    <td>{{ $value->en_option_name }}</td>
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
                            <td>{{ $value->amount }}</td>
                            <td>{{ $value->tranx_id }}</td>
                            </tr>
                           <?php $count++; ?>
                             <?php
                                  } } else {
                                ?>
                                <tr><td colspan="6"><center>No buyer found </center></td></tr>
                                 <?php    }
                                ?>
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
         </div>
       </div>
    </div>
