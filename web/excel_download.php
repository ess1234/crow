<?php

/** PHPExcel */
require_once "./PHPExcel_1.8/PHPExcel.php"; // PHPExcel.php을 불러와야 하며, 경로는 사용자의 설정에 맞게 수정해야 한다.
$objPHPExcel = new PHPExcel ();

// 데이타 생성부분
$objPHPExcel->setActiveSheetIndex ( 0 )
->setCellValue ( "A1", 'test1' )
->setCellValue ( "B1", '한글' );

$objPHPExcel->getActiveSheet ()->setTitle ( 'Seet name' );

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex ( 0 );

// 파일의 저장형식이 utf-8일 경우 한글파일 이름은 깨지므로 euc-kr로 변환해준다.
$filename = iconv ( "UTF-8", "EUC-KR", "테스트" );

// Redirect output to a client’s web browser (Excel5)
header ( 'Content-Type: application/vnd.ms-excel' );
header ( "Content-Disposition: attachment;filename=" . $filename . ".xls" );
header ( 'Cache-Control: max-age=0' );

$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
$objWriter->save ( 'php://output' );
exit ();

?>

