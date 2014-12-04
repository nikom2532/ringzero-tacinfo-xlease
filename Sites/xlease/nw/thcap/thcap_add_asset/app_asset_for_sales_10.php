<?php 
include("../../../config/config.php");
$astype = $_GET["astype"];

//-=============================================================
//	อนุมัติการเพิ่มรายละเอียดของสินค้าประเภทรถจักรยายนต์
//-=============================================================
		$i = 0;
		$qry_motorcycle = pg_query("
									SELECT a.*,b.*,c.*,d.*,e.\"fullname\"
									FROM thcap_asset_biz_detail a
									LEFT JOIN \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
									LEFT JOIN \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
									LEFT JOIN (	select * from \"thcap_asset_biz_detail_10_temp\" d1
												left join \"thcap_asset_biz_detail_central\" d2
												on d1.\"ascenID\" = d2.\"ascenID\" ) d ON a.\"assetDetailID\" = d.\"assetDetailID\"
									LEFT JOIN \"Vfuser\" e ON d.\"doerID\" = e.\"id_user\"
									WHERE d.\"statusapp\" = '0' AND a.\"astypeID\" = '$astype' 
									ORDER by d.\"doerDate\" DESC
								  ");
		$rows = pg_num_rows($qry_motorcycle);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>(THCAP) อนุมัติรายละเอียดสินทรัพย์สำหรับเช่า-ขาย</title>
<link type="text/css" rel="stylesheet" href="../act.css"></link>
<link type="text/css" href="../../../jqueryui/css/ui-lightness/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<script type="text/javascript" src="../../../jqueryui/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="../../../jqueryui/js/jquery-ui-1.8.2.custom.min.js"></script>   
<script type="text/javascript">
function popU(U,N,T) {
    newWindow = window.open(U, N, T);
}
</script>
</head>
<body >
<center>
	<div style="padding-top:30px;"></div>
	<fieldset style="width:60%;">
		<legend><b>รายการอนุมัติ รถจักรยานยนต์</b></legend>
				<table width="100%" frame="box" bgcolor="#FFFAFA">
					<tr>
						<td>
							<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
								<tr bgcolor="#CDC5BF">
									<th >ยี่ห้อ</th>
									<th >รุ่น</th>
									<th >รหัสรุ่น </th>
									<th >เลขเครื่อง </th>
									<th >ผู้ทำรายการ </th>
									<th >วันเวลาที่ทำรายการ </th>
									<th >การกระทำ</th>
									<th >ตรวจสอบ</th>
								</tr>
								
								<?php
							
									
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											$assetDetailID = $result["assetDetailID"];											
											$temp_10_ID = $result["temp_10_ID"];
											$fullnameuserdoer = $result["fullname"];
											$ascenID = $result["ascenID"];
											
											IF($result["add_or_edit"] == '0'){
												$statustxt = 'เพิ่มข้อมูล';
											}else{
												$statustxt = 'แก้ไขครั้งที่ '.$result["add_or_edit"];
											}
											
											$i++;
											if($i%2==0){
												echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
											}else{
												echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
											}	
											echo "
													<td align=\"center\">".$result["brand_name"]."</td>
													<td align=\"center\">".$result["model_name"]."</td>
													<td align=\"center\">".$result["secondaryID"]."</td>
													<td align=\"center\">".$result["productCode"]."</td>
													<td align=\"center\">$fullnameuserdoer</td>	
													<td align=\"center\">".$result["doerDate"]."</td>	
													<td align=\"center\"><font color=\"red\">".$statustxt."</font></td>		
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('add_asset_for_sales_10.php?ascenID=$ascenID&readonly=t&appv=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
												</tr>
												";	
											
										}
										echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"8\">รวม $rows รายการ</td></tr>";		
									}else{
										echo "<tr><td colspan=\"8\" align=\"center\">*** ไม่มีข้อมูล ***</td></tr>";
									}
								?>
							</table>
						</td>
					</tr>
				</table>	
			</fieldset>
<div style="padding-top:80px;"></div>

		<fieldset style="width:60%;">
		<legend><b>ประวัติรายการอนุมัติ รถจักรยานยนต์</b></legend>
				<table width="100%" frame="box" bgcolor="#FFFAFA">
					<tr>
						<td>
							<table width="100%" cellspacing="1" cellpadding="1" style="margin-top:1px" align="center">
								<tr bgcolor="#CDC5BF">
									<th >ยี่ห้อ</th>
									<th >รุ่น</th>
									<th >รหัสรุ่น </th>
									<th >เลขเครื่อง </th>
									<th >ผู้ทำรายการ </th>
									<th >วันเวลาที่ทำรายการ </th>
									<th >ผู้อนุมัติ </th>
									<th >วันเวลาที่อนุมัติ</th>
									<th >การกระทำ</th>
									<th >ดู</th>									
									<th >สถานะ</th>
									<th >เหตุผล</th>
								</tr>
								<?php
									$i = 0;
									$qry_motorcycle = pg_query("
																SELECT a.*,b.*,c.*,d.*,e.\"fullname\" as fulldoer,f.\"fullname\" as fullapp
																FROM thcap_asset_biz_detail a
																LEFT JOIN \"thcap_asset_biz_model\" b ON a.\"model\" = b.\"modelID\"
																LEFT JOIN \"thcap_asset_biz_brand\" c ON a.\"brand\" = c.\"brandID\"
																LEFT JOIN (	select * from \"thcap_asset_biz_detail_10_temp\" d1
																			left join \"thcap_asset_biz_detail_central\" d2
																			on d1.\"ascenID\" = d2.\"ascenID\" ) d ON a.\"assetDetailID\" = d.\"assetDetailID\"
																LEFT JOIN \"Vfuser\" e ON d.\"doerID\" = e.\"id_user\"
																LEFT JOIN \"Vfuser\" f ON d.\"appID\" = f.\"id_user\"
																WHERE d.\"statusapp\" != '0' AND a.\"astypeID\" = '$astype'
																ORDER by d.\"appDate\" DESC
																LIMIT 30
															  ");
									$rows = pg_num_rows($qry_motorcycle);
									
									if($rows){
										while($result = pg_fetch_array($qry_motorcycle)){
											$assetDetailID = $result["assetDetailID"];											
											$temp_10_ID = $result["temp_10_ID"];
											$statusapp = $result["statusapp"];
											$fullnameuserdoer = $result["fulldoer"];
											$fullnameuserapp = $result["fullapp"];
											$ascenID = $result["ascenID"];
											if($statusapp == '1'){
												$txtstatus = 'อนุมัติ';
											}else{
												$txtstatus = 'ไม่อนุมัติ';
												$note = "<img style=\"cursor:pointer;\" onclick=\"popU('frm_note_reprint.php?ascenID=$ascenID','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=550,height=250')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\">";
											}
											IF($result["add_or_edit"] == '0'){
												$statustxt1 = 'เพิ่มข้อมูล';
											}else{
												$statustxt1 = 'แก้ไขครั้งที่ '.$result["add_or_edit"];
											}
											
											$i++;
											if($i%2==0){
												echo "<tr bgcolor=#EEE5DE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEE5DE';\" align=center>";
											}else{
												echo "<tr bgcolor=#FFF5EE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#FFF5EE';\" align=center>";
											}	
											echo "
													<td align=\"center\">".$result["brand_name"]."</td>
													<td align=\"center\">".$result["model_name"]."</td>
													<td align=\"center\">".$result["secondaryID"]."</td>
													<td align=\"center\">".$result["productCode"]."</td>
													<td align=\"center\">$fullnameuserdoer</td>	
													<td align=\"center\">".$result["doerDate"]."</td>
													<td align=\"center\">$fullnameuserapp</td>	
													<td align=\"center\">".$result["appDate"]."</td>
													<td align=\"center\"><font color=\"red\">".$statustxt1."</font></td>
													<td align=\"center\"><img style=\"cursor:pointer;\" onclick=\"popU('add_asset_for_sales_10.php?ascenID=$ascenID&readonly=t','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=900,height=600')\" src=\"../images/detail.gif\" width=\"20px;\" height=\"20px;\"></td>	
													<td align=\"center\">$txtstatus</td>
													<td align=\"center\">$note</td>
												
												</tr>
												";	
											unset($note);
											unset($txtstatus);
										}
										echo "<tr bgcolor=\"#CDC5BF\"><td colspan=\"12\">รวม $rows รายการ</td></tr>";		
									}else{
										echo "<tr><td colspan=\"12\" align=\"center\">*** ไม่มีข้อมูล ***</td></tr>";
									}
								?>
							</table>
						</td>
					</tr>	
				</table>	
			</fieldset>	

</center>				
</body>
</html>