<div class="dropdown float-right">
  <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> All Service  </button>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
    <a class="dropdown-item" href="{{ route('admin.company.show_services_offered',$user_id) }}">Show Service</a>

    <a class="dropdown-item" href="{{ route('admin.company.add_services_offered',$user_id) }}">Add Service</a>

    <a class="dropdown-item" href="{{ route('admin.company.edit_services_offered',$user_id) }}">Edit Service</a>
  </div>
</div>



