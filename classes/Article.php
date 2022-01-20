<?php
 
/**
 * Класс для управления статьями
 */
 
class Article
{
  // Свойства
 
  /**
  * @var int ID статьи из базы данных
  */
  public $id = null;
 
  /**
  * @var int Дата публикации статьи
  */
  public $publicationDate = null;
 
  /**
  * @var int ID категории статьи
  */
  public $categoryId = null;
 
  /**
  * @var string Полное название статьи
  */
  public $title = null;
 
  /**
  * @var string Резюме статьи
  */
  public $summary = null;
 
  /**
  * @var string Содержание HTML статьи
  */
  public $content = null;
 
 
  /**
  * Устанавливаем свойства объекта с использованием значений из массива
  *
  * @param assoc Значения свойств
  */
 
  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['publicationDate'] ) ) $this->publicationDate = (int) $data['publicationDate'];
    if ( isset( $data['categoryId'] ) ) $this->categoryId = (int) $data['categoryId'];
    if ( isset( $data['title'] ) ) $this->title = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яА-Я0-9()]/iu", "", $data['title'] );
    if ( isset( $data['summary'] ) ) $this->summary = preg_replace ( "/[^\.\,\-\_\'\"\@\?\!\:\$ a-zA-Zа-яА-Я0-9()]/iu", "", $data['summary'] );
    if ( isset( $data['content'] ) ) $this->content = $data['content'];
  }
 
 
  /**
  * Устанавливаем свойства объекта с использованием значений из формы
  *
  * @param assoc Значения из формы
  */
 
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
 
 
  /**
  * Возвращаем объект Article соответствующий заданному ID
  *
  * @param int ID статьи
  * @return Article|false Объект Article или false, если запись не найдена или в случае другой ошибки
  */
 
  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Article( $row );
  }
 
 
  /**
  * Возвращает все (или диапазон) объекты Article из базы данных
  *
  * @param int Optional Количество возвращаемых строк (по умолчанию = all)
  * @param int Optional Вернуть статьи только из категории с указанным ID
  * @param string Optional Столбц, по которому выполняется сортировка статей (по умолчанию = "publicationDate DESC")
  * @return Array|false Двух элементный массив: results => массив объектов Article; totalRows => общее количество строк
  */
 
  public static function getList( $numRows=1000000, $categoryId=null, $order="publicationDate DESC" ) {
	try {
		
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
	
	} catch (PDOException $e) 
	{	
		echo "Error: Could not connect. " . $e->getMessage();
	}
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	try {
		$categoryClause = $categoryId ? "WHERE categoryId = :categoryId" : "";
		
		$sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) AS publicationDate
            FROM articles $categoryClause
            ORDER BY $order LIMIT :numRows";
			
			$stmt = $conn->prepare( $sql );
			$stmt->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
			
			if ( $categoryId )
				$stmt->bindValue( ":categoryId", $categoryId, PDO::PARAM_INT );
			$stmt->execute();
			
		$list = array();
 
		while ( $row = $stmt->fetch() ) {
		$article = new Article( $row );
		$list[] = $article;
		}
	
		// Получаем общее количество статей, которые соответствуют критерию
		$sql = "SELECT FOUND_ROWS() AS totalRows";
		$totalRows = $conn->query( $sql )->fetch();
		$conn = null;
		return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
	} catch (Exception $e)
	{
		die ('ERROR: ' . $e->getMessage());
	}

  }
 
 
  /**
  * Вставляем текущий объек Article в базу данных, устанавливаем его ID.
  */
 
  public function insert() {
 
    // Есть уже у объекта Article ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );
 
    // Вставляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO articles ( publicationDate, categoryId, title, summary, content ) VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId, :title, :summary, :content )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
    $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }
 
 
  /**
  * Обновляем текущий объект Article в базе данных.
  */
 
  public function update() {
 
    // У объекта Article есть ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );
    
    // Обновляем статью
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate), categoryId=:categoryId, title=:title, summary=:summary, content=:content WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
    $st->bindValue( ":categoryId", $this->categoryId, PDO::PARAM_INT );
    $st->bindValue( ":title", $this->title, PDO::PARAM_STR );
    $st->bindValue( ":summary", $this->summary, PDO::PARAM_STR );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
 
  /**
  * Удаляем текущий объект Article из базы данных
  */
 
  public function delete() {
 
    // У объекта Article есть ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );
 
    // Удаляем объект Article
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM articles WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }
 
}
 
?>