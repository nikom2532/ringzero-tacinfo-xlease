<?php 
include("../../config/config.php");
    set_time_limit(120);

	$txt_voucher = pg_escape_string($_REQUEST['txt_voucher']);
	$s_date = pg_escape_string($_REQUEST['s_date']);
	$s_month = pg_escape_string($_REQUEST['s_month']);
	$s_year = pg_escape_string($_REQUEST['s_year']);
	$s_datefrom = pg_escape_string($_REQUEST['s_datefrom']);
	$s_dateto = pg_escape_string($_REQUEST['s_dateto']);
	$s_sel_year = pg_escape_string($_REQUEST['s_sel_year']);//ปีที่ค้นหา
	$s_valuee = pg_escape_string($_REQUEST['s_value']);
	$s_detail = pg_escape_string($_REQUEST['s_detail']);
	$s_cancel = $_REQUEST['s_cancel'];
	$s_purpose_idx = pg_escape_string($_REQUEST['s_purpose_idx']);  // เลขรหัสจุดประสงค์ ของใบสำคัญ
	$s_chk_detail = pg_escape_string($_REQUEST['s_chk_detail']); // สถานะการเลือก  "ตามรายละเอียด "
	$s_chk_purpose = pg_escape_string($_REQUEST['s_chk_purpose']); // สถานะการเลือก "จุดประสงค์"
	
	if($s_cancel == "on"){	//ค้นหารายการที่  แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก ด้วย
		$condition_c = "";
	}else{					//ไม่ค้นหารายการที่  แสดงรายการที่ยกเลิก/รายการปรับปรุงยกเลิก 
		if($s_valuee=="4"){
			$condition_c = " where a.\"voucherStatus\" = '1' and a.\"voucherAdjustCancelFor\" is null ";
		}else{
			$condition_c = " and a.\"voucherStatus\" = '1' and a.\"voucherAdjustCancelFor\" is null ";
		}
	}                     
	$qry ="select a.* from v_thcap_temp_voucher_details_journal a  "; // Define Part Of SQL Command
	
	// Define Part Of SQL Comand ที่มาจากเลื่อนไขหลัก
	if($s_valuee=="0"){ // ค้นหา "Voucher ID"
		$Cnd1 = " where a.\"voucherID\"='$txt_voucher' $condition_c ";
	}else if($s_valuee=="1"){ // ค้นหา  "วันที่"
		$Cnd1 = " where a.\"voucherDate\"='$s_date' $condition_c ";
	}else if($s_valuee=="2"){ // ค้นหา "ตามเดือน"
		$Cnd1 = " where EXTRACT(MONTH FROM a.\"voucherDate\")='$s_month' and EXTRACT(YEAR FROM a.\"voucherDate\")='$s_year' $condition_c ";
	}else if($s_valuee=="3"){ // ค้นหา  "ตามช่วง"
		$Cnd1 .= " where a.\"voucherDate\" between '$s_datefrom' and '$s_dateto' $condition_c ";
	}else if($s_valuee=="4"){ //ค้นหา "ทัั้งหมด" 
		$Cnd1 = "$condition_c";
	}else if($s_valuee=="5"){ //เค้นหา  "ตามปี"
		$Cnd1 = " where EXTRACT(YEAR FROM a.\"voucherDate\")='$s_sel_year' $condition_c ";
	}else if($s_valuee=="6"){ //ค้นหา ."ตามรายละเอียด"   จาก code เดิม ไม่ใช้งาน
		$Cnd1 .= " where \"voucherRemark\" like '%$s_detail%' $condition_c ";
	}	
	
	// Define Part Of SQL Comand ที่มาจาก "เงื่อนไขรอง"
	if($s_chk_detail=="on"){ // กรณีที่เลือก การค้นหาตามรายละเอียด
	   $Cnd2 =  "  \"voucherRemark\" like '%$s_detail%' ";   
	} 
	if($s_chk_purpose=="on"){ //กรณีที่เลือกการค้นหาตามวัตถุประสงค์
	   if(strlen($Cnd2)>0){
	   $Cnd2 = $Cnd2." and  \"voucherPurpose\"  =	".$s_purpose_idx; 
	   }else{
	    $Cnd2 = $Cnd2." \"voucherPurpose\"  =	".$s_purpose_idx;
	   }
	} 
	
	//  เพิ่มเติกม การ Define Part Of SQL Comand ที่มาจาก "เงื่อนไขรอง" เพื่อการสร้าง SQL Comand
	if(strlen($Cnd1)==0){ 
	  if(strlen($Cnd2)>0){ 
	     echo "Cnd2 > 0";
	     $Cnd2 = " WHERE ".$Cnd2;
	   }
	}else{ 
	   if(strlen($Cnd2)>0){
	     $Cnd2 = " and ".$Cnd2;
	   }
	
	} 
	
    // SQL Comand For Control Order Data
	$qry_end = " order by a.\"voucherDate\", a.\"voucherTime\", a.\"voucherID\" ";
	
    // Create Complement SQL Comand For Query	
	$qry = $qry.$Cnd1.$Cnd2.$qry_end;
	
	$method = 0; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
    <title>(THCAP) ใบสำคัญรายวันทั่วไป</title>
    <meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="act.css"></link>

    <link type="text/css" href="../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="../../jqueryui/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>
