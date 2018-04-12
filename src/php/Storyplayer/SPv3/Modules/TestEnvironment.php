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
 * @package   Storyplayer/Modules/Supervisor
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2018-present Ganbaro Digital Ltd https://ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules;

use DataSift\Storyplayer\PlayerLib\StoryTeller;

use Storyplayer\SPv3\Modules\TestEnvironment\ExpectsRolesTable;
use Storyplayer\SPv3\Modules\TestEnvironment\FromRolesTable;
use Storyplayer\SPv3\Modules\TestEnvironment\FromTargetsTable;
use Storyplayer\SPv3\Modules\TestEnvironment\FromTestEnvironment;
use Storyplayer\SPv3\Modules\TestEnvironment\UsingProvisioning;
use Storyplayer\SPv3\Modules\TestEnvironment\UsingProvisioningDefinition;
use Storyplayer\SPv3\Modules\TestEnvironment\UsingProvisioningEngine;
use Storyplayer\SPv3\Modules\TestEnvironment\UsingRolesTable;
use Storyplayer\SPv3\Modules\TestEnvironment\UsingTargetsTable;

class TestEnvironment
{
    /**
     * returns the ExpectsRolesTable module
     *
     * This module provides support for ensuring that the roles table
     * has the entries that you expect.
     *
     * @return ExpectsRolesTable
     */
    public static function expectsRolesTable()
    {
        return new ExpectsRolesTable(StoryTeller::instance());
    }

    /**
     * returns the FromRolesTable module
     *
     * This module provides support looking for entries in the roles table.
     *
     * @return FromRolesTable
     */
    public static function fromRolesTable()
    {
        return new FromRolesTable(StoryTeller::instance());
    }

    /**
     *
     * @return FromTargetsTable
     */
    public static function fromTargetsTable()
    {
        return new FromTargetsTable(StoryTeller::instance());
    }

    /**
     *
     * @return FromTestEnvironment
     */
    public static function fromTestEnvironment()
    {
        return new FromTestEnvironment(StoryTeller::instance());
    }

    /**
     *
     * @return UsingProvisioning
     */
    public static function usingProvisioning()
    {
        return new UsingProvisioning(StoryTeller::instance());
    }

    /**
     *
     * @return UsingProvisioningDefinition
     */
    public static function usingProvisioningDefinition()
    {
        return new UsingProvisioningDefinition(StoryTeller::instance());
    }

    /**
     *
     * @return UsingProvisioningEngine
     */
    public static function usingProvisioningEngine()
    {
        return new UsingProvisioningEngine(StoryTeller::instance());
    }

    /**
     *
     * @return UsingRolesTable
     */
    public static function usingRolesTable()
    {
        return new usingRolesTable(StoryTeller::instance());
    }

    /**
     *
     * @return UsingTargetsTable
     */
    public static function usingTargetsTable()
    {
        return new UsingTargetsTable(StoryTeller::instance());
    }
}
