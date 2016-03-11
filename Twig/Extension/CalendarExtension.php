<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Twig\Extension;

/**
 * Calendar Extension for generate tinymce init
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CalendarExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $assets;
    
    /**
     * @param array $assets
     */
    public function __construct($assets)
    {
        $this->assets = $assets;
    }
    
    /**
     * {@inheritDoc}
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fullcalendar_init', array($this, 'fullCalendarInitJs'), array(
                'needs_environment' => true,
                'is_safe' => array('html')
            ))
        );
    }
    
    /**
     * Return the tinyMCE init js
     */
    public function fullCalendarInitJs(\Twig_Environment $environment)
    {
        $config = isset($this->assets['fullcalendar']['config']) ? $this->assets['fullcalendar']['config'] : array();
        $config['eventSources'] = array(array(
            'url' => "Routing.generate('".$this->assets['fullcalendar']['customize']['load_events_route']."')",
            'type' => 'POST',
            'data' => function(){},
            'error' => function(){}
        ));
        
        return $environment->render('ASFSchedulerBundle:calendar:init_js.html.twig', array(
            'calendar_config' => $config,
            'selector' => $this->assets['fullcalendar']['selector']
        ));
    }
    
    /**
     * {@inheritDoc}
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'asf_scheduler_calendar';
    }
}