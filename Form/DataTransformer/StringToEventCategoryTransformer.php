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
 * Transform a string to a Calendar Event Category entity
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class StringToEventCategoryTransformer implements DataTransformerInterface
{
	/**
	 * @var DefaultManagerInterface
	 */
	protected $eventCategoryManager;
	
	/**
	 * @param DefaultManagerInterface $eventManager
	 */
	public function __construct(DefaultManagerInterface $eventCategoryManager)
	{
		$this->eventCategoryManager = $eventCategoryManager;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\DataTransformerInterface::transform()
	 */
	public function transform($event_category)
	{
		if ( is_null($event_category) )
			return '';
	
		return $event_category->getTitle();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\DataTransformerInterface::reverseTransform()
	 */
	public function reverseTransform($string)
	{
		$event_category = $this->eventCategoryManager->getRepository()->findOneBy(array('title' => $string));
		return $event_category;
	}
}