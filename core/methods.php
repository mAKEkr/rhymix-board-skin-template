<?php
  class Methods {
    public function HiddenIPAddress($ip, $wildcard = '..***.***'){
      if(!isset($ip)) return;
  
      $wildcard_list = explode('.', $wildcard);
  
      if((int)count($wildcard_list) === 4){
        $ipblocks = explode('.', $ip);
  
        if((int)count($ipblocks) !== 4){
          return $ip;
        }
  
        $return_str = null;
  
        foreach($ipblocks as $key => $val){
          if($wildcard_list[$key] != NULL){
            $ipblocks[$key] = $wildcard_list[$key];
          }
  
          $return_str .= $ipblocks[$key] . '.';
        }
  
        unset($ipblocks);
        unset($wildcard_list);
  
        return substr($return_str, 0, -1);
      } else {
        unset($wildcard_list);
        return $ip;
      }
    }
  }
?>