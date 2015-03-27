<?php
/* Copyright (C) 2004		Rodolphe Quiedeville	<rodolphe@quiedeville.org>
 * Copyright (C) 2005-2010	Laurent Destailleur		<eldy@users.sourceforge.org>
 * Copyright (C) 2011		Juanjo Menent			<jmenent@2byte.es>
 * Copyright (C) 2012		Regis Houssin			<regis.houssin@capnetworks.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *      \file       prestashop/admin/configure.php
 *		\ingroup    prestashop
 *		\brief      Page to configure prestashop module
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/admin.lib.php';

$langs->load("admin");

if (! $user->admin)
	accessforbidden();

$actionsave=GETPOST("save");

// Sauvegardes parametres
if ($actionsave)
{
    $i=0;

    $db->begin();

    $i+=dolibarr_set_const($db,'PRESTASHOP_URL',trim(GETPOST("PRESTASHOP_URL")),'chaine',0,'',$conf->entity);
    $i+=dolibarr_set_const($db,'PRESTASHOP_KEY',trim(GETPOST("PRESTASHOP_KEY")),'chaine',0,'',$conf->entity);


    if ($i == 2)
    {
        $db->commit();
        setEventMessage($langs->trans("SetupSaved"));
    }
    else
    {
        $db->rollback();
        setEventMessage($langs->trans("Error"), 'errors');
    }
}


/*
 *	View
 */

llxHeader();

$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';
print_fiche_titre($langs->trans("Prestashop module setup"),$linkback,'setup');

print $langs->trans("PrestashopDesc")."<br>\n";
print "<br>\n";

print '<form name="agendasetupform" action="'.$_SERVER["PHP_SELF"].'" method="post">';
print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
print '<table class="noborder" width="100%">';

print '<tr class="liste_titre">';
print "<td>".$langs->trans("Parameter")."</td>";
print "<td>".$langs->trans("Value")."</td>";
print "<td>".$langs->trans("Examples")."</td>";
print "<td>&nbsp;</td>";
print "</tr>";

print '<tr class="impair">';
print ' <td class="fieldrequired">'.$langs->trans("Prestashop URL").'</td>';
print ' <td><input type="text" class="flat" id="PRESTASHOP_URL" name="PRESTASHOP_URL" value="'. (GETPOST('PRESTASHOP_URL')?GETPOST('PRESTASHOP_URL'):(! empty($conf->global->PRESTASHOP_URL)?$conf->global->PRESTASHOP_URL:'')) . '" size="40"></td>';
print ' <td>http://myserveur.com</td>';
print '<tr/>';
print '<tr class="impair">';
print ' <td class="fieldrequired">'.$langs->trans("Prestashop api key").'</td>';
print ' <td><input type="text" class="flat" id="PRESTASHOP_KEY" name="PRESTASHOP_KEY" value="'. (GETPOST('PRESTASHOP_KEY')?GETPOST('PRESTASHOP_KEY'):(! empty($conf->global->PRESTASHOP_KEY)?$conf->global->PRESTASHOP_KEY:'')) . '" size="40"></td>';
print '<td>&nbsp;</td>';
print '</tr>';

print '</table>';

print '<br><center>';
print '<input type="submit" name="save" class="button" value="'.$langs->trans("Save").'">';
print '</center>';

print '</form>';

llxFooter();
$db->close();

