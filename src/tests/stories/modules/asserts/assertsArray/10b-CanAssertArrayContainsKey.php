<?php

use Storyplayer\SPv3\Modules\Asserts;
use Storyplayer\SPv3\Modules\Checkpoint;
use Storyplayer\SPv3\Modules\Failure;
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
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// ACTIONS
//
// ------------------------------------------------------------------------

SP::runStep($story, function(Story $story) {
	// what are we doing?
	$log = Log::usingLog()->startStep("prove than an array contains a key");

	// this should pass
	$testData = [
		"alpha"   => "a",
		"bravo"   => "b",
		"charlie" => "c",
		"delta"   => "d"
	];
	Asserts::assertsArray($testData)->hasKey("alpha");
});

SP::runStep($story, function(Story $story) {
	// what are we doing?
	$log = Log::usingLog()->startStep("throw exception when array does not contain a key");

	// this should fail
	Failure::expectsFailure(function() {
		Asserts::assertsArray([])->hasKey("alpha");		
	});
});
