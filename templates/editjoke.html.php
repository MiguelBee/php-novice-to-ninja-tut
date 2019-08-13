<form action='editjoke.php' method="post">
	<input type="hidden" name="joke[id]" value="<?= $joke['id'] ?? '' ?>">
	<label for='joketext'>Type your joke here:</label>
	<textarea name='joke[joketext]' id='joketext' rows='3' cols='40'>
	<?=
	#Null coalescing operator, like a ternary
	 $joke['joketext'] ?? '' 
	?>

	</textarea>
	<input type='submit' value='Save'>
</form>