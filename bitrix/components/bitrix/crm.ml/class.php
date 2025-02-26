<?

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ErrorCollection;
use Bitrix\Main\Loader;
use Bitrix\Main\Error;

use Bitrix\Crm\Tracking;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

Loc::loadMessages(__FILE__);

class CrmMlComponent extends CBitrixComponent
{
	/** @var ErrorCollection $errors */
	protected $errors;

	protected function checkRequiredParams()
	{
		if (!Loader::includeModule('crm'))
		{
			$this->errors->setError(new Error('Module `crm` is not installed.'));
			return false;
		}
		return true;
	}

	protected function initParams()
	{
		$this->arParams['SEF_MODE'] = isset($this->arParams['SEF_MODE']) ? $this->arParams['SEF_MODE'] : 'Y';
		$this->arParams['SEF_FOLDER'] = isset($this->arParams['SEF_FOLDER']) ? $this->arParams['SEF_FOLDER'] : '';
		$this->arParams['ELEMENT_ID'] = isset($this->arParams['ELEMENT_ID']) ? $this->arParams['ELEMENT_ID'] : $this->request->get('id');

		$this->arParams['IFRAME'] = isset($this->arParams['IFRAME']) ? $this->arParams['IFRAME'] : true;
		$this->arResult['NAME_TEMPLATE'] = empty($this->arParams['NAME_TEMPLATE']) ? CSite::GetNameFormat(false) : str_replace(array("#NOBR#","#/NOBR#"), array("",""), $this->arParams["NAME_TEMPLATE"]);
		$this->arResult['PATH_TO_USER_PROFILE'] = isset($this->arParams['PATH_TO_USER_PROFILE']) ? $this->arParams['PATH_TO_USER_PROFILE'] : '/company/personal/user/#id#/';

		if (!isset($this->arParams['VARIABLE_ALIASES']))
		{
			$this->arParams['VARIABLE_ALIASES'] = array(
				"detail" => [],
			);
		}

		$arDefaultUrlTemplates404 = array(
			"detail" => "#entity_type#/#id#/detail",
		);

		if ($this->arParams['SEF_MODE'] == 'Y')
		{
			$arDefaultVariableAliases404 = array();
			$arComponentVariables = array('id');
			$arVariables = array();
			$arUrlTemplates = CComponentEngine::makeComponentUrlTemplates($arDefaultUrlTemplates404, $this->arParams['SEF_URL_TEMPLATES']);
			$arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases404, $this->arParams['VARIABLE_ALIASES']);
			$componentPage = CComponentEngine::parseComponentPath($this->arParams['SEF_FOLDER'], $arUrlTemplates, $arVariables);

			CComponentEngine::initComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);
			foreach ($arUrlTemplates as $url => $value)
			{
				$key = 'PATH_TO_'.strtoupper($url);
				$this->arResult[$key] = isset($this->arParams[$key][0]) ? $this->arParams[$key] : $this->arParams['SEF_FOLDER'] . $value;
			}

		}
		else
		{
			$arComponentVariables = array(
				isset($this->arParams['VARIABLE_ALIASES']['id']) ? $this->arParams['VARIABLE_ALIASES']['id'] : 'id'
			);

			$arDefaultVariableAliases = array(
				'id' => 'id'
			);
			$arVariables = array();
			$arVariableAliases = CComponentEngine::makeComponentVariableAliases($arDefaultVariableAliases, $this->arParams['VARIABLE_ALIASES']);
			CComponentEngine::initComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

			if (isset($_REQUEST['edit']))
			{
				$componentPage = 'edit';
			}

			/**	@var \CAllMain $APPLICATION */
			global $APPLICATION;
			foreach ($arDefaultUrlTemplates404 as $url => $value)
			{
				$key = 'PATH_TO_'.strtoupper($url);
				$value = substr($value, 0, -1);
				$value = str_replace('/', '&ID=', $value);
				$lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : null;
				$this->arResult[$key] = $APPLICATION->GetCurPage() . "?$value" . ($lang ? "&lang=$lang" : '');
			}
		}

		if (!is_array($this->arResult))
		{
			$this->arResult = array();
		}

		$this->arResult = array_merge(
			array(
				'COMPONENT_PAGE' => $componentPage,
				'VARIABLES' => $arVariables,
				'ALIASES' => $this->arParams['SEF_MODE'] == 'Y' ? array(): $arVariableAliases,
				'ID' => isset($arVariables['id']) ? intval($arVariables['id']) : '',
				'TYPE' => isset($arVariables['entity_type']) ? strval($arVariables['entity_type']) : '',
				'PATH_TO_USER_PROFILE' => $this->arParams['PATH_TO_USER_PROFILE']
			),
			$this->arResult
		);
	}

	protected function prepareResult()
	{
		return true;
	}

	protected function printErrors()
	{
		foreach ($this->errors as $error)
		{
			ShowError($error);
		}
	}

	public function executeComponent()
	{
		$this->errors = new \Bitrix\Main\ErrorCollection();
		$this->initParams();
		if (!$this->checkRequiredParams())
		{
			$this->printErrors();
			return;
		}

		if (!$this->prepareResult())
		{
			$this->printErrors();
			return;
		}

		if($this->arResult['COMPONENT_PAGE'] != '')
		{
			$this->includeComponentTemplate($this->arResult['COMPONENT_PAGE']);
		}
		else
		{
			LocalRedirect('/crm/');
		}
	}
}