<div class="dropdown float-right">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> All Request  </button>
  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="{{ route('admin.service_request.all_requests_by_status','all') }}">All</a>

    <a class="dropdown-item" href="{{ route('admin.service_request.all_requests_by_status','0') }}">Pending</a>

    <a class="dropdown-item" href="{{ route('admin.service_request.all_requests_by_status','1') }}">Accepted</a>


    <a class="dropdown-item" href="{{ route('admin.service_request.all_requests_by_status','2') }}">In-progress</a>

    <a class="dropdown-item" href="{{ route('admin.service_request.all_requests_by_status','3') }}">Rejected</a>

    <a class="dropdown-item" href="{{ route('admin.service_request.all_requests_by_status','4') }}">Completed</a>



  </div>
</div>
