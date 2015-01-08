<?php
namespace OliverHader\PdfRendering\Context;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class TextStreamInstruction.
 *
 * Holds a particular instruction for text rendering.
 * Multiple instructions are bound to one TextStreamContext.
 *
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextStreamInstruction {

	/**
	 * @var \ZendPdf\Resource\Font\AbstractFont
	 */
	protected $font;

	/**
	 * @var float
	 */
	protected $fontSize;

	/**
	 * @var \ZendPdf\Color\ColorInterface
	 */
	protected $color;

	/**
	 * Text contents - currently not used.
	 *
	 * @var string
	 */
	protected $text;

	/**
	 * @var float
	 */
	protected $characterSpacing;

	/**
	 * Gets the font.
	 *
	 * @return \ZendPdf\Resource\Font\AbstractFont
	 */
	public function getFont() {
		return $this->font;
	}

	/**
	 * Sets the font.
	 *
	 * @param \ZendPdf\Resource\Font\AbstractFont $font
	 * @return TextStreamInstruction
	 */
	public function setFont($font) {
		$this->font = $font;
		return $this;
	}

	/**
	 * Gets the font size.
	 *
	 * @return float
	 */
	public function getFontSize() {
		return $this->fontSize;
	}

	/**
	 * Sets the font size.
	 *
	 * @param float $fontSize
	 * @return TextStreamInstruction
	 */
	public function setFontSize($fontSize) {
		$this->fontSize = $fontSize;
		return $this;
	}

	/**
	 * Gets the color.
	 *
	 * @return \ZendPdf\Color\ColorInterface
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * Sets the color.
	 *
	 * @param \ZendPdf\Color\ColorInterface $color
	 * @return TextStreamInstruction
	 */
	public function setColor(\ZendPdf\Color\ColorInterface $color) {
		$this->color = $color;
		return $this;
	}

	/**
	 * Gets the text content.
	 *
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * Sets the text content.
	 *
	 * @param string $text
	 * @return TextStreamInstruction
	 */
	public function setText($text) {
		$this->text = $text;
		return $this;
	}

	/**
	 * Gets the character spacing.
	 *
	 * @return float
	 */
	public function getCharacterSpacing() {
		return $this->characterSpacing;
	}

	/**
	 * Sets the character spacing.
	 *
	 * @param float $characterSpacing
	 * @return TextStreamInstruction
	 */
	public function setCharacterSpacing($characterSpacing) {
		$this->characterSpacing = $characterSpacing;
		return $this;
	}

}