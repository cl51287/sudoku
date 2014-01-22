<?php
class Guesser
{
	private $_square;
	
	private $_lastSquare;
	
	public function __construct(Square $square)
	{
		$this->_square	= $square;
	}
	
	public function guess()
	{
		$bool	= $this->_guess($this->_square, 1);
		
		if ($bool) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getTrueSquare()
	{
		return $this->_lastSquare;
	}

	private function _guess(Square $square, $curSeq)
	{
		$copy	= clone $square;
		for ($total = Square::SQUARE_NUM * Square::SQUARE_NUM; $curSeq <= $total; $curSeq++) {
			$unit	= $square->getUnit($curSeq);
			if (is_numeric($unit)) {
				continue;
			} else {
				break;
			}
		}
		// 如果到最后一个单元格了还没算出来说明算不出来了
		if (empty($unit)) {
			return false;
		}
		
		$possible	= $unit;
		foreach ($possible as $num) {
			try {
				$square->setUnitNum($curSeq, $num);
			} catch (Exception $e) {
				$square	= clone $copy;
				$unit	= $square->getUnit($curSeq);
				continue;
			}
			
			if ($square->isOk()) {
				$this->_lastSquare	= $square;
				return true;
			} else {
				$isOk	= $this->_guess($square, $curSeq);
				
				if ($isOk) {
					return true;
				} else {
					$square	= clone $copy;
					$unit	= $square->getUnit($curSeq);
					continue;
				}
			}
		}
		
		$square	= $square	= clone $copy;
		return false;
	}
}