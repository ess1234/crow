<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2013 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2013 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */
 
	$IMG_ROOT = "http://".$_SERVER["HTTP_HOST"]."/asbesto";	
	include_once $_SERVER["DOCUMENT_ROOT"]."/asbesto/lib/config_db.php"; //db 정보
	include_once $_SERVER["DOCUMENT_ROOT"]."/asbesto/lib/class_mysql.php"; //db class
	
	$SqlExecute = new dbMySql($connect);	
	
	$where = " WHERE CL.DEL_YN='N' AND LT.DEL_YN='N' AND TN.DEL_YN='N'  ";
	$from  = " LECTURE LT INNER JOIN TRAINEE TN ON LT.TRAINEE_SEQ = TN.TRAINEE_SEQ INNER JOIN CLASS CL ON LT.CLASS_SEQ = CL.CLASS_SEQ ";
	$strQuery = "SELECT LT.CLASS_SEQ, LT.TRAINEE_SEQ, LT.PWD,
								CL.TITLE, CL.SCHEDULE, CL.SITE, CL.TEACHER,
								TN.NAME, TN.JOB, TN.BIRTH, TN.TEL, TN.MOBILE, TN.EMAIL, 
								TN.ORG_NAME, TN.ORG_TYPE, SUBSTR(TN.ORG_ADDR, 0, LENGTH(TN.ORG_ADDR)) ORG_ADDR, TN.ORG_TEL, TN.ORG_OWNER, TN.ORG_STUDENT, TN.ORG_TEACHER,
								LT.SMS_PAY, LT.PAY, LT.PAYMENT, LT.PAYDATE, LT.ATTENDANCE, LT.SMS_ATTENDANCE, LT.REG_ID, DATE_FORMAT(LT.REG_DTS,'%Y-%m-%d') REG_DTS FROM ".$from.$where;
							 
	$total = $SqlExecute->dbCnt($from,"LT.TRAINEE_SEQ",$where); //총 게시물 수
	
	$trainee_result = $SqlExecute->dbQuery($strQuery);
	
	$SqlExecute->dbClose($connect);

/** Error reporting */
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
date_default_timezone_set('Europe/London');

if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

/** Include PHPExcel */
require_once '../PHPExcel/PHPExcel.php';


// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Admin")
							 ->setLastModifiedBy("Admin")
							 ->setTitle("Office 2007 XLS Document")
							 ->setSubject("Office 2007 XLS Document")
							 ->setDescription("Test document for Office 2007 XLS, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("result file");


// Add some data
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', iconv("EUC-KR","UTF-8",'강좌명'))
            ->setCellValue('B1', iconv("EUC-KR","UTF-8",'강좌일시'))
            ->setCellValue('C1', iconv("EUC-KR","UTF-8",'강좌장소'))
            ->setCellValue('D1', iconv("EUC-KR","UTF-8",'강사'))
            ->setCellValue('E1', iconv("EUC-KR","UTF-8",'수강자명'))
            ->setCellValue('F1', iconv("EUC-KR","UTF-8",'직책'))
            ->setCellValue('G1', iconv("EUC-KR","UTF-8",'생일'))
            ->setCellValue('H1', iconv("EUC-KR","UTF-8",'전화번호'))
            ->setCellValue('I1', iconv("EUC-KR","UTF-8",'휴대폰'))
            ->setCellValue('J1', iconv("EUC-KR","UTF-8",'이메일'))
            ->setCellValue('K1', iconv("EUC-KR","UTF-8",'소속학교'))
            ->setCellValue('L1', iconv("EUC-KR","UTF-8",'학교급'))
            ->setCellValue('M1', iconv("EUC-KR","UTF-8",'학교주소'))
            ->setCellValue('N1', iconv("EUC-KR","UTF-8",'수강료납부유무'))
            ->setCellValue('O1', iconv("EUC-KR","UTF-8",'출석체크유무'))
            ->setCellValue('P1', iconv("EUC-KR","UTF-8",'수강등록일'));

if($total>0){
for ($i=2; $row=mysql_fetch_array($trainee_result); $i++)
 {    
// Miscellaneous glyphs, UTF-8
$objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, iconv("EUC-KR","UTF-8",$row["TITLE"]))
            ->setCellValue('B'.$i, $row["SCHEDULE"])
            ->setCellValue('C'.$i, iconv("EUC-KR","UTF-8",$row["SITE"]))
            ->setCellValue('D'.$i, iconv("EUC-KR","UTF-8",$row["TEACHER"]))
            ->setCellValue('E'.$i, iconv("EUC-KR","UTF-8",$row["NAME"]))
            ->setCellValue('F'.$i, iconv("EUC-KR","UTF-8",$row["JOB"]))
            ->setCellValue('G'.$i, $row["BIRTH"])
            ->setCellValue('H'.$i, $row["TEL"])
            ->setCellValue('I'.$i, $row["MOBILE"])
            ->setCellValue('J'.$i, $row["EMAIL"])
            ->setCellValue('K'.$i, iconv("EUC-KR","UTF-8",$row["ORG_NAME"]))
            ->setCellValue('L'.$i, iconv("EUC-KR","UTF-8",$row["ORG_TYPE"]))
            ->setCellValue('M'.$i, iconv("EUC-KR","UTF-8",$row["ORG_ADDR"]))
            ->setCellValue('N'.$i, $row["PAY"])
            ->setCellValue('O'.$i, $row["ATTENDANCE"])
            ->setCellValue('P'.$i, $row["REG_DTS"]);
            
	}
}

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle(iconv("EUC-KR","UTF-8","수강자"));


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

$today=date('Ymd');
$file_name="TRAINEE_".$today.".xls"; //저장할 파일이름
// Redirect output to a client’s web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename='.iconv("EUC-KR","UTF-8",$file_name));
header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>