<?php 
    /**
     * @Entity
     * @Table(name=projects)
     * annotations "façon" ORM comme Doctrine
     */
    class Project {
        /**
         * @Column(name=projectId, type=int)
         */
        private int $id;

        /**
         * @Column(name=name, type=varchar, size=120)
         */
        private string $name;

        /**
         * @Column(name=projectGenre, type=varchar, size=120)
         */
        private ?string $genre;

        /**
         * @Column(name=date_creation, type=datetime)
         */
        private DateTime $dateCreation;

        /**
         * @Column(name=projectCover, type=varchar, size=255, nullable=true)
         */
        private ?string $cover;    // project cover
        
        /**
         * @Column(name=id, type=int)
         */
        private int $user;  // id user

        public function __construct() {
            $this-> id = 0;
            $this-> name = "";
            $this-> genre = null;
            $this-> dateCreation = new DateTime();
            $this-> cover = null;
            $this-> user = 0;
        }

        public function __set($property, $value) {
            if ($property == "date_creation") {
                $this-> dateCreation = new DateTime($value);
            } else {
                $this-> $property = $value;
            }
        }

        public function __get($property) {
            if ($property == "date_creation") {
                return $this-> dateCreation;
            } else {
                return $this-> $property;
            }
        }

        /*************************************************  Add Project  ****************************************************/ 

            /**
            * @param void Rien à passer en paramètre !
            * @return bool TRUE in case of success or FALSE in case of failure
            */

            public function addProject() : bool {
                $projectName = $this->name;
                $projectCover = $this->cover;
                $userId = $this-> user;
        
                // Requête 
                $sql = "INSERT INTO projects (name, cover, user) VALUES (:name, :cover, :user);";
                    
                // Connexion à la base de données
                try {  
                    $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
        
                    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                    $requete = $connexion->prepare($sql);
                    $requete-> bindParam(":name", $projectName, PDO::PARAM_STR);
                    $requete-> bindParam(":cover", $projectCover, PDO::PARAM_STR);
                    $requete-> bindParam(":user", $userId, PDO::PARAM_STR);
                    return $requete-> execute();
    
                } catch (Exception $exc) {
                    // echo $exc-> getMessage();
                    return false;
                }
            }


        /**********************************************************  FIND ALL  **************************************************************************/


        public static function findAll(): array {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM projects ORDER BY projects.id DESC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Project"); 

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

        /******************************************************  FIND BY ID   ****************************************************************************/

        /**
         * @param int $project_id Identifiant du projet à retrouver
         * 
         * @return Project Le projet qui possède l'identifiant fourni en argument
         */
        public static function findByiD(int $projectId): Project {
            // Requête SQL à executer
            $sql = "SELECT * FROM projects WHERE id = :project_id;";

            try {
                // connexion à la base de données
                $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
    
                $requete = $connexion-> prepare($sql);
                $requete-> bindParam(":project_id", $projectId, PDO::PARAM_INT);
    
                $requete-> execute();

                $resultats = $requete-> fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "Project");

                //var_dump($resultats);

                if (count($resultats) > 0) {
                    return $resultats[0];
                } else {
                   return new Project();
                }

            } catch (Exception $exc) {
                //   echo $exc-> getMessage();

                return new Project();
            }
        }

        /**************************************************** FIND BY USER ******************************************************************************/

        public static function findUserProject(int $user) {
            require_once __DIR__ . "/database.class.php";

            $sql = "SELECT * FROM projects WHERE user=:user_id ORDER BY id ASC;";
                    
            try {
                $db = Database::dbConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $query = $db-> prepare($sql);
                $query-> bindParam("user_id", $user, PDO::PARAM_INT);
    
                if ($query-> execute()) {
                    $resultats = $query-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "Project"); 

                    // var_dump($resultats);
                    // die();
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