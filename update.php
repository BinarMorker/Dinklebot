<?php if($_POST['repository']['name'] == "mastodon-parking"){
  exec('git pull'); 
  exec('git status', $response);
  foreach($response as $line){
    echo($line . "<br/>"); 
  }
  file_put_contents("output.txt",$_POST);
}else{
  echo "You don't have the permission to do that.";
}
//Updates the website and the project inside its directory
