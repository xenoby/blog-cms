
<?php
	
ini_set( "display_errors", true );

date_default_timezone_set( "Europe/Samara" );  // http://www.php.net/manual/en/timezones.php
define( "DB_DSN", "mysql:host=localhost;dbname=cms; charset=UTF8" );
define( "DB_USERNAME", "root" );
define( "DB_PASSWORD", "Saraksh1996MakCallback" );
define( "CLASS_PATH", "classes" );
define( "UPLOAD_IMG_PATH", "upload/images" );
define( "LANGS_PATH", "lang" );
define( "TEMPLATE_PATH", "templates" );
define( "HOMEPAGE_NUM_ARTICLES", 5 );
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "mypass" );
require( CLASS_PATH . "/Article.php" );
require( CLASS_PATH . "/Category.php" );
require( CLASS_PATH . "/Contacts.php" );
require( CLASS_PATH . "/Gallery.php" );
require( CLASS_PATH . "/Upload.php" );

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
$acceptLang = ['ru', 'en']; 
$lang = in_array($lang, $acceptLang) ? $lang : 'ru';

require( LANGS_PATH . "/lang_{$lang}.php");

  

function handleException( $exception ) {
  echo "Sorry, a problem occurred. Please try later.";
  error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' );
?>
