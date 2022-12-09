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
        private int $post_id;

        /**
         * @Column(name=message, type=text)
         */
        private string $message;

        /**
         * @Column(name=posted_at, type=datetime)
         */
        private DateTime $postedAt;

        /**
         * @Column(name=attachment, type=varchar, size=255, nullable=true)
         */
        private ?string $attachment;    // path pièce jointe
        
        /**
         * @Column(name=id, type=int)
         */
        private int $id;  // id expéditeur

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
                return $this-> postedAt;
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

            $sql = "INSERT INTO posts (message, attachment, id) VALUES (:message, :attachment, :id);";

            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
    
                $query-> bindParam("message", $this-> message, PDO::PARAM_STR);
                $query-> bindParam("attachment", $this-> attachment, PDO::PARAM_STR);
                $query-> bindParam("id", $this->id, PDO::PARAM_STR);
    
                return $query-> execute();
            } catch (Exception | PDOException | Error $e ) {
                //die($e-> getMessage());
                return false;
            }
        }
    }