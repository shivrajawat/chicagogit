<?php
  /**
   * Controller
   *
   * @package  
   * @author  
   * @copyright 2010
   * @version $Id: controller.php, 
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
  
if (isset($_POST['showMonth']))
  : if (intval($_POST['showMonth']) == 0 || empty($_POST['showMonth']))
  : redirect_to("../register");
endif;

	$posted_month = sanitize($_POST['birth_month_id']); 
	$str = '';
	
	switch ($posted_month)
	{
	case "1":
	     
		 $str.='<select name="birth_date" id="birth_date" class="input-small" style="margin-left:30px;">';
		 $str.='<option value="">Date</option>';			 
				  
		 for($i=1; $i<=31; $i++){			 
			 
			  $str.='<option value="'.$i.'">'.$i.'</option>';	
			 
		 } 		 	 
		 $str.='</select>';		 
		 print $str;	
		 	 				 
		break;
		
	case "2":
		
			$str.='<select name="birth_date" id="birth_date" class="input-small" style="margin-left:30px;">';	 
			 $str.='<option value="">Date</option>';	
			 		  
			 for($i=1; $i<=29; $i++){
				 
				  $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
			break;
	 
	case "3":
	
		     $str.='<select name="birth_date" id="birth_date">'; 
			 $str.='<option value="">Date</option>';	
			  		  
			 for($i=1; $i<=31; $i++){
				 
				  $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
			break;
			
	 case "4":
	 
		  $str.='<select name="birth_date" id="birth_date">';	 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=30; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
	 
	  case "5":
	 
		  $str.='<select name="birth_date" id="birth_date">';	 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=31; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
		 case "6":
	 
		  $str.='<select name="birth_date" id="birth_date">';	 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=30; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
		 case "7":
	 
		  $str.='<select name="birth_date" id="birth_date">';		 
		  $str.='<option value="">Date</option>';	
		  			  
		  for($i=1; $i<=31; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
		 case "8":
	 
		  $str.='<select name="birth_date" id="birth_date">';		 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=31; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
		 case "9":
	 
		  $str.='<select name="birth_date" id="birth_date">'; 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=30; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
		
		 case "10":
	 
		  $str.='<select name="birth_date" id="birth_date">';	 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=31; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
		case "11":
	 
		  $str.='<select name="birth_date" id="birth_date">';	 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=30; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
		case "12":
	 
		  $str.='<select name="birth_date" id="birth_date">';	 
		  $str.='<option value="">Date</option>';
		  				  
		  for($i=1; $i<=31; $i++){
				 
			 $str.='<option value="'.$i.'">'.$i.'</option>';	
				 
			 } 		 	 
			 $str.='</select>';		 
			 print $str;	
								 
		break;
	default:
	  echo "Nothing ";
	}


 
endif;
?>