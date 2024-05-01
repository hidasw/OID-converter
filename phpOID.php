<?php
/*
// File name : phpOID.php
// Version : 1.1
// Begin : 26/03/2009
// Last Update : 05/01/2024
// Author : Hida - https://github.com/hidasw
// License : GPL-3.0 license
// -------------------------------------------------------------------
// 
// Covert OID to hex and vice versa.
// limited to 10 digit oid
*/
class oid {
//============================================================+
// function    : oid2hex
// Version     : 1.1
// Begin       : 26/03/2009
// Last Update : 30/04/2024
// Author      : Hida - https://github.com/hidasw
// License     : GPL-3.0 license
// Description : Convert oid number to hexadecimal form
// Changes     : Tuesday, 30 April 2024 08:25:52 Simplified from 3 functions to just one function
// -------------------------------------------------------------------
  public static function tohex($oid) {
    if(!preg_match("~^(?!\.)[0-9.]*$(?<!\.)~", $oid)) { // only allow dot and number
      return false;
    }
    $arr = explode(".", trim($oid, "."));
    if(count($arr)<2) {
      return false;
    }
    $i = 0;
    $ret = false;
    foreach($arr as $dec) {
      if($i == 0) {
        if($dec <= 2) {
          $add = $dec*40;
        } else {
          return false;
        }
      }
      if($i == 1) {
        if($dec > 39) {
          return false;
        } else {
          $ret = str_pad(dechex($dec+$add), 2, "0", STR_PAD_LEFT);
        }
      }
      if($i > 1) {
        if(strlen($dec)>10) { return false; } // max 10 digit
        if($dec >= 128) {
          $ix=0;
          $hex = array();
          while($dec != $dec%128) {
            $hida = $dec%128;
            if($ix != 0) { // not first loop
              $hida = $hida+128;
            }
            $dec = floor($dec/128);
            $hex[] = str_pad(dechex($hida), 2, "0", STR_PAD_LEFT);
            if($dec == $dec%128) { // end loop
              $hex[] = str_pad(dechex($dec+128), 2, "0", STR_PAD_LEFT);
            }
            $ix++;
          }
          $ret .= implode('', array_reverse($hex));
        } else {
          $ret .= str_pad(dechex($dec), 2, "0", STR_PAD_LEFT);
        }
      }
      $i++;
    }
    return $ret;
  }
  
//============================================================+
// function    : oidfromhex
// Version     : 1.1
// Begin       : 26/03/2009
// Last Update : 30/04/2024
// Author      : Hida - https://github.com/hidasw
// License     : GPL-3.0 license
// Description : Convert hex to oid
// -------------------------------------------------------------------
  public static function fromhex($hex) {
    if(!ctype_xdigit($hex)) { return false; }
    $split = str_split($hex, 2);
    $i = 0;
    $nex = false;
    $result = false;
    foreach($split as $val) {
      $dec = hexdec($val);
      if($i == 0) {
        if($dec >= 128) {
          $nex = (128*($dec-128))-80;
          $result = "2.";
        } elseif($dec >= 80) {
          $first = $dec-80;
          $result = "2.$first.";
        } elseif($dec >= 40) {
          $first = $dec-40;
          $result = "1.$first.";
        } elseif($dec < 40) {
          $first = $dec-0;
          $result = "0.$first.";
        }
      } else {
        $mplx = ($dec-128)*128;
        if($dec > 127) {
          if($nex == false) {
            $nex = $mplx;
          } else {
            $nex = ($nex*128)+$mplx;
          }
        } else {
          $result .= ($dec+$nex).".";
          if($dec <= 127) {
            $nex = 0;
          }
        }
      }
      $i++;
    }
    return rtrim($result, ".");
  }
?>