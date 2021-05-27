<?php
  $data = new StdClass();
  $B2 = Context::get('B2');

  Context::setResponseMethod('JSON', 'application/json');
  unset($B2->Methods);
  echo json_encode($B2);