<?php
namespace OliverHader\PdfRendering\ViewHelpers\Style;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;
use OliverHader\PdfRendering\ViewHelpers\AbstractDocumentViewHelper;

/**
 * Class FontViewHelper.
 *
 * Creates instruction for a different font and font size.
 *
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class FontViewHelper extends AbstractDocumentViewHelper {

	/**
	 * Renders (= creates instruction) for a different font and font size.
	 *
	 * @param string $fontName The name of the font
	 * @param float $fontSize THe font size
	 * @return string The wrapped instruction identifier
	 */
	public function render($fontName = NULL, $fontSize = NULL) {
		if (!$this->hasPage()) {
			return NULL;
		}

		if ($fontName === NULL && $fontSize === NULL) {
			return $this->renderChildren();

		}

		$currentFont = $this->getVariable('currentFont');
		$currentFontName = $this->getFontName($currentFont);
		$currentFontSize = $this->getVariable('currentFontSize');

		if (($fontName === NULL || $currentFontName === $fontName) && ($fontSize === NULL || (int)$currentFontSize === (int)$fontSize)) {
			return $this->renderChildren();
		}

		if ($fontName !== NULL) {
			$font = \ZendPdf\Font::fontWithName($fontName);
			$this->setVariable('currentFont', $font, TRUE);
		} else {
			$font = $currentFont;
		}
		if ($fontSize !== NULL) {
			$this->setVariable('currentFontSize', $fontSize, TRUE);
		} else {
			$fontSize = $currentFontSize;
		}

		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setFont($font)->setFontSize($fontSize);
		$content = $this->wrap($this->renderChildren(), $identifier);

		if ($fontName !== NULL) {
			$this->setVariable('currentFont', $currentFont, TRUE);
		}
		if ($fontSize !== NULL) {
			$this->setVariable('currentFontSize', $currentFontSize, TRUE);
		}

		return $content;
	}

}