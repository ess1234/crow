<?php
	$reg_id=$_SESSION[ID];	
	echo "<script language=\"javascript\">\n";
	echo "if('".$reg_id."'!=\"ADMIN\"){ ";
	echo " alert('권한이없습니다.'); ";
	echo "location.href=\"../main.php\"; ";
	echo " } ";
	echo "</script>";
?>