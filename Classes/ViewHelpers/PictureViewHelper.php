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

namespace \ESP\T3lib\ViewHelpers;

use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Extbase\Domain\Model\AbstractFileFolder;

class PictureViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper
{
	/**
	 *
	 * @var string
	 */
	protected $tagName = 'picture';
	
	/**
	 *
	 * @var string
	 */
	protected $innerTagName = 'source';
	
	/**
	 *
	 * @var string
	 */
	protected $fallbackTagName = 'img';
	
	/**
	 *
	 * @var integer
	 */
	protected $widthXSMin = 0;
	
	/**
	 *
	 * @var integer
	 */
	protected $widthXSMax = 768;
	
	/**
	 *
	 * @var integer
	 */
	protected $widthSMMin = 769;
	
	/**
	 *
	 * @var integer
	 */
	protected $widthSMMax = 991;
	
	/**
	 *
	 * @var integer
	 */
	protected $widthMDMin = 992;
	
	/**
	 *
	 * @var integer
	 */
	protected $widthMDMax = 1199;
	
	/**
	 *
	 * @var integer
	 */
	protected $widthLGMin = 1200;
	
	/**
	 *
	 * @var array This array hold the calculated image width for XS, SM and MD
	 */
	protected $widthArr = [];
	
	/**
	 * Get Image Service Instance
	 *
	 * @var \TYPO3\CMS\Extbase\Service\ImageService
	 * @inject
	 */
	protected $imageService;
	
	/**
	 * renderArguments
	 * 
	 * @return string
	 */
	protected function renderAgruments()
	{
		$content = '';
		foreach($this->arguments as $arg => $val)
		{
			$content .= ' ' . $arg . '="' . $val . '"';
		}
		return $content;
	}
	
	/**
	 * calcs image width for extra small, small and medium devices
	 * 
	 * @param integer $widthXS
	 * @param integer $widthSM
	 * @param integer $widthMD
	 * @return array
	 */
	protected function calcImageWidth($widthXS, $widthSM, $widthMD)
	{
		$this->widthArr['xs'] = ($this->widthXSMax / $widthXS) * 100;
		$this->widthArr['sm'] = ($this->widthSMMax / $widthSM) * 100;
		$this->widthArr['md'] = ($this->widthMDMax / $widthMD) * 100;
		return $this->widthArr;
	}
	
	protected function processImage($image, $width)
	{
		$processingInstructions = [
			'max-width' => $width
		];
		return $this->imageService->applyProcessingInstructions($iamge, $processingInstructions);
	}

	/**
	 * initializeArguments
	 * 
	 * @return void
	 */
	public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('class', 'string', 'CSS class(es) for this element');
        $this->registerArgument('dir', 'string', 'Text direction for this HTML element. Allowed strings: "ltr" (left to right), "rtl" (right to left)');
        $this->registerArgument('id', 'string', 'Unique (in this file) identifier for this HTML element.');
        $this->registerArgument('lang', 'string', 'Language for this element. Use short names specified in RFC 1766');
        $this->registerArgument('style', 'string', 'Individual CSS styles for this element');
        $this->registerArgument('title', 'string', 'Tooltip text of element');
        $this->registerArgument('accesskey', 'string', 'Keyboard shortcut to access this element');
        $this->registerArgument('tabindex', 'integer', 'Specifies the tab order of this element');
        $this->registerArgument('onclick', 'string', 'JavaScript evaluated for the onclick event');
        $this->registerArgument('alt', 'string', 'Specifies an alternate text for an image', false);
        $this->registerArgument('ismap', 'string', 'Specifies an image as a server-side image-map. Rarely used. Look at usemap instead', false);
        $this->registerArgument('longdesc', 'string', 'Specifies the URL to a document that contains a long description of an image', false);
        $this->registerArgument('usemap', 'string', 'Specifies an image as a client-side image-map', false);
    }
	
	/**
	 * render
	 * 
	 * @param string $src
	 * @param bool $treatIdAsReference
	 * @param FileInterface|AbstractFileFolder $image
	 * @param bool $absolute
	 * @param integer $widthXS
	 * @param integer $widthSM
	 * @param integer $widthMD
	 * 
	 * @return string
	 */
	public function render(
			$src = null,
			$treatIdAsReference = false,
			$image = null,
			$absolute = false,
			$widthXS = 100,
			$widthSM = 100,
			$widthMD = 100
	)
	{
		if (is_null($src) && is_null($image) || !is_null($src) && !is_null($image))
		{
            throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('You must either specify a string src or a File object.', 1479641029);
        }
		
		// get image from typo3 image service
		$image = $this->imageService->getImage($src, $image, $treatIdAsReference);
		$widthArr = $this->calcImageWidth($widthXS, $widthSM, $widthMD);
		
		$imageXS = $this->processImage($image, $widthArr['xs']);
		$imageSM = $this->processImage($image, $widthArr['sm']);
		$imageMD = $this->processImage($image, $widthArr['md']);
		
		$imageUriXS = $this->imageService->getImageUri($imageXS, $absolute);
		$imageUriSM = $this->imageService->getImageUri($imageSM, $absolute);
		$imageUriMD = $this->imageService->getImageUri($imageMD, $absolute);
		$imageUri = $this->imageService->getImageUri( $image, $absolute);
		
		$arguments = $this->renderAgruments();
		$output = '<' . $this->tagName . $arguments . '>';
		$output .= '<' . $this->innerTagName . 'srcset="' . $imageUriXS . '" media="(min-width: "' . $this->widthXSMin . 'px) and (max-width: ' . $this->widthXSMax . 'px)" />';
		$output .= '<' . $this->innerTagName . 'srcset="' . $imageUriSM . '" media="(min-width: "' . $this->widthSMMin . 'px) and (max-width: ' . $this->widthSMMax . 'px)" />';
		$output .= '<' . $this->innerTagName . 'srcset="' . $imageUriMD . '" media="(min-width: "' . $this->widthMDMin . 'px) and (max-width: ' . $this->widthMDMax . 'px)" />';
		$output .= '<' . $this->innerTagName . 'srcset="' . $imageUri . '" media="(min-width: "' . $this->widthLGMin . 'px)" />';
		$output .= '<' . $this->fallbackTagName . 'src="' . $imageUri . '" alt="" />';
		$output .= '</' . $this->tagName . '>';
		
		return $output;
	}
}