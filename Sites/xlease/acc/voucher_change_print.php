<?php
include("../config/config.php");

$id = pg_escape_string($_GET['id']);
$nowdate = Date('Y-m-d');

$qry_name=pg_query("SELECT * FROM account.tal_voucher WHERE \"vc_id\"='$id' ");
if($res_name=pg_fetch_array($qry_name)){
    $vc_type = $res_name["vc_type"];
    $vc_detail = $res_name["vc_detail"]; $vc_detail = explode("\n",$vc_detail);
    $cash_amt = $res_name["cash_amt"];
    $acid_bank = $res_name["acid_bank"];
    $cq_id = $res_name["cq_id"];
    $cq_date = $res_name["cq_date"];
    $cq_amt = $res_name["cq_amt"];
    $maker_id = $res_name["maker_id"];
    $print_date = $res_name["print_date"];
    $amt_change = $res_name["amt_change"];
    $VenderID = $res_name["VenderID"];
    
    $sum_amt = $cash_amt+$cq_amt;
    $amount_all = $sum_amt + ($amt_change);
    
    $qry_name2=pg_query("SELECT \"fullname\" FROM \"Vfuser\" WHERE \"id_user\"='$maker_id' ");
    if($res_name2=pg_fetch_array($qry_name2)){
        $fullname = $res_name2["fullname"];
    }
    
    $qry_name2=pg_query("SELECT * FROM account.\"vender\" WHERE \"VenderID\"='$VenderID' ");
    if($res_name2=pg_fetch_array($qry_name2)){
        $vd_name = $res_name2["vd_name"];
        $type_vd = $res_name2["type_vd"];
    }

}

//------------------- PDF -------------------//
require('../thaipdfclass.php');

class PDF extends ThaiPDF
{
    function Header(){
    }
}

$pdf=new PDF('P' ,'mm','a4');
$pdf->SetLeftMargin(0);
$pdf->SetTopMargin(0);
$pdf->AliasNbPages( 'tp' );
$pdf->SetThaiFont();
$pdf->AddPage();

$cline = 150;

$pdf->SetFont('AngsanaNew','B',16);
$pdf->SetXY(10,$cline);
$title=iconv('UTF-8','windows-874',"รับเงินเข้า");
$pdf->MultiCell(191,10,$title,0,'R',0);

$pdf->SetFont('AngsanaNew','',13);

$pdf->SetXY(10,$cline+3.5);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,6,$buss_name,0,'L',0);
$cline += 8.5;

/*

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่พิมพ์ $nowdate");
$pdf->MultiCell(191,6,$buss_name,0,'R',0);
$cline += 6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่เบิก : $print_date");
$pdf->MultiCell(50,6,$buss_name,0,'L',0);
$cline += 6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"รายละเอียด : ");
$pdf->MultiCell(30,6,$buss_name,0,'L',0);

$pdf->SetXY(40,$cline);
$buss_name=iconv('UTF-8','windows-874',"$vc_detail[0]");
$pdf->MultiCell(160,6,$buss_name,0,'L',0);
$cline += 6;

if(!empty($cash_amt)){
    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"เบิกเงินสด : ".number_format($cash_amt,2)." บาท");
    $pdf->MultiCell(191,6,$buss_name,0,'R',0);
    $cline += 6;
}

if(!empty($cq_id)){
    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"เบิกเช็ค : เลขที่ $cq_id ธนาคาร $acid_bank วันที่บนเช็ค $cq_date ยอดเงิน ".number_format($cq_amt,2)." บาท");
    $pdf->MultiCell(191,6,$buss_name,0,'R',0);
    $cline += 6;
}

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"รวมเงินที่เบิก : ". number_format($cash_amt+$cq_amt,2) ." บาท");
$pdf->MultiCell(191,6,$buss_name,0,'R',0);

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"เจ้าหน้าที่ทำรายการเบิก : $fullname");
$pdf->MultiCell(191,6,$buss_name,0,'L',0);
$cline += 1;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,6,$buss_name,0,'L',0);
$cline += 6;

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"รับเงินเข้า");
$pdf->MultiCell(150,6,$buss_name,0,'L',0);
$cline += 6;
*/

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"เลขที่สำคัญ : $id");
$pdf->MultiCell(50,6,$buss_name,0,'L',0);

if($amt_change > 0){
    $text_show = "เบิกเงินเพิ่ม";
}elseif($amt_change < 0){
    $text_show = "เงินทอน";
}

$amt_change_abs = abs($amt_change);

if($amt_change_abs != 0){
    $pdf->SetFont('AngsanaNew','',13);
    $pdf->SetXY(10,$cline);
    $buss_name=iconv('UTF-8','windows-874',"$text_show : $amt_change_abs บาท");
    $pdf->MultiCell(191,6,$buss_name,0,'R',0);
}

$cline += 6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"วันที่รับเข้า $nowdate");
$pdf->MultiCell(50,6,$buss_name,0,'L',0);

$pdf->SetFont('AngsanaNew','B',15);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"ยอดเงินใช้จริง : ".number_format($amount_all,2)." บาท");
$pdf->MultiCell(191,6,$buss_name,0,'R',0);
$cline += 1;

$pdf->SetFont('AngsanaNew','',13);
$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"_____________________________________________________________________________________________________________________________");
$pdf->MultiCell(196,6,$buss_name,0,'L',0);
$cline += 6;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"ลงชื่อ _________________________ ผู้เบิก");
$pdf->MultiCell(55,6,$buss_name,0,'L',0);

$pdf->SetXY(75,$cline);
$buss_name=iconv('UTF-8','windows-874',"ลงชื่อ _________________________ ผู้อนุมัติ");
$pdf->MultiCell(60,6,$buss_name,0,'C',0);

$pdf->SetXY(147,$cline);
$buss_name=iconv('UTF-8','windows-874',"ลงชื่อ _________________________ บัญชี");
$pdf->MultiCell(55,6,$buss_name,0,'R',0);
$cline += 5;

$pdf->SetXY(10,$cline);
$buss_name=iconv('UTF-8','windows-874',"(  $type_vd $vd_name  )");
$pdf->MultiCell(54,6,$buss_name,0,'C',0);

$pdf->SetXY(75,$cline);
$buss_name=iconv('UTF-8','windows-874',"(                                                                     )");
$pdf->MultiCell(60,6,$buss_name,0,'C',0);

$pdf->SetXY(148,$cline);
$buss_name=iconv('UTF-8','windows-874',"(                                                                  )");
$pdf->MultiCell(54,6,$buss_name,0,'C',0);

$pdf->Output();
?>