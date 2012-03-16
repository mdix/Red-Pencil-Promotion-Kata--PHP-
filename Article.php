<?php
class Article {
    private $originCharge;
    private $currentCharge;
    private $chargeChanged;
    private $isRedPencil = false;
    private $timestamp;
    
    public function __construct($charge, Timestamp $date) {
        $this->timestamp = $date;
        $this->originCharge = $charge;
        $this->currentCharge = $charge;
    }
    
    public function getOriginCharge() {
        return $this->originCharge;
    }

    public function reduceCharge($amountToReduceInPercent) {
        $this->isRedPencil   = $this->setRedPencilState($amountToReduceInPercent);
        $this->currentCharge = $this->currentCharge / 100 * (100 - $amountToReduceInPercent);
        $this->chargeChanged = time();
    }
    
    public function increaseCharge($amountToIncreaseInPercent) {
        $this->isRedPencil   = $this->setRedPencilState();
        $this->currentCharge = $this->currentCharge / 100 * (100 + $amountToIncreaseInPercent);
        $this->chargeChanged = time();
    }
    
    public function getCurrentCharge() {
        return $this->currentCharge;
    }
    
    public function isRedPencil() {
        return $this->isRedPencil;
    }
    
    private function setRedPencilState($amountToReduceInPercent = null) {
        if (null == $amountToReduceInPercent) {
            return false;
        }
        // changed within the last 30 days?
        if (null !== $this->chargeChanged) {
            if (($this->timestamp->getCurrentTimestamp() - $this->chargeChanged) / 86400 >= 30) {
                return false;
            }
        }
        // reduced by min 5 but max 30 percent?
        if ($amountToReduceInPercent >= 5 && $amountToReduceInPercent <= 30) {
            return true;
        }
        return false;
    }
}

?>