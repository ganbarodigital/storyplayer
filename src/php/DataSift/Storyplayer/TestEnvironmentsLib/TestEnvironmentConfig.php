<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/TestEnvironmentsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\TestEnvironmentsLib;

use DataSift\Storyplayer\ConfigLib\WrappedConfig;
use DataSift\Stone\ObjectLib\BaseObject;

/**
 * the class for the config that represents a single test environment
 *
 * @category  Libraries
 * @package   Storyplayer/TestEnvironmentsLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class TestEnvironmentConfig extends WrappedConfig
{
    public function __construct()
    {
        parent::__construct(self::ROOT_IS_ARRAY);
    }

    public function validateConfig()
    {
        // do we have any config?
        $config = $this->getConfig();
        if (count($config) == 0) {
            // at the moment, an empty test environment is not considered
            // to be an error
            //
            // perhaps it should be one day?
            return;
        }

        foreach ($config as $index => $group) {
            // what type of machines does this group define?
            if (!isset($group->type)) {
                throw new E4xx_TestEnvironmentGroupNeedsAType($index);
            }
            // is this a valid type?
            $this->validateGroupType($index, $group->type);

            // do we have any machines defined?
            if (!isset($group->details, $group->details->machines)) {
                throw new E4xx_TestEnvironmentGroupNeedsMachines($index);
            }

            // are the machines valid?
            foreach ($group->details->machines as $name => $machine) {
                // does each machine have a role?
                if (!isset($machine->roles)) {
                    throw new E4xx_TestEnvironmentMachineNeedsARole($index, $name);
                }
                // is the roles an array?
                if (!is_array($machine->roles)) {
                    throw new E4xx_TestEnvironmentMachineRolesMustBeArray($index, $name, gettype($machine->roles));
                }
            }
        }
    }

    protected function validateGroupType($groupIndex, $typeName)
    {
        $className = "DataSift\Storyplayer\HostLib\\" . $typeName;
        if (!class_exists($className)) {
            throw new E4xx_InvalidTestEnvironmentGroupType($groupIndex, $typeName, $className);
        }
    }

	public function mergeSystemUnderTestConfig($sutConfig)
	{
        // do we have anything to merge?
        if (!$sutConfig->hasData('roles')) {
            // nothing to merge
            return;
        }

        // get the list of roles
        $sutRoles = $sutConfig->getData('roles');

        foreach ($sutRoles as $sutRole) {
            foreach ($this->getConfig() as $envDetails) {
                foreach ($envDetails->details->machines as $machine) {
                    if (in_array($sutRole->role, $machine->roles) || in_array('*', $machine->roles)) {
                        if (!isset($machine->params)) {
                            $machine->params = new BaseObject;
                        }
                        $machine->params->mergeFrom($sutRole->params);
                    }
                }
            }
        }
	}
}
