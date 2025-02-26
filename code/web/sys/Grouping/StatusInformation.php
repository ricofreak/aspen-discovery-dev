<?php


class Grouping_StatusInformation {
	private bool $_available = false;
	private bool $_availableLocally = false;
	private bool $_availableHere = false;
	private bool $_availableOnline = false;
	private bool $_inLibraryUseOnly = true;
	private bool $_allLibraryUseOnly = true;
	private bool $_hasLocalItem = false;
	public string $_groupedStatus = 'Currently Unavailable';
	private int $_onOrderCopies = 0;
	private int $_numHolds = 0;
	private int $_copies = 0;
	private int $_availableCopies = 0;
	private int $_localCopies = 0;
	private int $_localAvailableCopies = 0;
	private int $_holdableCopies = 0;
	private bool $_isEContent = false;
	private bool $_isShowStatus = false;
	private bool $_isLocallyOwned = false;
	private bool $_isLibraryOwned = false;

	/**
	 * @return bool
	 */
	public function isAvailable() : bool {
		return $this->_available;
	}

	/**
	 * @param Grouping_StatusInformation $statusInformation
	 */
	public function updateStatus(Grouping_StatusInformation $statusInformation) : void {
		if ($statusInformation->isAvailableLocally()) {
			$this->_availableLocally = true;
		}
		if ($statusInformation->isAvailableHere()) {
			$this->_availableHere = true;
		}
		if ($statusInformation->isAvailableOnline()) {
			$this->_availableOnline = true;
		}
		if ($statusInformation->isEContent()) {
			$this->_isEContent = true;
		}
		if (!$this->_available && $statusInformation->isAvailable()) {
			$this->_available = true;
		}
		if ($statusInformation->isInLibraryUseOnly()) {
			$this->_inLibraryUseOnly = true;
		} else {
			$this->_allLibraryUseOnly = false;
		}
		if ($statusInformation->hasLocalItem()) {
			$this->_hasLocalItem = true;
		}
		if ($statusInformation->getNumHolds()) {
			$this->_numHolds += $statusInformation->getNumHolds();
		}
		$this->_onOrderCopies += $statusInformation->getOnOrderCopies();

		$this->_groupedStatus = GroupedWorkDriver::keepBestGroupedStatus($this->_groupedStatus, $statusInformation->getGroupedStatus());

		$this->_copies += $statusInformation->getCopies();
		$this->_availableCopies += $statusInformation->getAvailableCopies();
		$this->_holdableCopies += $statusInformation->getHoldableCopies();
		if ($statusInformation->getLocalCopies() > 0) {
			$this->_localCopies += $statusInformation->getLocalCopies();
			$this->_localAvailableCopies += $statusInformation->getLocalAvailableCopies();
			$this->_hasLocalItem = true;
		}
		if ($statusInformation->isShowStatus()) {
			$this->_isShowStatus = true;
		}
	}

	/**
	 * @return bool
	 */
	public function isAvailableHere(): bool {
		return $this->_availableHere;
	}

	/**
	 * @return bool
	 */
	public function isAvailableOnline(): bool {
		return $this->_availableOnline;
	}

	/**
	 * @return bool
	 */
	public function isAllLibraryUseOnly(): bool {
		return $this->_allLibraryUseOnly;
	}

	/**
	 * @return bool
	 */
	public function isAvailableLocally(): bool {
		return $this->_availableLocally;
	}

	/**
	 * @return bool
	 */
	public function hasLocalItem() : bool {
		return $this->_hasLocalItem;
	}

	/**
	 * @return bool
	 */
	public function isInLibraryUseOnly(): bool {
		return $this->_inLibraryUseOnly;
	}

	/**
	 * @return string
	 */
	public function getGroupedStatus(): string {
		return $this->_groupedStatus;
	}

	/**
	 * @param string $groupedStatus
	 */
	public function setGroupedStatus(string $groupedStatus): void {
		$this->_groupedStatus = $groupedStatus;
	}

	/**
	 * @return int
	 */
	public function getNumHolds(): int {
		return $this->_numHolds;
	}

	/**
	 * @return int
	 */
	public function getOnOrderCopies() : int {
		return $this->_onOrderCopies;
	}

	/**
	 * @return int
	 */
	public function getCopies() : int {
		return $this->_copies;
	}

	/**
	 * @return int
	 */
	public function getAvailableCopies() : int {
		return $this->_availableCopies;
	}

	/**
	 * @return int
	 */
	public function getHoldableCopies() : int {
		return $this->_holdableCopies;
	}

	/**
	 * @return int
	 */
	public function getLocalCopies(): int {
		return $this->_localCopies;
	}

	/**
	 * @return int
	 */
	public function getLocalAvailableCopies(): int {
		return $this->_localAvailableCopies;
	}

	/**
	 * @param int $numHolds
	 */
	public function setNumHolds(int $numHolds): void {
		$this->_numHolds = $numHolds;
	}

	/**
	 * @param bool $available
	 */
	function setAvailable(bool $available): void {
		$this->_available = $available;
	}

	/**
	 * @param bool $availableOnline
	 */
	function setAvailableOnline(bool $availableOnline): void {
		$this->_availableOnline = $availableOnline;
	}

	function addAvailableCopies(int $numCopies): void {
		$this->_availableCopies += $numCopies;
	}

