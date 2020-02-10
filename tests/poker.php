<?php

use PHPUnit\Framework\TestCase;

class poker extends TestCase {
    public function testCard() {
        $card = new Card("QH");
        $this->assertEquals("QH", Card::val2code($card->get_card()));
    }
    
    public function testHand() {
        $this->expectOutputString("Hand: 9C 4D 8D TH QH AH 9S \nHand: 4D 8D 9C 9S TH QH AH \n");

        $hand = new Hand();
        $hand->addCard(new Card("9S"));
        $hand->addCard(new Card("4D"));
        $hand->addCard(new Card("TH"));
        $hand->addCard(new Card("9C"));
        $hand->addCard(new Card("8D"));
        $hand->addCard(new Card("AH"));
        $hand->addCard(new Card("QH"));
        $hand->display();
        $hand->sortByValue();
        $hand->display();
    }
    
    public function testStraight() {
        $hand = new Hand();
        $hand->addCard(new Card("3S"));
        $hand->addCard(new Card("4D"));
        $hand->addCard(new Card("5H"));
        $hand->addCard(new Card("6C"));
        $hand->addCard(new Card("7D"));
        $hand->addCard(new Card("AH"));
        $hand->addCard(new Card("QH"));
        $this->assertFalse($hand->hasStraight(5, true));
        $this->assertTrue($hand->hasStraight(5, false));
    }
    
    public function testDeck() {
        $this->expectOutputString("AS");
        $deck = new Deck();
        $card = $deck->dealOne();
        $card->display();
    }

    public function testDeckShuffle() {
        $deck = new Deck();
        $deck->shuffle();
        $card = $deck->dealOne();
        $card->display();
        
        $val = $this->getActualOutput();
        $this->assertNotEquals($val, "AS");
    }
}

?>