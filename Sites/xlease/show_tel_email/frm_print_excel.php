<?php
include("../config/config.php");
include ("../Classes/PHPExcel.php");

$data_find = pg_escape_string($_GET['data_find']);
$find = pg_escape_string($_GET['condition']);
$type_show = pg_escape_string($_GET['type']);


if($find !=""){
	if(($find=="1") and ($data_find !="")){ //แผนก
		$qry_name=pg_query("
								SELECT
									\"dep_id\",
									\"dep_name\"
								FROM
									\"department\"
								WHERE
									\"dep_id\" = '$data_find' AND
									\"fdep_id\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)
							");
		$dep_name=pg_fetch_result($qry_name,0);		
	}
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getDefaultStyle()->getFont()->setName('Angsana New')->setSize(14);
$objPHPExcel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->SetCellValue('A1','ตารางแสดง เบอร์โทรศัพท์และ E-mail ของพนักงาน');
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->setName('Angsana New')->setSize(14);


$objPHPExcel->getActiveSheet()->SetCellValue('A2', 'ชื่อ -สกุล');
$objPHPExcel->getActiveSheet()->SetCellValue('B2', 'ชื่อเล่น');
$objPHPExcel->getActiveSheet()->SetCellValue('C2', 'เบอร์ภายใน');
$objPHPExcel->getActiveSheet()->SetCellValue('D2', 'เบอร์ตรง');
$objPHPExcel->getActiveSheet()->SetCellValue('E2', 'มือถือ');
$objPHPExcel->getActiveSheet()->SetCellValue('F2', 'E-mail');


$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(30);


$objPHPExcel->getActiveSheet()->getStyle('A2')->getFont()->setBold(true)->setName('Angsana New')->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true)->setName('Angsana New')->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('C2')->getFont()->setBold(true)->setName('Angsana New')->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('D2')->getFont()->setBold(true)->setName('Angsana New')->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('E2')->getFont()->setBold(true)->setName('Angsana New')->setSize(14);
$objPHPExcel->getActiveSheet()->getStyle('F2')->getFont()->setBold(true)->setName('Angsana New')->setSize(14);

	if($find=="0"){
		$qry_gpuser=pg_query("
								SELECT
									\"dep_id\",
									\"dep_name\",
									\"dep_tel\",
									\"dep_email\"
								FROM
									\"department\"
								WHERE
									\"fdep_id\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)
								ORDER BY
									\"dep_id\"
							");//แผนกในระบบทั้งหมด		
	}
	else if($find=="1"){
		$qry_gpuser=pg_query("
								SELECT
									\"dep_id\",
									\"dep_name\",
									\"dep_tel\",
									\"dep_email\"
								FROM
									\"department\"
								WHERE
									\"dep_id\" = '$dep_name' AND
									\"fdep_id\" IN(select \"fdep_id\" from \"f_department\" where \"fstatus\" = true)
								ORDER BY
									\"dep_id\"
							");//แผนกที่เลือก		
	}
	else if($find=="2"){
		$qry_gpuser=pg_query("
								SELECT
									a.\"dep_id\",
									a.\"dep_name\",
									a.\"dep_tel\",
									a.\"dep_email\"
								FROM
									\"department\" a
								LEFT JOIN
									\"Vfuser\" b on a.\"dep_id\" = b.\"user_group\"
								WHERE
									b.\"id_user\" = '$data_find' AND
									b.\"isadmin\" <> '1'
							");
		
		$condition="a.id_user='$data_find'";
	}
	$j = 2;
	while($res_type=pg_fetch_array($qry_gpuser))
	{		
		$dep_id=$res_type["dep_id"];
		$dep_name=$res_type["dep_name"];
		$dep_tel=$res_type["dep_tel"];
		$dep_email=$res_type["dep_email"];
		
		if($dep_tel != ""){$dep_tel_text = "(เบอร์กลาง #$dep_tel";}else{$dep_tel_text = "(เบอร์กลาง ยังไม่ระบุ";}
		if($dep_email != ""){$dep_email_text = "E-mail $dep_email)";}else{$dep_email_text = "E-mail ยังไม่ระบุ)";}
		
		if($find !='2'){
			$condition="a.\"user_group\"='$dep_id'";
		}
		
		$query=pg_query("SELECT
							a.fullname,
							b.u_extens,
							b.u_direct,
							CASE WHEN char_length(replace(b.u_tel, '-', '')) = 10 THEN -- เบอร์มือถือ
								substring(replace(b.u_tel, '-', '') from 1 for 3)||'-'||substring(replace(b.u_tel, '-', '') from 4 for 3)||'-'||substring(replace(b.u_tel, '-', '') from 7 for 4)
							ELSE
								CASE WHEN char_length(replace(b.u_tel, '-', '')) = 9 THEN -- เบอร์บ้าน
									substring(replace(b.u_tel, '-', '') from 1 for 2)||'-'||substring(replace(b.u_tel, '-', '') from 3 for 3)||'-'||substring(replace(b.u_tel, '-', '') from 6 for 4)
								ELSE
									b.u_tel
								END
							END AS \"u_tel\",
							b.u_email,
							b.nickname
						FROM
							\"Vfuser_active\" a
						LEFT JOIN
							\"fuser_detail\" b on a.\"id_user\"=b.\"id_user\"			
						WHERE
							$condition AND
							a.\"isadmin\" <> '1'
						ORDER BY
							a.id_user");
		
		if($type_show=="E"){	
			$j++;		
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $dep_name.$dep_tel_text.' : '.$dep_email_text);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$j.':'.'F'.$j)->getFill()
					->applyFromArray( array('type' => PHPExcel_Style_Fill::FILL_SOLID,
						'startcolor' => array('rgb' => "CCCCCC" )));
		}
		
		while($res_group=pg_fetch_array($query))
		{ 	
			$j++;
			$fullname=$res_group["fullname"];
			$u_extens=$res_group["u_extens"];
			if(($u_extens !="") and($u_extens !="-")){$u_extens ='#'.$u_extens;}
			$u_direct=$res_group["u_direct"];
			if(($u_direct !="")and($u_direct !="-")){$u_direct ='#'.$u_direct;}
			$u_tel=$res_group["u_tel"];
			$u_email=$res_group["u_email"];	
			$nickname=$res_group["nickname"];
			
			
			$objPHPExcel->getActiveSheet()->SetCellValue('A'.$j, $fullname);
			$objPHPExcel->getActiveSheet()->SetCellValue('B'.$j, $nickname);
			$objPHPExcel->getActiveSheet()->SetCellValue('C'.$j, $u_extens);
			$objPHPExcel->getActiveSheet()->SetCellValue('D'.$j, $u_direct);
			$objPHPExcel->getActiveSheet()->SetCellValue('E'.$j, $u_tel);
			$objPHPExcel->getActiveSheet()->SetCellValue('F'.$j, $u_email);	
		}
	}	

// ตั้งชื่อ Sheet
$objPHPExcel->getActiveSheet()->setTitle("Tel_E-mail");

$objPHPExcel->setActiveSheetIndex(0);

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="ตารางแสดง เบอร์โทรศัพท์และ E-mailของพนักงาน.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
}else{
	echo "เกิดข้อผิดพลาด";
}

?>