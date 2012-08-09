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
?>

<table border="0" width="770" class="rex-table">
  <tr>
    <td class="grey" style="padding:10px;">
<strong>Modul-Input:</strong><br /><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_jqlightbox['path'].'/modul-input.txt'));
?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="grey" style="padding:10px;">
<strong>Modul-Output:</strong><br /><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_jqlightbox['path'].'/modul-output.txt'));
?>
</textarea>
    </td>
  </tr>
</table>