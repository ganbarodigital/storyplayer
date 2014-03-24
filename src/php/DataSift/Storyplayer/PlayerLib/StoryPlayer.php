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
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\PlayerLib;

use Exception;
use stdClass;
use DataSift\Stone\LogLib\Log;
use DataSift\Stone\ObjectLib\E5xx_NoSuchProperty;
use DataSift\Storyplayer\Phases\PhaseResult;
use DataSift\Storyplayer\Prose\E5xx_ActionFailed;
use DataSift\Storyplayer\Prose\E5xx_ExpectFailed;
use DataSift\Storyplayer\Prose\E5xx_NotImplemented;
use DataSift\Storyplayer\StoryLib\Story;
use DataSift\Storyplayer\UserLib\UserGenerator;

/**
 * the main class for animating a single story
 *
 * @category  Libraries
 * @package   Storyplayer/PlayerLib
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class StoryPlayer
{
	const NEXT_CONTINUE  = 1;
	const NEXT_SKIPSTORY = 2;
	const NEXT_FAILSTORY = 3;

	public function play(StoryTeller $st, $phaseTypes)
	{
		// shorthand
		$story   = $st->getStory();
		$env     = $st->getEnvironment();
		$envName = $st->getEnvironmentName();
		$output  = $st->getOutput();
		$context = $st->getStoryContext();

		// set default callbacks up
		$story->setDefaultCallbacks();

		// keep track of how each phase goes
		$storyResult = new StoryResult($story);

		// this will keep track of any paired phases that we need to
		// attempt if we fail to execute the whole story
		$pairedPhases = array();
		foreach ($phaseTypes as $phaseType) {
			$pairedPhases[$phaseType] = [];
		}

		// tell the outside world what we're doing
		$this->announceStory($st);

		// we are going to need something to help us load each of our
		// phases
		$phasePlayer = new PhasePlayer;
		foreach($phaseTypes as $phaseType)
		{
			$phasePlayer->playPhases($st, $storyResult, $phaseType, $pairedPhases);
		}

		// make sense of what happened
		$storyResult->calculateStoryResult();

		// announce the results
		$output->endStory($storyResult);

		// all done
		return $storyResult;
	}

	public function announceStory(StoryTeller $st)
	{
		// shorthand
		$story = $st->getStory();
		$output = $st->getOutput();

		// tell all of our output plugins that the story has begun
		$output->startStory(
			$story->getName(),
			$story->getCategory(),
			$story->getGroup(),
			$st->getEnvironmentName(),
			$st->getDeviceName()
		);
	}
}
