<?php
    class Like {
        
        private int $id;
        private Datetime $likedAt;
        private int $id_post;
        private int $user_id;

        public function __construct() {
            $this->id = 0;
            $this->id_post = 0;
            $this->user_id = 0;
            $this->dateInscription = new Datetime();
        }

        // ************   GETTERS SETTERS  ************//

        public function __set($property, $value) {
            if ($property == "liked_at") {
                $this-> likedAt = new DateTime($value);
            } else {
                $this-> $property = $value;
            }
        }

        public function __get($property) {
            if ($property == "liked_at") {
                return $this-> likedAt-> format("Y-m-d");
            } else {
                return $this-> $property;
            }
        }

        /******************************************************* ADD A LIKE TO A POST *************************************************************/

        /**
        * @param void Rien à passer en paramètre !
        */

        public static function AddLike($id_post, $user_id) {
            require_once __DIR__ . "/database.class.php";

            $sql = "INSERT INTO likes (id_post, user_id) VALUES (:id_post, :user_id);";

            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
    
                $query-> bindParam(":id_post", $id_post, PDO::PARAM_STR);
                $query-> bindParam(":user_id", $id_post, PDO::PARAM_STR);
    
                return $query-> execute();
            } catch (Exception | PDOException | Error $e ) {
                //die($e-> getMessage());
                return false;
            }
        }

    }