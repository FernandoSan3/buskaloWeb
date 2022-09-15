<div class="dropdown float-right">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> All Request  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="{{route('admin.company.all_requests_by_status',['status' =>'all','user_id' => $user_id])}}">All</a>

    <a class="dropdown-item" href="{{route('admin.company.all_requests_by_status',['status' =>'0','user_id' => $user_id])}}">Pending</a>

    <a class="dropdown-item" href="{{route('admin.company.all_requests_by_status',['status' =>'1','user_id' => $user_id])}}">Accepted</a>


    <a class="dropdown-item" href="{{route('admin.company.all_requests_by_status',['status' =>'2','user_id' => $user_id])}}">In-progress</a>

    <a class="dropdown-item" href="{{route('admin.company.all_requests_by_status',['status' =>'3','user_id' => $user_id])}}">Rejected</a>

    <a class="dropdown-item" href="{{route('admin.company.all_requests_by_status',['status' =>'4','user_id' => $user_id])}}">Completed</a>



  </div>
</div>
