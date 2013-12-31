<?php
if(!$_FILES){
	header( 'Location: index.html' ) ;
	exit;
}
include_once("Architecture.php");
include_once("Region.php");

// []- Get the file and put it in a folder named "upload"
if ($_FILES["file"]["error"] > 0){
	echo "Error: " . $_FILES["file"]["error"] . "<br>";
}else{
	if(move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $_FILES["file"]["name"])){
		}
}

// []- Remove the garbage lines
$pfamfile = file('upload/'.$_FILES["file"]["name"], FILE_SKIP_EMPTY_LINES);
if (isset($pfamfile)){
	$flag = FALSE;
	$cont = 0;
	$findchar = '#';
	while(FALSE == $flag){
		if(strpos($pfamfile[$cont], $findchar) !== FALSE){
			unset($pfamfile[$cont]);
		}else{
			unset($pfamfile[$cont]);
			$pfamfile = array_values($pfamfile);
			$flag = TRUE;
		}
		$cont=$cont+1;
	}
}
file_put_contents('upload/'.$_FILES["file"]["name"],$pfamfile);
//unset($pfamfile); //free the mem used

//[]- Turn a pfam file into a JSON structure
$finalArch = file2object($pfamfile);
function file2object(&$file){
	$architectures = array();
	$actualArch = new Arch();
	foreach ($file as $row){
		$dom = explode(" ",$row);
		$count = count($dom);
		for ($i = 0; $i < $count; $i++) {
			if($dom[$i] == "" || $dom[$i] == " "){
				unset($dom[$i]);
			}
		}
		$dom = array_values($dom); //here we have an array whit 16 elements that represent a domain
		$identif = $dom[1];
		$newReg = new Region();
		// [[TYPE]]
		if($dom[7]=="Family" || $dom[7]=="Repeat" || $dom[7]=="Domain" || $dom[7]=="Motif"){
			$newReg->type = "pfama";
		}else{
			$newReg->type = "pfamb";
		}
		// [[TEXT]]
		$newReg->text = $dom[6];
		// [[DISPLAY]]
		if($dom[7]=="Family" || $dom[7]=="Repeat" || $dom[7]=="Domain" || $dom[7]=="Motif"){
			$newReg->display = true;
		}else{
			$newReg->display = false;
		}
		// [[START STYLE]]
		if ($dom[1] == $dom[3]) {
			if($dom[7]=="Repeat" || $dom[7]=="Motif"){
				$newReg->startStyle = "straight";
			}else{
				$newReg->startStyle = "curved";
			}
		}else{
			$newReg->startStyle = "jagged";
		}
		// [[END STYLE]]
		if ($dom[2] == $dom[4]) {
			if($dom[7]=="Repeat" || $dom[7]=="Motif"){
				$newReg->endStyle = "straight";
			}else{
				$newReg->endStyle = "curved";
			}
		}else{
			$newReg->endStyle = "jagged";
		}
		// [[START]]
		$newReg->start = $dom[3];
		// [[END]]
		$newReg->end = $dom[4];
		// [[ALIGNMENT START]]
		$newReg->aliStart = $dom[1];
		// [[ALIGNMENT END]]
		$newReg->aliEnd = $dom[2];
		// [[HREF]]
		$newReg->href .= $dom[5];
		//---------------------
		// [[METADATA - START]]
		$newReg->metadata->start = $dom[3];
		// [[METADATA - END]]
		$newReg->metadata->end = $dom[4];
		// [[METADATA - ALIGNMENT START]]
		$newReg->metadata->aliStart = $dom[1];
		// [[METADATA - ALIGNMENT END]]
		$newReg->metadata->aliEnd = $dom[2];
		// [[METADATA - TYPE]]
		$newReg->metadata->type = $dom[7];
		// [[METADATA - IDENTIFIER]]
		$newReg->metadata->identifier = $dom[6];
		// [[METADATA - ACCESSION]]
		$newReg->metadata->accession = $dom[5];
		// [[METADATA - SCORE]]
		$newReg->metadata->score = $dom[12];
		// [[METADATA - DESCRIPTION]]
		//$newReg->metadata->type = $dom[7];
		
		//ponemos la cabecera para separar las diferentes arquitecturas
		if($actualArch->empty === TRUE){
			$actualArch->secId = $dom[0];
			$actualArch->regions[] = $newReg;
			$actualArch->empty = False;
		}else{
			if($actualArch->secId == $dom[0]){
				$actualArch->regions[] = $newReg;
			}else{
				// [[COLOUR]]
				$actualArch->setColors();
				$architectures[] = $actualArch;
				
				// [[LENGTH]]
				$lastPos = count($actualArch->regions);
				$actualArch->length = ($actualArch->regions[$lastPos-1]->end)+100;
				/*
				print "<pre>";
				print_r($actualArch);
				print "</pre><hr>";
				*/
				$actualArch = new Arch();
				$actualArch->empty = false;
				$actualArch->secId = $dom[0];
				$actualArch->regions[] = $newReg;
			}
		}
		//echo json_encode($newReg);
		//print("<hr>");
	}//end foreach
	//print("<pre>");
	//print_r(json_encode($architectures));
	//print("</pre>");
	return $architectures;
}//end function file2object()

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0058)http://pfam.sanger.ac.uk/help/domain_graphics_example.html -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Domain graphics test page</title>
	<!-- load the prototype library from google -->
	<script type="text/javascript" src="./PDG/jsapi"></script>
	<script type="text/javascript">google.load("prototype", "1.7");</script>
	<script src="./PDG/prototype.js" type="text/javascript"></script>
	<!-- the domain graphics library -->
	<script type="text/javascript" src="./PDG/domain_graphics.js"></script>
	<script type="text/javascript" src="./PDG/canvas.text.js"></script>
	<script type="text/javascript" src="./PDG/canvas.text.js"></script>
	<script type="text/javascript" src="./PDG/optimer-bold-normal.js"></script>
	<script type="text/javascript" src="./PDG/prototip.js"></script>
	<script type="text/javascript" src="./PDG/styles.js"></script>
	<script type="text/javascript" src="./PDG/styles.js"></script>
	<!-- stylesheets. We only really need the rules that are specific to the tooltips -->
	<link rel="stylesheet" href="./PDG/pfam.css" type="text/css">
	<link rel="stylesheet" href="./PDG/prototip.css" type="text/css">
	<!--[if IE]>
	<script type="text/javascript" src="http://pfam.sanger.ac.uk/static/javascripts/excanvas.js"></script>
	<script type="text/javascript" src="http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js"></script>
	<![endif]-->
