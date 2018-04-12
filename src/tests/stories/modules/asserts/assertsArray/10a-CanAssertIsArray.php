<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Failure;
use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Stories\BuildStory;
use Storyplayer\SPv3\Stories\Story;
use Storyplayer\SPv3\Stories\StoryPlayer as SP;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = BuildStory::newStory();

// ========================================================================
//
// ACTION
//
// ------------------------------------------------------------------------

SP::runStep($story, function(Story $story) {
	// what are we doing?
	$log = Log::usingLog()->startStep("we can check for an array");

	// perform the test
	Asserts::assertsArray([])->isArray();
});

// ========================================================================
//
// ACTION
//
// ------------------------------------------------------------------------

// all of these are data points that should trigger an exception
$failureDatasets = [
	[ null ],
	[ true ],
	[ false ],
	[ function() {} ],
	[ 0.0 ],
	[ 3.1415927 ],
	[ 0 ],
	[ 100 ],
	[ STDIN ],
	[ "hello, world" ],
];

/**
 * @ShouldFail
 */
SP::runStepWithDatasets($story, $failureDataSets, function(Story $story, $notAnArray) {
	// what are we doing?
	$log = Log::usingLog()->newStep("assertsArray() rejects " . gettype($notAnArray));

	// perform the test
	//
	// this should trigger an exception
	Asserts::assertsArray($failureData)->isArray();
});
