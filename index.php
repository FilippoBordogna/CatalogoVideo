<?php
	/* 
		DA FARE:
		- Effettuare funzioni tendina una volta loggato
		- Effettuare controlli su $_GET (es. pagina)
		- Case 4 (Tutti i documentari)
		- Aggiungere ordinamenti diversi sui dati nel Case 4 (nazionalita, anno uscita, voto)
		- Ultimi accessi (ultimo) per controllo
		- Rifare lo schema ER/logico in base alle modifiche (PIPPO)

		SCHEMA SWITCH:
		- 0 HOMEPAGE
		- 1 TUTTI I FILM
		- 2 TUTTE LE SERIE TV
		- 3 TUTTE LE SAGHE
		- 4 TUTTI I DOCUMENTARI
		- 5 DETTAGLI VIDEO
		- 6 DETTAGLI SERIE 
		- 11 DETTAGLI EPISODI
		- 7 DETTAGLI SAGHE
		- 8 DETTAGLI PERSONE
		- 9 DETTAGLI PERSONAGGI
		- 10 RICERCA
		- 13 CURIOSITA DELL'UTENTE
		- 12 RECENSIONI DELL'UTENTE
		- 11 PROFILO UTENTE
		
	*/

	// Controllo Sessioni
	ini_set("session.gc_maxlifetime","1440");
	ini_set("session.cookie_lifetime","1440");
	session_start();
	if(!isset($_SESSION["login"]) || $_SESSION["login"]!=1) // Mancata presenza di dati integri per login 
		session_unset();
	if(isset($_GET["stato"])&&$_GET["stato"]=="logout") { // Operazione di logout 
		if(isset($_SESSION['idUser'])){
			$conn=dbConn();
			
			$query="SELECT current_timestamp()-dataOra durata FROM accessi WHERE idUtente=$_SESSION[idUser] ORDER BY dataOra DESC LIMIT 1"; // Preparazione Query: Durata ultimo accesso
			$risultato=$conn->query($query);
			$tempo=$risultato->fetch_assoc();
			//echo $tempo['durata'];
			if($tempo['durata']>1440) // La sessione dura da più di 1 giorno: pongo durata al limite massimo
				$query="UPDATE accessi SET durata=1440 WHERE idUtente=$_SESSION[idUser] ORDER BY dataOra DESC LIMIT 1";
			else // La sessione dura da meno di 1 giorno: pongo durata al valore effettivo
				$query="UPDATE accessi SET durata=current_timestamp()-dataOra WHERE idUtente=$_SESSION[idUser] ORDER BY dataOra DESC LIMIT 1";
			$risultato=$conn->query($query);
		}
		session_unset();
		session_destroy();
		session_start(); // Chiudo e riapro la sessione
		
		$redirect="Location:index.php?"; //reindirizzamento per logout e per evitare problemi di refresh
		if(isset($_GET["logout"])&&isset($_GET["id"])) {
			$_GET["stato"]=$_GET["logout"];
			if($_GET["stato"]<=14 && $_GET["stato"]>=12)
				$_GET["stato"]=0;
			$redirect.="stato=$_GET[stato]&id=$_GET[id]";
			if(isset($_GET["pagina"]))
				$redirect.="&pagina=$_GET[pagina]";
			if(isset($_GET["ordinamento"]))
				$redirect.="&ordinamento=$_GET[ordinamento]";
			if(isset($_GET["pagina2"]))
				$redirect.="&pagina2=$_GET[pagina2]";
			if(isset($_GET["ordinamento2"]))
				$redirect.="&ordinamento2=$_GET[ordinamento2]";
		}
		header($redirect);
		exit();
	}
	
	function dbConn() { // Connessione al DB 
		$host = ""; // Host Server MySQL 
		$user = "root"; // User Server MySQL XAMPP
		//$user = ""; // User Server MySQL Altervista
		$pwd = ""; // Password Server MySQL 
		$dbname = "catalogo"; // Nome DB MySQL XAMPP
		//$dbname = "my_bordognafilippo"; // Nome DB MySQL ALtervista Bordogna
		$conn = new mysqli ( $host , $user , $pwd , $dbname ); // Inizializzazione Connesione DB 
		if ($conn->connect_errno) { // Si sono verificati errori
			printf("Errore nella connessione al DB:</br>", $conn->connect_error); // Stampa eventuali errori 
			exit();
		}
		return $conn;
	}
?>


