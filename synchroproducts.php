<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file		synchroproducts.php
 *	\ingroup	prestashop
 *	\brief		Synchronisation of products
 */
$res = 0;
if (! $res && file_exists("../main.inc.php")) {
	$res = @include "../main.inc.php";
}
if (! $res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (! $res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
// The following should only be used in development environments
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) {
	$res = @include "../../../dolibarr/htdocs/main.inc.php";
}
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) {
	$res = @include "../../../../dolibarr/htdocs/main.inc.php";
}
if (! $res && file_exists("../../../../../dolibarr/htdocs/main.inc.php")) {
	$res = @include"../../../../../dolibarr/htdocs/main.inc.php";
}
if (! $res) {
	die("Main include failed");
}

global $db, $langs, $user;

dol_include_once('/prestashop/lib/PSWebServiceLibrary.lib.php');
dol_include_once('/prestashop/lib/synchro1product.lib.php');

// Load translation files required by the page
$langs->load("prestashop@prestashop");

ini_set('display_errors', 1);
error_reporting(E_ALL);

llxHeader('', $langs->trans('Prestashop product synchronisation'), '');

// action if reset synchronization
$action = GETPOST('action', 'alpha');
if ($action == "reset") {
    //Configuration::updateValue('products_last_synchro', "1970-01-01 00:00:00");
}

// Here we make the WebService Call
try
{
    $webService = new PrestaShopWebservice(PRESTASHOP_URL, PRESTASHOP_KEY, true);
    $xml = $webService->get(array('resource' => 'products'));
    // Here we get the elements from children of customers markup "products"
    $resources = $xml->products->children();
}
catch (PrestaShopWebserviceException $e)
{
    // Here we are dealing with errors
    $trace = $e->getTrace();
    if ($trace[0]['args'][0] == 404) echo 'Bad ID';
    else if ($trace[0]['args'][0] == 401) echo 'Bad auth key';
    else echo 'Other error '.$trace[0]['args'][0];
}


/*
 * VIEW
 *
 * Put here all code to build page
 */


echo '<table border="5">';
// if $resources is set we can lists element in it otherwise do nothing cause there's an error
if (isset($resources))
{
    echo '<tr><th>Id</th></tr>';
    foreach ($resources as $resource)
    {
        // Iterates on the found IDs
        synchroProduct($webService, $resource->attributes());
    }
}
echo '</table>';

echo "Synchronisation of products done<br>";
// End of page
llxFooter();
?>
