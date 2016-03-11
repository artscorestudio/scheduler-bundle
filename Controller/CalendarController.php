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
class CalendarController extends Controller
{
    /**
     * Ajax Request Load Events
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function loadCalendarAction(Request $request)
    {
        $startDatetime = new \DateTime();
        $startDatetime->setTimestamp($request->get('start'));
        
        $endDatetime = new \DateTime();
        $endDatetime->setTimestamp($request->get('start'));
        
        $events = $this->container->get('event_dispatcher')->dispatch(
            CalendarEvents::CONFIGURE, 
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
}