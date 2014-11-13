<?php
namespace Bitrix\Main\Page;


final class Frame
{
	private static $instance;
	private static $isEnabled = false;
	private static $isAjaxRequest = null;
	private static $useDeferExec = true;
	private static $useHTMLCache = false;
	private static $onBeforeHandleKey = false;
	private static $onHandleKey = false;
	private static $onPrologHandleKey = false;
	private $dynamicIDs = array();
	private $dynamicData = array();
	private $containers = array();
	private $curDynamicId = false;
	private $injectedJS = false;

	public $arDynamicData = array();

	private function __construct()
	{
		//use self::getInstance()
	}

	private function __clone()
	{
		//you can't clone it
	}

	public static function getInstance()
	{
		if (is_null(self::$instance))
		{
			self::$instance = new  Frame();
		}

		return self::$instance;
	}

	/**
	 * Gets ids of the dynamic blocks
	 * @return array
	 */
	public function getDynamicIDs()
	{
		return array_keys($this->dynamicIDs);
	}

	/**
	 * Adds dynamic data to be sent to the client.
	 *
	 * @param string $ID
	 * @param string $content
	 * @param string $stub
	 * @param string $containerId
	 */
	public function addDynamicData($ID, $content, $stub = "", $containerId = null)
	{
		$this->dynamicIDs[$ID] = $stub;
		$this->dynamicData[$ID] = $content;
		if ($containerId !== null)
			$this->containers[$ID] = $containerId;
	}

	/**
	 * Sets isEnable property value and attaches needle handlers
	 *
	 * @param bool $isEnabled
	 */
	public static function setEnable($isEnabled = true)
	{
		if ($isEnabled && !self::$isEnabled)
		{
			self::$onBeforeHandleKey = AddEventHandler("main", "OnBeforeEndBufferContent", array(__CLASS__, "OnBeforeEndBufferContent"));
			self::$onHandleKey = AddEventHandler("main", "OnEndBufferContent", array(__CLASS__, "OnEndBufferContent"));
			self::$isEnabled = true;
			\CJSCore::init(array("fc"), false);
		}
		elseif (!$isEnabled && self::$isEnabled)
		{
			if (self::$onBeforeHandleKey >= 0)
			{
				RemoveEventHandler("main", "OnBeforeEndBufferContent", self::$onBeforeHandleKey);
			}
			if (self::$onBeforeHandleKey >= 0)
			{
				RemoveEventHandler("main", "OnEndBufferContent", self::$onHandleKey);
			}

			self::$isEnabled = false;
		}
	}

	/**
	 * Marks start of a dynamic block
	 *
	 * @param $ID
	 *
	 * @return bool
	 */
	public function startDynamicWithID($ID)
	{
		if (!self::$isEnabled
			|| isset($this->dynamicIDs[$ID])
			|| $ID == $this->curDynamicId
			|| ($this->curDynamicId && !isset($this->dynamicIDs[$this->curDynamicId]))
		)
		{
			return false;
		}

		echo "<!--start_frame_cache_" . $ID . "-->";

		$this->curDynamicId = $ID;

		return true;
	}

	/**
	 * Marks end of the dynamic block if it's the current dynamic block
	 * and its start was being marked early.
	 *
	 * @param string $ID
	 * @param string $stub
	 * @param string $containerId
	 *
	 * @return bool
	 */
	public function finishDynamicWithID($ID, $stub = "", $containerId = null)
	{
		if (!self::$isEnabled || $this->curDynamicId !== $ID)
		{
			return false;
		}

		echo "<!--end_frame_cache_" . $ID . "-->";

		$this->curDynamicId = false;
		$this->dynamicIDs[$ID] = $stub;
		if ($containerId !== null)
			$this->containers[$ID] = $containerId;

		return true;
	}

