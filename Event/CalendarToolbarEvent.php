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

use Symfony\Component\EventDispatcher\Event;

/**
 * Calendar Toolbar Event
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CalendarToolbarEvent extends Event
{
	/**
	 * @var array
	 */
	protected $buttons;
	
	/**
	 * @param array $buttons
	 */
	public function __construct($buttons)
	{
		$this->buttons = $buttons;
	}
	
	/**
	 * @param string $href  Button href
	 * @param string $title Button title
	 */
	public function addButton($href, $title)
	{
		$this->buttons[] = array('href' => $href, 'title' => $title);
	}
	
	/**
	 * @return array
	 */
	public function getButtons()
	{
		return $this->buttons;
	}
}