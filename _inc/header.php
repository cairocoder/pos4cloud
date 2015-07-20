<?php

	require_once("_inc/sessionClass.php");
	require_once("_inc/dbClass.php");
	require_once("_inc/userClass.php");

	if(!$session->is_logged_in()) {
	  header("location: index.php");
	}

	if($_SESSION['user_id'] == 0 || $_SESSION['loc_id'] == 0)
	{
		header("location: login.php?action=logout");
	}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>RAViN POS - SYSTEM</title>
	<link href="_css/colorbox.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="_css/content.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="_css/component.css" rel="stylesheet" type="text/css" media="screen" />
	<link href="_css/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
</head>
<body>
	<div id="wrapper">
		<a id="logout" href="login.php?action=logout"><span>Logout</span><img src="_img/logout.png"></a>
		<a id="back" href="index.php"><img src="_img/back.png"><span>Back</span></a>
		<div id="loadingWrapper"><img src="_img/ajax-loader.gif" id="loading"></div>