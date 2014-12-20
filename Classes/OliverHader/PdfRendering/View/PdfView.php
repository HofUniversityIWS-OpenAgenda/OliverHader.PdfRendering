<?php
namespace OliverHader\PdfRendering\View;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * PageView
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class PdfView extends \TYPO3\Fluid\View\StandaloneView {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Mvc\Response
	 */
	protected $response;

	/**
	 * @param string $actionName
	 * @return \ZendPdf\PdfDocument
	 */
	public function render($actionName = NULL) {
		$documentIdentifier = uniqid('document', TRUE);
		$this->assign('documentIdentifier', $documentIdentifier);
		$this->baseRenderingContext->getTemplateVariableContainer()->add('response', $this->response);

		parent::render($actionName);
		$source = $this->response->getContent();
		return \ZendPdf\PdfDocument::parse($source);
	}

	/**
	 * @param string $filePath
	 * @param string $actionName
	 */
	public function save($filePath, $actionName = NULL) {
		$this->render($actionName)->save($filePath);
	}

}