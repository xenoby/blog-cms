<?php
 
/**
 * Класс для обработки загрузок
 */
 
class Upload
{
  /**
  * @var int ID категории из базы данных
  */
  public $id = null;
  public $publicationDate = null;
  public $image_title = null;
  public $image_fname = null;
  public $dir_name = null;
  
   public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['publicationDate'] ) ) $this->publicationDate = (int) $data['publicationDate'];
    if ( isset( $data['image_title'] ) ) $this->image_title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['image_title'] );
    if ( isset( $data['image_fname'] ) ) $this->image_fname = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['image_fname'] );
	if ( isset( $data['dir_name'] ) )    $this->dir_name    = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Z0-9()]/", "", $data['dir_name'] );
  }
  
  public function storeFormValues ( $params ) {
 
    // Сохраняем все параметры
    $this->__construct( $params );
 
    // Разбираем и сохраняем дату публикации
    if ( isset($params['publicationDate']) ) {
      $publicationDate = explode ( '-', $params['publicationDate'] );
 
      if ( count($publicationDate) == 3 ) {
        list ( $y, $m, $d ) = $publicationDate;
        $this->publicationDate = mktime ( 0, 0, 0, $m, $d, $y );
      }
    }
  }

  public static function can_upload($file){
	// если имя пустое, значит файл не выбран
    if($file['name'] == '')
		return LANG_NOT_SELECT_FILE;
	
	/* если размер файла 0, значит его не пропустили настройки 
	сервера из-за того, что он слишком большой */
	if($file['size'] == 0)
		return LANG_BIG_FILE;
	
	// разбиваем имя файла по точке и получаем массив
	$getMime = explode('.', $file['name']);
	// нас интересует последний элемент массива - расширение
	$mime = strtolower(end($getMime));
	// объявим массив допустимых расширений
	$types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
	
	// если расширение не входит в список допустимых - return
	if(!in_array($mime, $types))
		return LANG_ERR_FILE_FORMAT;
	
	return true;
  }
  
  public static function getRandomFileName($path, $extension='') {
        $extension = $extension ? '.' . $extension : '';
        $path = $path ? $path . '/' : '';
 
        do {
            $name = md5(microtime() . rand(0, 9999));
            $file = $path . $name . $extension;
        } while (file_exists($file));
 
        return $name;
  }
  
   /**
  * Вставляем текущий объект Category в базу данных и устанавливаем его свойство ID.
  */
 
  public function insert() {
 
    // У объекта Category уже есть ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Upload::insert(): Attempt to insert a Upload object that already has its ID property set (to $this->id).", E_USER_ERROR );
 
    // Вставляем категорию
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO image_files ( publicationDate, image_title, image_fname, dir_name ) VALUES ( FROM_UNIXTIME(:publicationDate), :image_title, :image_fname, :dir_name )";
	        
	
	$st = $conn->prepare ( $sql );
	$st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
    $st->bindValue( ":image_title", $this->image_title, PDO::PARAM_STR );
    $st->bindValue( ":image_fname", $this->image_fname, PDO::PARAM_STR );
	$st->bindValue( ":dir_name", $this->dir_name, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }
	
}

?>