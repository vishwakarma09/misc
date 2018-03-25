<?php
require_once __DIR__.'/vendor/autoload.php';

session_start();

// unset($_SESSION['access_token']);

$client = new Google_Client();
$client->setAuthConfig('client_secrets.json');
$client->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
// $client->addScope(Google_Service_Gmail::MAIL_GOOGLE_COM);
// $client->addScope(Google_Service_Gmail::GMAIL_COMPOSE);
// $client->addScope(Google_Service_Gmail::GMAIL_INSERT);
// $client->addScope(Google_Service_Gmail::GMAIL_LABELS);
// $client->addScope(Google_Service_Gmail::GMAIL_METADATA);
// $client->addScope(Google_Service_Gmail::GMAIL_MODIFY);
// $client->addScope(Google_Service_Gmail::GMAIL_SEND);
// $client->addScope(Google_Service_Gmail::GMAIL_SETTINGS_BASIC);
// $client->addScope(Google_Service_Gmail::GMAIL_SETTINGS_SHARING);

if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {

	/**
	 * Google drive metadata client
	 */

	$client->setAccessToken($_SESSION['access_token']);
	$drive = new Google_Service_Drive($client);
	$files = $drive->files->listFiles(array())->getFiles();
	echo json_encode($files);
	die();

	/**
	 * Gmail client
	 */
	$client->setAccessToken($_SESSION['access_token']);
	$service = new Google_Service_Gmail($client);

	gmail_send_email($service);
	die();

	// Print the labels in the user's account.
	gmail_read_labels($service);
	die();
	

} else {
	$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . '/gmail_oauth/oauth2callback.php';
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}



function gmail_send_email($service)
{
	$user = 'me';
	$strSubject = 'Test mail using GMail API' . date('M d, Y h:i:s A');
	$strRawMessage = "From: Sandeep Kumar<kkumar.sandeep89@gmail.com>\r\n";
	$strRawMessage .= "To: Parminder Kaur <parminderkaur4434@gmail.com>\r\n";
	$strRawMessage .= 'Subject: =?utf-8?B?' . base64_encode($strSubject) . "?=\r\n";
	$strRawMessage .= "MIME-Version: 1.0\r\n";
	$strRawMessage .= "Content-Type: text/html; charset=utf-8\r\n";
	$strRawMessage .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
	$strRawMessage .= "this <b>is a test message!\r\n";
	// The message needs to be encoded in Base64URL
	$mime = rtrim(strtr(base64_encode($strRawMessage), '+/', '-_'), '=');
	$msg = new Google_Service_Gmail_Message();
	$msg->setRaw($mime);
	//The special value **me** can be used to indicate the authenticated user.
	$service->users_messages->send("me", $msg);
	die('SO FAR SO FOOD');
}

function gmail_read_labels($service)
{
	$user = 'me';
	$results = $service->users_labels->listUsersLabels($user);

	if (count($results->getLabels()) == 0) {
		print "No labels found.\n";
	} else {
		print "Labels:\n";
		foreach ($results->getLabels() as $label) {
			printf("- %s\n", $label->getName());
		}
	}
}