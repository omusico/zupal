<?php

define('ZF_PATH', '/Applications/Zend/library');

include_once('bootstrap.php');

?>
<html>
    <head>
        <style>
            #nav {
                background-color: #CCFFCC;
}
        </style>
    </head>

    <body>
        <table id="outer">
            <tr>
                <td id="nav">
                    <ul class="menu">
                        <? foreach(Nav_Model_Nav::instance()->menu('main') as $item): ?>
                        <li>
                            <?= $item ?>
                        </li>
                        <? endforeach; ?>
                    </ul>

                </td>
                <td>
                    <h1>Zupal 3 - in 3D!</h1>

                </td>
            </tr>
        </table>
    </body>
</html>