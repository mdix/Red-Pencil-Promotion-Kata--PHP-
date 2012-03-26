<?php
class Article {
    private $originCharge;
    private $currentCharge;
    private $chargeChanged;
    private $isRedPencil = false;
    private $timestamp;
    
    public function __construct($charge, Timestamp &$timestamp) {
        $this->timestamp = $timestamp;
        $this->originCharge = $charge;
        $this->currentCharge = $charge;
    }
    
    public function getOriginCharge() {
        return $this->originCharge;
    }

    public function reduceCharge($amountToReduceInPercent) {
        $this->currentCharge = $this->currentCharge / 100 * (100 - $amountToReduceInPercent);
        $this->isRedPencil   = $this->setRedPencilState($amountToReduceInPercent);
        $this->chargeChanged = time();
    }
    
    public function increaseCharge($amountToIncreaseInPercent) {
        $this->currentCharge = $this->currentCharge / 100 * (100 + $amountToIncreaseInPercent);
        $this->isRedPencil   = $this->setRedPencilState();
        $this->chargeChanged = time();
    }
    
    public function getCurrentCharge() {
        return $this->currentCharge;
    }
    
    // Thought: Why a state? Should check when needed with a method similar to setRedPencilState().
    public function isRedPencil() {
        return $this->isRedPencil;
    }
    
    private function setRedPencilState($amountToReduceInPercent = null) {
        if (null == $amountToReduceInPercent) {
            return false;
        }
 
        if ($this->chargeNotStableFor30Days()) {
            return false;
        }
        
        if ($this->overallReductionHigherThan30Percent()) {
            return false;
        }

        if ($this->reductionLowerThan5Percent($amountToReduceInPercent) || $this->reductionHigherThan30Percent($amountToReduceInPercent)) {
            return false;
        }
        return true;
    }
    
    private function getOverallReductionInPercent($charge1, $charge2) {
        return ($charge2 * 100) / $charge1 - 100;
    }
    
    private function chargeNotStableFor30Days() {
        if (null !== $this->chargeChanged) {
            if (($this->timestamp->getCurrentTimestamp() - $this->chargeChanged) / 86400 < 30) {
                return true;
            }
        }
        return false;
    }
    
    private function overallReductionHigherThan30Percent() {
        if ($this->getOverallReductionInPercent($this->originCharge, $this->currentCharge) < -30) {
            return true;
        }
        return false;
    }
    
    private function reductionHigherThan30Percent($amountToReduceInPercent) {
        if ($amountToReduceInPercent <= 30) {
            return false;
        }
        return true;
    }
    
    private function reductionLowerThan5Percent($amountToReduceInPercent) {
        if ($amountToReduceInPercent >= 5) {
            return false;
        }
        return true;
    }
}

?>