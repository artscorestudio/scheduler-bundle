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

use ASF\SchedulerBundle\Model\CompanyEventCategory\CompanyEventCategoryInterface;

/**
 * Company Event Interface
 * 
 * @author Nicolas Claverie <info@artscore-studio.fr>
 *
 */
interface CompanyEventInterface
{
	/**
	 * @return \ASF\SchedulerBundle\Model\CompanyEventCategory\CompanyEventCategoryInterface
	 */
	public function getCategory();
	
	/**
	 * @param \ASF\SchedulerBundle\Model\CompanyEventCategory\CompanyEventCategoryInterface $category
	 * @return \ASF\SchedulerBundle\Model\CompanyEvent\CompanyEventInterface
	 */
	public function setCategory(CompanyEventCategoryInterface $category);
	
	/**
	 * @param \ASF\SchedulerBundle\Model\CompanyEvent\CompanyEventAgentInterface $agent
	 */
	public function getAgent();
	
	/**
	 * @param \ASF\SchedulerBundle\Model\CompanyEvent\CompanyEventAgentInterface $agent
	 * @return \ASF\SchedulerBundle\Model\CompanyEvent\CompanyEventInterface
	 */
	public function setAgent(CompanyEventAgentInterface $agent);
}