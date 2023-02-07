<?php
    class User {
        
        private int $id;
        private string $pseudo;
        private string $email;
        private string $password;
        private string $token;
        private ?string $avatar;
        private ?string $prenom;
        private ?string $nom;
        private int $online;
        private Datetime $dateInscription;

        public function __construct() {
            $this->id = 0;
            $this->pseudo = "";
            $this->email = "";
            $this->password = "";
            $this->token = "";
            $this->avatar = "";
            $this->prenom = "";
            $this->nom = "";
            $this->online = "0";
            $this->dateInscription = new Datetime();
        }

        /************************************************  MAGIC SETTER  *****************************************************/
        
        public function __set($property, $value) {
            if ($property == "date_inscription") {
                $this->dateInscription = new Datetime($value);
            } else {
               $this->$property = $value; 
            }           
        }

        /************************************************  MAGIC GETTER  *****************************************************/

        public function __get($property) {
            if ($property == "date_inscription") {
                return $this->dateInscription;
            } else {
              return $this->$property; 
            }   
        }

        /*************************************************  CONNEXION  ******************************************************/
        /**
            * @param string Email de l'utilisateur souhaitant se connecter
            * @param string Mot de passe en clair de l'utilisateur souhaitant se connecter
            * 
            * @return User l'utilisateur enregistré qui correspond aux critères
            */

        public function getPassword() {
            return $this->password;
        }

        public static function login(string $email, string $password) : User {

            try {
                $connexion = new PDO('mysql:host=localhost;dbname=testwayz;charset=utf8', 'root', '');
                $connexion-> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // On regarde si l'utilisateur est inscrit dans la table utilisateurs
                //$check = $connexion->prepare('SELECT * FROM utilisateurs WHERE email = :email AND actif = 1;');
                $check = $connexion->prepare('SELECT * FROM utilisateurs WHERE email = :email;');
                $check->bindParam(':email', $email, PDO::PARAM_STR);
                $check->execute();

                $data = $check->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "User");

                if ($check->rowCount()) {
                    $user = $data[0];
                    if(password_verify($password, $user->getPassword()))
                    {
                        return $user;

                    } else {
                        return new User();
                    }
                   
                } else {
                    return new User();
                }
            
            } catch(Exception $e) {

            return new User();
            }
        }

        /*************************************************  REGISTER  ****************************************************/ 

            /**
            * @param void Rien à passer en paramètre !
            * @return bool TRUE in case of success or FALSE in case of failure
            */

        public function creerCompte() : bool {
            $email = $this->email;
            $password = $this-> password;
            $pseudo = $this-> pseudo;
    
            // Requête 
            $sql = "INSERT INTO utilisateurs (email, password, pseudo) VALUES (:email, :password, :pseudo);";
                
            // Connexion à la base de données
            try {  
                $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
    
                $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
                $requete = $connexion->prepare($sql);
                $requete-> bindParam(":email", $email, PDO::PARAM_STR);
                $requete-> bindParam(":password", $password, PDO::PARAM_STR);
                $requete-> bindParam(":pseudo", $pseudo, PDO::PARAM_STR);
    
                return $requete-> execute();

            } catch (Exception $exc) {
                // echo $exc-> getMessage();
                return false;
            }
        }

        /*************************************************  RECHERCHE  *********************************************************************************/  

        /**
         * @param string $email Email de l'utilisateur souhaitant se connecter
         * 
         * @return bool TRUE si un compte portant cet email existe déjà, FALSE sinon
         */
        public static function exists(string $email): bool {
            // Requête SQL à executer
            $sql = "SELECT * FROM utilisateurs WHERE email LIKE :email;";

            try {
                // connexion à la base de données
                $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
    
                $requete = $connexion-> prepare($sql);
                $requete-> bindParam(":email", $email, PDO::PARAM_STR);
    
                $requete-> execute();

                $resultats = $requete-> fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");

                //var_dump($resultats);

                if (count($resultats) > 0) {
                    return true;
                } else {
                   return false;
                }

            } catch (Exception $exc) {
                //   echo $exc-> getMessage();

                // par sécurité et empêcher toute création de compte en cas de problème
                return true;
            }
        }

        /*************************************************  USER QUERY  *****************************************************************************/

        public static function queryUser(string $q): array {
            
            $q = "%$q%";
            $sql = "SELECT * FROM utilisateurs WHERE email LIKE :q OR pseudo LIKE :q OR prenom LIKE :q OR nom LIKE :q OR CONCAT(prenom, ' ', nom) LIKE :q;";

            try { 

                $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");

                $requete = $connexion->prepare($sql);
                $requete->bindParam(":q", $q, PDO::PARAM_STR);

                $requete-> execute();

                $resultats = $requete-> fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, "User"); 

                return $resultats;

            } catch (Exception $exc) {
                // echo $exc-> getMessage();
                return array();
            }
        }

        /******************************************************  FIND BY ID   ****************************************************************************/

        /**
         * @param int $id Identifiant de l'utilisateur à retrouver
         * 
         * @return User L'utilisateur qui possède l'identifiant fourni en argument
         */
        public static function findByiD(int $id): User {
            // Requête SQL à executer
            $sql = "SELECT * FROM utilisateurs WHERE id = :id;";

            try {
                // connexion à la base de données
                $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
    
                $requete = $connexion-> prepare($sql);
                $requete-> bindParam(":id", $id, PDO::PARAM_INT);
    
                $requete-> execute();

                $resultats = $requete-> fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, "User");

                //var_dump($resultats);

                if (count($resultats) > 0) {
                    return $resultats[0];
                } else {
                   return new User();
                }

            } catch (Exception $exc) {
                //   echo $exc-> getMessage();

                return new User();
            }
        }

        /**************************************************  FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID  *********************************************/

        function all_users($id){

            $sql = "SELECT id, pseudo, avatar FROM utilisateurs WHERE id != ?";

        try{

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");

            $requete = $connexion->prepare($sql);
            $requete-> bindParam(":id", $id, PDO::PARAM_INT);
            $requete-> execute([$id]);

            if($requete->rowCount() > 0){

                return $requete->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }

        catch (PDOException $e) {

            die($e->getMessage());
        }
    }

    /*********************************************************** IMAGE EXISTS **********************************************************************/

    public static function check_image_exists($url, $default = 'default.jpg') {

        $url = trim($url);
        $info = @getimagesize($url);

        if ((bool) $info) {
            return $url;
        } else {
            return $default;
        }
    }

    // /***********************************************************  USER STATUS  **********************************************************************/

    // public function userStatus() : string {
    //     if ($this->online == 0) {
    //         return "Not Logged In";
    //     } else {
    //         return "Logged In";
    //     }
    // }
}

    
    