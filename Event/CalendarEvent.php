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

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface;

/**
 * Calendar Event
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CalendarEvent extends Event
{
	/**
	 * @var \DateTime
	 */
	protected $startDatetime;
	
	/**
	 * @var \DateTime
	 */
	protected $endDatetime;
	
	/**
	 * @var Request
	 */
	protected $request;
	
	/**
	 * @var ArrayCollection
	 */
	protected $events;
	
	/**
	 * @param MenuItem $menu
	 * @param FactoryInterface $factory
	 */
	public function __construct(\DateTime $start, \DateTime $end, Request $request = null)
	{
		$this->startDatetime = $start;
		$this->endDatetime = $end;
		$this->request = $request;
		$this->events = new ArrayCollection();
	}
	
	/**
	 * @return ArrayCollection
	 */
	public function getEvents()
    {
        return $this->events;
    }
    
    /**
     * If the event isn't already in the list, add it
     * 
     * @param CalendarEventInterface $event
     * @return CalendarEvent $this
     */
    public function addEvent(CalendarEventInterface $event)
    {
        if ( !$this->events->contains($event) ) {
            $this->events->add($event);
        }
        return $this;
    }
    
    /**
     * Get start datetime 
     * 
     * @return \DateTime
     */
    public function getStartDatetime()
    {
        return $this->startDatetime;
    }
    
    /**
     * Get end datetime 
     * 
     * @return \DateTime
     */
    public function getEndDatetime()
    {
        return $this->endDatetime;
    }
    
    /**
     * Get request
     * 
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}