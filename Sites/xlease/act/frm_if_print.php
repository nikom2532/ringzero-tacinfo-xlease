<?php

include("../config/config.php");

$start_date = pg_escape_string($_GET['start_date']);
$end_date = pg_escape_string($_GET['end_date']); 
$nowdate = date("Y/m/d");  

    $strYear = date("Y",strtotime($start_date))+543;
    $strMonth = date("m",strtotime($start_date));
    $strDate = date("d",strtotime($start_date));
    
    $endYear = date("Y",strtotime($end_date))+543;
    $endMonth = date("m",strtotime($end_date));
    $endDate = date("d",strtotime($end_date));
    
    $nowYear = date("Y",strtotime($nowdate))+543;
    $nowMonth = date("m",strtotime($nowdate));
    $nowDate = date("d",strtotime($nowdate));

    $thaimonth=array("มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม ","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน ","ธันวาคม");
    $conv_start_date = $strDate." ".$thaimonth[$strMonth-1]." ".$strYear;
    $conv_end_date = $endDate." ".$thaimonth[$endMonth-1]." ".$endYear;
    $conv_nowdate = $nowDate." ".$thaimonth[$nowMonth-1]." ".$nowYear;

$qry_in=pg_query("select * from \"insure\".\"InsureForce\" WHERE (\"InsFIDNO\"='".pg_escape_string($_GET[insid])."')");
if($res_in=pg_fetch_array($qry_in)){
    $IDNO = $res_in["IDNO"];
    $Code = $res_in["Code"];    $SubCode = substr($Code, 0, 4);
    $Capacity = $res_in["Capacity"];
    $Premium = number_format($res_in["Premium"],2);
    $NetPremium = number_format($res_in["NetPremium"],2);
    $TaxStamp = number_format($res_in["TaxStamp"],2);
    $Vat = number_format($res_in["Vat"],2);
}

$qry_in2=pg_query("select * from \"insure\".\"RateInsForce\" WHERE (\"IFCode\"='$Code')");
if($res_in2=pg_fetch_array($qry_in2)){
    $BodyType = $res_in2["BodyType"];
    $CapacityUnit = $res_in2["CapacityUnit"];
}

$qry_in3=pg_query("select \"asset_id\" from \"Fp\" WHERE (\"IDNO\"='$IDNO')");
if($res_in3=pg_fetch_array($qry_in3)){
    $asset_id = $res_in3["asset_id"];
    
    $qry_in4=pg_query("select * from \"VCarregistemp\" WHERE (\"IDNO\"='$IDNO')");
    if($res_in4=pg_fetch_array($qry_in4)){
        $C_CARNAME = $res_in4["C_CARNAME"];
        $C_REGIS = $res_in4["C_REGIS"];
        $C_REGIS_BY = $res_in4["C_REGIS_BY"];
        $C_CARNUM = $res_in4["C_CARNUM"];
    }
    
}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

$pdf=new ThaiPDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->SetThaiFont();
$pdf->AddPage();

$pdf->SetFont('AngsanaNew','',13);
$buss_name1=iconv('UTF-8','windows-874',"บริษัท เอวี. ลีสซิ่ง จำกัด");  //ชื่อบริษัท 
$pdf->Text(70,53,$buss_name1);
$buss_name2=iconv('UTF-8','windows-874',"667 ถ.จรัญสนิทวงศ์ แขวงอรุณอมรินทร์"); 
$pdf->Text(70,58,$buss_name2);
$buss_name3=iconv('UTF-8','windows-874',"เขตบางกอกน้อย กรุงเทพฯ"); 
$pdf->Text(70,63,$buss_name3);

$start_date=iconv('UTF-8','windows-874',$conv_start_date);  //วันที่ 
$pdf->Text(68,70,$start_date);
$end_date=iconv('UTF-8','windows-874',$conv_end_date); 
$pdf->Text(122,70,$end_date);

$car_code=iconv('UTF-8','windows-874',$SubCode);  //รายการ 
$pdf->Text(6,96,$car_code);

$pdf->SetFont('AngsanaNew','',10);
$pdf->SetXY(22,93);
$car_name=iconv('UTF-8','windows-874',$C_CARNAME);
$pdf->MultiCell(37,4,$car_name,0,'L',0);

$pdf->SetFont('AngsanaNew','',13); 
$car_bc=iconv('UTF-8','windows-874',$C_REGIS . " No. " . $C_REGIS_BY);
$pdf->Text(60,96,$car_bc);
$car_mr=iconv('UTF-8','windows-874',$C_CARNUM);
$pdf->Text(91,96,$car_mr);
$car_num_body=iconv('UTF-8','windows-874',$BodyType);
$pdf->Text(140,96,$car_num_body);
$car_cc=iconv('UTF-8','windows-874',$Capacity." ".$CapacityUnit);
$pdf->Text(171,96,$car_cc);

$b_money=iconv('UTF-8','windows-874',$Premium);  //เบี้ย 
$pdf->Text(8,159,$b_money);
$b_discount=iconv('UTF-8','windows-874',"");  
$pdf->Text(17,159,$b_discount);
$b_net=iconv('UTF-8','windows-874',$NetPremium);  
$pdf->Text(85,159,$b_net);
$b_stm=iconv('UTF-8','windows-874',$TaxStamp);  
$pdf->Text(116,159,$b_stm);
$b_vat=iconv('UTF-8','windows-874',$Vat);  
$pdf->Text(140,159,$b_vat);
$b_all=iconv('UTF-8','windows-874',$Premium); 
$pdf->Text(172,159,$b_all);

$use=iconv('UTF-8','windows-874',"ใช้เป็นรถส่วนบุคคล หรือ รับจ้าง หรือ ให้เช่า");   //Fix
$pdf->Text(55,167,$use);

$cur_date=iconv('UTF-8','windows-874',$conv_nowdate);   //วันทำสัญญา
$pdf->Text(40,185,$cur_date);
$cur_date=iconv('UTF-8','windows-874',$conv_nowdate); 
$pdf->Text(140,185,$cur_date);

$user_bc=iconv('UTF-8','windows-874',$C_REGIS);   //User
$pdf->Text(81,241,$user_bc);
$user_cn=iconv('UTF-8','windows-874',$C_CARNUM);
$pdf->Text(150,241,$user_cn);
$user_date_st=iconv('UTF-8','windows-874',$conv_start_date);
$pdf->Text(30,254,$user_date_st);
$user_date_end=iconv('UTF-8','windows-874',$conv_end_date);
$pdf->Text(115,254,$user_date_end);

$pdf->Output();
?>