<!doctype html>
<html lang="it">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!--<meta charset="utf-8">-->
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
		<meta name="generator" content="Jekyll v4.0.1">
		<title>Catalogo Film, Documentari e Serie TV</title>


		<!-- Bootstrap core CSS -->
		<link href="assets/dist/css/bootstrap.css" rel="stylesheet">

		<style>
		.rate {
			float: left;
			height: 46px;
			padding: 0 10px;
		}
		.rate:not(:checked) > input {
			position:absolute;
			top:-9999px;
		}
		.rate:not(:checked) > label {
			float:right;
			width:1em;
			overflow:hidden;
			white-space:nowrap;
			cursor:pointer;
			font-size:30px;
			color:#ccc;
		}
		.rate:not(:checked) > label:before {
			content: '★ ';
		}
		.rate > input:checked ~ label {
			color: #ffc700;    
		}
		.rate:not(:checked) > label:hover,
		.rate:not(:checked) > label:hover ~ label {
			color: #deb217;  
		}
		.rate > input:checked + label:hover,
		.rate > input:checked + label:hover ~ label,
		.rate > input:checked ~ label:hover,
		.rate > input:checked ~ label:hover ~ label,
		.rate > label:hover ~ input:checked ~ label {
			color: #c59b08;
		}
		
		.bd-placeholder-img {
			font-size: 1.125rem;
			text-anchor: middle;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}

		@media (min-width: 768px) {
			.bd-placeholder-img-lg {
			font-size: 3.5rem;
			}
		}

		.col-md-3 :hover { 
			cursor: pointer;
			background-color: black;
			color: white;	  
			transition: all 0.7s; 
		} /* Quando passo su una casella la evidenzio */

		.card-text-description {
			display: none;
		} /* Quando non punto su una casella la descrizione è nascosta  */

		.col-md-3 :hover .card-text-description {
			display: block;
		} /* Quando punto su una casella la descrizione è visibile  */

		.text-left :hover {
			cursor: pointer;
		} /* Quanto passo su una casella cambia il cursore nella manina */

		.container-cat  {
			text-align: center; 
			cursor: pointer;
			color: blue;
		} /* Stile della tendina delle categorie */

		.container-cat :hover {
			cursor: pointer;
			background-color: black;
			color: white;	  
			transition: all 0.7s; 
		} /* Stile della tendina delle categorie: col cursore sopra */

		</style>
		<!-- -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Libreria Bootstrap ? -->
		<script> // Funzioni JavaScript 
			function logout() { // Effettua il logout 
				f.stato.value="logout";
				f.submit();
			}
			
			function passa_a(id,stato,pagina,ordinamento,pagina2,ordinamento2) { // Modifica l'dentificativo, lo stato, i numeri di pagina e gli ordinamenti
				f.id.value=id;
				f.stato.value=stato;
				f.pagina.value=pagina;
				f.ordinamento.value=ordinamento;
				f.pagina2.value=pagina2;
				f.ordinamento2.value=ordinamento2;
				f.submit();
			}
			
			function recens(id) { // Controlli sulla recensione ed effettivo inserimento 
				if(f.rate.value==0)
					alert("Errore! Devi inserire un voto per lasciare una recensione");
				else{
					if(f.textarea.value!="")
						recensione.rec.value=f.textarea.value;
					recensione.rate.value=f.rate.value;
					recensione.submit();
				}
			}
			function curios(id) { // Controlli sulla curiosita ed effettivo inserimento 
				if(f.textcur.value!=""){
					curiosita.cur.value=f.textcur.value;
					curiosita.submit();
				}
			}
			function elimina(id,idUtente) { // Eliminazione di una una recensione 
				recensione.rate.value="ELIMINA";
				recensione.idUtente.value=idUtente;
				recensione.submit();
			}
			function eliminaC(id,comando="ELIMINA") { // Eliminazione una recensione 
				curiosita.check.value=comando;
				curiosita.idCur.value=id;
				curiosita.submit();
			}
			function verifica(idUtente){ // Verifica della recensione da parte dell'admin 
				recensione.rate.value="VERIFICA";
				recensione.idUtente.value=idUtente;
				recensione.submit();
			}
			
			function verificaC(id){ // Verifica della recensione da parte dell'admin 
				curiosita.check.value="VERIFICA";
				curiosita.idCur.value=id;
				curiosita.submit();
			}
			function controllo() { // Controlli sulla registrazione utente
				if(registrazione.pass1.value!=registrazione.pass2.value) { // Le 2 password non coincidono
					document.getElementById('avviso1').style.visibility="visible";
					return false;
				}
				else
					if(registrazione.pass1.value==""||registrazione.newUser.value==""||registrazione.newEmail.value=="") { // Campi vuoti
						document.getElementById('avviso1').style.visibility="hidden";
						document.getElementById('avviso2').style.visibility="visible";
						return false;
					}
					else{ // Tutto OK
						registrazione.submit();
						return true;
					}
			}
			function check() { // Controlli sulla modifica della password
				if(changepw.newpw.value==changepw.newpwc.value) // Tutto OK
					return true;
				else { // Le 2 password non coincidono
					document.getElementById('avviso').style.visibility="visible";
					return false;
				}
			}
		</script>
  	</head>
  	<body>
		<header>
		  	<div class="navbar navbar-dark bg-dark shadow-sm"> <!-- Base della Navbar-->
		 		<div class="container d-flex justify-content-between"> <!-- Contenitore delle scorciatoie -->
			  		<a href="index.php" class="navbar-brand d-flex align-items-center" > <!-- Scorciatoia Homepage -->
						<i class="fa fa-home"></i>
					</a>
					<div class="dropdown"> <!-- Elenco categorie -->
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Categorie
						</button>
						<div class="dropdown-menu">
						<?php
							$conn=dbConn();	
							$query="SELECT DISTINCT G.tipo,G.id FROM generivideo GV JOIN generi G ON G.id=GV.idGenere"; // Preparazione Query: Categorie presenti nel DB
							if ($categorie=$conn->query($query)) // Query effettuata con successo
								if ($categorie->num_rows>0)// Almeno un risultato
									while ($categoria = $categorie->fetch_assoc())
										echo ' <div class="container-cat" onclick="passa_a('.$categoria["id"].',15,1,0,1,0);">'.$categoria["tipo"].'</div>';
								$categorie->free();
							$conn->close();									
						?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" data-toggle="modal" data-target="#register">Non trovi qualcosa?<br> Prova con la ricerca </a>
						</div>
					</div>
					<?php
						if(!isset($_SESSION["login"])) // Sessione Login non inizializzata
							$_SESSION["login"]=0;
						if(isset($_POST["user"]) && isset($_POST["pass"])){ // Username e Password specificati 
							$login=1;
							$utente=filter_var(trim($_POST["user"]), FILTER_SANITIZE_STRING); // Sanifico la stringa (evito SQL Injection)
							$password=filter_var(trim($_POST["pass"]), FILTER_SANITIZE_STRING); // Sanifico la stringa (evito SQL Injection)
							$conn=dbConn(); // Connessione al DB
							$query="SELECT * FROM utenti WHERE email='".$utente."' AND password='".md5($password)."';"; // Preparazione Query: Controllo Accesso
							$risultati=$conn->query($query); // Risultati della query 
							if (!$risultati->num_rows!=1) { // 1 unico risultato
								// Prelevo il valore
								
								$riga=$risultati->fetch_assoc();
								$admin=$riga["admin"];
								$risultati->free(); // Dealloco l'oggetto
								// Setto la sessione
								$_SESSION["user"]=$riga["username"];
								$_SESSION["idUser"]=$riga["id"];
								$_SESSION["admin"]=$riga["admin"];
								$_SESSION["login"]=1;
								$query="SELECT COUNT(*) n FROM accessi WHERE idUtente=$_SESSION[idUser]";
								$nAcc=$conn->query($query); // Risultati della query 
								if ($nAcc->num_rows>0) { // 1 unico risultato
									// Prelevo il valore
									$riga=$nAcc->fetch_assoc();
									if($riga['n']==10){
										$query="DELETE FROM accessi WHERE idUtente=$_SESSION[idUser] ORDER BY dataOra LIMIT 1";
										$risultati=$conn->query($query); // Risultati della query 	
									}
									$query="INSERT INTO accessi (indirizzoIP,durata,idUtente) VALUES ('$_SERVER[REMOTE_ADDR]',1440,$_SESSION[idUser])";
									$risultati=$conn->query($query); // Risultati della query 	
								}
							}
							else // Piu' di un utente con la stessa login (ERRORE) 
								$login=0; // Annullo l'operazione di login 
						
							$conn->close(); // Chiudo la connessione al DB
						}
						if(isset($_POST["newUser"])){ // Username nuovi specificato 
							$user=filter_var(trim($_POST["newUser"]), FILTER_SANITIZE_STRING); // Sanifico la stringa (evito SQL Injection)
							$email=filter_var(trim($_POST["newEmail"]), FILTER_SANITIZE_STRING); // Sanifico la stringa (evito SQL Injection)
							$password=filter_var(trim($_POST["pass1"]), FILTER_SANITIZE_STRING); // Sanifico la stringa (evito SQL Injection)
							$conn=dbConn(); // Connessione al DB
							$query="INSERT INTO utenti (username,email,password,admin)
							VALUES ('$user','$email','".md5($password)."',0)";
							if($conn->query($query)) /* Inserimento nel DB riuscito */
								echo "<script type='text/javascript'>alert('L\'utente è stato inserito!');</script>";
							else /* Inserimento nel DB NON riuscito */
								echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
							$conn->close(); // Chiudo la connessione al DB
						}

						if($_SESSION["login"]==0){ // Utente non loggato 
							echo (' 
								<div class="dropdown">
									<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Login
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<form class="px-4 py-3" method="post">
											<div class="form-group">
												<label class="ml-2" for="exampleDropdownFormEmail1">Indirizzo email</label>
												<input type="email" class="form-control" id="user" name="user" placeholder="email@email.com">
											</div>
											<div class="form-group">
												<label class="ml-2" for="exampleDropdownFormPassword1">Password</label>
												<input type="password" class="form-control" id="pass" name="pass" placeholder="Password">
											</div>
											<button type="submit" class="btn btn-primary ml-2 mt-2">Login</button>
										</form>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" data-toggle="modal" data-target="#register">Non ancora iscritto? Registrati!</a>
									</div>
								</div>
								'); // Tendina Login 
								echo '
									<div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
									  <div class="modal-dialog" role="document">
										<div class="modal-content">
										  <div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">Registrazione</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											  <span aria-hidden="true">&times;</span>
											</button>
										  </div>
										  <div class="modal-body">
											<form class="px-4 py-3" name="registrazione" id="registrazione" onsubmit="return controllo()" method="post">
												<div class="form-group">
													<label class="ml-2" >Username</label>
													<input type="text" class="form-control" id="newUser" name="newUser" placeholder="Username">
												</div>
												<div class="form-group">
													<label class="ml-2" >Indirizzo email</label>
													<input type="email" class="form-control" id="newEmail" name="newEmail" placeholder="email@email.com">
												</div>
												<div class="form-group">
													<label class="ml-2">Password</label>
													<input type="password" class="form-control" id="pass1" name="pass1" placeholder="Password">
												</div>
												<div class="form-group">
													<label class="ml-2">Conferma password</label>
													<input type="password" class="form-control" id="pass2" name="pass2" placeholder="Password">
												</div>
												<p style="color:red; visibility:hidden" id="avviso1" name="avviso1" class="ml-2">Le due password devono coincidere</p>
												<p style="color:red; visibility:hidden" id="avviso2" name="avviso2" class="ml-2">Non ci possono essere campi vuoti!</p>
												
												</div>
												<div class="modal-footer">
													<button type="submit" class="btn btn-primary">Registrati</button>
												</div>
										  </form>
										</div>
									  </div>
									</div>';
						}
						else // Utente loggato 
							echo ('
								<div class="dropdown">
									<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										'.$_SESSION["user"].'
									</button>
									<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
										<button class="dropdown-item" type="button" onclick="passa_a(null,14,null,null)">Visualizza Profilo</button>
										<button class="dropdown-item" type="button" onclick="passa_a(null,12,null,null)">Visualizza Recensioni</button>
										<button class="dropdown-item" type="button" onclick="passa_a(null,13,null,null)">Visualizza Curiosità</button>
										<button class="dropdown-item" type="button" onclick="logout()">Logout</button>
									</div>
								</div>
							'); // Tendina di gestione dei contenuti
					?>
				</div>
		  	</div>
		</header>

		<main role="main">
			<form id='search' name='search' method='get'> <!-- Form Ricerca -->
				<div class="container d-flex justify-content-center mt-2">
					<div class="form-row align-items-center">
						<div class="col-auto">
							<input class=" d-flex align-items-center form-control" name='ricerca' id='ricerca' type="text" placeholder="Ricerca">
						</div>
						<div class="col-auto">
							<button type="submit" class=" btn btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
			</form>

			<form name='f' id='f' method='get'> <!-- Form stato, id, pagina, ordinamento, logout (facoltativo) -->
				<input type='hidden' name='stato' id='stato'> <!-- Identificativo della pagina da caricare -->
				<div class="album py-5 bg-light">
					<div class="container">
						<div class="row">
							<?php
								echo "<input type='hidden' name='id' id='id'"; // Identificativo dell'oggetto a cui si fa riferimento
								if(isset($_GET["id"])&&!empty($_GET["id"])) // Id conosciuto
									 echo "value='".$_GET["id"]."'";
								echo ">";

								echo "<input type='hidden' name='pagina' id='pagina'"; // Numero di pagina 
								if(isset($_GET["pagina"])&&!empty($_GET["pagina"])) // Pagina conosciuta
									 echo "value='".$_GET["pagina"]."'>";
								else
									echo "value='1'>";

								echo "<input type='hidden' name='ordinamento' id='ordinamento'"; // Tipo di ordinamento 
								if(isset($_GET["ordinamento"])&&!empty($_GET["ordinamento"])) { // Ordinamento definito
									echo "value='".$_GET["ordinamento"]."'>";
								}
								else
									echo "value='0'>";
								
								echo "<input type='hidden' name='pagina2' id='pagina2'"; // Numero di pagina 
								if(isset($_GET["pagina2"])&&!empty($_GET["pagina2"])) // Pagina conosciuta
										echo "value='".$_GET["pagina2"]."'>";
								else
									echo "value='1'>";

								echo "<input type='hidden' name='ordinamento2' id='ordinamento2'"; // Tipo di ordinamento 
								if(isset($_GET["ordinamento2"])&&!empty($_GET["ordinamento2"])) { // Ordinamento definito
									echo "value='".$_GET["ordinamento2"]."'>";
								}
								else
									echo "value='0'>";

								if(isset($_GET["stato"])&&!empty($_GET["stato"])) { // Stato conosciuto
									$stato=$_GET["stato"];
									echo "<input type='hidden' name='logout' id='logout' value='$stato'>"; // Valore di ritorno post logout
								}
								else if(isset($_GET["ricerca"])) // Richiesta di ricerca 
										$stato=10;
									else // Stato sconosciuto 
										$stato=0;

								switch($stato) { // Seleziono la pagina da vedere di cui poi (nei case) caricherò dinamicamente il contenuto
									case 0: /* 
											****************
											*** HOMEPAGE ***
											****************
											*/

										$conn=dbConn(); // Connessione al DB

										// MIGLIORI VIDEO
										$query="SELECT V.id, V.nome, V.durata, V.idSaga, V.numero, V.sinossi, V.annoUscita, V.nazionalita, AVG(RV.voto) mediaVoti
										FROM recensionivideo RV JOIN video V ON V.id=RV.idVideo
										WHERE RV.idAdmin IS NOT NULL AND V.selettore!=2
										GROUP BY RV.idVideo
										ORDER BY mediaVoti DESC
										LIMIT 8"; // Preparazione Query: Migliori video (base voto) 
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Video</h2>
											</div>
											'); // Titolo

										if ($video=$conn->query($query)) { // Query effettuata con successo
											if ($video->num_rows>0) { // Almeno un risultato
												while ($elemento = $video->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$elemento["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($elemento["mediaVoti"],2).'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalita: '.$elemento["nazionalita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Anno d\'uscita: '.$elemento["annoUscita"].'</small>
																	</div>
														'); // Costruisco un riquadro per ogni video (pt.1)
													
													$query="SELECT G.tipo
														FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
														WHERE GV.idVideo=".$elemento["id"]."
														ORDER BY G.tipo"; // Preparazione query: Categorie video
													if($generi=$conn->query($query)) { // Query effettuata con successo
														if ($generi->num_rows>0) { // Almeno un risultato
															echo ('
																<div class="d-flex flex-row-reverse align-items-center">
																	<small class="text-muted">Categorie:
																');
															$i=0;
															while ($genere = $generi->fetch_assoc()) {
																echo $genere["tipo"];
																if($i<($generi->num_rows-1))
																	echo ', ';
																$i++;
															}
															echo ('
																	</small>
																</div>
															'); // Costruisco un riquadro per ogni video (pt.2)
														}
														$generi->free(); // Dealloco l'oggetto
													}

													echo('
																</div>
															</div>
														</div>
													'); // Costruisco un riquadro per ogni video (pt.3)
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun video è ancora stato recensito</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di video recensiti
											}
											$video->free();	// Dealloco l'oggetto
										}

										// MIGLIORI FILM
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, V.annoUscita, V.nazionalita, AVG(RV.voto) mediaVoti
										FROM recensionivideo RV JOIN video V ON V.id=RV.idVideo
										WHERE RV.idAdmin IS NOT NULL AND V.selettore=1
										GROUP BY RV.idVideo
										ORDER BY mediaVoti DESC
										LIMIT 8"; // Preparazione Query: Migliori film (base voto)
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Film</h2>
											</div>
											'); // Titolo

										if ($video=$conn->query($query)) { // Query effettuata con successo 
											if ($video->num_rows>0) { // Almeno un risultato 
												while ($film = $video->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$film["id"].',5,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$film["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$film["nome"].'">
																	<p class="card-text">'.$film["nome"].'</p>
																	<p class="card-text-description">'.$film["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$film["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($film["mediaVoti"],2).'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalita: '.$film["nazionalita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Anno d\'uscita: '.$film["annoUscita"].'</small>
																	</div>
														'); // Costruisco un riquadro per ogni film (pt.1)
													
													$query="SELECT G.tipo
													FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
													WHERE GV.idVideo=".$film["id"]."
													ORDER BY G.tipo"; // Preparazione query: Categorie film
													if($generi=$conn->query($query)) { // Query effettuata con successo
														if ($generi->num_rows>0) { // Almeno un risultato
															echo ('
																<div class="d-flex flex-row-reverse align-items-center">
																	<small class="text-muted">Categorie:
																');
															$i=0;
															while ($genere = $generi->fetch_assoc()) {
																echo $genere["tipo"];
																if($i<($generi->num_rows-1))
																	echo ', ';
																$i++;
															}
															echo ('
																	</small>
																</div>
															'); // Costruisco un riquadro per ogni film (pt.2)
														}
														$generi->free(); // Dealloco l'oggetto
													}

													echo('	
																</div>
															</div>
														</div>
														'); // Costruisco un riquadro per ogni film (pt.3)
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun film è ancora stato recensito</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di film recensiti
											}
											$video->free();	// Dealloco l'oggetto
										}
										echo ('	
														<div class="container" onclick="passa_a(null,1,1,0,null,null)"><a href="#">Vedi tutti</a></div>	
											'); // Link che mostra tutti i film (case 1) 

										// MIGLIORI SERIE TV
										$query="SELECT S.*, AVG(RS.voto) mediaVoti, COUNT(DISTINCT V.stagione) nStagioni, COUNT(DISTINCT V.id) nEpisodi, V.nazionalita, MIN(V.annoUscita) annoUscita, MAX(V.annoUscita) annoFine
										FROM recensioniserie RS JOIN serie S ON S.id=RS.idSerie JOIN video V on V.idserie=S.id
										WHERE RS.idAdmin IS NOT NULL
										GROUP BY S.id
										ORDER BY mediaVoti DESC
										LIMIT 8"; // Preparazione Query: Migliori serie TV (base voto)
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Serie TV</h2>
											</div>
											'); // Titolo

										if ($video=$conn->query($query)) { // Query effettuata con successo
											if ($video->num_rows>0) { // Almeno un risultato 
												while ($serie = $video->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$serie["id"].',6,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$serie["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$serie["nome"].'">
																	<p class="card-text">'.$serie["nome"].'</p>
																	<p class="card-text-description">'.$serie["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Stagioni: '.$serie["nStagioni"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi totali: '.$serie["nEpisodi"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($serie["mediaVoti"],2).'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalita: '.$serie["nazionalita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
														');
															if($serie["annoUscita"]!=$serie["annoFine"])
																echo '	<small class="text-muted">Anni di produzione: '.$serie["annoUscita"].'-'.$serie["annoFine"].'</small>';
																else
																echo '	<small class="text-muted">Anno di produzione: '.$serie["annoUscita"].'</small>';
															echo '</div>'; // Costruisco un riquadro per ogni serie TV (pt.1)
														 
														$query="SELECT DISTINCT G.tipo
														FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
														WHERE GV.idVideo IN (
																			 SELECT V.id
																			 FROM video V JOIN serie S ON S.id=V.idSerie
																			 WHERE S.id=".$serie["id"].")
														ORDER BY G.tipo"; // Preparazione query: Categorie serie TV
														if($generi=$conn->query($query)) { // Query effettuata con successo
															if ($generi->num_rows>0) { // Almeno un risultato
																echo ('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Categorie:
																	'); 
																$i=0;
																while ($genere = $generi->fetch_assoc()) {
																	echo $genere["tipo"];
																	if($i<($generi->num_rows-1))
																		echo ', ';
																	$i++;
																}
																echo ('
																		</small>
																	</div>
																'); // Costruisco un riquadro per ogni serie TV (pt.2)
															}
															$generi->free(); // Dealloco l'oggetto
														}

												echo ('	
																</div>
															</div>
														</div>
													'); // Costruisco un riquadro per ogni serie TV (pt.3)
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessuna serie TV è ancora stata recensita</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di serie TV recensite
											}
											$video->free(); // Dealloco l'oggetto
										}
										echo ('	
														<div class="container" onclick="passa_a(null,2,1,null,null,null)"><a href="#">Vedi tutte</a></div>	
											'); // Link che mostra tutte le serie (case 2)

										// MIGLIORI SAGHE
										$query="SELECT AVG (Medie.mediaVoti) media, COUNT(DISTINCT V.id) nFilm, S.id, S.nome, V.nazionalita, MIN(V.annoUscita) annoUscita, MAX(V.annoUscita) annoFine
										FROM (
												SELECT  AVG(R.voto) mediaVoti, V.*
												FROM recensionivideo R JOIN video V ON V.id=R.idVideo JOIN saghe S ON S.id=V.idSaga
												WHERE R.idAdmin IS NOT NULL
												GROUP BY V.id
											) Medie
										JOIN saghe S ON S.id=Medie.idSaga
                                        JOIN video V ON V.idSaga=Medie.idSaga
										GROUP BY Medie.idSaga
										ORDER BY Medie.mediaVoti DESC
										LIMIT 8"; // Preparazione Query: Migliori saghe ( in base al voto)
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Saghe</h2>
											</div>
											'); // Titolo

										if ($saghe=$conn->query($query)) { // Query effettuata con successo
											if ($saghe->num_rows>0) { // Almeno un risultato 
												while ($saga = $saghe->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',7,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/saghe/'.$saga["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/saghe/default.jpg\';" alt="Locandina di '.$saga["nome"].'">
																	<p class="card-text">'.$saga["nome"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Film: '.$saga["nFilm"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($saga["media"],2).'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$saga["nazionalita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
														'); 

														if($saga["annoUscita"]!=$saga["annoFine"])
															echo '	<small class="text-muted">Anni di produzione: '.$saga["annoUscita"].'-'.$saga["annoFine"].'</small>';
															else
															echo '	<small class="text-muted">Anno di produzione: '.$saga["annoUscita"].'</small>';
														echo '</div>'; // Costruisco un riquadro per ogni saga (pt.1)

														$query="SELECT DISTINCT G.tipo
														FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
														WHERE GV.idVideo IN (
																			 SELECT V.id
																			 FROM video V JOIN saghe S ON S.id=V.idSaga
																			 WHERE S.id=".$saga["id"].")
														ORDER BY G.tipo"; // Preparazione query: Categorie saga
														if($generi=$conn->query($query)) { // Query effettuata con successo
															if ($generi->num_rows>0) { // Almeno un risultato
																echo ('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Categorie:
																	'); 
																$i=0;
																while ($genere = $generi->fetch_assoc()) {
																	echo $genere["tipo"];
																	if($i<($generi->num_rows-1))
																		echo ', ';
																	$i++;
																}
																echo ('
																		</small>
																	</div>
																'); // Costruisco un riquadro per ogni saga (pt.2)
															}
															$generi->free(); // Dealloco l'oggetto
														}

														echo ('
																</div>
															</div>
														</div>
													');
												} // Costruisco un riquadro per ogni saga (pt.3)
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessuna saga è ancora stata recensita</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di saghe recensite
											}
											$saghe->free(); // Dealloco l'oggetto
										}
										echo ('	
														<div class="container" onclick="passa_a(null,3,1,null,0,null,null)"><a href="#">Vedi tutte</a></div>	
											'); // Link che mostra tutte le saghe (case 3) 

										// MIGLIORI DOCUMENTARI 
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, V.nazionalita, V.annoUscita, AVG(RV.voto) mediaVoti
										FROM recensionivideo RV JOIN video V ON V.id=RV.idVideo
										WHERE RV.idAdmin IS NOT NULL AND V.selettore=3
										GROUP BY RV.idVideo
										ORDER BY mediaVoti DESC
										LIMIT 8"; // Preparazione Query: Migliori documentari (base voto) 
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Documentari</h2>
											</div>
											'); // Titolo

										if ($video=$conn->query($query)) { // Query effettuata con successo
											if ($video->num_rows>0) { // Almeno un risultato
												while ($documentario = $video->fetch_assoc()) {
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$documentario["id"].',5,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$documentario["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$documentario["nome"].'">
																	<p class="card-text">'.$documentario["nome"].'</p>
																	<p class="card-text-description">'.$documentario["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$documentario["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($documentario["mediaVoti"],2).'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$documentario["nazionalita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Anno di produzione: '.$documentario["annoUscita"].'</small>
																	</div>
														'); // Costruisco un riquadro per ogni documentario (pt.1)
														
														$query="SELECT G.tipo
														FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
														WHERE GV.idVideo=".$documentario["id"]."
														ORDER BY G.tipo"; // Preparazione query: Categorie documentario
														if($generi=$conn->query($query)) { // Query effettuata con successo
															if ($generi->num_rows>0) { // Almeno un risultato
																echo ('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Categorie:
																	');
																$i=0;
																while ($genere = $generi->fetch_assoc()) {
																	echo $genere["tipo"];
																	if($i<($generi->num_rows-1))
																		echo ', ';
																	$i++;
																}
																echo ('
																		</small>
																	</div>
																'); // Costruisco un riquadro per ogni documentario (pt.2)
															}
															$generi->free(); // Dealloco l'oggetto
														}

													echo ('
																</div>
															</div>
														</div>
														
													');  // Costruisco un riquadro per ogni documentario (pt.3)
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun documentario ancora stato recensito</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di documentari recensiti
											}
											$video->free(); // Dealloco l'oggetto
										}
										echo ('	
														<div class="container" onclick="passa_a(null,4,1,0,null,null)"><a href="#">Vedi tutti</a></div>	
											'); // Link che mostra tutte i documentari

										$conn->close(); // Chiudo la connessione al DB
										break;
									

									case 1: /* 
										********************
										*** TUTTI I FILM ***
										********************
										*/
										$conn=dbConn(); // Connessione al DB
										$pagina=$_GET["pagina"]; // Numero pagina corrente 
										$stato=$_GET["stato"];// Stato della pagina corrente 
										$ordinamento=intval($_GET["ordinamento"]); // Ordinamento corrente 
										
										switch(floor($ordinamento/2)) { // Tipo di ordinamento
											case 0:
												$ord="V.nome";
												break;
											case 1:
												$ord="mediaVoti";
												break;
											case 2:
												$ord="V.durata";
												break;
											case 3:
												$ord="V.annoUscita";
												break;
											case 4:
												$ord="V.nazionalita";
												break;
										}
										if($ordinamento%2==1) // Ordinamento Discendente
											$ord.=" DESC";
										if(floor($ordinamento/2)==1)
											$ord.=", V.id DESC";

										$nris=8; // Risultati da mostrare per pagina
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, V.annoUscita, V.nazionalita, AVG(RV.voto) mediaVoti
										FROM recensionivideo RV RIGHT JOIN video V ON V.id=RV.idVideo
										WHERE V.selettore=1
										GROUP BY V.id
										ORDER BY $ord
										LIMIT $nris
										OFFSET ".($nris*($pagina-1)); // Preparazione Query: Tutti i film 
										$nFilm=$conn->query("SELECT COUNT(*) nFilm FROM video WHERE video.selettore=1")->fetch_assoc(); // Numero totale di film 

										echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Tutti i Film </h2>
											</div>
											'); // Titolo

										$valori=["Nome","Voto","Durata","Anno d'uscita", "Nazionalità"]; // Valori dell'ordinamento
										echo ('
											<div class="container"> Ordina per: 
												<select id="ord" onchange="passa_a(null,1,1,ord.value,null,null)">
											'); // Scelta dell'ordinamento
													for($i=0;$i<10;$i++) {
														echo '<option value="'.$i.'"';
														if($ordinamento==$i)
															echo 'selected>';
														else
															echo '>';
														echo $valori[$i/2];
														if($i%2==1)
															echo ' DECR';
														echo '</option>';
													}
										echo ('
												</select>
											</div>
										');
										
										if ($video=$conn->query($query)) { // Query effettuata con successo 
											if ($video->num_rows>0) { // Almeno un risultato 
												while ($film = $video->fetch_assoc()) {
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$film["id"].',5,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$film["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$film["nome"].'">
																	<p class="card-text">'.$film["nome"].'</p>
																	<p class="card-text-description">'.$film["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$film["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Anno d\'uscita: '.$film["annoUscita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$film["nazionalita"].'</small>
																	</div>
														');
													if($film["mediaVoti"]!=null)
														echo('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($film["mediaVoti"],2).'</small>
																	</div>
															'); // Costruisco un riquadro per ogni film (pt.1)

													$query="SELECT G.tipo
													FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
													WHERE GV.idVideo=".$film["id"]."
													GROUP BY G.tipo"; // Preparazione query: Categorie film
													if($generi=$conn->query($query)) { // Query effettuata con successo
														if ($generi->num_rows>0) { // Almeno un risultato
															echo ('
																<div class="d-flex flex-row-reverse align-items-center">
																	<small class="text-muted">Categorie:
																');
															$i=0;
															while ($genere = $generi->fetch_assoc()) {
																echo $genere["tipo"];
																if($i<($generi->num_rows-1))
																	echo ', ';
																$i++;
															}
															echo ('
																	</small>
																</div>
															'); // Costruisco un riquadro per ogni film (pt.2)
														}
														$generi->free(); // Dealloco l'oggetto
													}

													echo ('
															</div>
														</div>
													</div>
															'); // Costruisco un riquadro per ogni film (pt.3)
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun film trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di film
											}
											$video->free(); // Dealloco l'oggetto
										}
										echo "<div class='container'>";
										if ($pagina!=1) // Non è la prima pagina: Il tasto indietro funzionerà
											echo "<input type='button' value='<' / onclick='passa_a(null,1,".($pagina-1).",$ordinamento,null,null);'>";
										else // E' la prima pagina: Il tasto indietro non funzionerà
											echo "<input type='button' value='<' disabled />";

										for($i=1;$i<=ceil($nFilm["nFilm"]/$nris);$i++) { // Bottoni pagine
											echo "<button onclick='passa_a(null,1,$i,$ordinamento,null,null);' ";
											if($i==$pagina) // Se la pagina è la corrente la evidenzio
												echo "style='background-color: black; color: white;' disabled>$i";
											else
												echo ">$i";
											echo "</button>";
										}
										if ($pagina!=ceil($nFilm["nFilm"]/$nris)) // Non è l'ultima pagina : Il tasto avanti funzionerà
												echo "<input type='button' value='>' / onclick='passa_a(null,1,".($pagina+1).",$ordinamento,null,null);'>";
											else // E' l'ultima pagina : Il tasto avanti non funzionerà
												echo "<input type='button' value='>' disabled />";	
										echo ('
											</div>
											<div class="container">
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											</diV>
											'); // Bottone per tornare indietro

										$conn->close(); // Chiudo la connessione al DB
										break;

									case 2: /* 
										*************************
										*** TUTTE LE SERIE TV ***
										*************************
										*/

										$conn=dbConn(); // Connessione al DB
										$pagina=$_GET["pagina"]; // Numero pagina corrente 
										$stato=$_GET["stato"];// Stato della pagina corrente
										$ordinamento=intval($_GET["ordinamento"]); // Ordinamento corrente
										
										switch(floor($ordinamento/2)) { // Tipo di ordinamento
											case 0:
												$ord="S.nome";
												break;
											case 1:
												$ord="mediaVoti";
												break;
											case 2:
												$ord="nStagioni";
												break;
											case 3:
												$ord="nEpisodi";
												break;
											case 4:
												$ord="annoUscita";
												break;
											case 5:
												$ord="annoFine";
												break;
											case 6:
												$ord="V.nazionalita";
												break;
										}
										if($ordinamento%2==1) // Ordinamento decrescente
											$ord.=" DESC";
										if(floor($ordinamento/2)==1)
										$ord.=", S.id DESC";

										$nris=8; // Risultati da mostrare per pagina
										$query="SELECT S.*, MIN(V.annoUscita) annoUscita, MAX(V.annoUscita) annoFine, COUNT(DISTINCT V.id) nEpisodi, COUNT(DISTINCT V.stagione) nStagioni, AVG(RS.voto) mediaVoti, V.nazionalita
										FROM serie S INNER JOIN video V ON V.idSerie=S.id LEFT JOIN recensioniserie RS ON RS.idSerie=S.id
										GROUP BY S.id
										ORDER BY $ord
										LIMIT $nris
										OFFSET ".($nris*($pagina-1)); // Preparazione Query: Tutte le serie 
										$nSerie=$conn->query("SELECT COUNT(DISTINCT id) nSerie FROM serie S")->fetch_assoc(); // Numero totale delle serie
										echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Tutte le Serie</h2>
											</div>
											'); // Titolo

											$valori=["Nome","Voto","Numero Stagioni","Numero Episodi", "Anno d'uscita", "Anno di fine", "Nazionalità"]; // Valori dell'ordinamento
											echo ('
												<div class="container"> Ordina per: 
													<select id="ord" onchange="passa_a(null,2,1,ord.value,null,null)">
												'); // Scelta dell'ordinamento
											for($i=0;$i<14;$i++) {
												echo '<option value="'.$i.'"';
												if($ordinamento==$i)
													echo 'selected>';
												else
													echo '>';
												echo $valori[$i/2];
												if($i%2==1)
													echo ' DECR';
												echo '</option>';
											}
											echo ('
													</select>
												</div>
											');

										if ($serie=$conn->query($query)) { // Query effettuata con successo
											if ($serie->num_rows>0) { // Almeno un risultato
												while ($elemento = $serie->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Stagioni: '.$elemento["nStagioni"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi: '.$elemento["nEpisodi"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
													');
																	if($elemento["annoUscita"]!=$elemento["annoFine"])
																	echo '	<small class="text-muted">Anni di produzione: '.$elemento["annoUscita"].'-'.$elemento["annoFine"].'</small>';
																	else
																	echo '	<small class="text-muted">Anno di produzione: '.$elemento["annoUscita"].'</small>';
																	if($elemento["mediaVoti"]!=null)
																	echo('
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($elemento["mediaVoti"],2).'</small>
																	</div>
																	');
														echo ('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$elemento["nazionalita"].'</small>
																	</div>
															');

															$query="SELECT DISTINCT G.tipo
															FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
															WHERE GV.idVideo IN (
																				 SELECT V.id
																				 FROM video V JOIN serie S ON S.id=V.idSerie
																				 WHERE S.id=".$elemento["id"].")
															ORDER BY G.tipo"; // Preparazione query: Categorie serie TV
															if($generi=$conn->query($query)) { // Query effettuata con successo
																if ($generi->num_rows>0) { // Almeno un risultato
																	echo ('
																		<div class="d-flex flex-row-reverse align-items-center">
																			<small class="text-muted">Categorie:
																		'); 
																	$i=0;
																	while ($genere = $generi->fetch_assoc()) {
																		echo $genere["tipo"];
																		if($i<($generi->num_rows-1))
																			echo ', ';
																		$i++;
																	}
																	echo ('
																			</small>
																		</div>
																	'); // Costruisco un riquadro per ogni serie TV (pt.2)
																}
																$generi->free(); // Dealloco l'oggetto
															}

														echo ('
																</div>
															</div>
														</div>
														'); // Costruisco un riquadro per ogni serie TV (pt.3)
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi
											}
											$serie->free();	// Dealloco l'oggetto
										}
										
										echo "<div class='container'>";
										if ($pagina!=1) // Non è la prima pagina: Il tasto indietro funzionerà
											echo "<input type='button' value='<' / onclick='passa_a(null,2,".($pagina-1).",$ordinamento,null,null)'>";
										else // E' la prima pagina: Il tasto indietro non funzionerà
											echo "<input type='button' value='<' disabled />";
										for($i=1;$i<=ceil($nSerie["nSerie"]/$nris);$i++) { // Bottoni pagine
											echo "<button onclick='passa_a(null,2,$i,$ordinamento,null,null);'";
											if($i==$pagina) // Se la pagina è la corrente la evidenzio
												echo " style='background-color: black; color: white;' disabled>$i";
											else
												echo ">$i";
											echo "</button>";
										}
										if ($pagina!=ceil($nSerie["nSerie"]/$nris)) // Non è l'ultima' pagina: Il tasto avanti funzionerà
												echo "<input type='button' value='>' / onclick='passa_a(null,$stato,".($pagina+1).",$ordinamento,null,null)'>";
											else // E' l'ultima' pagina: Il tasto avanti non funzionerà
												echo "<input type='button' value='>' disabled />";	
											echo ('
											</div>
											<div class="container">
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											</diV>
											');

										$conn->close(); // Chiudo la connessione al DB
										break;

									case 3: /* 
										**********************
										*** TUTTE LE SAGHE ***
										**********************
										*/

										$conn=dbConn(); // Connessione al DB
										$pagina=$_GET["pagina"];  // Numero pagina corrente  
										$stato=$_GET["stato"]; // Stato della pagina corrente  
										$ordinamento=intval($_GET["ordinamento"]);  // Ordinamento corrente  
										
										switch(floor($ordinamento/2)) { // Tipo d'ordinamento
											case 0:
												$ord="S.nome";
												break;
											case 1:
												$ord="mediaVoti";
												break;
											case 2:
												$ord="nFilm";
												break;
											case 3:
												$ord="annoUscita";
												break;
											case 4:
												$ord="annoFine";
												break;
											case 5:
												$ord="V.nazionalita";
												break;
										}
										if($ordinamento%2==1) // Ordinamento discendente
											$ord.=" DESC";
										if(floor($ordinamento/2)==1)
											$ord.=", S.id DESC";

										$nris=8;  // Riusltati da mostrare per pagina  
										$query="SELECT S.id, S.nome, COUNT(DISTINCT V.id) nFilm, V.nazionalita, MIN(V.annoUscita) annoUscita, MAX(V.annoUscita) annoFine, AVG(RV.voto) mediaVoti
										FROM saghe S JOIN video V ON S.id=V.idSaga LEFT JOIN recensionivideo RV ON RV.idVideo=V.id
										GROUP BY S.id
										ORDER BY $ord
										LIMIT $nris
										OFFSET ".($nris*($pagina-1));  // Preparazione Query: Tutte le saghe  
										
										$nSaghe=$conn->query("SELECT COUNT(DISTINCT S.id) nSaghe FROM saghe S JOIN video V ON S.id=V.idSaga")->fetch_assoc();  // Numero totale di saghe   										
										
										echo ('
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Tutte le Saghe</h2>
											</div>
											'); // Titolo

											$valori=["Nome", "Voto", "Numero Film", "Anno d'uscita", "Anno di fine", "Nazionalità"]; // Valori ordinamento
											echo ('
												<div class="container"> Ordina per: 
													<select id="ord" onchange="passa_a(null,3,1,ord.value,null,null)">
												'); // Scelta dell'ordinamento
											for($i=0;$i<12;$i++) {
												echo '<option value="'.$i.'"';
												if($ordinamento==$i)
													echo 'selected>';
												else
													echo '>';
												echo $valori[$i/2];
												if($i%2==1)
													echo ' DECR';
												echo '</option>';
											}
											echo ('
													</select>
												</div>
											');
										
										if ($saghe=$conn->query($query)) {  // Query effettuata con successo  
											if ($saghe->num_rows>0) {  // Almeno un risultato  
												while ($saga = $saghe->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',7,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/saghe/'.$saga["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/saghe/default.jpg\';" alt="Locandina di '.$saga["nome"].'">
																	<p class="card-text">'.$saga["nome"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Film: '.$saga["nFilm"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$saga["nazionalita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
														');
															if($saga["annoUscita"]!=$saga["annoFine"])
																echo '	<small class="text-muted">Anni di produzione: '.$saga["annoUscita"].'-'.$saga["annoFine"].'</small>';
																else
																echo '	<small class="text-muted">Anno di produzione: '.$saga["annoUscita"].'</small>';
													echo '			</div>';

															if($saga["mediaVoti"]!=null)
																echo ('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto: '.round($saga["mediaVoti"],2).'</small>
																	</div>
																'); // Costruisco un riquadro per ogni saga (pt.1)

													$query="SELECT DISTINCT G.tipo
														FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
														WHERE GV.idVideo IN (
																			 SELECT V.id
																			 FROM video V JOIN saghe S ON S.id=V.idSaga
																			 WHERE S.id=".$saga["id"]."
														ORDER BY G.tipo)"; // Preparazione query: Categorie saga
														if($generi=$conn->query($query)) { // Query effettuata con successo
															if ($generi->num_rows>0) { // Almeno un risultato
																echo ('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Categorie:
																	'); 
																$i=0;
																while ($genere = $generi->fetch_assoc()) {
																	echo $genere["tipo"];
																	if($i<($generi->num_rows-1))
																		echo ', ';
																	$i++;
																}
																echo ('
																		</small>
																	</div>
																'); // Costruisco un riquadro per ogni saga (pt.2)
															}
															$generi->free(); // Dealloco l'oggetto
														}

													echo ('
																</div>
															</div>
														</div>
														'); // Costruisco un riquadro per ogni film 
												}
											}
											else {  
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun film è ancora stato recensito</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di film  
											}
											$saghe->free();	// Dealloco l'oggetto
										}
										
										echo "<div class='container'>";
										if ($pagina!=1) // Non è la prima pagina: Il tasto indietro funzionerà
											echo "<input type='button' value='<' / onclick='passa_a(null,$stato,".($pagina-1).",$ordinamento,null,null)'>";
										else // E' la prima pagina: Il tasto indietro non funzionerà
											echo "<input type='button' value='<' disabled />";
										for($i=1;$i<=ceil($nSaghe["nSaghe"]/$nris);$i++) { // Bottoni pagine
											echo "<button onclick='passa_a(null,$stato,$i,$ordinamento,null,null)';";
											if($i==$pagina) // Se la pagina è la corrente la evidenzio
												echo " style='background-color: black; color: white;' disabled>$i";
											else
												echo ">$i";
											echo "</button>";
										}
										if ($pagina!=ceil($nSaghe["nSaghe"]/$nris)) // Non è l'ultima' pagina: Il tasto avanti funzionerà
												echo "<input type='button' value='>' / onclick='passa_a(null,$stato,".($pagina+1).",$ordinamento,null,null)'>";
											else // E' l'ultima' pagina: Il tasto avanti non funzionerà
												echo "<input type='button' value='>' disabled />";	
											echo ('
											</div>
											<div class="container">
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											</diV>
											');

										$conn->close(); // Chiudo la connessione al DB
										break;
									
									case 4: /* 
										***************************
										*** TUTTI I DOCUMENTARI ***
										***************************
										*/
										$conn=dbConn(); // Connessione al DB
										$pagina=$_GET["pagina"]; // Numero pagina corrente 
										$stato=$_GET["stato"];// Stato della pagina corrente 
										$ordinamento=intval($_GET["ordinamento"]); // Ordinamento corrente 
										
										switch(floor($ordinamento/2)) { // Tipo di ordinamento
											case 0:
												$ord="V.nome"; 
												break;
											case 1:
												$ord="mediaVoti";
												break;
											case 2:
												$ord="V.durata";
												break;
											case 3:
												$ord="V.annoUscita";
												break;
											case 4:
												$ord="V.nazionalita";
												break;
										}
										if($ordinamento%2==1) // Ordinamento Discendente
											$ord.=" DESC";
										if(floor($ordinamento/2)==1)
											$ord.=", V.id DESC";

										$nris=8; // Risultati da mostrare per pagina
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, V.annoUscita, V.nazionalita, AVG(RV.voto) mediaVoti
										FROM recensionivideo RV RIGHT JOIN video V ON V.id=RV.idVideo
										WHERE V.selettore=3
										GROUP BY V.id
										ORDER BY $ord
										LIMIT $nris
										OFFSET ".($nris*($pagina-1)); // Preparazione Query: Tutti i documentari 
										$nDocumentari=$conn->query("SELECT COUNT(*) nDocumentari FROM video WHERE video.selettore=3")->fetch_assoc(); // Numero totale di documentari 

										echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Tutti i Documentari</h2>
											</div>
											'); // Titolo

										$valori=["Nome","Voto","Durata","Anno d'uscita", "Nazionalità"]; // Valori dell'ordinamento
										echo ('
											<div class="container"> Ordina per: 
												<select id="ord" onchange="passa_a(null,4,1,ord.value,null,null)">
											'); // Scelta dell'ordinamento
													for($i=0;$i<10;$i++) {
														echo '<option value="'.$i.'"';
														if($ordinamento==$i)
															echo 'selected>';
														else
															echo '>';
														echo $valori[$i/2];
														if($i%2==1)
															echo ' DECR';
														echo '</option>';
													}
										echo ('
												</select>
											</div>
										');
										
										if ($documentari=$conn->query($query)) { // Query effettuata con successo 
											if ($documentari->num_rows>0) { // Almeno un risultato 
												while ($documentario = $documentari->fetch_assoc()) {
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$documentario["id"].',5,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$documentario["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$documentario["nome"].'">
																	<p class="card-text">'.$documentario["nome"].'</p>
																	<p class="card-text-description">'.$documentario["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$documentario["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Anno d\'uscita: '.$documentario["annoUscita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$documentario["nazionalita"].'</small>
																	</div>
														');
													if($documentario["mediaVoti"]!=null)
														echo('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($documentario["mediaVoti"],2).'</small>
																	</div>
															'); // Costruisco un riquadro per ogni film (pt.1)

													$query="SELECT G.tipo
													FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
													WHERE GV.idVideo=".$documentario["id"]."
													ORDER BY G.tipo"; // Preparazione query: Categorie documentario
													if($generi=$conn->query($query)) { // Query effettuata con successo
														if ($generi->num_rows>0) { // Almeno un risultato
															echo ('
																<div class="d-flex flex-row-reverse align-items-center">
																	<small class="text-muted">Categorie:
																');
															$i=0;
															while ($genere = $generi->fetch_assoc()) {
																echo $genere["tipo"];
																if($i<($generi->num_rows-1))
																	echo ', ';
																$i++;
															}
															echo ('
																	</small>
																</div>
															'); // Costruisco un riquadro per ogni documentario (pt.2)
														}
														$generi->free(); // Dealloco l'oggetto
													}

													echo ('
															</div>
														</div>
													</div>
															'); // Costruisco un riquadro per ogni documentario (pt.3)
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun documentario trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di documentari
											}
											$documentari->free(); // Dealloco l'oggetto
										}
										echo "<div class='container'>";
										if ($pagina!=1) // Non è la prima pagina: Il tasto indietro funzionerà
											echo "<input type='button' value='<' / onclick='passa_a(null,4,".($pagina-1).",$ordinamento,null,null);'>";
										else // E' la prima pagina: Il tasto indietro non funzionerà
											echo "<input type='button' value='<' disabled />";

										for($i=1;$i<=ceil($nDocumentari["nDocumentari"]/$nris);$i++) { // Bottoni pagine
											echo "<button onclick='passa_a(null,4,$i,$ordinamento,null,null);'";
											if($i==$pagina) // Se la pagina è la corrente la evidenzio
												echo "style='background-color: black; color: white;' disabled>$i";
											else
												echo ">$i";
											echo "</button>";
										}
										if ($pagina!=ceil($nDocumentari["nDocumentari"]/$nris)) // Non è l'ultima pagina : Il tasto avanti funzionerà
												echo "<input type='button' value='>' / onclick='passa_a(null,4,".($pagina+1).",$ordinamento,null,null);'>";
											else // E' l'ultima pagina : Il tasto avanti non funzionerà
												echo "<input type='button' value='>' disabled />";	
										echo ('
											</div>
											<div class="container">
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											</diV>
											'); // Bottone per tornare indietro

										$conn->close(); // Chiudo la connessione al DB
										break;
									
									case 5: /* 
										**********************
										*** DETTAGLI VIDEO ***
										**********************
										*/
										$id=$_GET["id"]; // idVideo
										$conn=dbConn(); // Connessione al DB
										
										if(isset($_POST["rate"])&&isset($_SESSION["idUser"])) { // E' stato dato un voto 
											$voto=$_POST["rate"];
											if(isset($_POST["rec"])&&$_POST["rec"]!="") // E' stata data una recensione 
												$rec="'".filter_var($_POST["rec"], FILTER_SANITIZE_STRING)."'";
											else
												$rec="null";
											$query="SELECT * FROM recensionivideo WHERE idVideo=$id AND idUtente=$_SESSION[idUser]"; // Controllo che non abbia già fatto una recensione 
											$controllo=$conn->query($query);
											if($voto!="ELIMINA"&&$voto!="VERIFICA") { // Pubblico o modifico la recensione 
												if($controllo->num_rows==0){ // Non ha già recensito:  
													$recens="INSERT INTO recensionivideo VALUES ($id,$_SESSION[idUser],'$voto',$rec,null)";
													
													if($conn->query($recens)) // Inserimento nel DB riuscito 
														echo "<script type='text/javascript'>alert('La tua recensione è stata inserita!');</script>";
													else // Inserimento nel DB NON riuscito 
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
												}
												else{
													$recens="UPDATE recensionivideo SET voto='$voto', testo=$rec, idAdmin=null 
													WHERE idVideo=$id AND idUtente=$_SESSION[idUser]";
													
													if($conn->query($recens)) // Modifica del DB riuscita 
														echo "<script type='text/javascript'>alert('La tua recensione è stata aggiornata!');</script>";
													else // Modifica del DB NON riuscita 
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
											else{
												if($voto=="ELIMINA") { // Eliminazione dal DB riuscita 
													$recens="DELETE FROM recensionivideo WHERE idVideo=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else // Modifica del DB riuscita
												{
													$recens="UPDATE recensionivideo SET idAdmin=$_SESSION[idUser] WHERE idVideo=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}
										
										if(isset($_POST["cur"])&&isset($_SESSION["idUser"])) { // E' stata iserita una curiosita 
											if(isset($_POST["check"]))
												$check=$_POST["check"];
											$cur="'".filter_var($_POST["cur"], FILTER_SANITIZE_STRING)."'";
											if($check!="ELIMINA"&&$check!="VERIFICA") { // Pubblico o modifico la curiosita 
												
												$query="INSERT INTO curiositavideo (idVideo,idUtente,testo,idAdmin) VALUES ($id,$_SESSION[idUser],$cur,null)";
												if($conn->query($query)) // Inserimento nel DB riuscito 
													echo "<script type='text/javascript'>alert('La tua curiosita è stata inserita!');</script>";
												else // Inserimento nel DB NON riuscito 
													echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
											
											}
											else{
												if($check=="ELIMINA") { // Eliminazione del DB riuscita 
													$query="DELETE FROM curiositavideo WHERE id=$_POST[idCur]";
													//echo $query;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La curiosita è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else
												{
													$query="UPDATE curiositavideo SET idAdmin=$_SESSION[idUser] WHERE id=$_POST[idCur]";
													//echo $query;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La curiosita è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}
										
										$query="SELECT V.id,V.nome,V.durata,V.idSaga,V.idSerie,V.numero,V.stagione,V.sinossi, V.annoUscita, V.nazionalita, Se.nome nomeSe,Sa.nome nomeSa 
										FROM video V LEFT JOIN serie Se ON V.idSerie=Se.id LEFT JOIN saghe Sa ON Sa.id=V.idSaga 
										WHERE V.id=$id;"; // Preparazione Query: Dettagli video 
										$risultati=$conn->query($query);
										$video = $risultati->fetch_assoc();
										$risultati->free(); // Dealloco l'oggetto

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$video["nome"].'</h1>
													<img src="images/video/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$video["nome"].'">
													<div class="container-sm col-md-6 py2">
														<p class="card-text" style="text-align:center !important">'.$video["sinossi"].'</p>
												</div>
												<div class="d-flex justify-content-end bd-highlight mb-3">
													<small class="text-muted">Durata: '.$video["durata"].' minuti</small>
												</div>
												<div class="d-flex justify-content-end bd-highlight mb-3">
													<small class="text-muted">Anno d\'uscita: '.$video["annoUscita"].' minuti</small>
												</div>
												<div class="d-flex justify-content-end bd-highlight mb-3">
													<small class="text-muted">Nazionalità: '.$video["nazionalita"].' minuti</small>
												</div>												
											'); // Riquadro Dettagli Video
										
											$query="SELECT G.tipo
											FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
											WHERE GV.idVideo=".$video["id"]."
											ORDER BY G.tipo"; // Preparazione query: Categorie film
											if($generi=$conn->query($query)) { // Query effettuata con successo
												if ($generi->num_rows>0) { // Almeno un risultato
													echo ('
														<div class="d-flex flex-row-reverse align-items-center">
															<small class="text-muted">Categorie:
														');
													$i=0;
													while ($genere = $generi->fetch_assoc()) {
														echo $genere["tipo"];
														if($i<($generi->num_rows-1))
															echo ', ';
														$i++;
													}
													echo ('
															</small>
														</div>
														<div class="row">
													'); // Costruisco un riquadro per ogni categoria
												}
												$generi->free(); // Dealloco l'oggetto
											}	

										if($video["idSerie"]!=null)
											echo ('
												<div class="container text-left">
													<p class="card-text" onclick="passa_a('.$video["idSerie"].',6,null,null,null,null);"><strong>Serie: </strong><a href="#"> '.$video["nomeSe"].' ('.$video["stagione"].'X'.$video["numero"].')</a></p>
												</div>
											'); // Costruisco  riquadro Serie
										else if($video["idSaga"]!=null)
											echo ('
													<div class="container text-left">
														<p class="card-text" onclick="passa_a('.$video["idSaga"].',7,null,null,null,null);"><strong>Saga: </strong><a href="#"> '.$video["nomeSa"].' ('.$video["numero"].'° film)</a></p>
													</div>
												'); // Costruisco  riquadro Saga
										
										$query="SELECT AV.idPersona, Per.nome, Per.cognome, Pggi.nome nomeP 
										FROM attorivideo AV JOIN video V ON AV.idVideo=V.id JOIN persone Per ON Per.id=AV.idPersona 
										LEFT JOIN interpretazioni I ON I.idPersona=Per.id LEFT JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										LEFT JOIN comparizioni C ON C.idVideo=V.id AND Pggi.id=C.idPersonaggio
										WHERE V.id=$id AND (Pggi.id IN (SELECT idPersonaggio FROM comparizioni WHERE idVideo = $id) OR Pggi.id IS NULL)"; // Preparazione Query: Attori Film 

										if ($attori=$conn->query($query)) { // Risultati della query 
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												');
											if ($attori->num_rows>0) { // Almeno 1 risultato
												while ($attore = $attori->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["idPersona"].',8,null,null,null,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														'); // Costruisco un riquadro per ogni attore 
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>'); // Riquadro personaggio
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT RV.idPersona, Per.nome, Per.cognome
										FROM registivideo RV JOIN video V ON RV.idVideo=V.id JOIN persone Per ON Per.id=RV.idPersona 
										WHERE V.id=$id"; // Preparazione Query: Registi Film 

										if ($registi=$conn->query($query)) { // Risultati della query 
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												');
											if ($registi->num_rows>0) { // Almeno 1 risultato
												while ($regista = $registi->fetch_assoc()) { 
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["idPersona"].',8,null,null,null,null)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													');
												} // Costruisco un riquadro per ogni regista 
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											
											$registi->free(); // Dealloco l'oggetto
										}
																				
										$query="SELECT PV.idPersona, Per.nome, Per.cognome
										FROM produttorivideo PV JOIN video V ON PV.idVideo=V.id JOIN persone Per ON Per.id=PV.idPersona 
										WHERE V.id=$id"; // Preparazione Query: Produttori Film 

										if ($produttori=$conn->query($query)) { // Risultati della query 
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												');
											if ($produttori->num_rows>0) { // Almeno 1 elemento
												while ($produttore = $produttori->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["idPersona"].',8,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni produttore 
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											
											$produttori->free(); // Dealloco l'oggetto
										}

										$query="SELECT P.* 
										FROM video V JOIN comparizioni C ON V.id=C.idVideo JOIN personaggi P ON P.id=C.idPersonaggio 
										WHERE V.id=$id"; // Preparazione Query: Personaggi Film 

										if ($personaggi=$conn->query($query)) { // Risultati della query 
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												');
											if ($personaggi->num_rows>0) { // Alemno 1 elemento
												while ($personaggio = $personaggi->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													');
												} // Costruisco un riquadro per ogni personaggio 
											}
											else { 
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
										$personaggi->free(); // Dealloco l'oggetto
										}
										
										
										if($_SESSION["login"]==1) { // Utente loggato
											$query="SELECT voto, testo, username FROM recensionivideo LEFT JOIN utenti ON idAdmin=id WHERE idVideo=$id AND idUtente=$_SESSION[idUser]"; // Preparazione Query: Recensione video da parte dell'utente
											$recensione=$conn->query($query);
											if($recensione->num_rows==0) { // Non ha ancora recensito il film
												echo('
													<div class="container text-center"> 
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#exampleModalCenter">
														  Lascia una recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Recensioni</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10" />
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9" />
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8" />
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7" />
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6" />
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5" />
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4" />
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3" />
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2" />
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1" />
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255" placeholder="Scrivi la tua recensione"></textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button" onclick="recens('.$id.')" class="btn btn-primary">Salva recensione</button>
															  </div>
															</div>
														  </div>
														</div>
													</div>'); // Finestra modale per lasciare la recensione
											}
											else { // Ha già lasciato una recensione
												$rec=$recensione->fetch_assoc();
												echo('
													<div class="container text-center"> 
														<h4>Il tuo voto è: '.$rec['voto'].'/10<label style="color:#ffc700">★</label></h4>
														<div class="container-sm col-md-6 mt-2 mb-2 py2">
															<p class="card-text" style="text-align:center !important">'.$rec["testo"].'</p>
														</div>
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#exampleModalCenter">
														  Modifica la tua recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10"');
																	if($rec["voto"]==10)
																		echo 'checked';
																	echo '/>
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9"';
																	if($rec["voto"]==9)
																		echo 'checked';
																	echo '/>
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8"';
																	if($rec["voto"]==8)
																		echo 'checked';
																	echo '/>
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7"';
																	if($rec["voto"]==7)
																		echo 'checked';
																	echo '/>
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6"';
																	if($rec["voto"]==6)
																		echo 'checked';
																	echo '/>
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5"';
																	if($rec["voto"]==5)
																		echo 'checked';
																	echo '/>
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4"';
																	if($rec["voto"]==4)
																		echo 'checked';
																	echo '/>
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3"';
																	if($rec["voto"]==3)
																		echo 'checked';
																	echo '/>
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2"';
																	if($rec["voto"]==2)
																		echo 'checked';
																	echo '/>
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1"';
																	if($rec["voto"]==1)
																		echo 'checked';
																	echo ('/>
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255"  placeholder="Scrivi la tua recensione">'.$rec["testo"].'</textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#conferma">Elimina recensione</button>
																
																<button type="button" onclick="recens('.$id.')" class="btn btn-primary">Modifica recensione</button>
															  </div>
															</div>
														  </div>
														</div>
														
													</div>
													<!-- Modal -->
													<div class="modal fade" id="conferma" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalCenterTitle">Conferma</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
															Sei sicuro di voler eliminare la recensione? L\'operazione sarà irreversibile
														  </div>
														  <div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
															<button type="button" onclick="elimina('.$id.','.$_SESSION["idUser"].')" class="btn btn-primary">Sì</button>
														  </div>
														</div>
													  </div>
													</div>'); // Finestra modale per la modifica della recensione
											}
										}
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Recensioni degli utenti</h2>
													</div>
												');
										$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
										FROM recensionivideo R 
										INNER JOIN utenti U ON U.id=R.idUtente
										LEFT JOIN utenti A ON A.id=R.idAdmin
										WHERE idVideo=$id
										ORDER BY R.idAdmin DESC,R.idUtente
										LIMIT 4;"; // Preparazione query: Recensioni video (MAX 4)
										$recensioni=$conn->query($query);
										if($recensioni->num_rows==0) { 
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
														</div>
													</div>
												</div>'; // COmunicazione di mancanza di elementi
										}		
										else { // Almeno 1 elemento
											while($riga = $recensioni->fetch_assoc()) {
												echo 	
													'<div class="col-md-3 py2">
														<div class="card h-100 mb-4 shadow-sm">
															<div class="card-body">
																<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>';
																if($riga["testo"]!=null)
																	echo '<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																if($riga["admin"]!=null)
																echo '
																	<div class="d-flex justify-content-end bd-highlight mb-3">
																		<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																	</div>';
															echo '</div>';
																	if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																		echo'
																			<div class="modal-footer">
																				<button type="button"  class="btn btn-secondary" data-toggle="modal" data-target="#conferma">Elimina</button>
																				<button type="button" class="btn btn-primary" onclick="verifica('.$riga["id"].')" data-dismiss="modal">Verifica</button>
																			</div>';
																echo '
														</div>
													</div>'; // Riquadro recensione
											}

											$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
											FROM recensionivideo R 
											INNER JOIN utenti U ON U.id=R.idUtente
											LEFT JOIN utenti A ON A.id=R.idAdmin
											WHERE R.testo IS NOT NULL AND idVideo=$id
											ORDER BY R.idAdmin DESC,R.idUtente;";
											$recensioni=$conn->query($query); // Preparazione query: Recensioni video (TUTTE)
											if($recensioni->num_rows>4) { // Più di 4 elementi
												echo '
												<div class="container text-center">
													<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#exampleModalScrollable">
													  Visualizza tutte le recensioni
													</button>
													<!-- Modal -->
													<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
													  <div class="modal-dialog modal-dialog-scrollable" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalScrollableTitle">Recensioni degli utenti</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">';
														  while($riga = $recensioni->fetch_assoc()){
															  echo '
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>
																		<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																		if($riga["admin"]!=null)
																			echo '
																				<div class="d-flex justify-content-end bd-highlight mb-3">
																					<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																				</div>';
																		echo '</div>';
																	
																	if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																		echo'
																			<div class="modal-footer">
																				<button type="button"  class="btn btn-secondary" onclick="elimina('.$id.','.$riga["id"].')" data-dismiss="modal">Elimina</button>
																				<button type="button" class="btn btn-primary" onclick="verifica('.$riga["id"].')" data-dismiss="modal">Verifica</button>
																			</div>';
																echo '
																	</div>'; // FInestra modale dove mostro ogni recensione
														  } 
														  echo '
														</div>
													  </div>
													</div>
												</div>
											</div>';
											
											}
												
											
										}
										
										/*
										**************************
										*****CURIOSITA' VIDEO*****
										**************************
										*/
										
										if($_SESSION["login"]==1) { // Utente loggato
											echo('
												<div class="container text-center"> 
													<!-- Button trigger modal -->
													<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#curiositaMod">
													  Lascia una curiosita
													</button>
					
													<!-- Modal -->
													
													<div class="modal fade" id="curiositaMod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-dialog-centered" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">Curiosità</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  
														  <div class="modal-body">
															<div class="form-group">
																<textarea id="textcur" name="textcur" class="form-control" rows="5" maxlength="255" placeholder="Scrivi la tua curiosità"></textarea>
															</div>
														  </div>
														  
														  <div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
															<button type="button" onclick="curios('.$id.')" class="btn btn-primary">Salva curiosità</button>
														  </div>
														</div>
													  </div>
													</div>
												</div>'); // Finestra modale per lasciare una curiosità
										}
											
										
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Curiosità degli utenti</h2>
													</div>
												'); // Titolo

										$query="SELECT C.id idCur, C.testo,U.username, U.id, A.username admin
										FROM curiositavideo C 
										INNER JOIN utenti U ON U.id=C.idUtente
										LEFT JOIN utenti A ON A.id=C.idAdmin
										WHERE idVideo=$id
										ORDER BY C.idAdmin DESC,C.idUtente
										LIMIT 4;"; // Preparazione query: Curiosità video (MAX 4)

										$curiosita=$conn->query($query);
										if($curiosita->num_rows==0) { 
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna curiosita</p>
														</div>
													</div>
												</div>'; // Comunicazione ancanza di elementi
										}		
										else { // Almeno 1 elemento
												while($riga = $curiosita->fetch_assoc()) {
													echo 	
														'<div class="col-md-3 py2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"].'</h6>';
																	
																	echo '
																		<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																		if(isset($_SESSION["idUser"])&&$_SESSION["idUser"]==$riga["id"]){
																			echo' <div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																		}
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1)
																			if($_SESSION["idUser"]!=$riga["id"]){
																				echo' <div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																			}
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null){
																			echo'	<button type="button" class="btn btn-primary" onclick="verificaC('.$riga["idCur"].')" data-dismiss="modal">Verifica</button>';
																		}
																		if((isset($_SESSION["idUser"])&&$_SESSION["idUser"]==$riga["id"])||(isset($_SESSION["admin"])&&$_SESSION["admin"]==1))
																			echo '</div>';
																	echo '
															</div>
														</div>'; // Riquadro curiosità video
												}
												$query="SELECT C.id idCur, C.testo,U.username, U.id, A.username admin
												FROM curiositavideo C 
												INNER JOIN utenti U ON U.id=C.idUtente
												LEFT JOIN utenti A ON A.id=C.idAdmin
												WHERE C.testo IS NOT NULL AND idVideo=$id
												ORDER BY C.idAdmin DESC,C.idUtente;";
												$curiosita=$conn->query($query); // Preparazione query: Curiosità video (TUTTE)

												if($curiosita->num_rows>4) { // Più di 4 elementi
													echo '
													<div class="container text-center">
														<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#curiositaScroll">
														  Visualizza tutte le recensioni
														</button>
														<!-- Modal -->
														<div class="modal fade" id="curiositaScroll" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-scrollable" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalScrollableTitle">Curiosita degli utenti</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  <div class="modal-body">';
															  while($riga = $curiosita->fetch_assoc()){
																  echo 	
																	'<div class="card h-100 mb-4 shadow-sm">
																			<div class="card-body">
																				<h6 class="mt-1 ml-2">'.$riga["username"].'</h6>';
																				
																				echo '
																					<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																				if($riga["admin"]!=null)
																				echo '
																					<div class="d-flex justify-content-end bd-highlight mb-3">
																						<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																					</div>';
																			echo '</div>';
																					if($_SESSION["idUser"]==$riga["id"]){
																						echo' <div class="modal-footer">
																								<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																					}
																					if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1)
																						if($_SESSION["idUser"]!=$riga["id"]){
																							echo' <div class="modal-footer">
																								<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																						}
																					if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null){
																						echo'	<button type="button" class="btn btn-primary" onclick="verificaC('.$riga["idCur"].')" data-dismiss="modal">Verifica</button>';
																					}
																					if($_SESSION["idUser"]==$riga["id"]||(isset($_SESSION["admin"])&&$_SESSION["admin"]==1))
																						echo '</div>';
																				echo '
																		</div>'; // Finestra modale per la visualizzazione di tutte le curiosità
															  }
															  echo '
															</div>
														  </div>
														</div>
													</div>
												</div>';
												
												}
										}
										
										$conn->close(); // Chiudo la connessione al DB
								
			echo ('
						</div>
					</div>
				</div>
				<div class="container"> 
					<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
				</div>
				');
									break;

									case 6: /* 
										**********************
										*** DETTAGLI SERIE ***
										**********************
										*/

										$id=$_GET["id"]; // idSerie  
										$conn=dbConn(); // Connessione al DB				

										if(isset($_POST["rate"])&&isset($_SESSION["idUser"])) { // E' stato dato un voto  
											$voto=$_POST["rate"];
											if(isset($_POST["rec"])&&$_POST["rec"]!="") // E' stata data una recensione  
												$rec="'".filter_var($_POST["rec"], FILTER_SANITIZE_STRING)."'";
											else
												$rec="null";
											$query="SELECT * FROM recensioniserie WHERE idSerie=$id AND idUtente=$_SESSION[idUser]"; // Controllo che non abbia già fatto una recensione  
											$controllo=$conn->query($query);
											if($voto!="ELIMINA"&&$voto!="VERIFICA") { // Pubblico o modifico la recensione  
												if($controllo->num_rows==0){ // Non ha già recensito:   
													$recens="INSERT INTO recensioniserie VALUES ($id,$_SESSION[idUser],'$voto',$rec,null)";
													
													if($conn->query($recens)) // Inserimento nel DB riuscito  
														echo "<script type='text/javascript'>alert('La tua recensione è stata inserita!');</script>";
													else // Inserimento nel DB NON riuscito  
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
												}
												else{
													$recens="UPDATE recensioniserie SET voto='$voto', testo=$rec, idAdmin=null 
													WHERE idSerie=$id AND idUtente=$_SESSION[idUser]";
													
													if($conn->query($recens)) // Modifica del DB riuscita  
														echo "<script type='text/javascript'>alert('La tua recensione è stata aggiornata!');</script>";
													else // Modifica del DB NON riuscita  
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
											else{
												if($voto=="ELIMINA") { // Eliminazione dal DB riuscita  
													$recens="DELETE FROM recensioniserie WHERE idSerie=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else
												{
													$recens="UPDATE recensioniserie SET idAdmin=$_SESSION[idUser] WHERE idSerie=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}
										
										if(isset($_POST["cur"])&&isset($_SESSION["idUser"])) { // E' stata iserita una curiosita  
											if(isset($_POST["check"]))
												$check=$_POST["check"];
											$cur="'".filter_var($_POST["cur"], FILTER_SANITIZE_STRING)."'";
											if($check!="ELIMINA"&&$check!="VERIFICA") { // Pubblico o modifico la recensione  
												
												$query="INSERT INTO curiositaserie (idSerie,idUtente,testo,idAdmin) VALUES ($id,$_SESSION[idUser],$cur,null)";
												if($conn->query($query)) // Inserimento nel DB riuscito  
													echo "<script type='text/javascript'>alert('La tua curiosita è stata inserita!');</script>";
												else // Inserimento nel DB NON riuscito  
													echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
											
											}
											else{
												if($check=="ELIMINA") { // Eliminazione dal DB riuscita  
													$query="DELETE FROM curiositaserie WHERE id=$_POST[idCur]";
													//echo $recens;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La curiosita è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else
												{
													$query="UPDATE curiositaserie SET idAdmin=$_SESSION[idUser] WHERE id=$_POST[idCur]";
													//echo $recens;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La curiosita è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}
										
										$query="SELECT * FROM serie WHERE id=$id"; // Preparazione Query: Dettagli Serie  
										$risultati=$conn->query($query);
										$serie = $risultati->fetch_assoc();
										$risultati->free(); // Dealloco l'oggetto

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$serie["nome"].'</h1>
													<img src="images/serie/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$serie["nome"].'">
													<div class="container-sm col-md-6 py2">
														<p class="card-text" style="text-align:center !important">'.$serie["sinossi"].'</p>
													</div>
													<div class="container text-left">
														<p class="card-text mt-4" onclick="passa_a('.$serie["id"].',11,1,null,null,null);"><strong>Stagioni: </strong><a href="#">Visualizza tutti gli episodi</a></p>
													</div>
												</div>
												</div>
												<div class="row">
											'); // Riquadro Dettagli Video
										
										$query="SELECT Pers.*,Pggi.nome nomeP 
										FROM attorivideo AV JOIN interpretazioni I ON I.idPersona=AV.idPersona JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										JOIN persone Pers ON Pers.id=AV.idPersona 
										WHERE AV.idVideo IN 
														(SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id)  
										GROUP BY AV.idPersona"; // Preparazione Query: Attori Serie   

										if ($attori=$conn->query($query)) { // Risultati della query  
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null,null,null,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														'); // Costruisco un riquadro per ogni attore (pt.1)
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>'); // Costruisco un riquadro per ogni attore (pt.2)
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else {  
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											} // Comunicazione mancanza di elementi 
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pers.* 
										FROM registivideo RV JOIN video V ON V.id=RV.idVideo JOIN persone Pers ON Pers.id=RV.idPersona 
										WHERE RV.idVideo IN ( 
															SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY RV.idPersona"; // Preparazione Query: Registi Serie  

										if ($registi=$conn->query($query)) { // Risultati della query  
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												'); // Titolo

											if ($registi->num_rows>0) { // Almeno 1 elemento
												while ($regista = $registi->fetch_assoc()) { 
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["id"].',8,null,null,null,null)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													'); // Costruisco un riquadro per ogni regista  
												}
											}
											else {   
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi
											}
											
											$registi->free(); // Dealloco l'oggetto
										}
																				
										$query="SELECT Pers.* 
										FROM produttorivideo PV JOIN video V ON V.id=PV.idVideo JOIN persone Pers ON Pers.id=PV.idPersona 
										WHERE PV.idVideo IN ( 
															SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY PV.idPersona"; // Preparazione Query: Produttori Serie  

										if ($produttori=$conn->query($query)) { // Risultati della query  
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												'); // Titolo

											if ($produttori->num_rows>0) { // Almeno 1 elemento
												while ($produttore = $produttori->fetch_assoc()) {   
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["id"].',8,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni produttore
												}
											}
											else {  
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											
											$produttori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pggi.* 
										FROM comparizioni C JOIN personaggi Pggi ON Pggi.id=C.idPersonaggio 
										WHERE C.idVideo IN ( 
															SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY C.idPersonaggio"; // Preparazione Query: Personaggi Serie  

										if ($personaggi=$conn->query($query)) { // Risultati della query  
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												'); // Titolo

											if ($personaggi->num_rows>0) { // Almeno 1 elemento
												while ($personaggio = $personaggi->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni personaggio  
												}
											}
											else { 
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											} // Comunicazione mancanza di elementi  
											$personaggi->free(); // Dealloco l'oggetto
										}
										
										
										/*
										**************************
										*****RECENSIONI SERIE*****
										**************************
										*/
										
										if($_SESSION["login"]==1) { // Utente loggato
											$query="SELECT voto, testo, username FROM recensioniserie LEFT JOIN utenti ON idAdmin=id WHERE idSerie=$id AND idUtente=$_SESSION[idUser]"; // Preparazione query: Recensione dell'utente sulla serie
											$recensione=$conn->query($query);
											if($recensione->num_rows==0) { // Non ha lasciato già recensioni
												echo('
													<div class="container text-center"> 
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-1" data-toggle="modal" data-target="#exampleModalCenter">
														  Lascia una recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Recensione</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10" />
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9" />
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8" />
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7" />
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6" />
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5" />
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4" />
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3" />
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2" />
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1" />
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255" placeholder="Scrivi la tua recensione"></textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button" onclick="recens('.$id.')" class="btn btn-primary">Salva recensione</button>
															  </div>
															</div>
														  </div>
														</div>
													</div>'); // Finestra modale per lasciare una recensione
											}
											else { // Ha già lasciato 1 recensione
												$rec=$recensione->fetch_assoc();
												echo('
													<div class="container text-center"> 
														<h4>Il tuo voto è: '.$rec['voto'].'/10<label style="color:#ffc700">★</label></h4>
														<div class="container-sm col-md-6 mt-2 mb-2 py2">
															<p class="card-text" style="text-align:center !important">'.$rec["testo"].'</p>
														</div>
														<!-- Button trigger modal -->
														<button type="button" class="btn btn-primary mt-2" data-toggle="modal" data-target="#exampleModalCenter">
														  Modifica la tua recensione
														</button>
						
														<!-- Modal -->
														
														<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-centered" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  
															  <div class="modal-body" style="margin:0 auto;">
																 <div class="rate">
																	<input  type="radio" id="star10" name="rate" value="10"');
																	if($rec["voto"]==10)
																		echo 'checked';
																	echo '/>
																	<label for="star10" title="10/10">10 stars</label>
																	<input type="radio" id="star9" name="rate" value="9"';
																	if($rec["voto"]==9)
																		echo 'checked';
																	echo '/>
																	<label for="star9" title="9/10">9 stars</label>
																	<input type="radio" id="star8" name="rate" value="8"';
																	if($rec["voto"]==8)
																		echo 'checked';
																	echo '/>
																	<label for="star8" title="8/10">8 stars</label>
																	<input type="radio" id="star7" name="rate" value="7"';
																	if($rec["voto"]==7)
																		echo 'checked';
																	echo '/>
																	<label for="star7" title="7/10">7 stars</label>
																	<input type="radio" id="star6" name="rate" value="6"';
																	if($rec["voto"]==6)
																		echo 'checked';
																	echo '/>
																	<label for="star6" title="6/10">6 star</label>
																	<input type="radio" id="star5" name="rate" value="5"';
																	if($rec["voto"]==5)
																		echo 'checked';
																	echo '/>
																	<label for="star5" title="5/10">5 stars</label>
																	<input type="radio" id="star4" name="rate" value="4"';
																	if($rec["voto"]==4)
																		echo 'checked';
																	echo '/>
																	<label for="star4" title="4/10">4 stars</label>
																	<input type="radio" id="star3" name="rate" value="3"';
																	if($rec["voto"]==3)
																		echo 'checked';
																	echo '/>
																	<label for="star3" title="3/10">3 stars</label>
																	<input type="radio" id="star2" name="rate" value="2"';
																	if($rec["voto"]==2)
																		echo 'checked';
																	echo '/>
																	<label for="star2" title="2/10">2 stars</label>
																	<input type="radio" id="star1" name="rate" value="1"';
																	if($rec["voto"]==1)
																		echo 'checked';
																	echo ('/>
																	<label for="star1" title="1/10">1 star</label>
																  </div>
																	<div class="form-group">
																	  <textarea id="textarea" name="rec" class="form-control" rows="5" maxlength="255"  placeholder="Scrivi la tua recensione">'.$rec["testo"].'</textarea>
																	</div>
															  </div>
															  
															  <div class="modal-footer">
																<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
																<button type="button"  class="btn btn-primary" data-toggle="modal" data-target="#conferma">Elimina recensione</button>
																
																<button type="button" onclick="recens('.$id.')" class="btn btn-primary">Modifica recensione</button>
															  </div>
															</div>
														  </div>
														</div>
														
													</div>
													<!-- Modal -->
													<div class="modal fade" id="conferma" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalCenterTitle">Conferma</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  <div class="modal-body">
															Sei sicuro di voler eliminare la recensione? L\'operazione sarà irreversibile
														  </div>
														  <div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
															<button type="button" onclick="elimina('.$id.','.$_SESSION["idUser"].')" class="btn btn-primary">Sì</button>
														  </div>
														</div>
													  </div>
													</div>'); // Finestra modale per la modifica della recensione
											}
										}
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Recensioni degli utenti</h2>
													</div>
												'); // Titolo

										$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
										FROM recensioniserie R 
										INNER JOIN utenti U ON U.id=R.idUtente
										LEFT JOIN utenti A ON A.id=R.idAdmin
										WHERE idSerie=$id
										ORDER BY R.idAdmin DESC,R.idUtente
										LIMIT 4;"; // Preparazione query: Recensioni Serie (MAX 4)
										$recensioni=$conn->query($query);
										if($recensioni->num_rows==0) { // Nessuna recensione
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
														</div>
													</div>
												</div>'; // Comunico la mancanza di elementi
										}		
										else { // Almeno 1 elemento
												while($riga = $recensioni->fetch_assoc()) {
													echo 	
														'<div class="col-md-3 py2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>';
																	if($riga["testo"]!=null)
																		echo '
																	<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																			echo'
																				<div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" data-toggle="modal" data-target="#conferma">Elimina</button>
																					<button type="button" class="btn btn-primary" onclick="verifica('.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
															</div>
														</div>';
												} // Riquadro recensione

												$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
												FROM recensioniserie R 
												INNER JOIN utenti U ON U.id=R.idUtente
												LEFT JOIN utenti A ON A.id=R.idAdmin
												WHERE R.testo IS NOT NULL AND idSerie=$id
												ORDER BY R.idAdmin DESC,R.idUtente;";
												$recensioni=$conn->query($query); // Preparazione query: Recensioni Serie (Tutte)
												
												if($recensioni->num_rows>4) { // Almeno 4 recensioni
													echo '
													<div class="container text-center">
														<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#exampleModalScrollable">
														  Visualizza tutte le recensioni
														</button>
														<!-- Modal -->
														<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-scrollable" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalScrollableTitle">Recensioni degli utenti</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  <div class="modal-body">';
															  while($riga = $recensioni->fetch_assoc()){
																  echo '
																	<div class="card h-100 mb-4 shadow-sm">
																		<div class="card-body">
																		<h6 class="mt-1 ml-2">'.$riga["username"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>
																			<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																			if($riga["admin"]!=null)
																				echo '
																					<div class="d-flex justify-content-end bd-highlight mb-3">
																						<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																					</div>';
																			echo '</div>';
																		
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null)
																			echo'
																				<div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" onclick="elimina('.$id.','.$riga["id"].')" data-dismiss="modal">Elimina</button>
																					<button type="button" class="btn btn-primary" onclick="verifica('.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
																		</div>';
															  }
															  echo '
															</div>
														  </div>
														</div>
													</div>
												</div>'; // Finestra modale per visualizzare tutte le recensioni
												}
										}
										
										/*
										**************************
										*****CURIOSITA' SERIE*****
										**************************
										*/
										
										if($_SESSION["login"]==1) { // Utente loggato
											echo('
												<div class="container text-center"> 
													<!-- Button trigger modal -->
													<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#curiositaMod">
													  Lascia una curiosita
													</button>
					
													<!-- Modal -->
													
													<div class="modal fade" id="curiositaMod" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
													  <div class="modal-dialog modal-dialog-centered" role="document">
														<div class="modal-content">
														  <div class="modal-header">
															<h5 class="modal-title" id="exampleModalLongTitle">Curiosità</h5>
															<button type="button" class="close" data-dismiss="modal" aria-label="Close">
															  <span aria-hidden="true">&times;</span>
															</button>
														  </div>
														  
														  <div class="modal-body">
																<div class="form-group">
																<textarea id="textcur" name="textcur" class="form-control" rows="5" maxlength="255" placeholder="Scrivi la tua curiosità"></textarea>
																</div>
														  </div>
														  
														  <div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Annulla</button>
															<button type="button" onclick="curios('.$id.')" class="btn btn-primary">Salva curiosità</button>
														  </div>
														</div>
													  </div>
													</div>
												</div>'); // Finestra modale per l'inserimento di una curiosità
										} 
											
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Curiosità degli utenti</h2>
													</div>
												'); // Titolo

										$query="SELECT C.id idCur, C.testo,U.username, U.id, A.username admin
										FROM curiositaserie C 
										INNER JOIN utenti U ON U.id=C.idUtente
										LEFT JOIN utenti A ON A.id=C.idAdmin
										WHERE idSerie=$id
										ORDER BY C.idAdmin DESC,C.idUtente
										LIMIT 4;"; // Preparazione query: Curiosità Serie (MAX 4)
										$curiosita=$conn->query($query);
										if($curiosita->num_rows==0) { // Non ci sono curiosità
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna curiosita</p>
														</div>
													</div>
												</div>';
										}		
										else { // Almeno 1 elemento
												while($riga = $curiosita->fetch_assoc()) {
													echo 	
														'<div class="col-md-3 py2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"].'</h6>';
																	
																	echo '
																		<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																		if(isset($_SESSION["idUser"])&&$_SESSION["idUser"]==$riga["id"]){
																			echo' <div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																		}
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1)
																			if($_SESSION["idUser"]!=$riga["id"]){
																				echo' <div class="modal-footer">
																					<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																			}
																		if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null){
																			echo'	<button type="button" class="btn btn-primary" onclick="verificaC('.$riga["idCur"].')" data-dismiss="modal">Verifica</button>';
																		}
																		if(isset($_SESSION["idUser"]) && $_SESSION["idUser"]==$riga["id"]||(isset($_SESSION["admin"])&&$_SESSION["admin"]==1))
																			echo '</div>';
																	echo '
															</div>
														</div>'; // Riquadro curiosità
												}

												$query="SELECT C.id idCur, C.testo,U.username, U.id, A.username admin
												FROM curiositaserie C 
												INNER JOIN utenti U ON U.id=C.idUtente
												LEFT JOIN utenti A ON A.id=C.idAdmin
												WHERE C.testo IS NOT NULL AND idSerie=$id
												ORDER BY C.idAdmin DESC,C.idUtente;"; // Preparazione query: Curiosità Serie (Tutte)
												$curiosita=$conn->query($query);
												if($curiosita->num_rows>4) { // Almeno 4 elementi
													echo '
													<div class="container text-center">
														<button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#curiositaScroll">
														  Visualizza tutte le recensioni
														</button>
														<!-- Modal -->
														<div class="modal fade" id="curiositaScroll" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
														  <div class="modal-dialog modal-dialog-scrollable" role="document">
															<div class="modal-content">
															  <div class="modal-header">
																<h5 class="modal-title" id="exampleModalScrollableTitle">Curiosita degli utenti</h5>
																<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																  <span aria-hidden="true">&times;</span>
																</button>
															  </div>
															  <div class="modal-body">';
															  while($riga = $curiosita->fetch_assoc()){
																  echo  '
																		<div class="card h-100 mb-4 shadow-sm">
																			<div class="card-body">
																				<h6 class="mt-1 ml-2">'.$riga["username"].'</h6>';
																				
																				echo '
																					<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																				if($riga["admin"]!=null)
																				echo '
																					<div class="d-flex justify-content-end bd-highlight mb-3">
																						<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																					</div>';
																			echo '</div>';
																					if($_SESSION["idUser"]==$riga["id"]){
																						echo' <div class="modal-footer">
																								<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																					}
																					if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1)
																						if($_SESSION["idUser"]!=$riga["id"]){
																							echo' <div class="modal-footer">
																								<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].')">Elimina</button>';
																						}
																					if(isset($_SESSION["admin"])&&$_SESSION["admin"]==1&&$riga["admin"]==null){
																						echo'	<button type="button" class="btn btn-primary" onclick="verificaC('.$riga["idCur"].')" data-dismiss="modal">Verifica</button>';
																					}
																					if($_SESSION["idUser"]==$riga["id"]||(isset($_SESSION["admin"])&&$_SESSION["admin"]==1))
																						echo '</div>';
																				echo '
																		</div>'; // Finestra modale per visualzizare tutte le curiosità
															  }
															  echo '
															</div>
														  </div>
														</div>
													</div>
												</div>';
												
												}
										}
										
										 
										$conn->close();	// Chiudo la connessione al DB
										
										echo ('
															</div>
															</div>
														</div>
													</div>
												</div>
							</div>
						</div>
					</div>
					<div class="container"> 
						<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
					</div>
					');

										break;

									case 7: /* 
										**********************
										*** DETTAGLI SAGHE ***
										**********************
										*/

										$id=$_GET["id"];  // idPersona 
										$conn=dbConn(); // Connessione al DB
										
										$query="SELECT S.id,S.nome,COUNT(*) nFilm FROM saghe S 
										JOIN video V ON V.idSaga=S.id
                                        WHERE S.id=$id
										GROUP BY S.id";  // Preparazione Query: Dettagli Serie 
										$risultati=$conn->query($query);
										$saga = $risultati->fetch_assoc();
										$risultati->free(); // Dealloco l'oggetto

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$saga["nome"].'</h1>
													<img src="images/saghe/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/saghe/default.jpg\';" alt="Locandina di '.$saga["nome"].'">
													<div class="container-sm col-md-6 py2">
														<p class="card-text mt-4" style="text-align:center !important">Questa saga è composta da '.$saga["nFilm"].' film</p>
													</div>
													
												</div>
												</div>
												<div class="row">
											'); // Riquadro Dettagli Saghe
										
										$query="SELECT V.*
										FROM video V
										JOIN saghe S ON V.idSaga=S.id
										WHERE S.id=$id
										ORDER BY v.numero"; // Preparazione query: Tutti i video della saga
										
										if ($video=$conn->query($query)) {  // Risultati della query 
											if ($video->num_rows>0) { // Almeno 1 elemento
												echo ('	
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Film della saga</h2>
													</div>
													'); // Titolo

												while ($elemento = $video->fetch_assoc()) {  
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																	</div>
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni video 
												}
											}
											
											$video->free(); // Dealloco l'oggetto
										}
										
										$query="SELECT Pers.*,Pggi.nome nomeP 
										FROM attorivideo AV JOIN interpretazioni I ON I.idPersona=AV.idPersona JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										JOIN persone Pers ON Pers.id=AV.idPersona 
										WHERE AV.idVideo IN 
														(SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id)  
										GROUP BY AV.idPersona";  // Preparazione Query: Attori Saga 

										if ($attori=$conn->query($query)) {  // Risultati della query 
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												'); // Titolo

											if ($attori->num_rows>0) { // Almeno 1 elemento
												while ($attore = $attori->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null,null,null,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														');  // Costruisco un riquadro per ogni attore (pt.1)
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>'); // Costruisco un riquadro per ogni attore (pt.2)
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else {  
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pers.* 
										FROM registivideo RV JOIN video V ON V.id=RV.idVideo JOIN persone Pers ON Pers.id=RV.idPersona 
										WHERE RV.idVideo IN ( 
															SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id) 
										GROUP BY RV.idPersona";  // Preparazione Query: Registi Saga 

										if ($registi=$conn->query($query)) {  // Risultati della query 
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												'); // Titolo

											if ($registi->num_rows>0) { // Almeno 1 elemento
												while ($regista = $registi->fetch_assoc()) { 
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["id"].',8,null,null,null,null)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													');  // Costruisco un riquadro per ogni regista 
												}
											}
											else {  
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											} // Comunicazione mancanza di elementi 
											
											$registi->free(); // Dealloco l'oggetto
										}
																				
										$query="SELECT Pers.* 
										FROM produttorivideo PV JOIN video V ON V.id=PV.idVideo JOIN persone Pers ON Pers.id=PV.idPersona 
										WHERE PV.idVideo IN ( 
															SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id) 
										GROUP BY PV.idPersona";  // Preparazione Query: Produttori Saga 

										if ($produttori=$conn->query($query)) {  // Risultati della query 
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												'); // Titolo

											if ($produttori->num_rows>0) { // Almeno 1 elemento
												while ($produttore = $produttori->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["id"].',8,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni produttore 
												}
											}
											else {  
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											
											$produttori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pggi.* 
										FROM comparizioni C JOIN personaggi Pggi ON Pggi.id=C.idPersonaggio 
										WHERE C.idVideo IN ( 
															SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id) 
										GROUP BY C.idPersonaggio";  // Preparazione Query: Personaggi Serie 

										if ($personaggi=$conn->query($query)) {  // Risultati della query 
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												'); // Titolo

											if ($personaggi->num_rows>0) { // Almeno 1 elemento
												while ($personaggio = $personaggi->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni personaggio 
												}
											}
											else {  
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											$personaggi->free(); // Dealloco l'oggetto
										}
										
										break;

									case 8: /* 
										************************
										*** DETTAGLI PERSONE ***
										************************
										*/

										$id=$_GET["id"]; // idPersona 
										$conn=dbConn(); // Connessione al DB
										$query="SELECT nome,cognome FROM persone WHERE id=$id;"; // Preparazione Query: Dettagli Persona 
										$risultati=$conn->query($query);
										$persona = $risultati->fetch_assoc();
										$risultati->free(); // Dealloco l'oggetto

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$persona["nome"].' '.$persona["cognome"].'</h1>
													<img src="images/persone/'.$id.'.jpg" style="max-width: 50%; height: auto;" class="mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$persona["nome"].' '.$persona["cognome"].'">
												</div>
											'); // Riquadro dettaglio persona

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN attorivideo AV ON AV.idVideo=V.id JOIN persone P ON AV.idPersona=P.id 
										WHERE P.id=$id AND V.selettore!=2"; // Preparazione Query: Video da Attore 

										if ($video=$conn->query($query)) { // Risultati della query 
											$n=$video->num_rows;
											if ($n>0) { // Almeno 1 elemento
												echo ('	
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Attore in</h2>
													</div>
													'); // Titolo
											
												while ($elemento = $video->fetch_assoc())  { 
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																		</div>
																	</div>
																</div>
															</div>		
													'); // Costruisco un riquadro per ogni video 
												}
											}
										}
										
										$video->free(); // Dealloco l'oggetto	
										
										$query="SELECT S.* 
										FROM serie S JOIN video V ON V.idSerie=S.id JOIN attorivideo AV ON AV.idVideo=V.id 
										WHERE AV.idPersona=$id 
										GROUP BY S.id"; // Preparazione Query: Serie da Attore 

										if ($serie=$conn->query($query)) { // Risultati della query 
											if ($serie->num_rows>0) { // Almeno 1 elemento 
												if ($n==0) { // Intestazione non ancora scritta
													echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Attore in</h2>
														</div>
														');
												} // Titolo

												while ($elemento = $serie->fetch_assoc()) {  
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/serie/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																		</div>
																	</div>
																</div>
															</div>		
													');
												} // Costruisco un riquadro per ogni video
											}
											$serie->free(); // Dealloco l'oggetto
										}

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN registivideo RV ON RV.idVideo=V.id JOIN persone P ON RV.idPersona=P.id 
										WHERE P.id=$id AND V.selettore!=2"; // Preparazione Query: Video da Regista 
										
										if ($video=$conn->query($query)) { // Risultati della query 
											$n=$video->num_rows;
											if($n>0) { // Almeno 1 elemento
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Regista in</h2>
														</div>
													'); // Titolo
												
												while ($elemento = $video->fetch_assoc()) { 
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																	</div>
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni video 
												}
											}																				
											$video->free(); // Dealloco l'oggetto
										}
										
										$query="SELECT S.* 
										FROM serie S JOIN video V ON V.idSerie=S.id JOIN registivideo RV ON RV.idVideo=V.id 
										WHERE RV.idPersona=$id 
										GROUP BY S.id"; // Preparazione Query: Serie da Regista 

										if ($serie=$conn->query($query)) { // Risultati della query 
											if ($serie->num_rows>0) { // Almeno 1 elemento
												if ($n==0) { // Intestazione non ancora scritta
													echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Regista in</h2>
														</div>
														'); // Titolo
												}

												while ($elemento = $serie->fetch_assoc()) { 
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/serie/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																		</div>
																	</div>
																</div>
															</div>		
													'); // Costruisco un riquadro per ogni video 
												}
											}
											$serie->free(); // Dealloco l'oggetto
										}

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN produttorivideo PV ON PV.idVideo=V.id JOIN persone P ON PV.idPersona=P.id 
										WHERE P.id=$id AND V.selettore!=2"; // Preparazione Query: Video da Produttore 

										if ($video=$conn->query($query)) { // Risultati della query 
											$n=$video->num_rows;
											if($n>0) { // Almeno 1 elemento
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttore in</h2>
														</div>
													'); // Titolo
												
												while ($elemento = $video->fetch_assoc()) {  
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																	</div>
																</div>
															</div>
														</div>		
													');
												} // Costruisco un riquadro per ogni video
											}																				
											$video->free(); // Dealloco l'oggetto
										}
										
										$query="SELECT S.* 
										FROM serie S JOIN video V ON V.idSerie=S.id JOIN produttorivideo PV ON PV.idVideo=V.id 
										WHERE PV.idPersona=$id 
										GROUP BY S.id"; // Preparazione Query: Serie da Produttore 

										if ($serie=$conn->query($query)) { // Risultati della query 
											if ($serie->num_rows>0) { // Almeno 1 elemento
												if ($n==0) { // Intestazione non ancora scritta
													echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttore in</h2>
														</div>
														'); // Titolo
												}
												while ($elemento = $serie->fetch_assoc()) {  
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/serie/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																		</div>
																	</div>
																</div>
															</div>		
													');
												} // Costruisco un riquadro per ogni video
											}
											$serie->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pggi.* 
										FROM interpretazioni I JOIN persone Pers ON Pers.id=I.idPersona JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										WHERE Pers.id=$id"; // Preparazione Query: Personaggi interpretati 

										if ($personaggi=$conn->query($query)) { // Risultati della query 
											if($personaggi->num_rows>0) { // Almeno 1 elemento
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Interpreta</h2>
														</div>
													');
												
												while ($personaggio = $personaggi->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$personaggio["id"].',9,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
														'); // Costruisco un riquadro per ogni personaggio 
												}
											}																				
											$personaggi->free(); // Dealloco l'oggetto
										}
			echo ('
						</div>
					</div>
				</div>
				<div class="container"> 
					<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
				</div>
				');
										break;
									
									case 9: /* 
											****************************
											*** DETTAGLI PERSONAGGIO ***
											****************************
											*/
										$id=$_GET["id"]; // idPersonaggio 
										$conn=dbConn(); // Connessione al DB
										$query="SELECT nome FROM personaggi WHERE id=$id"; // Preparazione Query: Dettagli personaggio 
										$risultati=$conn->query($query);
										$personaggio = $risultati->fetch_assoc();
										$risultati->free(); // Dealloco l'oggetto

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$personaggio["nome"].'</h1>
													<img src="images/personaggi/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto del personaggio '.$personaggio["nome"].'">
												</div>
											'); // Riquadro Dettagli Personaggio
										
										$query="SELECT Pers.* 
										FROM interpretazioni I JOIN persone Pers ON Pers.id=I.idPersona JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio
										WHERE Pggi.id=$id"; // Preparazione Query: Attori che hanno interpretato il personaggio 

										if ($attori=$conn->query($query)) { // Risultati della query 
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori che lo hanno interpretato</h2>
												</div>
												'); // Titolo

											if ($attori->num_rows>0) { // Almeno 1 elemento
												while ($attore = $attori->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null,null,null,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni attore
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT V.id, V.nome, V.durata, V.sinossi
										FROM comparizioni C JOIN video V ON V.id=C.idVideo
										WHERE C.idPersonaggio=$id"; // Preparazione Query: Video in cui compare il personaggio 

										if ($video=$conn->query($query)) { // Query effettuata con successo 
											echo ('	
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Video in cui e\' apparso</h2>
												</div>
												'); // Titolo

											if ($video->num_rows>0) { // Almeno un risultato 
												while ($elemento = $video->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$elemento["durata"].' minuti</small>
																	</div>
																</div>
															</div>
														</div>
													'); // Costruisco un riquadro per ogni video 
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											$video->free(); // Dealloco l'oggetto
											$conn->close(); // Chiudo la connessione al DB
										}

										break;
									
									case 10: /* 
										***************
										*** RICERCA ***
										***************
										*/
										$ricerca=$_GET["ricerca"];
										$conn=dbConn(); // Connessione al DB
										echo ('
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
										'); // bottone indietro
										
										$query="SELECT id,nome,Sinossi,durata FROM video WHERE selettore=1 AND nome LIKE '%$ricerca%'"; // Preparazione query: Video coerenti con la ricerca
										
										if ($risultati=$conn->query($query)) { // Risultati della query 
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Film</h2>
														</div>
												'); // Titolo

											if ($risultati->num_rows>0) { // Almeno 1 elemento
												while ($elemento = $risultati->fetch_assoc()) { 
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null,null,null,null);" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																		<p class="card-text">'.$elemento["nome"].'</p>
																		<p class="card-text-description">'.$elemento["Sinossi"].'</p>			
																		<div class="d-flex justify-content-between align-items-center">
																		</div>
																		<div class="d-flex flex-row-reverse align-items-center">
																			<small class="text-muted">Durata: '.$elemento["durata"].' minuti</small>
																		</div>
																	</div>
																</div>
															</div>		
													'); // Costruisco un riquadro per ogni film 
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi
											}
											
											$risultati->free(); // Dealloco l'oggetto
										}
										
										
										$query="SELECT S.id,S.nome,S.sinossi,COUNT(*)
										FROM serie S 
										JOIN video V on V.idSerie=S.id 
										WHERE S.nome LIKE '%$ricerca%' 
										GROUP BY S.id"; // Preparazione query: Serie coerenti con la ricerca

										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Serie</h2>
											</div>
											'); // Titolo

										if ($serie=$conn->query($query)) { // Query effettuata con successo 
											if ($serie->num_rows>0) { // Almeno un risultato 
												while ($elemento = $serie->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
														'); // Costruisco un riquadro per ogni serie (pt.1)

														$query="SELECT COUNT(S.id) nEpisodi
														FROM serie S JOIN video V ON S.id=V.idSerie 
														WHERE V.idSerie=1"; // Preparazione Query: Numero episodi della serie 

														if ($risultato=$conn->query($query)) { // Query effettuata con successo 
															if ($risultato->num_rows==1) { // 1 risultato 
																$nepisodi = $risultato->fetch_assoc();
													echo ('			
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi: '.$nepisodi["nEpisodi"].'</small>
																	</div>
																</div>
															</div>
														</div>
														'); // Costruisco un riquadro per ogni serie (pt.2)
															}
														}
														$risultato->free(); // Dealloco l'oggetto
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi 
											}
											$serie->free();	// Dealloco l'oggetto
										}
										
										$query="SELECT S.id, S.nome, COUNT(*) nFilm
										FROM saghe S
										JOIN video V ON S.id=V.idSaga
										WHERE S.nome LIKE '%$ricerca%'
										GROUP BY S.id"; // Preparazione Query: Saghe coerenti con la ricerca

										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Saghe</h2>
											</div>
											'); // Titolo 

										if ($saghe=$conn->query($query)) { // Query effettuata con successo 
											if ($saghe->num_rows>0) { // Almeno un risultato 
												while ($saga = $saghe->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',6,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/saghe/'.$saga["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/saghe/default.jpg\';" alt="Locandina di '.$saga["nome"].'">
																	<p class="card-text">'.$saga["nome"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Film: '.$saga["nFilm"].'</small>
																	</div>
																</div>
															</div>
														</div>
													'); // Costruisco un riquadro per ogni saga TV 
												}
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessuna saga è ancora stata recensita</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di saghe 
											}
											$saghe->free(); // Dealloco l'oggetto
										}
										
										$query="SELECT id,nome,cognome FROM persone WHERE nome LIKE '%$ricerca%' OR cognome LIKE '%$ricerca%'"; // Preparazione query: Persone coerenti con la ricerca
										
										if ($attori=$conn->query($query)) { // Risultati della query 
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Persone</h2>
												</div>
												'); // Titolo

											if ($attori->num_rows>0) { // Almeno 1 elemento
												while ($attore = $attori->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null,null,null,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni attore 
												}
											}
											else {
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');  // Comunicazione mancanza di elementi 
											}	
											$attori->free(); // Dealloco l'oggetto
										}
										
										$query="SELECT id,nome FROM personaggi WHERE nome LIKE '%$ricerca%'"; // Preparazione query: Personaggi coerenti con la ricerca
										
										if ($personaggi=$conn->query($query)) { // Risultati della query 
											if($personaggi->num_rows>0) { // Almeno 1 elemento
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Personaggi</h2>
														</div>
													'); // Titolo
												
												while ($personaggio = $personaggi->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$personaggio["id"].',9,null,null,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
														'); // Costruisco un riquadro per ogni personaggio 
												}
											}																				
											$personaggi->free(); // Dealloco l'oggetto
										}
										echo ('
											<div class="container">
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											</div>
										'); // Bottone indietro
										
										$conn->close(); // Chiudo la connessione al DB
										
										break;
									
									case 11: /* 
										*************************
										*** DETTAGLI STAGIONI ***
										*************************
										*/
										
										$pagina=$_GET["pagina"]; // Stagione da mostare 
										$id=$_GET["id"]; // idSerie 
										$conn=dbConn(); // Connessione al DB
										$query="SELECT *
										FROM video V
										JOIN serie S ON V.idSerie=S.id
										WHERE S.id=$id"; // Preparazione Query: Nome della serie 	
										$nStag=$conn->query("SELECT COUNT( DISTINCT stagione) nStag FROM video WHERE idSerie=$id")->fetch_assoc()["nStag"]; // Numero di stagioni 

										echo ('
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
										'); // Bottone indietro
										
										if ($stagioni=$conn->query($query)) { // Risultati della query 
											if ($stagioni->num_rows>0) { // Almeno 1 elemento
												$elemento = $stagioni->fetch_assoc();   
												echo ('
														<div class="container text-center">
															<h1 class="mt-4 mb-4" >'.$elemento['nome'].'</h1>
														</div>
														<div class="container">'.
															$elemento['sinossi'].'
														</div>
													');												
											} // Costruisco un riquadro per ogni video
											$stagioni->free(); // Dealloco l'oggetto
										}

										echo '<div class="container"> <select id="sel" onchange="passa_a('.$id.',11,sel.value,null,null,null)">'; // Scelta ordinamento
										for($i=1;$i<=$nStag;$i++) {
											echo '<option value="'.$i.'"';
											if($i==$pagina)
												echo ' selected';
											echo '>Stagione '.$i.'</option>';
										}
										echo '</select></div>';
										
										$query="SELECT V.*
										FROM video V
										JOIN serie S ON V.idSerie=S.id
										WHERE S.id=$id AND V.stagione=$pagina
										ORDER BY V.numero"; // Preparazione query: Video della serie appartenenti alla stagione specificata

										if ($video=$conn->query($query)) { // Risultati della query 
											if ($video->num_rows>0) { // Almeno 1 elemento
												while ($elemento = $video->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null,null,null,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$elemento["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/video/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>			
																	<div class="d-flex justify-content-between align-items-center">
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$elemento["durata"].' minuti</small>
																	</div>
																</div>
															</div>
														</div>		
													'); // Costruisco un riquadro per ogni video 
												}
											}
											$video->free(); // Dealloco l'oggetto
										}
																
										break;
									
									case 12:
										/*
										*************************
										*** RECENSIONI UTENTE ***
										*************************
										*/
										$conn=dbConn(); // Connessione al DB
										if(isset($_POST['idCur']))
											$id=$_POST['idCur'];
										if(isset($_POST["cur"])&&isset($_SESSION["idUser"])) { // E' stato dato un voto 
											$voto=$_POST["check"];
												if($voto=="VIDEO") { // Eliminazione del DB riuscita 
													$query="DELETE FROM recensionivideo WHERE idVideo=$id AND idUtente=$_SESSION[idUser]";
													//echo $query;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else {
													$query="DELETE FROM recensioniserie WHERE idSerie=$id AND idUtente=$_SESSION[idUser]";
													//echo $query;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
										}
					
										if(isset($_SESSION)) { // Sessione settata
											echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h1 class="mt-4 mb-4" >Tutte le recensioni di '.$_SESSION['user'].'</h1>
											</div>
											'); // Titolo

											$id=$_SESSION["idUser"];
											$query="SELECT R.voto, R.testo, S.nome as serie, V.nome, U.id, R.idVideo ,U.username, A.username admin
											FROM recensionivideo R 
											INNER JOIN utenti U ON U.id=R.idUtente
											JOIN video V ON V.id=R.idVideo
											LEFT JOIN utenti A ON A.id=R.idAdmin
											LEFT JOIN serie S ON S.id=V.idSerie
											WHERE U.id=$id
											ORDER BY R.idAdmin DESC,R.idVideo;"; // Preparazione query: Tutte le recensioni dei video dell'Utente
											$recensioni=$conn->query($query);
											$vuoto=0;
											if($recensioni->num_rows==0) { // Nessun elemento
												$vuoto++;
											}		
											else { // Almeno 1 elemento
												while($riga = $recensioni->fetch_assoc()) {
													echo 	
														'<div class="col-md-3 py2 mb-2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["nome"];
																	if($riga['serie']!=null)
																		echo " ($riga[serie]) ";
																	echo ' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>';
																	if($riga["testo"]!=null)
																		echo '<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																echo'
																	<div class="modal-footer">
																		<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga['idVideo'].',\'VIDEO\')">Elimina</button>
																	</div>';
																	echo '
															</div>
														</div>'; // Riquadro recensione
												}
											}
											
											$query="SELECT R.voto, R.testo, S.nome, U.id, R.idSerie ,U.username, A.username admin
											FROM recensioniserie R 
											INNER JOIN utenti U ON U.id=R.idUtente
											JOIN serie S ON S.id=R.idSerie
											LEFT JOIN utenti A ON A.id=R.idAdmin
											WHERE U.id=$id
											ORDER BY R.idAdmin DESC,R.idSerie;"; // Preparazione query: Tutte le recensioni delle serie dell'utente
											$recensioni=$conn->query($query);

											if($recensioni->num_rows==0) { // Nessun elemento
												$vuoto++;
											}		
											else { // Almeno 1 elemento
												while($riga = $recensioni->fetch_assoc()) {
													echo 	
														'<div class="col-md-3 py2 mb-2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["nome"].' · '.$riga["voto"].'/10<label style="color:#ffc700">★</label></h6>';
																	if($riga["testo"]!=null)
																		echo '<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																echo'
																	<div class="modal-footer">
																		<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga['idSerie'].',\'SERIE\')">Elimina</button>
																	</div>';
																	echo '
															</div>
														</div>';
												} // Riquadro Recensione
											}
											if($vuoto==2) { // Non ci sono recensioni (video e serie)
												echo 	
													'<div class="col-md-3 py2">
														<div class="card h-100 mb-4 shadow-sm">
															<div class="card-body">
																<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
															</div>
														</div>
													</div>';
											} // Avviso mancanza di recensioni
										}
										
										$conn->close();
										break;

									case 13:
										/*
										************************
										*** CURIOSITÀ UTENTE ***
										************************
										*/

										$conn=dbConn();
										if(isset($_POST['idCur']))
											$id=$_POST['idCur'];
										if(isset($_POST["cur"])&&isset($_SESSION["idUser"])) { // E' stato dato un voto 
											$voto=$_POST["check"];
												if($voto=="VIDEO") { // Eliminazione del DB riuscita 
													$query="DELETE FROM curiositavideo WHERE id=$id";
													//echo $query;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La curiosità è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
												else{
													$query="DELETE FROM curiositaserie WHERE id=$id";
													//echo $query;
													if($conn->query($query))
														echo "<script type='text/javascript'>alert('La curiosità è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
										}
					
										if(isset($_SESSION)) { // Sessione settata
											echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h1 class="mt-4 mb-4" >Tutte le curiosità di '.$_SESSION['user'].'</h1>
											</div>
											'); // Titolo

											$id=$_SESSION["idUser"];
											$query="SELECT C.id as idCur, C.testo, S.nome serie, V.nome, U.id, C.idVideo ,U.username, A.username admin
											FROM curiositavideo C 
											INNER JOIN utenti U ON U.id=C.idUtente
											JOIN video V ON V.id=C.idVideo
											LEFT JOIN utenti A ON A.id=C.idAdmin
											LEFT JOIN serie S ON S.id=V.idSerie
											WHERE U.id=$id
											ORDER BY C.idVideo, C.idAdmin DESC;"; // Preparazione query: Tutte le curiosità dei video dell'utente
											$recensioni=$conn->query($query);
											$vuoto=0;

											if($recensioni->num_rows==0) { // Mancanza di recensioni
												$vuoto++;
											}		
											else { // Almeno 1 elemento
												while($riga = $recensioni->fetch_assoc()){
													echo 	
														'<div class="col-md-3 py2 mb-2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"];
																	if($riga['serie']!=null)
																		echo " ($riga[serie]) ";
																	echo '</h6>';
																	echo '
																		<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																		echo' <div class="modal-footer">
																			<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].',\'VIDEO\')">Elimina</button>';
																		echo '</div>';
																	echo '
															</div>
														</div>'; // Riquadro recensione
												}
											}
											
											$query="SELECT C.id as idCur, C.testo, S.nome, U.id, C.idSerie ,U.username, A.username admin
											FROM curiositaserie C 
											INNER JOIN utenti U ON U.id=C.idUtente
											JOIN serie S ON S.id=C.idSerie
											LEFT JOIN utenti A ON A.id=C.idAdmin
											WHERE U.id=$id
											ORDER BY C.idSerie, C.idAdmin DESC;"; // Preparazione query: tutte le curiosità delle serie dell'utente
											$recensioni=$conn->query($query);
											if($recensioni->num_rows==0) { // Mancanza di recensioni
												$vuoto++;
											}		
											else { // Almeno 1 elemento
												while($riga = $recensioni->fetch_assoc()) {
													echo 	
														'<div class="col-md-3 py2 mb-2">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<h6 class="mt-1 ml-2">'.$riga["username"];
																	echo '</h6>';
																	echo '
																		<p class="card-text" style="text-align:center !important">'.$riga["testo"].'</p>';
																	if($riga["admin"]!=null)
																	echo '
																		<div class="d-flex justify-content-end bd-highlight mb-3">
																			<small class="text-muted">Verificato da '.$riga["admin"].'</small>
																		</div>';
																echo '</div>';
																		echo' <div class="modal-footer">
																			<button type="button"  class="btn btn-secondary" onclick="eliminaC('.$riga["idCur"].',\'SERIE\')">Elimina</button>';
																		echo '</div>';
																	echo '
															</div>
														</div>'; // Riquadro recensione
												}
											}

											if($vuoto==2) { // Non ci sono relazioni (video e serie)
												echo 	
													'<div class="col-md-3 py2">
														<div class="card h-100 mb-4 shadow-sm">
															<div class="card-body">
																<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
															</div>
														</div>
													</div>'; // COmunicazione di mancanza di elementi
											}
										}
										$conn->close();
										break;

									case 14:
										/*
										**********************
										*** PROFILO UTENTE ***
										**********************
										*/

										echo "</form>";
										
										$conn=dbConn();
										
										if(isset($_POST["newpw"])) { // Nuova password
											$id=$_SESSION["idUser"];
											$query="SELECT password FROM utenti WHERE id=$id AND password='".md5($_POST["oldpw"])."'"; // Preparazione query: Vecchia password utente
											if($result=$conn->query($query))
												if($result->num_rows==1) { // 1 password
													$query="UPDATE utenti SET password='".md5($_POST["newpw"])."'
													WHERE id=$id"; // Preparazione query: Inserimento nel DB della nuova password
													if($conn->query($query)) // Inserimento nel DB riuscito 
														echo "<script type='text/javascript'>alert('La password è stata modificata con successo!');</script>";
													else // Inserimento nel DB NON riuscito 
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
										}

										echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h1 class="mt-4 mb-4" >Profilo utente</h1>
											'); // Titolo

										if(isset($_SESSION["idUser"])) { // Utente loggato
											$id=$_SESSION["idUser"];
											$query="SELECT indirizzoIP, dataOra, durata FROM accessi WHERE idUtente=$id ORDER BY dataOra";
											if($result=$conn->query($query))
												if($result->num_rows>0){
													echo "
														<h3><strong>Ultimi accessi</h3>
														<table style='text-align:center;' class='mb-4' width='100%'>
															  <tr>
																<th>Indirizzo IP</th>
																<th>Data e ora accesso</th>
																<th>durata sessione</th>
															  </tr>"; // Intestazione tabella ultimi accessi (MAX 10)
															  $i=1;
													while($accesso=$result->fetch_assoc()) {
														echo"
																  <tr>
																	<td>$accesso[indirizzoIP]</td>
																	<td>$accesso[dataOra]</td>";
														if($i!=$result->num_rows)			
															echo"   <td>$accesso[durata]</td>";
														else
															echo"   <td>In corso</td>";
														echo"		  </tr>"; // Costruzione riga tabella
														$i++;
													}

													$query="SELECT username, email, admin FROM utenti WHERE id=$id"; // Preparazione query: Username ed email Utente
													
													if($result=$conn->query($query))
														if($result->num_rows==1) { // 1 Risultato
														$utente=$result->fetch_assoc();
														
														echo '	</table>
																<h3><strong>Username: </strong>'.$utente["username"].'</h3>
																<h3><strong>Email: </strong>'.$utente["email"].'</h3>
														'; // Intestazione (pt.1)

														if($utente["admin"]==1)
															echo '<h3>Sei un <strong>AMMINISTRATORE</strong></h3>'; // Intestazione (pt.2)
														echo'
																<form name="changepw" id="changepw" method="post" onsubmit="return check()" action="index.php?stato='.$_GET['stato'].'">
																	<div class="form-group row">
																		<label for="inputPassword" class="col-sm-2 col-form-label">Vecchia password</label>
																		<div class="col-sm-10">
																		  <input type="password" class="form-control" id="oldpw" name="oldpw" placeholder="Vecchia password" required>
																		</div>
																	</div>
																	<div class="form-group row">
																		<label for="inputPassword" class="col-sm-2 col-form-label">Nuova password</label>
																		<div class="col-sm-10">
																		  <input type="password" class="form-control" id="newpw" name="newpw" placeholder="Nuova password" required>
																		</div>
																	</div>
																	<div class="form-group row">
																		<label for="inputPassword" class="col-sm-2 col-form-label">Conferma password</label>
																		<div class="col-sm-10">
																		  <input type="password" class="form-control" id="newpwc" name="newpwc" placeholder="Conferma password" required>
																		</div>
																	</div>
																	<p style="color:red; visibility:hidden" id="avviso" name="avviso" class="ml-2">Le due password devono coincidere</p>

																	<button type="submit" class="btn btn-primary ml-2 mt-2">Cambia password</button>
														'; // Riquadro ulteriori informazioni e modifica password
													}
														$result->free(); // Dealloco l'oggetto
												}
										}	
										echo '</div>';
										$conn->close();
										
										break;
									
									case 15: 
										/*
										************************
										*** PAGINA CATEGORIA ***
										************************
										*/
										
										$conn=dbConn(); // Connessione al DB
										$pagina=$_GET["pagina"]; // Numero pagina corrente 
										$ordinamento=intval($_GET["ordinamento"]); // Ordinamento corrente
										$pagina2=$_GET["pagina2"]; // Numero pagina corrente 
										$ordinamento2=intval($_GET["ordinamento2"]); // Ordinamento corrente
										$id=$_GET["id"]; // id Genere 
										
										// FILM E DOCUMENTARI
										switch(floor($ordinamento/2)) { // Tipo di ordinamento
											case 0:
												$ord="V.nome"; 
												break;
											case 1:
												$ord="mediaVoti";
												break;
											case 2:
												$ord="V.durata";
												break;
											case 3:
												$ord="V.annoUscita";
												break;
											case 4:
												$ord="V.nazionalita";
												break;
										}
										if($ordinamento%2==1) // Ordinamento Discendente
											$ord.=" DESC";
										if(floor($ordinamento/2)==1)
											$ord.=", V.id DESC";

										$nris=8; // Risultati da mostrare per pagina
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, V.annoUscita, V.nazionalita, AVG(RV.voto) mediaVoti 
										FROM recensionivideo RV RIGHT JOIN video V ON V.id=RV.idVideo JOIN generivideo GV ON GV.idVideo=V.id 
										JOIN generi G ON G.id=GV.idGenere WHERE V.selettore!=2 AND G.id=$id 
										GROUP BY V.id
										ORDER BY $ord
										LIMIT $nris
										OFFSET ".($nris*($pagina-1)); // Preparazione Query: Tutti i film e i doucmentari appartenenti alla categoria 
										$nTot=$conn->query("SELECT G.tipo, COUNT(*) nTot 
															 FROM generivideo GV JOIN video V ON V.id=GV.idVideo JOIN generi G ON G.id=GV.idGenere
															 WHERE GV.idGenere=$id AND V.selettore!=2")->fetch_assoc(); // Numero totale di film e documentari 

										echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Film e Documentari ('.$nTot["tipo"].')</h2>
											</div>
											'); // Titolo

										$valori=["Nome","Voto","Durata","Anno d'uscita", "Nazionalità"]; // Valori dell'ordinamento
										echo ('
											<div class="container"> Ordina per: 
												<select id="ord" onchange="passa_a('.$id.',15,1,ord.value,'.$pagina2.','.$ordinamento2.')">
											'); // Scelta dell'ordinamento
													for($i=0;$i<10;$i++) {
														echo '<option value="'.$i.'"';
														if($ordinamento==$i)
															echo 'selected>';
														else
															echo '>';
														echo $valori[$i/2];
														if($i%2==1)
															echo ' DECR';
														echo '</option>';
													}
										echo ('
												</select>
											</div>
										');
										
										if ($risultati=$conn->query($query)) { // Query effettuata con successo 
											if ($risultati->num_rows>0) { // Almeno un risultato 
												while ($video = $risultati->fetch_assoc()) {
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$video["id"].',5,null,null,null,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$video["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$video["nome"].'">
																	<p class="card-text">'.$video["nome"].'</p>
																	<p class="card-text-description">'.$video["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$video["durata"].' minuti</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Anno d\'uscita: '.$video["annoUscita"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$video["nazionalita"].'</small>
																	</div>
														');
													if($video["mediaVoti"]!=null)
														echo('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($video["mediaVoti"],2).'</small>
																	</div>
															'); // Costruisco un riquadro per ogni film o documentario (pt.1)

													$query="SELECT G.tipo
													FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
													WHERE GV.idVideo=".$video["id"]."
													GROUP BY G.tipo"; // Preparazione query: Categorie film
													if($generi=$conn->query($query)) { // Query effettuata con successo
														if ($generi->num_rows>0) { // Almeno un risultato
															echo ('
																<div class="d-flex flex-row-reverse align-items-center">
																	<small class="text-muted">Categorie:
																');
															$i=0;
															while ($genere = $generi->fetch_assoc()) {
																echo $genere["tipo"];
																if($i<($generi->num_rows-1))
																	echo ', ';
																$i++;
															}
															echo ('
																	</small>
																</div>
															'); // Costruisco un riquadro per ogni film o documentario (pt.2)
														}
														$generi->free(); // Dealloco l'oggetto
													}

													echo ('
															</div>
														</div>
													</div>
															'); // Costruisco un riquadro per ogni film o documentario (pt.3)
												}
												echo "<div class='container'>";
												if ($pagina!=1) // Non è la prima pagina: Il tasto indietro funzionerà
													echo "<input type='button' value='<' / onclick='passa_a($id,15,".($pagina-1).",$ordinamento,$pagina2,$ordinamento2);'>";
												else // E' la prima pagina: Il tasto indietro non funzionerà
													echo "<input type='button' value='<' disabled />";

												for($i=1;$i<=ceil($nTot["nTot"]/$nris);$i++) { // Bottoni pagine
													echo "<button onclick='passa_a($id,15,$i,$ordinamento,$pagina2,$ordinamento2);'";
													if($i==$pagina) // Se la pagina è la corrente la evidenzio
														echo "style='background-color: black; color: white;' disabled>$i";
													else
														echo ">$i";
													echo "</button>";
												}
												if ($pagina!=ceil($nTot["nTot"]/$nris)) // Non è l'ultima pagina : Il tasto avanti funzionerà
														echo "<input type='button' value='>' / onclick='passa_a($id,15,".($pagina+1).",$ordinamento,$pagina2,$ordinamento2);'>";
													else // E' l'ultima pagina : Il tasto avanti non funzionerà
														echo "<input type='button' value='>' disabled />";	
												echo '</div>';
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi
											}
											$risultati->free(); // Dealloco l'oggetto
										}																			
										
										// SERIE TV
										switch(floor($ordinamento2/2)) { // Tipo di ordinamento
											case 0:
												$ord2="S.nome";
												break;
											case 1:
												$ord2="mediaVoti, S.id";
												break;
											case 2:
												$ord2="nStagioni";
												break;
											case 3:
												$ord2="nEpisodi";
												break;
											case 4:
												$ord2="annoUscita";
												break;
											case 5:
												$ord2="annoFine";
												break;
											case 6:
												$ord2="V.nazionalita";
												break;
										}
										if($ordinamento2%2==1) // Ordinamento decrescente
											$ord2.=" DESC";
										if(floor($ordinamento/2)==1)
											$ord2.=", S.id DESC";

										$query="SELECT S.*, MIN(V.annoUscita) annoUscita, MAX(V.annoUscita) annoFine, COUNT(DISTINCT V.id) nEpisodi, COUNT(DISTINCT V.stagione) nStagioni, AVG(RS.voto) mediaVoti, V.nazionalita
										FROM serie S INNER JOIN video V ON V.idSerie=S.id LEFT JOIN recensioniserie RS ON RS.idSerie=S.id
										WHERE V.id IN (SELECT idVideo FROM generivideo WHERE idGenere=$id)
										GROUP BY S.id
										ORDER BY $ord2
										LIMIT $nris
										OFFSET ".($nris*($pagina2-1)); // Preparazione Query: Tutte le serie 
										$nSerie=$conn->query("SELECT COUNT(DISTINCT id) nSerie FROM serie S")->fetch_assoc(); // Numero totale delle serie
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Serie('.$nTot["tipo"].')</h2>
											</div>
											'); // Titolo

											$valori=["Nome","Voto","Numero Stagioni","Numero Episodi", "Anno d'uscita", "Anno di fine", "Nazionalità"]; // Valori dell'ordinamento
											echo ('
												<div class="container"> Ordina per: 
													<select id="ord2" onchange="passa_a('.$id.',15,'.$pagina.','.$ordinamento.',1,ord2.value)">
												'); // Scelta dell'ordinamento
											for($i=0;$i<14;$i++) {
												echo '<option value="'.$i.'"';
												if($ordinamento2==$i)
													echo 'selected>';
												else
													echo '>';
												echo $valori[$i/2];
												if($i%2==1)
													echo ' DECR';
												echo '</option>';
											}
											echo ('
													</select>
												</div>
											');

										if ($serie=$conn->query($query)) { // Query effettuata con successo
											if ($serie->num_rows>0) { // Almeno un risultato
												while ($elemento = $serie->fetch_assoc()) { 
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null,null,,null,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Stagioni: '.$elemento["nStagioni"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi: '.$elemento["nEpisodi"].'</small>
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
													'); // Costruisco un riquadro per ogni serie (pt.1)
																	if($elemento["annoUscita"]!=$elemento["annoFine"])
																	echo '	<small class="text-muted">Anni di produzione: '.$elemento["annoUscita"].'-'.$elemento["annoFine"].'</small>';
																	else
																	echo '	<small class="text-muted">Anno di produzione: '.$elemento["annoUscita"].'</small>';
																	if($elemento["mediaVoti"]!=null)
																	echo('
																	</div>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($elemento["mediaVoti"],2).'</small>
																	</div>
																	'); // Costruisco un riquadro per ogni serie (pt.2)
														echo ('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Nazionalità: '.$elemento["nazionalita"].'</small>
																	</div>
															'); // Costruisco un riquadro per ogni serie (pt.3)

															$query="SELECT DISTINCT G.tipo
															FROM generivideo GV JOIN generi G ON G.id=GV.idGenere
															WHERE GV.idVideo IN (
																				 SELECT V.id
																				 FROM video V JOIN serie S ON S.id=V.idSerie
																				 WHERE S.id=".$elemento["id"].")
															ORDER BY G.tipo"; // Preparazione query: Categorie serie TV
															if($generi=$conn->query($query)) { // Query effettuata con successo
																if ($generi->num_rows>0) { // Almeno un risultato
																	echo ('
																		<div class="d-flex flex-row-reverse align-items-center">
																			<small class="text-muted">Categorie:
																		'); 
																	$i=0;
																	while ($genere = $generi->fetch_assoc()) {
																		echo $genere["tipo"];
																		if($i<($generi->num_rows-1))
																			echo ', ';
																		$i++;
																	}
																	echo ('
																			</small>
																		</div>
																	'); // Costruisco un riquadro per ogni serie TV (pt.2)
																}
																$generi->free(); // Dealloco l'oggetto
															}

														echo ('
																</div>
															</div>
														</div>
														'); // Costruisco un riquadro per ogni serie TV (pt.3)
												}
												echo "<div class='container'>";
												if ($pagina2!=1) // Non è la prima pagina: Il tasto indietro funzionerà
													echo "<input type='button' value='<' / onclick='passa_a($id,15,$pagina,$ordinamento,".($pagina2-1).",$ordinamento2);'>";
												else // E' la prima pagina: Il tasto indietro non funzionerà
													echo "<input type='button' value='<' disabled />";
												for($i=1;$i<=ceil($nSerie["nSerie"]/$nris);$i++) { // Bottoni pagine
													echo "<button onclick='passa_a($id,15,$pagina,$ordinamento,$i,$ordinamento2);'";
													if($i==$pagina2) // Se la pagina è la corrente la evidenzio
														echo " style='background-color: black; color: white;' disabled>$i";
													else
														echo ">$i";
													echo "</button>";
												}
												if ($pagina2!=ceil($nSerie["nSerie"]/$nris)) // Non è l'ultima' pagina: Il tasto avanti funzionerà
														echo "<input type='button' value='>' / onclick='passa_a($id,15,$pagina,$ordinamento,".($pagina2+1).",$ordinamento2);'>";
												else // E' l'ultima' pagina: Il tasto avanti non funzionerà
													echo "<input type='button' value='>' disabled />";	
												echo '</div>';
											}
											else { 
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di elementi
											}
											$serie->free();	// Dealloco l'oggetto
										}
										
										echo ('
											<div class="container">
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											</div>
											');
										//---
										$conn->close(); // Chiudo la connessione al DB
										break;
									
									default:
									echo "<h1><strong>404. PAGE NOT FOUND</strong></h1>";
									break;
								}
								if($stato!=14)
									echo "</form>";
							?>
			
		</main>

		<form name="recensione" id="recensione" method="post" action="
		<?php
			echo 'index.php?stato='.$_GET["stato"].'&id='.$_GET["id"].'">';
		?>
			<input type='hidden' name='rate' id='rate'> <!-- Memorizzazione voto -->
			<input type='hidden' name='rec' id='rec'> <!-- Memorizzazione recensione -->
			<input type='hidden' name='idUtente' id='idUtente'> <!-- Memorizzazione recensione -->
				
		</form>
		
		<form name="curiosita" id="curiosita" method="post" action="
		<?php
			echo 'index.php?stato='.$_GET["stato"].'&id='.$_GET["id"].'">';
		?>
			<input type='hidden' name='check' id='check'> <!-- Memorizzazione voto -->
			<input type='hidden' name='cur' id='cur'> <!-- Memorizzazione recensione -->
			<input type='hidden' name='idCur' id='idCur'> <!-- Memorizzazione recensione -->
				
		</form>	
		
		<footer class="text-muted">
			<div class="container">
				<p class="float-right">
				<a href="#">Back to top</a>
				</p>
				
				<!--<p>Album example is &copy; Bootstrap, but please download and customize it for yourself!</p>
				<p>New to Bootstrap? <a href="https://getbootstrap.com/">Visit the homepage</a> or read our <a href="../getting-started/introduction/">getting started guide</a>.</p>-->
			</div>
		</footer>
		<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
			<script>window.jQuery || document.write('<script src="../assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
			<script src="assets/dist/js/bootstrap.bundle.js"></script>
	</body>
</html>
