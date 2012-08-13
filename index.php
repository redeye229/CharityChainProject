<!DOCTYPE HTML>

<!--
	This is the basic structure of the document, written by Ben Saletta
	all design, functionalty, and content will be added via external documents
	
	
	
-->	
<head>
	<meta charset="UTF-8" /> 
	<title>Charity Chain</title>
	<link rel="stylesheet" type="text/css" href="style/style.css">
	<?php include 'script/functions.php'; ?>
</head>	
<body onload="userCheck()">
		<div id="loginForm">
		<table>
		<tr>
		<td>Username:</td><td><input type="text" name="uname" class="loginData" ondeactivate="" /></td>
		</tr>
		<tr>
		<td>Password:</td><td><input type="password" class="loginData" name="paswd"/></td>
		</tr>
		<tr class="signup" id="signupPWD">
		<td>Confirm Password:</td><td><input type="password" name="conf paswd"></td>
		</tr>
		<tr class="signup" id="signupEmail">
		<td>Email:</td><td><input type="email" class="signup" name="email"/></td>
		</tr>
		</table>
			<input type="button" value="Signup" class="signup" id="signupButton" />
			<span id="loginButtons"><input type="submit" value="login" onclick="userLogin();"/> or <a id="signupButton" onclick="showSignup()">Signup</a></span>
		</div>
		<div id="title">
		 <h1 id="Heading">Charity Chain</h1>
		 <h3 id="Subheading">Social Networking giving back to society</h3>
		 </div>
	<div id="main">
		<?php echo contentGen("content.xml", "main_discription"); ?>
		 <input type="button" value="JS test" onclick="userCheck()" />
		 </div>
		 <span id="testspace"><?php //userLogin("root","1234"); ?></span>
	<div id="nav">
		Hey this is the NAV and it loaded!!!
		 </div>
	<script type="text/javascript" src="script/script.js"></script> <!-- JavaScript loaded at the bottom for more efficent page loading -->
	<span class="dim" id="dimmer"></span>
</body>