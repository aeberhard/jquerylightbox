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
		return;
	}

	// Dateien aus dem Ordner files/jquerylightbox löschen
	if (isset($rxa_jqlightbox['filesdir']) and ($rxa_jqlightbox['filesdir']<>'') and ($rxa_jqlightbox['name']<>'') ) {
		if ($dh = opendir($rxa_jqlightbox['filesdir'])) {
			while ($el = readdir($dh)) {
				$path = $rxa_jqlightbox['filesdir'].'/'.$el;
				if ($el != '.' && $el != '..' && is_file($path)) {
					@unlink($path);
				}
			}
		}
	}
	@closedir($dh);
	@rmdir($rxa_jqlightbox['filesdir']);
	
	// Evtl Ausgabe einer Meldung
	// De-Installation nicht erfolgreich
	if ( $rxa_jqlightbox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_jqlightbox['name']] = '<br /><br />'.$rxa_jqlightbox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_jqlightbox['name']] = 1;
	// De-Installation erfolgreich
	} else {
		$REX['ADDON']['install'][$rxa_jqlightbox['name']] = 0;
	}
?>