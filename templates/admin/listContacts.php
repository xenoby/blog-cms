<?php include "templates/include/header.php" ?>
<?php include "templates/admin/include/header.php" ?>
 
    <h1><?php echo htmlspecialchars( $results['pageHeading'] ) ?></h1>

 
<?php if ( isset( $results['errorMessage'] ) ) { ?>
        <div class="errorMessage"><?php echo $results['errorMessage'] ?></div>
<?php } ?>
 
 
<?php if ( isset( $results['statusMessage'] ) ) { ?>
        <div class="statusMessage"><?php echo $results['statusMessage'] ?></div>
<?php } ?>
 
      <table>
        <tr>
          <th> <?=LANG_CATEGORY ?> </th>
        </tr>
 
<?php foreach ( $results['contacts'] as $contact ) { ?>
 
        <tr onclick="location='admin.php?action=editContacts&amp;contactId=<?php echo $contact->id?>'">
          <td>
            <?php echo $contact->content?>
          </td>
        </tr>
 
<?php } ?>
 
      </table>
 
      <p><a href="admin.php?action=newContact"> <?=LANG_ADD_CATEGORY ?> </a></p>
 
<?php include "templates/include/footer.php" ?>