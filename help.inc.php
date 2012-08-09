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

	include('config.inc.php');
	if (!isset($rxa_jqlightbox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		return;
	}
		
	echo $rxa_jqlightbox['i18n']->msg('text_help_title');
	$i=1;
	while ($rxa_jqlightbox['i18n']->msg('text_help_'.$i)<>'[translate:text_help_'.$i.']') {
		echo $rxa_jqlightbox['i18n']->msg('text_help_'.$i);
		$i++;
		if ($i>10) { break; }
	}
?>
