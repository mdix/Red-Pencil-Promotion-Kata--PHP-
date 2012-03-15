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
    
    public function testRedPencilReductionReducesCurrentChargeByGivenValue() {
        $article = new Article(100.00);
        $article->reduceCharge(5);
        $this->assertEquals(95, $article->getCurrentCharge());
        
        $article->reduceCharge(10);
        $this->assertEquals(90, $article->getCurrentCharge());
        unset($article);
        
        
        $article = new Article(149.92);
        $article->reduceCharge(10);
        $this->assertEquals(134.928, $article->getCurrentCharge());
        
        $article->reduceCharge(11.2);
        $this->assertEquals(133.12896, $article->getCurrentCharge());
        
        $article->reduceCharge(45);
        $this->assertEquals(82.456, $article->getCurrentCharge());
        unset($article);
        
        
        $article = new Article(12.13);
        $article->reduceCharge(19);
        $this->assertEquals(9.8253, $article->getCurrentCharge());

        $article->reduceCharge(100);
        $this->assertEquals(0, $article->getCurrentCharge());        

        $article->reduceCharge(63.65);
        $this->assertEquals(4.409255, $article->getCurrentCharge());   
        unset($article);
    }
    
    public function testIncreaseChargeIncreasesCurrentChargeByGivenPercentValue() {
        $article = new Article(100.00);
        $article->increaseCharge(5);
        $this->assertEquals(105, $article->getCurrentCharge());
        
        $article->increaseCharge(15);
        $this->assertEquals(115, $article->getCurrentCharge());
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
    
    public function testredPencilStateIsFalseWhenChargeIsReducedByMoreThan30Percent() {
        $this->article->reduceCharge(30.1);
        $this->assertFalse($this->article->isRedPencil());
        
        $this->article->reduceCharge(84);
        $this->assertFalse($this->article->isRedPencil());
        
        $this->article->reduceCharge(37.65);
        $this->assertFalse($this->article->isRedPencil());
    }   

    public function testRedPencilStateIsResettedOnChargeIncrease() {
        $this->article->reduceCharge(10);
        $this->assertTrue($this->article->isRedPencil());
        $this->article->increaseCharge(15.3);
        $this->assertFalse($this->article->isRedPencil());
    }
    
}

?>
