<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Model\CalendarEventCategory;

/**
 * Company Event Entity
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
abstract class CalendarEventCategoryModel implements CalendarEventCategoryInterface
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
	protected $bgColor;
	
	/**
	 * @var string
	 */
	protected $fgColor;
	
	/**
	 * @var string
	 */
	protected $cssClassName;

	public function __construct()
	{
		$this->state = self::STATE_DISABLED;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::getId()
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::getState()
	 */
	public function getState()
	{
		return $this->state;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::setState()
	 */
	public function setState($state)
	{
		$this->state = $state;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::getTitle()
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::setTitle()
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::getBgColor()
	 */
	public function getBgColor()
	{
		return $this->bgColor;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::setBgColor()
	 */
	public function setBgColor($bg_color)
	{
		$this->bgColor = $bg_color;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::getFgColor()
	 */
	public function getFgColor()
	{
		return $this->fgColor;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::setFgColor()
	 */
	public function setFgColor($fg_color)
	{
		$this->fgColor = $fg_color;
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::getCssClassName()
	 */
	public function getCssClassName()
	{
		return $this->cssClassName;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryInterface::setCssClassName()
	 */
	public function setCssClassName($css_classname)
	{
		$this->cssClassName = $css_classname;
		return $this;
	}
}