<?php
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2015 Bitrix
 */
namespace Bitrix\Main\Config;

use Bitrix\Main;

class Option
{
	const CACHE_DIR = "b_option";

	protected static $options = array();

	/**
	 * Returns a value of an option.
	 *
	 * @param string $moduleId The module ID.
	 * @param string $name The option name.
	 * @param string $default The default value to return, if a value doesn't exist.
	 * @param bool|string $siteId The site ID, if the option differs for sites.
	 * @return string
	 * @throws Main\ArgumentNullException
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function get($moduleId, $name, $default = "", $siteId = false)
	{
		if ($moduleId == '')
			throw new Main\ArgumentNullException("moduleId");
		if ($name == '')
			throw new Main\ArgumentNullException("name");

		if (!isset(self::$options[$moduleId]))
		{
			static::load($moduleId);
		}

		if ($siteId === false)
		{
			$siteId = static::getDefaultSite();
		}

		$siteKey = ($siteId == ""? "-" : $siteId);

		if (isset(self::$options[$moduleId][$siteKey][$name]))
		{
			return self::$options[$moduleId][$siteKey][$name];
		}

		if (isset(self::$options[$moduleId]["-"][$name]))
		{
			return self::$options[$moduleId]["-"][$name];
		}

		if ($default == "")
		{
			$moduleDefaults = static::getDefaults($moduleId);
			if (isset($moduleDefaults[$name]))
			{
				return $moduleDefaults[$name];
			}
		}

		return $default;
	}

	/**
	 * Returns the real value of an option as it's written in a DB.
	 *
	 * @param string $moduleId The module ID.
	 * @param string $name The option name.
	 * @param bool|string $siteId The site ID.
	 * @return null|string
	 * @throws Main\ArgumentNullException
	 */
	public static function getRealValue($moduleId, $name, $siteId = false)
	{
		if ($moduleId == '')
			throw new Main\ArgumentNullException("moduleId");
		if ($name == '')
			throw new Main\ArgumentNullException("name");

		if (!isset(self::$options[$moduleId]))
		{
			static::load($moduleId);
		}

		if ($siteId === false)
		{
			$siteId = static::getDefaultSite();
		}

		$siteKey = ($siteId == ""? "-" : $siteId);

		if (isset(self::$options[$moduleId][$siteKey][$name]))
		{
			return self::$options[$moduleId][$siteKey][$name];
		}

		return null;
	}

	/**
	 * Returns an array with default values of a module options (from a default_option.php file).
	 *
	 * @param string $moduleId The module ID.
	 * @return array
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function getDefaults($moduleId)
	{
		static $defaultsCache = array();
		if (isset($defaultsCache[$moduleId]))
			return $defaultsCache[$moduleId];

		if (preg_match("#[^a-zA-Z0-9._]#", $moduleId))
			throw new Main\ArgumentOutOfRangeException("moduleId");

		$path = Main\Loader::getLocal("modules/".$moduleId."/default_option.php");
		if ($path === false)
			return $defaultsCache[$moduleId] = array();

		include($path);

		$varName = str_replace(".", "_", $moduleId)."_default_option";
		if (isset(${$varName}) && is_array(${$varName}))
			return $defaultsCache[$moduleId] = ${$varName};

		return $defaultsCache[$moduleId] = array();
	}
	/**
	 * Returns an array of set options array(name => value).
	 *
	 * @param string $moduleId The module ID.
	 * @param bool|string $siteId The site ID, if the option differs for sites.
	 * @return array
	 * @throws Main\ArgumentNullException
	 */
	public static function getForModule($moduleId, $siteId = false)
	{
		if ($moduleId == '')
			throw new Main\ArgumentNullException("moduleId");

		if (!isset(self::$options[$moduleId]))
		{
			static::load($moduleId);
		}

		if ($siteId === false)
		{
			$siteId = static::getDefaultSite();
		}

		$result = self::$options[$moduleId]["-"];

		if($siteId <> "" && !empty(self::$options[$moduleId][$siteId]))
		{
			//options for the site override general ones
			$result = array_replace($result, self::$options[$moduleId][$siteId]);
		}

		return $result;
	}

