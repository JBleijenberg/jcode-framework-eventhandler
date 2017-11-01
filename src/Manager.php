<?php
/**
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the General Public License (GPL 3.0)
 * that is bundled with this package in the file LICENSE
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/GPL-3.0
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category    J!Code: Framework
 * @package     J!Code: Framework
 * @author      Jeroen Bleijenberg <jeroen@jcode.nl>
 *
 * @copyright   Copyright (c) 2017 J!Code (http://www.jcode.nl)
 * @license     http://opensource.org/licenses/GPL-3.0 General Public License (GPL 3.0)
 */
namespace Jcode\Event;

use Jcode\Application;
use Jcode\DataObject\Collection;

class Manager
{

    protected $eventId = 'event.manager';

    protected $isSharedInstance = true;

    public function dispatchEvent($eventID, $eventObject)
    {
        $modules = Application::registry('module_collection');

        if ($modules instanceof Collection) {
            foreach ($modules as $module) {
                if ($module->getEvents()) {
                    foreach ($module->getEvents() as $trigger => $targets) {
                        if ($trigger == $eventID) {
                            foreach ($targets as $target) {
                                list($class, $method) = explode('::', $target);

                                Application::getClass($class)->$method($eventObject);
                            }
                        }
                    }
                }
            }
        }
    }
}