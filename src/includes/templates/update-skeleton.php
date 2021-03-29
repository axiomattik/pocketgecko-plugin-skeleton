<form class="update-skeleton-form" id="frm-update-skeleton-<?php echo $id; ?>" method="post" action="/">
  <input id="inp-title-<?php echo $id; ?>" type="text" value="<?php echo $title; ?>">
  <input id="inp-value-<?php echo $id; ?>" type="text" value="<?php echo $meta_value; ?>">
  <input type="submit" value="Update Skeleton Post">
  <input class="delete-skeleton-button" id="btn-delete-<?php echo $id; ?>" type="button" value="Delete">

</form>
