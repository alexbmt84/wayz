<?php
    class Alert {
// Attribut de classe = propriété encapsulées. // Données membres de la classe (public string).
        private string $title;                       
        private string $body;
        private string $footer;

// POO : Méthode de classe

// ACCESSEURS :

// - Setters

        public function setTitle(string $texte) {
            $this->title = $texte;
           // $self = class
        }

        public function setBody(string $texte) {
            $this->body = $texte;
           // $self = class
        }

// - Getters

        public function getTitle() {
            return $this->title;
        }

        public function getBody() {
            return $this->body;
        }

// Méthode magique qui est appelée automatiquement par echo

        public function __toString() {
            $text = "<div class='alert alert-danger' role='alert'>
                    <h4>{$this->title}</h4>
                        {$this->body}
                    </div>";
            return $text;
            
        }
    }