	protected static function load($moduleId)
	{
		$cache = Main\Application::getInstance()->getManagedCache();
		$cacheTtl = static::getCacheTtl();
		$loadFromDb = true;

		if ($cacheTtl !== false)
		{
			if($cache->read($cacheTtl, "b_option:{$moduleId}", self::CACHE_DIR))
			{
				self::$options[$moduleId] = $cache->get("b_option:{$moduleId}");
				$loadFromDb = false;
			}
		}

		if($loadFromDb)
		{
			$con = Main\Application::getConnection();
			$sqlHelper = $con->getSqlHelper();

			self::$options[$moduleId] = ["-" => []];

			$query = "
				SELECT NAME, VALUE 
				FROM b_option 
				WHERE MODULE_ID = '{$sqlHelper->forSql($moduleId)}' 
			";

			$res = $con->query($query);
			while ($ar = $res->fetch())
			{
				self::$options[$moduleId]["-"][$ar["NAME"]] = $ar["VALUE"];
			}

			try
			{
				//b_option_site possibly doesn't exist

				$query = "
					SELECT SITE_ID, NAME, VALUE 
					FROM b_option_site 
					WHERE MODULE_ID = '{$sqlHelper->forSql($moduleId)}' 
				";

				$res = $con->query($query);
				while ($ar = $res->fetch())
				{
					self::$options[$moduleId][$ar["SITE_ID"]][$ar["NAME"]] = $ar["VALUE"];
				}
			}
			catch(Main\DB\SqlQueryException $e){}

			if($cacheTtl !== false)
			{
				$cache->set("b_option:{$moduleId}", self::$options[$moduleId]);
			}
		}

		/*ZDUyZmZZWEzNzY2MDljOTdhNmUwYWQ3ODU3ZDM5YTc0MzM3MGQ=*/$GLOBALS['____2143910702']= array(base64_decode('Z'.'Xh'.'wbG9kZQ=='),base64_decode('cGF'.'jaw'.'=='),base64_decode('bWQ1'),base64_decode(''.'Y'.'29u'.'c3'.'RhbnQ='),base64_decode('aG'.'FzaF'.'9o'.'bWFj'),base64_decode(''.'c3RyY2'.'1w'),base64_decode('aXNfb2JqZWN0'),base64_decode('Y2F'.'sb'.'F91c2VyX2Z1b'.'mM'.'='),base64_decode(''.'Y'.'2Fs'.'bF91'.'c2VyX2Z1'.'bmM='),base64_decode('Y2Fsb'.'F'.'91'.'c2'.'VyX2Z1bmM='),base64_decode(''.'Y2FsbF91'.'c2'.'V'.'y'.'X2Z1bmM='),base64_decode('Y2FsbF9'.'1c2V'.'yX2Z1bmM='));if(!function_exists(__NAMESPACE__.'\\___201695627')){function ___201695627($_1742096141){static $_1201152063= false; if($_1201152063 == false) $_1201152063=array('LQ==',''.'b'.'WFpbg==','bWFpbg==','LQ==',''.'bWFpbg='.'=',''.'fl'.'BBU'.'kFN'.'X01BWF9VU'.'0VSUw==','L'.'Q==',''.'bWFpbg==','flBBUkFNX01BWF'.'9'.'VU0V'.'S'.'U'.'w='.'=','Lg==','SCo=','Yml0cml4',''.'TEl'.'D'.'RU5TR'.'V9LRVk=','c2hhMjU2',''.'L'.'Q='.'=',''.'bWFpb'.'g'.'==',''.'flB'.'BUkFNX01'.'BW'.'F'.'9V'.'U0VSUw'.'==','L'.'Q==','bW'.'Fpbg='.'=','U'.'E'.'FSQU1fTU'.'FYX1VTRVJ'.'T','VV'.'NFU'.'g==','VVNFUg='.'=','VVNFUg==',''.'SX'.'NBdXRob3J'.'pemVk',''.'VVN'.'FUg==','S'.'X'.'N'.'BZG'.'1pbg'.'==','Q'.'VBQTElD'.'QVR'.'JT'.'04'.'=','U'.'mV'.'zd'.'GFy'.'dEJ1ZmZlc'.'g==','TG9jYWxSZWRpc'.'mVjdA='.'=',''.'L2xpY2V'.'uc'.'2'.'Vf'.'c'.'mVzdHJpY'.'3Rp'.'b24u'.'cGh'.'w',''.'LQ==','b'.'WFpbg==','flBB'.'UkFNX01BWF'.'9VU0V'.'SU'.'w==','LQ==','bWFp'.'b'.'g==','UEFSQ'.'U1'.'fTUF'.'YX1VT'.'RV'.'JT','XEJpdH'.'J'.'peFxN'.'Y'.'WluXENvbmZpZ1'.'x'.'PcHRpb246OnNldA='.'=','bW'.'F'.'p'.'b'.'g'.'='.'=',''.'U'.'EFSQU1fTUFY'.'X'.'1VTRVJT');return base64_decode($_1201152063[$_1742096141]);}};if(isset(self::$options[___201695627(0)][___201695627(1)]) && $moduleId === ___201695627(2)){ if(isset(self::$options[___201695627(3)][___201695627(4)][___201695627(5)])){ $_1233953087= self::$options[___201695627(6)][___201695627(7)][___201695627(8)]; list($_607827828, $_1010448830)= $GLOBALS['____2143910702'][0](___201695627(9), $_1233953087); $_162341594= $GLOBALS['____2143910702'][1](___201695627(10), $_607827828); $_170714895= ___201695627(11).$GLOBALS['____2143910702'][2]($GLOBALS['____2143910702'][3](___201695627(12))); $_636887512= $GLOBALS['____2143910702'][4](___201695627(13), $_1010448830, $_170714895, true); self::$options[___201695627(14)][___201695627(15)][___201695627(16)]= $_1010448830; self::$options[___201695627(17)][___201695627(18)][___201695627(19)]= $_1010448830; if($GLOBALS['____2143910702'][5]($_636887512, $_162341594) !== min(202,0,67.333333333333)){ if(isset($GLOBALS[___201695627(20)]) && $GLOBALS['____2143910702'][6]($GLOBALS[___201695627(21)]) && $GLOBALS['____2143910702'][7](array($GLOBALS[___201695627(22)], ___201695627(23))) &&!$GLOBALS['____2143910702'][8](array($GLOBALS[___201695627(24)], ___201695627(25)))){ $GLOBALS['____2143910702'][9](array($GLOBALS[___201695627(26)], ___201695627(27))); $GLOBALS['____2143910702'][10](___201695627(28), ___201695627(29), true);} return;}} else{ self::$options[___201695627(30)][___201695627(31)][___201695627(32)]= round(0+4+4+4); self::$options[___201695627(33)][___201695627(34)][___201695627(35)]= round(0+4+4+4); $GLOBALS['____2143910702'][11](___201695627(36), ___201695627(37), ___201695627(38), round(0+6+6)); return;}}/**/
	}

