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

	unset($rxa_jqlightbox);
	include('config.inc.php');

	if (!isset($rxa_jqlightbox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		$REX['ADDON']['install'][$rxa_jqlightbox['name']] = 0;
		return;
	}

   // Gültige REDAXO-Version abfragen
	if ( !in_array($rxa_jqlightbox['rexversion'], array('3.11', '32', '40', '41', '42')) ) {
		echo '<font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_jqlightbox['rexversion'].'</strong></font>';
		$REX['ADDON']['installmsg'][$rxa_jqlightbox['name']] = '<br /><br /><font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_jqlightbox['rexversion'].'</strong></font>';
		$REX['ADDON']['install'][$rxa_jqlightbox['name']] = 0;
		return;
	}

	// Verzeichnis files/jquerylightbox anlegen
	if ( !@is_dir($rxa_jqlightbox['filesdir']) ) {
		if ( !@mkdir($rxa_jqlightbox['filesdir']) ) {
			$rxa_jqlightbox['meldung'] .= $rxa_jqlightbox['i18n']->msg('error_createdir', $rxa_jqlightbox['filesdir']);
		}
	}

	// Dateien ins Verzeichnis files/jquerylightbox kopieren
	if ($dh = opendir($rxa_jqlightbox['sourcedir'])) {
		while ($el = readdir($dh)) {
			$rxa_jqlightbox['file'] = $rxa_jqlightbox['sourcedir'].'/'.$el;
			if ($el != '.' && $el != '..' && is_file($rxa_jqlightbox['file'])) {
				if ( !@copy($rxa_jqlightbox['file'], $rxa_jqlightbox['filesdir'].'/'.$el) ) {
					$rxa_jqlightbox['meldung'] .= $rxa_jqlightbox['i18n']->msg('error_copyfile', $el, $REX['HTDOCS_PATH'].'files/'.$rxa_jqlightbox['name'].'/');
				}
			}
		}
	} else {
		$rxa_jqlightbox['meldung'] .= $rxa_jqlightbox['i18n']->msg('error_readdir',$rxa_jqlightbox['sourcedir']);
	}
	
	// Evtl Ausgabe einer Meldung
	// $rxa_jqlightbox['meldung'] = 'Das Addon wurde nicht installiert, weil...';
	if ( $rxa_jqlightbox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_jqlightbox['name']] = '<br /><br />'.$rxa_jqlightbox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_jqlightbox['name']] = 0;
	} else {
	// Installation erfolgreich
		$REX['ADDON']['install'][$rxa_jqlightbox['name']] = 1;
	}
?>