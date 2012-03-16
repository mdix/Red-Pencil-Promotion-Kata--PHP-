<?php

class Article {
    private $originCharge;
    private $currentCharge;
    private $chargeChanged;
    private $isRedPencil = false;
    
    public function __construct($charge) {
        $this->originCharge = $charge;
        $this->currentCharge = $charge;
    }
    
    public function getOriginCharge() {
        return $this->originCharge;
    }

    public function reduceCharge($amountToReduceInPercent) {
        $this->isRedPencil   = $this->setRedPencilState($amountToReduceInPercent);
        $this->currentCharge = $this->currentCharge / 100 * (100 - $amountToReduceInPercent);
    }
    
    public function increaseCharge($amountToIncreaseInPercent) {
        $this->isRedPencil   = $this->setRedPencilState();
        $this->currentCharge = $this->currentCharge / 100 * (100 + $amountToIncreaseInPercent);
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
        
        // reduced by min 5 but max 30 percent?
        if ($amountToReduceInPercent >= 5 && $amountToReduceInPercent <= 30) {
            return true;
        }
        return false;
    }
}

?>