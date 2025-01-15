<?php
    class Quiz{
        private $name;
        private $creator;
        private $questions;
        private $options;
        private $answer;

        public function __construct($name, $creator, $questions){
            $this->name = $name;
            $this->creator = $creator;
            $this->questions = $questions;
        }

        public function getName(){
            return $this->name;
        }

        public function getCreator(){
            return $this->creator;
        }

        public function getQuestions(){
            return $this->questions;
        }

    }
?>