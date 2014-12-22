<?php
namespace OliverHader\PdfRendering\ViewHelpers\Element;

/*                                                                           *
 * This script belongs to the TYPO3 Flow package "OliverHader.PdfRendering". *
 *                                                                           */

use TYPO3\Flow\Annotations as Flow;
use OliverHader\PdfRendering\Context\TextStreamInstruction;
use OliverHader\PdfRendering\ViewHelpers\AbstractDocumentViewHelper;

/**
 * Class TotalViewHelper
 * @author Oliver Hader <oliver.hader@typo3.org>
 */
class TextViewHelper extends AbstractDocumentViewHelper {

	/**
	 * @param int $x
	 * @param int $y
	 * @param int $width
	 * @return void
	 */
	public function render($x, $y, $width = NULL) {
		if (!$this->hasPage()) {
			return;
		}

		if ($width === NULL) {
			$width = $this->getPage()->getWidth();
		}

		$textStreamContext = new \OliverHader\PdfRendering\Context\TextStreamContext();
		$textStreamContext->setX($x)->setY($y)->setWidth($width)->setCurrentX($x)->setCurrentY($y);

		$this->templateVariableContainer->add('textStreamContext', $textStreamContext);

		$this->processChildren();

		$this->templateVariableContainer->remove('textStreamContext');
	}

	protected function processChildren() {
		$content = trim($this->renderChildren());
		$content = preg_replace('#[\s]+#', ' ', $content);
		$textStreamContext = $this->getTextStreamContext();

		$instructions = array();
		$chunks = explode('{instruction', $content);

		foreach ($chunks as $chunk) {
			if ($chunk === '') {
				continue;
			}

			$indicator = strstr($chunk, '}', TRUE);
			$identifier = 'instruction' . $indicator;

			// No instruction found
			if (!$textStreamContext->hasInstruction($identifier)) {
				$this->renderContent($chunk);
			// Already processed (closing instruction)
			} elseif (isset($instructions[$identifier])) {
				$this->processInstruction($instructions[$identifier]);
				$chunkContent = substr($chunk, strlen($indicator) + 1);
				$this->renderContent($chunkContent);
			// Starting new instruction
			} else {
				$instructions[$identifier] = $this->processInstruction($textStreamContext->getInstruction($identifier));
				$chunkContent = substr($chunk, strlen($indicator) + 1);
				$this->renderContent($chunkContent);
			}
		}

		return $content;
	}

	/**
	 * @param TextStreamInstruction $instruction
	 * @return TextStreamInstruction
	 */
	protected function processInstruction(TextStreamInstruction $instruction) {
		$page = $this->getPage();

		$revertInstruction = new TextStreamInstruction();

		if ($instruction->getFont() || $instruction->getFontSize()) {
			$revertInstruction->setFont($page->getFont())->setFontSize($page->getFontSize());
			$page->setFont(
				$instruction->getFont(),
				$instruction->getFontSize()
			);
		}

		if ($instruction->getColor()) {
			$revertInstruction->setColor($this->getVariable('currentColor'));
			$this->setVariable('currentColor', $instruction->getColor());
			$page->setFillColor($instruction->getColor());
		}

		if ($instruction->getCharacterSpacing() !== NULL) {
			$revertInstruction->setCharacterSpacing($this->getVariable('currentCharacterSpacing'));
			$this->setVariable('currentCharacterSpacing', $instruction->getCharacterSpacing());
			$page->setCharacterSpacing($instruction->getCharacterSpacing());
		}

		return $revertInstruction;
	}

	/**
	 * @param string $content
	 */
	protected function renderContent($content) {
		if ($content === NULL || $content === FALSE || $content === '') {
			return;
		}

		$page = $this->getPage();
		$textStreamContext = $this->getTextStreamContext();

		$line = '';
		$usedWidth = 0;
		$words = preg_split('#([\s,.-])#', $content, NULL, PREG_SPLIT_DELIM_CAPTURE);

		$currentX = $textStreamContext->getCurrentX();
		$currentY = $textStreamContext->getCurrentY();
		$width = $textStreamContext->getWidth();
		$availableWidth = $width - $currentX;

		foreach ($words as $word) {
			if ($word === '') {
				continue;
			}

			$isLineBreak = (preg_match('#<br\s*/?>#i', $word) > 0);
			$wordWidth = $this->calculateWordWidth($word);

			if ($isLineBreak) {
				$page->drawText($line, $currentX, $currentY);
				$line = '';

				$usedWidth = 0;
				$currentX = $textStreamContext->getX();
				$currentY -= $this->getLineHeight();
				$availableWidth = $width - $currentX;
				continue;
			}

			if ($usedWidth + $wordWidth > $availableWidth || $isLineBreak) {
				if ($line !== '') {
					$page->drawText($line, $currentX, $currentY);
					$line = '';
				}
				$usedWidth = 0;
				$currentX = $textStreamContext->getX();
				$currentY -= $this->getLineHeight();
				$availableWidth = $width - $currentX;

				$word = ltrim($word);
				$wordWidth = $this->calculateWordWidth($word);
			}

			$line .= $word;
			$usedWidth += $wordWidth;
		}

		if ($line !== '' && $usedWidth) {
			$page->drawText($line, $currentX, $currentY);
			$currentX += $usedWidth;
		}

		$textStreamContext->setCurrentX($currentX)->setCurrentY($currentY);
	}

	/**
	 * @return float
	 */
	protected function getLineHeight() {
		$page = $this->getPage();
		$font = $page->getFont();
		$fontSize = $page->getFontSize();
		return ($font->getLineHeight() + $font->getLineGap()) / $font->getUnitsPerEm() * $fontSize;
	}

	/**
	 * @param string $text
	 * @return float|int
	 */
	protected function calculateWordWidth($text) {
		$page = $this->getPage();
		$font = $page->getFont();
		$fontSize = $page->getFontSize();

		$width = 0;
		foreach (preg_split('/(?<!^)(?!$)/u', $text) as $character) {
			$characterWidth = $font->widthForGlyph(
				$font->glyphNumberForCharacter(utf8_ord($character))
			);
			$width += $characterWidth / $font->getUnitsPerEm() * $fontSize;
		}
		return $width;
	}

}