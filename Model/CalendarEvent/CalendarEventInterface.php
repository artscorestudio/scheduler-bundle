<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Model\CalendarEvent;

use ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface;

/**
 * Calendar Event Interface
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface CalendarEventInterface
{
	/**
	 * @return \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface
	 */
	public function getCategory();
	
	/**
	 * @param \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface $category
	 * @return \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface
	 */
	public function setCategory(CalendarEventCategoryInterface $category);
}