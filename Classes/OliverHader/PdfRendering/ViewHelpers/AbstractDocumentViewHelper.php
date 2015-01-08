<?php
namespace OliverHader\PdfRendering\ViewHelpers;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class AbstractDocumentViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class AbstractDocumentViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	const NAME_ViewHelper = 'OliverHader\\PdfRendering\\ViewHelpers\\DocumentViewHelper';

	/**
	 * @var bool
	 */
	protected $escapingInterceptorEnabled = TRUE;

	/**
	 * Determines whether a variable is available.
	 *
	 * @param string $variableName The variable identifier
	 * @return bool
	 */
	protected function hasVariable($variableName) {
		return $this->viewHelperVariableContainer->exists(self::NAME_ViewHelper, $variableName);
	}

	/**
	 * Gets the value of a variable if available, NULL otherwise.
	 *
	 * @param string $variableName The variable identifier
	 * @return NULL|mixed
	 */
	protected function getVariable($variableName) {
		$value = NULL;
		if ($this->hasVariable($variableName)) {
			$value = $this->viewHelperVariableContainer->get(self::NAME_ViewHelper, $variableName);
		}
		return $value;
	}

	/**
	 * Sets the value for a variable.
	 * If the variable has been set already, it's ignored unless the $override argument is TRUE.
	 *
	 * @param string $variableName The variable identifier
	 * @param mixed $value The value of for this variable
	 * @param bool $override Whether to override existing variables
	 */
	protected function setVariable($variableName, $value, $override = FALSE) {
		if ($override || !$this->hasVariable($variableName)) {
			$this->viewHelperVariableContainer->addOrUpdate(self::NAME_ViewHelper, $variableName, $value);
		}
	}

	/**
	 * Removes a variable.
	 *
	 * @param string $variableName The variable identifier
	 */
	protected function unsetVariable($variableName) {
		if (!$this->hasVariable($variableName)) {
			$this->viewHelperVariableContainer->remove(self::NAME_ViewHelper, $variableName);
		}
	}

	/**
	 * Determines whether the PDF document is known.
	 *
	 * @return bool
	 */
	protected function hasDocument() {
		return $this->hasVariable('document');
	}

	/**
	 * Gets the current PDF document.
	 *
	 * @return NULL|\ZendPdf\PdfDocument
	 */
	protected function getDocument() {
		return $this->getVariable('document');
	}

	/**
	 * Sets the current PDF document.
	 *
	 * @param \ZendPdf\PdfDocument $document
	 */
	protected function setDocument(\ZendPdf\PdfDocument $document) {
		$this->setVariable('document', $document);
	}

	/**
	 * Forgets about the current PDF document.
	 */
	protected function unsetDocument() {
		$this->unsetVariable('document');
	}

	/**
	 * Determines whether the current PDF page is known.
	 *
	 * @return bool
	 */
	protected function hasPage() {
		return $this->hasVariable('page');
	}

	/**
	 * Gets the current PDF page.
	 *
	 * @return NULL|\ZendPdf\Page
	 */
	protected function getPage() {
		return $this->getVariable('page');
	}

	/**
	 * Sets the current PDF page.
	 *
	 * @param \ZendPdf\Page $page
	 */
	protected function setPage(\ZendPdf\Page $page) {
		$this->setVariable('page', $page);
	}

	/**
	 * Forgets about the current PDF page.
	 */
	protected function unsetPage() {
		$this->unsetVariable('page');
	}

	/**
	 * Determines whether a text stream context is known.
	 *
	 * @return bool
	 */
	protected function hasTextStreamContext() {
		return $this->templateVariableContainer->exists('textStreamContext');
	}

	/**
	 * Gets the current text stream context.
	 *
	 * @return NULL|\OliverHader\PdfRendering\Context\TextStreamContext
	 */
	protected function getTextStreamContext() {
		$textStreamContext = NULL;
		if ($this->hasTextStreamContext()) {
			$textStreamContext = $this->templateVariableContainer->get('textStreamContext');
		}
		return $textStreamContext;
	}

	/**
	 * Processes special characters that are given as HTML entities.
	 *
	 * @param string $content
	 * @return string
	 */
	protected function processSpecialCharacters($content) {
		return html_entity_decode($content, ENT_COMPAT, 'utf-8');
	}

	/**
	 * Renders child elements.
	 *
	 * @return NULL|string
	 */
	public function renderChildren() {
		$content = parent::renderChildren();

		if ($content !== NULL) {
			$content = trim($this->processSpecialCharacters($content));
		}

		return $content;
	}

	/**
	 * Creates a new instruction.
	 *
	 * @param string $identifier The identifier of the instruction
	 * @return \OliverHader\PdfRendering\Context\TextStreamInstruction
	 */
	protected function createInstruction($identifier) {
		$instruction = new \OliverHader\PdfRendering\Context\TextStreamInstruction();
		$this->getTextStreamContext()->setInstruction($identifier, $instruction);
		return $instruction;
	}

	/**
	 * Wraps a content block with instruction identifier marks.
	 *
	 * @param string $content The content to be wrapped
	 * @param string $identifier The instruction identifier
	 * @return string The wrapped content block
	 */
	protected function wrap($content, $identifier) {
		return '{' . $identifier . '}' . $content . '{' . $identifier . '}';
	}

	/**
	 * Gets the name of a PDF font.
	 *
	 * @param \ZendPdf\Resource\Font\AbstractFont $font
	 * @return string
	 */
	protected function getFontName(\ZendPdf\Resource\Font\AbstractFont $font) {
		return $font->getFontName(\ZendPdf\Font::NAME_POSTSCRIPT, 'en', '//TRANSLIT');
	}

}