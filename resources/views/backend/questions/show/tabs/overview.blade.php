<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th class="en_que_detail">Title(En)</th>
                <td class="en_que_detail">{{$question_details->en_title}}</td>
            </tr>

            <tr>
                <th>Title(Es)</th>
                <td>{{$question_details->es_title}}</td>
            </tr>

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Category(Es)</th>
                <td>{{ $question_details->es_category_name }}</td>
            </tr>

             <tr>
                <th en_que_detail><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Category(En)</th>
                <td>{{ $question_details->en_category_name }}</td>
            </tr>

           

            <tr>
                <th class="en_que_detail"><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Service(En)</th>
                <td class="en_que_detail">{{ $question_details->en_service_name }}</td>
            </tr>
            

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Service(Es)</th>
                <td>{{ $question_details->es_services_name }}</td>
            </tr>

            <tr>
                <th class="en_que_detail"><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Sub Service(En)</th>
                <td class="en_que_detail">{{ $question_details->en_subservice_name }}</td>
            </tr>

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Sub Service(Es)</th>
                <td>{{ $question_details->es_subservice_name }}</td>
            </tr>

            <tr class="en_que_detail">
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Child Sub Service(En)</th>
                <td>{{ $question_details->en_childsubservice_name }}</td>
            </tr>

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Child Sub Service(Es)</th>
                <td>{{ $question_details->en_childsubservice_name }}</td>
            </tr>

            <?php 
                if(isset($question_details->options) && !empty($question_details->options)){
                    foreach ($question_details->options as $key => $value) {
            ?>
                <tr>
                    <th class="en_que_detail">Answere Option {{$key+1}} (En) </th>
                    <td class="en_que_detail">{{ $value->en_option }}</td>
                </tr>

                <tr>
                    <th>Answere Option {{$key+1}} (Es) </th>
                    <td>{{ $value->es_option }}</td>
                </tr> 

                <tr>
                    <th>Price</th>
                    <td>{{ $value->price }}</td>
                </tr>

                 <tr>
                    <th>Factor Percentage(%)</th>
                    <td>{{ $value->factor }}</td>
                </tr>

                <tr>
                    <th>Quantity </th>
                    <td>{{ $value->quantity }}</td>
                </tr>          
            <?php
                    }
                }
            ?>
           

            <tr>
                <th>Created At</th>
                <td>
                    @if($question_details->created_at)
                        {{ timezone()->convertToLocal($question_details->created_at) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>

            
        </table>
    </div>
</div><!--table-responsive-->
