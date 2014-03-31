<?php
  /**
   * Class PDF
   * 
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
class PDF extends FPDF
{

function Header()
{
	// Logo
	$this->Image(SITEURL.'/uploads/logo_grey.png',10,6,30);
	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(50);
	// Title
	$this->Cell(60,10,'Viewing Coupon Master',1,0,'C');
	// Line break
	$this->Ln(20);
    /*//Logo
	$name="Export PDF";
    $this->SetFont('Arial','B',15);
    //Move to the right
    $this->Cell(80);
    //Title
	$this->SetFont('Arial','B',9);
    //Line break
    $this->Ln(20);*/
}

//Page footer
function Footer()
{
   
}

//Load data
function LoadData($file)
{
	//Read file lines
	$lines=file($file);
	$data=array();
	foreach($lines as $line)
		$data[]=explode(';',chop($line));
	return $data;
}

//Simple table
function BasicTable($header,$data)
{ 

$this->SetFillColor(0,255,0);
$this->SetDrawColor(128,0,0);
$w=array(30,15,20,10,10,10,10,10,15,15,15,15,15);

	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	//Data
	foreach ($data as $eachResult) 
	{ //width
		$this->Cell(30,6,$eachResult["email_id"],1);
		/*$this->Cell(15,6,$eachResult["name"],1);
		$this->Cell(20,6,$eachResult["location"],1);
		$this->Cell(10,6,$eachResult["address"],1);
		$this->Cell(10,6,$eachResult["telephone"],1);*/
		$this->Ln();
		 	 	 	 	
	}
}

//Simple table Fo Coupan
function CBasicTable($header,$data)
{ 

$this->SetFillColor(0,255,0);
$this->SetDrawColor(128,0,0);
$w=array(30,15,40,40,10,10,10,10,15,15,15,15,15);

	//Header
	for($i=0;$i<count($header);$i++)
		$this->Cell($w[$i],7,$header[$i],1,0,'C',true);
	$this->Ln();
	//Data
	foreach ($data as $eachResult) 
	{ //width
		$this->Cell(30,6,$eachResult["coupon_id"],1);
		$this->Cell(15,6,$eachResult["title"],1);
		$this->Cell(40,6,$eachResult["no_of_coupon"],1);
		$this->Cell(40,6,$eachResult["type_of_discount"],1);
		$this->Cell(10,6,$eachResult["discount"],1);
		$this->Ln();
		 	 	 	 	
	}
}

//Better table
}
function forme()

{
$d=date('d_m_Y');
print "<script type=\"text/javascript\"> "
					."\n // <![CDATA[ "					
 					."\n window.location.href = '" . SITEURL . "/uploads/pdffile/customer_" . date("d_M_Y_h_ia") . ".pdf';"					
					."\n // ]]>"
					."\n </script>";
//echo "PDF generated successfully. To download document click on the link >> <a href=".$d.".pdf>DOWNLOAD</a>";
}

?>
