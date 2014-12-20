<?php
namespace OliverHader\PdfRendering\ViewHelpers;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class DocumentViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class DocumentViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param string $fileName
	 * @param string $defaultFont
	 * @param string $defaultFontSize
	 * @param array $defaultColor
	 * @param float $defaultCharacterSpacing
	 */
	public function render($fileName, $defaultFont = NULL, $defaultFontSize = NULL, array $defaultColor = NULL, $defaultCharacterSpacing = 0.0) {
		if (!file_exists($fileName)) {
			return;
		}

		$this->initializeDefaultFont($defaultFont);
		$this->initializeDefaultFontSize($defaultFontSize);
		$this->initializeDefaultColor($defaultColor);
		$this->initializeDefaultStyle($defaultCharacterSpacing);

		$document = \ZendPdf\PdfDocument::load($fileName);

		$this->setDocument($document);
		$this->renderChildren();
		$this->unsetDocument();
		$this->unsetDefaults();

		$content = $document->render();
		$this->getResponse()->setContent($content);
	}

	/**
	 * @return NULL|\TYPO3\Flow\Mvc\Response
	 */
	protected function getResponse() {
		$response = NULL;
		if ($this->templateVariableContainer->exists('response')) {
			$response = $this->templateVariableContainer->get('response');
		}
		return $response;
	}

	protected function initializeDefaultFont($defaultFont = NULL) {
		if ($defaultFont === NULL) {
			$defaultFont = \ZendPdf\Font::FONT_HELVETICA;
		}

		$font = \ZendPdf\Font::fontWithName($defaultFont);
		$this->setVariable('defaultFont', $font);
		$this->setVariable('currentFont', $font, TRUE);
	}

	protected function initializeDefaultFontSize($defaultFontSize = NULL) {
		if ($defaultFontSize === NULL) {
			$defaultFontSize = 10;
		}

		$this->setVariable('defaultFontSize', $defaultFontSize);
		$this->setVariable('currentFontSize', $defaultFontSize, TRUE);
	}

	protected function initializeDefaultColor(array $defaultColor = NULL) {
		if ($defaultColor === NULL) {
			$defaultColor = array(
				'r' => 0,
				'g' => 0,
				'b' => 0,
			);
		}

		$color = new \ZendPdf\Color\Rgb(
			$defaultColor['r'],
			$defaultColor['g'],
			$defaultColor['b']
		);

		$this->setVariable('defaultColor', $color);
		$this->setVariable('currentColor', $color, TRUE);
	}

	/**
	 * @param float $defaultCharacterSpacing
	 */
	protected function initializeDefaultStyle($defaultCharacterSpacing = 0.0) {
		$this->setVariable('defaultCharacterSpacing', $defaultCharacterSpacing);
		$this->setVariable('currentCharacterSpacing', $defaultCharacterSpacing);
	}

	protected function unsetDefaults() {
		$this->unsetVariable('defaultFont');
		$this->unsetVariable('currentFont');
		$this->unsetVariable('defaultFontSize');
		$this->unsetVariable('currentFontSize');
		$this->unsetVariable('defaultColor');
		$this->unsetVariable('currentColor');
		$this->unsetVariable('defaultCharacterSpacing');
		$this->unsetVariable('currentCharacterSpacing');
	}

}