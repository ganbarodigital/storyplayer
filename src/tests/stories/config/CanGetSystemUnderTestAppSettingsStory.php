<?php

use Storyplayer\SPv3\Modules as SPv3;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can get system-under-test appSettings');

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
    $appSettings = SPv3\Storyplayer::fromSystemUnderTest()->getAppSettings('testData');

    SPv3\Asserts::assertsObject($appSettings)->isNotNull();
    SPv3\Asserts::assertsObject($appSettings)->hasAttribute('name');
    SPv3\Asserts::assertsObject($appSettings)->hasAttribute('version');
    SPv3\Asserts::assertsObject($appSettings)->hasAttribute('isAppSettings');
});