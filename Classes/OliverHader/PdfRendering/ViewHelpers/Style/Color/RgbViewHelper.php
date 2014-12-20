<?php
namespace OliverHader\PdfRendering\ViewHelpers\Style\Color;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;
use OliverHader\PdfRendering\ViewHelpers\AbstractDocumentViewHelper;

/**
 * Class TotalViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class RgbViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param float $r
	 * @param float $g
	 * @param float $b
	 * @return string
	 */
	public function render($r, $g, $b) {
		if (!$this->hasPage()) {
			return '';
		}

		/** @var \ZendPdf\Color\Rgb $currentColor */
		$currentColor = $this->getVariable('currentColor');

		if (implode(',', $currentColor->getComponents()) === implode(',', array($r, $g, $b))) {
			return $this->renderChildren();
		}

		$color = new \ZendPdf\Color\Rgb($r, $g, $b);
		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setColor($color);

		$this->setVariable('currentColor', $color, TRUE);
		$content = $this->wrap($this->renderChildren(), $identifier);
		$this->setVariable('currentColor', $currentColor, TRUE);

		return $content;
	}

}