<?php
    /**
     * @Entity
     * @Table(name=posts)
     * annotations "façon" ORM comme Doctrine
     */
    class Post {
        /**
         * @Column(name=post_id, type=int)
         */
        private int $post_id; // Id du post

        /**
         * @Column(name=message, type=text)
         */
        private string $message; // Message du post

        /**
         * @Column(name=posted_at, type=datetime)
         */
        private DateTime $postedAt; // Date de création du post

        /**
         * @Column(name=attachment, type=varchar, size=255, nullable=true)
         */
        private ?string $attachment;    // Path pièce jointe
        
        /**
         * @Column(name=id, type=int)
         */
        private int $id;  // Id de l'expéditeur

        public function __construct() {
            $this-> post_id = 0;
            $this-> message = "";
            $this-> postedAt = new DateTime();
            $this-> attachment = null;
            $this-> id = 0;
        }

        public function __set($property, $value) {
            if ($property == "posted_at") {
                $this-> postedAt = new DateTime($value);
            } else {
                $this-> $property = $value;
            }
        }

        public function __get($property) {
            if ($property == "posted_at") {
                return $this-> postedAt-> format("Y-m-d");
            } else {
                return $this-> $property;
            }
        }

        

        /**************************************** Some Posts Stuff. [ Publishing, Retreiving, Checking Existence ] *******************************************/

        /******************************************************* Inserting New Posts To DataBase *************************************************************/

        /**
        * @param void Rien à passer en paramètre !
        * @return bool TRUE in case of success or FALSE in case of failure
        */

        public function publishPost(): bool {
            require_once __DIR__ . "/database.class.php";

            $message = $this->message; // initialisation des variables ($this = cette classe (Event)).
            $attachment = $this-> attachment;
            $idUser = $this-> id;

            $sql = "INSERT INTO posts (message, attachment, id) VALUES (:message, :attachment, :id);";

            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
    
                $query-> bindParam(":message", $this-> message, PDO::PARAM_STR);
                $query-> bindParam(":attachment", $attachment, PDO::PARAM_STR);
                $query-> bindParam(":id", $this->id, PDO::PARAM_STR);
    
                return $query-> execute();
            } catch (Exception | PDOException | Error $e ) {
                //die($e-> getMessage());
                return false;
            }
        }

        /******************************************************* FIND FRIENDS POSTS *************************************************************/

        /**
        * @param void Rien à passer en paramètre !

        */

        public static function findFriendsPosts(int $id) {
            require_once __DIR__ . "/database.class.php";


            $sql = "SELECT posts.*, utilisateurs.pseudo, utilisateurs.avatar, utilisateurs.online
                    FROM `posts` INNER JOIN friends 
                    ON (friends.user_one = posts.id AND friends.user_two = :my_id) 
                    OR (friends.user_two = posts.id AND friends.user_one = :my_id) 
                    INNER JOIN utilisateurs
                    ON posts.id = utilisateurs.id
                    ORDER BY id DESC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindParam(":my_id", $id, PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Post"); 

                    return $resultats;
                } else {
                    return array();
                }
            } catch (Exception | PDOException | Error $e ) {
                die($e-> getMessage());
                return array();
            }
        }

        /******************************************************* FIND FRIENDS POSTS *************************************************************/

        /**
        * @param void Rien à passer en paramètre !

        */

        public static function findUserPosts(int $id) {
            require_once __DIR__ . "/database.class.php";


            $sql = "SELECT * FROM posts
                    WHERE id = :my_id
                    ORDER BY id DESC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindParam(":my_id", $id, PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Post"); 

                    return $resultats;
                } else {
                    return array();
                }
            } catch (Exception | PDOException | Error $e ) {
                die($e-> getMessage());
                return array();
            }
        }
    }