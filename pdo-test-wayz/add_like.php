<?php

require 'core/init.php';
require 'core/like.class.php';
require_once 'core/post.class.php';

if (isset($_SESSION['user']) && isset($_SESSION['user-id'])) {
    $user_data = $user_obj->findByiD($_SESSION['user-id']);
    if ($user_data ===  false) {
        header('Location: logout.php');
        exit;
    }
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    $all_users = $user_obj->all_users($_SESSION['user-id']);
} else {
    header('Location: logout.php');
    exit;
}

$posts = Post::findFriendsPosts($user_data->id);
$get_all_friends = $frnd_obj->get_all_friends($_SESSION['user-id'], true);

foreach ($posts as $post) {
$post_id = $post->post_id ;
$user_id = $post-> id;
}
  
  $add_like = Like::AddLike($post_id, $user_id);
  
  echo json_encode($add_like);


?>