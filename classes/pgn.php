<?php 
namespace Chess;
class PGNGame {
  public $move_string = '';
  public $parsed = false;
  public $moves = null;

  public function parse() {

    preg_match_all("/\s*(\d+)\.\s*([\da-hKQRBN]+)\s+([\da-hKQRBN]+)/",$this->move_string,$matches);
    //print_r($matches);
    if ( ! empty($matches[0]) ) {
      $this->moves = array();
      foreach( $matches[1] as $j=>$i) {
        $this->moves[$i - 1] = array($matches[2][$j],$matches[3][$j]);
      }
    }
    $this->parsed = true;
    return $this;
  }

}
class PGN {
  public $moves = null;
  public $info = null;
  public $file = null;
  public $lines = null;

  public function __construct() {
    $this->moves = array();
    $this->info = array();
    $this->file = '';
  }
  public function load($fname) {
    if ( ! file_exists($fname) ) {
      die("PGN: file path $fname does not exist.\n");
    }
    $this->lines = file($fname);
    return $this;
  }
  public function parse() {
    if ( !$this->lines) {
      die("PGN: No lines to parse.\n");
    }
    $this->games = array(new PGNGame());
    $i = 0;
    $state = 0;
    foreach ( $this->lines as $line) {
      preg_match("/^\[([A-Za-z]+)\s+\"(.+)\"\]\s*$/",$line,$matches);
      if ( ! empty( $matches ) ) {
        if ( $state == 1 ) {
          $i++;
          $this->games[$i] = new PGNGame();
        }
        $this->games[$i]->{$matches[1]} = $matches[2];
      } else {
        // Okay, not a tag, what about algebra
        preg_match("/^\s*\d+\.\s*[a-hQRNKB\d]+/",$line,$matches);
        if ( ! empty( $matches ) ) {
          $state = 1;
          $this->games[$i]->move_string .= " $line";
        }
      }
    }
    foreach ($this->games as &$game) {
      if ( ! $game->parsed ) {
        $game->parse();
      }
    }
    print_r($this->games);
    return $this;
  }
}