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

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Source\Entity;

use ASF\SchedulerBundle\Model\CalendarEventCategory\CalendarEventCategoryModel;

/**
 * Company Event Category Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CalendarEventCategoryController extends Controller
{
    /**
     * List all calendar event category 
     *
     * @throws AccessDeniedException If authenticate user is not allowed to access to this resource (minimum ROLE_ADMIN)
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction()
    {
        if ( false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') )
            throw new AccessDeniedException();
    
        // Set Datagrid source
        $source = new Entity($this->get('asf_scheduler.calendar_event_category.manager')->getClassName());
        $tableAlias = $source->getTableAlias();
        $source->manipulateQuery(function($query) use ($tableAlias){
            $query instanceof QueryBuilder;
            	
            if ( count($query->getDQLPart('orderBy')) == 0) {
                $query->orderBy($tableAlias . '.title', 'ASC');
            }
        });
    
        // Get Grid instance
        $grid = $this->get('grid');
        $grid instanceof Grid;

        // Attach the source to the grid
        $grid->setSource($source);
        $grid->setId('asf_calendar_event_category_list');

        // Columns configuration
        $grid->hideColumns(array('id'));

        $grid->getColumn('title')->setTitle($this->get('translator')->trans('Category name', array(), 'asf_scheduler'))
            ->setDefaultOperator('like')
            ->setOperatorsVisible(false);

        $grid->getColumn('state')->setTitle($this->get('translator')->trans('State', array(), 'asf_scheduler'))
            ->setFilterType('select')->setSelectFrom('values')->setOperatorsVisible(false)
            ->setDefaultOperator('eq')->setValues(array(
                CalendarEventCategoryModel::STATE_ENABLED => $this->get('translator')->trans('Enabled', array(), 'asf_scheduler'),
                CalendarEventCategoryModel::STATE_DISABLED => $this->get('translator')->trans('Disabled', array(), 'asf_scheduler')
            ));
        
        $grid->getColumn('bgColor')->setSize(100)->setTitle($this->get('translator')->trans('Background color', array(), 'asf_scheduler'));
        $grid->getColumn('fgColor')->setSize(100)->setTitle($this->get('translator')->trans('Foreground color', array(), 'asf_scheduler'));
        $grid->getColumn('cssClassName')->setSize(100)->setTitle($this->get('translator')->trans('CSS class name', array(), 'asf_scheduler'));

        $editAction = new RowAction('btn_edit', 'asf_scheduler_calendar_event_category_edit');
        $editAction->setRouteParameters(array('id'));
        $grid->addRowAction($editAction);

        $deleteAction = new RowAction('btn_delete', 'asf_scheduler_calendar_event_category_delete', true);
        $deleteAction->setRouteParameters(array('id'))
            ->setConfirmMessage($this->get('translator')->trans('Do you want to delete this category?', array(), 'asf_scheduler'));
        $grid->addRowAction($deleteAction);

        $grid->setNoDataMessage($this->get('translator')->trans('No category was found.', array(), 'asf_scheduler'));

        return $grid->getGridResponse('ASFSchedulerBundle:CalendarEventCategory:list.html.twig');
    }
    
	/**
	 * Add Calendar Event Category
	 * 
	 * @param Request $request
	 * @return Symfony\Component\HttpFoundation\Response
	 */
	public function addAction(Request $request)
	{
		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		$cat_event = $this->get('asf_scheduler.calendar_event_category.manager')->createInstance();
		
		$formFactory = $this->get('asf_scheduler.form.factory.calendar_event_category');
		$form = $formFactory->createForm();
		$form->setData($cat_event);
		
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
			
			try {
				$this->get('asf_scheduler.calendar_event_category.manager')->getEntityManager()->persist($cat_event);
				$this->get('asf_scheduler.calendar_event_category.manager')->getEntityManager()->flush();
				
				$this->get('asf_layout.flash_message')->success($this->get('translator')->trans('The Event Category "%name%" successfully saved.', array('%name%' => $cat_event->getTitle(), 'asf_scheduler')));
				return $this->redirectToRoute('asf_scheduler_calendar_event_category_edit', array('id' => $cat_event->getId()));
				
			} catch (\Exception $e) {
				$this->get('asf_layout.flash_message')->danger($this->get('translator')->trans('An error occured when creating an event category: %msg%', array('%msg%' => $e->getMessage())));
			}
		}
		
		return $this->render('ASFSchedulerBundle:CalendarEventCategory:add.html.twig', array(
			'form' => $form->createView()
		));
	}
	
	/**
	 * Calendar Event Category edit
	 * 
	 * @param Request $request
	 * @param integer $id      ASFSchedulerBundle::CalendarEventCategory ID 
	 */
	public function editAction(Request $request, $id)
	{
		if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
			throw $this->createAccessDeniedException();
		}
		
		$cat_event = $this->get('asf_scheduler.calendar_event_category.manager')->getRepository()->findOneBy(array('id' => $id));
		
		$formFactory = $this->get('asf_scheduler.form.factory.calendar_event_category');
		$form = $formFactory->createForm();
		$form->setData($cat_event);
		
		$form->handleRequest($request);
		
		if ( $form->isSubmitted() && $form->isValid() ) {
				
			try {
				$this->get('asf_scheduler.calendar_event_category.manager')->getEntityManager()->flush();
				if ( $this->has('asf_layout.flash_message') ) {
					$this->get('asf_layout.flash_message')->success($this->get('translator')->trans('The Event Category "%name%" successfully saved.', array('%name%' => $cat_event->getTitle(), 'asf_scheduler')));
				}
			} catch (\Exception $e) {
				if ( $this->has('asf_layout.flash_message') ) {
					$this->get('asf_layout.flash_message')->danger($this->get('translator')->trans('An error occured when creating an event category: %msg%', array('%msg%' => $e->getMessage())));
				}
			}
		}
		
		return $this->render('ASFSchedulerBundle:CalendarEventCategory:edit.html.twig', array(
			'form' => $form->createView(),
			'cat_event' => $cat_event
		));
	}
	
	/**
	 * Delete a category
	 *
	 * @param  integer $id           ASFSchedulerBundle:EventCategory Entity ID
	 * @throws AccessDeniedException If user does not have ACL's rights for delete the category
	 * @throws \Exception            Error on category not found or on removing element from DB
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function deleteAction($id)
	{
		if ( false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN') )
			throw new AccessDeniedException();
			
			$entityManager = $this->get('asf_scheduler.calendar_event_category.manager');
			$category = $entityManager->getRepository()->findOneBy(array('id' => $id));
	
			try {
				$entityManager->getEntityManager()->remove($category);
				$entityManager->getEntityManager()->flush();
					
				if ( $this->has('asf_layout.flash_message') ) {
					$this->get('asf_layout.flash_message')->success($this->get('translator')->trans('The category "%name%" successfully deleted', array('%name%' => $category->getTitle()), 'asf_scheduler'));
				}
	
			} catch (\Exception $e) {
				if ( $this->has('asf_layout.flash_message') ) {
					$this->get('asf_layout.flash_message')->danger($e->getMessage());
				}
			}
	
			return $this->redirect($this->get('router')->generate('asf_scheduler_calendar_event_category_list'));
	}
}