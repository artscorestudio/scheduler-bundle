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

/**
 * Comapny Event Category Form
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CompanyEventCategoryFormType extends AbstractType
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
		->add('bgColor', TextType::class, array(
			'label' => 'Background color',
			'required' => false
		))
		->add('fgColor', TextType::class, array(
			'label' => 'Forground color',
			'required' => false
		))
		->add('cssClassName', ChoiceType::class, array(
			'label' => 'CSS class name',
			'choices' => array(
				'.event-cat-routes' => 'Routes',
				'.event-cat-vert' => 'Espaces verts',
				'.event-cat-repos' => 'Repos'
			),
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
			'data_class' => 'ASF\SchedulerBundle\Entity\DVIEventCategory',
			'translation_domain' => 'asf_scheduler'
		));
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Form\AbstractType::getName()
	 */
	public function getName()
	{
		return 'company_event_category_form';
	}
}