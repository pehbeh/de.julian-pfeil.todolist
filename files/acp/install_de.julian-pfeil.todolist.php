<?php

/**
 * @author  Julian Pfeil <https://julian-pfeil.de>
 * @copyright   2022 Julian Pfeil Websites & Co.
 * @license Creative Commons <by> <https://creativecommons.org/licenses/by/4.0/legalcode>
 */

use wcf\system\WCF;
use wcf\data\category\CategoryEditor;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\database\util\PreparedStatementConditionBuilder;

// add default todo category
$categoryObjectTypeID = ObjectTypeCache::getInstance()->getObjectTypeIDByName('com.woltlab.wcf.category', 'de.julian-pfeil.todolist.todo.category');
CategoryEditor::create([
    'objectTypeID' => $categoryObjectTypeID,
    'title' => 'Default Category',
    'time' => TIME_NOW,
]);

$conditionBuilder = new PreparedStatementConditionBuilder();
$conditionBuilder->add('objectTypeID = ?', [$categoryObjectTypeID]);
$sql = "SELECT  categoryID
        FROM    wcf" . WCF_N . "_category category
        " . $conditionBuilder . "
        ORDER BY category.categoryID DESC";
$limit = 1;
$statement = WCF::getDB()->prepareStatement($sql, $limit);
$statement->execute($conditionBuilder->getParameters());
$categoryID = $statement->fetchAll(\PDO::FETCH_COLUMN)[0];

// set every todos category to default
$sql = "UPDATE  todolist" . WCF_N . "_todo todo
        SET     todo.categoryID = ?";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute([$categoryID]);
