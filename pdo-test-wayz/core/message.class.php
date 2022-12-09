<?php 
    /**
     * @Entity
     * @Table(name=messages)
     * annotations "façon" ORM comme Doctrine
     */
    class Message {
        /**
         * @Column(name=id, type=int)
         */
        private int $id;

        /**
         * @Column(name=subject, type=varchar, size=120)
         */
        private string $subject;

        /**
         * @Column(name=message, type=text)
         */
        private string $message;

        /**
         * @Column(name=created_at, type=datetime)
         */
        private DateTime $dateEnvoi;

        /**
         * @Column(name=attachment, type=varchar, size=255, nullable=true)
         */
        private ?string $attachment;    // path pièce jointe
        
        /**
         * @Column(name=from, type=int)
         */
        private int $_from;  // id expéditeur

        /**
         * @Column(name=to, type=int)
         */
        private int $_to;    // id destinataire

        public function __construct() {
            $this-> id = 0;
            $this-> subject = "";
            $this-> message = "";
            $this-> dateEnvoi = new DateTime();
            $this-> attachment = null;
            $this-> _from = 0;
            $this-> _to = 0;
        }

        public function __set($property, $value) {
            if ($property == "created_at") {
                $this-> dateEnvoi = new DateTime($value);
            } else {
                $this-> $property = $value;
            }
        }

        public function __get($property) {
            if ($property == "created_at") {
                return $this-> dateEnvoi;
            } else {
                return $this-> $property;
            }
        }

        public function send(): bool {
            require_once __DIR__ . "/database.class.php";

            $sql = "INSERT INTO messages (message, attachment, _from, _to) VALUES (:message, :attachment, :from, :to);";

            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
    
                $query-> bindParam("message", $this-> message, PDO::PARAM_STR);
                $query-> bindParam("attachment", $this-> attachment, PDO::PARAM_STR);
                $query-> bindParam("from", $this-> _from, PDO::PARAM_STR);
                $query-> bindParam("to", $this-> _to, PDO::PARAM_STR);
    
                return $query-> execute();
            } catch (Exception | PDOException | Error $e ) {
                //die($e-> getMessage());
                return false;
            }
        }

        public static function findByFrom(int $from) {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM messages WHERE from=:from ORDER BY id DESC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindParam("from", $from, PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Message"); 

                    return $resultats;
                } else {
                    return array();
                }
            } catch (Exception | PDOException | Error $e ) {
                die($e-> getMessage());
                return array();
            }
        }

        public static function findByTo(int $to) {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM messages WHERE to=:to ORDER BY id DESC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindParam("to", $to, PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Message"); 

                    return $resultats;
                } else {
                    return array();
                }
            } catch (Exception | PDOException | Error $e ) {
                die($e-> getMessage());
                return array();
            }
        }

        public static function findByFromAndTo(int $from, int $to): array {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM messages WHERE _from=:from AND _to=:to ORDER BY id ASC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindParam("from", $from, PDO::PARAM_INT);
                $query-> bindParam("to", $to, PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Message"); 

                    return $resultats;
                } else {
                    return array();
                }
            } catch (Exception | PDOException | Error $e ) {
                die($e-> getMessage());
                return array();
            }
        }

        public static function findUserMessage(): array {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM messages WHERE CASE WHEN _from = 1 THEN _to = :_from WHEN _from = :_from THEN _to = 1 END;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindValue(':_from', $_SESSION['user-id'], PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Message"); 

                    return $resultats;
                } else {
                    return array();
                }
            } catch (Exception | PDOException | Error $e ) {
                die($e-> getMessage());
                return array();
            }
        }

        public static function findMessageByFromAndTo(int $expediteur, int $destinataire): array {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM messages WHERE (_from=:from OR _from=:to) AND (_to=:from OR _to=:to) ORDER BY created_at ASC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindParam(':from', $expediteur, PDO::PARAM_INT);
                $query-> bindParam(':to', $destinataire, PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Message"); 

                    return $resultats;
                } else {
                    return array();
                }
            } catch (Exception | PDOException | Error $e ) {
                die($e-> getMessage());
                return array();
            }
        }

        public static function findAll(): array {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM messages ORDER BY id DESC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Message"); 

                    // Pour débugage seulement...
                    // var_dump($resultats);
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