<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
		$dbRes = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>"59", "ACTIVE"=>"Y", "PROPERTY_FILIAL"=>$arResult["FILIAL"]["ID"]), array("PROPERTY_DOMAIN", "PROPERTY_FILIAL", "PROPERTY_SEOTEXT"));
		if($arRes = $dbRes->Fetch()){
			if(!empty($arRes)){
				$dbDomain = CIBlockElement::GetList(array("SORT"=>"ASC"), array("IBLOCK_ID"=>"56", "ID"=>$arRes["PROPERTY_DOMAIN_VALUE"]), array("PROPERTY_URL"));
				if($arDomain = $dbDomain->Fetch()){
					if(!empty($arDomain)){
						// print_r($arRes);
						// print_r($_SERVER);
						// echo SITE_SERVER_NAME;
						// echo $arDomain["PROPERTY_URL_VALUE"];
						if($arDomain["PROPERTY_URL_VALUE"] == $_SERVER["HTTP_HOST"]){
							?>
								<div class="text">
									<a class="show-more" href="#" title="Подробнее">Детальное описание<span></span></a>  
									<div class="more">
										<?if($arRes["PROPERTY_SEOTEXT_VALUE"]["TYPE"] == "html"):?>
											<?=$arRes["PROPERTY_SEOTEXT_VALUE"]["TEXT"]?>
										<?else:?>
											<p><?=$arRes["PROPERTY_SEOTEXT_VALUE"]["TEXT"]?></p>
										<?endif;?>
									</div>
								</div>
							<?
						}
					}
				}
			}
			// print_r($arRes);
		}
	?>