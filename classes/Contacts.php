<?php

/**
 * Класс для обработки статей
 */

class Contacts
{
  // Свойства

  /**
  * @var int ID статей из базы данных
  */
  public $id = null;
  public $publicationDate = null;
  public $content = null;


  /**
  * Устанавливаем свойства с помощью значений в заданном массиве
  *
  * @param assoc Значения свойств
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['content'] ) ) $this->content = $data['content'];
  }


  /**
  * Устанавливаем свойств с помощью значений формы редактирования записи в заданном массиве
  *
  * @param assoc Значения записи формы
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

 public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM contacts WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Contacts( $row );
  }


  /**
  * Возвращает все (или диапазон) объектов статей в базе данных
  *
  * @param int Optional Количество строк (по умолчанию все)
  * @param string Optional Столбец по которому производится сортировка  статей (по умолчанию "publicationDate DESC")
  * @return Array|false Двух элементный массив: results => массив, список объектов статей; totalRows => общее количество статей
  */

  public static function getList( $numRows=1000000, $order="publicationDate DESC" ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM contacts
            ORDER BY $order LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $contact = new Contacts( $row );
      $list[] = $contact;
    }

    // Получаем общее количество статей, которые соответствуют критерию
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Вставляем текущий объект в базу данных, устанавливаем его свойства.
  */

  public function insert() {

    // Есть у объекта ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Contacts::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Вставляем
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO contacts ( publicationDate, content ) VALUES ( FROM_UNIXTIME(:publicationDate), :content )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Обновляем текущий объект в базе данных
  */

  public function update() {

    // Есть ли у объекта ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR );
   
    // Обновляем 
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE contacts SET publicationDate=FROM_UNIXTIME(:publicationDate), content=:content WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":publicationDate", $this->publicationDate, PDO::PARAM_INT );
    $st->bindValue( ":content", $this->content, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Удаляем текущий объект из базы данных
  */

  public function delete() {

    // Есть ли у объекта ID?
    if ( is_null( $this->id ) ) trigger_error ( "Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR );

    // Удаляем 
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM contacts WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }

}

?>