	/**
	 * This method returns the divided content.
	 * The content is divided by two parts - static and dynamic.
	 * Example of returned value:
	 * <code>
	 * array(
	 *    "static"=>"Hello World!"
	 *    "dynamic"=>array(
	 *        array("ID"=>"someID","CONTENT"=>"someDynamicContent", "HASH"=>"md5ofDynamicContent")),
	 *        array("ID"=>"someID2","CONTENT"=>"someDynamicContent2", "HASH"=>"md5ofDynamicContent2"))
	 * );
	 * </code>
	 *
	 * @param $content
	 *
	 * @return array
	 */
	public function getDividedPageData($content)
	{
		$data = array(
			"dynamic" => array(),
			"static" => $content,
			"md5" => "",
		);

		$ids = self::getInstance()->getDynamicIDs();
		if (count($ids) > 0) //Do we have any dynamic blocks?
		{
			$match = array();
			$regexp = "/<!--start_frame_cache_(" . implode("|", $ids) . ")-->(.+?)<!--end_frame_cache_(?:" . implode("|", $ids) . ")-->/is";
			if (preg_match_all($regexp, $content, $match))
			{
				/*
					Notes:
					$match[0] -	an array of dynamic blocks with macros'
					$match[1] - ids of dynamic blocks
					$match[2] - array of dynamic blocks
				*/

				$replacedArray = array();
				$replacedEmpty = array();
				foreach ($match[1] as $i => $id)
				{
					$data["dynamic"][] = self::getInstance()->arDynamicData[] = array(
						"ID" => isset($this->containers[$id])? $this->containers[$id]: "bxdynamic_".$id,
						"CONTENT" => isset($this->dynamicData[$id])? $this->dynamicData[$id]: $match[2][$i],
						"HASH" => md5(isset($this->dynamicData[$id])? $this->dynamicData[$id]: $match[2][$i]),
					);

					if (isset($this->containers[$id]))
					{
						$replacedArray[] = $this->dynamicIDs[$id];
						$replacedEmpty[] = '';
					}
					else
					{
						$replacedArray[] = '<div id="bxdynamic_'.$id.'">'.$this->dynamicIDs[$id].'</div>';
						$replacedEmpty[] = '<div id="bxdynamic_'.$id.'"></div>';
					}
				}

				$data["static"] = str_replace($match[0], $replacedArray, $content);
				$pureContent = str_replace($match[0], $replacedEmpty, $content);
			}
			else
			{
				$pureContent = $content;
			}
		}
		else
		{
			$pureContent = $content;
		}

		$patterns = array(
			"/'SERVER_TIME':'\\d{1,}'/",
			"/'bitrix_sessid':'.{1,}'/"
		);
		$values = Array(
			"'SERVER_TIME':'#CURRENT_SERVER_TIME#'",
			"'bitrix_sessid':'#CURRENT_SESSID#'"
		);
		$pureContent = preg_replace($patterns, $values, $pureContent);
		$data["md5"] = md5($pureContent);

		return $data;
	}

	/**
	 * This is a handler of "BeforeProlog" event
	 * Use it to switch on feature of static caching.
	 */
	public static function onBeforeProlog()
	{
		global $USER;
		/**
		 * @global \CUser $USER
		 */

		$staticHtmlCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
		self::getInstance()->setEnable();
		if ($staticHtmlCache->isCacheable() && $staticHtmlCache->isExists() && !self::isAjaxRequest())
		{
			//TODO replace it by normal code in the future
			$patterns = Array(
				"#CURRENT_SESSID#"
			);

			$values = Array(
				bitrix_sessid()
			);

			$content = str_replace($patterns, $values, $staticHtmlCache->read());

			if (strlen($content) > 0)
			{
				echo $content;
				die();
			}
		}
	}

	/**
	 * OnBeforeEndBufferContent handler
	 */
	public static function onBeforeEndBufferContent()
	{
		global $APPLICATION;

		$params = array();

		if (self::getUseAppCache())
		{
			$manifest = \Bitrix\Main\Data\AppCacheManifest::getInstance();
			$params = $manifest->OnBeforeEndBufferContent();
			$params["CACHE_MODE"] = "APPCACHE";
		}
		elseif (self::getUseHTMLCache())
		{
			$staticHTMLCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();

			if ($staticHTMLCache->isCacheable())
			{
				$params["CACHE_MODE"] = "HTMLCACHE";
				$params["PAGE_URL"] = $staticHTMLCache->getRequestUri();
			}
			else
			{
				return;
			}
		}

		$frame = self::getInstance();
		$frame->injectedJS = $frame->getInjectedJs($params);
		$APPLICATION->AddHeadString($frame->injectedJS["start"], false, true);
		$APPLICATION->AddHeadString($frame->injectedJS["end"]);
	}

