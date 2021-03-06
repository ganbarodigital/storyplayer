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
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules\TestEnvironment;

use Prose\Prose;
use DataSift\Stone\ObjectLib\BaseObject;
use Storyplayer\SPv3\Modules\Log;
use StoryplayerInternals\SPv3\Modules\RuntimeTable;

/**
 * retrieve data from the internal hosts table
 *
 * @category  Libraries
 * @package   Storyplayer/Prose
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class FromRolesTable extends Prose
{
    /**
     * entryKey
     * The key that this table interacts with in the RuntimeConfig
     *
     * @var string
     */
    protected $entryKey = "roles";


    /**
     * getRolesTable
     *
     * @return object The roles table
     */
    public function getRolesTable()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get the roles table for the current test environment");

        // what is our roles table?
        $rolesTable = RuntimeTable::fromRuntimeTable($this->entryKey)->getTable();

        // all done
        $log->endAction();
        return $rolesTable;
    }

    /**
     * getDetailsForRole
     *
     * @param string $roleName
     *        The role we're looking for
     *
     * @return BaseObject Details about $roleName
     */
    public function getDetailsForRole($roleName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("get details for role '{$roleName}' for the current test environment");

        // pull the table
        $rolesTable = $this->getRolesTable();

        if (!isset($rolesTable->$roleName)) {
            // create an empty entry for this role
            $rolesTable->$roleName = [];
        }

        // all done
        $log->endAction();
        return $rolesTable->$roleName;
    }
}
