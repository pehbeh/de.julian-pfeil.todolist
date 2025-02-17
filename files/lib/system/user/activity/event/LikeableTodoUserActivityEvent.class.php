<?php

namespace todolist\system\user\activity\event;

use todolist\data\todo\TodoList;

use wcf\system\SingletonFactory;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\system\WCF;

/**
 * Class LikeableTodoUserActivityEvent
 *
 * @author  Julian Pfeil <https://julian-pfeil.de>
 * @copyright   2022 Julian Pfeil Websites & Co.
 * @license Creative Commons <by> <https://creativecommons.org/licenses/by/4.0/legalcode>
 */
class LikeableTodoUserActivityEvent extends SingletonFactory implements IUserActivityEvent
{
    /**
     * @inheritDoc
     */
    public function prepare(array $events)
    {
        $todoIDs = [];
        foreach ($events as $event) {
            $todoIDs[] = $event->objectID;
        }

        // fetch entries
        $todoList = new TodoList();
        $todoList->setObjectIDs($todoIDs);
        $todoList->readObjects();
        $entries = $todoList->getObjects();

        // set message
        foreach ($events as $event) {
            if (isset($entries[$event->objectID])) {
                $todo = $entries[$event->objectID];

                // check permissions
                if (!$todo->canRead()) {
                    continue;
                }
                $event->setIsAccessible();

                // short output
                $text = WCF::getLanguage()->getDynamicVariable('todolist.general.recentActivity.likedTodo', ['todo' => $todo]);
                $event->setTitle($text);

                // output
                $event->setDescription($todo->getExcerpt());
            } else {
                $event->setIsOrphaned();
            }
        }
    }
}