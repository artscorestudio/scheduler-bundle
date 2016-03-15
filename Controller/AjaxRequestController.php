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
use Symfony\Component\HttpFoundation\Response;

use ASF\SchedulerBundle\Event\CalendarEvents;
use ASF\SchedulerBundle\Event\CalendarEvent;

/**
 * Calendar Controller
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class AjaxRequestController extends Controller
{
    /**
     * Ajax Request Load Events
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadCalendarAction(Request $request)
    {
        $startDatetime = new \DateTime($request->get('start'));
        $endDatetime = new \DateTime($request->get('end'));
        
        $events = $this->container->get('event_dispatcher')->dispatch(
            CalendarEvents::LOAD_EVENTS, 
            new CalendarEvent($startDatetime, $endDatetime, $request
        ))->getEvents();
        
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');
        
        $return_events = array();
        
        foreach($events as $event) {
            $return_events[] = $event->toArray();
        }
        
        $response->setContent(json_encode($return_events));
        
        return $response;
    }
    
    public function eventResizeAction(Request $request)
    {
    	$response = new Response();
    	$response->headers->set('Content-Type', 'application/json');
    	
    	$entityManager = $this->get('asf_scheduler.calendar_event.manager');
    	
    	$id = $request->get('id');
    	$startedAt = new \DateTime($request->get('startedAt'));
    	$finishedAt = new \DateTime($request->get('finishedAt'));
    	$isAllDay = $request->get('isAllDay');
    	$return_value = array();
    	try {
    		$event = $entityManager->getRepository()->findOneBy(array('id' => $id));
    		if ( is_null($event) ) {
    			throw new \Exception(sprintf('The vent with id "%s" not found.', $id));
    		}
    		
    		$event->setStartedAt($startedAt)->setFinishedAt($finishedAt)->setIsAllDay($isAllDay);
    		$entityManager->getEntityManager()->flush();
    		
    		$return_value['msg'] = $this->get('translator')->trans('The event %name% has been successfully saved.', array('%name%' => $event->getTitle(), 'asf_scheduler'));
    		
    	} catch (\Exception $e) {
    		$return_value['msg'] = $e->getMessage();
    	}
    	
    	$response->setContent(json_encode($return_value));
    	return $response;
    }
}