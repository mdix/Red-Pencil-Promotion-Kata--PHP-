<?php

class Article {
    private $charge;
    private $currentCharge;
    private $reducedDate;
    private $isRedPencil = false;
    
    public function __construct($charge) {
        $this->charge = $charge;
    }
    
    public function getOriginCharge() {
        return $this->charge;
    }

    public function reduceCharge($amountToReduceInPercent) {
        $this->isRedPencil   = $this->setRedPencilState($amountToReduceInPercent);
        $this->currentCharge = $this->charge / 100 * (100 - $amountToReduceInPercent);
    }
    
    public function increaseCharge($amountToIncreaseInPercent) {
        $this->removeRedPencilState();
        $this->currentCharge = $this->charge / 100 * (100 + $amountToIncreaseInPercent);
    }
    
    public function getCurrentCharge() {
        return $this->currentCharge;
    }
    
    public function isRedPencil() {
        return $this->isRedPencil;
    }
    
    private function setRedPencilState($amountToReduceInPercent) {
        if ($amountToReduceInPercent >= 5 && $amountToReduceInPercent <= 30) {
            return true;
        }
        return false;
    }
    
    private function removeRedPencilState() {
        $this->isRedPencil = false;
        $this->reducedDate = null;
    }
}

?>