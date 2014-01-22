<?php
class Square
{
	const SQUARE_NUM		= 9;

	const COLLECTION_ROW	= 'row';
	
	const COLLECTION_COL	= 'col';
	
	const COLLECTION_SQUARE	= 'smallsquare';
	
	private $_units	= array();
	
	private $_collectionEnsureNums	= array();
	
	private static $_squareSeqs	= array();
	
	private static $_collectionUnitSeqs	= array();

	public function __construct(array $squareArr)
	{
		$this->_initSquare($squareArr);
	}

	private function _initSquare(array $squareArr)
	{
		$seq	= 1;
		for ($i = 0; $i < self::SQUARE_NUM; $i++) {
			$row	= $squareArr[$i];
			if (false === $row) {
				throw new Exception(($i + 1) . " row have not number.");
			}
			for ($j = 0; $j < self::SQUARE_NUM; $j++) {
				$unit	= $row[$j];
				if ($unit < 0 || $unit > 9) {
					throw new Exception(($i + 1) . " row, " . ($j + 1) . " col is not a correct number.");
				}

				if (0 == $unit) {
					$possibleNums	= $this->_calUnitPossibleNums($seq);
					$this->_setUnitPossibleNums($seq, $possibleNums);
				} else {
					$this->_setUnitNum($seq, $unit);
				}
				$seq++;
			}
		}
	}
	
	public function getUnit($seq)
	{
		return isset($this->_units[$seq]) ? $this->_units[$seq] : array();
	}

	private function _setUnitNum($seq, $num)
	{
		// 如果该单元格已经有其他数了，肯定不对了，发生了异常
		if (isset($this->_units[$seq]) && !is_array($this->_units[$seq])) {
			throw new Exception('unit ' . $seq . ' have had a number');
		}
		$this->_units[$seq]	= $num;
	
		$this->_noticeAUnitHaveEnsured($seq, $num);
		
		return $this;
	}
	
	public function setUnitNum($seq, $num)
	{
		return $this->_setUnitNum($seq, $num);
	}
	
	/**
	 * to notice a unit num have ensured
	 * @param Int $seq
	 * @param Int $num 1-9
	 */
	private function _noticeAUnitHaveEnsured($seq, $num)
	{
		$position	= $this->_getUnitPosition($seq);
		$rownum		= $position[self::COLLECTION_ROW];
		$colnum		= $position[self::COLLECTION_COL];
		$squarenum	= $position[self::COLLECTION_SQUARE];
		
		$this->_addCollectionEnsureNum(self::COLLECTION_ROW, $rownum, $num);
		$this->_addCollectionEnsureNum(self::COLLECTION_COL, $colnum, $num);
		$this->_addCollectionEnsureNum(self::COLLECTION_SQUARE, $squarenum, $num);
		
		$collections	= array(
			self::COLLECTION_ROW	=> $rownum,
			self::COLLECTION_COL	=> $colnum,
			self::COLLECTION_SQUARE	=> $squarenum,
		);
		
		$haveExcludeUnits	= array();
		foreach ($collections as $collection => $collectionSeq) {
			$seqs	= $this->_getCollectionUnitSeqs($collection, $collectionSeq);
			foreach ($seqs as $unitSeq) {
				if (!isset($haveExcludeUnits[$unitSeq])) {
					$this->_excludeUnitPossibleNum($unitSeq, $num);
					$haveExcludeUnits[$unitSeq]	= $unitSeq;
				}
			}
		}
		
		return $this;
	}
	
	private function _getCollectionUnitSeqs($collection, $collectionSeq)
	{
		if (!isset(self::$_collectionUnitSeqs[$collection][$collectionSeq])) {
			$seqs	= array();
			if ($collection == self::COLLECTION_ROW) {
				$start	= ($collectionSeq - 1) * 9;
				for ($i = 1; $i <= self::SQUARE_NUM; $i++) {
					$seqs[]	= $start + $i;
				}
			} else if ($collection == self::COLLECTION_COL) {
				for ($i = 0, $addNum = 0; $i < self::SQUARE_NUM; $i++, $addNum += 9) {
					$seqs[]	= $collectionSeq + $addNum;
				}
			} else {
				$start	= intval(($collectionSeq - 1) / 3) * 27 + (($collectionSeq - 1) % 3) * 3 + 1;
				for ($i = 0; $i < 3; $i++) {
					for ($j = 0; $j < 3; $j++) {
						$seqs[]	= $start + $i * 9 + $j;
					}
				}
			}
			self::$_collectionUnitSeqs[$collection][$collectionSeq]	= $seqs;
		}
		
		return self::$_collectionUnitSeqs[$collection][$collectionSeq];
	}
	
