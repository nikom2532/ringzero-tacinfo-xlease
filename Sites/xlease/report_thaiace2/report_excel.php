<?php
set_time_limit(0);
ini_set('memory_limit', '512M');

require_once('include/config.php');
require_once('../Classes/PHPExcel.php');

date_default_timezone_set('Europe/London');

$objPHPExcel = new PHPExcel();

$objPHPExcel->getProperties()->setCreator("Essoft")
                             ->setLastModifiedBy("Essoft")
                             ->setTitle("Essoft")
                             ->setSubject("Essoft")
                             ->setDescription("Essoft")
                             ->setKeywords("Essoft")
                             ->setCategory("Essoft");


$sheet = $objPHPExcel->getActiveSheet();

$sheet->getColumnDimension("A")->setAutoSize(true);
$sheet->getColumnDimension("B")->setAutoSize(true);
$sheet->getColumnDimension("C")->setAutoSize(true);
$sheet->getColumnDimension("D")->setAutoSize(true);

$sheet->mergeCells("A1:D1");
$sheet->getStyle("A1:D1")->applyFromArray(array("font" => array( "bold" => true)));

$j = 1;
$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$j, $project['name']);


$sheet->getStyle("A2:D2")->applyFromArray(array("font" => array( "bold" => true)));

$j++;
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A'.$j, "ชื่อลูกค้า")
    ->setCellValue('B'.$j, 'ที่อยู่')
    ->setCellValue('C'.$j, "เบอร์มือถือ")
    ->setCellValue('D'.$j, "เลขทะเบียน");

    $rows=0;

    $qry = pg_query("SELECT A.\"CusID\",A.\"IDNO\",A.\"C_REGIS\",A.\"full_name\",A.\"CusID\",C.*
    FROM \"UNContact\" AS A
    LEFT JOIN \"Fa1\" AS C ON A.\"CusID\"=C.\"CusID\"
    WHERE A.\"C_REGIS\" LIKE 'ท%' ORDER BY A.\"C_REGIS\" ASC");
    while($res = pg_fetch_array($qry)){
        $rows++;
		$CusID = trim($res['CusID']);
        $IDNO = $res['IDNO'];
        $C_REGIS = trim($res['C_REGIS']);
        $full_name = trim($res['full_name']);

        $A_NO = trim($res['A_NO']);
        $A_SUBNO = trim($res['A_SUBNO']);
        $A_SOI = trim($res['A_SOI']);
        $A_RD = trim($res['A_RD']);
        $A_TUM = trim($res['A_TUM']);
        $A_AUM = trim($res['A_AUM']);
        $A_PRO = trim($res['A_PRO']);
        $A_POST = trim($res['A_POST']);
		//ข้อมูลโทรศัพท์
		$A_MOBILE = trim($res['A_MOBILE']);
		$A_TELEPHONE = trim($res['A_TELEPHONE']);

        $A_NO = ($A_NO == "") ? '-' : $A_NO;
        $A_SUBNO = ($A_SUBNO == "") ? '-' : $A_SUBNO;
        $A_SOI = ($A_SOI == "") ? '-' : $A_SOI;
        $A_RD = ($A_RD == "") ? '-' : $A_RD;
        $A_TUM = ($A_TUM == "") ? '-' : $A_TUM;
        $A_AUM = ($A_AUM == "") ? '-' : $A_AUM;
        $A_PRO = ($A_PRO== "") ? '-' : $A_PRO;
        $A_POST = ($A_POST == "") ? '-' : $A_POST;

        $full_name = (trim($full_name) == "") ? '-' : $full_name;

        $str_address = "";
        $str_address = "$A_NO หมู่.$A_SUBNO ซอย.$A_SOI ถนน.$A_RD ตำบล.$A_TUM อำเภอ.$A_AUM จังหวัด.$A_PRO $A_POST";
		
		//ตรวจสอบ ว่ามีข้อมูลโทรศัพท์มือถือ ถ้าไม่มีให้เอาโทรศัพท์บ้าน ถ้าไม่มีอีกให้ขึ้นว่า ไม่มีข้อมูลเบอร์โทรศัพท์
		if(($A_MOBILE=="") OR ($A_MOBILE=="-") OR ($A_MOBILE=="--") OR ($A_MOBILE==null)){
			if(($A_TELEPHONE=="") OR ($A_TELEPHONE=="-") OR ($A_TELEPHONE=="--") OR ($A_TELEPHONE==null)){
			$qry_IDCARD = pg_query("select \"N_IDCARD\" from \"Fn\" WHERE  \"CusID\"='$CusID' AND \"N_IDCARD\" IS NOT NULL");
			list($N_IDCARD)=pg_fetch_array($qry_IDCARD);
			if($N_IDCARD==''){
				$qry_Contact = pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"CusID\"='$CusID'");
			}
			else {				
				$qry_Contact = pg_query("select \"N_ContactAdd\" from \"Fn\" WHERE  \"N_IDCARD\"='$N_IDCARD'");					
			}			
			$count=0;
				while($res_ContactAdd = pg_fetch_array($qry_Contact)){
					$N_ContactAdd = trim($res_ContactAdd['N_ContactAdd']);
				//1.กรณีที่เป็น มือถือ
				//format 089-1111111
				$phone_format_1 = '/(08|09|06)(\d{1})-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_1,$N_ContactAdd,$match))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 089-111-1111
				$phone_format_2 = '/(08|09|06)(\d{1})-(\d{3})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_2,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}	
				//format 0891111111
				$phone_format_3 = '/(08|09|06)(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_3,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//011111111
				$phone_format_4 = '/0(1|3|4|5|6|7|8|9)(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_4,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;
					}
				}
				//01-111-1111
				$phone_format_5 = '/0(1|3|4|5|6|7|8|9)-(\d{3})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_5,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;						
					}
				}
				
				// กรณี มือถือ แบบเก่า  01-1111111
				$phone_format_6 = '/0(1|3|4|5|6|7|8|9)-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_6,$N_ContactAdd,$match ))
					{
						$mobile_telephone_array=$match[0];
						$arr1 = str_split($mobile_telephone_array);						
						array_splice($arr1, 1, 0, array('8'));
						$mobile_telephone=implode($arr1,"");
						$count++;
					}
				}
				
				//2.กรณีที่เป็น บ้าน
				//02-965-7759
				$phone_format_7 = '/02-(\d{3})-(\d{4})/';
				
				if($count==0){
					if (preg_match($phone_format_7,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//02-7777777
				$phone_format_8 = '/02-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_8,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//027777777
				$phone_format_9 = '/02-(\d{7})/';
				if($count==0){
					if (preg_match($phone_format_9,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 038-111111
				$phone_format_10 = '/0(\d{2})-(\d{6})/';
				if($count==0){
					if (preg_match($phone_format_10,$N_ContactAdd,$match))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 038-111-111
				$phone_format_11 = '/0(\d{2})-(\d{3})-(\d{3})/';
				if($count==0){
					if (preg_match($phone_format_11,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}	
				//format 038111111
				$phone_format_12 = '/0(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_12,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 0-2222-2222
				$phone_format_13 = '/0-(\d{4})-(\d{4})/';
				if($count==0){
					if (preg_match($phone_format_13,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				//format 0-22222222
				$phone_format_14 = '/0-(\d{8})/';
				if($count==0){
					if (preg_match($phone_format_14,$N_ContactAdd,$match ))
					{
						$mobile_telephone=$match[0];
						$count++;
					}
				}
				
				if($count==0){
					$mobile_telephone="ไม่มีข้อมูลเบอร์โทรศัพท์";	
					
				}
				}
			}
			else{
				$mobile_telephone=$A_TELEPHONE;
			}
		}
		else{
			$mobile_telephone=$A_MOBILE;
		}
		//จบ
		
        //data to excel
        $j++;
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$j, "$full_name")
            ->setCellValue('B'.$j, "$str_address")            
			->setCellValue('C'.$j, "$mobile_telephone")
            ->setCellValue('D'.$j, "$C_REGIS");
		$objPHPExcel->getActiveSheet()
			->setCellValueExplicit('C'.$j, "$mobile_telephone",PHPExcel_Cell_DataType::TYPE_STRING);
    }

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('worksheet1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client‚Äôs web browser (Excel5)
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="export_excel.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>