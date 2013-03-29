<?php
if ($data->type_obj == 1) include '_view_people.php';
elseif ($data->type_obj == 2) include '_view_document.php';
elseif ($data->type_obj == 3) include '_view_event.php';
