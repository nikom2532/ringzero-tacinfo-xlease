<?php
require_once("../../config/config.php");
$all = pg_escape_string($_GET["all"]);
if($all !='1'){
	$select_limit=" limit 30" ;
}
echo "
	<div class=\"tab_menu_contrainer\">
		<div class=\"menu_box\">
			<div class=\"tab_box\">
				<div class=\"slide_tab\">";
				
				// นับจำนวนรายการทั้งหมด
				$qry_selcol = pg_query("SELECT * FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE  \"appvStatus\" <> '9'  ORDER BY \"appvStamp\" DESC $select_limit
				");
				$row_Selcol = pg_num_rows($qry_selcol);
				
				echo "<div class=\"tab active\"><a id=\"0\" href=\"javascript:list_tab_menu_app('0');\">ทั้งหมด <font color=\"red\"> ($row_Selcol)</font></a></div>";			
				$qry_year=pg_query("
					SELECT distinct(\"conType\")
					FROM thcap_contract
				");
				while($restype=pg_fetch_array($qry_year)){
					list($contracttype)=$restype;
					
					// นับจำนวนของสัญญานั้นๆ
					$qry_selcol = pg_query("
									SELECT * FROM (SELECT * FROM \"thcap_temp_payment_debtor_creditors_cost\" WHERE  \"appvStatus\" <> '9'  
									ORDER BY \"appvStamp\" DESC $select_limit) a  WHERE
									( \"thcap_get_contractType\"(\"contractID\") = '$contracttype' OR subStr(\"contractID\" ,0,3)='$contracttype')
									ORDER BY \"doerStamp\" ASC
					");
					$row_Selcol = pg_num_rows($qry_selcol);
					echo "<div class=\"tab active\"><a id=\"$contracttype\" href=\"javascript:list_tab_menu_app('$contracttype');\">$contracttype <font color=\"red\"> ($row_Selcol) </font></a></div>";
				}
			echo "
				</div>
			</div>
		</div>
	</div>
	<div class=\"list_tab_menu_app\"></div>
";
?>