<style>
* {margin: 0; padding: 0;}
 
ul {
  list-style-type: none;
  width: 100%;
}
 
h3 {
  font: bold 15px Helvetica, Verdana, sans-serif;
}
 
li p {
  font: 200 12px/1.5 Georgia, Times New Roman, serif;
}
 
.liArch {
	border: solid 1px grey;
	border-radius:5px;
	list-style: none;
}
 
li:hover {
  background: #eee;
 }
body{
	background: url(images/bg.png) top center repeat #a4d1bc;
}
.Bcontainer{
	margin: 0 auto;
	width: 98%;
	height: 0%;
	background: #f9f1ed;
	margin-top: 2%;
	box-shadow: 0 0 5px .5px black;
	border-radius: 4px;
}
.contenedor{
		margin:0 auto;
		width:416px;
		height:300px;
		background:#f9f1ed;
		margin-top: 3%;
		box-shadow: 0 0 5px .5px black;
	}
.encabezado{
	font-size:18pt;
	font-family: Arial, Helvetica, sans-serif;
	text-transform: capitalize;
	color: white;
	text-shadow: 0 1px  5px black;
	text-align:center;
	background: url(images/hform.jpg) repeat;
	height:53px;
	line-height: 53px;
	margin: 0;
	border-radius: 4px 4px 0 0;
}
.prot-info{
	float:left;
	margin: 5px 0 5px 20px;
	padding-right: 20px;
}
.ident{
	max-width: 200px;
	overflow: auto;
	border-right: ridge 2px;
}
.arch{
	max-width: 790px;
	overflow: auto;
	border-right: ridge 2px;
}
.exist{
	max-width: 100px;
}
</style>
</head>
    <body style="background-image:url(images/bg.png)">
    <div class="contenedor" style="height: 200px;width: 800px;margin: auto auto;">
        <div class="encabezado" style="line-height: 30px;padding: 10px;height: 57px;font-size: 19pt;">
            Convenio especial de cooperación entre el IGUN de la Universidad Nacional y el CECAD de la Universidad Distrital
        </div>
        <img src="images/banner3.png" alt="banner UN-UD"/>
    </div>
	<div class="Bcontainer">
		<div class="encabezado">
		Domain Architecture Viewer
                </div>
		<p style="padding: 10px 10px;font-size: 13pt;"> 
		<?php print(count($finalArch));?> architectures have been generated..
		</p>
		<div id="errors" style="display: none;">SyntaxError: Unexpected end of input</div> 
		<div id="form" style="padding: 10px 10px;font-size: 13pt;">
                    <label for="xscale">X-scale: <input id="xscale" value="1.0" size="4"/></label><br>
                    <label for="yscale">Y-scale: <input id="yscale" value="1.0" size="4"/></label><br>
                    <button id="submit">resize</button>
                    <button id="getZip" name="getZip">Get Zip</button>
                </div>
		
		<!-- this will hold the canvas element -->
		<div id="dg">
			<ul id="dgul">
			</ul>
		</div>
	</div>
        <div style="display:none" id="imgBase64">
            <form id="formCanvas" name="formCanvas" method="post" action="canvas2img.php">  
            </form>
            
        </div>
