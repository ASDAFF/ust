<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<style>

table.grain-tables-table-view { border: 1px solid #999999; border-collapse: collapse }
table.grain-tables-table-view td { padding: 4px !important; border: 1px solid #999999 !important }
table.grain-tables-table-view thead td { background-color: #EAEAEA }
table.grain-tables-table-view thead div.grain-tables-table-view-tooltip { font-size: 0.8em }

table.grain-tables-table-view tfoot a { text-decoration: none }
table.grain-tables-table-view tfoot a img { vertical-align: middle }
table.grain-tables-table-view tfoot a span { text-decoration: underline }

</style>

<table class="grain-tables-table-view" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<?foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn):?>
				<td>
					<?=$arColumn["LANG"][LANGUAGE_ID]["NAME"]?>
					<?if($arColumn["LANG"][LANGUAGE_ID]["TOOLTIP"]):?>
						<div class="grain-tables-table-view-tooltip"><?=$arColumn["LANG"][LANGUAGE_ID]["TOOLTIP"]?></div>
					<?endif?>
				</td>
			<?endforeach?>
		</tr>
	</thead>
	<tbody>
		<?
		if($arParams["MULTIPLE"]=="Y") $arResult["VALUE"] = $arParams["VALUE"];
		else $arResult["VALUE"] = Array($arParams["VALUE"]);
		?>
    	<?foreach($arResult["VALUE"] as $value_id=>$arValues):?>
    	<tr>
    		<?foreach($arParams["SETTINGS"]["COLUMNS"] as $arColumn):?>
    			<?$value=$arValues[$arColumn["NAME"]];?>
    			<td>
    				<?
    				
    				switch($arColumn["TYPE"]):

    				
    				case "text":
    				case "textarea":
    				case "date":

    					echo $value;
    											
    				break;
    				
    				
    				case "checkbox":
    				
    					if($value=="Y") 
    						echo GetMessage("GRAIN_TABLES_TV_TEMPLATE_YES");
    					else 
    						echo GetMessage("GRAIN_TABLES_TV_TEMPLATE_NO");
    				
    				break;
    				
    				
    				case "select":
    				
   						$str_val = "";

    					foreach($arColumn["VALUES"] as $option):
    					
    						if($arColumn["MULTIPLE"]=="Y" && is_array($value)) $sel=in_array($option["VALUE"],$value);
    						else $sel = $value==$option["VALUE"];
    						
    						if($sel):
    						
    							if(strlen($str_val)>0) $str_val .= ", ";
    							$str_val .= $option["LANG"][LANGUAGE_ID];
    						
    						endif;
    						
    					endforeach;
    					
    					echo $str_val;
    				
    				break;


					case "filepath":
						
						if(strlen($value)>0): 
							
							if($arColumn["SHOW_AS"]=="image"):
							
								if(CFile::IsImage($value)):
							
									$imgsize=getimagesize($_SERVER["DOCUMENT_ROOT"]."/".$value);
							
									echo CFile::Show2Images(
										$value,
										$value,
										100,
										100,
										"border=\"0\"",
										GetMessage("GRAIN_TABLES_TV_TEMPLATE_IMAGE_TITLE")
									);
								
								endif;
								
							elseif($arColumn["SHOW_AS"]=="link"):
							
								?><a href="<?=$value?>"><?=GetMessage("GRAIN_TABLES_TV_TEMPLATE_FILE_DOWNLOAD")?></a><?
							
							endif;
						
						endif;

					break;

					case "link":
					
						if(
							GPropertyTable::IsLinksInstalled()
							&& !in_array(CModule::IncludeModuleEx("grain.links"),Array(MODULE_NOT_FOUND,MODULE_DEMO_EXPIRED))
						) {
						
							$SELECTED=Array();

							$arParameters = $arColumn["LINK"];
							
							$arParameters["SHOW_URL"] = $arColumn["SHOW_URL"]=="Y";
							$arParameters["MULTIPLE"] = $arColumn["MULTIPLE"]=="Y";
							$arParameters["ADMIN_SECTION"] = false;
						
							$DATA = CGrain_LinksTools::GetSelected($arParameters,$value);
						
							if(is_array($value)) foreach($value as $value_id=>$val) {
								if(!array_key_exists($val,$DATA))
									continue;
								$arItem=$DATA[$val];
							    $SELECTED[$val] = $arItem;
							} else {
							    if(array_key_exists($value,$DATA)) {
							    	$SELECTED=Array($value => $DATA[$value]);
							    }
							}

							$str_val = "";

	    					foreach($SELECTED as $val=>$arItem):
	    							
	    						if(strlen($str_val)>0) $str_val .= ", ";
	    										
	    						if($arColumn["SHOW_URL"]=="Y" && $arItem["URL"])
	    							$str_val .= "<a href=\"".$arItem["URL"]."\">".$arItem["NAME"]."</a>";
								else
	    							$str_val .= $arItem["NAME"];
	    						
	    					endforeach;

							echo $str_val;
						
						} else {
						
							$str_val = "";
							
							if(is_array($value)):
							foreach($value as $val):
								if(strlen($str_val)>0) $str_val .= ", ";
								$str_val .= $val;
							endforeach;
							else:
								$str_val .= $value;
							endif;

							echo $str_val;
						
						}
					
					break;

    				endswitch;
    				
    				?>
    			</td>
    		<?endforeach?>
		</tr>
    	<?endforeach?>
	</tbody>
</table>