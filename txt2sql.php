<?php
$link = mysqli_connect("localhost","carojasq","@lxJas17spSv","carojasq_domarch") or die("Error " . mysqli_error($link));


$sqlfile = file('sql.txt', FILE_SKIP_EMPTY_LINES);

foreach($sqlfile as $row){
	$splitRow = explode("	",$row);
	print_r($row);
	$a0 = $splitRow[0];
	$a1 = $splitRow[1];
	$a2 = $splitRow[2];
	$a3 = $splitRow[3];
	$a4 = $splitRow[4];
	$query = "INSERT INTO architecture VALUES ($a0,'$a1',$a2,$a3,'$a4');";
	if (mysqli_query($link, $query)) {
        print $splitRow[0]."<br>";
    }else{
		print mysqli_error($link);
		break;
	}	
}
?>

