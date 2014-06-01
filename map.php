<?php $v = 0; if(isset($_GET['1'])) $v = 1;
if(isset($_GET['2'])) $v = 2;
if(isset($_GET['3'])) $v = 3;
if(isset($_GET['4'])) $v = 4;
if($v == 0) $v = 1;
?>
<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>EnJoy by #impact</title>
    <style>
      html,body,div{
        font-family: 'Helvetica';
      }
      a:link,a:active,a:visited{
        text-decoration: none;
        color:black;
      }
      html, body, #map-canvas, #hihi, #XX{
        height: 100% !important;
        margin: 0px;
        padding: 0px;
      }
      #map-canvas{
        width:79% !important;
        display: inline-block;
        vertical-align: top;
      }
      #hihi{
        display: inline-block;
        vertical-align: text-top;
      }
      #hihi, #XX{
        width:19% !important;
      }
      div.title{
        font-size:20px;
        color:#2c3e50;
        color:#333;
        font-weight: bold;
      }
      div.directions{
        color:#333 !important;
        font-size: 12px;
      }
    </style>
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script>
$(document).ready(function() {
  var banks = [];
  var locations = {};
  $.getJSON("cluster.php", function(d) {
        banks = d.banks;
        locations = d.locations;
        initialize();
    }).fail( function(d, textStatus, error) {
        console.error("getJSON failed, status: " + textStatus + ", error: "+error)
    });


var fail;


function initialize() {
  var mapOptions = {
    zoom: 13,
    center: new google.maps.LatLng(43.667905, -79.408648)
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var active = [];
  var index, index2;
  for(index = 0; index < locations.routes.length; ++index){
    for(index2 = 0; index2 < locations.routes[index].length; ++index2)
    {
      active.push([locations.routes[index][index2].lat, locations.routes[index][index2].lng]);
    }
  }

  var inactive = [];
  for(index = 0; index < locations.inactive.length; ++index){
      inactive.push([locations.inactive[index].lat,locations.inactive[index].lng]);
  }

  var food = [];
  for(index = 0; index < banks.length; ++index){
    food.push([banks[index].lat,banks[index].lng]);
  }

  var lines = [];
  for(index = 0; index < banks.length; ++index){
    var temp = "start="+banks[index].addr;
    if(locations.routes[index].length > 0){
      temp+= "&way=";
      for(index2 = 0; index2 < locations.routes[index].length; ++index2){
        temp+= locations.routes[index][index2].addr+"|";
      }
      temp = temp.substring(0,temp.length-1);
    }
    temp = "directions.php?index="+index+"&"+temp;
    // $.get(temp, function(data){
    $.ajax({
      url: temp,
      asynch: false
    }).done(function(data){
      var cc  = "black";
      var tt = jQuery.parseJSON(data);
      var showmap = [];
      var colors = ["#c0392b", "#d35400", "#27ae60", "#2980b9", "#8e44ad", "#2c3e50"];
      var colors_light = ["#e74c3c", "#e67e22", "#2ecc71", "#3498db", "#9b59b6", "#34495e"];
      var index = tt[1].index;

      

      $("#hihi").append("<a href='#"+(parseInt(index)+1)+"'><div style='width:100%;text-align:center;padding:3px;margin:3px;background:"+colors_light[index]+";'><div class='title'>Food Bank "+(parseInt(index)+1)+"</div><div "+(<?php echo $v-1; ?> == index?"":"style='display:none;'")+" class='directions a"+index+"'></div></div></a>");


      var s1 =0, s2 =0;//s1 = distance (m), s2 = duration (s)
      var toappend = "";
      for(index2 = 0; index2 < tt.length; ++index2){
        showmap.push(new google.maps.LatLng(tt[index2].loc[0],tt[index2].loc[1]));
        cc = tt[index2].index;
        toappend+= tt[index2].html_instructions;
        if(tt[index2].html_instructions.indexOf("Destination will be on the") <= -1) toappend+="<br />";
        if(tt[index2].duration.text != ""){
          toappend+= "<span style='font-size:14px;font-weight:bold;'>"+tt[index2].distance.text+" - "+tt[index2].duration.text+"</span><br />";
          s1+= tt[index2].distance.value;
          s2+= tt[index2].duration.value;
        }
        if(index2 != 0) toappend+="<br />";
      }
      $(".a"+index).append("<span style='font-size:20px;font-weight:bold;'>Total: "+Math.ceil(s1/1000)+" km - "+Math.ceil(s2/60)+" minutes</span><br />");
      $(".a"+index).append(toappend);
      toappend = "";

      var flightPath = new google.maps.Polyline({
        path: showmap,
        geodesic: true,
        strokeColor: colors[cc],
        strokeOpacity: 1.0,
        strokeWeight: 2
      });

      flightPath.setMap(fail);


    });
  }

  setMarkers(map, active, 'restaurant-map.png');
  setMarkers(map, inactive, 'food2.png');
  setMarkers(map, food, 'foodbank-map.png');
  fail = map;
}

function setMarkers(map, locations, icon) {
  var image = {
    url: icon,
    size: new google.maps.Size(30, 30),// This marker is 20 pixels wide by 32 pixels tall.
    origin: new google.maps.Point(0,0),// The origin for this image is 0,0.
    anchor: new google.maps.Point(15,15)// The anchor for this image is the base of the flagpole at 0,32.
  };
  // Shapes define the clickable region of the icon.
  // The type defines an HTML &lt;area&gt; element 'poly' which
  // traces out a polygon as a series of X,Y points. The final
  // coordinate closes the poly by connecting to the first
  // coordinate.
  var shape = {
      coords: [1, 1, 1, 20, 18, 20, 18 , 1],
      type: 'poly'
  };
  for (var i = 0; i < locations.length; i++) {
    var beach = locations[i];
    var myLatLng = new google.maps.LatLng(beach[0], beach[1]);
    var marker = new google.maps.Marker({
        position: myLatLng,
        map: map,
        icon: image,
        shape: shape
    });
  }
}
$(document).on('click', 'div.title', function(){
    if($(this).parent("div").children("div.directions").is(":visible")){
      $("div.directions").slideUp();
    }else
    {
      $("div.directions").slideUp();
      $(this).parent("div").children("div.directions").slideDown();
    }
}); 

});
    </script>
  </head>
  <body style='overflow:none;'>
  <div id="map-canvas"></div>
    <div id="hihi" style="font-style: Helvetica;font-size:14px;height:100%;margin:0;padding:0;">
    </div>
  </body>
</html>