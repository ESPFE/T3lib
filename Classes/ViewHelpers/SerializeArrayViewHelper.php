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
 * Returns a serialized array
 *
 * = Examples =
 *
 * <code title="Example">
 * <esp:serializeArray array="{array}" />
 * </code>
 * <output>
 *  {}
 * </output>
 */
class SerializeArrayViewHelper extends AbstractViewHelper
{
    /**
     * Disable Output escaping
     * 
     * @var bool
     */
     protected $escapeOutput = false;
        
    /**
     * Returns a serialized array
     *
     * @param mixed text
     * @return string
     */
    public function render($array = null)
    {
        $cnt = count($array);
        $i = 0;
        $json = '{';
        foreach($array as $key => $val)
        {
            $json .= '"' . $key . '" : "' . $val . '"';
            if($i < $cnt - 1) { $json .= ', '; }
            $i += 1;
            
        }
        $json .= '}';
        return $json;
    }
}
