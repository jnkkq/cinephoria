<?php
if (extension_loaded("mongodb")) {
    echo "L'extension MongoDB est bien installée et activée !";
} else {
    echo "L'extension MongoDB n'est PAS installée ou activée.";
}
?>