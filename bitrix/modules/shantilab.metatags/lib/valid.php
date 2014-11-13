<?php
namespace Shantilab\MetaTags;
IncludeModuleLangFile(__FILE__);

class Valid{
	public static function checkRequestExpression($request){

		$return = false;

		$request = explode("&",$request);

		foreach($request as $param){
			$param = explode("=",$param);

			if (is_array($param) && count($param) == 2 && $_REQUEST[$param[0]] == $param[1]){
				$return = true;
			}elseif(is_array($param) && count($param) == 1  && isset($_REQUEST[$param[0]])){
				$return = true;
			}else{
				$return = false;
				break;
			}
		}

		return $return;
	}

	public function getVarValFromText($variable){
		$pattern = "/^[$]{1}[A-za-z]{1}[a-zA-Z0-9]+/";
		if (defined("BX_UTF")){
			$pattern .= 'u';
		}
		preg_match($pattern, $variable, $matches);
		if ($matches){
			$var = substr(current($matches),1);
			global $$var;
			$arTmp = (explode("]",str_replace(current($matches),"",$variable)));
			$arValue = $$var;
			foreach($arTmp as $index => $val){
				$arReplace = array("\"","\'","]","["," ");
				$val = str_replace($arReplace,"",$val);
				if ($val){
					$arValue = $arValue[$val];
				}
			}
			if ($arValue)
				$variable = $arValue;
		}

		if ($arValue)
			return $variable;
		else
			return '';
	}
}