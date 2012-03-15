<?php

class Article {
    private $charge;
    private $reducedCharge;
    private $reducedDate;
    private $isRedPencil = false;
    
    public function __construct($charge) {
        $this->charge = $charge;
    }
    
    public function getOriginCharge() {
        return $this->charge;
    }

    public function reduceCharge($amountToReduceInPercent) {
        $this->isRedPencil = $this->setRedPencilState($amountToReduceInPercent);
        $this->reducedCharge = $this->charge / 100 * (100 - $amountToReduceInPercent);
    }
    
    public function getRedPencilCharge() {
        return $this->reducedCharge;
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
}

?>
