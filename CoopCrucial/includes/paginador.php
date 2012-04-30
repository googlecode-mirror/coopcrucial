<?php		
	class Paginador{
		var $smarty;
		var $url;
		var $cont;
		var $regXPagina = 10;
		var $regMaxPaginador = 6;
		
		var $numPagina = 1;
		var $numPaginas;
		var $paginas;
		var $urlPaginador;
		
		function Paginador($cont, $url){
			global $smarty;
			$this->smarty = $smarty;
			$this->cont = $cont;
			$this->url = $url;
		}
		function paginar($numPagina, $regXPagina){
			$this->numPagina = $numPagina;
			$this->regXPagina = $regXPagina;
		
			$totalRegistros = $this->cont->getCount();			
			$numPaginas = ceil($totalRegistros/$this->regXPagina);

			if($this->numPagina > $numPaginas)
				$this->numPagina = $numPaginas;
				
			if($this->numPagina < 1)
				$this->numPagina = 1;			
			
			$regMaxPaginador = $this->regMaxPaginador;
			
			$paginas = array();
			$regMaxPaginador--;
			if($this->numPagina != 1 && $this->numPagina != $numPaginas)
				$regMaxPaginador--;
			$pInicio = 1;
			while(($pInicio < $this->numPagina - 2) && ($pInicio < $numPaginas) && ($pInicio+$regMaxPaginador < $numPaginas))
				$pInicio++;
			
			$pFin = $pInicio+$regMaxPaginador;
			if($pFin > $numPaginas)
				$pFin = $numPaginas;
		
			for($i = $pInicio; $i <= $pFin; $i++)
				$paginas[] = $i;		
		
			$this->cont->setLimit(($this->numPagina-1)*$this->regXPagina, $this->regXPagina);			
			
			$this->numPaginas = $numPaginas;
			$this->paginas = $paginas;
			$this->urlPaginador = $this->url;
			

			
			$this->smarty->assign("numPagina",$this->numPagina);
			$this->smarty->assign("numPaginas",$numPaginas);			
			$this->smarty->assign("paginas",$paginas);
			$this->smarty->assign("urlPaginador",$this->url);
		
		}
	}
?>