	/**
	 * OnEndBufferContent handler
	 * There are two variants of content's modification in this method.
	 * The first one:
	 * If it's ajax-hit the content will be replaced by json data with dynamic blocks,
	 * javascript files and etc. - dynamic part
	 *
	 * The second one:
	 * If it's simple hit the content will be modified also,
	 * all dynamic blocks will be cutted out of the content - static part.
	 *
	 * @param $content
	 */
	public static function onEndBufferContent(&$content)
	{
		global $APPLICATION;
		global $USER;
		$dividedData = self::getInstance()->getDividedPageData($content);
		$htmlCacheChanged = false;

		if (self::getUseHTMLCache())
		{
			$staticHTMLCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
			if ($staticHTMLCache->isCacheable())
			{
				if (
					!$staticHTMLCache->isExists()
					|| $staticHTMLCache->getSubstring(-35, 32) !== $dividedData["md5"]
				)
				{
					$staticHTMLCache->delete();
					$staticHTMLCache->write($dividedData["static"]."<!--".$dividedData["md5"]."-->");
				}

				$frame = self::getInstance();

				$ids = $frame->getDynamicIDs();
				foreach ($ids as $i => $id)
				{
					if (isset($frame->containers[$id]))
						unset($ids[$i]);
				}

				$dividedData["static"] = preg_replace(
					array(
						'/<!--start_frame_cache_('.implode("|", $ids).')-->/',
						'/<!--end_frame_cache_('.implode("|", $ids).')-->/',
					),
					array(
						'<div id="\1">',
						'</div>',
					),
					$content
				);

				if ($frame->injectedJS)
				{
					if (isset($frame->injectedJS["start"]))
						$dividedData["static"] = str_replace($frame->injectedJS["start"], "", $dividedData["static"]);
					if (isset($frame->injectedJS["end"]))
						$dividedData["static"] = str_replace($frame->injectedJS["end"], "", $dividedData["static"]);
				}

			}
			elseif (!$staticHTMLCache->isCacheable())
			{
				$staticHTMLCache->delete();
				return;
			}
		}

		if (self::getUseAppCache() == true) //Do we use html5 application cache?
		{
			\Bitrix\Main\Data\AppCacheManifest::getInstance()->generate($dividedData["static"]);
		}
		else
		{
			\Bitrix\Main\Data\AppCacheManifest::checkObsoleteManifest();
		}

		if (self::isAjaxRequest()) //Is it a check request?
		{
			header("Content-Type: application/x-javascript");
			$autoTimeZone = (is_object($GLOBALS["USER"])) ? trim($USER->GetParam("AUTO_TIME_ZONE")) : "N";
			$content = array(
				"js" => $APPLICATION->arHeadScripts,
				"additional_js" => $APPLICATION->arAdditionalJS,
				"lang" => array(
					'LANGUAGE_ID' => LANGUAGE_ID,
					'FORMAT_DATE' => FORMAT_DATE,
					'FORMAT_DATETIME' => FORMAT_DATETIME,
					'COOKIE_PREFIX' => \COption::GetOptionString("main", "cookie_name", "BITRIX_SM"),
					'USER_ID' => $USER->GetID(),
					'SERVER_TIME' => time(),
					'SERVER_TZ_OFFSET' => date("Z"),
					'USER_TZ_OFFSET' => \CTimeZone::GetOffset(),
					'USER_TZ_AUTO' => $autoTimeZone == 'N' ? 'N' : 'Y',
					'bitrix_sessid' => bitrix_sessid(),
				),
				"css" => $APPLICATION->GetCSSArray(),
				"htmlCacheChanged" => $htmlCacheChanged,
				"isManifestUpdated" => \Bitrix\Main\Data\AppCacheManifest::getInstance()->getIsModified(),
				"dynamicBlocks" => $dividedData["dynamic"],
			);

			if (!\Bitrix\Main\Application::getInstance()->isUtfMode())
			{
				//TODO I use it because there is no similar method in the new Bitrix Framework yet
				$content = $APPLICATION->convertCharsetarray($content, SITE_CHARSET, "UTF-8");
			}

			$content = (self::getUseDeferExec()
				? "var frameData = " . json_encode($content) . "; if(window.BX && typeof(window.BX.onCustomEvent) == \"function\"){BX.onCustomEvent(\"onDeferredDataReceived\", [frameData])}"
				: json_encode($content)
			);
		}
		else
		{
			$content = $dividedData["static"];
		}
	}

