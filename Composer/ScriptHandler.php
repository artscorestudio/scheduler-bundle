<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\LayoutBundle\Composer;

use Composer\Script\CommandEvent;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * ScriptHandler
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class ScriptHandler
{
    /**
     * Composer variables are declared static so that an event could update
     * a composer.json and set new options, making them immediately available
     * to forthcoming listeners.
     */
    protected static $options = array(
        'symfony-app-dir' => 'app',
        'symfony-web-dir' => 'web',
        'symfony-assets-install' => 'hard',
        'symfony-cache-warmup' => false,
    );
    
    /**
     * Asks if the new directory structure should be used, installs the structure if needed.
     *
     * @param CommandEvent $event
     */
    public static function defineDirectoryStructure(CommandEvent $event)
    {
        $options = static::getOptions($event);
    
        if (!getenv('SENSIOLABS_ENABLE_NEW_DIRECTORY_STRUCTURE') || !$event->getIO()->askConfirmation('Would you like to use Symfony 3 directory structure? [y/N] ', false)) {
            return;
        }
    
        $rootDir = getcwd();
        $appDir = $options['symfony-app-dir'];
        $webDir = $options['symfony-web-dir'];
        $binDir = static::$options['symfony-bin-dir'] = 'bin';
        $varDir = static::$options['symfony-var-dir'] = 'var';
    }
    
    /**
     * @param CommandEnvent $event
     */
    public static function installFullCalendar(CommandEvent $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'install FullCalendar.io files');
        
        if (null === $consoleDir) {
            return;
        }
        
        $webDir = $options['symfony-web-dir'];
        
        if (!static::hasDirectory($event, 'symfony-web-dir', $webDir, 'install FullCalendar.io files')) {
            return;
        }
        
        static::executeCommand($event, $consoleDir, 'asf:fullcalendar:copy', $options['process-timeout']);
    }
    
    /**
     * @param CommandEnvent $event
     */
    public static function installMoment(CommandEvent $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'install Moment.js files');
        
        if (null === $consoleDir) {
            return;
        }
        
        $webDir = $options['symfony-web-dir'];
        
        if (!static::hasDirectory($event, 'symfony-web-dir', $webDir, 'install Moment.js files')) {
            return;
        }
        
        static::executeCommand($event, $consoleDir, 'asf:moment:copy', $options['process-timeout']);
    }
    
    /**
     * @param CommandEvent $event
     * @param string       $consolePath
     * @param string       $cmd
     * @param number       $timeout
     * @throws \RuntimeException
     */
    public static function executeCommand(CommandEvent $event, $consoleDir, $cmd, $timeout = 300)
    {
        $php = escapeshellarg(static::getPhp(false));
        $phpArgs = implode(' ', array_map('escapeshellarg', static::getPhpArguments()));
        $console = escapeshellarg($consoleDir.'/console');
        if ($event->getIO()->isDecorated()) {
            $console .= ' --ansi';
        }

        $process = new Process($php.($phpArgs ? ' '.$phpArgs : '').' '.$console.' '.$cmd, null, null, null, $timeout);
        $process->run(function ($type, $buffer) use ($event) { $event->getIO()->write($buffer, false); });
        if (!$process->isSuccessful()) {
            throw new \RuntimeException(sprintf("An error occurred when executing the \"%s\" command:\n\n%s\n\n%s.", escapeshellarg($cmd), $process->getOutput(), $process->getErrorOutput()));
        }
    }
    
    protected static function hasDirectory(CommandEvent $event, $configName, $path, $actionName)
    {
        if (!is_dir($path)) {
            $event->getIO()->write(sprintf('The %s (%s) specified in composer.json was not found in %s, can not %s.', $configName, $path, getcwd(), $actionName));
    
            return false;
        }
    
        return true;
    }
    
    /**
     * Clears the Symfony cache.
     *
     * @param $event CommandEvent A instance
     */
    public static function clearCache(CommandEvent $event)
    {
        $options = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'clear the cache');
    
        if (null === $consoleDir) {
            return;
        }
    
        $warmup = '';
        if (!$options['symfony-cache-warmup']) {
            $warmup = ' --no-warmup';
        }
    
        static::executeCommand($event, $consoleDir, 'cache:clear'.$warmup, $options['process-timeout']);
    }
    
    protected static function getOptions(CommandEvent $event)
    {
        $options = array_merge(static::$options, $event->getComposer()->getPackage()->getExtra());

        $options['symfony-assets-install'] = getenv('SYMFONY_ASSETS_INSTALL') ?: $options['symfony-assets-install'];

        $options['process-timeout'] = $event->getComposer()->getConfig()->get('process-timeout');

        return $options;
    }
    
    protected static function getPhp($includeArgs = true)
    {
        $phpFinder = new PhpExecutableFinder();
        if (!$phpPath = $phpFinder->find($includeArgs)) {
            throw new \RuntimeException('The php executable could not be found, add it to your PATH environment variable and try again');
        }

        return $phpPath;
    }

    protected static function getPhpArguments()
    {
        $arguments = array();

        $phpFinder = new PhpExecutableFinder();
        if (method_exists($phpFinder, 'findArguments')) {
            $arguments = $phpFinder->findArguments();
        }

        if (false !== $ini = php_ini_loaded_file()) {
            $arguments[] = '--php-ini='.$ini;
        }

        return $arguments;
    }
    
    /**
     * Returns a relative path to the directory that contains the `console` command.
     *
     * @param CommandEvent $event      The command event.
     * @param string       $actionName The name of the action
     *
     * @return string|null The path to the console directory, null if not found.
     */
    protected static function getConsoleDir(CommandEvent $event, $actionName)
    {
        $options = static::getOptions($event);
    
        if (static::useNewDirectoryStructure($options)) {
            if (!static::hasDirectory($event, 'symfony-bin-dir', $options['symfony-bin-dir'], $actionName)) {
                return;
            }
    
            return $options['symfony-bin-dir'];
        }
    
        if (!static::hasDirectory($event, 'symfony-app-dir', $options['symfony-app-dir'], 'execute command')) {
            return;
        }
    
        return $options['symfony-app-dir'];
    }
    
    /**
     * Returns true if the new directory structure is used.
     *
     * @param array $options Composer options
     *
     * @return bool
     */
    protected static function useNewDirectoryStructure(array $options)
    {
        return isset($options['symfony-var-dir']) && is_dir($options['symfony-var-dir']);
    }
}