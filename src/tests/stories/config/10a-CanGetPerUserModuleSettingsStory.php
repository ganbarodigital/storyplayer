<?php

use Storyplayer\SPv3\Modules as SPv3;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can get per-user module settings');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// CHECK FOR REQUIRED TEST DATA
//
// ------------------------------------------------------------------------

$story->addTestCanRunCheck(function() {
	// do we have a user dotfile installed?
	if (!file_exists(getenv("HOME") . '/.storyplayer/storyplayer.json')) {
		return false;
	}
});

// ========================================================================
//
// TEST SETUP
//
// ------------------------------------------------------------------------

$story->addTestSetup(function() {
	$checkpoint = SPv3\Checkpoint::getCheckpoint();
	$checkpoint->expectedData = "fred";
});

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	$checkpoint = SPv3\Checkpoint::getCheckpoint();
	$checkpoint->actualData = SPv3\Config::fromConfig()->getModuleSetting("per-user.data1.value1");
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() {
	$checkpoint = SPv3\Checkpoint::getCheckpoint();
	SPv3\Asserts::assertsObject($checkpoint)->hasAttribute("expectedData");
	SPv3\Asserts::assertsObject($checkpoint)->hasAttribute("actualData");
	SPv3\Asserts::assertsString($checkpoint->actualData)->equals($checkpoint->expectedData);
});