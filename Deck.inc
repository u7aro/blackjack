<?php

class Deck {
  private $cards = [];

  function __construct() {
    $this->generateCards();
    shuffle($this->cards);
  }

  function generateCards($num_decks = 1) {
    // Symbol meaning
    // S: Spade, D: Diamond, H: Heart, C: Club
    $suits = ['S', 'D', 'H', 'C'];
    foreach ($suits as $suit) {
      for ($card_i = 1; $card_i <= 13; $card_i++) {
        $card = $suit . '-' . $card_i;
        $this->cards[] = $card;
      }
    }
  }

  function pullCard() {
    return array_shift($this->cards);
  }

  function getCards() {
    return $this->cards;
  }
}