	private function _excludeUnitPossibleNum($seq, $excludeNum)
	{
		$nums	= $this->getUnit($seq);
		if (is_array($nums) && $nums) {
			$key	= array_search($excludeNum, $nums);
			if ($key !== false) {
				unset($nums[$key]);
				if (count($nums) == 1) {
					$this->_setUnitNum($seq, current($nums));
				} else if (count($nums) == 0) {
					throw new Exception('unit ' . $seq . ' can not have a number.');
				} else {
					$this->_setUnitPossibleNums($seq, $nums);
				}
			}
		} 
		return $this;
	}
	
	private function _addCollectionEnsureNum($collection, $collectionSeq, $num)
	{
		if (isset($this->_collectionEnsureNums[$collection][$collectionSeq])) {
			if (isset($this->_collectionEnsureNums[$collection][$collectionSeq][$num])) {
				throw new Exception($collectionSeq . ' ' . $collection . ' have had number ' . $num);
			}
			$this->_collectionEnsureNums[$collection][$collectionSeq][$num]	= $num;
		} else {
			$this->_collectionEnsureNums[$collection][$collectionSeq]	= array($num => $num);
		}
		return $this;
	}
	
	private function _setUnitPossibleNums($seq, array $nums)
	{
		$this->_units[$seq]	= $nums;
		return $this;
	}
	
	/**
	 * calculate a unit may have numbers that it is possible
	 * @param Int $seq
	 */
	private function _calUnitPossibleNums($seq)
	{
		$position	= $this->_getUnitPosition($seq);
		$rownum	= $position[self::COLLECTION_ROW];
		$colnum	= $position[self::COLLECTION_COL];
		$squarenum	= $position[self::COLLECTION_SQUARE];
		
		$rowNums	= $this->_getCollectionEnsureNums(self::COLLECTION_ROW, $rownum);
		$colNums	= $this->_getCollectionEnsureNums(self::COLLECTION_COL, $colnum);
		$squareNums	= $this->_getCollectionEnsureNums(self::COLLECTION_SQUARE, $squarenum);
		
		$allPossibleNums	= array(1, 2, 3, 4, 5, 6, 7, 8, 9);
		$possibleNums	= array();
		foreach ($allPossibleNums as $num) {
			if (in_array($num, $rowNums) || in_array($num, $colNums) || in_array($num, $squareNums)) {
				continue;
			} else {
				$possibleNums[]	= $num;
			}
		}
		return $possibleNums;
	}
	
	/**
	 * get a collection (like row, col etc) which special collection seq have ensured numbers
	 * @param Int $rownum
	 * @return Array
	 */
	private function _getCollectionEnsureNums($collection, $collectionSeq)
	{
		if (!isset($this->_collectionEnsureNums[$collection][$collectionSeq])) {
			return array();
		}
		return $this->_collectionEnsureNums[$collection][$collectionSeq];
	}
	
	/**
	 * 
	 * @param Int $seq
	 * @return Array include rownum colnum smallsquarenum
	 */
	private function _getUnitPosition($seq)
	{
		if (!isset(self::$_squareSeqs[$seq])) {
			$rownum	= intval(($seq - 1) / 9) + 1;
			$colnum	= (($seq - 1) % 9) + 1;
			self::$_squareSeqs[$seq]	= array(
				self::COLLECTION_ROW	=> $rownum,
				self::COLLECTION_COL	=> $colnum,
				self::COLLECTION_SQUARE	=> intval(($rownum - 1) / 3) * 3 + intval(($colnum - 1) / 3) + 1,
			);
		}
		
		return self::$_squareSeqs[$seq];
	}

	public function draw()
	{
		$html	= '<table border="1">';
		$i	= 1;
		foreach ($this->_units as $unit) {
			if ($i == 1) {
				$html	.= '<tr>';
			}
			
			$num	= is_array($unit) ? implode(',', $unit) : $unit;
			$html	.= '<td>' . $num . '</td>';
			
			if ($i++ == 9) {
				$i	= 1;
				$html	.= '</tr>';
			}
		}
		$html	.= '</table>';
		echo $html;
	}
	
	public function isOk()
	{
		foreach ($this->_units as $unit) {
			if (is_array($unit)) {
				return false;
			}
		}
		return true;
	}
	
	public function checkCorrect()
	{
		for ($i = 1; $i <= self::SQUARE_NUM; $i++) {
			$collections	= array(self::COLLECTION_ROW, self::COLLECTION_COL, self::COLLECTION_SQUARE);
			foreach ($collections as $collection) {
				$seqs	= $this->_getCollectionUnitSeqs($collection, $i);
				
				$units	= array();
				foreach ($seqs as $seq) {
					$unit	= $this->getUnit($seq);
					$units[]	= $unit;
				}
				sort($units);
				
				if ($units != array(1, 2, 3, 4, 5, 6, 7, 8, 9)) {
					return false;
				}
			}
		}
		return true;
	}
}