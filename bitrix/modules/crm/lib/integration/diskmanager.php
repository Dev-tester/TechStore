<?php
/**
 * Created by PhpStorm.
 * User: zg
 * Date: 22.11.2014
 * Time: 18:13
 */

namespace Bitrix\Crm\Integration;
use Bitrix\Disk\Driver;
use Bitrix\Disk\File;
use Bitrix\Disk\Folder;
use Bitrix\Disk\SystemUser;
use Bitrix\Disk\Ui\Text;
use Bitrix\Main\Loader;
use Bitrix\Main\EventResult;

class DiskManager
{
	private static $defaultSiteID = null;
	private static function getDefaultSiteID()
	{
		if(self::$defaultSiteID !== null)
		{
			return self::$defaultSiteID;
		}

		$siteEntity = new \CSite();
		$dbSites = $siteEntity->GetList($by = 'sort', $order = 'desc', array('DEFAULT' => 'Y', 'ACTIVE' => 'Y'));
		$defaultSite = is_object($dbSites) ? $dbSites->Fetch() : null;
		if(is_array($defaultSite))
		{
			return (self::$defaultSiteID = $defaultSite['LID']);
		}

		return (self::$defaultSiteID = 's1');
	}
	public static function checkFileReadPermission($fileID, $userID = 0)
	{
		if(!Loader::includeModule('disk'))
		{
			return false;
		}

		/** @var File $file */
		$file = File::loadById($fileID);
		if(!$file)
		{
			return false;
		}

		$userID = (int)$userID;
		if($userID <= 0)
		{
			$userID = \CCrmSecurityHelper::getCurrentUserID();
		}

		return $file->canRead($file->getStorage()->getSecurityContext($userID));
	}
	/**
	 * @param int $fileID
	 * @return string
	 */
	public static function getFileName($fileID)
	{
		if(!Loader::includeModule('disk'))
		{
			return "[{$fileID}]";
		}

		$fileID = (int)$fileID;
		if($fileID <= 0)
		{
			return "[{$fileID}]";
		}

		/** @var File $file */
		$file = File::loadById($fileID);
		return $file ? $file->getName() : "[{$fileID}]";
	}
	public static function getFileInfo($fileID, $checkPermissions = true, $options = null)
	{
		if(!Loader::includeModule('disk'))
		{
			return null;
		}

		$fileID = (int)$fileID;
		if($fileID <= 0)
		{
			return null;
		}

		/** @var File $file */
		$file = File::loadById($fileID);
		if(!$file)
		{
			return null;
		}

		if(!is_array($options))
		{
			$options = array();
		}

		$ownerID = isset($options['OWNER_ID']) ? $options['OWNER_ID'] : 0;
		$ownerTypeID = isset($options['OWNER_TYPE_ID']) ? $options['OWNER_TYPE_ID'] : \CCrmOwnerType::Undefined;

		$canRead = true;
		$viewUrl = '';
		if($ownerID > 0 && \CCrmOwnerType::isDefined($ownerTypeID))
		{
			$viewUrlParams = array('fileId' => $fileID, 'ownerTypeId' => $ownerTypeID, 'ownerId' => $ownerID);
			if(isset($options['VIEW_PARAMS']) && is_array($options['VIEW_PARAMS']))
			{
				$viewUrlParams = array_merge($viewUrlParams, $options['VIEW_PARAMS']);
			}
			$viewUrl = \CHTTP::urlAddParams('/bitrix/tools/crm_show_file.php', $viewUrlParams);
			if(isset($options['USE_ABSOLUTE_PATH']) && $options['USE_ABSOLUTE_PATH'])
			{
				$viewUrl = \CCrmUrlUtil::ToAbsoluteUrl($viewUrl);
			}
		}
		elseif($checkPermissions)
		{
			$canRead = $file->canRead($file->getStorage()->getSecurityContext(\CCrmSecurityHelper::getCurrentUserID()));
			if($canRead)
			{
				$viewUrl = Driver::getInstance()->getUrlManager()->getUrlForDownloadFile($file);
			}
		}

		$previewUrl = '';
		if (!empty($viewUrl))
		{
			if (\Bitrix\Disk\TypeFile::isImage($file))
				$previewUrl = \CHTTP::urlAddParams($viewUrl, array('preview' => 'Y'));
		}

		return array(
			'ID' => $fileID,
			'FILE_ID' => $file->getFileId(),
			'NAME' => $file->getName(),
			'SIZE' => \CFile::FormatSize($file->getSize()),
			'CAN_READ' => $canRead,
			'VIEW_URL' => $viewUrl,
			'PREVIEW_URL' => $previewUrl,
		);
	}
	public static function makeFileArray($fileID)
	{
		if(!Loader::includeModule('disk'))
		{
			return null;
		}

		/** @var File $file */
		$file = File::loadById($fileID);
		if(!$file)
		{
			return null;
		}

		$originalFileID = $file->getFileId();
		if($originalFileID <= 0)
		{
			return null;
		}

		$fileData = \CFile::MakeFileArray($originalFileID);
		$fileData['ORIGINAL_NAME'] = $file->getName();
		return $fileData;
	}
	/**
	 * @param string $siteId
	 * @return \Bitrix\Disk\Storage|null
	 */
	public static function getStorage($siteID = '')
	{
		if(!Loader::includeModule('disk'))
		{
			return null;
		}

		if($siteID === '')
		{
			$siteID = self::getDefaultSiteID();
		}

		return Driver::getInstance()->getStorageByCommonId('shared_files_'.$siteID);
	}
	/**
	 * @param int $typeID
	 * @param string $siteID
	 * @return \Bitrix\Disk\Folder|null
	 */
	public static function ensureFolderCreated($typeID, $siteID = '')
	{
		if(!Loader::includeModule('disk'))
		{
			return null;
		}

		if(!StorageFileType::isDefined($typeID))
		{
			return null;
		}

		if($siteID === '')
		{
			$siteID = self::getDefaultSiteID();
		}

		$xmlID = StorageFileType::getFolderXmlID($typeID);
		$name = StorageFileType::getFolderName($typeID);

		$storage = self::getStorage($siteID);
		if (!$storage)
		{
			return null;
		}

		$folderModel = Folder::load(
			array(
				'STORAGE_ID' => $storage->getId(),
				'PARENT_ID' => $storage->getRootObjectId(),
				'=XML_ID' => $xmlID,
			)
		);

		if ($folderModel)
		{
			return $folderModel;
		}

		$rights = array();
		if($typeID === StorageFileType::EmailAttachment)
		{
			$specificRights = Driver::getInstance()->getRightsManager()->getSpecificRights($storage->getRootObject());
			foreach ($specificRights as $right)
			{
				unset($right['ID'], $right['DOMAIN'], $right['OBJECT_ID']);
				$right['NEGATIVE'] = true;
				$rights[] = $right;
			}
		}

		return $storage->addFolder(
			array(
				'NAME' => $name,
				'XML_ID' => $xmlID,
				'CREATED_BY' => SystemUser::SYSTEM_USER_ID
			),
			$rights,
			true
		);
	}
	/**
	 * @param array $fileData
	 * @param string $siteID
	 * @return int|false
	 */
	public static function saveEmailAttachment(array $fileData, $siteID = '', $params = array())
	{
		$params['TYPE_ID'] = StorageFileType::EmailAttachment;
		return self::saveFile($fileData, $siteID, $params);
	}
	/**
	 * @param array $fileData
	 * @param string $siteID
	 * @param array $params
	 * @return int|false
	 */
	public static function saveFile(array $fileData, $siteID = '', $params = array())
	{
		if (!(IsModuleInstalled('disk')
			&& Loader::includeModule('disk')))
		{
			return false;
		}

		if($siteID === '')
		{
			$siteID = self::getDefaultSiteID();
		}

		if(!is_array($params))
		{
			$params = array();
		}

		$typeID = isset($params['TYPE_ID']) ? (int)$params['TYPE_ID'] : StorageFileType::Undefined;
		if(!StorageFileType::IsDefined($typeID))
		{
			$typeID = StorageFileType::EmailAttachment;
		}

		$folder = self::ensureFolderCreated($typeID, $siteID);
		if(!$folder)
		{
			return false;
		}

		$userID = isset($params['USER_ID']) ? (int)$params['USER_ID'] : 0;
		if($userID <= 0)
		{
			$userID = \CCrmSecurityHelper::GetCurrentUserID();
		}
		if($userID <= 0)
		{
			$userID = SystemUser::SYSTEM_USER_ID;
		}

		$file = $folder->addFile(
			array(
				'NAME' => Text::correctFilename($fileData['ORIGINAL_NAME']),
				'FILE_ID' => (int)$fileData['ID'],
				'SIZE' => (int)$fileData['FILE_SIZE'],
				'CREATED_BY' => $userID,
		), array(), true);

		return $file ? $file->getId() : false;
	}

