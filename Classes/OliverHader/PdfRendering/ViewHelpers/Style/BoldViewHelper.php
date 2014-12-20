<?php
namespace OliverHader\PdfRendering\ViewHelpers\Style;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;
use OliverHader\PdfRendering\ViewHelpers\AbstractDocumentViewHelper;

/**
 * Class TotalViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class BoldViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @return string
	 */
	public function render() {
		if (!$this->hasPage()) {
			return NULL;
		}

		$currentFont = $this->getVariable('currentFont');
		$currentFontSize = $this->getVariable('currentFontSize');

		if ($currentFont->isBold()) {
			return $this->renderChildren();
		}

		$currentFontName = $this->getFontName($currentFont);
		$currentFontParts = explode('-', $currentFontName, 2);
		if (isset($currentFontParts[1])) {
			$currentFontParts[1] = 'Bold' . $currentFontParts[1];
		} else {
			$currentFontParts[1] = 'Bold';
		}

		$fontName = implode('-', $currentFontParts);
		$font = \ZendPdf\Font::fontWithName($fontName);
		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setFont($font)->setFontSize($currentFontSize);

		$this->setVariable('currentFont', $font, TRUE);
		$content = $this->wrap($this->renderChildren(), $identifier);
		$this->setVariable('currentFont', $currentFont, TRUE);

		return $content;
	}

}