<?php
class Friend{

    // CHECK IF ALREADY FRIENDS
    public function is_already_friends($my_id, $user_id){
        try{
            $sql = "SELECT * FROM friends WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $requete = $connexion->prepare($sql);
            $requete->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $requete->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $requete->execute();

            if($requete->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        
    }

    //  IF I AM THE REQUEST SENDER
    public function am_i_the_req_sender($my_id, $user_id){
        try{

            $sql = "SELECT * FROM friend_request WHERE sender = ? AND receiver = ?";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $requete = $connexion->prepare($sql);
            $requete->execute([$my_id, $user_id]);

            if($requete->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //  IF I AM THE RECEIVER 
    public function am_i_the_req_receiver($my_id, $user_id){
        
        try{
            $sql = "SELECT * FROM friend_request WHERE sender = ? AND receiver = ?";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $requete = $connexion->prepare($sql);
            $requete->execute([$user_id, $my_id]);

            if($requete->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // CHECK IF REQUEST HAS ALREADY BEEN SENT
    public function is_request_already_sent($my_id, $user_id){
        
        try{
            $sql = "SELECT * FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $requete = $connexion->prepare($sql);
            $requete->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $requete->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $requete->execute();
    
            if($requete->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // MAKE PENDING FRIENDS (SEND FRIEND REQUEST)
    public function make_pending_friends($my_id, $user_id){
        
        try{
            $sql = "INSERT INTO friend_request (sender, receiver) VALUES(?,?)";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $requete = $connexion->prepare($sql);
            $requete->execute([$my_id, $user_id]);
            header('Location: user_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // CANCEL FRIEND REQUEST
    public function cancel_or_ignore_friend_request($my_id, $user_id){
        
        try{
            $sql = "DELETE FROM friend_request WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $requete = $connexion->prepare($sql);
            $requete->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $requete->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $requete->execute();
            header('Location: user_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // MAKE FRIENDS
    public function make_friends($my_id, $user_id){
        
        try{

            $delete_pending_friends = "DELETE FROM friend_request WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");

            $delete_requete = $connexion->prepare($delete_pending_friends);
            $delete_requete->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $delete_requete->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_requete->execute();
            if($delete_requete->execute()){

                $sql = "INSERT INTO friends (user_one, user_two) VALUES(?, ?)";

                $requete = $connexion->prepare($sql);
                $requete->execute([$my_id, $user_id]);
                header('Location: user_profile.php?id='.$user_id);
                exit;
                
            }            
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }
    // DELETE FRIENDS 
    public function delete_friends($my_id, $user_id){
        try{
            $delete_friends = "DELETE FROM friends WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $delete_requete = $connexion->prepare($delete_friends);
            $delete_requete->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $delete_requete->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_requete->execute();
            header('Location: user_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // REQUEST NOTIFICATIONS
    public function request_notification($my_id, $send_data){
        try{
            $sql = "SELECT sender, pseudo, avatar FROM `friend_request` JOIN utilisateurs ON friend_request.sender = utilisateurs.id WHERE receiver = ?";

            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");
            $requete = $connexion->prepare($sql);
            $requete->execute([$my_id]);
            if($send_data){
                return $requete->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return $requete->rowCount();
            }

        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }


    public function get_all_friends($my_id, $send_data){
        try{
            $sql = "SELECT * FROM friends WHERE user_one = :my_id OR user_two = :my_id";
            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");

            $requete = $connexion->prepare($sql);
            $requete->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $requete->execute();

                if($send_data){

                    $return_data = [];
                    $all_users = $requete->fetchAll(PDO::FETCH_OBJ);

                    foreach($all_users as $row){
                        if($row->user_one == $my_id){
                            $get_user = "SELECT id, pseudo, avatar FROM utilisateurs WHERE id = ?";

                            $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");

                            $get_user_requete = $connexion->prepare($get_user);
                            $get_user_requete->execute([$row->user_two]);
                            array_push($return_data, $get_user_requete->fetch(PDO::FETCH_OBJ));
                        }else{
                            $get_user = "SELECT id, pseudo, avatar FROM utilisateurs WHERE id = ?";
                            $get_user_requete = $connexion->prepare($get_user);
                            $get_user_requete->execute([$row->user_one]);
                            array_push($return_data, $get_user_requete->fetch(PDO::FETCH_OBJ));
                        }
                    }

                    return $return_data;

                }
                else{
                    return $requete->rowCount();
                }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }


    /*******************************      FOLLOW      ************************/

    
//         public function followUser() { {			
// 		$sql = "INSERT INTO follow (follower_id, followed_id) VALUES (?, ?);";
//         $connexion = new PDO("mysql: host=localhost; port=3306; dbname=testwayz; charset=utf8", "root", "");

// 		$requete = conn->prepare($sql);						
// 		$requete->bind_param("ii", $_SESSION["user_id"], $this->followUserId);	
// 		if($requete->execute()){				
// 			$output = array(			
// 				"success"	=> 	1;
// 			);
// 		}
// 	}		
// }
}
?>