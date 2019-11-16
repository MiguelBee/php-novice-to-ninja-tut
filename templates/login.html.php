<?php

if(isset($errors)):
	echo '<div class="errors">' . $errors . '</div>';
endif;

?>

<form method="post" action="/login">
	<label for="email">Your Email Address</label>
	<input type="text" id="email" name="email">

	<label for="password">Your password</label>
	<input type="text" id="password" name="password">

	<input type="submit" name="login" value="Log In">
</form>

<p>Don't have an account?<br>
	<a href="/author/register">Click here to register an account</a>
</p>