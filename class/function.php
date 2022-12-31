<?php 
function sanitizeInputs($input)
{
   $input = strip_tags($input);
   $input = htmlentities($input);
   return $input;
}