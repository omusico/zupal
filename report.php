<?php

define('ZF_PATH', '/Applications/Zend/library');

include_once('bootstrap.php');


function routes($routes) {
    ?>
<table>
    <tr>
        <th>
            Action
        </th>
        <th>
            Handler
        </th>
        <th>
            Subject Type
        </th>
        <th>
            Weight
        </th>
    </tr>

        <?php
        foreach($routes as $route) {
        if (is_array($route)){
            $route = (object) $route;
        }
            ?>
    <tr>
        <td>
                    <?=  $route->action ?>
        </td>
        <td>
                    <?= $route->handler ?>
        </td>
        <td>
                    <?= $route->subject_type ? $route->subject_type : 'ANY' ?>
        </td>
        <td>
                    <?= $route->weight ? $route->weight : '0.' ?>
        </td>
    </tr>
            <?php
        }
        ?>
</table>
    <? } ?>

<html>

    <body>
        <h2>Routes</h2>

        <? routes(Zupal_Event_Routes_Domain::instance()->find_all(null, 'action')) ?>

        <h2>Modules</h2>

        <table>
            <tr>
                <th>
                    Name
                </th>
                <th>
                    Handlers
                </th>
            </tr>

            <? foreach( Zupal_Module_Model_Mods::instance()->find_all(null, 'name') as $mod) { ?>
            <tr>
                <td nowrap valign="top">
                        <?= $mod->name ?>
                </td>
                <td>
                        <? if (array_key_exists('handlers', $mod->profile)) { 
                            routes($mod->profile['handlers']);
    } // end if handlers
    ?>
                </td>
            </tr>
    <? } ?>
        </table>
        
        <h2>Navigation</h2>

        <table>
            <tr>
                <th>
                    Uri
                </th>
                <th>
                    Action
                </th>
                <th>
                    Section
                </th>
                <th>
                    Module
                </th>
                <th>
                    Parent
                </th>
            </tr>

            <? foreach(Nav_Model_Nav::instance()->find_all(NULL, 'uri') as $nav){ ?>
            <tr>
                <th><?= $nav->uri ?></th>
                <td><?= $nav->action ?></td>
                <td><?= $nav->section ?></td>
                <td><?= $nav->module ?></td>
                <td><?= $nav->parent ?></td>
            </tr>
            <? } ?>

        </table>
    </body>
</html>