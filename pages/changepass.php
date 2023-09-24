<?PHP
session_start();
$uname = $_SESSION['uname'];                   //added line
$pass = "";
$newpass = "";
$confirmnewpass = "";
$passErr = "";
$newpassErr = "";
$confErr = "";
$errMessage = "";

if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
	header ("Location: login1.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	require '../configure1.php';
	
	if (empty($_POST["password"])) {
    $passErr = "Password is required!"."<BR>";
  } else {
    $pass = test_input($_POST["password"]);
	$pass = md5($pass);
  }
  
if (empty($_POST["newpassword"])) {
    $newpassErr = "New password is required!"."<BR>";
  } else {
    $newpass = test_input($_POST["newpassword"]);
	$newpass = md5($newpass);
  }
  
if (empty($_POST["confirmnewpassword"])) {
    $confErr = "Confirm password is required!"."<BR>";
  } else {
    $confirmnewpass = test_input($_POST["confirmnewpassword"]);
	$confirmnewpass = md5($confirmnewpass);
  }
 
 $db_handle = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
 $database = "design1";
 $db_found = mysqli_select_db($db_handle, $database);
 
if (db_found) 
{
	if ((!(empty($_POST["password"]))) && (!(empty($_POST["newpassword"]))) && (!(empty($_POST["confirmnewpassword"]))))
	{
		$SQL = "SELECT * FROM parentdetails WHERE P_Pass = '$pass' AND PEadd = '$uname'";
		$result = mysqli_query($db_handle, $SQL);
		$cnt = mysqli_num_rows($result);
		
		if ($cnt == 1)
		{
			if (($_POST["newpassword"]) == ($_POST["confirmnewpassword"]))
			{
				$SQLL = "UPDATE parentdetails SET P_Pass = '$newpass' WHERE PEadd = '$uname'";
				mysqli_query($db_handle, $SQLL);
				
				header("Location: page1.php");
			}
			else
			{
				$errMessage = "New password doesn't match!"."<BR>";
			}
		}
		else 
		{
			$errMessage = "Incorrect Password!"."<BR>";
		}
	}
	else
	{
		$errMessage = "Complete all fields!"."<BR>";
	}
}
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>


<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
     <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Password Change</title>
	<link rel="Stylesheet" TYPE="text/css" HREF="asd.css">
     </head>
    <body>
    <h1>Change Password</h1>
   <form method="POST" action="changepass.php">
    <p>
    Enter your existing password:
    <input type="password" size="50" name="password">
	</p>
	<p>
    Enter your new password:
    <input type="password" size="50" name="newpassword">
	</p>
	<p>
   Re-enter your new password:
   <input type="password" size="50" name="confirmnewpassword">
    </p>
    <p><input type="submit" value="Update Password">
    </form>
   
   
 <span class="error">  <?PHP 
	print $errMessage;
	print $passErr;
	print $newpassErr;
	print $confErr;
	?>	</span>
	<p><a href="page1.php">Cancel</a>
   </body>
</html>  