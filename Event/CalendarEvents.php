<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Event;

/**
 * Calendar Events
 *
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
final class CalendarEvents
{
    /**
     * Calendar load events
     *
     * @var string
     */
    const CONFIGURE = 'asf_scheduler.calendar.load_events';
}