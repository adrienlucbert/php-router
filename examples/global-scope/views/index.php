<?php
$global_variable = "It works!";

function print_global_variable() {
    global $global_variable;
    if ($global_variable == null) {
        echo "It doesn't work";
    } else {
        echo $global_variable;
    }
}

print_global_variable();