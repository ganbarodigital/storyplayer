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

use Storyplayer\SPv3\Modules\Exceptions;
use Storyplayer\SPv3\Modules\Filesystem;
use Storyplayer\SPv3\Modules\Host;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Modules\Screen;
use StoryplayerInternals\SPv3\Modules\Deprecated;
use StoryplayerInternals\SPv3\Helpers\ManualUrls;

/**
 *
 * test the state of a (possibly remote) computer
 *
 * @category  Libraries
 * @package   Storyplayer/Modules/Host
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class ExpectsHost extends HostAwareModule
{
    public function hostIsRunning()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("make sure host '{$this->args[0]}' is running");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // is it running?
        $running = Host::fromHost($hostDetails->hostId)->getHostIsRunning();
        if (!$running) {
            $log->endAction();
            throw Exceptions::newExpectFailedException(__METHOD__, 'host is running', 'host is not running');
        }

        // all done
        $log->endAction();
    }

    public function hostIsNotRunning()
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("make sure host '{$this->args[0]}' is not running");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // is it running?
        $running = Host::fromHost($hostDetails->hostId)->getHostIsRunning();
        if ($running) {
            $log->endAction();
            throw Exceptions::newExpectFailedException(__METHOD__, 'host is not running', 'host is running');
        }

        // all done
        $log->endAction();
    }

    public function packageIsInstalled($packageName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("make sure package '{$packageName}' is installed on host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // is it installed?
        $details = Host::fromHost($hostDetails->hostId)->getInstalledPackageDetails($packageName);
        if (!isset($details->version)) {
            $log->endAction();
            throw Exceptions::newExpectFailedException(__METHOD__, "package installed", "package is not installed");
        }

        // all done
        $log->endAction();
    }

    public function packageIsNotInstalled($packageName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("make sure package '{$packageName}' is not installed on host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // is it installed?
        $details = Host::fromHost($hostDetails->hostId)->getInstalledPackageDetails($packageName);

        if (isset($details->version)) {
            $log->endAction();
            throw Exceptions::newExpectFailedException(__METHOD__, "package not installed", "package is installed");
        }

        // all done
        $log->endAction();
    }

    public function processIsRunning($processName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("make sure process '{$processName}' is running on host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // is the process running?
        $isRunning = Host::fromHost($hostDetails->hostId)->getProcessIsRunning($processName);

        if (!$isRunning) {
            throw Exceptions::newExpectFailedException(__METHOD__, "process '{$processName}' running", "process '{$processName}' is not running");
        }

        // all done
        $log->endAction();
    }

    public function processIsNotRunning($processName)
    {
        // what are we doing?
        $log = Log::usingLog()->startAction("make sure process '{$processName}' is not running on host '{$this->args[0]}'");

        // make sure we have valid host details
        $hostDetails = $this->getHostDetails();

        // is the process running?
        $isRunning = Host::fromHost($hostDetails->hostId)->getProcessIsRunning($processName);

        if ($isRunning) {
            throw Exceptions::newExpectFailedException(__METHOD__, "process '{$processName}' not running", "process '{$processName}' is running");
        }

        // all done
        $log->endAction();
    }

    /**
     * @deprecated since v2.4.0
     */
    public function screenIsRunning($sessionName)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Screen::expectsHost($this->args[0])->screenIsRunning($sessionName);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function screenIsNotRunning($sessionName)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Screen::expectsHost($this->args[0])->screenIsNotRunning($sessionName);
    }

    /**
     * the old SPv1 call, when this was part of expectsShell()
     *
     * @deprecated use $this->screenIsRunning() instead
     *
     * @param  string $sessionName
     *         name of the screen session to check on
     * @return void
     */
    public function isRunningInScreen($sessionName)
    {
        return $this->screenIsRunning($sessionName);
    }

    /**
     * the old SPv1 call, when this was part of expectsShell()
     *
     * @deprecated use $this->screenIsNotRunning() instead
     *
     * @param  string $sessionName
     *         name of the screen session to check on
     * @return void
     */
    public function isNotRunningInScreen($sessionName)
    {
        return $this->screenIsNotRunning($sessionName);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function hasFileWithPermissions($filename, $owner, $group, $mode)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Filesystem::expectsHost($this->args[0])->hasFileWithPermissions($filename, $owner, $group, $mode);
    }

    /**
     * @deprecated since v2.4.0
     */
    public function hasFolderWithPermissions($folder, $owner, $group, $mode)
    {
        Deprecated::fireDeprecated(__METHOD__, "v2.4.0", ManualUrls::HOST_MODULE_BREAKUP);
        return Filesystem::expectsHost($this->args[0])->hasFolderWithPermissions($folder, $owner, $group, $mode);
    }
}
