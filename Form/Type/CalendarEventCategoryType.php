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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use ASF\SchedulerBundle\Utils\Manager\DefaultManagerInterface;
use ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryModel;

/**
 * Calendar Event Category Form
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CalendarEventCategoryType extends AbstractType
{
    /**
     * @var DefaultManagerInterface
     */
    protected $entityManager;
   
    /**
     * @param DefaultManagerInterface $entityManager
     */
    public function __construct(DefaultManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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
		->add('bgColor', TextType::class, array(
			'label' => 'Background color',
			'required' => false
		))
		->add('fgColor', TextType::class, array(
			'label' => 'Foreground color',
			'required' => false
		))
		->add('cssClassName', ChoiceType::class, array(
			'label' => 'CSS class name',
			'required' => false,
			'choices' => array(
				'Blue' => '.blue-event'
			)
		))->add('state', ChoiceType::class, array(
			'label' => 'State',
			'required' => true,
			'choices' => array(
				'Enabled' => CalendarEventCategoryModel::STATE_ENABLED,
				'Disabled' => CalendarEventCategoryModel::STATE_DISABLED
			)
		));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => $this->entityManager->getClassName(),
			'translation_domain' => 'asf_scheduler'
		));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::getName()
	 */
	public function getName()
	{
		return 'calendar_event_category_type';
	}
}