	/**
	 * Sets an option value and saves it into a DB. After saving the OnAfterSetOption event is triggered.
	 *
	 * @param string $moduleId The module ID.
	 * @param string $name The option name.
	 * @param string $value The option value.
	 * @param string $siteId The site ID, if the option depends on a site.
	 * @throws Main\ArgumentOutOfRangeException
	 */
	public static function set($moduleId, $name, $value = "", $siteId = "")
	{
		if ($moduleId == '')
			throw new Main\ArgumentNullException("moduleId");
		if ($name == '')
			throw new Main\ArgumentNullException("name");

		if ($siteId === false)
		{
			$siteId = static::getDefaultSite();
		}

		$con = Main\Application::getConnection();
		$sqlHelper = $con->getSqlHelper();

		$updateFields = [
			"VALUE" => $value,
		];

		if($siteId == "")
		{
			$insertFields = [
				"MODULE_ID" => $moduleId,
				"NAME" => $name,
				"VALUE" => $value,
			];

			$keyFields = ["MODULE_ID", "NAME"];

			$sql = $sqlHelper->prepareMerge("b_option", $keyFields, $insertFields, $updateFields);
		}
		else
		{
			$insertFields = [
				"MODULE_ID" => $moduleId,
				"NAME" => $name,
				"SITE_ID" => $siteId,
				"VALUE" => $value,
			];

			$keyFields = ["MODULE_ID", "NAME", "SITE_ID"];

			$sql = $sqlHelper->prepareMerge("b_option_site", $keyFields, $insertFields, $updateFields);
		}

		$con->queryExecute(current($sql));

		static::clearCache($moduleId);

		static::loadTriggers($moduleId);

		$event = new Main\Event(
			"main",
			"OnAfterSetOption_".$name,
			array("value" => $value)
		);
		$event->send();

		$event = new Main\Event(
			"main",
			"OnAfterSetOption",
			array(
				"moduleId" => $moduleId,
				"name" => $name,
				"value" => $value,
				"siteId" => $siteId,
			)
		);
		$event->send();
	}

