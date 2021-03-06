<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

use ASF\SchedulerBundle\Utils\Manager\DefaultManagerInterface;

/**
 * Transform a string to a Calendar Event entity
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class StringToEventTransformer implements DataTransformerInterface
{
	/**
	 * @var DefaultManagerInterface
	 */
	protected $eventManager;
	
	/**
	 * @param DefaultManagerInterface $eventManager
	 */
	public function __construct(DefaultManagerInterface $eventManager)
	{
		$this->eventManager = $eventManager;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\DataTransformerInterface::transform()
	 */
	public function transform($event)
	{
		if ( is_null($event) )
			return '';
	
		return $event->getTitle();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
	 */
	public function reverseTransform($string)
	{
		$event = $this->eventManager->getRepository()->findOneBy(array('title' => $string));
		return $event;
	}
}