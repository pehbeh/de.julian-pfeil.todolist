<?php
/**
 * @author  Julian Pfeil <https://julian-pfeil.de>
 * @copyright   2022 Julian Pfeil Websites & Co.
 * @license Creative Commons <by> <https://creativecommons.org/licenses/by/4.0/legalcode>
 * @package Todolist/Core
 */

// define paths
define('RELATIVE_TODOLIST_DIR', '../');

/* 
 * include config
 * @noinspection PhpIncludeInspection 
 */
require_once dirname(__FILE__, 2) . '/config.inc.php';

/*
 * include wcf
 */
require_once RELATIVE_WCF_DIR . 'acp/global.php';