</body>
    <script type="text/javascript">
		// <![CDATA[

		// instantiate an object
		var pg = new PfamGraphic();

		// the function that gets called when the "submit" button is clicked
		var generate = function() {

			// start by getting hiding any previous error messages and then 
			// delete the old canvas element. We could effectively blank the
			// old canvas by overwriting it with a big white rectangle, but 
			// here we're just removing it and letting the domain graphics
			// library generate a new one
			$("errors").hide();
			if ( $("dg").select("canvas").size() > 0 ) {
			  $("dg").select("canvas").first().remove();
			}
			

			// see if we can turn the contents of the text area into a valid
			// javascript data structure. If not, display an error message.
			<?php
			//If already canvas, delete that
			print 'try {
			var deleteContainer = document.getElementById("dg");
			deleteContainer.innerHTML = "";
			} catch ( e ) {
			  $("errors").update( e ).show();
			  return;
			}
			';
			$cont = 0;
			
			foreach ($finalArch as $singleArch){

				//get the json architecture
				print 'try {
				  sequence = eval( "sequence = " + \''.json_encode($singleArch).'\' );
				} catch ( e ) {
				  $("errors").update( e ).show();
				  return;
				}
				';
				//create the div where will put the canvas
				print '
					var li = document.createElement("li");
					li.className = "liArch";
					var divIdentifier = document.createElement("div");
					divIdentifier.className="prot-info ident";
					var divArchitecture = document.createElement("div");
					divArchitecture.className="prot-info arch";
					var divExist = document.createElement("div");
					divExist.className="prot-info exist";
					var divCanvas = document.createElement("div");
					divCanvas.id = "dg'.$cont.'";
					divCanvas.style.width = "98%";
					divCanvas.style.overflowX = "auto";
					var divJSON = document.createElement("div");
					divJSON.id = "dg'.$cont.'JSON";
					divJSON.style.display = "none";
					divJSON.innerHTML = \''.json_encode($singleArch).'\';
					var h3Ident = document.createElement("h3");
					h3Ident.innerHTML = "Protein Identifier";
					divIdentifier.appendChild(h3Ident);
					divIdentifier.innerHTML += \''.$singleArch->secId.'\';
					var h3Arch = document.createElement("h3");
					h3Arch.innerHTML = "Architecture";
					divArchitecture.appendChild(h3Arch);
					divArchitecture.innerHTML += "'.$singleArch->getArchNotation().'";
					var h3Exist = document.createElement("h3");
					h3Exist.innerHTML = "Reported";
					divExist.appendChild(h3Exist);
					divExist.innerHTML += "'.$singleArch->exist().'";
					var container = document.getElementById("dg");
					li.appendChild(divIdentifier);
					li.appendChild(divArchitecture);
					li.appendChild(divExist);
					li.appendChild(divCanvas);
					li.appendChild(divJSON);
					container.appendChild(li);
				';
				//set the info
				print '
					pg.setParent( "dg'.$cont.'" );
					pg.setImageParams( {
					  xscale: $F("xscale"),
					  yscale: $F("yscale")
					} );
				';
				//try to create the graphics
				print '
					try {
						pg.setSequence( sequence );
						pg.render();
					} catch ( e ) {
						$("errors").update( e ).show();
						return;
					}
				';
			$cont+=1;
			}
                        
			?>
		};

		  // a function to blank everything and start again
		  var clear = function() {
			$("errors").hide();
		  };

		  // when the DOM is loaded, add listeners to the two buttons
		  document.observe( "dom:loaded", function() {
			$("submit").observe( "click", generate );

			// we could generate the domain graphic as soon as the page is loaded,
			// assuming the textarea contains a real domain graphics description
			generate();
		  } );
                  
                  var canvas2img = function() {
                      var canv = document.getElementsByTagName("canvas");
                      for (index = 0; index < canv.length; ++index) {
                          var inputH = document.createElement("input");
                          inputH.id = "canv"+index;
                          inputH.name = "canv"+index;
                          inputH.type = "hidden";
                          inputH.value = canv[index].toDataURL("image/png");
                          var formCanvas =document.getElementById("formCanvas");
                          formCanvas.appendChild(inputH);
                        }
                        document.formCanvas.submit();
		  };
                        document.observe( "dom:loaded", function() {
                           $("getZip").observe( "click", canvas2img );
                        } );

		  // ]]>
                    
		</script>
</html>
