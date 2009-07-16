<form method="post" action="<?php echo BASE_URL; ?>/login/login.php">
	<fieldset><legend>Login</legend>
		<input name="return_url" type="hidden" value="<?php echo View::escape($_SERVER['REQUEST_URI']); ?>" />
		<div>
			<label>Username: <input name="username" /></label>
		</div>
		<div>
			<label>Password: <input type="password" name="password" /></label>
		</div>
		<button type="submit" class="login">Login</button>
	</fieldset>
</form>
