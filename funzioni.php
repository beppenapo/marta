<?php
  function filtraPost($ilpost) {
	  $postfiltrato = filter_input(INPUT_POST,$ilpost, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	  return $postfiltrato;
  }
  function filtraGet($ilpost) {
	  $postfiltrato = filter_input(INPUT_GET,$ilpost, FILTER_SANITIZE_STRING);
	  return $postfiltrato;
  }
  function filtraInt($ilpost) {
	  $postfiltrato = (int)$ilpost;
	  return $postfiltrato;
  }
?>