<?php
namespace OliverHader\PdfRendering\Context;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;

/**
 * Class TextStreamContext
 *
 * Handles positions and instructions inside a `<pdf:element.text>` block.
 * This context is bound inside the accordant TextViewHelper.
 *
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextStreamContext {

	/**
	 * @var float
	 */
	protected $x;

	/**
	 * @var float
	 */
	protected $y;

	/**
	 * @var float
	 */
	protected $width;

	/**
	 * @var float
	 */
	protected $currentX;

	/**
	 * @var float
	 */
	protected $currentY;

	/**
	 * @var array|TextStreamInstruction[]
	 */
	protected $instructions = array();

	/**
	 * Gets the starting x position.
	 *
	 * @return float
	 */
	public function getX() {
		return $this->x;
	}

	/**
	 * Sets the starting x position.
	 *
	 * @param float $x
	 * @return TextStreamContext
	 */
	public function setX($x) {
		$this->x = $x;
		return $this;
	}

	/**
	 * Gets the starting y position.
	 *
	 * @return float
	 */
	public function getY() {
		return $this->y;
	}

	/**
	 * Sets the starting y position.
	 *
	 * @param float $y
	 * @return TextStreamContext
	 */
	public function setY($y) {
		$this->y = $y;
		return $this;
	}

	/**
	 * Gets the available width.
	 *
	 * @return float
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Sets the available width.
	 *
	 * @param float $width
	 * @return TextStreamContext
	 */
	public function setWidth($width) {
		$this->width = $width;
		return $this;
	}

	/**
	 * Gets the current x position.
	 *
	 * @return float
	 */
	public function getCurrentX() {
		return $this->currentX;
	}

	/**
	 * Sets the current x position.
	 *
	 * @param float $currentX
	 * @return TextStreamContext
	 */
	public function setCurrentX($currentX) {
		$this->currentX = $currentX;
		return $this;
	}

	/**
	 * Gets the current y position.
	 *
	 * @return float
	 */
	public function getCurrentY() {
		return $this->currentY;
	}

	/**
	 * Sets the current y position.
	 *
	 * @param float $currentY
	 * @return TextStreamContext
	 */
	public function setCurrentY($currentY) {
		$this->currentY = $currentY;
		return $this;
	}

	/**
	 * Sets an instruction with a given identifier.
	 *
	 * @param string $identifier
	 * @param TextStreamInstruction $instruction
	 */
	public function setInstruction($identifier, TextStreamInstruction $instruction) {
		$this->instructions[$identifier] = $instruction;
	}

	/**
	 * Determines the existence of an instruction for a given identifier.
	 *
	 * @param string $identifier
	 * @return bool
	 */
	public function hasInstruction($identifier) {
		return isset($this->instructions[$identifier]);
	}

	/**
	 * Gets an instruction for a given identifier.
	 *
	 * @param string $identifier
	 * @return TextStreamInstruction
	 */
	public function getInstruction($identifier) {
		return $this->instructions[$identifier];
	}

	/**
	 * Gets all defined instructions.
	 *
	 * @return array|TextStreamInstruction[]
	 */
	public function getInstructions() {
		return $this->instructions;
	}

}