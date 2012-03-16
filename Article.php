<?php
class Article {
    private $originCharge;
    private $currentCharge;
    private $chargeChanged;
    private $isRedPencil = false;
    private $date;
    
    public function __construct($charge, Date $date) {
        $this->date = $date;
        $this->originCharge = $charge;
        $this->currentCharge = $charge;
    }
    
    public function getOriginCharge() {
        return $this->originCharge;
    }

    public function reduceCharge($amountToReduceInPercent) {
        $this->isRedPencil   = $this->setRedPencilState($amountToReduceInPercent);
        $this->currentCharge = $this->currentCharge / 100 * (100 - $amountToReduceInPercent);
        $this->chargeChanged = date("d.m.y",time());
    }
    
    public function increaseCharge($amountToIncreaseInPercent) {
        $this->isRedPencil   = $this->setRedPencilState();
        $this->currentCharge = $this->currentCharge / 100 * (100 + $amountToIncreaseInPercent);
        $this->chargeChanged = date("d.m.y",time());
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