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

	protected function hasVariable($variableName) {
		return $this->viewHelperVariableContainer->exists(self::NAME_ViewHelper, $variableName);
	}

	/**
	 * @param string $variableName
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
	 * @param string $variableName
	 * @param mixed $value
	 * @param bool $override
	 */
	protected function setVariable($variableName, $value, $override = FALSE) {
		if ($override || !$this->hasVariable($variableName)) {
			$this->viewHelperVariableContainer->addOrUpdate(self::NAME_ViewHelper, $variableName, $value);
		}
	}

	/**
	 * @param $variableName
	 */
	protected function unsetVariable($variableName) {
		if (!$this->hasVariable($variableName)) {
			$this->viewHelperVariableContainer->remove(self::NAME_ViewHelper, $variableName);
		}
	}

	/**
	 * @return bool
	 */
	protected function hasDocument() {
		return $this->hasVariable('document');
	}

	/**
	 * @return NULL|\ZendPdf\PdfDocument
	 */
	protected function getDocument() {
		return $this->getVariable('document');
	}

	/**
	 * @param \ZendPdf\PdfDocument $document
	 */
	protected function setDocument(\ZendPdf\PdfDocument $document) {
		$this->setVariable('document', $document);
	}

	protected function unsetDocument() {
		$this->unsetVariable('document');
	}

	/**
	 * @return bool
	 */
	protected function hasPage() {
		return $this->hasVariable('page');
	}

	/**
	 * @return NULL|\ZendPdf\Page
	 */
	protected function getPage() {
		return $this->getVariable('page');
	}

	/**
	 * @param \ZendPdf\Page $page
	 */
	protected function setPage(\ZendPdf\Page $page) {
		$this->setVariable('page', $page);
	}

	protected function unsetPage() {
		$this->unsetVariable('page');
	}

	/**
	 * @return bool
	 */
	protected function hasTextStreamContext() {
		return $this->templateVariableContainer->exists('textStreamContext');
	}

	/**
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
	 * @param string $content
	 * @return string
	 */
	protected function processSpecialCharacters($content) {
		return html_entity_decode($content, ENT_COMPAT, 'utf-8');
	}

	/**
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
	 * @param string $identifier
	 * @return \OliverHader\PdfRendering\Context\TextStreamInstruction
	 */
	protected function createInstruction($identifier) {
		$instruction = new \OliverHader\PdfRendering\Context\TextStreamInstruction();
		$this->getTextStreamContext()->setInstruction($identifier, $instruction);
		return $instruction;
	}

	/**
	 * @param string $content
	 * @param string $identifier
	 * @return string
	 */
	protected function wrap($content, $identifier) {
		return '{' . $identifier . '}' . $content . '{' . $identifier . '}';
	}

	/**
	 * @param \ZendPdf\Resource\Font\AbstractFont $font
	 * @return string
	 */
	protected function getFontName(\ZendPdf\Resource\Font\AbstractFont $font) {
		return $font->getFontName(\ZendPdf\Font::NAME_POSTSCRIPT, 'en', '//TRANSLIT');
	}

}