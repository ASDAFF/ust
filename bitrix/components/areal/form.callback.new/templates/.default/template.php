<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="title">Заказать обратный звонок</div>
<p>Заполните, пожалуйста, форму, чтобы наш менеджер мог связаться с Вами:</p>
<form name="callback" method="post" action="<?=$APPLICATION->GetCurPage()?>" class="ajax">
    <input type="hidden" name="FormType" value="CallbackSend" />
    <?foreach($arResult["PROPS"] as $arProps):?>
        <?switch($arProps["PROPERTY_TYPE"]){
            case 'S': 
                if($arProps["ROW_COUNT"] > 1){
                ?>            
                <div class="line">
                    <label class="message">Комментарий</label>                
                    <textarea name="PROPS_<?=$arProps["CODE"]?>" placeholder="Ваши комментарии"><?=$_REQUEST[$arProps["CODE"]]?></textarea>
                    <div class="clear"></div>
                </div>      
                <?
                }
                else{
                ?>
                <div class="line <?if($arProps["CODE"] == "TOWN"):?>autocomplete small<?endif?>">
                    <label class="<?if($arProps["CODE"] == "NAME" || $arProps["CODE"] == "ADDRESS" || $arProps["CODE"] == "MODEL"):?>top3 <?endif?>"><?=$arProps["NAME"]?><?if($arProps["IS_REQUIRED"] == "Y"):?><span class="required">*</span><?endif?></label>
                    <input type="text" name="PROPS_<?=$arProps["CODE"]?>" class="<?if($arProps["IS_REQUIRED"] == "Y"):?>required <?endif?><?if($arProps["CODE"] == "TOWN"):?>autocomplete town <?endif?><?if($arProps["CODE"] == "EMAIL"):?>email<?endif?><?if($arProps["CODE"] == "PHONE"):?>phone<?endif?>" value="<?=$_REQUEST[$arProps["CODE"]]?><?if($arProps["CODE"] == "TOWN"):?>Москва<?endif?>" />
                    <?if($arProps["CODE"] == "TOWN"):?><div class="b-places"></div><?endif?>
                    <div class="clear"></div>
                </div>
                <?   
                }
            break;
            case 'L':
                ?>
                <div class="line">
                    <label><?=$arProps["NAME"]?><?if($arProps["IS_REQUIRED"] == "Y"):?><span class="required">*</span><?endif?></label>
                    <select name="PLIST_<?=$arProps["CODE"]?>">
                        <?foreach($arProps["VAL_LIST"] as $arVal):?>
                            <option value="<?=$arVal["ID"]?>" <?if($arVal["DEF"] == "Y"):?>selected="selected"<?endif;?>><?=$arVal["VALUE"]?></option>
                        <?endforeach;?>
                    </select>
                    <div class="clear"></div>
                </div>
                <?
            break;
            case 'E':
                //echo '<pre>'; print_r($arProps); echo '</pre>';
            break;
        }
        ?>
    <?endforeach?>
    <?/*<div class="line">
		<label>Ваше имя<!--span class="required">*</span--></label>
		<input   type="text" name="NAME" value="" />
		<div class="clear"></div>
	</div>
	<div class="line">
		<label>Телефон<span class="required">*</span></label>
		<input class="required phone" type="text" name="PHONE" value="" />
		<div class="clear"></div>
	</div>
	<div class="line autocomplete">
		<label>Город</label>				
		<input type="text" class="autocomplete town" name="TOWN" value="<?if(!empty($_SESSION["SELECTED_TOWN"])) echo trim($_SESSION["SELECTED_TOWN"])?>" />
		<div class="b-places"></div>
		<div class="clear"></div>
	</div>
	<?if(!empty($arResult["TIMES"])):?>
		<div class="line">
			<label>Время звонка</label>
			<select name="TIME">
				<?foreach($arResult["TIMES"] as $key => $time):?>
					<option value="<?=$key?>"><?=$time?></option>
				<?endforeach;?>
			</select>
			<div class="clear"></div>
		</div>
	<?endif;?>
	<div class="line">
		<label>Комментарии<!--span class="required">*</span--></label>
		<textarea  type="text" name="COMMENT" /></textarea>
		<div class="clear"></div>
	</div>    */?>
    <div class="line"><button type="submit" name="send">Заказать звонок</button></div>
        
        
				 
</form>