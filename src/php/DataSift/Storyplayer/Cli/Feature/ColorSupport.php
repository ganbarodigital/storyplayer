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
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Exception;
use stdClass;
use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\ExceptionsLib1\Legacy_ErrorHandler;
use Phix_Project\ExceptionsLib1\Legacy_ErrorException;
use DataSift\Stone\ConfigLib\E5xx_ConfigFileNotFound;
use DataSift\Stone\ConfigLib\E5xx_InvalidConfigFile;
use DataSift\Storyplayer\PlayerLib\E4xx_NoSuchReport;
use DataSift\Storyplayer\PlayerLib\TalePlayer;
use DataSift\Storyplayer\Console\DevModeConsole;
use DataSift\Storyplayer\PlayerLib\StoryTeller;
use DataSift\Storyplayer\Injectables;

/**
 * Support for enabling / disabling colour
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class Feature_ColorSupport implements Feature
{
    public function addSwitches(CliCommand $command, $additionalContext)
    {
        $command->addSwitches([
            new Feature_ColorSwitch
        ]);
    }

    public function initBeforeModulesAvailable(CliEngine $engine, CliCommand $command, Injectables $injectables)
    {
    	// which colour mode are we in?
    	switch ($engine->options->color) {
    		case Feature_ColorSwitch::NO_COLOR:
    			$injectables->output->disableColourSupport();
    			break;

    		case Feature_ColorSwitch::ALWAYS_COLOR:
    			$injectables->output->enforceColourSupport();
    			break;

    		case Feature_ColorSwitch::AUTO_COLOR:
    			$injectables->output->enableColourSupport();
    			break;
    	}
    }

    public function initAfterModulesAvailable(StoryTeller $st, CliEngine $engine, Injectables $injectables)
    {
        // no-op
    }
}
