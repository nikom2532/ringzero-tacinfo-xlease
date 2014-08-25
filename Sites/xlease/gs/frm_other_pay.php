<?php 
include("../config/config.php"); 
$date_check = pg_escape_string($_POST['date_check']);

if(empty($date_check)){
    $show_cdate = date("Y-m-d");   
}else{
    $show_cdate = $date_check;
}
?> 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
	<title>AV.LEASING</title>
	<meta http-equiv="Content-Type" content="txt/html; charset=utf-8" />
    <link type="text/css" rel="stylesheet" href="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.css?random=20051112" media="screen"></LINK>
    <script type="text/javascript" src="../dhtmlgoodies_calendar/dhtmlgoodies_calendar/dhtmlgoodies_calendar.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="act.css"></link>  
</head>
<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="left">
	<tr>
		<td>

<div class="header"><h1>ระบบทะเบียนรถ</h1></div>
<div class="wrapper">
 
<fieldset><legend><b>ใส่ข้อมูลการชำระเงินค่าอื่นๆ</b></legend>

<form method="post" action="" name="f_list" id="f_list">
<div align="left">
<b>เลือกวันที่</b>
<input name="date_check" id="date_check" type="text" readonly="true" size="11" value="<?php echo $show_cdate; ?>" style="text-align:center;"/><input name="button" type="button" onclick="displayCalendar(document.f_list.date_check,'yyyy-mm-dd',this)" value="ปฏิทิน"/><input type="submit" name="submit" value="ค้นหา">
</div>
</form>

<table width="100%" border="0" cellSpacing="1" cellPadding="2" align="center" bgcolor="#F0F0F0">
    <tr style="font-weight:bold;" valign="middle" bgcolor="#79BCFF">
        <td align="center">IDNO</td>
        <td align="center">ชื่อ</td>
        <td align="center">ทะเบียน</td>
        <td align="center">วันที่</td>
        <td align="center">จำนวนเงิน</td>
        <td align="center">รูปแบบ</td>
        <td align="center">วันนัด</td>
        <td align="center">สถานะ</td>
    </tr>
   
<?php
if( isset($date_check) ){

        $qry_name=pg_query("SELECT * FROM carregis.\"CarTaxDue\" where (\"ApointmentDate\" = '$date_check') AND (\"TypeDep\" is not null) AND \"TaxValue\" is null ORDER BY \"IDNO\" ASC ");
        $rows = pg_num_rows($qry_name);
        while($res_name=pg_fetch_array($qry_name)){
            $IDCarTax = $res_name["IDCarTax"];
            $IDNO = $res_name["IDNO"];
            $CusAmt = $res_name["CusAmt"];
            $TypeDep = $res_name["TypeDep"];
            $ApointmentDate = $res_name["ApointmentDate"];
            $TaxValue = $res_name["TaxValue"];
            $TaxDueDate = $res_name["TaxDueDate"];
                $TaxDueDate = date("Y-m-d",strtotime($TaxDueDate));
            
        $qry_name2=pg_query("select * from \"VContact\" WHERE \"IDNO\"='$IDNO' ");
        if($res_name2=pg_fetch_array($qry_name2)){
            $asset_id = $res_name2["asset_id"];
            $full_name = $res_name2["full_name"];
            $asset_type = $res_name2["asset_type"];
            $C_REGIS = $res_name2["C_REGIS"];
            $car_regis = $res_name2["car_regis"];
                if($asset_type == 1){ $show_regis = $C_REGIS; } else { $show_regis = $car_regis; }
        }else{
            $full_name = "ไม่พบข้อมูล";
            $show_regis = "ไม่พบข้อมูล";
        }
        
        $TName = "";
        $pieces = explode(",", $TypeDep);
        for($i=0; $i<count($pieces);$i++){
                $get_type = $pieces[$i];
                $qry_name4=pg_query("select * from \"TypePay\" WHERE \"TypeID\"='$get_type' ");
                if($res_name4=pg_fetch_array($qry_name4)){
                    if(count($pieces) == $i+1){  
                        $TName .= $res_name4["TName"];
                    }else{
                        $TName .= $res_name4["TName"].",";
                    }
                }
        }
        
        $in+=1;
        if($in%2==0){
            echo "<tr class=\"odd\">";
        }else{
            echo "<tr class=\"even\">";
        }
?>
        <td align="center"><?php echo "$IDNO"; ?></td>
        <td align="left"><?php echo "$full_name"; ?></td>
        <td align="left"><?php echo "$show_regis"; ?></td>
        <td align="center"><?php echo "$TaxDueDate"; ?></td>
        <td align="right"><?php echo number_format($CusAmt,2); ?></td>
        <td align="left"><?php echo "$TName"; ?></td>
        <td align="center"><?php echo "$ApointmentDate"; ?></td>
        <td align="center">
            <?php if( empty($TaxValue) ){ ?>
                <a href="frm_other_pay_add.php?cid=<?php echo "$IDCarTax";?>"><img src="add.png" border="0" width="16" height="16" align="absmiddle" alt="ใส่ข้อมูลการชำระเงิน"></a>
            <?php } ?>
        </td>
    </tr>
 <?php
        }
}

if($rows > 0){

 ?>
    <tr bgcolor="#ffffff" style="font-size:11px;">
        <td align="left" colspan="10"><b>ทั้งหมด</b> <?php echo $rows; ?> <b>รายการ</b></td>
    </tr>                                                                      
<?php } ?>
</table>

</div>
		</td>
	</tr>
</table>

</body>
</html>