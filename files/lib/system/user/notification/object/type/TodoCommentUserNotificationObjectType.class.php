<?php
namespace todolist\system\user\notification\object\type;
use wcf\data\comment\Comment;
use wcf\data\comment\CommentList;
use wcf\system\user\notification\object\type\AbstractUserNotificationObjectType;
use wcf\system\user\notification\object\type\ICommentUserNotificationObjectType;
use wcf\system\user\notification\object\CommentUserNotificationObject;
use wcf\system\WCF;

/**
 * Represents a comment notification object type.
 * 
 * @author  Julian Pfeil <https://julian-pfeil.de>
 * @copyright   2022 Julian Pfeil Websites & Co.
 * @license Creative Commons <by> <https://creativecommons.org/licenses/by/4.0/legalcode>
 */
class TodoCommentUserNotificationObjectType extends AbstractUserNotificationObjectType implements ICommentUserNotificationObjectType {
    /**
     * @inheritDoc
     */
    protected static $decoratorClassName = CommentUserNotificationObject::class;
    
    /**
     * @inheritDoc
     */
    protected static $objectClassName = Comment::class;
    
    /**
     * @inheritDoc
     */
    protected static $objectListClassName = CommentList::class;
    
    /**
     * @inheritDoc
     */
    public function getOwnerID($objectID) {
        $sql = "SELECT		todo.userID
                FROM		wcf".WCF_N."_comment comment
                LEFT JOIN	todolist".WCF_N."_todo todo
                ON			(todo.todoID = comment.objectID)
                WHERE		comment.commentID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute([$objectID]);
        
        return $statement->fetchSingleColumn() ?: 0;
    }
}
