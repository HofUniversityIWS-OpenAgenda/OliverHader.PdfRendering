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
	 * Renders a PDF document
	 *
	 * @param string $fileName The file name of the source PDF document template
	 * @param string $defaultFont The name of the default PDF font
	 * @param float $defaultFontSize The size of the default PDF font
	 * @param array $defaultColor The default RGB color values
	 * @param float $defaultCharacterSpacing The default additional character spacing
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
	 * Gets the response object using in the view.
	 *
	 * @return NULL|\TYPO3\Flow\Mvc\Response
	 */
	protected function getResponse() {
		$response = NULL;
		if ($this->templateVariableContainer->exists('response')) {
			$response = $this->templateVariableContainer->get('response');
		}
		return $response;
	}

	/**
	 * Initializes the default PDF font.
	 * If none is provided Helvetica will be used.
	 *
	 * @param NULL|string $defaultFont The name of the default PDF font
	 */
	protected function initializeDefaultFont($defaultFont = NULL) {
		if ($defaultFont === NULL) {
			$defaultFont = \ZendPdf\Font::FONT_HELVETICA;
		}

		$font = \ZendPdf\Font::fontWithName($defaultFont);
		$this->setVariable('defaultFont', $font);
		$this->setVariable('currentFont', $font, TRUE);
	}

	/**
	 * Initializes the default PDF font size.
	 * If none is given the size of 10 will be used.
	 *
	 * @param NULL|float $defaultFontSize The default PDF font size
	 */
	protected function initializeDefaultFontSize($defaultFontSize = NULL) {
		if ($defaultFontSize === NULL) {
			$defaultFontSize = 10;
		}

		$this->setVariable('defaultFontSize', $defaultFontSize);
		$this->setVariable('currentFontSize', $defaultFontSize, TRUE);
	}

	/**
	 * Initializes the default RGB color.
	 * If none is given black (RGB 0,0,0) will be used.
	 *
	 * @param array $defaultColor The default RGB color values
	 */
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
	 * Initializes default PDF styles.
	 *
	 * @param float $defaultCharacterSpacing The default additional character spacing
	 */
	protected function initializeDefaultStyle($defaultCharacterSpacing = 0.0) {
		$this->setVariable('defaultCharacterSpacing', $defaultCharacterSpacing);
		$this->setVariable('currentCharacterSpacing', $defaultCharacterSpacing);
	}

	/**
	 * Forgets about the initialized default values.
	 */
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