<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) 2012-2015 Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Company Event Form
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CompanyEventFormType extends AbstractType
{
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
		->add('url', TextType::class, array(
			'label' => 'Event URL',
			'required' => false
		))
		->add('startedAt', DateTimeType::class, array(
			'label' => 'Start date',
			'required' => true
		))
		->add('finishedAt', DateTimeType::class, array(
			'label' => 'End date',
			'required' => true
		))
		->add('isAllDay', CheckboxType::class, array(
			'label' => 'All day event',
			'required' => false
		))
		->add('category', EntityType::class, array(
			'class' => 'ASFSchedulerBundle:DVIEventCategory',
			'choice_label' => 'title',
			'label' => 'Category',
			'required' => true
		))
		->add('agent', EntityType::class, array(
			'class' => 'ASFContactBundle:Person',
			'choice_label' => 'name',
			'label' => 'Agent',
			'required' => false
		));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::configureOptions()
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'ASF\SchedulerBundle\Entity\DVIEvent',
			'translation_domain' => 'cd31_scheduler'
		));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::getName()
	 */
	public function getName()
	{
		return 'company_event_form';
	}
}