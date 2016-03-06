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

use ASF\SchedulerBundle\Form\Type\CompanyEventFormType;

/**
 * Company Event Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CompanyEventController extends Controller
{
	/**
	 * Add Company Event
	 * 
	 * @param Request $request
	 * @return Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		$event = $this->get('asf_scheduler.company_event.manager')->createInstance();
		
		$form = $this->createForm(CompanyEventFormType::class, $event);
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
			
			try {
				$this->get('asf_scheduler.company_event.manager')->getEntityManager()->persist($event);
				$this->get('asf_scheduler.company_event.manager')->getEntityManager()->flush();
				
				$this->get('asf_layout.flash_message')->success(sprintf('Your Calendar Event "%s" successfully saved.', $event->getTitle()));
				$this->redirectToRoute('asf_scheduler_company_event_edit', array('id' => $event->getId()));
				
			} catch (\Exception $e) {
				$this->get('asf_layout.flash_message')->danger(sprintf('An error occured when creating an event : %s', $e->getMessage()));
			}
		}
		
		return $this->render('ASFSchedulerBundle:CompanyEvent:add.html.twig', array(
			'form' => $form->createView()
		));
	}
	
	/**
	 * Company Event edit
	 * 
	 * @param Request $request
	 * @param integer $id      ASFSchedulerBundle::CompanyEvent ID 
	 */
	public function editAction(Request $request, $id)
	{
		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		$event = $this->get('asf_scheduler.company_event.manager')->getRepository()->findOneBy(array('id' => $id));
		
		$form = $this->createForm(CompanyEventFormType::class, $event);
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
				
			try {
				$this->get('asf_scheduler.company_event.manager')->flush();
				$this->get('asf_layout.flash_message')->success(sprintf('Your Calendar Event "%s" successfully saved.', $event->getName()));
		
			} catch (\Exception $e) {
				$this->get('asf_layout.flash_message')->danger(sprintf('An error occured when creating an event : %s', $e->getMessage()));
			}
		}
		
		return $this->render('ASFSchedulerBundle:CompanyEvent:edit.html.twig', array(
			'form' => $form->createView(),
			'event' => $event
		));
	}
}