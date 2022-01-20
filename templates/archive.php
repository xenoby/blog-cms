<?php include "templates/include/header.php" ?>
 
      <h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?></h1>
<?php if ( $results['category'] ) { ?>
      <h3 class="categoryDescription"><?php echo htmlspecialchars( $results['category']->description ) ?></h3>
<?php } ?>
 
      <ul id="headlines" class="archive">
 
<?php foreach ( $results['articles'] as $article ) { ?>
 
        <li>
          <h2>
            <span class="pubDate"><?php echo date('j F Y', $article->publicationDate)?></span><a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><?php echo htmlspecialchars( $article->title )?></a>
<?php if ( !$results['category'] && $article->categoryId ) { ?>
            <span class="category"><?=LANG_HOME_PAGE_SECTION?>  <a href=".?action=archive&amp;categoryId=<?php echo $article->categoryId?>"><?php echo htmlspecialchars( $results['categories'][$article->categoryId]->name ) ?></a></span>
<?php } ?>           
          </h2>
          <p class="summary"><?php echo htmlspecialchars( $article->summary )?></p>
        </li>
 
<?php } ?>
 
      </ul>
 
      <p> <?php echo ( $results['totalRows'] != 1 ) ? strtolower(LANG_ARTICLE_TOTAL) .": ". $results['totalRows'] : $results['totalRows']. " " . strtolower(LANG_ARTICLE) ?> </p>
 
      <p><a href="./"> <?=LANG_BACK_TO_MAIN?> </a></p>
 
<?php include "templates/include/footer.php" ?>