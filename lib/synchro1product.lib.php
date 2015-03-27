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
 *	\file		lib/synchroproducts.lib.php
 *	\ingroup	prestashop
 *	\brief		synchroProducts : Retrieve products from Prestashop 
 *              and update them
 */
require_once DOL_DOCUMENT_ROOT."/product/class/product.class.php";

function synchroProduct($webService, $id_product)
{
    global $db,$conf,$langs;
    include_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';

    //retrieve prestashop product
    $opt['resource'] = 'products';
    $opt['id'] = $id_product;
    $xml = $webService->get($opt);
    $prestashop
    $product = new Product($db);
    $result=$product->fetch('','',$id_product); // ref_ext
    if ($result > 0)
    {
        //update product
    }
    else
    {
        // create product
        $product->ref=$product['ref'];
        $product->ref_ext=$id_product;
        $product->type=$product['type'];
        $product->libelle=$product['label']; // TODO deprecated
        $product->label=$product['label'];
        $product->description=$product['description'];
        $product->note=$product['note'];
        $product->status=$product['status_tosell'];
        $product->status_buy=$product['status_tobuy'];
        $product->price=$product['price_net'];
        $product->price_ttc=$product['price'];
        $product->tva_tx=$product['vat_rate'];
        $product->price_base_type=$product['price_base_type'];
        $product->date_creation=$now;
        if ($product['barcode'])
        {
        $product->barcode = $product['barcode'];
        $product->barcode_type = $product['barcode_type'];
        }
        $product->stock_reel=$product['stock_real'];
        $product->pmp=$product['pmp'];
        $product->seuil_stock_alert=$product['stock_alert'];
        $product->country_id=$product['country_id'];
        if ($product['country_code']) $product->country_id=getCountry($product['country_code'],3);
        $product->customcode=$product['customcode'];
        $product->canvas=$product['canvas'];
        $db->begin();
        $result=$product->create($fuser,0);
        if ($result <= 0)
        {
        $error++;
        }
        if (! $error)
        {
        $db->commit();
        $objectresp=array('result'=>array('result_code'=>'OK', 'result_label'=>''),'id'=>$product->id,'ref'=>$product->ref);
        }
        else
        {
        $db->rollback();
        $error++;
        $errorcode='KO';
        $errorlabel=$product->error;
}
    }

   /* $product_description = Configuration::get('product_description');
    var_dump($product_description);

    if ($product = Db::getInstance()->GetRow("select * from "._DB_PREFIX_."product where id_product = '".$id_product."'"))
    {
	    // retrieve params
	    $prefix_ref_product=Configuration::get('prefix_ref_product');
	    $prefix_ref_product = accents_sans("$prefix_ref_product"); 

        //retrieve product data
        $prix_produit_normal_HT=$product['price'];
        $active=$product['active'];
        $reference=$product['reference'];
        $reference=produits_caract("$reference");
        $en_vente=$product['active'];
        $barcode=$product['ean13'];
        //$datec=$product['date_add'];
        //$tms=$product['date_upd'];
        //$weight=$product['weight'];
     
        // find tva rate  
        $id_tax_rules_group=$product['id_tax_rules_group'];
        //var_dump($id_tax_rules_group);
        $donnees_id_tax_rules_group = Db::getInstance()->GetRow("select * from "._DB_PREFIX_."tax_rule where id_tax_rules_group = '".$id_tax_rules_group."'");
        //var_dump($donnees_id_tax_rules_group);
        $id_tax=$donnees_id_tax_rules_group['id_tax'];
        //var_dump($id_tax);
        $donnees_tax = Db::getInstance()->GetRow("select * from "._DB_PREFIX_."tax where id_tax = '".$id_tax."'");
        $vat_rate=$donnees_tax['rate'];
        echo "vat_rate : $vat_rate";
        $prix_produit_normal_HT=sprintf("%.2f",$prix_produit_normal_HT);

        //find description
		$product_data = Db::getInstance()->GetRow("select * from "._DB_PREFIX_."product_lang where id_product = '".$id_product."' AND id_lang = '".Context::getContext()->language->id."'");

		if ($product_description == '0') {
			$description = $product_data['description_short'];
		} else {
			$description = $product_data['description'];
		}

        $label = $product_data['name'];

        // RECUPERATION DES DONNEES DU PRODUIT DANS LA BASE ARTICLES *********************************************

        // RECUPERATION ID IMAGE ****************************************************
        //$donnees_id_image = Db::getInstance()->GetRow("select * from ".$prefix_presta."image where id_product='".$product_id."'");
        //$id_image=$donnees_id_image['id_image'];
        // FIN RECUPERATION ID IMAGE ****************************************************
  

        $dolibarr = Dolibarr::getInstance();

		// Check if already exists in Dolibarr
		$exists = $dolibarr->getProduct($prefix_ref_product.$id_product);
		
		$product = new DolibarrProduct();
		$product->ref_ext = $prefix_ref_product.$id_product;
        $product->ref = $reference;
        $product->label = $label;
		$product->description = $description;
		$product->price_net = $prix_produit_normal_HT;
		$product->vat_rate = $vat_rate;
		if ($barcode) {
			$product->barcode = $barcode;
			$product->barcode_type = '2'; // 2 = ean13
		}

		if ($exists["result"]->result_code == 'NOT_FOUND')
        {
			// Create new product
			echo "Create new product : <br>";
			var_dump($product);
			$result = $dolibarr->createProduct($product);
			
			if ($result["result"]->result_code == 'KO')
            {
				echo "Erreur de synchronisation : ".$result["result"]->result_label;
				echo "<br>product : " ;
				var_dump($product);
				echo "<br>result : " ;
				var_dump($result);
			}
		} else
        {
			// Update product
			echo "update product<br>";
			$oldProduct = $exists["product"];
			$product->id = $oldProduct->id;
			$result = $dolibarr->updateProduct($product);
			if ($result["result"]->result_code == 'KO')
            {
				echo "Erreur de synchronisation : ".$result["result"]->result_label;
			}
		}	

    }*/
}
/*
?>
