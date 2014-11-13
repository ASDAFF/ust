<?php
namespace Bitrix\Main\Data;

use Bitrix\Main;

/**
 * Class StaticHtmlCache
 *
 * <code>
 * $staticHtmlCache = \Bitrix\Main\Data\StaticHtmlCache::getInstance();
 *
 * if ($staticHtmlCache->isExists())
 * &#123;
 * 	$staticHtmlCache->read();
 * 	die();
 * &#125;
 *
 * if ($staticHtmlCache->isCacheable())
 * &#123;
 * 	$staticHtmlCache->write($content);
 * &#125;
 * else
 * &#123;
 * 	$staticHtmlCache->delete();
 * &#125;
 *
 * if ($staticHtmlCache->isCacheable() && $staticHtmlCache->isExists())
 * &#123;
 * 	if (md5($content) !== $staticHtmlCache->getMd5())
 * 		$staticHtmlCache->write($content); //update cache
 * 	//send Json
 * &#125;
 * </code>
 *
 * @package Bitrix\Main\Data
 */
class StaticHtmlCache
{
	/**
	 * @var StaticHtmlCache
	 */
	protected static $instance = null;
	/**
	 * @var Main\IO\File
	 */
	private $cacheFile = null;
	/**
	 * @var Main\IO\File
	 */
	private $statFile = null;
	/**
	 * @var bool
	 */
	private $canCache = true;

	/**
	 * Creates new cache manager instance.
	 */
	public function __construct()
	{
	}

