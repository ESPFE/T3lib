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

use TYPO3\CMS\Core\Resource\Exception\ResourceDoesNotExistException;
use TYPO3\CMS\Core\Imaging\ImageManipulation\CropVariantCollection;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Service\ImageService;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Exception;
use TYPO3\CMS\Extbase\Annotation\Inject;

class PictureViewHelper extends AbstractTagBasedViewHelper
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
	protected $widthXSMax = 768;
	
	/**
	 *
	 * @var integer
	 */
	protected $widthSMMax = 991;

	/**
	 *
	 * @var integer
	 */
	protected $widthMDMax = 1199;
	
	/**
	 *
	 * @var integer
	 */
	protected $maxImgWidth = 2500;
	
	/**
	 *
	 * @var array This array hold the calculated image width for XS, SM and MD
	 */
	protected $widthArr = [];
	
	/**
	 * Get Image Service Instance
	 *
	 * @var \TYPO3\CMS\Extbase\Service\ImageService
	 */
	protected ImageService $imageService;
        
    /**
     * Disable Output escapeing
     *
     * @var bool
     */
    protected $escapeOutput = false;

    public function __construct()
    {
        parent::__construct();
        $this->imageService = GeneralUtility::makeInstance(ImageService::class);
    }
	
	/**
	 * renderArguments
	 * 
	 * @return string
	 */
	protected function renderAgruments()
	{
		$content = '';
		
		$content .= (isset($this->arguments['class']))		? ' class="'		.	$this->arguments['class'] . '"'		: '';
		$content .= (isset($this->arguments['dir']))		? ' dir="'		.	$this->arguments['dir'] . '"'		: '';
		$content .= (isset($this->arguments['id']))		? ' id="'		.	$this->arguments['id'] . '"'		: '';
		$content .= (isset($this->arguments['lang']))		? ' lang="'		.	$this->arguments['lang'] . '"'		: '';
		$content .= (isset($this->arguments['style']))		? ' style="'		.	$this->arguments['style'] . '"'		: '';
		$content .= (isset($this->arguments['title']))		? ' title="'		.	$this->arguments['title'] . '"'		: '';
		$content .= (isset($this->arguments['accesskey']))	? ' accesskey="'	.	$this->arguments['accesskey'] . '"'	: '';
		$content .= (isset($this->arguments['tabindex']))	? ' tabindex="'		.	$this->arguments['tabindex'] . '"'	: '';
		$content .= (isset($this->arguments['onclick']))	? ' onclick="'		.	$this->arguments['onclick'] . '"'	: '';
		$content .= (isset($this->arguments['alt']))		? ' alt="'		.	$this->arguments['alt'] . '"'		: ' alt=""';
		$content .= (isset($this->arguments['ismap']))		? ' ismap="'		.	$this->arguments['ismap'] . '"'		: '';
		$content .= (isset($this->arguments['longdesc']))	? ' longdesc="'		.	$this->arguments['longdesc'] . '"'	: '';
		$content .= (isset($this->arguments['usemap']))		? ' usemap="'		.	$this->arguments['usemap'] . '"'	: '';
		
		return ltrim($content);
	}
	
	/**
	 * calcs image width for extra small, small and medium devices
	 * 
	 * @param integer $widthXS
	 * @param integer $widthSM
	 * @param integer $widthMD
	 * @param integer $widthLG
	 * 
	 * @return array
	 */
	protected function calcImageWidth($widthXS, $widthSM, $widthMD, $widthLG)
	{
		$this->widthArr['xs'] = ($widthXS / 100) * $this->widthXSMax;
		$this->widthArr['sm'] = ($widthSM / 100) * $this->widthSMMax;
		$this->widthArr['md'] = ($widthMD / 100) * $this->widthMDMax;
		$this->widthArr['lg'] = ($widthLG / 100) * $this->maxImgWidth;
		return $this->widthArr;
	}
	
	protected function processImage($image, $width)
	{
		if($image->getProperty('width') > $width)
		{
			$processingInstructions = [
				'width' => $width
			];
			$image = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
		}
		
		return $image;
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
            $this->registerArgument('crop', 'string|bool', 'overrule cropping of image (setting to FALSE disables the cropping set in FileReference)');
            $this->registerArgument('cropVariant', 'string', 'select a cropping variant, in case multiple croppings have been specified or stored in FileReference', false, 'default');
            $this->registerArgument('width', 'string', 'width of the image. This can be a numeric value representing the fixed width of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
            $this->registerArgument('height', 'string', 'height of the image. This can be a numeric value representing the fixed height of the image in pixels. But you can also perform simple calculations by adding "m" or "c" to the value. See imgResource.width for possible options.');
            $this->registerArgument('minWidth', 'int', 'minimum width of the image');
            $this->registerArgument('minHeight', 'int', 'minimum width of the image');
            $this->registerArgument('maxWidth', 'int', 'minimum width of the image');
            $this->registerArgument('maxHeight', 'int', 'minimum width of the image');
            $this->registerArgument('src', 'string', 'image path', true);
            $this->registerArgument('treatIdAsReference', 'bool', 'treat id as reference', false, false);
            $this->registerArgument('image', 'FileInterface|AbstractFileFolder', 'File Interface Object', false, null);
            $this->registerArgument('absolute', 'bool', 'src is absolute path', false, false);
            $this->registerArgument('widthXS', 'int', 'relative width for xs', false, 100);
            $this->registerArgument('widthSM', 'int', 'relative width for sm', false, 100);
            $this->registerArgument('widthMD', 'int', 'relative width for md', false, 100);
            $this->registerArgument('widthLG', 'int', 'relative width for lg', false, 100);
        }
	

	public function render()
	{
            $src = $this->arguments['src'];
            $treatIdAsReference = $this->arguments['treatIdAsReference'];
            $image = $this->arguments['image'];
            $absolute = $this->arguments['absolute'];
            $widthXS = $this->arguments['widthXS'];
            $widthSM = $this->arguments['widthSM'];
            $widthMD = $this->arguments['widthMD'];
            $widthLG = $this->arguments['widthLG'];
                    
            if (is_null($src) && is_null($image) || !is_null($src) && !is_null($image))
            {
                throw new \TYPO3\CMS\Fluid\Core\ViewHelper\Exception('You must either specify a string src or a File object.', 1479641029);
            }
		
            // get image from typo3 image service
            $image = $this->imageService->getImage($src, $image, $treatIdAsReference);
            $widthArr = $this->calcImageWidth($widthXS, $widthSM, $widthMD, $widthLG);
            
            $cropString = $this->arguments['crop'];
            if ($cropString === null && $image->hasProperty('crop') && $image->getProperty('crop')) {
                $cropString = $image->getProperty('crop');
            }
            $cropVariantCollection = CropVariantCollection::create((string)$cropString);
            $cropVariant = $this->arguments['cropVariant'] ?: 'default';
            $cropArea = $cropVariantCollection->getCropArea($cropVariant);
            
            $processingInstructions = [
                'width' => $widthArr['xs'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            $imageXS = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
            $processingInstructions = [
                'width' => $widthArr['sm'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            $imageSM = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
            $processingInstructions = [
                'width' => $widthArr['md'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            $imageMD = $this->imageService->applyProcessingInstructions($image, $processingInstructions);
            $processingInstructions = [
                'width' => $widthArr['lg'],
                'crop' => $cropArea->isEmpty() ? null : $cropArea->makeAbsoluteBasedOnFile($image),
            ];
            $imageDefault = $this->imageService->applyProcessingInstructions($image, $processingInstructions);

            $imageUriXS = $this->imageService->getImageUri($imageXS, $absolute);
            $imageUriSM = $this->imageService->getImageUri($imageSM, $absolute);
            $imageUriMD = $this->imageService->getImageUri($imageMD, $absolute);
            $imageUri = $this->imageService->getImageUri( $imageDefault, $absolute);

            $output = '<' . $this->tagName . '>';
            $output .= '<' . $this->innerTagName . ' srcset="' . $imageUriXS . '" media="(max-width: ' . $this->widthXSMax . 'px)" />';
            $output .= '<' . $this->innerTagName . ' srcset="' . $imageUriSM . '" media="(max-width: ' . $this->widthSMMax . 'px)" />';
            $output .= '<' . $this->innerTagName . ' srcset="' . $imageUriMD . '" media="(max-width: ' . $this->widthMDMax . 'px)" />';
            $output .= '<' . $this->innerTagName . ' srcset="' . $imageUri . '" />';
            $output .= '<' . $this->fallbackTagName . ' src="' . $imageUri . '" ' . $this->renderAgruments() . ' />';
            $output .= '</' . $this->tagName . '>';

            return $output;
	}
}