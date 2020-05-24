<?php
	/* 
		DA FARE:
		- Aggiungere campo datauscita al video (da discutere)
		- Copiare e incollare quanto fatto per le recensioni dei film per le curiosità dei film (non c'è voto, possono esserci più commenti(?))
		- Copiare e incollare quanto sopra per le curiosità delle serie
		- Permettere ad Admin di validare commenti  
		- Registrazione (nuova pagina)
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
	*/

	// Controllo Sessioni 
	session_start();
	if(!isset($_SESSION["login"]) || $_SESSION["login"]!=1) // Mancata presenza di dati integri per login 
		session_unset();
	if(isset($_GET["stato"])&&$_GET["stato"]=="logout") { // Operazione di logout 
		session_unset();
		session_destroy();
		session_start(); // Chiudo e riapro la sessione
		if(isset($_GET["logout"]))
			$_GET["stato"]=$_GET["logout"];
	}
	
	function dbConn() { // Connessione al DB 
		$host = ""; // Host Server MySQL 
		$user = "root"; // User Server MySQL 
		$pwd = ""; // Password Server MySQL 
		$dbname = "catalogo"; // Nome DB MySQL 
		$conn = new mysqli ( $host , $user , $pwd , $dbname ); // Inizializzazione Connesione DB 
		if ($conn->connect_errno) { //
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
			transition: all 0.7s;
			color: white;	   
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

		</style>
		<!-- -->
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> <!-- Libreria Bootstrap ? -->
		<script> // Funzioni JavaScript 
			function logout(id,stato) { // Effettua il logout 
				f.stato.value="logout";
				f.submit();
			}
			
			function passa_a(id,stato,pagina) { // Modifica lo stato (e quindi la pagina), l'identificativo e il numero di pagina
				f.id.value=id;
				f.stato.value=stato;
				f.pagina.value=pagina;
				f.submit();
			}
			
			function recensione(id) { // Controlli sulla recensione ed effettivo inserimento 
				if(f.rate.value==0)
					alert("Errore! Devi inserire un voto per lasciare una recensione");
				else{
					if(f.textarea.value!="")
						recensioni.rec.value=f.textarea.value;
					recensioni.rate.value=f.rate.value;
					recensioni.submit();
				}
			}
			function elimina(id,idUtente) { // Elimina una recensione 
				recensioni.rate.value="ELIMINA";
				recensioni.idUtente.value=idUtente;
				recensioni.submit();
			}
			function verifica(id,idUtente){ // Verifica della recensione da parte dell'admin 
				recensioni.rate.value="VERIFICA";
				recensioni.idUtente.value=idUtente;
				recensioni.submit();
			}
			function controllo(){
				if(registrazione.pass1.value!=registrazione.pass2.value){
					document.getElementById('avviso1').style.visibility="visible";
					return false;
				}
				else
					if(registrazione.pass1.value==""||registrazione.newUser.value==""||registrazione.newEmail.value==""){
						document.getElementById('avviso1').style.visibility="hidden";
						document.getElementById('avviso2').style.visibility="visible";
						return false;
					}
					else{
						registrazione.submit();
						return true;
					}
			}
			
		</script>
  	</head>
  	<body>
		<header>
		  	<div class="navbar navbar-dark bg-dark shadow-sm"> <!-- Base della Navbar-->
		 		<div class="container d-flex justify-content-between"> <!-- Contenitore delle scorciatoie -->
			  		<a href="#" class="navbar-brand d-flex align-items-center" onclick="passa_a(null,0,null)"> <!-- Scorciatoia Homepage -->
						<strong>Homepage</strong>
					</a>
					
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
							VALUES ('$user','$email','$password',0)";
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
										<button class="dropdown-item" type="button">Visualizza Profilo</button>
										<button class="dropdown-item" type="button">Visualizza Recensioni</button>
										<button class="dropdown-item" type="button">Visualizza Curiosità</button>
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
			<form name='f' id='f' method='get'> <!-- Form stato, id, pagina, logout (facoltativo) -->
				<input type='hidden' name='stato' id='stato'> <!-- Identificativo della pagina da caricare -->
				<div class="album py-5 bg-light">
					<div class="container">
						<div class="row">
							<?php
								echo "<input type='hidden' name='id' id='id'"; // Identificativo dell'oggetto a cui si fa riferimento
								if(isset($_GET["id"])&&!empty($_GET["id"])){ // Id conosciuto
									 echo "value='$_GET[id]'";
								}
								echo ">";

								echo "<input type='hidden' name='pagina' id='pagina'"; // Numero di pagina 
								if(isset($_GET["pagina"])&&!empty($_GET["pagina"])) { // Pagina conosciuta
									 echo "value='$_GET[pagina]'>";
								}
								else
									echo "value='1'>";

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
										$query="SELECT V.id, V.nome, V.durata, V.idSaga, V.numero, V.sinossi, AVG(RV.voto) mediaVoti
										FROM recensionevideo RV JOIN video V ON V.id=RV.idVideo
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
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null)">
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
																	<p class="card-text">Nessun video è ancora stato recensito</p>
																</div>
															</div>
														</div>
													'); // Comunicazione mancanza di video recensiti
											}
											$video->free();	// Dealloco l'oggetto
										}

										// MIGLIORI FILM
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, AVG(RV.voto) mediaVoti
										FROM recensionevideo RV JOIN video V ON V.id=RV.idVideo
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
														<div class="col-md-3 py2" onclick="passa_a('.$film["id"].',5,null)">
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
													'); // Comunicazione mancanza di film recensiti
											}
											$video->free();	// Dealloco l'oggetto
										}
										echo ('	
														<div class="container" onclick="passa_a(null,1,1)"><a href="#">Vedi tutti</a></div>	
											'); // Link che mostra tutti i film (case 1) */

										// MIGLIORI SERIE TV
										$query="SELECT S.*, AVG(RS.voto) mediaVoti, MAX(V.stagione) nStagioni, COUNT(DISTINCT V.id) nEpisodi
										FROM recensioneserie RS JOIN serie S ON S.id=RS.idSerie JOIN video V on V.idserie=S.id
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
														<div class="col-md-3 py2" onclick="passa_a('.$serie["id"].',6,null)">
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
																</div>
															</div>
														</div>
													'); // Costruisco un riquadro per ogni serie TV
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
														<div class="container" onclick="passa_a(null,2,1)"><a href="#">Vedi tutte</a></div>	
											'); // Link che mostra tutte le serie (case 2)

										// MIGLIORI SAGHE
										$query="SELECT AVG (Medie.mediaVoti) media, COUNT(DISTINCT V.id) nFilm, S.id, S.nome
										FROM (
												SELECT  AVG(R.voto) mediaVoti, V.*
												FROM recensionevideo R JOIN video V ON V.id=R.idVideo JOIN saghe S ON S.id=V.idSaga
												WHERE R.idAdmin IS NOT NULL
												GROUP BY V.id
											) Medie
										JOIN saghe S ON S.id=Medie.idSaga
                                        JOIN video V ON V.idSaga=Medie.idSaga
										GROUP BY Medie.idSaga
										ORDER BY Medie.mediaVoti DESC
										LIMIT 8"; // Preparazione Query: Migliori saghe (base voto)
										
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Saghe</h2>
											</div>
											'); // Titolo

										if ($saghe=$conn->query($query)) { // Query effettuata con successo
											if ($saghe->num_rows>0) { // Almeno un risultato 
												while ($saga = $saghe->fetch_assoc()) {  
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',7,null)">
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
																</div>
															</div>
														</div>
													');
												} // Costruisco un riquadro per ogni saga TV
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
														<div class="container" onclick="passa_a(null,3,1)"><a href="#">Vedi tutte</a></div>	
											'); // Link che mostra tutte le saghe (case 3) */

										// MIGLIORI DOCUMENTARI 
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, AVG(RV.voto) mediaVoti
										FROM recensionevideo RV JOIN video V ON V.id=RV.idVideo
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
														<div class="col-md-3 py2" onclick="passa_a('.$documentario["id"].',5,null)">
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
																</div>
															</div>
														</div>
													');  // Costruisco un riquadro per ogni documentario
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
														<div class="container" onclick="passa_a(null,4,1)"><a href="#">Vedi tutti</a></div>	
											'); // Link che mostra tutte i documentari

										$conn->close(); // Chiudo la connessione al DB
										break;
									

									case 1: /* 
										******************
										*** TUTTI FILM ***
										******************
										*/
										$conn=dbConn(); // Connessione al DB
										$pagina=$_GET["pagina"]; /* Numero pagina corrente */
										$stato=$_GET["stato"];/* Stato della pagina corrente */
										$nris=8; /* Riusltati da mostrare per pagina */
										$query="SELECT V.id, V.nome, V.durata, V.sinossi, AVG(RV.voto) mediaVoti
										FROM recensionevideo RV RIGHT JOIN video V ON V.id=RV.idVideo
										WHERE V.selettore=1
										GROUP BY V.id
										ORDER BY mediaVoti DESC
										LIMIT $nris
										OFFSET ".($nris*($pagina-1)); /* Preparazione Query: Tutti i film (ordinati per voto) */
										$nFilm=$conn->query("SELECT COUNT(*) nFilm FROM video WHERE video.selettore=1")->fetch_assoc(); /* Numero totale di film */

										echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Tutti i Film</h2>
											</div>
											');

										if ($video=$conn->query($query)) { /* Query effettuata con successo */
											if ($video->num_rows>0) { /* Almeno un risultato */
												while ($film = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni film */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$film["id"].',5,null)">
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/video/'.$film["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/video/default.jpg\';" alt="Locandina di '.$film["nome"].'">
																	<p class="card-text">'.$film["nome"].'</p>
																	<p class="card-text-description">'.$film["sinossi"].'</p>
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Durata: '.$film["durata"].' minuti</small>
																	</div>
														');
														if($film["mediaVoti"]!=null)
															echo('
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Voto Medio: '.round($film["mediaVoti"],2).'</small>
																	</div>
																');

														echo ('
																</div>
															</div>
														</div>
															');
												}
											}
											else { /* Comunicazione mancanza di film */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun film trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$video->free(); // Dealloco l'oggetto
										}
										echo "<div class='container'>";
										if ($pagina!=1)
											echo "<input type='button' value='<' / onclick='passa_a(null,$stato,".($pagina-1).")'>";
										else
											echo "<input type='button' value='<' disable />";
										for($i=1;$i<=ceil($nFilm["nFilm"]/$nris);$i++) {
											echo '<button onclick="passa_a(null,'.$stato.','.$i.')";';
											if($i==$pagina)
												echo " style='background-color: black; color: white;'>$i";
											else
												echo ">$i";
											echo "</button>";
										}
										if ($pagina!=ceil($nFilm["nFilm"]/$nris))
												echo "<input type='button' value='>' / onclick='passa_a(null,$stato,".($pagina+1).")'>";
											else
												echo "<input type='button' value='>' disable />";	
										echo ('
											</div>
											<div class="container">
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											</diV>
											');

										$conn->close(); // Chiudo la connessione al DB
										break;

									case 2: /* 
										*************************
										*** TUTTE LE SERIE TV ***
										*************************
										*/

										$conn=dbConn(); // Connessione al DB
										$pagina=$_GET["pagina"]; /* Numero pagina corrente */
										$stato=$_GET["stato"];/* Stato della pagina corrente */
										$nris=8; /* Riusltati da mostrare per pagina */
										$query="SELECT *
										FROM serie
										LIMIT $nris
										OFFSET ".($nris*($pagina-1)); /* Preparazione Query: Tutte le serie */
										$nSerie=$conn->query("SELECT COUNT(DISTINCT S.id) nSerie FROM serie S JOIN video V ON V.idSerie=S.id")->fetch_assoc(); /* Numero totale di film */
										echo ('	
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Serie piu\' recenti</h2>
											</div>
											');
										if ($serie=$conn->query($query)) { /* Query effettuata con successo */
											if ($serie->num_rows>0) { /* Almeno un risultato */
												while ($elemento = $serie->fetch_assoc()) { /* Costruisco un riquadro per ogni serie */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
														');
														$query="SELECT COUNT(S.id) nepisodi
														FROM serie S JOIN video V ON S.id=V.idSerie 
														WHERE V.idSerie=".$elemento["id"]; /* Preparazione Query: Numero episodi della serie */
														if ($risultato=$conn->query($query)) { /* Query effettuata con successo */
															if ($risultato->num_rows==1) { /* Almeno un risultato */
																$nepisodi = $risultato->fetch_assoc();
													echo ('			
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi: '.$nepisodi["nepisodi"].'</small>
																	</div>
																</div>
															</div>
														</div>
														');
															}
														}
														$risultato->free(); // Dealloco l'oggetto
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$serie->free();	// Dealloco l'oggetto
										}
										
										echo "<div class='container'>";
										if ($pagina!=1)
											echo "<input type='button' value='<' / onclick='passa_a(null,$stato,".($pagina-1).")'>";
										else
											echo "<input type='button' value='<' disable />";
										for($i=1;$i<=ceil($nSerie["nSerie"]/$nris);$i++) {
											echo '<button onclick="passa_a(null,'.$stato.','.$i.')";';
											if($i==$pagina)
												echo " style='background-color: black; color: white;'>$i";
											else
												echo ">$i";
											echo "</button>";
										}
										if ($pagina!=ceil($nSerie["nSerie"]/$nris))
												echo "<input type='button' value='>' / onclick='passa_a(null,$stato,".($pagina+1).")'>";
											else
												echo "<input type='button' value='>' disable />";	
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
										$pagina=$_GET["pagina"]; /* Numero pagina corrente */
										$stato=$_GET["stato"];/* Stato della pagina corrente */
										$nris=8; /* Riusltati da mostrare per pagina */
										
										$query="SELECT S.id, S.nome, COUNT(*) nFilm
										FROM saghe S
										JOIN video V ON S.id=V.idSaga
										GROUP BY S.id
										LIMIT $nris
										OFFSET ".($nris*($pagina-1)); /* Preparazione Query: Tutte le saghe */
										
										$nSaghe=$conn->query("SELECT COUNT(DISTINCT S.id) nSaghe FROM saghe S JOIN video V ON S.id=V.idSaga")->fetch_assoc(); /* Numero totale di saghe */ 										
										
										echo ('
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Tutte le saghe</h2>
											</div>
											');

										if ($saghe=$conn->query($query)) { /* Query effettuata con successo */
											if ($saghe->num_rows>0) { /* Almeno un risultato */
												while ($saga = $saghe->fetch_assoc()) { /* Costruisco un riquadro per ogni film */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',7,null)">
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
														');
												}
											}
											else { /* Comunicazione mancanza di film */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun film è ancora stato recensito</p>
																</div>
															</div>
														</div>
													');
											}
											$saghe->free();	// Dealloco l'oggetto
										}
										
										echo "<div class='container'>";
										if ($pagina!=1)
											echo "<input type='button' value='<' / onclick='passa_a(null,$stato,".($pagina-1).")'>";
										else
											echo "<input type='button' value='<' disable />";
										for($i=1;$i<=ceil($nSaghe["nSaghe"]/$nris);$i++) {
											echo '<button onclick="passa_a(null,'.$stato.','.$i.')";';
											if($i==$pagina)
												echo " style='background-color: black; color: white;'>$i";
											else
												echo ">$i";
											echo "</button>";
										}
										if ($pagina!=ceil($nSaghe["nSaghe"]/$nris))
												echo "<input type='button' value='>' / onclick='passa_a(null,$stato,".($pagina+1).")'>";
											else
												echo "<input type='button' value='>' disable />";	
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
										echo ('
											<div class="container"><input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)"/></div>
											<div class="container">TUTTI I DOCUMENTARI</div>
											<div class="container"><input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)"/></div>
											');
										break;
									
									case 5: /* 
										**********************
										*** DETTAGLI VIDEO ***
										**********************
										*/
										$id=$_GET["id"]; /* idVideo */
										$conn=dbConn(); // Connessione al DB
										
										
										if(isset($_POST["rate"])&&isset($_SESSION["idUser"])) { /* E' stato dato un voto */
											$voto=$_POST["rate"];
											if(isset($_POST["rec"])&&$_POST["rec"]!="") /* E' stata data una recensione */
												$rec="'".filter_var($_POST["rec"], FILTER_SANITIZE_STRING)."'";
											else
												$rec="null";
											$query="SELECT * FROM recensionevideo WHERE idVideo=$id AND idUtente=$_SESSION[idUser]"; /* Controllo che non abbia già fatto una recensione */
											$controllo=$conn->query($query);
											if($voto!="ELIMINA"&&$voto!="VERIFICA") { /* Pubblico o modifico la recensione */
												if($controllo->num_rows==0){ /* Non ha già recensito:  */
													$recens="INSERT INTO recensionevideo VALUES ($id,$_SESSION[idUser],'$voto',$rec,null)";
													
													if($conn->query($recens)) /* Inserimento nel DB riuscito */
														echo "<script type='text/javascript'>alert('La tua recensione è stata inserita!');</script>";
													else /* Inserimento nel DB NON riuscito */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
												}
												else{
													$recens="UPDATE recensionevideo SET voto='$voto', testo=$rec, idAdmin=null 
													WHERE idVideo=$id AND idUtente=$_SESSION[idUser]";
													
													if($conn->query($recens)) /* Modifica del DB riuscita */
														echo "<script type='text/javascript'>alert('La tua recensione è stata aggiornata!');</script>";
													else /* Modifica del DB NON riuscita */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
											else{
												if($voto=="ELIMINA") { /* Eliminazione del DB riuscita */
													$recens="DELETE FROM recensionevideo WHERE idVideo=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else
												{
													$recens="UPDATE recensionevideo SET idAdmin=$_SESSION[idUser] WHERE idVideo=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}

										$query="SELECT V.id,V.nome,V.durata,V.idSaga,v.idSerie,V.numero,V.stagione,V.sinossi,Se.nome nomeSe,Sa.nome nomeSa 
										FROM video V LEFT JOIN serie Se ON V.idSerie=Se.id LEFT JOIN saghe Sa ON Sa.id=V.idSaga 
										WHERE V.id=$id;"; /* Preparazione Query: Dettagli video */
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
												</div>
												</div>
												<div class="d-flex justify-content-end bd-highlight mb-3">
													<small class="text-muted">Durata: '.$video["durata"].' minuti</small>
												</div>
												<div class="row">
											');

										if($video["idSerie"]!=null)
											echo ('
												<div class="container text-left">
													<p class="card-text" onclick="passa_a('.$video["idSerie"].',6,null);"><strong>Serie: </strong><a href="#"> '.$video["nomeSe"].' ('.$video["stagione"].'X'.$video["numero"].')</a></p>
												</div>
											');
										else if($video["idSaga"]!=null)
											echo ('
													<div class="container text-left">
														<p class="card-text" onclick="passa_a('.$video["idSaga"].',7,null);"><strong>Saga: </strong><a href="#"> '.$video["nomeSa"].' ('.$video["numero"].'° film)</a></p>
													</div>
												');
										
										$query="SELECT Par.idPersona, Per.nome, Per.cognome, Pggi.nome nomeP 
										FROM partecipazioni Par JOIN video V ON Par.idVideo=V.id JOIN persone Per ON Per.id=Par.idPersona JOIN interpretazioni I ON I.idAttore=Per.id JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										WHERE V.id=$id AND Par.selettore=2"; /* Preparazione Query: Attori Film */

										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["idPersona"].',8,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														');
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>');
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Par.idPersona, Per.nome, Per.cognome
										FROM partecipazioni Par JOIN video V ON Par.idVideo=V.id JOIN persone Per ON Per.id=Par.idPersona
										WHERE V.id=$id AND Par.selettore=1"; /* Preparazione Query: Registi Film */

										if ($registi=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												');
											if ($registi->num_rows>0) {
												while ($regista = $registi->fetch_assoc()) { /* Costruisco un riquadro per ogni regista */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["idPersona"].',8,null)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$registi->free(); // Dealloco l'oggetto
										}
																				
										$query="SELECT Par.idPersona, Per.nome, Per.cognome
										FROM partecipazioni Par JOIN video V ON Par.idVideo=V.id JOIN persone Per ON Per.id=Par.idPersona 
										WHERE V.id=$id AND Par.selettore=3"; /* Preparazione Query: Produttori Film */

										if ($produttori=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												');
											if ($produttori->num_rows>0) {
												while ($produttore = $produttori->fetch_assoc()) { /* Costruisco un riquadro per ogni produttore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["idPersona"].',8,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["idPersona"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$produttori->free(); // Dealloco l'oggetto
										}

										$query="SELECT P.* 
										FROM video V JOIN comparizioni C ON V.id=c.idVideo JOIN personaggi P ON P.id=C.idPersonaggio 
										WHERE V.id=$id"; /* Preparazione Query: Personaggi Film */

										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												');
											if ($personaggi->num_rows>0) {
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
										}
										$personaggi->free(); // Dealloco l'oggetto
										
										if($_SESSION["login"]==1){
											$query="SELECT voto, testo, username FROM recensionevideo LEFT JOIN Utenti ON idAdmin=id WHERE idVideo=$id AND idUtente=$_SESSION[idUser]";
											$recensione=$conn->query($query);
											if($recensione->num_rows==0){
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
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Salva recensione</button>
															  </div>
															</div>
														  </div>
														</div>
													</div>');
											}
											else{
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
																
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Modifica recensione</button>
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
													</div>');
											}
										}
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Recensioni degli utenti</h2>
													</div>
												');
										$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
										FROM recensionevideo R 
										INNER JOIN utenti U ON U.id=R.idUtente
										LEFT JOIN utenti A ON A.id=R.idAdmin
										WHERE idVideo=$id
										ORDER BY R.idAdmin DESC,R.idUtente
										LIMIT 4;";
										$recensioni=$conn->query($query);
										if($recensioni->num_rows==0){
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
														</div>
													</div>
												</div>';
										}		
										else{
												while($riga = $recensioni->fetch_assoc()){
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
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
															</div>
														</div>';
												}
												$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
												FROM recensionevideo R 
												INNER JOIN utenti U ON U.id=R.idUtente
												LEFT JOIN utenti A ON A.id=R.idAdmin
												WHERE R.testo IS NOT NULL AND idVideo=$id
												ORDER BY R.idAdmin DESC,R.idUtente;";
												$recensioni=$conn->query($query);
												if($recensioni->num_rows>4){
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
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
																		</div>';
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

										$id=$_GET["id"]; /* idSerie */
										$conn=dbConn(); // Connessione al DB				

										if(isset($_POST["rate"])&&isset($_SESSION["idUser"])) { /* E' stato dato un voto */
											$voto=$_POST["rate"];
											if(isset($_POST["rec"])&&$_POST["rec"]!="") /* E' stata data una recensione */
												$rec="'".filter_var($_POST["rec"], FILTER_SANITIZE_STRING)."'";
											else
												$rec="null";
											$query="SELECT * FROM recensioneserie WHERE idSerie=$id AND idUtente=$_SESSION[idUser]"; /* Controllo che non abbia già fatto una recensione */
											$controllo=$conn->query($query);
											if($voto!="ELIMINA"&&$voto!="VERIFICA") { /* Pubblico o modifico la recensione */
												if($controllo->num_rows==0){ /* Non ha già recensito:  */
													$recens="INSERT INTO recensioneserie VALUES ($id,$_SESSION[idUser],'$voto',$rec,null)";
													
													if($conn->query($recens)) /* Inserimento nel DB riuscito */
														echo "<script type='text/javascript'>alert('La tua recensione è stata inserita!');</script>";
													else /* Inserimento nel DB NON riuscito */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";	
												}
												else{
													$recens="UPDATE recensioneserie SET voto='$voto', testo=$rec, idAdmin=null 
													WHERE idSerie=$id AND idUtente=$_SESSION[idUser]";
													
													if($conn->query($recens)) /* Modifica del DB riuscita */
														echo "<script type='text/javascript'>alert('La tua recensione è stata aggiornata!');</script>";
													else /* Modifica del DB NON riuscita */
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
											else{
												if($voto=="ELIMINA") { /* Eliminazione del DB riuscita */
													$recens="DELETE FROM recensioneserie WHERE idSerie=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata eliminata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
													
												}
												else
												{
													$recens="UPDATE recensioneserie SET idAdmin=$_SESSION[idUser] WHERE idSerie=$id AND idUtente=$_POST[idUtente]";
													//echo $recens;
													if($conn->query($recens))
														echo "<script type='text/javascript'>alert('La recensione è stata verificata!');</script>";
													else
														echo "<script type='text/javascript'>alert('Siamo spiacenti. Qualcosa è andato storto');</script>";
												}
											}
										}
										
										$query="SELECT * FROM serie WHERE id=$id"; /* Preparazione Query: Dettagli Serie */
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
														<p class="card-text mt-4" onclick="passa_a('.$serie["id"].',11,null);"><strong>Stagioni: </strong><a href="#">Visualizza tutti gli episodi</a></p>
													</div>
												</div>
												</div>
												<div class="row">
											');
										
										$query="SELECT Pers.*,Pggi.nome nomeP 
										FROM partecipazioni P JOIN interpretazioni I ON I.idAttore=P.idPersona JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.idVideo IN 
														(SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
											AND P.selettore=2 
										GROUP BY P.idPersona"; /* Preparazione Query: Attori Serie  */

										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														');
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>');
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pers.* 
										FROM partecipazioni P JOIN video V ON V.id=P.idVideo JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.selettore=1 AND P.idVideo IN ( 
																			SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY P.idPersona"; /* Preparazione Query: Registi Serie */

										if ($registi=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												');
											if ($registi->num_rows>0) {
												while ($regista = $registi->fetch_assoc()) { /* Costruisco un riquadro per ogni regista */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["id"].',8,null)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$registi->free(); // Dealloco l'oggetto
										}
																				
										$query="SELECT Pers.* 
										FROM partecipazioni P JOIN video V ON V.id=P.idVideo JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.selettore=3 AND P.idVideo IN ( 
																			SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY P.idPersona"; /* Preparazione Query: Produttori Serie */

										if ($produttori=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												');
											if ($produttori->num_rows>0) {
												while ($produttore = $produttori->fetch_assoc()) { /* Costruisco un riquadro per ogni produttore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["id"].',8,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$produttori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pggi.* 
										FROM comparizioni C JOIN personaggi Pggi ON Pggi.id=C.idPersonaggio 
										WHERE C.idVideo IN ( 
															SELECT V.id FROM video V JOIN serie S ON V.idSerie=S.id WHERE S.id=$id) 
										GROUP BY C.idPersonaggio"; /* Preparazione Query: Personaggi Serie */

										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												');
											if ($personaggi->num_rows>0) {
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$personaggi->free(); // Dealloco l'oggetto
										}
										
										
										/*
										**************************
										*****RECENSIONI SERIE*****
										**************************
										*/
										
										if($_SESSION["login"]==1){
											$query="SELECT voto, testo, username FROM recensioneserie LEFT JOIN Utenti ON idAdmin=id WHERE idSerie=$id AND idUtente=$_SESSION[idUser]";
											$recensione=$conn->query($query);
											if($recensione->num_rows==0){
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
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Salva recensione</button>
															  </div>
															</div>
														  </div>
														</div>
													</div>');
											}
											else{
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
																
																<button type="button" onclick="recensione('.$id.')" class="btn btn-primary">Modifica recensione</button>
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
													</div>');
											}
										}
										echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Recensioni degli utenti</h2>
													</div>
												');
										$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
										FROM recensioneserie R 
										INNER JOIN utenti U ON U.id=R.idUtente
										LEFT JOIN utenti A ON A.id=R.idAdmin
										WHERE idSerie=$id
										ORDER BY R.idAdmin DESC,R.idUtente
										LIMIT 4;";
										$recensioni=$conn->query($query);
										if($recensioni->num_rows==0){
											echo 	
												'<div class="col-md-3 py2">
													<div class="card h-100 mb-4 shadow-sm">
														<div class="card-body">
															<p class="card-text" style="text-align:center !important">Non è presente ancora nessuna recensione</p>
														</div>
													</div>
												</div>';
										}		
										else{
												while($riga = $recensioni->fetch_assoc()){
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
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
															</div>
														</div>';
												}
												$query="SELECT R.voto, R.testo,U.username, U.id, A.username admin
												FROM recensioneserie R 
												INNER JOIN utenti U ON U.id=R.idUtente
												LEFT JOIN utenti A ON A.id=R.idAdmin
												WHERE R.testo IS NOT NULL AND idSerie=$id
												ORDER BY R.idAdmin DESC,R.idUtente;";
												$recensioni=$conn->query($query);
												if($recensioni->num_rows>4){
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
																					<button type="button" class="btn btn-primary" onclick="verifica('.$id.','.$riga["id"].')" data-dismiss="modal">Verifica</button>
																				</div>';
																	echo '
																		</div>';
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
										$id=$_GET["id"]; /* idPersona */
										$conn=dbConn(); // Connessione al DB
										
										$query="SELECT S.id,S.nome,count(*) nFilm FROM saghe S 
										JOIN video V ON V.idSaga=S.id
                                        WHERE S.id=$id
										GROUP BY S.id"; /* Preparazione Query: Dettagli Serie */
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
											');
										
										$query="SELECT V.*
										FROM video V
										JOIN saghe S ON V.idSaga=S.id
										WHERE S.id=$id
										ORDER BY v.numero";
										
										if ($video=$conn->query($query)) { /* Risultati della query */
											if ($video->num_rows>0) {
												echo ('	
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Film della saga</h2>
													</div>
													');

												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null);" >
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
												}
											}
											
											$video->free(); // Dealloco l'oggetto
										}
										
										$query="SELECT Pers.*,Pggi.nome nomeP 
										FROM partecipazioni P JOIN interpretazioni I ON I.idAttore=P.idPersona JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.idVideo IN 
														(SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id) 
											AND P.selettore=2 
										GROUP BY P.idPersona"; /* Preparazione Query: Attori Serie  */
										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
														');
													if ($attore["nomeP"]!=null)
														echo ('		<p class="card-text">'.$attore["nomeP"].'</p>');
																		
													echo ('
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pers.* 
										FROM partecipazioni P JOIN video V ON V.id=P.idVideo JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.selettore=1 AND P.idVideo IN ( 
																			SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id) 
										GROUP BY P.idPersona"; /* Preparazione Query: Registi Serie */

										if ($registi=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Registi</h2>
														</div>
												');
											if ($registi->num_rows>0) {
												while ($regista = $registi->fetch_assoc()) { /* Costruisco un riquadro per ogni regista */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$regista["id"].',8,null)" >
																<div class="card h-100 mb-4 shadow-sm">
																	<div class="card-body">
																		<img src="images/persone/'.$regista["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$regista["nome"].' '.$regista["cognome"].'">
																		<p class="card-text">'.$regista["nome"].' '.$regista["cognome"].'</p>				
																	</div>
																</div>
															</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$registi->free(); // Dealloco l'oggetto
										}
																				
										$query="SELECT Pers.* 
										FROM partecipazioni P JOIN video V ON V.id=P.idVideo JOIN persone Pers ON Pers.id=P.idPersona 
										WHERE P.selettore=3 AND P.idVideo IN ( 
																			SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id) 
										GROUP BY P.idPersona"; /* Preparazione Query: Produttori Serie */

										if ($produttori=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttori</h2>
														</div>
												');
											if ($produttori->num_rows>0) {
												while ($produttore = $produttori->fetch_assoc()) { /* Costruisco un riquadro per ogni produttore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$produttore["id"].',8,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$produttore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$produttore["nome"].' '.$produttore["cognome"].'">
																	<p class="card-text">'.$produttore["nome"].' '.$produttore["cognome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$produttori->free(); // Dealloco l'oggetto
										}

										$query="SELECT Pggi.* 
										FROM comparizioni C JOIN personaggi Pggi ON Pggi.id=C.idPersonaggio 
										WHERE C.idVideo IN ( 
															SELECT V.id FROM video V JOIN saghe S ON V.idSaga=S.id WHERE S.id=$id) 
										GROUP BY C.idPersonaggio"; /* Preparazione Query: Personaggi Serie */

										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											echo ('
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Personaggi</h2>
													</div>
												');
											if ($personaggi->num_rows>0) {
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 mb-4 py2" onclick="passa_a('.$personaggio["id"].',9,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 mb-4">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$personaggi->free(); // Dealloco l'oggetto
										}
										
										break;

									case 8: /* 
										************************
										*** DETTAGLI PERSONE ***
										************************
										*/
										$id=$_GET["id"]; /* idPersona */
										$conn=dbConn(); // Connessione al DB
										$query="SELECT nome,cognome FROM persone WHERE id=$id;"; /* Preparazione Query: Dettagli Persona */
										$risultati=$conn->query($query);
										$persona = $risultati->fetch_assoc();
										$risultati->free(); // Dealloco l'oggetto

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$persona["nome"].' '.$persona["cognome"].'</h1>
													<img src="images/persone/'.$id.'.jpg" style="max-width: 50%; height: auto;" class="mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$persona["nome"].' '.$persona["cognome"].'">
												</div>
											');

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN partecipazioni Par ON Par.idVideo=V.id JOIN persone Per ON Par.idPersona=Per.id 
										WHERE Par.selettore=2 AND Per.id=$id"; /* Preparazione Query: Video da Attore */

										if ($video=$conn->query($query)) { /* Risultati della query */
											if ($video->num_rows>0) {
												echo ('	
													<div class="container text-center"> 
														<h2 class="mt-4 mb-4" >Attore in</h2>
													</div>
													');

												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null);" >
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
												}
											}
											
											$video->free(); // Dealloco l'oggetto
										}

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN partecipazioni Par ON Par.idVideo=V.id JOIN persone Per ON Par.idPersona=Per.id 
										WHERE Par.selettore=1 AND Per.id=$id"; /* Preparazione Query: Video da Regista */

										if ($video=$conn->query($query)) { /* Risultati della query */
											if($video->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Regista in</h2>
														</div>
													');
												
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null);" >
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
												}
											}																				
											$video->free(); // Dealloco l'oggetto
										}

										$query="SELECT V.nome,V.durata,V.sinossi,V.id
										FROM video V JOIN partecipazioni Par ON Par.idVideo=V.id JOIN persone Per ON Par.idPersona=Per.id 
										WHERE Par.selettore=3 AND Per.id=$id"; /* Preparazione Query: Video da Produttore */

										if ($video=$conn->query($query)) { /* Risultati della query */
											if($video->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Produttore in</h2>
														</div>
													');
												
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null);" >
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
												}
											}																				
											$video->free(); // Dealloco l'oggetto
										}
										//-------------------------------------------------
										$query="SELECT Pggi.* 
										FROM interpretazioni I JOIN persone Pers ON Pers.id=I.idAttore JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio 
										WHERE Pers.id=$id"; /* Preparazione Query: Personaggi interpretati */

										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											if($personaggi->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Interpreta</h2>
														</div>
													');
												
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$personaggio["id"].',9,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
														');
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
										$id=$_GET["id"]; /* idPersonaggio */
										$conn=dbConn(); // Connessione al DB
										$query="SELECT nome FROM personaggi WHERE id=$id"; /* Preparazione Query: Dettagli personaggio */
										$risultati=$conn->query($query);
										$personaggio = $risultati->fetch_assoc();
										$risultati->free(); // Dealloco l'oggetto

										echo ('
												<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
												<div class="container text-center">
													<h1 class="mt-4 mb-4">'.$personaggio["nome"].'</h1>
													<img src="images/personaggi/'.$id.'.jpg" style="max-width: 50%; class="img-fluid mt-4 mb-4" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto del personaggio '.$personaggio["nome"].'">
												</div>
											');
										
										$query="SELECT Pers.* 
										FROM interpretazioni I JOIN persone Pers ON Pers.id=I.idAttore JOIN personaggi Pggi ON Pggi.id=I.idPersonaggio
										WHERE Pggi.id=$id"; /* Preparazione Query: Attori che hanno interpretato il personaggio */

										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Attori che lo hanno interpretato</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$attori->free(); // Dealloco l'oggetto
										}

										$query="SELECT V.id, V.nome, V.durata, V.sinossi
										FROM comparizioni C JOIN video V ON V.id=c.idVideo
										WHERE C.idPersonaggio=$id"; /* Preparazione Query: Video in cui compare il personaggio */
										if ($video=$conn->query($query)) { /* Query effettuata con successo */
											echo ('	
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Video in cui e\' apparso</h2>
												</div>
												');
											if ($video->num_rows>0) { /* Almeno un risultato */
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,,null)" >
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
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
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
										');
										
										$query="SELECT id,nome,Sinossi,durata FROM video WHERE selettore=1 AND nome LIKE '%$ricerca%'"; 
										
										if ($risultati=$conn->query($query)) { /* Risultati della query */
											echo ('	
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Film</h2>
														</div>
												');
											if ($risultati->num_rows>0) {
												while ($elemento = $risultati->fetch_assoc()) { /* Costruisco un riquadro per ogni film */
													echo ('
															<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null);" >
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
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											
											$risultati->free(); // Dealloco l'oggetto
										}
										
										
										$query="SELECT S.id,S.nome,S.sinossi,COUNT(*)
										FROM serie S 
										JOIN video V on V.idSerie=S.id 
										WHERE S.nome LIKE '%$ricerca%' 
										GROUP BY S.id"; 
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Serie</h2>
											</div>
											');
										if ($serie=$conn->query($query)) { /* Query effettuata con successo */
											if ($serie->num_rows>0) { /* Almeno un risultato */
												while ($elemento = $serie->fetch_assoc()) { /* Costruisco un riquadro per ogni serie */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',6,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/serie/'.$elemento["id"].'.jpg" style="max-height=30%" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null; this.src=\'images/serie/default.jpg\';" alt="Locandina di '.$elemento["nome"].'">
																	<p class="card-text">'.$elemento["nome"].'</p>
																	<p class="card-text-description">'.$elemento["sinossi"].'</p>
														');
														$query="SELECT COUNT(S.id) nepisodi
														FROM serie S JOIN video V ON S.id=V.idSerie 
														WHERE V.idSerie=1"; /* Preparazione Query: Numero episodi della serie */
														if ($risultato=$conn->query($query)) { /* Query effettuata con successo */
															if ($risultato->num_rows==1) { /* Almeno un risultato */
																$nepisodi = $risultato->fetch_assoc();
													echo ('			
																	<div class="d-flex flex-row-reverse align-items-center">
																		<small class="text-muted">Episodi: '.$nepisodi["nepisodi"].'</small>
																	</div>
																</div>
															</div>
														</div>
														');
															}
														}
														$risultato->free(); // Dealloco l'oggetto
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}
											$serie->free();	// Dealloco l'oggetto
										}
										
										$query="SELECT S.id, S.nome, COUNT(*) nFilm
										FROM saghe S
										JOIN video V ON S.id=V.idSaga
										WHERE S.nome LIKE '%$ricerca%'
										GROUP BY S.id"; /* Preparazione Query: Tutte le saghe */
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Saghe</h2>
											</div>
											');
										if ($saghe=$conn->query($query)) { /* Query effettuata con successo */
											if ($saghe->num_rows>0) { /* Almeno un risultato */
												while ($saga = $saghe->fetch_assoc()) { /* Costruisco un riquadro per ogni saga TV */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$saga["id"].',6,null)">
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
													');
												}
											}
											else { /* Comunicazione mancanza di saghe */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessuna saga è ancora stata recensita</p>
																</div>
															</div>
														</div>
													');
											}
											$saghe->free(); // Dealloco l'oggetto
										}
										
										$query="SELECT id,nome,cognome FROM persone WHERE nome LIKE '%$ricerca%' OR cognome LIKE '%$ricerca%'"; 
										if ($attori=$conn->query($query)) { /* Risultati della query */
											echo ('
												<div class="container text-center"> 
													<h2 class="mt-4 mb-4" >Persone</h2>
												</div>
												');
											if ($attori->num_rows>0) {
												while ($attore = $attori->fetch_assoc()) { /* Costruisco un riquadro per ogni attore */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$attore["id"].',8,null);" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/persone/'.$attore["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/persone/default.jpg\';" alt="Foto di '.$attore["nome"].' '.$attore["cognome"].'">
																	<p class="card-text">'.$attore["nome"].' '.$attore["cognome"].'</p>
																</div>
															</div>
														</div>		
													');
												}
											}
											else { /* Comunicazione mancanza di elementi */
												echo ('
														<div class="col-md-3 ">
															<div class="card mb-4 shadow-sm">
																<div class="card-body">
																	<p class="card-text">Nessun risultato di ricerca trovato</p>
																</div>
															</div>
														</div>
													');
											}	
											$attori->free(); // Dealloco l'oggetto
										}
										
										
										
										$query="SELECT id,nome FROM personaggi WHERE nome LIKE '%$ricerca%'"; 
										
										if ($personaggi=$conn->query($query)) { /* Risultati della query */
											if($personaggi->num_rows>0) {
												echo ('
														<div class="container text-center"> 
															<h2 class="mt-4 mb-4" >Personaggi</h2>
														</div>
													');
												
												while ($personaggio = $personaggi->fetch_assoc()) { /* Costruisco un riquadro per ogni personaggio */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$personaggio["id"].',9,null)" >
															<div class="card h-100 mb-4 shadow-sm">
																<div class="card-body">
																	<img src="images/personaggi/'.$personaggio["id"].'.jpg" class="img-fluid bd-placeholder-img card-img-top" width="100%" height="100%"  focusable="false" role="img" aria-label="Placeholder: Thumbnail" onerror="this.onerror=null;this.src=\'images/personaggi/default.jpg\';" alt="Foto di '.$personaggio["nome"].'">
																	<p class="card-text">'.$personaggio["nome"].'</p>				
																</div>
															</div>
														</div>		
														');
												}
											}																				
											$personaggi->free(); // Dealloco l'oggetto
										}
										echo ('	
											<div class="container text-center"> 
												<h2 class="mt-4 mb-4" >Migliori Saghe</h2>
											</div>
											');
											
										echo ('
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
										');
										
										$conn->close(); // Chiudo la connessione al DB
										
										break;
									default:
										echo "<h1><strong>404. PAGE NOT FOUND</strong></h1>";
										break;
									
									case 11: /* 
										*************************
										*** DETTAGLI STAGIONI ***
										*************************
										*/
										$pagina=$_GET["pagina"]; /* Stagione da mostare */
										$id=$_GET["id"]; /* idSerie */
										$conn=dbConn(); // Connessione al DB
										$query="SELECT *
										FROM video V
										JOIN serie S ON V.idSerie=S.id
										WHERE S.id=$id"; /* Preparazione Query: Nome della serie */	
										$nStag=$conn->query("SELECT COUNT( DISTINCT stagione) nStag FROM video WHERE idSerie=$id")->fetch_assoc()["nStag"]; /* Numero di stagioni */

										echo ('
											<input type="button" class="btn btn-secondary dropdown-toggle" value="Indietro" onclick="history.back(-1)" />
										');
										
										if ($stagioni=$conn->query($query)) { /* Risultati della query */
											if ($stagioni->num_rows>0) {
												$elemento = $stagioni->fetch_assoc();  /* Costruisco un riquadro per ogni video */
												echo ('
														<div class="container text-center">
															<h1 class="mt-4 mb-4" >'.$elemento['nome'].'</h1>
														</div>
														<div class="container">'.
															$elemento['sinossi'].'
														</div>
													');												
											}
											$stagioni->free(); // Dealloco l'oggetto
										}

										echo '<div class="container"> <select id="sel" onchange="passa_a('.$id.',11,sel.value)">';
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
										ORDER BY V.numero";
										if ($video=$conn->query($query)) { /* Risultati della query */
											if ($video->num_rows>0) {
												while ($elemento = $video->fetch_assoc()) { /* Costruisco un riquadro per ogni video */
													echo ('
														<div class="col-md-3 py2" onclick="passa_a('.$elemento["id"].',5,null);" >
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
													');
												}
											}
												
											$video->free(); // Dealloco l'oggetto
										}
																
									break;
								}
		 
							?>
			</form>
		</main>
		<form name="recensioni" id="recensioni" method="post" action="
		<?php
			echo "index.php?stato=$_GET[stato]&id=$_GET[id]";
		?>
		">
			<input type='hidden' name='rate' id='rate'> <!-- Memorizzazione voto -->
			<input type='hidden' name='rec' id='rec'> <!-- Memorizzazione recensione -->
			<input type='hidden' name='idUtente' id='idUtente'> <!-- Memorizzazione recensione -->
				
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