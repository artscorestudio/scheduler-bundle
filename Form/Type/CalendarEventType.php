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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use ASF\SchedulerBundle\Utils\Manager\DefaultManagerInterface;
use ASF\LayoutBundle\Form\Type\DatePickerType;

/**
 * Calendar Event Form
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CalendarEventType extends AbstractType
{
    /**
     * @var DefaultManagerInterface
     */
    protected $calendarEventManager;
    
    /**
     * @param DefaultManagerInterface $calendarEventManager
     */
    public function __construct(DefaultManagerInterface $calendarEventManager)
    {
        $this->calendarEventManager = $calendarEventManager;
    }
    
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::buildForm()
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', TextType::class, array(
			'label' => 'Event Title',
			'required' => true
		))
		->add('startedAt', DatePickerType::class, array(
			'label' => 'Start date',
			'required' => true,
		))
		->add('finishedAt', DatePickerType::class, array(
			'label' => 'End date',
			'required' => true,
		))
		->add('isAllDay', CheckboxType::class, array(
			'label' => 'All day event',
			'required' => false
		))
		->add('category', SearchCalendarEventCategoryType::class);
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => $this->calendarEventManager->getClassName(),
			'translation_domain' => 'asf_scheduler'
		));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::getName()
	 */
	public function getName()
	{
		return 'calendar_event_type';
	}
}