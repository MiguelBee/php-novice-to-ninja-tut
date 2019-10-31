<?php
	if(!empty($errors)):
	?>
	<div class="errors">
		<p>Your account could not be created, please check the following:</p>
		<ul>
			<?php
				foreach($errors as $error):
			?>
			<li><?= $error ?></li>
			<?php endforeach; ?>	
		</ul>
	</div>
<?php endif;?>
<br>
<br>
<form action='/author/register' method='post'>
	<label for='email'> Your Email Address</label>
	<input name='author[email]' id='email' type='text' value="<?php $author['email'] ?? ''?>" required>

	<label for='name'>Your Name</label>
	<input name='author[name]' id='name' type='text' value="<?php $author['name'] ?? ''?>" required>

	<label for='password'>Password</label>
	<input name='author[password]' id='password' type='password' value="<?php $author['password'] ?? ''?>" required>

	<input type='submit' name='submit' value="Register Account">
</form>
