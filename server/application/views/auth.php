<?php


/**
 * HTML Template for registration and logging in
 */

?>

<html>

<head>

	<title>Log in or Sign Up!</title>
</head>

<body>

<div id="mainDiv">

If you have an account...

<?php ui_render(); ?>

<form action="<?php echo site_url("auth/login"); ?>" method="post">
Log In

<div class="inputField">
<input type="text" name="username" placeholder="Username" />
</div>

<div class="inputField">
<input type="password" name="password" placeholder="Password" />
</div>

<input type="submit" value="Log In!" name="login" />


</form>

Or

<form action="<?php echo site_url("auth/register"); ?>" method="post">

<div class="inputField">
<input type="text" name="firstname" placeholder="First Name" />
</div>

<div class="inputField">
<input type="text" name="lastname" placeholder="Surname" />
</div>

<div class="inputField">
<input type="text" name="username" placeholder="Username" />
</div>

<div class="inputField">
<input type="password" name="password" placeholder="Password" />
</div>

<input type="submit" value="Register!" />

</form>

</div>

</body>

</html>