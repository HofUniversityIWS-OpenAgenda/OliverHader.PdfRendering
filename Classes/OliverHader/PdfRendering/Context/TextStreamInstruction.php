<?php
namespace OliverHader\PdfRendering\Context;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * PageView
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextStreamInstruction {

	protected $font;
	protected $fontSize;
	protected $color;
	protected $text;
	protected $characterSpacing;

	public function getFont() {
		return $this->font;
	}

	public function setFont($font) {
		$this->font = $font;
		return $this;
	}

	public function getFontSize() {
		return $this->fontSize;
	}

	public function setFontSize($fontSize) {
		$this->fontSize = $fontSize;
		return $this;
	}

	public function getColor() {
		return $this->color;
	}

	public function setColor($color) {
		$this->color = $color;
		return $this;
	}

	public function getText() {
		return $this->text;
	}

	public function setText($text) {
		$this->text = $text;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getCharacterSpacing() {
		return $this->characterSpacing;
	}

	/**
	 * @param float $characterSpacing
	 * @return TextStreamInstruction
	 */
	public function setCharacterSpacing($characterSpacing) {
		$this->characterSpacing = $characterSpacing;
		return $this;
	}

}