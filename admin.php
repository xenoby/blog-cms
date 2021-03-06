<?php
 
require( "config.php" );
session_start();
$action = isset( $_GET['action'] ) ? $_GET['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";
 
if ( $action != "login" && $action != "logout" && !$username ) {
  login();
  exit;
}
 
switch ( $action ) {
  case 'login':
    login();
    break;
  case 'logout':
    logout();
    break;
  case 'newArticle':
    newArticle();
    break;
  case 'editArticle':
    editArticle();
    break;
  case 'deleteArticle':
    deleteArticle();
    break;
  case 'listCategories':
    listCategories();
    break;
  case 'newCategory':
    newCategory();
    break;
  case 'editCategory':
    editCategory();
    break;
  case 'deleteCategory':
    deleteCategory();
    break;
  case 'listContacts':
    listContacts();
	break;
  case 'newContact':
    newContact();
	break;
  case 'editContact':
    editContact();
    break;
  case 'deleteContact':
    deleteContact();
    break;
  case 'uploadNewImage':
    uploadNewImage();
	break;
  default:
    listArticles();
}
 
 
function login() {
 
  $results = array();
  $results['pageTitle'] = "Admin Login | Widget News";
 
  if ( isset( $_POST['login'] ) ) {
 
    // User has posted the login form: attempt to log the user in
 
    if ( $_POST['username'] == ADMIN_USERNAME && $_POST['password'] == ADMIN_PASSWORD ) {
 
      // Login successful: Create a session and redirect to the admin homepage
      $_SESSION['username'] = ADMIN_USERNAME;
      header( "Location: admin.php" );
 
    } else {
 
      // Login failed: display an error message to the user
      $results['errorMessage'] = "Incorrect username or password. Please try again.";
      require( TEMPLATE_PATH . "/admin/loginForm.php" );
    }
 
  } else {
 
    // User has not posted the login form yet: display the form
    require( TEMPLATE_PATH . "/admin/loginForm.php" );
  }
 
}
 
 
function logout() {
  unset( $_SESSION['username'] );
  header( "Location: admin.php" );
}
 
 
function newArticle() {
 
  $results = array();
  $results['pageTitle'] = LANG_NEW_ARTICLE;
  $results['formAction'] = "newArticle";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the article edit form: save the new article
    $article = new Article;
    $article->storeFormValues( $_POST );
    $article->insert();
    header( "Location: admin.php?status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the article list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the article edit form yet: display the form
    $results['article'] = new Article;
    $data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }
 
}
 
 
function editArticle() {
 
  $results = array();
  $results['pageTitle'] = LANG_EDIT_ARTICLE;
  $results['formAction'] = "editArticle";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the article edit form: save the article changes
 
    if ( !$article = Article::getById( (int)$_POST['articleId'] ) ) {
      header( "Location: admin.php?error=articleNotFound" );
      return;
    }
 
    $article->storeFormValues( $_POST );
    $article->update();
    header( "Location: admin.php?status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the article list
    header( "Location: admin.php" );
  } else {
 
    // User has not posted the article edit form yet: display the form
    $results['article'] = Article::getById( (int)$_GET['articleId'] );
    $data = Category::getList();
    $results['categories'] = $data['results'];
    require( TEMPLATE_PATH . "/admin/editArticle.php" );
  }
 
}
 
 
function deleteArticle() {
 
  if ( !$article = Article::getById( (int)$_GET['articleId'] ) ) {
    header( "Location: admin.php?error=articleNotFound" );
    return;
  }
 
  $article->delete();
  header( "Location: admin.php?status=articleDeleted" );
}
 
 
function listArticles() {
  $results = array();
  $data = Article::getList();
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $data = Category::getList();
  $results['categories'] = array();
  foreach ( $data['results'] as $category ) $results['categories'][$category->id] = $category;
  $results['pageTitle'] = LANG_ALL_ARTICLES;
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "articleNotFound" ) $results['errorMessage'] = "Error: Article not found.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "articleDeleted" ) $results['statusMessage'] = "Article deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listArticles.php" );
}
 
 
function listCategories() {
  $results = array();
  $data = Category::getList();
  $results['categories'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageTitle'] = LANG_ARTICLE_CATEGORIES;
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
    if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listCategories.php" );
}
 
 
function newCategory() {
 
  $results = array();
  $results['pageTitle'] = LANG_NEW_CATEGORY;
  $results['formAction'] = "newCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the category edit form: save the new category
    $category = new Category;
    $category->storeFormValues( $_POST );
    $category->insert();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {
 
    // User has not posted the category edit form yet: display the form
    $results['category'] = new Category;
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }
 
}
 
 
function editCategory() {
 
  $results = array();
  $results['pageTitle'] = LANG_EDIT_CATEGORY;
  $results['formAction'] = "editCategory";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the category edit form: save the category changes
 
    if ( !$category = Category::getById( (int)$_POST['categoryId'] ) ) {
      header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
      return;
    }
 
    $category->storeFormValues( $_POST );
    $category->update();
    header( "Location: admin.php?action=listCategories&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listCategories" );
  } else {
 
    // User has not posted the category edit form yet: display the form
    $results['category'] = Category::getById( (int)$_GET['categoryId'] );
    require( TEMPLATE_PATH . "/admin/editCategory.php" );
  }
 
}
 
 
function deleteCategory() {
 
  if ( !$category = Category::getById( (int)$_GET['categoryId'] ) ) {
    header( "Location: admin.php?action=listCategories&error=categoryNotFound" );
    return;
  }
 
  $articles = Article::getList( 1000000, $category->id );
 
  if ( $articles['totalRows'] > 0 ) {
    header( "Location: admin.php?action=listCategories&error=categoryContainsArticles" );
    return;
  }
 
  $category->delete();
  header( "Location: admin.php?action=listCategories&status=categoryDeleted" );
}

