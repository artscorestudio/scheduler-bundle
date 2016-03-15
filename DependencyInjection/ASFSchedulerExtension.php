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

use Symfony\Component\HttpKernel\DependencyInjection\Extension;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

/**
 * Bundle extension
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class ASFSchedulerExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Maps parameters in container
     *
     * @param ContainerBuilder $container
     * @param string $rootNodeName
     * @param array $config
     */
    public function mapsParameters(ContainerBuilder $container, $rootNodeName, $config)
    {
        foreach($config as $name => $value) {
            if ( is_array($value) ) {
                $this->mapsParameters($container, $rootNodeName . '.' . $name, $value);
            } else {
                $container->setParameter($rootNodeName . '.' . $name, $value);
            }
        }
    }
    
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\DependencyInjection\Extension\ExtensionInterface::load()
	 */
	public function load(array $configs, ContainerBuilder $container)
	{
		$configuration = new Configuration();
		$config = $this->processConfiguration($configuration, $configs);
        $this->mapsParameters($container, $this->getAlias(), $config);
		$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		
		if ( $config['enable_twig_support'] == true ) {
		    $container->setParameter('asf_scheduler.assets', $config['assets']);
		}
		
		$loader->load('services/services.xml');
		$loader->load('services/calendar_event.xml');
		$loader->load('services/calendar_event_category.xml');
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface::prepend()
	 */
	public function prepend(ContainerBuilder $container)
	{
	    $bundles = $container->getParameter('kernel.bundles');
	
	    $configs = $container->getExtensionConfig($this->getAlias());
	    $config = $this->processConfiguration(new Configuration(), $configs);
	
	    if ( $config['enable_twig_support'] == true )
	    	$this->configureTwigBundle($container, $config);
	    
	    if ( !array_key_exists('FOSJsRoutingBundle', $bundles) && $config['assets']['fos_js_routing'] == true )
	        throw new InvalidConfigurationException('You have enabled the support of FOSJsRouting but it is not enabled. Install it or disable FOSJsRoutingBundle support in Layout bundle.');

	    if ( !array_key_exists('AsseticBundle', $bundles) && $config['enable_assetic_support'] == true )
	       throw new InvalidConfigurationException('You have enabled the support of Assetic but Assetic is not enabled. Please install symfony/assetic-bundle.');
	
	    if ( array_key_exists('AsseticBundle', $bundles) && count($config['assets']) > 0 && $config['enable_assetic_support'] == true )
	        $this->configureAsseticBundle($container, $config);
	}
	
	/**
	 * Add assets to Assetic Bundle
	 *
	 * @param ContainerBuilder $container
	 * @param array $config
	 */
	public function configureAsseticBundle(ContainerBuilder $container, array $config)
	{
	    foreach(array_keys($container->getExtensions()) as $name) {
	        switch($name) {
	            case 'assetic':
            	    if ( $config['assets']['fullcalendar']['src_dir'] !== false ) {
            	        $container->prependExtensionConfig('assetic', array(
            	            'assets' => array(
            	                'fullcalendar_js' => $config['assets']['fullcalendar']['src_dir'].'/'.$config['assets']['fullcalendar']['js'],
            	                'fullcalendar_css' => $config['assets']['fullcalendar']['src_dir'].'/'.$config['assets']['fullcalendar']['css'],
            	                'fullcalendar_lang_js' => $config['assets']['fullcalendar']['src_dir'].'/'.$config['assets']['fullcalendar']['lang']
            	            )
            	        ));
            	    }
            	    
            	    if ( $config['assets']['moment']['src_dir'] !== false ) {
            	        $container->prependExtensionConfig('assetic', array(
            	            'assets' => array(
            	                'moment_js' => $config['assets']['moment']['src_dir'].'/'.$config['assets']['moment']['js']
            	            )
            	        ));
            	    }
	                break;
	        }
	    }
	}
	
	/**
	 * Configure twig bundle
	 *
	 * @param ContainerBuilder $container
	 * @param array $config
	 */
	public function configureTwigBundle(ContainerBuilder $container, array $config)
	{
		foreach(array_keys($container->getExtensions()) as $name) {
			switch($name) {
				case 'twig':
					$container->prependExtensionConfig($name, array(
					'form_themes' => array($config['form_theme'])
					));
					break;
			}
		}
	}
}