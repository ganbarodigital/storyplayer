<?php

use Storyplayer\SPv3\Modules as SPv3;

// ========================================================================
//
// STORY DETAILS
//
// ------------------------------------------------------------------------

$story = newStoryFor('Storyplayer')
         ->inGroup('Config')
         ->called('Can merge system-under-test params into test environment config');

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
    $expected = SPv3\Storyplayer::fromConfig()->get("systemundertest.roles.0.params.filename");
    $actual   = SPv3\Storyplayer::fromConfig()->get("target.groups.0.details.machines.default.params.filename");

    SPv3\Asserts::assertsString($actual)->equals($expected);
});