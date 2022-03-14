<?php
session_start();
require_once("../header.php");
if(isset($_GET['esegui_login'])){
      $esegui_login=1;
}
else{
      $esegui_login=0;
}
if(isset($_GET['esegui_register'])){
      $esegui_register=1;
}
else{
      $esegui_register=0;
}
if(isset($_GET['logout'])||isset($_GET['operation'])){
      echo"entrato";
      if($_GET['operation']==3)
            $operation=3;
}
else{
      $operation=0;
}
if($_SERVER['REQUEST_METHOD']=='POST'){
      if(isset($_POST['login'])){
            $operation=1;
      }
      else{
            $operation=0;
      }
      if(isset($_POST['register'])){
            if(isset($_POST['register'])){
                  $operation=2;
            }
            else{
                  $operation=0;
            }
      }
}
if(isset($_GET['esegui_login'])){
      $esegui_login=1;
}
if(isset($_GET['esegui_register'])){
      $esegui_register=1;
}
//=========================scelta=====================================================================================
echo"
<form method='post'action='" . $_SERVER['PHP_SELF']. "'>
      <input type='submit' value='login' name='login'/>
      </br>
      <input type='submit' value='register' name='register'/>
</form>
";
//========================================login=========================================================================
if($operation==1){
      echo"
      <!DOCTYPE html>
      <html>
          <head>
              <title>Login</title>
              <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans&display=swap'>
              <link rel='stylesheet' href='/css/style.css'>
          </head>
          <body>
              <form method='post' action='/negozio/account_negozio/choice_log_reg.php?esegui_login=1'>
                  <h1>Login</h1>
                  <input type='text' id='username' placeholder='Username' name='username'>
                  <input type='password' id='password' placeholder='Password' name='password'>
                  <button type='submit'name='login'>Accedi</button>
              </form>
              <a href='../chi_siamo.php' targhet='_blank'>torna indietro</a>
          </body>
      </html>

      ";
}
if($esegui_login){
      if (isset($_POST['login'])) {
          $username = $_POST['username'] ?? '';
          $password = $_POST['password'] ?? '';
          if (empty($username) || empty($password)) {
              $msg = 'Inserisci username e password %s';
          } else {
              $query = "
                  SELECT nome, password,id_utente
                  FROM utenti_negozio
                  WHERE nome = '$username'
              ";
              echo  $query;
              $result = mysqli_query($connessione,$query);
              $user = mysqli_fetch_assoc($result);
              if (!$user || password_verify($password, $user['password']) === false) {
                  $msg = 'Credenziali utente errate %s';
              } else {
                  session_regenerate_id();
                  $_SESSION['utente_negozio_id'] = session_id();
                  $_SESSION['session_db_set_negozio_user'] = $user['nome'];
                  $_SESSION['nome_utente']=$username;
                  $_SESSION['id_utente']=$user['id_utente'];
                  echo"loggato";
                  $host  = $_SERVER['HTTP_HOST'];
            	header("Location: http://" . $host . "/negozio/chi_siamo.php");
                  exit;
              }
          }
          printf($msg, '<a href="/negozio/account_negozio/choice_log_reg.php">torna indietro</a>');
      }
}
//===================================================register==================================================================
if($operation==2){
      echo"
      <!DOCTYPE html>
      <html>
          <head>
              <title>Registrazione</title>
              <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans&display=swap'>
              <link rel='stylesheet'href=''/css/style.css'>
          </head>
          <body>
              <form method='post' action='/negozio/account_negozio/choice_log_reg.php?esegui_register=1'>
                  <h1>Registrazione</h1>
                  <input type='text' id='username' placeholder='Username' name='username' maxlength='50' required>
                  <input type='password' id='password' placeholder='Password' name='password' required>
                  <button type='submit' name='register'>Registrati</button>
              </form>
          </body>
      </html>";
      echo"<a href='../chi_siamo.php' targhet='_blank'>torna indietro</a>";
}
if($esegui_register){
      if (isset($_POST['register'])) {
          $username = $_POST['username'] ?? '';
          $password = $_POST['password'] ?? '';
          $isUsernameValid = filter_var(
              $username,
              FILTER_VALIDATE_REGEXP, [
                  "options" => [
                      "regexp" => "/^[a-z\d_]{3,20}$/i"
                  ]
              ]
          );
          $pwdLenght = mb_strlen($password);

          if (empty($username) || empty($password)) {
              $msg = 'Compila tutti i campi %s';
          } elseif (false === $isUsernameValid) {
              $msg = 'Lo username non è valido. Sono ammessi solamente caratteri
                      alfanumerici e l\'underscore. Lunghezza minina 3 caratteri.
                      Lunghezza massima 20 caratteri';
          } elseif ($pwdLenght < 8 || $pwdLenght > 20) {
              $msg = 'Lunghezza minima password 8 caratteri.
                      Lunghezza massima 20 caratteri';
          } else {
              $password_hash = password_hash($password, PASSWORD_BCRYPT);
              echo"entato";

              $query = "
                  SELECT id_utente
                  FROM utenti_negozio
                  WHERE nome = '$username'
              ";

              echo"query riga 159</br>";
              var_dump($query);
              $result = mysqli_query($connessione,$query);
              $user = mysqli_fetch_assoc($result);

              if (db_num_rows($query)>0){
                  $msg = 'Username già in uso %s';
              } else {
                  $query = "
                      INSERT INTO utenti_negozio (nome,password,admin)
                      VALUES ('$username', '$password_hash',0)
                  ";
                  var_dump($query);
                  $result = mysqli_query($connessione,$query);
//mysqli_error
                  if ($result) {
                      $msg = 'Registrazione eseguita con successo';
                     session_regenerate_id();
                     $_SESSION['utente_negozio_id'] = session_id();
                     $_SESSION['session_db_set_negozio_user'] = $user['nome'];
                     $_SESSION['nome_utente']=$username;
                     $_SESSION['id_utente']=$user['id_utente'];
                     echo"loggato";
                     $host  = $_SERVER['HTTP_HOST'];
                     header("Location: http://" . $host . "/negozio/chi_siamo.php");

                  } else {
                      $msg = 'Problemi con l\'inserimento dei dati %s';
                  }
              }
          }
          printf($msg, '<a href="/negozio/account_negozio/choice_log_reg.php">torna indietro</a>');
          echo"<form method='post'action='/negozio/account_negozio/choice_log_reg.php'>
                  <input type='submit' value='registration_done' name='registration_done'/>
               </form>
          ";
      }

}
//==================================================logout=============================================================================================================================
if($operation==3){
      session_destroy();
      echo"<h1>LOGOUT EFFETUATO CON SUCCESSO</h1></br>";
}
require_once( "../footer.php");
?>
