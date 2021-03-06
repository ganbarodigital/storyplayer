<?php

use Storyplayer\SPv3\Modules\Asserts;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Stories > Groups')
         ->called('Can use a string for groups');

$story->requiresStoryplayerVersion(2);

// ========================================================================
//
// STORY SETUP / TEAR-DOWN
//
// ------------------------------------------------------------------------

// ========================================================================
//
// POSSIBLE ACTION(S)
//
// ------------------------------------------------------------------------

$story->addAction(function() {
	// nothing to do
});

// ========================================================================
//
// POST-TEST INSPECTION
//
// ------------------------------------------------------------------------

$story->addPostTestInspection(function() use($story) {
	$groups = $story->getGroup();

	Asserts::assertsArray($groups)->isArray();
	Asserts::assertsArray($groups)->equals(['Stories', 'Groups']);
});