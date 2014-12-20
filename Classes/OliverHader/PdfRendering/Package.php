<?php
namespace OliverHader\PdfRendering;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Package\Package as BasePackage;

/**
 * Class Package
 *
 * @Flow\Scope("singleton")
 * @package OliverHader\PdfRendering
 * @author Oliver Hader <oliver@typo3.org>
 */
class Package extends BasePackage {

	const PackageName = 'OliverHader.PdfRendering';

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Package\PackageManagerInterface
	 */
	protected $packageManager;

	/**
	 * @param \TYPO3\Flow\Core\Bootstrap $bootstrap
	 */
	public function boot(\TYPO3\Flow\Core\Bootstrap $bootstrap) {
		// Some Zend packages define custom annotations which need to be ignored by Doctrine2
		\Doctrine\Common\Annotations\AnnotationReader::addGlobalIgnoredName('configkey');
		// Load UTF8 functions for PHP usage
		$functionFile = $this->packageManager->getPackage(static::PackageName)->getResourcesPath() . 'Private/Scripts/functions.php';
		require_once $functionFile;
	}

}