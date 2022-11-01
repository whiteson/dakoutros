<?php

// Utilities functions here

function get_svg($filename){
  locate_template( "assets/dist/svg/$filename.svg", true, false );
}
