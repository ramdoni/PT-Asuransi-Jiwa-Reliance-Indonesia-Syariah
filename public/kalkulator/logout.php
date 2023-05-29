<?php
	session_start();
	
	//Hapus semua session yang ada
	session_destroy();
	header("location: index.php");
?>