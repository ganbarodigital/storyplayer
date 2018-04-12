<?php

use Storyplayer\SPv3\Modules\Log;
use Storyplayer\SPv3\Modules\Vagrant;
use Storyplayer\SPv3\TestEnvironments\BuildTestEnvironment;
use Storyplayer\SPv3\TestEnvironments\TestEnvironment;
use Storyplayer\SPv3\TestEnvironments\TestEnvironmentPlayer as TEP;
use Storyplayer\SPv3\TestEnvironments\Templates\MinimalLocalhost;
use Storyplayer\SPv3\TestEnvironments\Templates\VagrantMachine;
use StoryplayerInternals\SPv3\SelfTest\SelfTestModuleSettings;
use StoryplayerInternals\SPv3\SelfTest\SelfTestRoles;
use StoryplayerInternals\SPv3\SelfTest\SelfTestStorySettings;

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

TEP::runStep($env, MinimalLocalhost::registerHost);
TEP::runStep($env, SelfTestModuleSettings::applyConfig);
TEP::runStep($env, SelfTestStorySettings::applyConfig);

TEP::runStep($env, function(TestEnvironment $env) {
    // what are we doing?
    $log = Log::usingLog()->startAction("starting up the Vagrant VM");

    // start up our single Vagrant VM
    Vagrant::usingVagrantFile(__DIR__ . '/Vagrantfile')->startVm('default');
});

// ========================================================================
//
// TEST ENVIRONMENT INVENTORY
//
// Tell SPv3 about the machine(s) in your test environment.
//
// You only need to add the machines that your stories will interact with.
//
// ------------------------------------------------------------------------

TEP::runStep($env, function(TestEnvironment $env) {
    // what are we doing?
    $log = Log::usingLog()->startAction("registering Vagrant VM(s) into inventory");

    // register our default VM
    $hostDetails = Vagrant::fromVagrantFile(__DIR__ . '/Vagrantfile')->getHostDetails('default');
    $env->addHost('default', $hostDetails);
});

TEP::runStep($env, SelfTestRoles::applyRoles);
