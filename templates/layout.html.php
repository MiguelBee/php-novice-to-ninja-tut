<!DOCTYPE html>
<html>
<head>
	<meta charset='utf-8'>
	<link rel="stylesheet" href="/jokes.css">
	<title> <?= $title; ?></title>
</head>
<body>
	<nav>
		<header>
			<h1>Internet Jokes Database</h1>
		</header>
		<ul>
			<li><a href="/">Home</a></li>
			<li><a href="/joke/list">Jokes List</a></li>
			<li><a href="/joke/edit">Add a Joke</a></li>
			<li><a href="/author/register"> Register</a></li>
		</ul>
	</nav>

	<main>
		<?php if (isset($error)): ?>
			<p>
				<?= $error; ?>
			</p>
		<?php else: ?>
			<?= $output; ?>
		<?php endif; ?>
	</main>
	
	<footer> &copy; MAB <?= date('Y'); ?></footer>
</body>
</html>