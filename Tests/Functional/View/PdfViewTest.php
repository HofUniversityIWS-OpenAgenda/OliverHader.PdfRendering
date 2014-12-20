<?php
namespace OliverHader\PdfRendering\Tests\Functional\View;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * @group large
 */
class PdfViewTest extends \TYPO3\Flow\Tests\FunctionalTestCase {

	/**
	 * @var boolean
	 */
	static protected $testablePersistenceEnabled = TRUE;

	/**
	 * @var \OliverHader\PdfRendering\View\PdfView
	 */
	protected $fixture;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * Sets up this test case.
	 */
	public function setUp() {
		parent::setUp();

		$this->fixture = new \OliverHader\PdfRendering\View\PdfView();
	}

	/**
	 * Tears down this test case.
	 */
	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function pdfDocumentIsCreated() {
		$subject = array(
			'title' => uniqid('Test Title'),
			'message' => uniqid('Test Message'),
		);

		$target = $this->getTargetFilePath(__FUNCTION__ . '.pdf');
		$source = dirname(__FILE__) . '/Fixtures/Template.pdf';
		$template = dirname(__FILE__) . '/Fixtures/Template.html';

		$this->fixture->setTemplatePathAndFilename($template);
		$this->fixture->assign('source', $source);
		$this->fixture->assign('subject', $subject);
		$this->fixture->save($target);

		$this->assertFileExists($target);
		$this->assertContains($subject['title'], file_get_contents($target));
	}

	/**
	 * @param string $name
	 * @return string
	 */
	protected function getTargetFilePath($name) {
		 return FLOW_PATH_DATA . str_replace('\\', '', __CLASS__) . '_' . $name;
	}

}
