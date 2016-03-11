<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Copy Moment.js Files Command
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
class CopyMomentFilesCommand extends ContainerAwareCommand
{
    /**
     * @var array
     */
    protected $config;

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this->setName('asf:moment:copy')
            ->setDescription('Install Moment.js files in web folder')
            ->addArgument(
                'target_dir',
                InputArgument::OPTIONAL,
                'Where do you want to copy files ?'
            )
            ->addOption(
                'exclude_files',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
                'Fill in the list of exclude files (separate multiple names with a space) ?'
            )
        ;
    }
    
    /**
     * {@inheritDoc}
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$assets = $this->getContainer()->getParameter('asf_scheduler.assets');
    	$this->config = $assets['moment'];
        
        $dest_dir = $input->getArgument('target_dir') ? $input->getArgument('target_dir') : null;
        if ( is_null($dest_dir) && isset($this->config['customize']['dest_dir']) ) {
            $dest_dir = $this->config['customize']['dest_dir'];
        }
        
        $exclude_files = $input->getOption('exclude_files') ? $input->getOption('exclude_files') : $this->config['customize']['exclude_files'];
        $src_dir = sprintf('%s', $this->config['src_dir'].'/min');

        $finder = new Finder();
        $fs = new Filesystem();
        
        try {
            if ( !$fs->exists($dest_dir) )
                $fs->mkdir($dest_dir);
        
        } catch (IOException $e) {
            $output->writeln(sprintf('<error>Could not create directory %s.</error>', $dest_dir));
            return;
        }
        
        if (false === file_exists($src_dir)) {
            $output->writeln(sprintf(
                '<error>Source directory "%s" does not exist. Did you install Moment.js ? '.
                'Don\'t forget to specify the path to Moment.js folder in '.
                '"asf_scheduler.assets.moment.src_dir".</error>',
                $src_dir
                ));
            return;
        }
        
        foreach ($iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($src_dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST) as $item) {
                if ($item->isDir() && !in_array($item->getBasename(), $exclude_files)) {
                    $fs->mkdir($dest_dir . '/' . $iterator->getSubPathName());
                } elseif ( !in_array($item->getBasename(), $exclude_files) ) {
                    $fs->copy($item, $dest_dir . '/' . $iterator->getSubPathName());
                }
            }
            
        $output->writeln(sprintf('[OK] Moment.js files was successfully copied.'));
    }
}