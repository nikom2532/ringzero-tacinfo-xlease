<?php
echo "<div style=\"padding-top:50px\"></div>";
?>
<table width="1100" border="0" cellspacing="0" cellpadding="0" style="margin-top:1px" align="center">
<tr>
	<td>
		<div class="wrapper">
		<table width="100%" border="0" cellSpacing="1" cellPadding="3" align="center" bgcolor="#FFFFFF">
			<tr bgcolor="#FFFFFF">
				<td colspan="12" align="left" style="font-weight:bold;">
				ประวัติการอนุมัติยกเลิกใบกำกับภาษี 30 รายการล่าสุด (<a style="color:#0099FF;cursor:pointer;" onclick="javascript:popU('frm_hisroty_appdetail.php','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1400,height=650')"><u>ทั้งหมด</u></a>) 
				</td>
			</tr>
			<tr style="font-weight:bold;" valign="middle" bgcolor="#D6D6D6" align="center">
				<td>เลขที่สัญญา</td>
				<td>เลขที่ใบกำกับภาษี</td>
				<td>วันที่ใบกำกับภาษี</td>
				<td>จำนวนเงิน</td>
				<td>ผู้ขอยกเลิก</td>
				<td>วันเวลาที่ทำการขอยกเลิก</td>
				<td>ผู้อนุมัติ</td>
				<td>วันที่อนุมัติ</td>	
				<td>ผลการอนุมัติ</td>
                <td>เหตุผล</td>			
			</tr>
			<?php			
			$qry_app=pg_query("	
								SELECT a.\"cancelTaxID\",a.\"contractID\", a.\"taxinvoiceID\",b.\"fullname\", a.\"approveStatus\",a.\"requestDate\", a.\"approveDate\"
								FROM thcap_temp_taxinvoice_cancel a 
								LEFT JOIN \"Vfuser\" b on a.\"approveUser\" = b.\"id_user\" 
								WHERE a.\"approveStatus\"<>'2'
								ORDER BY a.\"approveDate\" DESC limit 30
							");
			$nub=pg_num_rows($qry_app);
			while($res_app=pg_fetch_array($qry_app)){
				$cancelTaxID=$res_app["cancelTaxID"];
				$contract=$res_app["contractID"];
				$taxinvoiceID1=$res_app["taxinvoiceID"];
				$approveStatus=$res_app["approveStatus"];
				$requestDate=$res_app["requestDate"];
				$approveDate=$res_app["approveDate"];
				$approveUser=$res_app["fullname"];
				
				//หาว่าใบกำกับภาษีนี้ถูกลบหรือยังจากตาราง  thcap_v_taxinvoice_otherpay
				$qrychk=pg_query("SELECT * FROM thcap_v_taxinvoice_otherpay WHERE \"taxinvoiceID\"='$taxinvoiceID1'");
				$nubchk=pg_num_rows($qrychk);
				//หาข้อมูลที่เหลือออกมาแสดง 
				if($nubchk>0){ //แสดงว่ายังไม่ถูกลบ
					$qry_receipt2=pg_query("	SELECT a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",f.\"receiveAmount\",e.\"debtID\",e.\"typePayID\"
												FROM \"thcap_temp_taxinvoice_cancel\" a
												LEFT JOIN \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
												LEFT JOIN \"thcap_v_taxinvoice_otherpay\" e on a.\"taxinvoiceID\"=e.\"taxinvoiceID\"
												LEFT JOIN (		select aa1.\"taxinvoiceID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
																from \"thcap_v_taxinvoice_otherpay\" aa1
																left join \"thcap_temp_taxinvoice_cancel\" bb1 on aa1.\"taxinvoiceID\" = bb1.\"taxinvoiceID\"
																where bb1.\"cancelTaxID\" = '$cancelTaxID'
																group by aa1.\"taxinvoiceID\"
														  ) f on f.\"taxinvoiceID\" = a.\"taxinvoiceID\"
												WHERE a.\"cancelTaxID\"='$cancelTaxID'
												ORDER BY \"approveDate\" DESC
											");				
				}else{ //กรณีถูกลบ
					$qry_receipt2=pg_query("	SELECT a.\"cancelTaxID\",a.\"contractID\",a.\"taxinvoiceID\",\"fullname\",\"requestDate\",e.\"taxpointDate\",f.\"receiveAmount\",e.\"debtID\",e.\"typePayID\"
												FROM \"thcap_temp_taxinvoice_cancel\" a
												LEFT JOIN \"Vfuser\" b on a.\"requestUser\"=b.\"id_user\"
												LEFT JOIN \"thcap_v_taxinvoice_otherpay_cancel\" e on a.\"taxinvoiceID\"=e.\"taxinvoiceID\"
												LEFT JOIN (		select aa1.\"taxinvoiceID\",sum(aa1.\"debtAmt\") as \"receiveAmount\"
																from \"thcap_v_taxinvoice_otherpay_cancel\" aa1
																left join \"thcap_temp_taxinvoice_cancel\" bb1 on aa1.\"taxinvoiceID\" = bb1.\"taxinvoiceID\"
																where bb1.\"cancelTaxID\" = '$cancelTaxID'
																group by aa1.\"taxinvoiceID\"
														  ) f on f.\"taxinvoiceID\" = a.\"taxinvoiceID\"
												WHERE a.\"cancelTaxID\"='$cancelTaxID'
												ORDER BY \"approveDate\" DESC
					
										   ");
				}
				$res_receipt2=pg_fetch_array($qry_receipt2);
				$cancelTaxID=$res_receipt2["cancelTaxID"];
				$contractID=$res_receipt2["contractID"];
				$taxinvoiceID=$res_receipt2["taxinvoiceID"];
				$taxpointDate=$res_receipt2["taxpointDate"];
				$receiveAmount=$res_receipt2["receiveAmount"];
				$fullname=$res_receipt2["fullname"];
				$byChannel=$res_receipt2["nameChannel"];
				$debtID=$res_receipt2["debtID"];
				$typePayID=$res_receipt2["typePayID"];
				$byChannelRef=$res_receipt2["byChannelRef"];
				$byChannelsend=$res_receipt2["byChannel"];
				$byChannelshow = "<a onclick=\"javascript:popU('frm_byway_transpay_detail.php?taxinvoiceID=$taxinvoiceID&bychannel=$byChannelsend','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=350')\" style=\"cursor:pointer;\" ><u>$byChannelRef</u></a>";
			
				$i+=1;
				if($i%2==0){
					echo "<tr bgcolor=#EEEEEE onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#EEEEEE';\" align=center>";
				}else{
					echo "<tr bgcolor=#F5F5F5 onmouseover=\"javascript:this.bgColor = '#FFFF99';\" onmouseout=\"javascript:this.bgColor = '#F5F5F5';\" align=center>";
				}
				
				//หา typePayID ของเลขที่สัญญานี้ว่าถ้าเป็นเงินต้นจะรหัสอะไร
				$select = pg_query("SELECT account.\"thcap_mg_getMinPayType\"('$contractID')");
				list($typeID) = pg_fetch_array($select);
				
				if($approveStatus == '1'){
					$stautsapp = 'อนุมัติ';
				}else if($approveStatus == '0'){
					$stautsapp = 'ไม่อนุมัติ';
				}else if($approveStatus == '3'){
					$stautsapp = 'ยกเลิกการทำรายการ';
				}
			?>
				<td><span onclick="javascript:popU('../thcap_installments/frm_Index.php?show=1&idno=<?php echo $contractID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=1100,height=800')" style="cursor:pointer;"><font color="red"><u><?php echo $contractID;?></u></font></span></td>
				<!--<td align="center" style="color:#0000FF;"><span onclick="javascript:popU('Channel_detail.php?taxinvoiceID=<?php echo $taxinvoiceID; ?>&debtID=<?php echo $debtID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $taxinvoiceID; ?></u></span></td>-->
				<td align="center"><span onclick="javascript:popU('../thcap/Channel_detail_v.php?receiptID=<?php echo $taxinvoiceID; ?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=800,height=600')" style="cursor: pointer;"><u><?php echo $taxinvoiceID; ?></u></span></td>
				<td align="center"><?php echo $taxpointDate; ?></td>
				<td><?php echo number_format($receiveAmount,2); ?></td>
				<td align="left"><?php echo $fullname; ?></td>
				<td align="center"><?php echo $requestDate; ?></td>
				<td><?php echo $approveUser; ?></td>
				<td><?php echo substr($res_app["approveDate"],0,19); ?></td>
				<td><?php echo $stautsapp; ?></td>
                <td>
                	<img src="images/detail.gif" width="19" height="19" onclick="javascript:popU('result_cancelreceipt.php?cancelTaxID=<?php echo $cancelTaxID?>','','toolbar=no,menubar=no,resizable=no,scrollbars=yes,status=no,location=no,width=500,height=300')" style="cursor:pointer;" />
                </td>
			</tr>
			<?php
			}
			?>
			<tr bgcolor="#D6D6D6">
				<td colspan="10" align="right" >จำนวนแสดง : <?php echo $aprows = pg_num_rows($qry_app); ?>  รายการ</td>
			</tr>
			</table><br>
		</div>
	</td>
</tr>	
</table>