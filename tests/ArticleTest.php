<?php
require_once dirname(__FILE__) . '/../Article.php';

class ArticleTest extends PHPUnit_Framework_TestCase {

    protected $article;
    protected $charge = 100.00;
    
    protected function setUp() {
        $this->article = new Article($this->charge);
    }

    protected function tearDown() {
        
    }

    public function testChargeIsValuePassedToConstructor() {
        $this->assertEquals($this->charge, $this->article->getOriginCharge());
    }
    
    public function testRedPencilReductionReducesByGivenValue() {
        $article = new Article(100.00);
        $article->reduceCharge(5);
        $this->assertEquals(95, $article->getRedPencilCharge());
        
        $article->reduceCharge(10);
        $this->assertEquals(90, $article->getRedPencilCharge());
        unset($article);
        
        
        $article = new Article(149.92);
        $article->reduceCharge(10);
        $this->assertEquals(134.928, $article->getRedPencilCharge());
        
        $article->reduceCharge(11.2);
        $this->assertEquals(133.12896, $article->getRedPencilCharge());
        
        $article->reduceCharge(45);
        $this->assertEquals(82.456, $article->getRedPencilCharge());
        unset($article);
        
        
        $article = new Article(12.13);
        $article->reduceCharge(19);
        $this->assertEquals(9.8253, $article->getRedPencilCharge());

        $article->reduceCharge(100);
        $this->assertEquals(0, $article->getRedPencilCharge());        

        $article->reduceCharge(63.65);
        $this->assertEquals(4.409255, $article->getRedPencilCharge());   
        unset($article);
    }
    
    public function testredPencilStateIsFalseWhenChargeIsReducedByLessThan5Percent() {
        $this->article->reduceCharge(3);
        $this->assertFalse($this->article->isRedPencil());
        
        $this->article->reduceCharge(4.51);
        $this->assertFalse($this->article->isRedPencil());
    }
    
    public function testredPencilStateIsTrueWhenChargeIsReducedByAtLeast5ButAtMost30Percent() {
        $this->article->reduceCharge(5);
        $this->assertTrue($this->article->isRedPencil());
        
        $this->article->reduceCharge(20);
        $this->assertTrue($this->article->isRedPencil());
        
        $this->article->reduceCharge(30);
        $this->assertTrue($this->article->isRedPencil());
        
        $this->article->reduceCharge(26.7);
        $this->assertTrue($this->article->isRedPencil());
    }

}

?>
