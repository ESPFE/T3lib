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
 * Returns a serialized object
 * The object must implement the \JsonSerializable interface
 *
 * = Examples =
 *
 * <code title="Example">
 * <esp:serialize object="{object}" />
 * </code>
 * <output>
 *  {}
 * </output>
 */
class SerializeViewHelper extends AbstractViewHelper
{
    /**
     * Disable Output escapeing
     * 
     * @var bool
     */
     protected $escapeOutput = false;
        
    /**
     * Returns the day of week of a given datetime object
     *
     * @param mixed text
     * @return string
     */
    public function render($object = null)
    {
        return $object->jsonSerialize();
    }
}
