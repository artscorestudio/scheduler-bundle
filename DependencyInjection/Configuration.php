<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Bundle configuration
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class Configuration implements ConfigurationInterface
{
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\Config\Definition\ConfigurationInterface::getConfigTreeBuilder()
	 */
	public function getConfigTreeBuilder()
	{
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root('asf_scheduler');
		
		$rootNode
		  ->children()
    		  ->booleanNode('enable_twig_support')
    		      ->defaultTrue()
    		  ->end()
    		  ->booleanNode('enable_assetic_support')
    		      ->defaultTrue()
    		  ->end()
		      ->arrayNode('assets')
		          ->addDefaultsIfNotSet()
		          ->children()
		              ->append($this->addFullCalendarParameterNode())
        		      ->append($this->addMomentParameterNode())
		          ->end()
		      ->end()
		      
		      ->append($this->addCalendarEventParameterNode())
		      ->append($this->addCalendarEventCategoryParameterNode())
		  ->end();
        
		return $treeBuilder;
	}
	
	/**
	 * Add FullCalendar Js Node Parameter
	 */
	protected function addFullCalendarParameterNode()
	{
	    $builder = new TreeBuilder();
	    $node = $builder->root('fullcalendar');
	    
	    $config = array(
	        'header' => array(
                'left' => 'prev, next, today',
                'center' => 'title',
                'right' => 'month, agendaWeek, agendaDay'
            ),
            'defaultView' => 'agendaWeek',
            'timezone' => 'Europe/Paris',
            'timeFormat' => "H:mm",
            'businessHours' => array(
                'start' => '08:00',
                'end' => '17:00',
                'dow' => array(1,2,3,4,5)
            )
	    );
	    
	    $node
    	    ->treatTrueLike(array(
    	        'src_dir' => '%kernel.root_dir%/../vendor/fullcalendar/fullcalendar',
    	        'js' => "dist/fullcalendar.min.js",
    	        'css' => "dist/fullcalendar.min.css",
    	        'selector' => '#calendar-holder',
    	        'config' => $config,
    	        'customize' => array(
    	            'load_events_route' => 'asf_scheduler_fullcalendar_loader',
    	            'dest_dir' => '%kernel.root_dir%/../web/js/fullcalendar'
    	        )
    	    ))
    	    ->treatFalseLike(array(
    	        'calendar_dir' => false
    	    ))
    	    ->treatNullLike(array(
    	        'src_dir' => '%kernel.root_dir%/../vendor/fullcalendar/fullcalendar',
    	        'js' => "dist/fullcalendar.min.js",
    	        'css' => "dist/fullcalendar.min.css",
    	        'selector' => '#calendar-holder',
    	        'config' => $config,
    	        'customize' => array(
    	            'load_events_route' => 'asf_scheduler_fullcalendar_loader',
    	            'dest_dir' => '%kernel.root_dir%/../web/js/fullcalendar'
    	        )
    	    ))
    	    ->addDefaultsIfNotSet()
    	    ->children()
                ->scalarNode('src_dir')
                    ->cannotBeEmpty()
                    ->defaultValue('%kernel.root_dir%/../vendor/fullcalendar/fullcalendar')
                ->end()
                ->scalarNode('js')
                    ->cannotBeEmpty()
                    ->defaultValue("dist/fullcalendar.min.js")
                ->end()
                ->scalarNode('css')
            	    ->cannotBeEmpty()
            	    ->defaultValue("dist/fullcalendar.min.css")
                ->end()
        	    ->scalarNode('lang')
            	    ->cannotBeEmpty()
            	    ->defaultValue("dist/lang-all.js")
        	    ->end()
        	    ->scalarNode('selector')
            	    ->cannotBeEmpty()
            	    ->defaultValue('#calendar-holder')
        	    ->end()
        	    ->arrayNode('config')
        	       ->fixXmlConfig('config')
        	       ->prototype('scalar')->end()
        	       ->defaultValue($config)
        	    ->end()
        	    ->arrayNode('customize')
        	       ->addDefaultsIfNotSet()
        	       ->children()
            	       ->scalarNode('dest_dir')
                	       ->cannotBeEmpty()
                	       ->defaultValue('%kernel.root_dir%/../web/js/fullcalendar')
            	       ->end()
            	       ->arrayNode('exclude_files')
                	       ->fixXmlConfig('exclude_files')
                	       ->prototype('scalar')->end()
            	       ->end()
            	       ->scalarNode('load_events_route')
            	           ->defaultValue('asf_scheduler_fullcalendar_loader')
            	       ->end()
        	       ->end()
        	    ->end()
    	    ->end();
	    
	    return $node;
	}
	
	/**
	 * Add Moment Js Node Parameter
	 */
	protected function addMomentParameterNode()
	{
	    $builder = new TreeBuilder();
	    $node = $builder->root('moment');
        
	    $node
    	    ->treatTrueLike(array(
    	        'src_dir' => '%kernel.root_dir%/../vendor/moment/moment',
    	        'js' => "min/moment.min.js",
    	        'customize' => array(
    	            'dest_dir' => '%kernel.root_dir%/../web/js/moment',
    	        )
    	    ))
    	    ->treatFalseLike(array(
    	        'src_dir' => false
    	    ))
    	    ->treatNullLike(array(
    	        'src_dir' => '%kernel.root_dir%/../vendor/moment/moment',
    	        'js' => "min/moment.min.js",
    	        'customize' => array(
    	            'dest_dir' => '%kernel.root_dir%/../web/js/moment',
    	        )
    	    ))
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('src_dir')
                    ->cannotBeEmpty()
                    ->defaultValue('%kernel.root_dir%/../vendor/moment/moment')
                ->end()
                ->scalarNode('js')
                    ->cannotBeEmpty()
                    ->defaultValue('min/moment.min.js')
                ->end()
                ->arrayNode('customize')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('dest_dir')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.root_dir%/../web/js/moment')
                        ->end()
                        ->arrayNode('exclude_files')
                            ->fixXmlConfig('exclude_files')
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    	    
        return $node;
	}

	/**
	 * Add Calendar Event Entity Configuration
	 */
	protected function addCalendarEventParameterNode()
	{
	    $builder = new TreeBuilder();
	    $node = $builder->root('calendar_event');
	
	    $node
    	    ->treatTrueLike(array('form' => array('type' => "ASF\SchedulerBundle\Form\Type\CalendarEventType")))
    	    ->treatFalseLike(array('form' => array('type' => "ASF\SchedulerBundle\Form\Type\CalendarEventType")))
    	    ->addDefaultsIfNotSet()
    	    ->children()
        	    ->arrayNode('form')
            	    ->addDefaultsIfNotSet()
            	    ->children()
                	    ->scalarNode('type')
                	       ->defaultValue('ASF\SchedulerBundle\Form\Type\CalendarEventType')
                	    ->end()
                	    ->scalarNode('name')
                	       ->defaultValue('calendar_event_type')
                	    ->end()
                	    ->arrayNode('validation_groups')
                	       ->prototype('scalar')->end()
                	       ->defaultValue(array("Default"))
                	    ->end()
            	    ->end()
        	    ->end()
    	    ->end()
	    ;
	
	    return $node;
	}
	
	/**
	 * Add Calendar Event Category Entity Configuration
	 */
	protected function addCalendarEventCategoryParameterNode()
	{
	    $builder = new TreeBuilder();
	    $node = $builder->root('calendar_event_category');
	
	    $node
    	    ->treatTrueLike(array('form' => array('type' => "ASF\SchedulerBundle\Form\Type\CalendarEventCategoryType")))
    	    ->treatFalseLike(array('form' => array('type' => "ASF\SchedulerBundle\Form\Type\CalendarEventCategoryType")))
    	    ->addDefaultsIfNotSet()
    	    ->children()
        	    ->arrayNode('form')
            	    ->addDefaultsIfNotSet()
            	    ->children()
                	    ->scalarNode('type')
                	       ->defaultValue('ASF\SchedulerBundle\Form\Type\CalendarEventCategoryType')
                	    ->end()
                	    ->scalarNode('name')
                	       ->defaultValue('calendar_event_category_type')
                	    ->end()
                	    ->arrayNode('validation_groups')
                	       ->prototype('scalar')->end()
                	       ->defaultValue(array("Default"))
                	    ->end()
                	->end()
            	->end()
    	    ->end()
	    ;
	
	    return $node;
	}
}