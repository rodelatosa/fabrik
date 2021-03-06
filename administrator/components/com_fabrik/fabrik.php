<?php
/**
 * @package Joomla
 * @subpackage Fabrik
 * @copyright Copyright (C) 2005 Rob Clayburn. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_fabrik')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

//load front end language file as well
$lang =& JFactory::getLanguage();
$lang->load('com_fabrik', JPATH_SITE.'/components/com_fabrik');

jimport('joomla.filesystem.file');
JHtml::_('script', 'media/com_fabrik/js/head.js');
JHTML::stylesheet('administrator/components/com_fabrik/headings.css');

// Include dependancies
jimport('joomla.application.component.controller');

// System plugin check
if (!defined('COM_FABRIK_FRONTEND')) {
	JError::raiseError(400, JText::_('COM_FABRIK_SYSTEM_PLUGIN_NOT_ACTIVE'));
}

//load in front end model path
JModel::addIncludePath(COM_FABRIK_FRONTEND.DS.'models', 'FabrikFEModel');

// Execute the task.
$controller	= &JController::getInstance('Fabrik');

//test that they've published some element plugins!
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('COUNT(extension_id)')->from('#__extensions')->where('enabled = 1 AND folder = "fabrik_element"');
$db->setQuery($query);
if (count($db->loadResult()) === 0) {
	JError::raiseNotice(E_WARNING, JText::_('COM_FABRIK_PUBLISH_AT_LEAST_ONE_ELEMENT_PLUGIN'));
}
//echo '<pre>';print_r($controller);
//echo JRequest::getCmd('task', 'home.display');exit;
$controller->execute(JRequest::getCmd('task', 'home.display'));
$controller->redirect();
