<?$data = getServiceAddress();?>
<div class="adress-phone image">
	<a href="#" id="service_address" title="Адреса сервисных центров" > 				 
		<span class="title">Адрес сервисного центра</span>
		<?if(!empty($data["address"])):?>
			<span class="img image"><table><tr><td><?=$data["address"]?></td></tr></table></span>
		<?else:?>
			<span class="img image">Для вашего города не указан адрес сервисного центра</span>
		<?endif;?>
		<span>Адреса всех сервисных центров</span>
	</a> 		
</div>	 
<div class="adress-phone last image">
	<a href="#" id="service_phones" title="Телефоны аварийной круглосуточной службы поддержки">
		<?if(!empty($data["phone"])):?>
			<span class="img"><b>Телефон круглосуточной службы поддержки в городе <?=$_SESSION["SELECTED_TOWN"]?></b><br /><span class="phone"><?=$data["phone"]?></span></span>
		<?else:?>
			<span class="img">Телефоны аварийной круглосуточной службы поддержки</span>
		<?endif;?>
		<span>Телефоны в других городах</span>
	</a> 	
</div>
<?
function getServiceAddress() {
	$return = array();
	if(!empty($_SESSION["SELECTED_TOWN"]) && CModule::IncludeModule("iblock")) {
		$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => TOWNS, "ACTIVE" => "Y", "NAME" => $_SESSION["SELECTED_TOWN"]), false, false, array("ID", "NAME"));
		if($town = $res->GetNext()) {
			$addresses = CIBlockElement::GetList(array(), array("IBLOCK_ID" => FILIALS, "ACTIVE" => "Y", "PROPERTY_TOWN" => $town["ID"], "PROPERTY_TYPE" => 36), false, false, array("ID", "PROPERTY_TOWN", "PROPERTY_ADDRESS"));
			if($address = $addresses->GetNext())
				$return["address"] = $address["PROPERTY_ADDRESS_VALUE"];
			$phones = CIBlockElement::GetList(array(), array("IBLOCK_ID" => SERVICE_PHONE, "ACTIVE" => "Y", "PROPERTY_TOWN" => $town["ID"]), false, false, array("ID", "PROPERTY_TOWN", "PROPERTY_PHONE"));
			if($phone = $phones->GetNext())
				$return["phone"] = implode(", ", $phone["PROPERTY_PHONE_VALUE"]);
		}
	}
	return $return;
}
?>