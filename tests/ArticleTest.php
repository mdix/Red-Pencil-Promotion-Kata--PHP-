<?php
require_once dirname(__FILE__) . '/../Article.php';

date_default_timezone_set ('Europe/Berlin');
class Timestamp {
    private $timestamp;
    
    public function __construct() {
        $this->timestamp = time();
    }
    
    public function getCurrentTimestamp() {
        return $this->timestamp;
    }
    
    public function addDaysToCurrentTimestamp($days) {
        $daysAsSeconds = $days * 86400;
        $this->timestamp = $this->timestamp + $daysAsSeconds;
    }
}

class ArticleTest extends PHPUnit_Framework_TestCase {

    protected $article;
    protected $charge = 100.00;
    
    protected function setUp() {
        $this->article = new Article($this->charge, new Timestamp());
    }

    protected function tearDown() {

    }

    public function testChargeIsValuePassedToConstructor() {
        $this->assertEquals($this->charge, $this->article->getOriginCharge());
    }
    
    public function testRedPencilReductionReducesCurrentChargeByGivenValue() {
        $article = new Article(100.00, new Timestamp());
        $article->reduceCharge(5);
        $this->assertEquals(95, $article->getCurrentCharge());
        
        $article->reduceCharge(10);
        $this->assertEquals(85.5, $article->getCurrentCharge());
        unset($article);
        
        
        $article = new Article(149.92, new Timestamp());
        $article->reduceCharge(10);
        $this->assertEquals(134.928, $article->getCurrentCharge());
        
        $article->reduceCharge(11.2);
        $this->assertEquals(119.816064, $article->getCurrentCharge());
        
        $article->reduceCharge(45);
        $this->assertEquals(65.8988352, $article->getCurrentCharge());
        unset($article);
        
        
        $article = new Article(12.13, new Timestamp());
        $article->reduceCharge(19);
        $this->assertEquals(9.8253, $article->getCurrentCharge());

        $article->reduceCharge(100);
        $this->assertEquals(0, $article->getCurrentCharge());        

        $article->reduceCharge(63.65);
        $this->assertEquals(0, $article->getCurrentCharge());   
        unset($article);
    }
    
    public function testIncreaseChargeIncreasesCurrentChargeByGivenPercentValue() {
        $this->article = new Article(100.00, new Timestamp());
        $this->article->increaseCharge(5);
        $this->assertEquals(105, $this->article->getCurrentCharge());
        
        $this->article->increaseCharge(15);
        $this->assertEquals(120.75, $this->article->getCurrentCharge());
        unset($this->article);
    }
    
    public function testMultipleIncreaseAndDecreaseOperations() {
        $this->article->increaseCharge(10);
        $this->assertEquals(110, $this->article->getCurrentCharge());
        $this->article->increaseCharge(5);
        $this->assertEquals(115.5, $this->article->getCurrentCharge());
        $this->article->reduceCharge(8);
        $this->assertEquals(106.26, $this->article->getCurrentCharge());
        $this->article->increaseCharge(2);
        $this->assertEquals(108.3852, $this->article->getCurrentCharge());
        $this->article->increaseCharge(18);
        $this->assertEquals(127.894536, $this->article->getCurrentCharge());
        $this->article->reduceCharge(22);
        $this->assertEquals(99.75773808, $this->article->getCurrentCharge());
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
        
        /* Tests won't work due to changed requirements (price wasn't stable for at least 30 days 
         * between changes
        $this->article->reduceCharge(20);
        $this->assertTrue($this->article->isRedPencil());
        
        $this->article->reduceCharge(30);
        $this->assertTrue($this->article->isRedPencil());
        
        $this->article->reduceCharge(26.7);
        $this->assertTrue($this->article->isRedPencil());
        */
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
    
    public function testPriceWasStableFor30DaysArticleShouldBeRedPencil() {
        $timestamp = new Timestamp();
        $article = new Article(100.00, $timestamp);
        
        $article->increaseCharge(3);
        $timestamp->addDaysToCurrentTimestamp(30);
        $article->reduceCharge(20);
        $this->assertTrue($article->isRedPencil());
    }
    
    public function testPriceWasntStableFor30DaysArticleShouldntBeRedPencil() {
        $timestamp = new Timestamp();
        $article = new Article(100.00, $timestamp);
        
        $article->increaseCharge(3);
        $timestamp->addDaysToCurrentTimestamp(29);
        $article->reduceCharge(20);
        $this->assertFalse($article->isRedPencil());
    }
    
    public function testOverallReductionIsMoreThan30PercenArticleShouldntBeRedPencil() {
        $timestamp = new Timestamp();
        $article = new Article(100.00, $timestamp);
        
        $article->reduceCharge(5);
        $timestamp->addDaysToCurrentTimestamp(49);
        $article->reduceCharge(29);
        $this->assertFalse($article->isRedPencil());
    }
}

?>
