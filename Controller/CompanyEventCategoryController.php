<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use ASF\SchedulerBundle\Form\Type\CompanyEventCategoryFormType;

/**
 * Company Event Category Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CompanyEventCategoryController extends Controller
{
	/**
	 * Add Company Event Category
	 * 
	 * @param Request $request
	 * @return Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		$cat_event = $this->get('asf_scheduler.company_event_category.manager')->createInstance();
		
		$form = $this->createForm(CompanyEventCategoryFormType::class, $cat_event);
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
			
			try {
				$this->get('asf_scheduler.company_event_category..manager')->getEntityManager()->persist($cat_event);
				$this->get('asf_scheduler.company_event_category..manager')->getEntityManager()->flush();
				
				$this->get('asf_layout.flash_message')->success(sprintf('Your Event Category "%s" successfully saved.', $cat_event->getTitle()));
				$this->redirectToRoute('asf_scheduler_company_event_category_edit', array('id' => $event->getId()));
				
			} catch (\Exception $e) {
				$this->get('asf_layout.flash_message')->danger(sprintf('An error occured when creating an event : %s', $e->getMessage()));
			}
		}
		
		return $this->render('ASFSchedulerBundle:CompanyEventCategory:add.html.twig', array(
			'form' => $form->createView()
		));
	}
	
	/**
	 * Company Event Category edit
	 * 
	 * @param Request $request
	 * @param integer $id      ASFSchedulerBundle::CompanyEventCategory ID 
	 */
	public function editAction(Request $request, $id)
	{
		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		$cat_event = $this->get('asf_scheduler.company_event_category..manager')->getRepository()->findOneBy(array('id' => $id));
		
		$form = $this->createForm(CompanyEventCategoryFormType::class, $cat_event);
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
				
			try {
				$this->get('asf_scheduler.company_event_category..manager')->flush();
				$this->get('asf_layout.flash_message')->success(sprintf('Your Event Category "%s" successfully saved.', $cat_event->getTitle()));
		
			} catch (\Exception $e) {
				$this->get('asf_layout.flash_message')->danger(sprintf('An error occured when creating an event : %s', $e->getMessage()));
			}
		}
		
		return $this->render('ASFSchedulerBundle:CompanyEventCategory:edit.html.twig', array(
			'form' => $form->createView(),
			'cat_event' => $cat_event
		));
	}
}