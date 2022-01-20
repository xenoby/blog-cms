<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
       <h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?></h1>
 
      <form action="admin.php?action=<?php echo $results['formAction']?>" method="post">
        <input type="hidden" name="categoryId" value="<?php echo $results['contact']->id ?>"/>
 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
        <ul>
 
          <li>
            <label for="name"><?=LANG_CATEGORY_NAME?></label>
            <input type="text" name="name" id="name" placeholder="Name of the category" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['contact']->content )?>" />
          </li>
 
        </ul>
 
        <div class="buttons">
          <input type="submit" name="saveChanges" value="Save Changes" />
          <input type="submit" formnovalidate name="cancel" value="Cancel" />
        </div>
 
      </form>
 
<?php if ( $results['contact']->id ) { ?>
      <p><a href="admin.php?action=deleteContact&amp;contactId=<?php echo $results['contact']->id ?>" onclick="return confirm('Delete This Contact?')"><?=LANG_DEL_CATEGORY?></a></p>
<?php } ?>
 
<?php include "templates/include/footer.php" ?>