<?php include "templates/include/header.php" ?>

<h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?></h1>
 
      <ul id="headlines" class="archive">
 
<?php foreach ( $results['contacts'] as $contact ) { ?>
 
        <li>
          <h2>
            <span class="pubDate"><?php echo date('j F Y', $contact->publicationDate)?></span>
			<a href=".?action=viewArticle&amp;articleId=<?php echo $contact->id?>">		
          </h2>
          <p class="summary"><?php echo htmlspecialchars( $contact->content )?></p>
        </li>
 
<?php } ?>
 
      </ul>
 
 
<?php include "templates/include/footer.php" ?>