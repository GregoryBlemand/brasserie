<?php
/* Copyright (C) 2007-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
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
 *   	\file       brasserie/brasserie_card.php
 *		\ingroup    brasserie
 *		\brief      This file is an example of a php page
 *					Initialy built by build_class_from_table on 2017-06-26 09:22
 */

//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)

// Change this following line to use the correct relative path (../, ../../, etc)
$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../dolibarr/htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../dolibarr/htdocs/main.inc.php")) $res=@include '../../../../dolibarr/htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
include_once(DOL_DOCUMENT_ROOT.'/core/class/html.formcompany.class.php');
dol_include_once('/brasserie/class/brasserie.class.php');

// Load traductions files requiredby by page
$langs->load("brasserie");
$langs->load("other");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$cancel     = GETPOST('cancel');
$backtopage = GETPOST('backtopage');
$myparam	= GETPOST('myparam','alpha');

$mode       = 'view';

$search_ref=GETPOST('search_ref','alpha');
$search_label=GETPOST('search_label','alpha');
$search_adresse=GETPOST('search_adresse','alpha');
$search_status=GETPOST('search_status','int');
$search_fk_user_author=GETPOST('search_fk_user_author','int');
$search_fk_soc=GETPOST('search_fk_soc','int');



if (empty($action) && empty($id) && empty($ref)) $action='create';

// Protection if external user
if ($user->societe_id > 0)
{
	//accessforbidden();
}
//$result = restrictedArea($user, 'brasserie', $id);


$object = new Brasserie($db);
if(!empty($id) || $id !== ''){
	if($object->fetch($id) <1){
		echo 'brasserie introuvable';
		exit;
	}	
}
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

// Load object
include DOL_DOCUMENT_ROOT.'/core/actions_fetchobject.inc.php';  // Must be include, not include_once  // Must be include, not include_once. Include fetch and fetch_thirdparty but not fetch_optionals

// Initialize technical object to manage hooks of modules. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('brasserie'));



/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

$parameters=array();
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

if (empty($reshook))
{
	if ($cancel) 
	{
		if ($action != 'addlink')
		{
			$urltogo=$backtopage?$backtopage:dol_buildpath('/brasserie/brasserie_list.php',1);
			header("Location: ".$urltogo);
			exit;
		}		
		if ($id > 0 || ! empty($ref)) $ret = $object->fetch($id,$ref);
		$action='';
	}
	
	// Action to add record
	if ($action == 'add')
	{
		if (GETPOST('cancel'))
		{
			$urltogo=$backtopage?$backtopage:dol_buildpath('/brasserie/brasserie_list.php',1);
			header("Location: ".$urltogo);
			exit;
		}

		$error=0;

		/* object_prop_getpost_prop */
		
	$object->ref=GETPOST('ref','alpha');
	$object->label=GETPOST('label','alpha');
	$object->adresse=GETPOST('adresse','alpha');
	$object->status=GETPOST('status','int');
	$object->fk_user_author=GETPOST('fk_user_author','int');
	$object->fk_soc=GETPOST('fk_soc','int');

		

		if (empty($object->ref))
		{
			$error++;
			setEventMessages($langs->trans("ErrorFieldRequired",$langs->transnoentitiesnoconv("Ref")), null, 'errors');
		}

		if (! $error)
		{
			$result=$object->create($user);
			if ($result > 0)
			{
				// Creation OK
				$urltogo=$backtopage?$backtopage:dol_buildpath('/brasserie/brasserie_list.php',1);
				header("Location: ".$urltogo);
				exit;
			}
			{
				// Creation KO
				if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
				else  setEventMessages($object->error, null, 'errors');
				$action='create';
			}
		}
		else
		{
			$action='create';
		}
	}

	// Action to update record
	if ($action == 'update')
	{
		$error=0;

		
	$object->ref=GETPOST('ref','alpha');
	$object->label=GETPOST('label','alpha');
	$object->adresse=GETPOST('adresse','alpha');
	$object->status=GETPOST('status','int');
	$object->fk_user_author=GETPOST('fk_user_author','int');
	$object->fk_soc=GETPOST('fk_soc','int');

		

		if (empty($object->ref))
		{
			$error++;
			setEventMessages($langs->transnoentitiesnoconv("ErrorFieldRequired",$langs->transnoentitiesnoconv("Ref")), null, 'errors');
		}

		if (! $error)
		{
			$result=$object->update($user);
			if ($result > 0)
			{
				$action='view';
			}
			else
			{
				// Creation KO
				if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
				else setEventMessages($object->error, null, 'errors');
				$action='edit';
			}
		}
		else
		{
			$action='edit';
		}
	}

	// Action to delete
	if ($action == 'confirm_delete')
	{
		$result=$object->delete($user);
		if ($result > 0)
		{
			// Delete OK
			setEventMessages("RecordDeleted", null, 'mesgs');
			header("Location: ".dol_buildpath('/custom/brasserie/brasserie_list.php',1));
			exit;
		}
		else
		{
			if (! empty($object->errors)) setEventMessages(null, $object->errors, 'errors');
			else setEventMessages($object->error, null, 'errors');
		}
	}
	
	// Action to validate
	if ($action == 'validate'){
		if (!empty($user->rights->brasserie->create)) $object->setValid($db) ;
		
		header('Location: '.dol_buildpath('/custom/brasserie/brasserie_card.php', 1).'?id='.$object->id);
		exit;
	}
}




