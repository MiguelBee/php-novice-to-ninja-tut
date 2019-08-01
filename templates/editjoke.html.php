<form action='editjoke.php' method="post">
	<input type="hidden" name="jokeid" value="<?= $joke['id']; ?>">
	<label for='joketext'>Type your joke here:</label>
	<textarea name='joketext' id='joketext' rows='3' cols='40'><?= $joke['joketext']; ?></textarea>
	<input type='submit' value='Update'>
</form>