	function addHoldableCopies(int $numCopies): void {
		$this->_holdableCopies += $numCopies;
	}

	/**
	 * @param bool $allLibraryUseOnly
	 */
	function setAllLibraryUseOnly(bool $allLibraryUseOnly): void {
		$this->_allLibraryUseOnly = $allLibraryUseOnly;
	}

	/**
	 * @param bool $inLibraryUseOnly
	 */
	function setInLibraryUseOnly(bool $inLibraryUseOnly): void {
		$this->_inLibraryUseOnly = $inLibraryUseOnly;
	}

	/**
	 * @param bool $availableHere
	 */
	function setAvailableHere(bool $availableHere): void {
		$this->_availableHere = $availableHere;
	}

	/**
	 * @param bool $availableLocally
	 */
	function setAvailableLocally(bool $availableLocally): void {
		$this->_availableLocally = $availableLocally;
	}

	/**
	 * @param int $copies
	 */
	function addCopies(int $copies) : void {
		$this->_copies += $copies;
	}

	/**
	 * @param int $localCopies
	 */
	function addLocalCopies(int $localCopies): void {
		$this->_localCopies += $localCopies;
		if ($localCopies > 0) {
			$this->_hasLocalItem = true;
		}
	}

	function addOnOrderCopies(int $numCopies): void {
		$this->_onOrderCopies += $numCopies;
	}

	function getNumberOfCopiesMessage() : string {
		//Build the string to be translated
		$numberOfCopiesMessage = '';
		global $library;
		//If we don't have holds or on order copies, we don't need to show anything.
		if (($this->getNumHolds() == 0 || $this->getHoldableCopies() == 0) && $this->getOnOrderCopies() == 0 && $library->showGroupedHoldCopiesCount != 3) {
			/** @noinspection PhpConditionAlreadyCheckedInspection */
			$numberOfCopiesMessage = '';
		} else {
			if ($this->getAvailableCopies() > 9999) {
				$numberOfCopiesMessage .= 'Always Available';
			} else {
				if ($this->getNumHolds() == 0 || $this->getHoldableCopies() == 0) {
					if ($this->getAvailableCopies() == 1) {
						$numberOfCopiesMessage .= '1 copy available';
					} elseif ($this->getAvailableCopies() > 1) {
						$numberOfCopiesMessage .= '%1% copies available';
					}
				}

				if ($library->showGroupedHoldCopiesCount == 2 || $library->showGroupedHoldCopiesCount == 3) {
					$showWaitList = true;
				}else if ($library->showGroupedHoldCopiesCount == 1) {
					$showWaitList = $this->getAvailableCopies() == 0 && !$this->isAvailableOnline();
				}else{
					$showWaitList = false;
				}
				if (($this->getNumHolds() > 0 && $this->getHoldableCopies() > 0) && ($showWaitList)) {
					if ($this->getCopies() == 1) {
						$numberOfCopiesMessage .= '1 copy';
					} elseif ($this->getCopies() > 1) {
						$numberOfCopiesMessage .= '%1% copies';
					}
					if (!empty($numberOfCopiesMessage)) {
						$numberOfCopiesMessage .= ', ';
					}
					if ($this->getNumHolds() == 1) {
						$numberOfCopiesMessage .= '1 person is on the wait list';
					} else {
						$numberOfCopiesMessage .= '%2% people are on the wait list';
					}
				}
			}
			if (!empty($numberOfCopiesMessage)) {
				$numberOfCopiesMessage .= '. ';
			}
			if ($this->getOnOrderCopies() > 0 && $this->getCopies() < 10000) {
				if ($this->getGroupedStatus() != 'Under Consideration') {
					if ($library->showOnOrderCounts) {
						if ($this->getOnOrderCopies() == 1) {
							$numberOfCopiesMessage .= '1 copy on order.';
						} else {
							$numberOfCopiesMessage .= '%3% copies on order.';
						}
					} else {
						//Only show that additional copies are on order if there are existing copies
						if ($this->getCopies() > 0) {
							$numberOfCopiesMessage .= 'Additional copies on order.';
						}
					}
				}
			}
		}

		return translate([
			'text' => $numberOfCopiesMessage,
			1 => $this->getCopies(),
			2 => $this->getNumHolds(),
			3 => $this->getOnOrderCopies(),
			'isPublicFacing' => true,
		]);
	}

	public function setIsEContent(bool $flag) : void {
		$this->_isEContent = $flag;
	}

	public function isEContent() : bool {
		return $this->_isEContent;
	}

	/** @noinspection PhpUnused */
	public function showCopySummary() : bool {
		return !$this->_isEContent;
	}

	/**
	 * @return bool
	 */
	public function isShowStatus(): bool {
		return $this->_isShowStatus;
	}

	/**
	 * @param bool $isShowStatus
	 */
	public function setIsShowStatus(bool $isShowStatus): void {
		$this->_isShowStatus = $isShowStatus;
	}

	public function isLocallyOwned() : bool {
		return $this->_isLocallyOwned;
	}

	public function setIsLocallyOwned(bool $isLocallyOwned): void {
		$this->_isLocallyOwned = $isLocallyOwned;
	}

	public function isLibraryOwned() : bool {
		return $this->_isLibraryOwned;
	}

	public function setIsLibraryOwned(bool $isLibraryOwned): void {
		$this->_isLibraryOwned = $isLibraryOwned;
	}
}