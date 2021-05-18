<?php
  $data = new StdClass();
  $B2 = Context::get('B2');

  Context::setResponseMethod('JSON', 'application/json');
  echo json_encode($B2);