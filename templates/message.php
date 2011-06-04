<?php if (@$message) foreach((array) $message as $msg) { ?>
  <p><?php e($msg); ?></p>
<?php } ?>
<?php if (@$link) { ?>
  <p><a href="<?php e($link); ?>"><?php e($link); ?></p>
<?php } ?>
