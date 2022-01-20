<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo htmlspecialchars( $results['pageTitle'] )?></title>
    <link rel="stylesheet" type="text/css" href="style.css" />
  </head>
  <body>
    <div id="container">
 
     <!-- <a href="."><img id="logo" src="images/logo.jpg" alt="Widget News" /></a> -->
	  
		<nav>
		<ul class="top-menu">
		<!--<li class="active">ABOUT</li> -->
		
			<li class="active"><a href="./"><?=LANG_HOME_PAGE?></a></li>	
			<li class="unactive"><a href="./?action=gallery"><?=LANG_WORK_GALLERY_PAGE?></a></li>
			<li class="unactive"><a href="./?action=archive"><?=LANG_BLOG_PAGE?></a></li>
			<li class="unactive"><a href="./?action=about"><?=LANG_ABOUT_PAGE?></a></li>
			<li class="unactive"><a href="./?action=contacts"><?=LANG_CONTACT_PAGE?></a></li>
			
			
		</ul>
		</nav>	
	  
	  <div id="logo">
	  </div>
	