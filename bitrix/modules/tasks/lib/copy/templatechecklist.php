<?
namespace Bitrix\Tasks\Copy;

use Bitrix\Main\Copy\Copyable;
use Bitrix\Main\Copy\ContainerManager;
use Bitrix\Main\Result;
use Bitrix\Tasks\Copy\Entity\TemplateCheckList as TemplateCheckListEntity;

class TemplateChecklist implements Copyable
{
	private $checkList;
	private $executiveUserId;

	/**
	 * @var Result
	 */
	private $result;

	public function __construct(TemplateCheckListEntity $checkList, $executiveUserId)
	{
		$this->checkList = $checkList;
		$this->executiveUserId = $executiveUserId;

		$this->result = new Result();
	}

	/**
	 * Copies template checklists.
	 *
	 * @param ContainerManager $containerManager The object with data to copy.
	 * @return Result
	 */
	public function copy(ContainerManager $containerManager)
	{
		$containers = $containerManager->getContainers();

		/** @var Container[] $containers */
		foreach ($containers as $container)
		{
			$checkListItems = $this->checkList->getCheckListItemsByEntityId($container->getEntityId());

			if ($this->checkList->hasErrors())
			{
				$this->result->addErrors($this->checkList->getErrors());
			}
			else
			{
				$this->checkList->add($container->getCopiedEntityId(), $this->executiveUserId, $checkListItems);
			}
		}

		return $this->result;
	}
}