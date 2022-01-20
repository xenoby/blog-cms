<div id="adminHeader">
  <h2><?=LANG_ADMIN_PANEL ?></h2>
  <p> <?=LANG_YOU_LOGGED ?> <b><?php echo htmlspecialchars( $_SESSION['username']) ?></b>. </p> 
  <p> 
      <a href="admin.php?action=listArticles"><?=LANG_EDIT_ARTICLES ?></a> 
	  <a href="admin.php?action=listCategories"><?=LANG_EDIT_CATEGORIES ?></a>
	  <a href="admin.php?action=listContacts"><?=LANG_CONTACT_PAGE ?></a>
	  <a href="admin.php?action=uploadNewImage"?><?=LANG_UPLOAD_IMAGE ?></a>
	  <a href="admin.php?action=logout"?><?=LANG_LOGOUT ?></a>  
  </p>
</div>