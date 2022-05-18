<div class="btn-toolbar float-right" role="toolbar" aria-label="@lang('labels.general.toolbar_btn_groups')">

    <a href="{{ route('admin.cities.create_polygon',$city_id) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="create New Polygon"><i class="fas fa-plus-circle"></i></a>

	<a href="{{ route('admin.cities.all_zone_by_city',$city_id) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="all polygons in city">Polygons View</a>


    <div class="dropdown">
	  <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    List
	  </button>
	  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
	   
	    <a class="dropdown-item" href="{{ route('admin.cities.polygons',$city_id) }}">All polygons </a>
	    <a class="dropdown-item" href="{{ route('admin.cities.polygons_by_area',['city_id'=>$city_id, 'area_type'=>'low_resources_area']) }}">Low Resources </a>
	    <a class="dropdown-item" href="{{ route('admin.cities.polygons_by_area',['city_id'=>$city_id, 'area_type'=>'avg_resources_area']) }}">AVG Resources </a>
	    <a class="dropdown-item" href="{{ route('admin.cities.polygons_by_area',['city_id'=>$city_id, 'area_type'=>'high_resources_area']) }}">High Resources </a>
	  </div>
	</div>
   
</div>