	/**
	 * Sets useAppCache property
	 *
	 * @param bool $useAppCache
	 */
	public function setUseAppCache($useAppCache = true)
	{
		if(self::getUseAppCache())
			self::getInstance()->setUseHTMLCache(false);
		$appCache = \Bitrix\Main\Data\AppCacheManifest::getInstance();
		$appCache->setEnabled($useAppCache);
	}

	/**
	 * Gets useAppCache property
	 * @return bool
	 */
	public function getUseAppCache()
	{
		$appCache = \Bitrix\Main\Data\AppCacheManifest::getInstance();
		return $appCache->isEnabled();
	}

	/**
	 * @return boolean
	 */
	public static function getUseHTMLCache()
	{
		return self::$useHTMLCache;
	}

	/**
	 * @param boolean $useHTMLCache
	 */
	public static function setUseHTMLCache($useHTMLCache = true)
	{
		self::$useHTMLCache = $useHTMLCache;
		self::$onPrologHandleKey = AddEventHandler("main", "onBeforeProlog", array(__CLASS__, "onBeforeProlog"));
	}

	/**
	 * @return boolean
	 */
	public static function getUseDeferExec()
	{
		return (self::$useDeferExec && self::getUseHTMLCache());
	}

	/**
	 * @param boolean $useDeferExec
	 */
	public static function setUseDeferExec($useDeferExec = true)
	{
		self::$useDeferExec = $useDeferExec;
	}

	public static function isAjaxRequest()
	{
		if (self::$isAjaxRequest == null)
		{
			$actionType = \Bitrix\Main\Context::getCurrent()->getServer()->get("HTTP_BX_ACTION_TYPE");
			self::$isAjaxRequest = (
				$actionType == "get_dynamic"
				|| (
					defined("actionType")
					&& constant("actionType")  == "get_dynamic"
				)
			);
		}

		return self::$isAjaxRequest;
	}

	protected function  getInjectedJs($params = array())
	{
		$dynamic = self::getInstance()->getDynamicIDs();
		$params["PAGE_URL"] = (!$params["PAGE_URL"])
			? \Bitrix\Main\Context::getCurrent()->getServer()->getRequestUri()
			: $params["PAGE_URL"];

		$checkUrl = $params["PAGE_URL"];
		$mergedParams = array_merge(array("dynamic" => self::getInstance()->getDynamicIDs()), $params);

		/**
		 * We use deferred script in case of using htmlcache only
		 */
		if (self::getUseDeferExec())
		{
			//The parameters for page identification
			$checkUrl .= (strpos($checkUrl, "?") === false ? "?" : "&") . "actionType=get_dynamic";

			foreach ($params as $param => $value)
			{
				if (
					$param !== "PAGE_URL"
					|| !self::getUseHTMLCache()
				)
				{
					$checkUrl .= "&".$param."=".$value;
				}
			}

			//dynamic blocks
			if (!self::getUseHTMLCache())
			{
				foreach ($dynamic as $i => $d)
				{
					$checkUrl .= "&dynamic[".$i."]=".$d;
				}
			}

			//adding random params if we are using appcache
			if (self::getUseAppCache())
			{
				$checkUrl .= (strpos($checkUrl, "?") >= 0 ? "&" : "?") . "r=random";
			}

			return array(
				"start" => "<script type=\"text/javascript\" src = \"" . $checkUrl . "\" defer=\"defer\" async=\"async\"></script>",
				"end" => "<script>window.addEventListener('DOMContentLoaded', function(){ BX.frameCache.vars = " . json_encode($mergedParams) . "; BX.frameCache.update(true)},false);</script>"
			);
		}
		else
		{
			return array("end" => "<script>window.addEventListener('DOMContentLoaded', function(){ BX.frameCache.vars = " . json_encode($mergedParams) . ";BX.frameCache.update();},false);</script>");
		}
	}

}