<?php
/**
 * --------------------------------------------------------------------
 *
 * Redaxo Addon: jQueryLightbox
 * Version: 1.1, 19.03.2008
 * 
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 * 
 * Verwendet wird das Script von Leandro Vieira Pinho
 * http://leandrovieira.com/projects/jquery/lightbox/
 *
 * --------------------------------------------------------------------
 */

	// Name des Addons und Pfade
	unset($rxa_jqlightbox);
	$rxa_jqlightbox['name'] = 'jquerylightbox';

	$REX['ADDON']['version'][$rxa_jqlightbox['name']] = '1.1';
	$REX['ADDON']['author'][$rxa_jqlightbox['name']] = 'Andreas Eberhard';

	$rxa_jqlightbox['path'] = $REX['INCLUDE_PATH'].'/addons/'.$rxa_jqlightbox['name'];
	$rxa_jqlightbox['basedir'] = dirname(__FILE__);
	$rxa_jqlightbox['lang_path'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_jqlightbox['name'] .'/lang';
	$rxa_jqlightbox['sourcedir'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_jqlightbox['name'] .'/'. $rxa_jqlightbox['name'];
	$rxa_jqlightbox['filesdir'] = $REX['HTDOCS_PATH'].'files/'.$rxa_jqlightbox['name'];
	$rxa_jqlightbox['meldung'] = '';
	$rxa_jqlightbox['rexversion'] = isset($REX['VERSION']) ? $REX['VERSION'] . $REX['SUBVERSION'] : $REX['version'] . $REX['subversion'];

/**
 * --------------------------------------------------------------------
 * Nur im Backend
 * --------------------------------------------------------------------
 */
	if (!$REX['GG']) {
		// Sprachobjekt anlegen
		$rxa_jqlightbox['i18n'] = new i18n($REX['LANG'],$rxa_jqlightbox['lang_path']);

		// Anlegen eines Navigationspunktes im REDAXO Hauptmenu
		$REX['ADDON']['page'][$rxa_jqlightbox['name']] = $rxa_jqlightbox['name'];
		// Namensgebung für den Navigationspunkt
		$REX['ADDON']['name'][$rxa_jqlightbox['name']] = $rxa_jqlightbox['i18n']->msg('menu_link');

		// Berechtigung für das Addon
		$REX['ADDON']['perm'][$rxa_jqlightbox['name']] = $rxa_jqlightbox['name'].'[]';
		// Berechtigung in die Benutzerverwaltung einfügen
		$REX['PERM'][] = $rxa_jqlightbox['name'].'[]';
	}

/**
 * --------------------------------------------------------------------
 * Outputfilter für das Frontend
 * --------------------------------------------------------------------
 */
	if ($REX['GG'])
	{
		rex_register_extension('OUTPUT_FILTER', 'jquerylightbox_opf');

		// Prüfen ob die aktuelle Kategorie mit der Auswahl übereinstimmt
		function jquerylightbox_check_cat($acat, $aart, $subcats, $lightbox_cats)
		{

			// prüfen ob Kategorien ausgewählt
			if (!is_array($lightbox_cats)) return false;

			// aktuelle Kategorie in den ausgewählten dabei?
			if (in_array($acat, $lightbox_cats)) return true;

			// Prüfen ob Parent der aktuellen Kategorie ausgewählt wurde
			if ( ($acat > 0) and ($subcats == 1) )
			{
				$cat = OOCategory::getCategoryById($acat);
				while($cat = $cat->getParent())
				{
					if (in_array($cat->_id, $lightbox_cats)) return true;
				}
			}

			// evtl. noch Root-Artikel prüfen
			if (strstr(implode('',$lightbox_cats), 'r'))
			{
				if (in_array($aart.'r', $lightbox_cats)) return true;
			}

			// ansonsten keine Ausgabe!
			return false;
		}
		
      // Output-Filter
		function jquerylightbox_opf($params)
		{
			global $REX, $REX_ARTICLE;
			global $rxa_jqlightbox;

			$content = $params['subject'];
			
			if ( !strstr($content,'</head>') or !file_exists($rxa_jqlightbox['path'].'/'.$rxa_jqlightbox['name'].'.ini')
			 or ( strstr($content,'<script type="text/javascript" src="files/jquerylightbox/jquery.lightbox-0.4.js"></script>') and strstr($content,'<link rel="stylesheet" href="files/jquerylightbox/jquery.lightbox-0.4.css" type="text/css" media="screen" />') ) ) {
				return $content;
			}

   		// Einstellungen aus ini-Datei laden
			if (($lines = file($rxa_jqlightbox['path'].'/'.$rxa_jqlightbox['name'].'.ini')) === false) {
				return $content;
			} else {
				$va = explode(',', trim($lines[0]));
				$allcats = trim($va[0]);
				$subcats = trim($va[1]);
				$lightbox_cats = array();
				$lightbox_cats = unserialize(trim($lines[1]));
			}

			// aktuellen Artikel ermitteln
			$artid = isset($_GET['article_id']) ? $_GET['article_id']+0 : 0;
			if ($artid==0) {
				$artid = $REX_ARTICLE->getValue('article_id')+0;
			}
			if ($artid==0) { $artid = $REX['START_ARTICLE_ID']; }

			if (!$artid) { return $content; }

			$article = OOArticle::getArticleById($artid);
			if (!$article) { return $content; }

			// aktuelle Kategorie ermitteln
			if ( in_array($rxa_jqlightbox['rexversion'], array('3.11')) ) {
				$acat = $article->getCategoryId();
			}
			if ( in_array($rxa_jqlightbox['rexversion'], array('32', '40', '41', '42')) ) {
				$cat = $article->getCategory();
				if ($cat) {
					$acat = $cat->getId();
				}
			}
			// Wenn keine Kategorie ermittelt wurde auf -1 setzen für Prüfung in jquerylightbox_check_cat, Prüfung auf Artikel im Root
			if (!$acat) { $acat = -1; }

         // Array anlegen falls keine Kategorien ausgewählt wurden
			if (!is_array($lightbox_cats)){
				$lightbox_cats = array();
			}

			// Code für Lightbox im head-Bereich ausgeben
			if ( ($allcats==1) or (jquerylightbox_check_cat($acat, $artid, $subcats, $lightbox_cats) == true) )
			{
				$rxa_jqlightbox['output'] = '	<!-- Addon jQueryLightbox '.$REX['ADDON']['version'][$rxa_jqlightbox['name']].' -->'."\n";
				$rxa_jqlightbox['output'] .= '	<script type="text/javascript" src="files/jquerylightbox/jquery-1.2.2.pack.js"></script>'."\n";
				$rxa_jqlightbox['output'] .= '	<script type="text/javascript" src="files/jquerylightbox/jquery.lightbox-0.4.js"></script>'."\n";
				$rxa_jqlightbox['output'] .= '	<link rel="stylesheet" href="files/jquerylightbox/jquery.lightbox-0.4.css" type="text/css" media="screen" />'."\n";
				$content = str_replace('</head>', $rxa_jqlightbox['output'].'</head>', $content);
			}

			return $content;
		}

	}
?>