function listContacts() {
  $results = array();
  $data = Contacts::getList();
  $results['contacts'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];
  $results['pageHeading'] = LANG_CONTACT_PAGE;
  $results['pageTitle'] = LANG_CONTACT_PAGE;
 
  if ( isset( $_GET['error'] ) ) {
    if ( $_GET['error'] == "categoryNotFound" ) $results['errorMessage'] = "Error: Category not found.";
    if ( $_GET['error'] == "categoryContainsArticles" ) $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
  }
 
  if ( isset( $_GET['status'] ) ) {
    if ( $_GET['status'] == "changesSaved" ) $results['statusMessage'] = "Your changes have been saved.";
    if ( $_GET['status'] == "categoryDeleted" ) $results['statusMessage'] = "Category deleted.";
  }
 
  require( TEMPLATE_PATH . "/admin/listContacts.php" );
}

function newContact() {
 
  $results = array();
  $results['pageHeading'] = LANG_CONTACT_PAGE;
  $results['pageTitle'] = LANG_CONTACT_PAGE;
  $results['formAction'] = "newContact";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the category edit form: save the new category
    $contact = new Contacts;
    $contact->storeFormValues( $_POST );
    $contact->insert();
    header( "Location: admin.php?action=listContacts&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listContacts" );
  } else {
 
    // User has not posted the category edit form yet: display the form
    $results['contact'] = new Contacts;
    require( TEMPLATE_PATH . "/admin/editContacts.php" );
  }
 
}
function editContact() {
 
  $results = array();
  $results['pageHeading'] = LANG_CONTACT_PAGE;
  $results['pageTitle'] = LANG_CONTACT_PAGE;
  $results['formAction'] = "editContact";
 
  if ( isset( $_POST['saveChanges'] ) ) {
 
    // User has posted the category edit form: save the category changes
 
    if ( !$contact = Contacts::getById( (int)$_POST['contactId'] ) ) {
      header( "Location: admin.php?action=listContacts&error=contactNotFound" );
      return;
    }
 
    $contact->storeFormValues( $_POST );
    $contact->update();
    header( "Location: admin.php?action=listContacts&status=changesSaved" );
 
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the category list
    header( "Location: admin.php?action=listContacts" );
  } else {
 
    // User has not posted the category edit form yet: display the form
    $results['contact'] = Contacts::getById( (int)$_GET['contactId'] );
    require( TEMPLATE_PATH . "/admin/editContacts.php" );
  }
 
}

function deleteContact() {
 
  if ( !$contact = Contacts::getById( (int)$_GET['contactId'] ) ) {
    header( "Location: admin.php?action=listContacts&error=contactNotFound" );
    return;
  }
  $contact->delete();
  header( "Location: admin.php?action=listContacts&status=categoryDeleted" );
}

function uploadNewImage() {
	
  $results = array();
  $results['pageTitle'] = LANG_UPLOAD_IMAGE;
  $results['formAction'] = "uploadNewImage";
  
   if ( isset( $_POST['uploadFile'] ) ) {
 
    // User has posted the article edit form: save the new article
    $upload = new Upload;
    $upload->storeFormValues( $_POST );
    $upload->insert();
    
	header( "Location: admin.php?status=upload" );
    
  } elseif ( isset( $_POST['cancel'] ) ) {
 
    // User has cancelled their edits: return to the article list
    header( "Location: admin.php" );
	
  } else {
	  
	require( TEMPLATE_PATH . "/admin/uploadForm.php" );
  }
  
}
    
 
?>