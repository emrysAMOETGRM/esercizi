


<?php
//balblabla

//http://zqktlwiuavvvqqt4ybvgvi7tyo4hjl5xgfuvpdf6otjiycgwqbym2qad.onion/wiki/index.php/Main_Page
session_start();
if (isset($_SESSION['session_id_db_set_blackjack'])) {
	echo $_SESSION['session_id_db_set_blackjack_user'];
	$session_user = htmlspecialchars($_SESSION['session_id_db_set_blackjack_user'], ENT_QUOTES, 'UTF-8');
	$session_id = htmlspecialchars($_SESSION['session_id_db_set_blackjack']);
	printf("<p>Benvenuto %s</p>", $_SESSION['nome_utente']);
	echo "<br>";
	$username=$_SESSION['nome_utente'];
	echo"<a href='../login/php/logout.php' targhet='_blank'>logout:</a>";
	require_once(dirname(__FILE__) . "/../init.php");
	require_once($dir_file_src . "session.php");
	require_once('funzioniblackjack.php');
	$header_title = "BLACKJACK";
	$subtitle = "Game";
	$file_css = "game.css?ts=<?=time()";
	require_once($dir_file_src . "header_game.php");
	require_once('connection_db.php');
	if(!isset($_GET["exec"])) {
		echo "ERRORE: Nessun exec trovato";
		exit;
	}
	//Proform21
	$semi = semi();
	if(isset($_SESSION['flag_c_l'])){
		$flag_c_l=$_SESSION['flag_c_l'];
	}

	if(!isset($_SESSION['prima_carta_banco'])) {
		$prima_carta_banco = 0;
		$_SESSION['prima_carta_banco'] = $prima_carta_banco;
	} else {
		$prima_carta_banco = $_SESSION['prima_carta_banco'];
	}

	if(!isset($_SESSION['seconda_carta_banco'])) {
	 	$seconda_carta_banco = 0;
		$_SESSION['seconda_carta_banco'] = $seconda_carta_banco;
	} else {
		$seconda_carta_banco = $_SESSION['seconda_carta_banco'];
	}

	if(!isset($_SESSION['totale_punteggio_utente'])) {
		$totale_punteggio_utente = 0;
		$_SESSION['totale_punteggio_utente'] = $totale_punteggio_utente;
	} else {
		$totale_punteggio_utente = $_SESSION['totale_punteggio_utente'];
	}

	if(!isset($_SESSION['prima_carta_utente'])) {
		$prima_carta_utente = 0;
		$_SESSION['prima_carta_utente'] = $prima_carta_utente;
	} else {
		$prima_carta_utente = 	$_SESSION['prima_carta_utente'];
	}

	if(!isset($_SESSION['seconda_carta_utente'])) {
		$seconda_carta_utente = 0;
		$_SESSION['seconda_carta_utente'] = $seconda_carta_utente;
	} else {
		$seconda_carta_utente = $_SESSION['seconda_carta_utente'];
	}

	if(!isset($_SESSION['array_carte_utente'])) {
		$array_carte_utente = array();
		$_SESSION['array_carte_utente'] = $array_carte_utente;
	} else {
		$array_carte_utente = $_SESSION['array_carte_utente'];
	}

	if(!isset($_SESSION['totale_punteggio_banco'])) {
		$totale_punteggio_banco = 0;
		$_SESSION['totale_punteggio_banco'] = $totale_punteggio_banco;
	} else {
		$totale_punteggio_banco = $_SESSION['totale_punteggio_banco'];
	}

	if(!isset($_SESSION['count_banco'])) {
		$count_banco = 0;
		$_SESSION['count_banco'] = $count_banco;
	} else {
		$count_banco = $_SESSION['count_banco'];
	}

	if(!isset($_SESSION['count_utente'])) {
		$count_utente = 0;
		$_SESSION['count_utente'] = $count_utente;
	} else {
		$count_utente = $_SESSION['count_utente'];
	}

	if(!isset($_SESSION['soldi_utente'])) {
		$soldi_utente = 1000;
		$_SESSION['soldi_utente'] = $soldi_utente;
	} else {
		$soldi_utente = $_SESSION['soldi_utente'];
	}

	if(!isset($_SESSION['mazzo'])) {
		$mazzo = mazzo();
		$_SESSION['mazzo'] = $mazzo ;
	} else {
		$mazzo = $_SESSION['mazzo'];
	}

	if(!isset($_SESSION['puntata'])) {
		$puntata = 0;
		$_SESSION['puntata'] = $puntata;
	} else {
		$puntata = $_SESSION['puntata'];
	}

	if($_GET["exec"] == 1) { //================================================================Nuova partita
		$flag_c_l=0;
		$_SESSION['flag_c_l']=$flag_c_l;
		if(!isset($_GET["sub_exec"]))
			$_GET["sub_exec"] = 0;
		if($_GET["sub_exec"] == 0) {
			$mazzo = mazzo();
			$soldi_utente = 1000;
			$_GET["sub_exec"] = 1;
			unset($_SESSION['totale_punteggio_utente']);
			unset($_SESSION['totale_punteggio_banco']);
			unset($_SESSION['count_utente']);
			unset($_SESSION['count_banco']);
			unset($_SESSION['puntata']);
		}
		if($_GET["sub_exec"] == 1) {
			stampa_soldi_posseduti($soldi_utente);  //==========================================================puntata
			if($soldi_utente==0) {
				echo "hai finito tutti i soldi";
			}
			form_puntata_nuova_partita();
		}
		if($_GET["sub_exec"] == 2) { //==================================================================controllo puntata
			if(isset($_POST['puntata_i'])) {
				$puntata = $_POST['puntata'];
				if(puntata_is_valid($puntata, $soldi_utente)) {
					$_GET["sub_exec"] = 3;
				}
				else {
					messaggio_puntata_invalid();
					rinserisci_puntata_form_nuova_partita();
					$_GET["sub_exec"] = 1; //===================================================================da guardare come riaggiornare pagina
				}
			}
		}
		if($_GET["sub_exec"] == 3) {	 //====================================================================Prima mano gioco
			$prima_carta_banco = carta($mazzo);
			$seconda_carta_banco = carta($mazzo);
			$valore_prima_carta_banco = valore_carte($prima_carta_banco, $totale_punteggio_banco);
			$valore_seconda_carta_banco = valore_carte($seconda_carta_banco, $totale_punteggio_banco);
		  	$totale_punteggio_banco += $valore_prima_carta_banco + $valore_seconda_carta_banco;
		  	$count_banco = 2;
			$c = print_carta($prima_carta_banco, $semi);
			stampa_immagine_carta($c);
			stampa_immagine_carta_retro();
		  	stampa_numero_carte_pescate_banco($count_banco);

		  	$prima_carta_utente = carta($mazzo);
			$seconda_carta_utente = carta($mazzo);
		  	$valore_prima_carta_utente = valore_carte($prima_carta_utente, $totale_punteggio_utente);
			$valore_seconda_carta_utente = valore_carte($seconda_carta_utente, $totale_punteggio_utente);
		  	$count_utente = 2;
			$array_carte_utente = array();
			array_push($array_carte_utente, $prima_carta_utente);
			array_push($array_carte_utente, $seconda_carta_utente);
			$totale_punteggio_utente += $valore_prima_carta_utente + $valore_seconda_carta_utente;
			$c = print_carta($prima_carta_utente, $semi);
			stampa_immagine_carta($c);
			$c = print_carta($seconda_carta_utente, $semi);
			stampa_immagine_carta($c);
			stampa_punteggio_totale_utente($totale_punteggio_utente);
		  	stampa_numero_carte_pescate_utente($count_utente);
			if($totale_punteggio_utente == 21){
				stampa_pulsante_termina_nuova_partita();
				stampa_blackjack();

			}
			else{

			//==========================================================================================utente chiede un altra carta
			stampa_pulsante_carta_nuova_partita();
				//========================================================================================utente vuole terminare la partita
			stampa_pulsante_termina_nuova_partita();
			}

		}
		if($_GET["sub_exec"] == 4) { //================================================================carta
			$carta = carta($mazzo);
		  	$totale_punteggio_utente += valore_carte($carta, $totale_punteggio_utente);
			echo $count_utente;
			for($i=0; $i<$count_utente; $i++) {
				$c = print_carta($_SESSION['array_carte_utente'][$i], $semi);
				stampa_immagine_carta($c);
			}
		  	$count_utente += 1;
			array_push($array_carte_utente, $carta);
			$c = print_carta($carta, $semi);
			stampa_immagine_carta($c);
			stampa_punteggio_totale_utente($totale_punteggio_utente);
		  	stampa_numero_carte_pescate_utente($count_utente);
			$_SESSION['mazzo'] = $mazzo;
			if($totale_punteggio_utente > 21) {
				stampa_pulsante_termina_nuova_partita();
				stampa_sballato();
			} else {
				stampa_pulsante_carta_nuova_partita();
				//========================================================================================utente vuole terminare la partita
				stampa_pulsante_termina_nuova_partita();
			}
		}
		if($_GET["sub_exec"] == 5) { //==============================================================termina
			for($i=0; $i<$count_utente; $i++) {
				$c = print_carta($array_carte_utente[$i], $semi);
				stampa_immagine_carta($c);
			}
			echo"</br>";
			$c = print_carta($prima_carta_banco, $semi);
			stampa_immagine_carta($c);
			$c = print_carta($seconda_carta_banco, $semi);
			stampa_immagine_carta($c);
			$flag1 = 0;
			while($flag1 != 1) {
				if($totale_punteggio_banco + 5>21) {
					break;
				}
				$carta_banco = carta($mazzo, $semi);
				$c = print_carta($carta_banco, $semi);
				stampa_immagine_carta($c);
				$count_banco++;
				$totale_punteggio_banco += valore_carte($carta_banco, $totale_punteggio_banco);
		 	}
			carte_pescate_banco($count_banco);
			vincitore($totale_punteggio_banco, $totale_punteggio_utente, $soldi_utente, $puntata,$username,$flag_c_l,$connessione);
			unset($_SESSION['totale_punteggio_utente']);
			unset($_SESSION['totale_punteggio_banco']);
			unset($_SESSION['count_utente']);
			unset($_SESSION['count_banco']);
			unset($_SESSION['puntata']);
			unset($_SESSION['prima_carta_banco']);
			unset($_SESSION['seconda_carta_banco']);
			unset($_SESSION['prima_carta_utente']);
			unset($_SESSION['seconda_carta_utente']);
			unset($_SESSION['array_carte_utente']);
			pulsante_rigioca_nuova_partita();
			pulsante_torna_al_menu();
		}
		if($_GET["sub_exec"] == 7) {
			$_GET["exec"] = 2;
			unset($_GET["sub_exec"]);
			pulsante_inizia_gioco();
		}
		if($_GET["sub_exec"] == 8) {
			header("location: menu.php");
		}

		if($_GET["sub_exec"] != 5){
			$_SESSION['mazzo'] = $mazzo;
			$_SESSION['soldi_utente'] = $soldi_utente;
			$_SESSION['prima_carta_banco'] = $prima_carta_banco;
			$_SESSION['seconda_carta_banco'] = $seconda_carta_banco;
	      	$_SESSION['count_banco'] = $count_banco;
			$_SESSION['count_utente'] = $count_utente;
			$_SESSION['prima_carta_utente'] = $prima_carta_utente;
			$_SESSION['seconda_carta_utente'] = $seconda_carta_utente;
			$_SESSION['array_carte_utente']=$array_carte_utente;
			$_SESSION['totale_punteggio_banco'] = $totale_punteggio_banco;
			$_SESSION['totale_punteggio_utente'] = $totale_punteggio_utente;
	  		$_SESSION['count_utente'] = $count_utente;
		}
	}



	if($_GET["exec"] == 2) {//==================================================================Continua partita
		$_SESSION['soldi_utente']=return_s($username,$connessione);
		$flag_c_l=1;
		$_SESSION['flag_c_l']=$flag_c_l;
		if(!isset($_GET["sub_exec"]))
			$_GET["sub_exec"] = 6;
		if($_GET["sub_exec"] == 6){
			$mazzo = mazzo();
			$soldi_utente = $_SESSION['soldi_utente'];
			$_SESSION['mazzo'] = $mazzo;
			$_SESSION['soldi_utente'] = $soldi_utente;
			$_GET["sub_exec"] = 1;
			unset($_SESSION['totale_punteggio_utente']);
			unset($_SESSION['totale_punteggio_banco']);
			unset($_SESSION['count_utente']);
			unset($_SESSION['count_banco']);
			unset($_SESSION['puntata']);
			unset($_SESSION['prima_carta_banco']);
			unset($_SESSION['seconda_carta_banco']);
		}
		if($_GET["sub_exec"] == 1) {
			stampa_soldi_posseduti($soldi_utente);  //==========================================================puntata
			 form_puntata_continua();
		}
		if($_GET["sub_exec"] == 2) { //==================================================================controllo puntata
			if(isset($_POST['puntata_i'])) {
				$puntata = $_POST['puntata'];
				$_SESSION['puntata'] = $puntata;
				if($c = puntata_is_valid($puntata,$soldi_utente)==1)
					$_GET["sub_exec"] = 3;
				else {
					messaggio_puntata_invalid();
					rinserisci_puntata_form_continua();
					$_GET["sub_exec"] = 1; //===================================================================da guardare come riaggiornare pagina
				}
			}
		}
		if($_GET["sub_exec"] == 3) { //====================================================================Prima mano gioco
			$prima_carta_banco = carta($mazzo);
			$seconda_carta_banco = carta($mazzo);
			$valore_prima_carta_banco=valore_carte($prima_carta_banco,$totale_punteggio_banco);
			$valore_seconda_carta_banco=valore_carte($seconda_carta_banco,$totale_punteggio_banco);
			$_SESSION['prima_carta_banco'] = $prima_carta_banco;
			$_SESSION['seconda_carta_banco'] = $seconda_carta_banco;
			$array_carte_banco=array();
			array_push($array_carte_banco,$prima_carta_banco);
			array_push($array_carte_banco,$seconda_carta_banco);
			$_SESSION['array_carte_banco']=$array_carte_banco;
		  	$totale_punteggio_banco += $valore_prima_carta_banco +$valore_seconda_carta_banco;

		  	$count_banco += 2;
			$c=print_carta($prima_carta_banco,$semi);
			stampa_immagine_carta($c);
			stampa_immagine_carta_retro();
		  	stampa_numero_carte_pescate_banco($count_banco);


		  	$prima_carta_utente = carta($mazzo);
			$seconda_carta_utente = carta($mazzo);
		  	$valore_prima_carta_utente = valore_carte($prima_carta_utente,$totale_punteggio_utente);
			$valore_seconda_carta_utente = valore_carte($seconda_carta_utente,$totale_punteggio_utente);
		  	$count_utente += 2;
			$_SESSION['prima_carta_utente'] = $prima_carta_utente;
			$_SESSION['seconda_carta_utente'] = $seconda_carta_utente;
			$array_carte_utente=array();
			array_push($array_carte_utente,$prima_carta_utente);
			array_push($array_carte_utente,$seconda_carta_utente);
			$_SESSION['array_carte_utente']=$array_carte_utente;
			$totale_punteggio_utente += $valore_prima_carta_utente + $valore_seconda_carta_utente;
			$c=print_carta($prima_carta_utente,$semi);
			stampa_immagine_carta($c);
			$c=print_carta($seconda_carta_utente,$semi);
			stampa_immagine_carta($c);
			stampa_punteggio_totale_utente($totale_punteggio_utente);
		  	stampa_numero_carte_pescate_utente($count_utente);
			if($totale_punteggio_utente == 21){
				stampa_blackjack();
				$_GET["sub_exec"] = 5;
			}

				//==========================================================================================utente chiede un altra carta
				stampa_pulsante_carta_continua();
					//========================================================================================utente vuole terminare la partita
				stampa_pulsante_termina_continua();
						 $_SESSION['count_utente'] = $count_utente;
						 $_SESSION['totale_punteggio_utente'] = $totale_punteggio_utente;
						 $_SESSION['count_banco'] = $count_banco;
						 $_SESSION['totale_punteggio_banco'] = $totale_punteggio_banco;
						 $_SESSION['mazzo']=$mazzo;
		}
		if($_GET["sub_exec"] == 4) { //================================================================carta
			$carta = carta($mazzo);
		  $totale_punteggio_utente += valore_carte($carta,$totale_punteggio_utente);
			$_SESSION['totale_punteggio_utente'] = $totale_punteggio_utente;
			for($i=0;$i<$count_utente;$i++){
				$c=print_carta($_SESSION['array_carte_utente'][$i],$semi);
				stampa_immagine_carta($c);
			}
		  $count_utente += 1;
		  $_SESSION['count_utente'] = $count_utente;
			array_push($_SESSION['array_carte_utente'],$carta);
			$c=print_carta($carta,$semi);
			stampa_immagine_carta($c);
			stampa_punteggio_totale_utente($totale_punteggio_utente);
		 	stampa_numero_carte_pescate_utente($count_utente);
			$_SESSION['mazzo']=$mazzo;
			if($totale_punteggio_utente > 21){
				stampa_sballato();
				$_GET["sub_exec"] = 5;
			}
			else{
				stampa_pulsante_carta_continua();
					//========================================================================================utente vuole terminare la partita
				stampa_pulsante_termina_continua();
			}

		}
		if($_GET["sub_exec"] == 5) { //==============================================================termina
			$c=print_carta($_SESSION['prima_carta_banco'],$semi);
			stampa_immagine_carta($c);
			$c=print_carta($_SESSION['seconda_carta_banco'],$semi);
			stampa_immagine_carta($c);
			$flag1 = 0;
			while($flag1 != 1) {
				if($totale_punteggio_banco + 5>21){
					break;
				}
				$carta_banco = carta($mazzo,$semi);
				$c=print_carta($carta_banco,$semi);
				stampa_immagine_carta($c);
				$count_banco++;
			      stampa_numero_carte_pescate_banco($count_banco);
				$_SESSION['count_banco'] = $count_banco;
				$totale_punteggio_banco += valore_carte($carta_banco,$totale_punteggio_banco);
				$_SESSION['$totale_punteggio_banco'] = $totale_punteggio_banco;
			}
			vincitore($totale_punteggio_banco,$totale_punteggio_utente,$soldi_utente,$puntata,$username,$flag_c_l,$connessione);
			unset($_SESSION['totale_punteggio_utente']);
			unset($_SESSION['totale_punteggio_banco']);
			unset($_SESSION['count_utente']);
			unset($_SESSION['count_banco']);
			unset($_SESSION['puntata']);
			unset($_SESSION['prima_carta_banco']);
			unset($_SESSION['seconda_carta_banco']);
			unset($_SESSION['prima_carta_utente']);
			unset($_SESSION['seconda_carta_utente']);
			unset($_SESSION['array_carte_banco']);
			unset($_SESSION['array_carte_utente']);
			pulsante_rigioca_continua_partita();
			pulsante_torna_al_menu();
		}
		if($_GET["sub_exec"] == 7) {
			$_GET["exec"] = 2;
			$_GET["sub_exec"] = NULL;
			pulsante_inizia_gioco();
		}
		if($_GET["sub_exec"] == 8) {
			header("location: menu.php");
		}
	}
	require_once($dir_file_src . "footer_game.php");
}
else {
    printf("<p>Effettua il %s per accedere all'area riservata</p>", '<a href="../login.html">login</a>');
}
require_once('disconnection_db.php');






















































