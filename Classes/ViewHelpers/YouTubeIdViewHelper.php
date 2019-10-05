<?php

/* 
 * Copyright (C) 2016 asuennemann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ESP\T3lib\ViewHelpers;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Extracts the YouTube VideoId from an url
 * https://www.youtube.com/watch?v=x5gJ8pisvrU  =>  x5gJ8pisvrU

 * = Examples =
 *
 * <code title="Example">
 * <esp:youTubeId url=="{text}" />
 * </code>
 * <output>
 * x5gJ8pisvrU
 * </output>
 */
class YouTubeIdViewHelper extends AbstractViewHelper
{
    /**
     * Extracts the YouTube VideoId from an url
     *
     * @param string $url
     * @return string
     */
    public function render(string $url = null)
    {
        $needle = 'v=';
        $start = strpos($url, $needle);
        $start += strlen($needle);
        error_log('START: ' . $start);
        $id = substr($url, $start);
        error_log('ID: ' . $id);
        return $id;
    }
}
