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
	    
	    $node
    	    ->treatTrueLike(array(
    	        'src_dir' => '%kernel.root_dir%/../vendor/fullcalendar/fullcalendar',
    	        'js' => "dist/fullcalendar.min.js",
    	        'css' => "dist/fullcalendar.min.css",
    	        'selector' => '#calendar-holder',
    	        'config' => array(),
    	        'customize' => array(
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
    	        'config' => array(),
    	        'customize' => array(
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
        	    ->arrayNode('config')->end()
        	    ->arrayNode('customize')
        	       ->addDefaultsIfNotSet()
        	       ->children()
            	       ->scalarNode('dest_dir')
                	       ->cannotBeEmpty()
                	       ->defaultValue('%kernel.root_dir%/../web/js/fullcalendar')
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
    	        'js' => "moment.js",
    	        'customize' => array(
    	            'dest_dir' => '%kernel.root_dir%/../web/js/moment',
    	        )
    	    ))
    	    ->treatFalseLike(array(
    	        'src_dir' => false
    	    ))
    	    ->treatNullLike(array(
    	        'src_dir' => '%kernel.root_dir%/../vendor/moment/moment',
    	        'js' => "moment.js",
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
                    ->defaultValue('moment.js')
                ->end()
                ->arrayNode('customize')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('dest_dir')
                            ->cannotBeEmpty()
                            ->defaultValue('%kernel.root_dir%/../web/js/moment')
                        ->end()
                    ->end()
                ->end()
            ->end();
    	    
        return $node;
	}
}