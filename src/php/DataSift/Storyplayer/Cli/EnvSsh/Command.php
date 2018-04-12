<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
 * Copyright (c) 2015-present Ganbaro Digital Ltd
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the names of the copyright holders nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Exception;
use Phix_Project\CliEngine;
use Phix_Project\CliEngine\CliCommand;
use Phix_Project\ExceptionsLib1\Legacy_ErrorHandler;
use Phix_Project\ExceptionsLib1\Legacy_ErrorException;
use DataSift\Storyplayer\Injectables;
use DataSift\Storyplayer\PlayerLib\StoryTeller;

/**
 * A command to SSH into a host in a test environment
 *
 * @category  Libraries
 * @package   Storyplayer/Cli
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */
class EnvSsh_Command extends BaseCommand implements CliSignalHandler
{
    // we need to track this for handling CTRL-C
    protected $st;

    // we track this for convenience
    protected $output;

    // our injected data / services
    // needed for when user presses CTRL+C
    protected $injectables;

    public function __construct($injectables)
    {
        // call our parent
        parent::__construct($injectables);

        // define the command
        $this->setName('env-ssh');
        $this->setShortDescription('SSH into a test environment host');
        $this->setLongDescription(
            "Use this command to SSH into a test environment host."
            .PHP_EOL
        );
        $this->setArgsList(array(
            "[<hostId>]" => "host to SSH into"
        ));

        // add in the features that this command relies on
        $this->addFeature(new Feature_ConsoleSupport);
        $this->addFeature(new Feature_ColorSupport);
        $this->addFeature(new Feature_TestEnvironmentConfigSupport);
//        $this->addFeature(new Feature_SystemUnderTestConfigSupport);
        $this->addFeature(new Feature_LocalhostSupport);
        $this->addFeature(new Feature_ActiveConfigSupport);

        // now setup all of the switches that we support
        $this->addFeatureSwitches();
    }

    /**
     *
     * @param  CliEngine $engine
     * @param  array     $params
     * @param  Injectables|null $injectables
     * @return integer
     */
    public function processCommand(CliEngine $engine, $params = array(), $injectables = null)
    {
        // we need to wrap our code to catch old-style PHP errors
        $legacyHandler = new Legacy_ErrorHandler();

        // run our code
        try {
            $returnCode = $legacyHandler->run([$this, 'processInsideLegacyHandler'], [$engine, $params, $injectables]);
            return $returnCode;
        }
        catch (Exception $e) {
            $injectables->output->logCliError($e->getMessage());
            $engine->options->dev = true;
            if (isset($engine->options->dev) && $engine->options->dev) {
                $injectables->output->logCliError("Stack trace is:\n\n" . $e->getTraceAsString());
            }

            // stop the browser if available
            if (isset($this->st)) {
                $this->st->stopDevice();
            }

            // tell the calling process that things did not end well
            exit(1);
        }
    }

    public function processInsideLegacyHandler(CliEngine $engine, $params = array(), $injectables = null)
    {
        // process the common functionality
        $this->initFeaturesBeforeModulesAvailable($engine);

        // now it is safe to create our shorthand
        $runtimeConfig        = $injectables->getRuntimeConfig();
        $runtimeConfigManager = $injectables->getRuntimeConfigManager();
        $output               = $injectables->output;
        $runningEnvs          = $injectables->runningTestEnvironmentsList;

        // save the output for use in other methods
        $this->output = $output;

        // at this point, all of the services / data held in $injectables
        // has been initialised and is ready for use
        //
        // what's left is the stuff that needs initialising in phases
        // or $st

        // create a new StoryTeller object
        $st = new StoryTeller($injectables);

        // remember our $st object, as we'll need it for our
        // shutdown function
        $this->st = $st;

        // now that we have $st, we can initialise any feature that
        // wants to use our modules
        $this->initFeaturesAfterModulesAvailable($st, $engine, $injectables);

        // install signal handling, now that $this->st is defined
        //
        // we wouldn't want signal handling called out of order :)
        $this->initSignalHandling($injectables);

        // now, we need to make sure that the chosen test environment is
        // actually running
        $testEnvName = $runtimeConfig->getName();
        var_dump($testEnvName);
        if (!$runningEnvs->hasEntry($testEnvName)) {
            $output->logCliError("test environment '{$testEnvName}' is not running");
            exit(1);
        }

        // all done
        return 0;
    }

    // ==================================================================
    //
    // the individual initX() methods
    //
    // these are processed *after* the objects defined in the
    // CommonFunctionalitySupport trait have been initialised
    //
    // ------------------------------------------------------------------

    /**
     *
     * @param  Injectables $injectables
     * @return void
     */
    protected function initSignalHandling(Injectables $injectables)
    {
        // we need to remember the injectables, for when we handle CTRL+C
        $this->injectables = $injectables;

        // setup signal handling
        pcntl_signal(SIGTERM, array($this, 'sigtermHandler'));
        pcntl_signal(SIGINT , array($this, 'sigtermHandler'));
    }

    // ==================================================================
    //
    // SIGNAL handling
    //
    // ------------------------------------------------------------------

    /**
     *
     * @param  integer $signo
     * @return void
     */
    public function sigtermHandler($signo)
    {
        // tell the user what is happening
        echo PHP_EOL;
        echo "============================================================" . PHP_EOL;
        echo "USER ABORT!!" . PHP_EOL;

        echo " done" . PHP_EOL . "============================================================" . PHP_EOL . PHP_EOL;

        // force a clean shutdown
        exit(1);
    }
}
