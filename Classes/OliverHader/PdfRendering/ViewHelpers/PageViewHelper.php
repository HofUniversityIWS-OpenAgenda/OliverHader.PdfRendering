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
	 * @param int $pageNumber
	 * @return void
	 */
	public function render($pageNumber) {
		$pageIndex = $pageNumber - 1;

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