<?php

// This is the test environment setup script that we load *if*
// the end-user doesn't use the -t/--target switch to load their own
// test environment script.

use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\TestEnvironments\BuildTestEnvironment;
use Storyplayer\SPv3\TestEnvironments\TestEnvironmentPlayer as TEP;
use Storyplayer\SPv3\TestEnvironments\HostTypes\Localhost;

// ========================================================================
//
// TEST ENVIRONMENT DETAILS
//
// ------------------------------------------------------------------------

$env = BuildTestEnvironment::newTestEnvironment();

// ========================================================================
//
// TEST ENVIRONMENT SETUP
//
// Add one function per step. This makes it easier to debug and maintain
// your test environment construction.
//
// ------------------------------------------------------------------------

TEP::runStep($env, function(TestEnvironment $env) {
    // what are we doing?
    $log = Log::usingLog()->startAction("setting up 'localhost'");

    // here, we are simply setting up a default 'localhost'
    $localhost = new Localhost();
    $localhost->addRoles(['*']);
    $env->addHost($localhost);

    // all done
    $log->endAction();
});
