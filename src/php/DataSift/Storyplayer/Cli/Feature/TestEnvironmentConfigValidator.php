<?php

/**
 * Copyright (c) 2011-present Mediasift Ltd
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
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace DataSift\Storyplayer\Cli;

use Phix_Project\ValidationLib4\Validator;
use Phix_Project\ValidationLib4\ValidationResult;

class Feature_TestEnvironmentConfigValidator implements Validator
{
    const MSG_FILENOTREADABLE = "test environment folder '%value%' exists, but contains Env.php script(s) that we cannot read; permissions problem?";
    const MSG_FOLDERNOTFOUND = "test environment folder '%value%' not found";
    const MSG_NOTAFOLDER = "test environment '%value%' not a folder";

    /**
     *
     * @param  mixed $value
     * @param  ValidationResult $result
     * @return ValidationResult
     */
    public function validate($value, ValidationResult $result = null)
    {
        if ($result === null) {
            $result = new ValidationResult($value);
        }

        // the test environment itself must be a folder, not a file
        if (!file_exists($value)) {
            $result->addError(static::MSG_FOLDERNOTFOUND);
            return $result;
        }
        if (!is_dir($value)) {
            $result->addError(static::MSG_NOTAFOLDER);
            return $result;
        }

        // these files are NOT required ...
        //
        // but if they are present, they need to be usable!
        foreach (['setupEnv.php', 'teardownEnv.php'] as $envFile) {
            $envFile = $value . '/' . $envFile;
            if (!file_exists($envFile)) {
                continue;
            }

            // can we read it?
            //
            // @TODO: fix how validator results are reported, so that
            // we can pass in whatever values we need
            if (!is_readable($envFile)) {
                $result->addError(static::MSG_FILENOTREADABLE);
                return $result;
            }
        }

        // if we get here, all is good
        return $result;
    }
}
