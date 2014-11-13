<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<form name="order_catalog" method="post" action="<?=$APPLICATION->GetCurPage(false)?>" class="protection ajax">
	<div class="title">Форма заказа</div>
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="FormType" value="orderFormSend" />
	<input type="hidden" name="URL" value="" />
	<input type="hidden" name="THEME_TYPE" value="<?=$arParams["THEME_TYPE"]?>" />
	<?foreach($arResult["PROPS"] as $arProps):?>
        <?switch($arProps["PROPERTY_TYPE"]){
            case 'S': 
                if($arProps["ROW_COUNT"] > 1){
                ?>            
                <div class="line">
                    <label class="message"><?=$arProps["NAME"]?></label>                
                    <textarea name="PROPS_<?=$arProps["CODE"]?>" placeholder="Ваши комментарии"><?=$_REQUEST[$arProps["CODE"]]?></textarea>
                    <div class="clear"></div>
                </div>      
                <?
                }
                elseif($arProps["CODE"] !== "URL"){
                ?>
                <?if($arProps["CODE"] == "TYPE"):?>
                    <?if(count($arResult["THEME"]) > 1):?>
                        <div class="line">
                            <label>Тема<?if($arProps["IS_REQUIRED"] == "Y"):?><span class="required">*</span><?endif?></label>
                            <select name="THEME">
                                <?foreach($arResult["THEME"] as $theme):?>
                                    <option value="<?=$theme?>"><?=$theme?></option>
                                <?endforeach;?>
                            </select>
                            <div class="clear"></div>
                        </div>
                    <?else:?>
                        <input type="hidden" name="THEME" value="<?=$arResult["THEME"][0]?>" />
                    <?endif;?>
                <?else:?>
                <div class="line <?if($arProps["CODE"] == "TOWN"):?>autocomplete small<?endif?>">
                    <label class="<?if($arProps["CODE"] == "NAME" || $arProps["CODE"] == "ADDRESS" || $arProps["CODE"] == "MODEL"):?>top3 <?endif?>"><?=$arProps["NAME"]?><?if($arProps["IS_REQUIRED"] == "Y"):?><span class="required">*</span><?endif?></label>
                    <input type="text" name="<?if($arProps["CODE"] !== "DESCRIPTION"):?>PROPS_<?endif?><?=$arProps["CODE"]?>" class="<?if($arProps["IS_REQUIRED"] == "Y"):?>required <?endif?><?if($arProps["CODE"] == "TOWN"):?>autocomplete town <?endif?><?if($arProps["CODE"] == "EMAIL"):?>email<?endif?><?if($arProps["CODE"] == "PHONE"):?>phone<?endif?>"<?if($arProps["CODE"] == "DESCRIPTION"):?>readonly<?endif?> value="<?=$_REQUEST[$arProps["CODE"]]?><?if($arProps["CODE"] == "TOWN"):?>Москва<?endif?>" />
                    <?if($arProps["CODE"] == "TOWN"):?><div class="b-places"></div><?endif?>
                    <div class="clear"></div>
                </div>
                <?endif?>
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
		<label class="top3">ФИО и Название компании<span class="required">*</span></label>
		<input type="text" name="NAME" class="required" value="" />
		<div class="clear"></div>
	</div>
	<div class="line">
		<label>Телефон</label>
		<input type="text" name="PHONE" class="phone" value="" />
		<div class="clear"></div>
	</div>
	<div class="line">
		<label>E-mail<span class="required">*</span></label>
		<input type="text" name="EMAIL" class="email required" value="" />
		<div class="clear"></div>
	</div>
	<div class="line autocomplete small">
		<label>Город</label>				
		<input type="text" class="autocomplete town" name="TOWN" value="<?=$_SESSION["SELECTED_TOWN"]?>" />
		<div class="b-places"></div>
		<div class="clear"></div>
	</div>
	<?if(count($arResult["THEME"]) > 1):?>
		<div class="line">
			<label>Тема<span class="required">*</span></label>
			<select name="THEME">
				<?foreach($arResult["THEME"] as $theme):?>
					<option value="<?=$theme?>"><?=$theme?></option>
				<?endforeach;?>
			</select>
			<div class="clear"></div>
		</div>
	<?else:?>
		<input type="hidden" name="THEME" value="<?=$arResult["THEME"][0]?>" />
	<?endif;?>
	<div class="line">
		<label>Описание</label>
		<input type="text" name="DESCRIPTION" value="" readonly />
		<div class="clear"></div>
	</div>
	<div class="line">
		<label class="message">Комментарий</label>				
		<textarea name="MESSAGE" placeholder="Ваши комментарии"></textarea>
		<div class="clear"></div>
	</div>      */?>
	<p class="hint">* - поля, обязательные для заполнения.</p>
	<div class="line">
		<button type="submit" name="send">Отправить</button>
		<div class="clear"></div>
	</div>
</form>