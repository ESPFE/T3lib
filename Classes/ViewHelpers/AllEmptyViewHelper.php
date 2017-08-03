<?php

/* 
 * Copyright (C) 2017 André Sünnemann <a.suennemann@edv-peuker.de>
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

/**
 * renders then, when all array entries are an empty string
 *
 * = Examples =
 *
 * <code title="Example">
 * <esp:allEmpty fields="{0: field.headlineL, 1: field.linkL, 2: field.headlineC, 3: field.linkC, 4: field.headlineR, 5: field.linkR}">
        <f:then>Do something</f:then>
        <f:else>Do something</f:else>
 * </esp:allEmpty>
 * </code>
 * <output>
 *  {}
 * </output>
 */
class AllEmptyViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper
{
    /**
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('fields', 'array', 'fields to be testet');
    }
    
    public function render()
    {
        $array = $this->arguments['fields'];
        foreach($array as $key => $value)
        {
            if(strlen((string)$value) > 0)
            {
                return $this->renderElseChild();
            }
        }
        return $this->renderThenChild();
    }
}