	public static function OnDiskFileDelete($objectID, $deletedByUserID)
	{
		$objectID = (int)$objectID;
		if($objectID <= 0)
		{
			return;
		}

		\CCrmActivity::HandleStorageElementDeletion(StorageType::Disk, $objectID);
		\CCrmQuote::HandleStorageElementDeletion(StorageType::Disk, $objectID);
	}

	public static function writeFileToResponse($fileID, $options = array())
	{
		if(!Loader::includeModule('disk'))
		{
			return;
		}

		$file = File::loadById($fileID);
		if(!$file)
		{
			return;
		}
		$fileData = $file->getFile();
		if(!$fileData)
		{
			return;
		}

		if (!empty($options['preview']))
		{
			$tmpFile = \CFile::resizeImageGet(
				$fileData,
				array('width' => 100, 'height' => 100),
				BX_RESIZE_IMAGE_EXACT,
				true, false, true
			);

			if ($tmpFile['src'] && $tmpFile['width'] > 0 && $tmpFile['height'] > 0)
			{
				$fileData['FILE_SIZE'] = $tmpFile['size'];
				$fileData['SRC'] = $tmpFile['src'];
			}
		}

		\CFile::viewByUser($fileData, array('force_download' => false, 'cache_time' => 0, 'attachment_name' => $file->getName()));
	}

	/**
	 * Returns available entities for tasks module
	 * @return EventResult
	 */
	public static function onBuildConnectorList()
	{
		return new EventResult(EventResult::SUCCESS, array(
			'TASK' => array(
				'ENTITY_TYPE' => 'crm_timeline', // should match entity type from user fields: CRM_TIMELINE
				'MODULE_ID' => 'crm',
				'CLASS' => Disk\CommentConnector::className()
			)
		));
	}
}