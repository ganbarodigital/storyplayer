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
 * @package   Storyplayer
 * @author    Stuart Herbert <stuart.herbert@datasift.com>
 * @copyright 2011-present Mediasift Ltd www.datasift.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://datasift.github.io/storyplayer
 */

namespace Storyplayer\SPv3\Stories;

class BuildStory
{
    /**
     * Create a new story object
     *
     * @param  string $category the category that the story belongs to
     * @return Story            the new story object to use
     */
    public static function newStory()
    {
        // our return value
        $story = new Story();

        // our output reports may need to know which file the story itself
        // is defined in
        $story->determineStoryFilename();

        // calculate these from the filesystem path
        // $filenameParts = static::getPartsFromFilename($story->getStoryFilename());
        // if (!empty($filenameParts)) {
        //     $story->setCategory(static::determineCategory($filenameParts));
        //     $story->inGroup(static::determineGroup($filenameParts));
        //     $story->called(static::determineCalled($filenameParts));
        // }

        // we know that this story requires Storyplayer v2
        $story->requiresStoryplayerVersion(2);

        // all done
        return $story;
    }

    private function determineCategory($filenameParts)
    {
        return $filenameParts[0];
    }

    private function determineGroup($filenameParts)
    {
        $group = array_slice($filenameParts, 1, -1);
        return $group;
    }

    private function determineCalled($filenameParts)
    {
        return end($filenameParts);
    }

    private function getPartsFromFilename($filename)
    {
        // let's try our best to make this from the root of the project
        $cwd = getcwd() . DIRECTORY_SEPARATOR;
        $filename = str_replace($cwd, '', $filename);

        // now, let's look at what we have
        $parts = explode(DIRECTORY_SEPARATOR, $filename);
        while (!empty($parts)) {
            $part = array_shift($parts);
            if ($part === 'stories') {
                return $parts;
            }
        }

        return [];
    }
}