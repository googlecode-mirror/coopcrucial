<?php
function smarty_modifier_utf ($string)
 {
 $utf = utf8_encode ($string);
 return $utf;
}
?> 