<?php

require_once("functions.php");

echo '
<style>

/* width */
::-webkit-scrollbar {
  width: 10px;
}

/* Track */
::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey;
  border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: green;
  border-radius: 10px;
}

div[class^="popup-overlay"] {
  /*Hides pop-up when there is no "active" class*/
  visibility: hidden;
  position: absolute;
  background: #ffffff;
  border: 1px solid green;
  width: 70%;
  height: auto;
  left: 25%;
  padding-bottom: 10px;
  padding-left: 5px;
  padding-right: 5px;
}

div[class^="popup-overlay"].active {
  /*displays pop-up when "active" class is present*/
  visibility: visible;
  text-align: left;
}

div[class^="popup-content"] {
  /*Hides pop-up content when there is no "active" class */
  visibility: hidden;
}

div[class^="popup-content"].active {
  /*Shows pop-up content when "active" class is present */
  visibility: visible;
}

</style>
';

buildNavMenu();

?>
