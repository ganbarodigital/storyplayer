<?php

use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Modules\Vagrant;
use Storyplayer\SPv3\TestEnvironments\TestEnvironment;
use Storyplayer\SPv3\TestEnvironments\TestEnvironmentPlayer as TEP;

// ========================================================================
//
// TEST ENVIRONMENT DETAILS
//
// ------------------------------------------------------------------------

$env = Storyplayer::getCurrentTestEnvironment();

// ========================================================================
//
// TEST ENVIRONMENT TEARDOWN
//
// Keep your steps small. This makes it easier to debug and maintain your
// test environment cleanup.
//
// ------------------------------------------------------------------------

TEP::runStep($env, function(TestEnvironment $env) {
    // what are we doing?
    $log = Log::usingLog()->startAction("shutting down the Vagrant VM(s)");

    // undo anything that you did in addTestEnvironmentSetup()
    Vagrant::usingVagrantFile(__DIR__ . '/Vagrantfile')->destroyAllVms();
});
