<?php
	verifyUser("Administrator");

	include(GLOBAL_INCLUDES."/xhtmlHeader.inc");
	include(APPLICATION_HOME."/includes/banner.inc");
	include(APPLICATION_HOME."/includes/menubar.inc");
	include(APPLICATION_HOME."/includes/sidebar.inc");
?>
<div id="mainContent">
	<?php include(GLOBAL_INCLUDES."/errorMessages.inc"); ?>

	<h1>New User</h1>
	<form method="post" action="addUser.php">
	<fieldset><legend>Login Info</legend>
		<table>
		<tr><td><label for="authenticationMethod">Authentication</label></td>
			<td><select name="authenticationMethod" id="authenticationMethod">
					<option>LDAP</option>
					<option>local</option>
				</select>
			</td>
		</tr>
		<tr><td><label for="username">Username</label></td>
			<td><input name="username" id="username" /></td></tr>
		<tr><td><label for="password">Password</label></td>
			<td><input name="password" id="password" /></td></tr>
		<tr><td><label for="roles">Roles</label></td>
			<td><select name="roles[]" id="roles" size="5" multiple="multiple">
				<?php
					$roles = new RoleList();
					$roles->find();
					foreach($roles as $role) { echo "<option>$role</option>"; }
				?>
				</select>
			</td>
		</tr>
		</table>

	</fieldset>
	<fieldset><legend>Personal Info</legend>
		<p>This information is only necessary for local users.  For LDAP authentication, this information will be pulled from LDAP.</p>
		<table>
		<tr><td><label for="firstname">Firstname</label></td>
			<td><input name="firstname" id="firstname" /></td></tr>
		<tr><td><label for="lastname">Lastname</label></td>
			<td><input name="lastname" id="lastname" /></td></tr>
		<tr><td><label for="department">Department</label></td>
			<td><input name="department" id="department" /></td></tr>
		<tr><td><label for="phone">Phone</label></td>
			<td><input name="phone" id="phone" /></td></tr>
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