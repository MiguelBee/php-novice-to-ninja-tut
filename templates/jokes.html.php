<p><?= $totalJokes ?> jokes have been submitted to the site</p>

<?php foreach($jokes as $joke): ?>
	<blockquote>
		<p>
		<?= htmlspecialchars($joke['joketext'], ENT_QUOTES, 'UTF-8'); ?>
		<br>
		(by <a href="mailto:<?php echo htmlspecialchars($joke['email'], ENT_QUOTES, 'UTF-8'); ?>">
				<?php echo htmlspecialchars($joke['name'], ENT_QUOTES, 'UTF-8'); ?>
				</a> on 
				<?php
					$date = new DateTime($joke['jokedate']);
					echo $date->format('jS F Y');
				?>)
		<pre>
		</pre> 
		<a href="/joke/edit&id=<?=$joke['id']?>">Edit</a>
		<form action='/joke/delete' method="post" onclick="areYouSure()">
			<input type="hidden" name='id' value="<?= $joke['id']?>">
			<input type="submit" value="Delete">
		</form>
		</p>
	</blockquote>
<?php endforeach; ?>

<script>
	function areYouSure(){
		confirm('Are You Sure?');
	}
</script>
