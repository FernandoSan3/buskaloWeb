@extends('backend.layouts.app')

@section('title', __('labels.backend.zone.management') . ' | ' . __('labels.backend.zone.create'))

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.zone.management')
                        <small class="text-muted">All Polygons in City</small>
                    </h4>
                </div><!--col-->
            </div><!--row-->

            <hr>

             <div class="form-group row">
                <label class="col-md-2 form-control-label">{{-- Address --}}</label>

                <div class="col-md-6" >
                    <input type="address"  id="geofencings-address" name="address" class="form-control" value="">
                </div><!--col-->
            </div><!--form-group-->

            <div class="row mt-4">
                <div class="col">
                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"> {{-- Polygons --}} </label>
                        <div class="col-md-6" >
                           {{--   <input type="button" id="resetPolygon" value="Reset"/> --}}
                             {{--  <input type="button" id="resetPolygonedit" value="Resetedit"/> --}}
                             <div id="map"  style="height:500px;width:100%;"></div>
                            <div id="infowindow-content" class="hide">
                                  <img src="" width="16" height="16" id="place-icon">
                                  <span id="place-name"  class="title"></span><br>
                                  <span id="place-address"></span>
                            </div>
                            <input type="hidden" name="latlng" class="geo-cordinate" value="">

                            <input type="hidden" name="center_lat" id="center_lat" value="">
                            <input type="hidden" name="center_long" id="center_long" value="">

                            <input type="hidden" name="removed_index" id="removed_index" value="">
                        </div><!--col-->
                    </div><!--form-group-->
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{-- form_cancel(route('admin.zone.index'), __('buttons.general.cancel')) --}}
                </div><!--col-->

               
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->



<style type="text/css">
    .hide{
        display: none;
    }
</style>

