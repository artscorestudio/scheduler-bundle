<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) 2012-2015 Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use ASF\CoreBundle\Model\Manager\ASFEntityManagerInterface;

/**
 * Calendar Subscriber
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CalendarSubscriber implements EventSubscriberInterface
{
	/**
	 * @var ASFEntityManagerInterface
	 */
	protected $eventEntityManager;
	
	/**
	 * @param ASFEntityManagerInterface $eventEntityManager
	 */
	public function __construct(ASFEntityManagerInterface $eventEntityManager)
	{
		$this->eventEntityManager = $eventEntityManager;
	}
	
	/**
	 * Subscribed Events
	 */
	public static function getSubscribedEvents()
	{
		return array(
			CalendarEvents::LOAD_EVENTS => array('onCalendarLoadEvents', 0),
			CalendarEvents::TOOLBAR => array('onCalendarToolbarEvent', 0)
		);
	}
	
	/**
	 * @param CalendarEvent $event
	 */
	public function onCalendarLoadEvents(CalendarEvent $calendar_event)
	{
		$start_date = $calendar_event->getStartDatetime();
		$end_date = $calendar_event->getEndDatetime();
		$request = $calendar_event->getRequest();
		$filter = $request->get('filter');
		
		// Get DVI Events
		$company_events = $this->eventEntityManager->getRepository()
			->createQueryBuilder('e')
			->where('e.startedAt BETWEEN :startDate AND :endDate')
			->setParameter('startDate', $start_date->format('Y-m-d H:i:s'))
			->setParameter('endDate', $end_date->format('Y-m-d H:i:s'))
			->getQuery()->getResult();
		
		// Create Calendar Events
		foreach($company_events as $company_event){
			$calendar_event->addEvent($company_event);
		}
	}
	
	/**
	 * @param CalendarToolbarEvent $event
	 */
	public function onCalendarToolbarEvent(CalendarToolbarEvent $event)
	{
		$event->addButton('asf_scheduler_calendar_event_add', 'Add New Event');
	}
}