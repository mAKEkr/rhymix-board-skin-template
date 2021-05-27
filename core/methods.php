<?php
  class Methods {
    public function loadPublicAsset($path) {
      $B2 = Context::get('B2');

      if (
        __B2_DEVELOPMENT__ === true &&
        defined('__B2_DEVELOPMENT_FRONTEND_HOST__') === true
      ) {
        return __B2_DEVELOPMENT_FRONTEND_HOST__ . $path;
      } else {
        return $path;
      }
    }
  }
?>