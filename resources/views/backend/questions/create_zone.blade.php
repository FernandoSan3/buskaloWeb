@extends('backend.layouts.app')

@section('title', __('labels.backend.questions.management') . ' | ' . __('labels.backend.questions.create'))

@section('content')
{{ html()->form('POST', route('admin.questions.store'))->class('form-horizontal')->id('question-form')->open() }}
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                         @lang('labels.backend.questions.management')
                        <small class="text-muted">
                        @lang('labels.backend.questions.create')</small>
                    </h4>
                </div><!--col-->
                
            </div><!--row-->

            <hr>

            <div class="row mt-4">
                <div class="col">

                    
                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Service </label>   

                        <div class="col-md-6">
                            
                              <input type="text" name="title" class="form-control" value="">
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        
                        <label class="col-md-2 form-control-label">Select Sub Service </label>   

                        <div class="col-md-6" >
                            
                            <input type="address" id="geofencings-address" name="address" class="form-control" value="">
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">                        
                        <label class="col-md-2 form-control-label">Question Order </label>
                        <div class="col-md-6" >
                             <div id="map"  style="height:500px;width:100%;"></div>
                            <div id="infowindow-content" class="hide">
                                  <img src="" width="16" height="16" id="place-icon">
                                  <span id="place-name"  class="title"></span><br>
                                  <span id="place-address"></span>
                            </div>
                            <input type="hidden" name="latlng" class="geo-cordinate">
                           
                            <input type="hidden" name="center_lat" id="center_lat" value="">
                            <input type="hidden" name="center_long" id="center_long" value="">                           
                        </div><!--col-->
                    </div><!--form-group-->



                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.questions.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    <button type="submit" id="addRecipesSubmitBtn" class="btn btn-primary" >Submit</button>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
{{ html()->form()->close() }}


<style type="text/css">
    .hide{
        display: none;
    }
</style>

<script type="text/javascript">
    var u=0;
    var centerLat = '<?=!empty($geoFenc['center_lat'])?$geoFenc['center_lat']:'';?>';
    var centerLong = '<?=!empty($geoFenc['center_long'])?$geoFenc['center_long']:'';?>';
    var runMaps = function() {
            var gmarkers = [];
            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 26.8733785, lng: 75.7771069},
                zoom: 12
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
            
            google.maps.event.addListener(drawingManager, 'polygoncomplete', function(event) {

                    u++;
                    if(u!=1){
                        alert('You can make only one fencing at a time. If fencing are not according to yours please click "Delete Selected area" button then make new fencing');
                        window.location.reload();
                    }else{
                          
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
                        console.log(valq);
                        $('#cordi').val(valq);
                        $('.geo-cordinate').val(valq);
                            
                    }
            });
                    
            google.maps.event.addListener(drawingManager, 'overlaycomplete', function(event) {
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
            google.maps.event.addListener(drawingManager, "drawingmode_changed", function() {
              //console.log("drawing mode changed:"+drawingManager.getDrawingMode());
              if(drawingManager.getDrawingMode()=='polygon'){
               
                    var target = $('input[name=address]');
                    if (target.length) {
                        var top = target.offset().top;
                        $('html,body').animate({scrollTop: top}, 1000);
                        //return false;
                    }
                disableScroll();                
              }
              else{
                enableScroll();
              }
            })
            // disable n enable scroll end

            var centerControlDiv = document.createElement('div');
            var centerControl = new CenterControl(centerControlDiv, map);

            centerControlDiv.index = 1;
            map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(centerControlDiv);
    };     

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
            var boundary = '<?=$latLngStr;?>';
            if(boundary == ''){return}
             u++;
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
                strokeColor: '#000000',
                strokeOpacity: 0.8,
                strokeWeight: 3,
                fillColor: '#222222',
                fillOpacity: 0.35

            });         
            if(centerLat!=''){
                //map.setCenter([lat:centerLat,lng:centerLong]);
                map.setCenter(new google.maps.LatLng(centerLat, centerLong));
            }
            else{
                map.setCenter(boundarydata[latlongs.length-1]);
            }
            console.log(boundarydata[latlongs.length-1]);

            boundaryPolygon.setMap(map);
            
             google.maps.event.addListener(boundaryPolygon.getPath(), "insert_at", getPolygonCoords);
             //google.maps.event.addListener(myPolygon.getPath(), "remove_at", getPolygonCoords);
             google.maps.event.addListener(boundaryPolygon.getPath(), "set_at", getPolygonCoords);
        }
        setTimeout(function(){drawPolygon();},500);                         
        
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
