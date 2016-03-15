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
 * Calendar Event Entity
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
abstract class CalendarEventModel implements CalendarEventInterface
{
	/**
	 * Allowed states for CompanyEvent entity
	
	 * @var string
	 */
	const STATE_ENABLED  = 'enabled';
	const STATE_DISABLED = 'disabled';
	
	/**
	 * @var integer
	 */
	protected $id;
	
	/**
	 * @var string
	 */
	protected $state;
	
	/**
	 * @var string
	 */
	protected $title;
	
	/**
	 * @var string
	 */
	protected $url;
	
	/**
	 * @var \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface
	 */
	protected $category;
	
	/**
	 * @var boolean
	 */
	protected $isAllDay;
	
	/**
	 * @var \DateTime
	 */
	protected $startedAt;
	
	/**
	 * @var \DateTime
	 */
	protected $finishedAt;

	public function __construct()
	{
		$this->startedAt = new \DateTime();
		$this->isAllDay = false;
		$this->state = self::STATE_DISABLED;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getId()
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getState()
	 */
	public function getState()
	{
		return $this->state;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::setState()
	 */
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getTitle()
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::setTitle()
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getUrl()
	 */
	public function getUrl()
	{
		return $this->url;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::setUrl()
	 */
	public function setUrl($url)
	{
		$this->url = $url;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getCategory()
	 */
	public function getCategory()
	{
		return $this->category;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::setCategory()
	 */
	public function setCategory(CalendarEventCategoryInterface $category)
	{
		$this->category = $category;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getStartedAt()
	 */
	public function getStartedAt()
	{
		return $this->startedAt;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::setStartedAt()
	 */
	public function setStartedAt(\DateTime $start_date)
	{
		$this->startedAt = $start_date;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getFinishedAt()
	 */
	public function getFinishedAt()
	{
		return $this->finishedAt;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::setFinishedAt()
	 */
	public function setFinishedAt(\DateTime $end_date)
	{
		$this->finishedAt = $end_date;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::getIsAllDay()
	 */
	public function getIsAllDay()
	{
		return $this->isAllDay;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::setIsAllDay()
	 */
	public function setIsAllDay($is_all_day)
	{
		$this->isAllDay = (boolean) $is_all_day;
		return $this;
	}
	
	/**
	 * Fired on prePersist Doctrine Event
	 */
	public function onPrePersist()
	{
		if ( is_null($this->finishedAt) && $this->isAllDay === false  ) {
			throw new \Exception('You must specify an end date for the event or define it on all day.');
		}
	}

	/**
	 * {@inheritDoc}
	 * @see \ASF\SchedulerBundle\Model\CalendarEvent\CalendarEventInterface::toArray()
	 */
	public function toArray()
	{
		$event = array();
		
        if ( !is_null($this->id) ) {
            $event['id'] = $this->id;
        }
        
        $event['title'] = $this->title;
        $event['start'] = $this->startedAt->format("Y-m-d\TH:i:sP");
        
        if ( !is_null($this->url) ) {
            $event['url'] = $this->url;
        }
        
        if ( !is_null($this->category) ) {
	        if ( !is_null($this->category->getBgColor()) ) {
	            $event['backgroundColor'] = $this->category->getBgColor();
	            $event['borderColor'] = $this->category->getBgColor();
	        }
	        
	        if ( !is_null($this->category->getFgColor()) ) {
	            $event['textColor'] = $this->category->getFgColor();
	        }
	        
	        if ( !is_null($this->category->getCssClassName()) ) {
	            $event['className'] = $this->category->getCssClassName();
	        }
        }
        
        if ( !is_null($this->finishedAt)) {
            $event['end'] = $this->finishedAt->format("Y-m-d\TH:i:sP");
        }
        
        $event['allDay'] = $this->isAllDay;

        return $event;
	}
}