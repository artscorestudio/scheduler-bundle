<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
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
	protected $companyEventManager;
	
	/**
	 * @param ASFEntityManagerInterface $company_event_manager
	 */
	public function __construct(ASFEntityManagerInterface $company_event_manager)
	{
		$this->companyEventManager = $company_event_manager;
	}
	
	/**
	 * Subscribed Events
	 */
	public static function getSubscribedEvents()
	{
		return array(
			CalendarEvents::CONFIGURE => array('onCalendarLoadEvents', 0),
			//CalendarEvents::CALENDAR_TOOLBAR => array('onCalendarToolbarEvent', 0)
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
		$company_events = $this->companyEventManager->getRepository()
			->createQueryBuilder('e')
			->where('e.startedAt BETWEEN :startDate AND :endDate')
			->setParameter('startDate', $start_date->format('Y-m-d H:i:s'))
			->setParameter('endDate', $end_date->format('Y-m-d H:i:s'))
			->getQuery()->getResult();
		
		// Create Calendar Events
		foreach($company_events as $company_event){
			if ( $company_event->getIsAllDay() === false ) {
				$eventEntity = new EventEntity($company_event->getTitle(), $company_event->getStartedAt(), $dvi_event->getFinishedAt());
			} else {
				$eventEntity = new EventEntity($company_event->getTitle(), $company_event->getStartedAt(), null, true);
			}
			
			if ( !is_null($company_event->getCategory()) ) {
				$eventEntity->setBgColor($company_event->getCategory()->getBgColor());
				$eventEntity->setFgColor($company_event->getCategory()->getFgColor());
				$eventEntity->setCssClass($company_event->getCategory()->getCssClassName());
			}
			
			$calendar_event->addEvent($eventEntity);
		}
	}
	
	/**
	 * @param CalendarToolbarEvent $event
	 */
	public function onCalendarToolbarEvent(CalendarToolbarEvent $event)
	{
		$event->addButton('asf_scheduler_company_event_add', 'Add New Event');
	}
}