/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

$form=new Form($db);


// Put here content of your page

// Example : Adding jquery code
print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	function init_myfunc()
	{
		jQuery("#myid").removeAttr(\'disabled\');
		jQuery("#myid").attr(\'disabled\',\'disabled\');
	}
	init_myfunc();
	jQuery("#mybutton").click(function() {
		init_myfunc();
	});
});
</script>';


// Part to create
if ($action == 'create')
{
	llxHeader('',$langs->trans("NewPub"),'');
	
	print load_fiche_titre($langs->trans("NewPub"));

	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="action" value="add">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';

	dol_fiche_head();

	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.GETPOST('ref').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlabel").'</td><td><input class="flat" type="text" name="label" value="'.GETPOST('label').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldadresse").'</td><td><input class="flat" type="text" name="adresse" value="'.GETPOST('adresse').'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldstatus").'</td><td>'.$object->getLibStatut().'<input class="flat" type="hidden" name="status" value="0"></td></tr>';
print '<input class="flat" type="hidden" name="fk_user_author" value="'.$user->id.'">';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_soc").'</td><td>'.$form->select_company($object->fk_soc,'fk_soc','',1).'</td></tr>';

	print '</table>'."\n";

	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="add" value="'.$langs->trans("Create").'"> &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'"></div>';

	print '</form>';
}



// Part to edit record
if (($id || $ref) && $action == 'edit')
{
	llxHeader('',$langs->trans("EditPub"),'');
	
	print load_fiche_titre($langs->trans("MyModule"));
	print '<form method="POST" action="'.$_SERVER["PHP_SELF"].'">';
	print '<input type="hidden" name="action" value="update">';
	print '<input type="hidden" name="backtopage" value="'.$backtopage.'">';
	print '<input type="hidden" name="id" value="'.$object->id.'">';
	
	dol_fiche_head();

	$form = new Form($db);
	
	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td><input class="flat" type="text" size="36" name="label" value="'.$label.'"></td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td><input class="flat" type="text" name="ref" value="'.$object->ref.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlabel").'</td><td><input class="flat" type="text" name="label" value="'.$object->label.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldadresse").'</td><td><input class="flat" type="text" name="adresse" value="'.$object->adresse.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldstatus").'</td><td>'.$object->getLibStatut().'<input class="flat" type="hidden" name="status" value="'.$object->status.'"></td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_soc").'</td><td>'.$form->select_company($object->fk_soc,'fk_soc','',1).'</td></tr>';

	print '</table>';
	
	dol_fiche_end();

	print '<div class="center"><input type="submit" class="button" name="save" value="'.$langs->trans("Save").'">';
	print ' &nbsp; <input type="submit" class="button" name="cancel" value="'.$langs->trans("Cancel").'">';
	print '</div>';

	print '</form>';
}



