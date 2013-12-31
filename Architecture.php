<?php
include_once("Region.php");
include_once("sql.php");

class Arch{
	public $length = "1000";
	public $secId;
	public $regions = array();
	public $markups = array();
	public $empty = TRUE;
	public $exist = FALSE;
	public $colors = array();
	public $notation;
	
	function getArchNotation(){
		$notation = "";
		foreach($this->regions as $dom){
			if($dom->type != "pfamb"){
				$notation .= $dom->text."~";
			}
		}
		$notation = trim($notation, '~');
		$this->notation = $notation;
		return $notation;
	}
	
	function exist(){
		$link = connect();
		$query = "SELECT architecture FROM architecture WHERE architecture='$this->notation'";
		$result = mysqli_query($link, $query);
		$exist = mysqli_num_rows($result);
		if ($exist > 0) {
                    foreach ($this->regions as $reg) {
                        $acc.=$reg->metadata->accession.'+';
                    }
                    return "<a href='exist.php?$acc' target='_blank'><img src='images/yes.gif'></a>";
		}else{
			return "<img src='images/no.png'>";
		}	
	}
	
	function setColors(){
		foreach($this->regions as &$dom){
			if($dom->colour == ""){
				$newColor = $this->random_color();
				$dom->colour = $newColor;
				for($i=0;$i<count($this->regions);$i++){
					if($this->regions[$i]->text == $dom->text){
							$this->regions[$i]->colour = $newColor;
					}
				}
			}
		}
		//klahksjhs
	}
	function random_color_part() {
		return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
	}

	function random_color() {
		return $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
	}
}//end class Arch
?>
