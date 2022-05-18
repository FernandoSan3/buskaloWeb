<html>
<head>
<title>map</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
{{--<script src="https://maps.googleapis.com/maps/api/js?libraries=drawing&key=AIzaSyCkUOdZ5y7hMm0yrcCQoCvLwzdM6M8s5qk"></script>--}}
 <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDg7Axyq3hQ9nUwBepdIdpZZ5MSHwg6TOI&libraries=places,drawing,geometry&language=en&callback=drawPolygon"></script>



<style>
#map_canvas {
  height: 100%;
  width: 100%;
  margin: 0px;
  padding: 0px
}
</style>
</head>
<body>

<input type="button" id="enablePolygon" value="Calculate Area" />
<input type="button" id="resetPolygon" value="Reset"/>
<div id="showonPolygon" style="display:none;"><b>Area:</b>  <span id="areaPolygon">&nbsp;</span>
</div>
<div id="map_canvas"></div>
<script>
var map;

function initialize() {
  map = new google.maps.Map(document.getElementById('map_canvas'), {
    center: {
      lat: 25.774252,
      lng: -80.190262
    },
    zoom: 2
  });

  var latlngs = [
    [{
      lat: 25.774252,
      lng: -80.190262
    }, {
      lat: 18.466465,
      lng: -66.118292
    }, {
      lat: 32.321384,
      lng: -64.757370
    }, {
      lat: 25.774252,
      lng: -80.190262
    }, ],
    [{
      lat: 59.677361,
      lng: -2.469846
    }, {
      lat: 59.299717,
      lng: -6.314917
    }, {
      lat: 57.877247,
      lng: -9.314917
    }, {
      lat: 54.428078,
      lng: -11.638861
    }, {
      lat: 51.784554,
      lng: -11.702241
    }]
  ];
  for (var y = 0; y < latlngs.length; y++) {
    createEditablePolygon(latlngs[y], y);
  }
}

function createEditablePolygon(latlngs, index) {
  var sample = [];
  for (var z = 0; z < latlngs.length; z++) {
    sample.push(new google.maps.LatLng(parseFloat(latlngs[z].lat), parseFloat(latlngs[z].lng)));
  }

  var boundary = new google.maps.Polygon({
    paths: sample,
    strokeColor: 'black',
    strokeWeight: 2,
    fillColor: 'black',
    fillOpacity: 0.2,
    zIndex: 1,
    content: 'AREA ' + index
  });
  boundary.setMap(map);

  var infoWindow = new google.maps.InfoWindow;
  boundary.addListener('click', function(event) {
    // toggle editable state
    boundary.setEditable(!boundary.getEditable());
    infoWindow.setContent(this.content);
    infoWindow.setPosition(event.latLng);
    infoWindow.open(map);
  });
}
google.maps.event.addDomListener(window, "load", initialize);
</script>
</body>
</html>