<?php
/*
	$_GET variables:	userID
*/
	verifyUser("Administrator","Supervisor");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php
		include(GLOBAL_INCLUDES."/errorMessages.inc");

		$user = new User($_GET['userID']);
	?>
	<h1>Edit <?php echo $user->getUsername(); ?></h1>
	<form method="post" action="updateUser.php">
	<fieldset><legend>Login Info</legend>
		<input name="userID" type="hidden" value="<?php echo $user->getUserID(); ?>" />
		<table>
		<tr><td><label for="authenticationMethod">Authentication</label></td>
			<td><select name="authenticationMethod" id="authenticationMethod">
					<option <?php if ($user->getAuthenticationMethod()=='LDAP') echo "selected=\"selected\""; ?>>LDAP</option>
					<option <?php if ($user->getAuthenticationMethod()=='local') echo "selected=\"selected\""; ?>>local</option>
				</select>
			</td>
		</tr>
		<tr><td><label for="username">Username</label></td>
			<td><input name="username" id="username" value="<?php echo $user->getUsername(); ?>" /></td></tr>
		<tr><td><label for="password">Password</label></td>
			<td><input name="password" id="password" /></td></tr>
		<tr><td><label for="roles">Roles</label></td>
			<td><select name="roles[]" id="roles" size="5" multiple="multiple">
				<?php
					$roles = new RoleList();
					$roles->find();
					foreach($roles as $role)
					{
						if (in_array($role,$user->getRoles())) { echo "<option selected=\"selected\">$role</option>"; }
						else { echo "<option>$role</option>"; }
					}
				?>
				</select>
			</td>
		</tr>
		</table>

	</fieldset>
	<fieldset><legend>Personal Info</legend>
		<table>
		<tr><td><label for="firstname">Firstname</label></td>
			<td><input name="firstname" id="firstname" value="<?php echo $user->getFirstname(); ?>" /></td></tr>
		<tr><td><label for="lastname">Lastname</label></td>
			<td><input name="lastname" id="lastname" value="<?php echo $user->getLastname(); ?>" /></td></tr>
		<tr><td><label for="department">Department</label></td>
			<td><input name="department" id="department" value="<?php echo $user->getDepartment(); ?>" /></td></tr>
		<tr><td><label for="phone">Phone</label></td>
			<td><input name="phone" id="phone" value="<?php echo $user->getPhone(); ?>" /></td></tr>
		</table>

		<button type="submit" class="submit">Submit</button>
		<button type="button" class="cancel" onclick="document.location.href='home.php';">Cancel</button>
	</fieldset>
	</form>
</div>

<?php
	include(APPLICATION_HOME."/includes/footer.inc");
	include(GLOBAL_INCLUDES."/xhtmlFooter.inc");
?>