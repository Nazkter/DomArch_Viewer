<?php
//$petition=str_replace('+', ' ', $_SERVER['QUERY_STRING']);
$petition=$_SERVER['QUERY_STRING'];
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    
	<title>Domain Architecture Viewer</title>
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
        <script type='text/javascript' src='http://pfam.sanger.ac.uk/static/javascripts/excanvas.js'></script>
        <script type='text/javascript' src='http://pfam.sanger.ac.uk/static/javascripts/domain_graphics.js'></script>
        <script type='text/javascript' src='http://pfam.sanger.ac.uk/static/javascripts/domain_graphics_loader.js'></script> 
           
            <!--[if IE]>
	<script type="text/javascript" src="http://pfam.sanger.ac.uk/static/javascripts/excanvas.js"></script>
	<script type="text/javascript" src="http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js"></script>
	<![endif]-->

	<style type="text/css">
	body{
		background: url(images/bg.png) top center repeat #a4d1bc;
	}
	.contenedor{
		margin:0 auto;
		width:416px;
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
		
	}
	input{
		margin:15px;
		width:386px;
		height:28px;
		font-size:14pt;
		border-radius:5px;
		border:none;
		box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
		font-weight:bold;
		color:#90bf7f;
	}
	label{
		font-size:14pt;
		font-weight:bold;
		color:#90bf7f;
	}
	input[type="text"]{
		text-align:center;
		background:url(images/icon-nombre.png) no-repeat;
		background-position: 10px ;
	}
	input[type="Email"]{
		color:#e07361;
		text-align:center;
		background:url(images/icon-correo.png) no-repeat;
		background-position: 10px ;

	}
	input[type="submit"]{
		width:120px;
		height:36px;
		border-radius:5px ;
		cursor: pointer;
		background: url(images/hform.jpg) repeat;
		font-size:13px;
		color:white;
		text-shadow: 0 1px  5px black;
		margin-right:40px;
		float:right;
	}
	textarea{
		width:331px;
		height:138px;
		border-radius:5px;
		border:none;
		box-shadow:0 1px 4px rgba(0, 0, 0, 0.3), 0 0 40px rgba(0, 0, 0, 0.1) inset;
		font-size:14pt;
		text-align:center;
		font-family:Arial, Helvetica, sans-serif;
		font-weight:bold;
		color:#90bf7f;
		background:url(images/icon-mensaje.png) no-repeat;
		background-position: 10px ;
	}
	
	.formulario{
		text-align:center;
		padding-top:60px;

	}
	.divisor{
		margin-top:40px;
		background:url("images/divisor.png");
		width:416px;
		height:1px;
	}
	</style>
</head>
<body>
    <div class="contenedor" style="height: 200px;width: 800px;margin: auto auto;">
        <div class="encabezado" style="line-height: 30px;padding: 10px;height: 57px;font-size: 19pt;">
            Convenio especial de cooperación entre el IGUN de la Universidad Nacional y el CECAD de la Universidad Distrital
        </div>
        <img src="images/banner3.png" alt="banner UN-UD"/>
    </div>
    <div class="contenedor">
        <div class="encabezado">
            Domain Architecture Viewer
        </div>
        <div class="formulario">
            <?php
            $c = curl_init('http://pfam.sanger.ac.uk/search/domain');
            curl_setopt($c, CURLOPT_POSTFIELDS, "have=$petition&not=");
            curl_setopt($c, CURLOPT_FOLLOWLOCATION, true); 
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");

            curl_exec ($c);
            curl_close ($c);
            ?>
        </div>
    </div>
</body>
</html>
