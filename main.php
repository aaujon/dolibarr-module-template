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
 *	\file		main.php
 *	\ingroup	prestashop
 *	\brief		Main prestashop module page
 */

//if (! defined('NOREQUIREUSER'))	define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))		define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))	define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))	define('NOREQUIRETRAN','1');
// Do not check anti CSRF attack test
//if (! defined('NOCSRFCHECK'))		define('NOCSRFCHECK','1');
// Do not check style html tag into posted data
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');
// Do not check anti POST attack test
//if (! defined('NOTOKENRENEWAL'))	define('NOTOKENRENEWAL','1');
// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREMENU'))	define('NOREQUIREMENU','1');
// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREHTML'))	define('NOREQUIREHTML','1');
//if (! defined('NOREQUIREAJAX'))	define('NOREQUIREAJAX','1');
// If this page is public (can be called outside logged session)
//if (! defined("NOLOGIN"))			define("NOLOGIN",'1');
// Change the following lines to use the correct relative path
// (../, ../../, etc)
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

dol_include_once('/prestashop/class/myclass.class.php');

// Load translation files required by the page
$langs->load("prestashop@prestashop");

// Get parameters
$id = GETPOST('id', 'int');
$action = GETPOST('action', 'alpha');
$myparam = GETPOST('myparam', 'alpha');

// Access control
if ($user->societe_id > 0) {
	// External user
	accessforbidden();
}

// Default action
if (empty($action) && empty($id) && empty($ref)) {
	$action='create';
}

// Load object if id or ref is provided as parameter
/*$object = new MyClass($db);
if (($id > 0 || ! empty($ref)) && $action != 'add') {
	$result = $object->fetch($id, $ref);
	if ($result < 0) {
		dol_print_error($db);
	}
}*/

/*
 * ACTIONS
 *
 * Put here all code to do according to value of "action" parameter
 */

if ($action == 'add') {
	/*$myobject = new MyClass($db);
	$myobject->prop1 = $_POST["field1"];
	$myobject->prop2 = $_POST["field2"];
	$result = $myobject->create($user);
	if ($result > 0) {
		// Creation OK
	} {
		// Creation KO
		$mesg = $myobject->error;
	}*/
}

/*
 * VIEW
 *
 * Put here all code to build page
 */
llxHeader('', $langs->trans('Prestashop synchronisation'), '');

print '<br />
          <fieldset class="width10">
			    <legend><img src="img/synchro.png" /> '.$langs->trans('Synchronization').'</legend>    
				    <fieldset style="width8">
						<legend>'.$langs->trans('Clients').'</legend>  
								  <a>'.$langs->trans('Last synchronization : ').', 
								  '.$langs->trans('There is ').$nb_clients_to_sync.$langs->trans(' updated or new clients').'</a><br />																													
								  <img src="img/yes.gif" /> > </a><a href="synchroclients.php" target="blank" ><b style="color: #000099;">' .
                                            $langs->trans('Synchronize updated and new clients').'</b></a><br />
                                  <img src="img/yes.gif" /> > </a><a href="synchroclients.php?action=reset" target="blank" ><b style="color: #000099;">' .
                                            $langs->trans('Synchronize all clients').'</b></a><br />
                            <br />
					</fieldset>
					<fieldset style="width8">
						<legend>'.$langs->trans('Products').'</legend>  
								   <a>'.$langs->trans('Last synchronization : ').$langs->trans('There is ').$nb_products_to_sync.$langs->trans(' updated or new products').'</a><br />
                                  <img src="img/yes.gif" /> > </a><a href="synchroproducts.php" target="blank" ><b style="color: #000099;">' .
                                            $langs->trans('Synchronize updated and new products').'</b></a><br />
                                  <img src="img/yes.gif" /> > </a><a href="synchroproducts.php?action=reset" target="blank" ><b style="color: #000099;">' .
                                            $langs->trans('Synchronize all products').'</b></a><br />	
                            <br />		
					</fieldset> 
					<fieldset style="width8">
						<legend>'.$langs->trans('Orders and invoices').'</legend>  
								   <a>'.$langs->trans('Last synchronization : ').$langs->trans('There is ').$nb_orders_to_sync.$langs->trans(' updated or new orders or invoices').'</a><br />
                                  <img src="img/yes.gif" /> > </a><a href="synchroorders.php" target="blank" ><b style="color: #000099;">' .
                                            $langs->trans('Synchronize updated and new orders or invoices').'</b></a><br />
                                  <img src="img/yes.gif" /> > </a><a href="synchroorders.php?action=reset" target="blank" ><b style="color: #000099;">' .
                                            $langs->trans('Synchronize all orders and invoices').'</b></a><br />
                            <br />		
					</fieldset>                 
			</fieldset>';


//$form = new Form($db);

// Put here content of your page
// Example 1: Adding jquery code
/*echo '<script type="text/javascript" language="javascript">
	jQuery(document).ready(function() {
		public function init_myfunc()
		{
			jQuery("#myid").removeAttr(\'disabled\');
			jQuery("#myid").attr(\'disabled\',\'disabled\');
		}
		init_myfunc();
		jQuery("#mybutton").click(function() {
			init_needroot();
		});
	});
</script>';*/

// Example 2: Adding links to objects
// The class must extend CommonObject for this method to be available
//$somethingshown = $myobject->showLinkedObjectBlock();

dol_htmloutput_mesg($mesg);

// End of page
llxFooter();
