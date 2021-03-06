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
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Modules\Host;

use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Stone\ObjectLib\BaseObject;
use Prose\Prose;
use Storyplayer\SPv3\Modules\Exceptions;
use Storyplayer\SPv3\Modules\Host;

/**
 * base class for all 'Host' Prose modules
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class HostAwareModule extends Prose
{
    protected $hostDetails;

    public function __construct(StoryTeller $st, $args = array())
    {
        // call the parent constructor
        parent::__construct($st, $args);

        // arg[0] is the name of the box
        if (!isset($args[0])) {
            throw Exceptions::newActionFailedException(__METHOD__, "Param #0 needs to be the name you've given to the machine");
        }
    }

    protected function getHostDetails()
    {
        // shorthand
        $hostId = $this->args[0];

        // do we know anything about this host?
        $hostsTable = Host::fromHostsTable()->getHostsTable();
        if (!isset($hostsTable->$hostId)) {
            $hostDetails = new BaseObject();
            $hostDetails->hostId = $hostId;
            $hostDetails->osName = "unknown";
            $hostDetails->nameInHostsTable = $hostId;
            $hostDetails->invalidHost = true;
        }
        else {
            $hostDetails = $hostsTable->$hostId;
        }

        return $hostDetails;
    }

    protected function getIsLocalhost()
    {
        if (strtolower($this->args[0]) == 'localhost') {
            return true;
        }

        return false;
    }
}
