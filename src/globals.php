<?php

use function DT\Home\view;
function dt_home_back_button() : string
{
   echo view('partials/return-to-launcher-button');
}
