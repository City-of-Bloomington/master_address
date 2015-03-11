<?php
/**
 *	Logs a user into the system using CAS
 *
 * @copyright 2009-2015 City of Bloomington, Indiana
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
if (isset($_REQUEST['return_url'])) {
	$_SESSION['return_url'] = $_REQUEST['return_url'];
}

require_once CAS.'/CAS.php';
\phpCAS::client(CAS_VERSION_2_0, CAS_SERVER, 443, CAS_URI, false);
\phpCAS::setNoCasServerValidation();
\phpCAS::forceAuthentication();
// at this step, the user has been authenticated by the CAS server
// and the user's login name can be read with phpCAS::getUser().
$username = \phpCAS::getUser();

// They may be authenticated according to CAS,
// but that doesn't mean they have person record
// and even if they have a person record, they may not
// have a user account for that person record.
try {
    $_SESSION['USER'] = new User($username);

    if (isset($_SESSION['return_url'])) {
        header('Location: '.$_SESSION['return_url']);
    }
    else {
        header('Location: '.BASE_URL);
    }
}
catch (Exception $e) {
    $_SESSION['errorMessages'][] = $e;
}

header('Location: '.BASE_URL);