<script type="text/javascript">
    var u=0;
    var removed_index = [];   

    ay_geo_cordinate_new = JSON.stringify(<?php echo json_encode($dummy_arr); ?>);
    ay_geo_cordinate_new = JSON.parse(ay_geo_cordinate_new);   

    var centerLat = '<?=!empty($geoFenc['center_lat'])?$geoFenc['center_lat']:'';?>';
    var centerLong = '<?=!empty($geoFenc['center_long'])?$geoFenc['center_long']:'';?>';
    
    /*runMaps Start*/
    var runMaps = function() {
        var gmarkers = [];
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -2.90055, lng: -79.00453},
            zoom: 15
        });

        var input = document.getElementById('geofencings-address');
        //var country = 'IN';

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);
          autocomplete.setFields(
        ['address_components', 'geometry', 'icon', 'name']);
        // autocomplete.setComponentRestrictions({ 'country': country });  

        var infowindow = new google.maps.InfoWindow();
        var infowindowContent = document.getElementById('infowindow-content');
        infowindow.setContent(infowindowContent);
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
          
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            infowindow.close();
            marker.setVisible(false);
            var place = autocomplete.getPlace();
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(1);  // Why 17? Because it looks good.
            }
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components)  {
                address = [
                  (place.address_components[0] && place.address_components[0].short_name || ''),
                  (place.address_components[1] && place.address_components[1].short_name || ''),
                  (place.address_components[2] && place.address_components[2].short_name || '')
                ].join(' ');
            }
            var lattitude =  place.geometry.location.lat();
            var longitude =  place.geometry.location.lng();
            document.getElementById('center_lat').value = lattitude;
            document.getElementById('center_long').value = longitude;
            placeMarker(lattitude,longitude);
        });
            
        function placeMarker(lattitude,longitude) {

            if(gmarkers.length>0)
            {
                alert('For getting starting point fill address again you should start fencing from markers point');
                window.location.reload();
                setMapOnAll(null);
            }
            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lattitude,longitude),
                map: map
            });
            gmarkers.push(marker);
        }
        
        var all_overlays = [];
        var selectedShape;

        var drawingManager = new google.maps.drawing.DrawingManager({

            //drawingMode: google.maps.drawing.OverlayType.MARKER,
            drawingMode: null,
            drawingControl: true,

            drawingControlOptions: {
                position: google.maps.ControlPosition.TOP_CENTER,
                drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                // drawingModes: ['circle', 'polygon', 'rectangle']
            },
            polygonOptions: {
                clickable: true,
                draggable: false,                    
                editable: true
           
            }
        });
            
        function clearSelection() {
            if (selectedShape) {
                selectedShape.setEditable(true);
                selectedShape = null;
            }
        }

        function setSelection(shape) {

            clearSelection();
            selectedShape = shape;
            shape.setEditable(true);
            // google.maps.event.addListener(selectedShape.getPath(), 'insert_at', getPolygonCoords(shape));
            // google.maps.event.addListener(selectedShape.getPath(), 'set_at', getPolygonCoords(shape));
        }

        function deleteSelectedShape() {
            if (selectedShape) {
                u--;
                selectedShape.setMap(null);
            }
        }

        function deleteAllShape() {
            for (var i = 0; i < all_overlays.length; i++) {
                all_overlays[i].overlay.setMap(null);
            }
            all_overlays = [];
        }

        function CenterControl(controlDiv, map) {
            return;
            // Set CSS for the control border.
            var controlUI = document.createElement('div');
            controlUI.style.backgroundColor = '#fff';
            controlUI.style.border = '2px solid #fff';
            controlUI.style.borderRadius = '3px';
            controlUI.style.boxShadow = '0 2px 6px rgba(0,0,0,.3)';
            controlUI.style.cursor = 'pointer';
            controlUI.style.marginBottom = '22px';
            controlUI.style.textAlign = 'center';
            controlUI.title = '<?=__('Select to delete the shape')?>';
            controlUI.class = 'deleteAreaBtn';
            controlDiv.appendChild(controlUI);

            // Set CSS for the control interior.
            var controlText = document.createElement('div');
            controlText.style.color = 'rgb(25,25,25)';
            controlText.style.fontFamily = 'Roboto,Arial,sans-serif';
            controlText.style.fontSize = '16px';
            controlText.style.lineHeight = '38px';
            controlText.style.paddingLeft = '5px';
            controlText.style.paddingRight = '5px';
            controlText.class = 'deleteAreaBtn';
            controlText.innerHTML = '<?=__('Delete Selected Area')?>';
            controlUI.appendChild(controlText);

            // Setup the click event listeners: simply set the map to Chicago.
            controlUI.addEventListener('click', function() {
                deleteSelectedShape();
            });
        }
            
        drawingManager.setMap(map);
        
        var getPolygonCoords = function(newShape) {
            var len = newShape.getPath().getLength();
            for (var i = 0; i < len; i++) {
                console.log(newShape.getPath().getAt(i).toUrlValue(6));
            }
        };
            
        var ay_geo_cordinate = [];            
        google.maps.event.addListener(drawingManager,'polygoncomplete', function(event){
            u++;
            event.getPath().getLength();
            google.maps.event.addListener(event.getPath(), 'insert_at', function() {
                var len = event.getPath().getLength();
                for (var i = 0; i < len; i++) {
                    console.log(event.getPath().getAt(i).toUrlValue(5));
                }
            });

            google.maps.event.addListener(event.getPath(), 'set_at', function() {
                var len = event.getPath().getLength();
                for (var i = 0; i < len; i++) {
                    console.log(event.getPath().getAt(i).toUrlValue(5));
                }
            });
                    
            var path = event.getPath()
            var coordinates = [];
            var arrayLength = path.length; // Main array length
            var limit = 2; // Number of squares
            var array1 = [];

            for ( var i = 0; i < arrayLength; i++ )
            {
                array1[i] = []; // Create subArray

                for( var j = 1; j <= limit; j++ )
                {
                    if(j == 1){
                        array1[i].push(path.getAt(i).lat());
                    }
                    else{
                        array1[i].push(path.getAt(i).lng());
                    }
                }
            }
            var valq = JSON.stringify(array1);
            $('#cordi').val(valq);
            ay_geo_cordinate.push(valq);         
            $('.geo-cordinate').val(ay_geo_cordinate);
        });

        $('#resetPolygon').click(function() {
            if (selectedShape) {
             selectedShape.setMap(null);
            }
        });

         
                    
        google.maps.event.addListener(drawingManager,'overlaycomplete', function(event){
            all_overlays.push(event);
            if (event.type !== google.maps.drawing.OverlayType.MARKER) {

                drawingManager.setDrawingMode(null);
                //Write code to select the newly selected object.

                var newShape = event.overlay;
                newShape.type = event.type;
                google.maps.event.addListener(newShape, 'click', function() {
                    setSelection(newShape);
                });
                setSelection(newShape);
            }
        });

            // disable n enable scroll
        google.maps.event.addListener(drawingManager,"drawingmode_changed", function() {
        //console.log("drawing mode changed:"+drawingManager.getDrawingMode());
            if(drawingManager.getDrawingMode()=='polygon'){
           
                var target = $('input[name=address]');
                if (target.length) {
                    var top = target.offset().top;
                    $('html,body').animate({scrollTop: top}, 1000);
                    //return false;
                }
                disableScroll();                
            }else {
                enableScroll();
            }
        })
        // disable n enable scroll end

        var centerControlDiv = document.createElement('div');
        var centerControl = new CenterControl(centerControlDiv, map);

        centerControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerControlDiv);
    };  

    /*runMaps End*/
    var keys = {37: 1, 38: 1, 39: 1, 40: 1};

    function preventDefault(e) {
      e = e || window.event;
      if (e.preventDefault)
          e.preventDefault();
      e.returnValue = false;
    }

    function preventDefaultForScrollKeys(e) {
        if (keys[e.keyCode]) {
            preventDefault(e);
            return false;
        }
    }

    function disableScroll() {

      if (window.addEventListener) // older FF
          window.addEventListener('DOMMouseScroll', preventDefault, false);
      window.onwheel = preventDefault; // modern standard
      window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
      window.ontouchmove  = preventDefault; // mobile
      document.onkeydown  = preventDefaultForScrollKeys;
    }

    function enableScroll() {
        if (window.removeEventListener)
            window.removeEventListener('DOMMouseScroll', preventDefault, false);
        window.onmousewheel = document.onmousewheel = null;
        window.onwheel = null;
        window.ontouchmove = null;
        document.onkeydown = null;
    }

    function drawPolygon() {
        runMaps();
       
        var boundary12 = [];            
        boundary12 = JSON.stringify(<?php echo json_encode($dummy_arr); ?>);
        boundary12 = JSON.parse(boundary12);
        
        if(boundary12.length > 0) {

            $.each(boundary12,function(key,val){
                   
                var boundary = val;

                if(boundary == ''){
                    return
                }
                
                var boundarydata = new Array();
                var latlongs = boundary.split(",");

                for (var i = 0; i < latlongs.length; i++) {
                    latlong = latlongs[i].trim().split(" ");
                    boundarydata[i] = new google.maps.LatLng(latlong[0], latlong[1]);
                }

                boundaryPolygon = new google.maps.Polygon({
                    paths: boundarydata,
                    draggable: false, // turn off if it gets annoying
                    editable: true,
                    clickable: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: '#FF0000',
                    fillOpacity: 0.35,
                   // zIndex: 1,
                    content: 'AREA ' + key,
                    shape:google.maps.drawing.OverlayType.POLYGON


                });           

                // boundaryPolygon.setEditable(true);
                if(centerLat!=''){
                    //map.setCenter([lat:centerLat,lng:centerLong]);
                    map.setCenter(new google.maps.LatLng(centerLat, centerLong));
                }else{
                    map.setCenter(boundarydata[latlongs.length-1]);
                }

                console.log(boundarydata[latlongs.length-1]);
                boundaryPolygon.setMap(map);


            });
        }


        var boundary123 = [];            
        boundary123 = JSON.stringify(<?php echo json_encode($dummy_arr12); ?>);
        boundary123 = JSON.parse(boundary123);

        if(boundary123.length > 0) {
            $.each(boundary123,function(key,val){
                   
                var boundary1 = val;

                if(boundary1 == ''){
                    return
                }
                
                
                var boundarydata = new Array();
                var latlongs = boundary1.split(",");

                for (var i = 0; i < latlongs.length; i++) {
                    latlong = latlongs[i].trim().split(" ");
                    boundarydata[i] = new google.maps.LatLng(latlong[0], latlong[1]);
                }

                boundaryPolygon = new google.maps.Polygon({
                    paths: boundarydata,
                    draggable: false, // turn off if it gets annoying
                    editable: true,
                    clickable: true,
                    strokeColor: '#0000FF',
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: '#0000FF',
                    fillOpacity: 0.35,
                   // zIndex: 1,
                    content: 'AREA ' + key,
                    shape:google.maps.drawing.OverlayType.POLYGON


                });           

                // boundaryPolygon.setEditable(true);
                if(centerLat!=''){
                    //map.setCenter([lat:centerLat,lng:centerLong]);
                    map.setCenter(new google.maps.LatLng(centerLat, centerLong));
                }else{
                    map.setCenter(boundarydata[latlongs.length-1]);
                }

                console.log(boundarydata[latlongs.length-1]);
                boundaryPolygon.setMap(map);


            });
        }


        var boundary234 = [];            
        boundary234 = JSON.stringify(<?php echo json_encode($dummy_arr24); ?>);
        boundary234 = JSON.parse(boundary234);

        if(boundary234.length > 0) {

            $.each(boundary234,function(key,val){
                    
                var boundary2 = val;

                if(boundary2 == ''){
                    return
                }
                
                
                var boundarydata = new Array();
                var latlongs = boundary2.split(",");

                for (var i = 0; i < latlongs.length; i++) {
                    latlong = latlongs[i].trim().split(" ");
                    boundarydata[i] = new google.maps.LatLng(latlong[0], latlong[1]);
                }

                boundaryPolygon = new google.maps.Polygon({
                    paths: boundarydata,
                    draggable: false, // turn off if it gets annoying
                    editable: true,
                    clickable: true,
                    strokeColor: '#00FF00',
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: '#00FF00',
                    fillOpacity: 0.35,
                   // zIndex: 1,
                    content: 'AREA ' + key,
                    shape:google.maps.drawing.OverlayType.POLYGON


                });           

                // boundaryPolygon.setEditable(true);
                if(centerLat!=''){
                    //map.setCenter([lat:centerLat,lng:centerLong]);
                    map.setCenter(new google.maps.LatLng(centerLat, centerLong));
                }else{
                    map.setCenter(boundarydata[latlongs.length-1]);
                }

                console.log(boundarydata[latlongs.length-1]);
                boundaryPolygon.setMap(map);


            });
        }

        var boundary456 = [];            
        boundary456 = JSON.stringify(<?php echo json_encode($dummy_arr34); ?>);
        boundary456 = JSON.parse(boundary456);

        if(boundary456.length > 0) {

            $.each(boundary456,function(key,val){
                    
                var boundary2 = val;

                if(boundary2 == ''){
                    return
                }
                
                
                var boundarydata = new Array();
                var latlongs = boundary2.split(",");

                for (var i = 0; i < latlongs.length; i++) {
                    latlong = latlongs[i].trim().split(" ");
                    boundarydata[i] = new google.maps.LatLng(latlong[0], latlong[1]);
                }

                boundaryPolygon = new google.maps.Polygon({
                    paths: boundarydata,
                    draggable: false, // turn off if it gets annoying
                    editable: true,
                    clickable: true,
                    strokeColor: '#FFFF00',
                    strokeOpacity: 0.8,
                    strokeWeight: 3,
                    fillColor: '#FFFF00',
                    fillOpacity: 0.35,
                   // zIndex: 1,
                    content: 'AREA' + key,
                    shape:google.maps.drawing.OverlayType.POLYGON


                });           

                // boundaryPolygon.setEditable(true);
                if(centerLat!=''){
                    //map.setCenter([lat:centerLat,lng:centerLong]);
                    map.setCenter(new google.maps.LatLng(centerLat, centerLong));
                }else{
                    map.setCenter(boundarydata[latlongs.length-1]);
                }

                console.log(boundarydata[latlongs.length-1]);
                boundaryPolygon.setMap(map);


            });
        }

        

        $('#resetPolygonedit').click(function() {
           
            if (selectedShape) {
               
             selectedShape.setMap(null);
            }else{
                
            }
        });


    }
    setTimeout(function(){ drawPolygon();},500);

    function getPolygonCoords() {

        var path = boundaryPolygon.getPath();
        var array2Length = path.length;
        var limit = 2;
        var array2 = [];
        for ( var i = 0; i < array2Length; i++ )
        {
            array2[i] = []; // Create subArray
            for( var j = 1; j <= limit; j++ )
            {
                if(j == 1){
                    array2[i].push(path.getAt(i).lat());
                }
                else{
                    array2[i].push(path.getAt(i).lng());
                }
            }
        }
        $('.geo-cordinate').val(JSON.stringify(array2));
    }


</script>


@endsection
