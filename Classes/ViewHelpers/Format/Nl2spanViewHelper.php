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

use TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\CMS\Fluid\Core\ViewHelper\Facets\CompilableInterface;

/**
 * converts every linebreak in a span
 *
 * = Examples =
 *
 * <code title="Example">
 * <esp:format.nl2span>{text_with_linebreaks}</esp:format.nl2span>
 * </code>
 * <output>
 * <span class="1">text with line</span><span class="2"> breaks replaced by</span>
 * </output>
 *
 * <code title="Inline notation">
 * {text_with_linebreaks -> esp:format.nl2span()}
 * </code>
 * <output>
 * <span class="1">text with line</span><span class="2"> breaks replaced by</span>
 * </output>
 */
class Nl2spanViewHelper extends AbstractViewHelper implements CompilableInterface
{
    /**
    * Disable Output escapeing
    * 
    * @var bool
    */
   protected $escapeOutput = false;
    
    /**
     * converts every linebreak in a span
     *
     * @param string $value string to format
     * @return string the altered string.
     * @api
     */
    public function render($value = null)
    {
        return static::renderStatic(
            [
                'value' => $value
            ],
            $this->buildRenderChildrenClosure(),
            $this->renderingContext
        );
    }

    /**
     * Applies nl2br() on the specified value.
	 * converts every line break into a <span>
     *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param \TYPO3\CMS\Fluid\Core\Rendering\RenderingContextInterface $renderingContext
     * @return string
     */
    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $value = $arguments['value'];
        if ($value === null) {
            $value = $renderChildrenClosure();
        }

        $value = nl2br($value);
		
		$valueArr = explode('<br />', $value);
		for($i = 0; $i < count($valueArr); $i++)
		{
			$valueArr[$i] = '<span class="span-' . $i . '">' . $valueArr[$i] . '</span>';
		}
		return implode('', $valueArr);
    }
}
