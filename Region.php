<?php
include_once("Metadata.php");

class Region{
	public $metadata;
	public $type;		//[pfama]
	public $text;		//[DomName]
	public $colour = "";		//[#000000]
	public $display;	//[true][false]
	public $startStyle;	//[jagged][curved][straight]
	public $endStyle;	//[jagged][curved][straight]
	public $start;		//[num]
	public $end;		//[num]
	public $aliStart;	//[num]
	public $aliEnd;		//[num]
	public $href = "http://pfam.sanger.ac.uk/family/";
	
	function __construct() {
		$this->metadata = new Metadata();
	}
}//end class Region
?>
