function addLike(event) {
    const id_post = event.target.closest('.posts').getAttribute('id');

    $.ajax({
      type: "POST",
      url: "add_like.php",
      data: {id_post: id_post},
      success: function(response) {
          console.log(response);
          console.log(id_post)
      } 
    });
  }