// Part to show record
if ($object->id > 0 && (empty($action) || ($action != 'edit' && $action != 'create')))
{
	llxHeader('',$langs->trans("MyPub"),'');
    $res = $object->fetch_optionals($object->id, $extralabels);
    
    $h = 0;
    $head = [];
    $head[$h][0] = dol_buildpath('/brasserie/brasserie_card.php', 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans('brasserie');
    $head[$h][2] = 'brasserie';
    $h++;
    
    $head[$h][0] = dol_buildpath('/brasserie/biere_list.php', 1).'?id='.$object->id;
    $head[$h][1] = $langs->trans('listbiere');
    $head[$h][2] = 'listbiere';
    $h++;
    
	dol_fiche_head($head, 0, $langs->trans("Brasserie"), 0, 'order');
		
	print load_fiche_titre($langs->trans("MyModule"));
    
	dol_fiche_head();

	if ($action == 'delete') {
		$formconfirm = $form->formconfirm($_SERVER["PHP_SELF"] . '?id=' . $object->id, $langs->trans('DeleteMyOjbect'), $langs->trans('ConfirmDeleteMyObject'), 'confirm_delete', '', 0, 1);
		print $formconfirm;
	}
	
	$thirdparty_static = new Societe($db);
	$thirdparty_static->fetch($object->fk_soc);
	
	print '<table class="border centpercent">'."\n";
	// print '<tr><td class="fieldrequired">'.$langs->trans("Label").'</td><td>'.$object->label.'</td></tr>';
	// 
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldref").'</td><td>'.$object->ref.'</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldlabel").'</td><td>'.$object->label.'</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldadresse").'</td><td>'.$object->adresse.'</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldstatus").'</td><td>'.$object->getLibStatut().'</td></tr>';
print '<tr><td class="fieldrequired">'.$langs->trans("Fieldfk_soc").'</td><td>'.$thirdparty_static->getNomUrl(1).'</td></tr>';

	print '</table>';
	
	dol_fiche_end();


	// Buttons
	print '<div class="tabsAction">'."\n";
	$parameters=array();
	$reshook=$hookmanager->executeHooks('addMoreActionsButtons',$parameters,$object,$action);    // Note that $action and $object may have been modified by hook
	if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');

	if (empty($reshook))
	{
		if ($user->rights->brasserie->create)
		{
			if($object->status == 0){
				print '<div class="inline-block divButAction"><a class="butAction" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=validate">'.$langs->trans("Validate").'</a></div>'."\n";
			} else {
				print '<div class="inline-block divButAction"><a class="butAction" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=validate">'.$langs->trans("Reopen").'</a></div>'."\n";
			}
			print '<div class="inline-block divButAction"><a class="butAction" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=edit">'.$langs->trans("Modify").'</a></div>'."\n";
		}

		if ($user->rights->brasserie->delete)
		{
			print '<div class="inline-block divButAction"><a class="butActionDelete" href="'.$_SERVER["PHP_SELF"].'?id='.$object->id.'&amp;action=delete">'.$langs->trans('Delete').'</a></div>'."\n";
		}
	}
	print '</div>'."\n";


	// Example 2 : Adding links to objects
	// Show links to link elements
	//$linktoelem = $form->showLinkToObjectBlock($object, null, array('brasserie'));
	//$somethingshown = $form->showLinkedObjectBlock($object, $linktoelem);

}


// End of page
llxFooter();
$db->close();
