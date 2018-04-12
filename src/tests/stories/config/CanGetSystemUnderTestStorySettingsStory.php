<?php

use Storyplayer\SPv3\Modules as SPv3;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can get system-under-test storySettings');

$story->requiresStoryplayerVersion(2);

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

$story->addPostTestInspection(function() {
    $storySettings = SPv3\Storyplayer::fromSystemUnderTest()->getStorySetting('testData');

    SPv3\Asserts::assertsObject($storySettings)->isNotNull();
    SPv3\Asserts::assertsObject($storySettings)->hasAttribute('name');
    SPv3\Asserts::assertsObject($storySettings)->hasAttribute('version');
    SPv3\Asserts::assertsObject($storySettings)->hasAttribute('isStorySettings');
});