	/**
	 * Returns current instance of the StaticHtmlCache.
	 *
	 * @return StaticHtmlCache
	 */
	public static function getInstance()
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static();
			static::$instance->init();
		}

		return static::$instance;
	}

	/**
	 * Initializes an instance.
	 *
	 * @return void
	 */
	public function init()
	{
		if ($this->getRequestMethod() === "GET")
			$PageFile = $this->convertUriToPath($this->getRequestUri());
		else
			$PageFile = "";

		if ($PageFile)
		{
			$this->cacheFile = new Main\IO\File(Main\IO\Path::convertRelativeToAbsolute(
				Main\Application::getPersonalRoot()
				."/html_pages"
				.$PageFile
			));
			$this->statFile = new Main\IO\File(Main\IO\Path::convertRelativeToAbsolute(
				Main\Application::getPersonalRoot()
				."/html_pages/"
				.".enabled"
			));
		}
	}

	/**
	 * Returns request uri
	 *
	 * @return string
	 */
	public function getRequestUri()
	{
		$uri = Main\Context::getCurrent()->getServer()->getRequestUri();
		return $uri;
	}

	/**
	 * Returns request method
	 *
	 * @return string
	 */
	public function getRequestMethod()
	{
		return Main\Context::getCurrent()->getServer()->getRequestMethod();
	}

	/**
	 * Converts request uri into path safe file with .html extention.
	 * Returns empty string if fails.
	 *
	 * @param string $Uri
	 * @return string
	 */
	public function convertUriToPath($Uri)
	{
		$match = array();
		$PageFile = "/".Main\Context::getCurrent()->getServer()->getHttpHost();
		$PageFile = preg_replace("/:(\\d+)\$/", "-\\1", $PageFile);

		if (preg_match("#^(/.+?)\\.php\\?([^\\\\/]*)#", $Uri, $match) > 0)
		{
			$PageFile .= $match[1]."@".$match[2].".html";
		}
		elseif (preg_match("#^(/.+)\\.php\$#", $Uri, $match) > 0)
		{
			$PageFile .= $match[1]."@.html";
		}
		elseif (preg_match("#^(/.+?|)/\\?([^\\\\/]*)#", $Uri, $match) > 0)
		{
			$PageFile .= $match[1]."/index@".$match[2].".html";
		}
		elseif(preg_match("#^(/.+|)/\$#", $Uri, $match) > 0)
		{
			$PageFile .= $match[1]."/index@.html";
		}

		if (!Main\IO\Path::validate($PageFile))
			return "";
		if (Main\IO\Path::normalize($PageFile) !== $PageFile)
			return "";

		return $PageFile;
	}

	/**
	 * Saves html content into file
	 * with predefined path (current request uri)
	 *
	 * @param string $content
	 * @return void
	 */
	public function write($content)
	{
		if ($this->cacheFile)
		{
			if (defined("BX_COMPOSITE_DEBUG"))
			{
				if ($this->cacheFile->isExists())
				{
					$backupName = $this->cacheFile->getPath().".write.".microtime(true);
					AddMessage2Log($backupName, "composite");
					$backupFile = new Main\IO\File($backupName);
					$backupFile->putContents($this->cacheFile->getContents());
				}
			}
			$written = $this->cacheFile->putContents($content);
			//Update total files size
			$this->writeStatistic(
				0, //hit
				1, //miss
				0, //quota
				0, //posts
				$written //files
			);
		}
		
	}

	/**
	 * Returns html content from the file
	 * with predefined path (current request uri)
	 * Returns empty string when there is no file.
	 *
	 * @return string
	 */
	public function read()
	{
		if ($this->cacheFile && $this->cacheFile->isExists())
			return $this->cacheFile->getContents();
		else
			return "";
	}

	/**
	 * Deletes the file
	 * with predefined path (current request uri)
	 *
	 * @return void
	 */
	public function delete()
	{
		if ($this->cacheFile && $this->cacheFile->isExists())
		{
			$cacheDirectory = $this->cacheFile->getDirectory();
			$fileSize = $this->cacheFile->getFileSize();
			if (defined("BX_COMPOSITE_DEBUG"))
			{
				$backupName = $this->cacheFile->getPath().".delete.".microtime(true);
				AddMessage2Log($backupName, "composite");
				$backupFile = new Main\IO\File($backupName);
				$backupFile->putContents($this->cacheFile->getContents());
			}
			$this->cacheFile->delete();
			//Try to cleanup directory
			$children = $cacheDirectory->getChildren();
			if (empty($children))
				$cacheDirectory->delete();
			//Update total files size
			$this->writeStatistic(0, 0, 0, 0, -$fileSize);
		}
	}

	/**
	 * Returns true if file exists
	 * with predefined path (current request uri)
	 *
	 * @return bool
	 */
	public function isExists()
	{
		if ($this->cacheFile)
		{
			return $this->cacheFile->isExists();
		}
		else
		{
			return false;
		}
	}

	/**
	 * Returns true if file exists
	 * with predefined path (current request uri)
	 *
	 * @return bool
	 */
	public function isCacheable()
	{
		if ($this->cacheFile)
		{
			return $this->canCache;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Marks current page as non cacheable.
	 *
	 * @return void
	 */
	public function markNonCacheable()
	{
		$this->canCache = false;
	}

	/**
	 * Returns substring from the file.
	 *
	 * @param int $offset
	 * @param int $length
	 * @return string
	 */
	public function getSubstring($offset, $length)
	{
		if ($this->isExists())
		{
			return substr($this->read(), $offset, $length);
		}
		return "";
	}

	/**
	 * Returns array with cache statistics data.
	 * Returns an empty array in case of disabled html cache.
	 *
	 * @return array
	 */
	public function readStatistic()
	{
		$result = array();
		if(
			$this->statFile
			&& $this->statFile->isExists()
		)
		{
			$fileValues = explode(",", $this->statFile->getContents());
			$result = array(
				"HITS" => intval($fileValues[0]),
				"MISSES" => intval($fileValues[1]),
				"QUOTA" => intval($fileValues[2]),
				"POSTS" => intval($fileValues[3]),
				"FILE_SIZE" => doubleval($fileValues[4]),
			);
		}
		return $result;
	}

	/**
	 * Updates cache usage statistics.
	 * Each of parameters is added to appropriate existing stats.
	 *
	 * @param int $hit
	 * @param int $miss
	 * @param int $quota
	 * @param int $posts
	 * @param float $files
	 * @return void
	 */
	public function writeStatistic($hit = 0, $miss = 0, $quota = 0, $posts = 0, $files = 0.0)
	{
		$fileValues = $this->readStatistic();
		if($fileValues)
		{
			$newValues = array(
				intval($fileValues["HITS"]) + $hit,
				intval($fileValues["MISSES"]) + $miss,
				intval($fileValues["QUOTA"]) + $quota,
				intval($fileValues["POSTS"]) + $posts,
				$files === false? 0: doubleval($fileValues["FILE_SIZE"]) + doubleval($files),
			);
			$this->statFile->putContents(implode(",", $newValues));
		}
	}

}