/*
html=============================
<div id="wrapper">
  <div id="game">
    <div id="alert" class="alert alert-error hide"><span></span></div>
    <div id="dealer">
      <div id="dhand"></div>
    </div>
    <div id="player">
      <div id="phand"></div>
    </div>
    <div id="money">
      <span id="cash">Cash: $<span></span></span>
      <div id="bank">Winnings: $<span></span></div>
    </div>
  </div>
  <div id="actions">
    <button id="deal" class="btn">Deal!</button>
    <button id="hit" class="btn" disabled>Hit</button>
    <button id="stand" class="btn" disabled>Stand</button>
    <button id="double" class="btn" disabled>Double Down</button>
    <button id="split" class="btn" disabled>Split</button>
    <button id="insurance" class="btn" disabled>Insurance</button>
    <strong>Wager:</strong> $<input id="wager" class="input-small" type="text" />
  </div>
</div>
<div id="myModal" class="modal hide fade">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Out of cash!</h3>
  </div>
  <div class="modal-body">
    <p>You ran out of cash, but you spot an ATM nearby. Would you like to withdraw another $1,000 and try your luck again?</p>
  </div>
  <div class="modal-footer">
    <a href="#" id="cancel" class="btn">Nah</a>
    <a href="#" id="newGame" class="btn btn-primary">Yes!</a>
  </div>
</div>

#include <stdio.h>
#include <limits.h>
#define ELEMENT_SIZE 10
unsigned int covert(void);
void displayBits(unsigned int value); // prototype
int main(int argc, const char * argv[]) {
    unsigned int x=3;
    displayBits(x);
}
void displayBits(unsigned int value){
    unsigned int mask=1;
    mask<<=16;
    printf("%u",mask),
    for(unsigned int i=1;i<=16;i++){
        printf(value & mask?"1" : "0");
        value<<=1;
    }
}

css==========================================================





*/