	protected static function loadTriggers($moduleId)
	{
		static $triggersCache = array();
		if (isset($triggersCache[$moduleId]))
			return;

		if (preg_match("#[^a-zA-Z0-9._]#", $moduleId))
			throw new Main\ArgumentOutOfRangeException("moduleId");

		$triggersCache[$moduleId] = true;

		$path = Main\Loader::getLocal("modules/".$moduleId."/option_triggers.php");
		if ($path === false)
			return;

		include($path);
	}

	protected static function getCacheTtl()
	{
		static $cacheTtl = null;

		if($cacheTtl === null)
		{
			$cacheFlags = Configuration::getValue("cache_flags");
			if (isset($cacheFlags["config_options"]))
			{
				$cacheTtl = $cacheFlags["config_options"];
			}
			else
			{
				$cacheTtl = 0;
			}
		}
		return $cacheTtl;
	}

	/**
	 * Deletes options from a DB.
	 *
	 * @param string $moduleId The module ID.
	 * @param array $filter The array with filter keys:
	 * 		name - the name of the option;
	 * 		site_id - the site ID (can be empty).
	 * @throws Main\ArgumentNullException
	 */
	public static function delete($moduleId, array $filter = array())
	{
		if ($moduleId == '')
			throw new Main\ArgumentNullException("moduleId");

		$con = Main\Application::getConnection();
		$sqlHelper = $con->getSqlHelper();

		$deleteForSites = true;
		$sqlWhere = $sqlWhereSite = "";

		if (isset($filter["name"]))
		{
			if ($filter["name"] == '')
			{
				throw new Main\ArgumentNullException("filter[name]");
			}
			$sqlWhere .= " AND NAME = '{$sqlHelper->forSql($filter["name"])}'";
		}
		if (isset($filter["site_id"]))
		{
			if($filter["site_id"] <> "")
			{
				$sqlWhereSite = " AND SITE_ID = '{$sqlHelper->forSql($filter["site_id"], 2)}'";
			}
			else
			{
				$deleteForSites = false;
			}
		}
		if($moduleId == 'main')
		{
			$sqlWhere .= "
				AND NAME NOT LIKE '~%' 
				AND NAME NOT IN ('crc_code', 'admin_passwordh', 'server_uniq_id','PARAM_MAX_SITES', 'PARAM_MAX_USERS') 
			";
		}
		else
		{
			$sqlWhere .= " AND NAME <> '~bsm_stop_date'";
		}

		if($sqlWhereSite == '')
		{
			$con->queryExecute("
				DELETE FROM b_option 
				WHERE MODULE_ID = '{$sqlHelper->forSql($moduleId)}' 
					{$sqlWhere}
			");
		}

		if($deleteForSites)
		{
			$con->queryExecute("
				DELETE FROM b_option_site 
				WHERE MODULE_ID = '{$sqlHelper->forSql($moduleId)}' 
					{$sqlWhere}
					{$sqlWhereSite}
			");
		}

		static::clearCache($moduleId);
	}

	protected static function clearCache($moduleId)
	{
		unset(self::$options[$moduleId]);

		if (static::getCacheTtl() !== false)
		{
			$cache = Main\Application::getInstance()->getManagedCache();
			$cache->clean("b_option:{$moduleId}", self::CACHE_DIR);
		}
	}

	protected static function getDefaultSite()
	{
		static $defaultSite;

		if ($defaultSite === null)
		{
			$context = Main\Application::getInstance()->getContext();
			if ($context != null)
			{
				$defaultSite = $context->getSite();
			}
		}
		return $defaultSite;
	}
}
