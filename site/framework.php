<?php
/**
 * @version     1.0.0
 * @package     com_framework
 * @copyright   Copyright (C) 2014. All rights reserved.
 * @license     MIT
 */

// no direct access
defined('_JEXEC') or die;

const COMPONENT_ROOT = __DIR__;

JLoader::registerNamespace('Framework', __DIR__ . '/src');

$app = JFactory::getApplication();

// Set the default view
$app->input->def('view', 'default');

$container = new Joomla\DI\Container;
$container->set('JApplicationCms', $app);
$container->set('JInput', $app->input);
$container->set('JDatabaseDriver', JFactory::getDbo());

echo (new Framework\Dispatcher($app, $container))->execute();
