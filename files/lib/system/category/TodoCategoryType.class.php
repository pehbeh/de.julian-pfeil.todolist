<?php
namespace todolist\system\category;

use todolist\data\category\TodoCategory;
use todolist\data\todo\TodoAction;
use todolist\data\todo\TodoList;

use wcf\data\category\CategoryEditor;
use wcf\system\category\AbstractCategoryType;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Class TodoCategoryType
 *
 * @author  Julian Pfeil <https://julian-pfeil.de>
 * @copyright   2022 Julian Pfeil Websites & Co.
 * @license Creative Commons <by> <https://creativecommons.org/licenses/by/4.0/legalcode>
 */
class TodoCategoryType extends AbstractCategoryType
{
    /**
     * @inheritDoc
     */
    protected $langVarPrefix = 'todolist.todo.category';

    /**
     * @inheritDoc
     */
    protected $forceDescription = false;

    /**
     * @inheritDoc
     */
    protected $maximumNestingLevel = 0;

    /**
     * @inheritDoc
     */
    protected $objectTypes = ['com.woltlab.wcf.acl' => TodoCategory::OBJECT_TYPE_NAME];

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function afterDeletion(CategoryEditor $categoryEditor)
    {
        // delete images with no categories
        $todoList = new TodoList();
        $todoList->getConditionBuilder()->add("todo.categoryID IS NULL");
        $todoList->readObjects();

        if (count($todoList)) {
            $ticketAction = new TodoAction($todoList->getObjects(), 'delete');
            $ticketAction->executeAction();
        }
    }

    /**
     * @inheritDoc
     */
    public function getApplication()
    {
        return 'todolist';
    }

    /**
     * @inheritDoc
     */
    public function canAddCategory()
    {
        return $this->canEditCategory();
    }

    /**
     * @inheritDoc
     */
    public function canEditCategory()
    {
        return WCF::getSession()->getPermission('admin.todolist.general.canManageCategory');
    }

    /**
     * @inheritDoc
     */
    public function canDeleteCategory()
    {
        return $this->canEditCategory();
    }
}