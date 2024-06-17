<?php
// unset session
session_start();

if (@$_SESSION["admin"]) {
    session_unset();
    session_destroy();
    header("Location: ../admin");
} elseif (@$_SESSION["userdudi"]){
    session_unset();
    session_destroy();

    header("Location: ../");
} else {
// redirect to login page
header("Location: ../");
}
