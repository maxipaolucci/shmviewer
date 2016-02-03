<?php

if (!isset ($path) || empty($path)) {
  $path = '.';
}

include($path . '/Connection.class.php');
include($path . '/includes/Mail.class.php');
include($path . '/includes/model/Table.class.php');
include($path . '/includes/model/PostTable.class.php');
include($path . '/includes/model/PostUpdateTable.class.php');
include($path . '/includes/model/CategoryTable.class.php');
include($path . '/includes/model/TagTable.class.php');
include($path . '/includes/model/UserTable.class.php');

include($path . '/includes/videoProvider/Youtube.class.php');
include($path . '/includes/videoProvider/Vimeo.class.php');

include($path . '/includes/model/Post.class.php');
include($path . '/includes/model/PostUpdate.class.php');
include($path . '/includes/model/Tag.class.php');
include($path . '/includes/model/Category.class.php');
include($path . '/includes/model/User.class.php');
?>
