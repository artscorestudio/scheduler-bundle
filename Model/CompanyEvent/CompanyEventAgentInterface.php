<?php
/*
 * This file is part of the Artscore Studio Framework package.
 *
 * (c) Nicolas Claverie <info@artscore-studio.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ASF\SchedulerBundle\Model\CompanyEvent;

/**
 * Company Event Agent Interface
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface CompanyEventAgentInterface
{
	/**
	 * Return the name of the attached agent to the event
	 * 
	 * @return string
	 */
	public function getName();
}