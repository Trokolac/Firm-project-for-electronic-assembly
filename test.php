<?php 
if(isset($_POST['submit'])){
  if($_FILES['file']['name']){
    $filename = explode(".", $_FILES['file']['name']);
  }
}
?>

<form method="post" enctype="multipart/form-data" >
<p>Upload CSV: <input type="file" name="file"></p>
<p><input type="submit" name="submit" value="import"></p>
</form>