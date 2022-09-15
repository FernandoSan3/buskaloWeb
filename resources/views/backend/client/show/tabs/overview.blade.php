<div class="col">
    <div class="table-responsive">
        <table class="table table-hover">
            <tr>
                <th>Title(En)</th>
                <td>{{$question_details->en_title}}</td>
            </tr>

            <tr>
                <th>Title(Es)</th>
                <td>{{$question_details->es_title}}</td>
            </tr>

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Service(En)</th>
                <td>{{ $question_details->en_service_name }}</td>
            </tr>

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Service(Es)</th>
                <td>{{ $question_details->es_services_name }}</td>
            </tr>

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Sub Service(En)</th>
                <td>{{ $question_details->en_subservice_name }}</td>
            </tr>

            <tr>
                <th><!-- @lang('labels.backend.access.users.tabs.content.overview.email') --> Sub Service(Es)</th>
                <td>{{ $question_details->es_subservice_name }}</td>
            </tr>

            <?php 
                if(isset($question_details->options) && !empty($question_details->options)){
                    foreach ($question_details->options as $key => $value) {
            ?>
                <tr>
                    <th>Answere Option {{$key+1}} (En) </th>
                    <td>{{ $value->en_option }}</td>
                </tr>

                <tr>
                    <th>Answere Option {{$key+1}} (Es) </th>
                    <td>{{ $value->es_option }}</td>
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
