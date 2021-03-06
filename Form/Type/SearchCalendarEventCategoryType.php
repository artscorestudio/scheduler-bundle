<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use ASF\SchedulerBundle\Form\DataTransformer\StringToEventCategoryTransformer;
use ASF\SchedulerBundle\Utils\Manager\DefaultManagerInterface;

/**
 * Field for searching calendar event category
 * 
 * @author Nicolas Claverie qinfo@artscore-studio.fr>
 *
 */
class SearchCalendarEventCategoryType extends AbstractType
{
	/**
	 * @var DefaultManagerInterface
	 */
	protected $eventCategoryManager;
	
	/**
	 * @param DefaultManagerInterface $calendarEventManager
	 */
	public function __construct(DefaultManagerInterface $eventCategoryManager)
	{
		$this->eventCategoryManager = $eventCategoryManager;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$eventTransformer = new StringToEventCategoryTransformer($this->eventCategoryManager);
		$builder->addModelTransformer($eventTransformer);
	}

	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
		    'label' => 'Calendar Event Category',
			'class' => $this->eventCategoryManager->getClassName(),
		    'choice_label' => 'title',
		    'placeholder' => 'Choose a category',
		    'attr' => array('class' => 'select2-entity')
		));
	}
	
	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\Form\FormTypeInterface::getName()
	 */
	public function getName()
	{
		return 'search_calendar_event_category';
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::getParent()
	 */
	public function getParent()
	{
		return EntityType::class;
	}
}