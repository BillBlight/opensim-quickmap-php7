<?php
    require_once("dbconnect.php");
    $resolution = GetValue("resolution", 2, 128, 8);
    $height = GetValue("size", 256, 4096, 1024);
?>



<html>
  <head>
    <title>Karte Map</title>
    <style type="text/css">
      body{ padding:0px; margin:0px; }
      #tooltip{ position:absolute; border:1px solid black; padding:2px; background-color:yellow; visibility:hidden; z-index:100 }
    </style>
  </head>
  <body>
    <?php echo "<img id=\"quickmap\" src=\"quickmap.php?resolution=$resolution&size=$height\" width=\"$height\" height=\"$height\" border\"=0\" usemap=\"#mapcoords\" onMouseover=\"tip('Ocean','lightblue')\" onMouseout=\"hidetip()\" onMousemove=\"movetip()\"/>"; ?>
    <map id="mapcoords" name="mapcoords">
	
	
	
	
	
<?php
    $imagemap = "";
    $min = 5500 - ($height/$resolution/2);
    $max = $min + ($height/$resolution);
    $low = $min*256;
    $high = $max*256;
    $result = mysqli_query($link, "select regionName,cast(locX/256 as unsigned),cast(locY/256 as unsigned),serverURI from regions where locX >= $low and locY >= $low and locX <= $high and locY <= $high order by RegionName" );
    
	if (!$result)
    {
        echo "Fehler beim Abrufen der Daten aus der Datenbank. \n";
        exit;
    }
	
    $lines = mysqli_num_rows( $result );
	
    for ($i = 0; $i < $lines; ++$i)
    {
        list( $RegionName, $x, $y, $host ) = mysqli_fetch_row( $result );

        $x1 = ($x - $min) * $resolution;
        $y1 = $height - (($y - $min) * $resolution);
        $x2 = $x1 + $resolution - 1;
        $y2 = $y1 + $resolution - 1;
        $gridx = $min + ($x / $resolution);
        $gridy = $min + ($y / $resolution);
        $html=htmlentities($RegionName,ENT_QUOTES);
        $slashes=addslashes($RegionName);
        $imagemap .= "      <area shape=\"rect\" href=\"http://www.opencad.de/$html/\" coords=\"$x1,$y1,$x2,$y2\" onMouseover=\"tip('<b>$slashes</b> ($x,$y)')\" onMouseout=\"hidetip()\" />\n";
    }
  echo $imagemap;
?>



    </map>
<div id="tooltip"></div>

<script type="text/javascript">
/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/
/*
     Modified by Warin Cascabel to calculate coordinates when over ocean regions.
*/
var offsetxpoint=-20 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
var savex = 0;
var savey = 0;
if (ie||ns6)
{
  var tipobj=document.all? document.all["tooltip"] : document.getElementById? document.getElementById("tooltip") : ""
  var imgobj=document.all? document.all["quickmap"] : document.getElementById? document.getElementById("quickmap") : ""
  var mapobj=document.all? document.all["mapcoords"] : document.getElementById? document.getElementById("mapcoords") : ""

  var mapleft = maptop = 0;
  var obj = imgobj;
  if (obj.offsetParent) do
  {
    mapleft += obj.offsetLeft;
    maptop += obj.offsetTop;
  } while (obj = obj.offsetParent);
}


function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function tip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function truncate(n)
{
  return Math[n>0?"floor":"ceil"](n);
}

function movetip() {
  if (enabletip)
  {
    var thex = Math.floor((savex-mapleft) / <?php echo $resolution; ?>) + <?php echo $min; ?>;
    var they = <?php echo $max; ?> - (Math.floor((savey-maptop) / <?php echo $resolution; ?>));
    tipobj.innerHTML = thex+","+they;
  }
}



function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
savex = curX;
savey = curY;
//Finden Sie heraus, wie nah sich die Maus an der Ecke des Fensters befindet
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//Wenn der horizontale Abstand nicht ausreicht, um die Breite des Kontextmenüs aufzunehmen
if (rightedge<tipobj.offsetWidth)
//Verschieben Sie die horizontale Position des Menüs um die Breite nach links
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//Positionieren Sie die horizontale Position des Menüs, in dem sich die Maus befindet
tipobj.style.left=curX+offsetxpoint+"px"

//Gleiches Konzept mit der vertikalen Position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}




function hidetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip

</script>


  </body>
</html>
<?php
    function GetValue( $varname, $min, $max, $default )
    {
        $result = $default;
        if (isset($_GET[$varname])) $result = $_GET[$varname];
        if ($result < $min) $result=$min;
        $result = (($result-($j=pow(2,((int)(log($result)/log(2))))))?$j<<1:$j); // increase non-power-of-2 to next biggest power of 2
        return ($result>$max) ? $max : $result;
    }
?>