</head>
<body>

<form name="frm" action="pdf_payment_voucher.php" method="post" target="_blank">
	<div style="margin-top:10px;"align="center">
		<table cellpadding="5" cellspacing="0" border="0" width="80%" bgcolor="#F0F0F0" align="center">
			<tr bgcolor="white">
				<td colspan="3" align="left"><font size="3" color="blue"><b>รายการใบสำคัญรายวันทั่วไป</b></font></td>
				<td colspan="7" align="right">
					<input type="button" name="cancel" id="cancel" value="ขอยกเลิก" onclick="validate(this.form,'C');"/>
					<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF" onclick="validate(this.form,'PDF');"/>
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#BEBEBE" align="center">
				<td>ลำดับที่</td>
				<td>รหัสใบสำคัญรายวันทั่วไป</td>
				<td>วันที่ใบสำคัญรายวันทั่วไป</td>
				<td>เวลาใบสำคัญรายวันทั่วไป</td>
				<td>ผลรวม</td>
				<td>เลขที่บันทึกบัญชี</td>
				<td>ผู้ำทำรายการ</td>
				<td>วันเวลาที่ทำรายการ</td>
				<td><span id="selectAll" style="cursor:pointer;"><u><font color="blue">เลือกรายการ</font></u></span></td>
				<td>หมายเหตุ</td>
			</tr>
			<?php 
				$query_list = pg_query($qry);
				$num_row = pg_num_rows($query_list);
				if($num_row>0){
					$i = 0;
					while($res_v = pg_fetch_array($query_list)){
						$i++;
						$sum_debit=0;
						$voucherID = $res_v['voucherID'];
						$voucherDate = $res_v['voucherDate'];
						$doerFull = $res_v['doerFull'];
						$doerStamp = $res_v['doerStamp'];
						$abh_id = $res_v['abh_id'];
						$voucherTime = $res_v['voucherTime'];
						$voucherCancelRef = $res_v['voucherCancelRef'];
						$voucherStatus = $res_v['voucherStatus'];
						$voucherAdjustCancelFor = $res_v['voucherAdjustCancelFor'];//not null คือ ปรับปรุงยกเลิก
						//เพิ่มรายละเอียด ในบรรทัดใหม่ #6771
						$voucherRemark = $res_v['voucherRemark'];
						
						$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
						$abh_autoid = pg_fetch_result($qry_bookhead,0);
						
						$qry_concurrent = pg_query("select \"voucherID\" from thcap_temp_voucher_cancel where \"voucherID\"='$voucherID' and \"appvStatus\"='9' ");
						$num = pg_num_rows($qry_concurrent);
						
						if($num>0){
							$textStatus = "รออนุมัติยกเลิก";
							$Fcolor = "FF8000";
						}else if($voucherStatus == '0'){
							$textStatus = "ยกเลิกแล้ว";
							$Fcolor = "red";
						}else if($voucherAdjustCancelFor != ''){
							$textStatus = "ปรับปรุงยกเลิก";
							$Fcolor = "#CD853F";
								
						}else{
							$textStatus = "";
						}
						//หาผลรวม เดบิต
						$qry_bookhead = pg_query("select abh_autoid from account.\"all_accBookHead\" where abh_refid = '$voucherID'");
						$abh_autoid = pg_fetch_result($qry_bookhead,0);						
						$qry_detail = pg_query("select sum(\"abd_amount\") as \"sum_amount\" from account.\"all_accBookDetail\" 
						where abd_autoidabh='$abh_autoid' and \"abd_bookType\" ='1' ");
						$sum_debit = pg_fetch_result($qry_detail,0);						
						$sum_debit = number_format($sum_debit,2);
						
					$bgcolor="";	
					if($i%2==0){	
						if($voucherStatus == "0"){
							echo "<tr bgcolor=\"#CCCCCC\" >";
							$bgcolor="#CCCCCC";
						}else if($voucherAdjustCancelFor != ''){
							echo "<tr bgcolor=\"#FFFFCC\" >";
							$bgcolor="#FFFFCC";
						}else{
							echo "<tr bgcolor=\"#EDF8FE\" >";
							$bgcolor="#EDF8FE";
						}
					}else{
						if($voucherStatus == "0"){
							echo "<tr bgcolor=\"#CCCCCC\" >";
							$bgcolor="#CCCCCC";
						}else if($voucherAdjustCancelFor != ''){
							echo "<tr bgcolor=\"#FFFFCC\" >";
							$bgcolor="#FFFFCC";
						}else{
							echo "<tr>";
						}
					}
							echo "<td align=\"center\">$i</td>";
							echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('voucher_channel_detail.php?voucherID=$voucherID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=720');\" style=\"cursor:pointer;\">$voucherID</a></u></font></td>";
							echo "<td align=\"left\">$voucherDate</td>";
							echo "<td align=\"left\">$voucherTime</td>";
							echo "<td align=\"right\">$sum_debit</td>";
							echo "<td align=\"left\"><font color=\"blue\"><u><a onclick=\"javascript:popU('../accountEdit/frm_account_show.php?abh_autoid=$abh_autoid','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=980,height=550');\" style=\"cursor:pointer;\">$abh_id</a></u></font></td>";
							echo "<td align=\"left\">$doerFull</td>";
							echo "<td align=\"left\">$doerStamp</td>";
							echo "<td align=\"center\"><input type=\"checkbox\" name=\"select_print[]\" id=\"select_print$i\" value=\"$voucherID\"></td>";
							echo "<td align=\"center\"><font color=\"$Fcolor\">$textStatus</font></td>";
						echo "</tr>";
						
						// if($s_valuee=="6"){
						//format เลขที่สัญญา xx-xxxx-xxxxxxx	และ เลขที่สัญญา xx-xxxx-xxxxxxx/xxxx
						$fromChannelDetails_format = '/(\w{2})-(\w{2})(\d{2})-(\d{7})(\/\d{4})?/';
						$fromChannelDetails_popup = "<span onclick=\"javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=".'\1-\2\3-\4\5'."','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')\" style=\"cursor:pointer;\"><font color=\"red\"><u>".'\1-\2\3-\4\5'."</u></font></span>";
		
						//รายละเอียด
						$voucherRemark = preg_replace($fromChannelDetails_format,$fromChannelDetails_popup,$voucherRemark);
						echo "<tr bgcolor=$bgcolor><td colspan=\"10\"><b>รายละเอียด:</b> $voucherRemark</tr>";
						// }
					}
				}else{
					echo "<tr><td colspan=\"10\" align=\"center\">ไม่พบข้อมูลที่ค้นหา</td></tr>";
				}
			?>
			<tr cellspacing="10px" bgcolor="#BEBEBE">
				<td colspan="10" align= "right">
					<input type="hidden" id="AllorClear" value="A"/>
					<input type="button" name="cancel" id="cancel" value="ขอยกเลิก" onclick="validate(this.form,'C');"/>
					<input type="button" name="PrintPDF" id="PrintPDF" value="PrintPDF" onclick="validate(this.form,'PDF');"/>
				</td>
			</tr>
		</table>
	</div>
</form>

</body>
<script>
$("#selectAll").click(function(){
	var select = $("input[name=select_print[]]");
	var chkBT = $("#AllorClear").val();
	var num = 0;
	
	if(chkBT=="A"){
		for(i=0; i<select.length; i++){
			$(select[i]).attr("checked","checked");
		}
		$("#AllorClear").val('C');
	}else{
		for(i=0; i<select.length; i++){
			$(select[i]).removeAttr("checked");
		}
		$("#AllorClear").val('A');
	}
});

function validate(frm,method){
	
	var select = $("input[name=select_print[]]:checked");
	var ErrorMessage = "Error Message! \n";
	var Error = 0;
	if(select.length<1){
		ErrorMessage += "กรุณาเลือกรายการที่ต้องการ Print";
		Error++;
	}

	if(Error>0){
		alert(ErrorMessage);
		return false;
	}else{
		if(method == "PDF"){			
			frm.action="pdf_journal_voucher.php";
			frm.submit();
		}else if(method == "C"){
			frm.action="cancel_journal_voucher.php";
			frm.submit();
		}
	} 
}
</script>
</html>	