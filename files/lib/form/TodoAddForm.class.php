<?php

namespace todolist\form;

use todolist\data\todo\TodoAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\MultilineTextFormField;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\WCF;
use wcf\system\request\LinkHandler;

/**
 * Shows the form to create a new todo.
 *
 * @author  Julian Pfeil <https://julian-pfeil.de>
 * @copyright   2022 Julian Pfeil Websites & Co.
 * @license Creative Commons <by> <https://creativecommons.org/licenses/by/4.0/legalcode>
 * @package WoltLabSuite\Core\Form
 */
class TodoAddForm extends AbstractFormBuilderForm
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'de.julian-pfeil.todolist.AddTodo';

    /**
     * @inheritDoc
     */
    public $formAction = 'create';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['user.todolist.canAddTodos'];

    /**
     * @inheritDoc
     */
    public $objectActionClass = TodoAction::class;

    /**
     * @inheritDoc
     */
    public $objectEditLinkController = TodoEditForm::class;

    /**
     * @inheritDoc
     */
    protected function createForm()
    {
        parent::createForm();

        $this->form->appendChild(
            FormContainer::create('data')
                ->label('wcf.global.form.data')
                ->appendChildren([
                    TextFormField::create('todoName')
                        ->label('todolist.column.todoName')
                        ->required()
                        ->autoFocus()
                        ->maximumLength(255),
						
                    MultilineTextFormField::create('description')
                        ->label('todolist.column.description')
                        ->required()
                        ->maximumLength(8192),
						
                    BooleanFormField::create('done')
                        ->label('todolist.column.done')
                        ->value(false),
                ])
        );
    }

    /**
     * @inheritDoc
     */
    public function save()
    {
        parent::save();

        if ($this->formAction == 'create')
        {
            WCF::getTPL()->assign([
                'success' => true,
                'objectEditLink' => LinkHandler::getInstance()->getControllerLink(TodoEditForm::class, ['id' => $this->objectAction->getReturnValues()['returnValues']->todoID])
            ]);
        }
    }
}