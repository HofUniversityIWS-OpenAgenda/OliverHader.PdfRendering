<?php
namespace OliverHader\PdfRendering\ViewHelpers;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class PageViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class PageViewHelper extends AbstractDocumentViewHelper {

	/**
	 * Renders a PDF page.
	 * Results are directly applied to the known PDF document.
	 *
	 * @param int $pageNumber Page index number to be rendered in the target PDF document (staring with 1)
	 * @return void
	 */
	public function render($pageNumber) {
		if (!$this->hasDocument()) {
			return;
		}

		$document = $this->getDocument();
		if ($pageNumber < 1 || $pageNumber > count($document->pages)) {
			return;
		}

		$page = $document->pages[0];
		$this->applyDefaults($page);

		$this->setPage($page);
		$this->renderChildren();
		$this->unsetPage();
	}

	/**
	 * Applies default values to the PDF page to be rendered.
	 *
	 * @param \ZendPdf\Page $page
	 */
	protected function applyDefaults(\ZendPdf\Page $page) {
		$defaultFont = $this->getVariable('defaultFont');
		$defaultFontSize = $this->getVariable('defaultFontSize');
		$currentColor = $this->getVariable('currentColor');
		$currentCharacterSpacing = $this->getVariable('currentCharacterSpacing');

		$page->setFont($defaultFont, $defaultFontSize);
		$page->setFillColor($currentColor);
		$page->setCharacterSpacing($currentCharacterSpacing);
	}

}