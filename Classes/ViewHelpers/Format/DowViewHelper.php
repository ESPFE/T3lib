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

namespace ESP\T3lib\ViewHelpers\Format;

use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Returns the day of week of a given datetime object
 * 0 => Sunday
 * 1 => Monday
 * ...
 * 6 => Saturday
 *
 * = Examples =
 *
 * <code title="Example">
 * <esp:format.dow dateTime="{dateTime.Object}" />
 * </code>
 * <output>
 * 1
 * </output>
 */
class DowViewHelper extends AbstractViewHelper
{
    /**
     * Returns the day of week of a given datetime object
     *
     * @param \DateTime $dateTime
     * @return int
     */
    public function render(\DateTime $dateTime = null)
    {
        return date('w', $dateTime->getTimestamp());
    }
}
