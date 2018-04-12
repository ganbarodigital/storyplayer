<?php

use Storyplayer\SPv3\Modules as SPv3;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Stories > Checkpoint')
         ->called('Each story starts with empty checkpoint (pt 1)');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// TEST SETUP / TEARDOWN
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
	// do nothing
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = SPv3\Checkpoint::getCheckpoint();
	$checkpoint->thisDataShouldDisappearInPt2 = true;
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function($st) {
	$checkpoint = SPv3\Checkpoint::getCheckpoint();

	SPv3\Asserts::assertsObject($checkpoint)->hasAttribute('thisDataShouldDisappearInPt2');
	SPv3\Asserts::assertsBoolean($checkpoint->thisDataShouldDisappearInPt2)->isTrue();
});