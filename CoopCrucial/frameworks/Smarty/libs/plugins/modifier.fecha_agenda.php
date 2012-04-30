<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty plugin
 * -------------------------------------------------------------
 * Type:     modifier<br>
 * Name:     fecha_agenda<br>
 * Purpose:  Dar la fecha en el formato fecha inicio - fecha final
 * Example:  {$fechaInicio|fecha_agenda:"fechafinal"}
 * -------------------------------------------------------------
 */

function smarty_modifier_fecha_agenda($inicio,$final)
{
		$arrayMes=array("01"=>"Ene","02"=>"Feb","03"=>"Mar","04"=>"Abr","05"=>"May","06"=>"Jun","07"=>"Jul","08"=>"Ago","09"=>"Sep","10"=>"Oct","11"=>"Nov","12"=>"Dic");
		$mesInicio=$inicio[5].$inicio[6];
		$mesFinal=$final[5].$final[6];
		$anyoI=$inicio[2].$inicio[3];
		$anyoF=$final[2].$final[3];		
		//Mostrar formato de realizacion del evento 
		if($anyoI==$anyoF){
			if($mesInicio==$mesFinal){
				$diaEvento=$arrayMes[$mesInicio]." ".$inicio[8].$inicio[9]."-".$final[8].$final[9];
			}else{
				$diaEvento=$arrayMes[$mesInicio]." ".$inicio[8].$inicio[9]."-<br>".$arrayMes[$mesFinal]." ".$final[8].$final[9];
			}
		}else{
			$diaEvento=$arrayMes[$mesInicio]." ".$inicio[8].$inicio[9]."/".$anyoI."-<br>".$arrayMes[$mesFinal]." ".$final[8].$final[9]."/".$anyoF;
		}
		return $diaEvento;
}

/* vim: set expandtab: */
?>