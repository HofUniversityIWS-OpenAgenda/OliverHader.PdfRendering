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
class CharacterSpacingViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param float $value
	 * @return string
	 */
	public function render($value) {
		if (!$this->hasPage()) {
			return '';
		}

		$currentCharacterSpacing = $this->getVariable('currentCharacterSpacing');

		if ($currentCharacterSpacing === $value) {
			return $this->renderChildren();
		}

		$identifier = uniqid('instruction', TRUE);
		$this->createInstruction($identifier)->setCharacterSpacing($value);

		$this->setVariable('currentCharacterSpacing', $value, TRUE);
		$content = $this->wrap($this->renderChildren(), $identifier);
		$this->setVariable('currentCharacterSpacing', $currentCharacterSpacing, TRUE);

		return $content;
	}

}