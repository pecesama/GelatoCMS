<?php
/*
	Digg-Style Pagination
	
	Autor: jason (http://www.strangerstudios.com/blog/2006/12/29/pagination-update/)
	Update: Victor De la Rocha (http://www.mis-algoritmos.com/2006/11/23/paginacion-al-estilo-digg-y-sabrosus/)
*/
function pagination($total_pages,$limit,$page=1,$pagina="default.php",$adjacents=2){
	/*
		$adjacents -> How many adjacent pages should be shown on each side?
		$total_pages -> items
		$limit  -> how many items to show per page
		$page -> Current page
		$pagina -> Pagina
	*/
		if(!is_array($pagina)&&!strstr($pagina,"?"))
			$pagina = $pagina."?";
			
		/* Setup vars for query. */
		if($page) 
			$start = ($page - 1) * $limit; 			//first item to display on this page
		else
			$start = 0;								//if no page var is given, set start to 0
	
		/* Setup page vars for display. */
		if ($page == 0) $page = 1;					//if no page var is given, default to 1.
		$prev = $page - 1;							//previous page is page - 1
		$next = $page + 1;							//next page is page + 1
		$lastpage = ceil($total_pages/$limit);		//lastpage is = total pages / items per page, rounded up.
		$lpm1 = $lastpage - 1;						//last page minus 1
		
		/* 
			Now we apply our rules and draw the pagination object. 
			We're actually saving the code to a variable in case we want to draw it more than once.
		*/
		$pagination = "";
		if($lastpage > 1)
		{	
			$pagination .= "<div class=\"pagination\">";
			//previous button
			if ($page > 1){
				$pagina_uf = is_array($pagina)?str_replace($pagina[1],$prev,$pagina[0]):$pagina."&page=$prev";
				$pagination.= "<a href=\"".$pagina_uf."\"> Previous</a>";
			}else
				$pagination.= "<span class=\"disabled\"> Previous</span>";	
			
			//pages	
			if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
			{	
				for ($counter = 1; $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination.= "<span class=\"current\">$counter</span>";
					else{
							$pagina_uf = is_array($pagina)?str_replace($pagina[1],$counter,$pagina[0]):$pagina."&page=$counter";
							$pagination.= "<a href=\"".$pagina_uf."\">$counter</a>";					
						}
				}
			}
			elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
			{
				//close to beginning; only hide later pages
				if($page < 1 + ($adjacents * 2))		
				{
					for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else{
								$pagina_uf = is_array($pagina)?str_replace($pagina[1],$counter,$pagina[0]):$pagina."&page=$counter";
								$pagination.= "<a href=\"".$pagina_uf."\">$counter</a>";					
							}
					}
					$pagination.= "...";
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],$lpm1,$pagina[0]):$pagina."&page=$lpm1";
					$pagination.= "<a href=\"".$pagina_uf."\">$lpm1</a>";
				
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],$counter,$pagina[0]):$pagina."&page=$counter";
					$pagination.= "<a href=\"".$pagina_uf."\">$lastpage</a>";		
				}
				//in middle; hide some front and some back
				elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
				{
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],1,$pagina[0]):$pagina."&page=1";
					$pagination.= "<a href=\"".$pagina_uf."\">1</a>";

					$pagina_uf = is_array($pagina)?str_replace($pagina[1],2,$pagina[0]):$pagina."&page=2";
					$pagination.= "<a href=\"".$pagina_uf."\">2</a>";
					$pagination.= "...";
					for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
					{
						if ($counter == $page)
								$pagination.= "<span class=\"current\">$counter</span>";
							else{
								$pagina_uf = is_array($pagina)?str_replace($pagina[1],$counter,$pagina[0]):$pagina."&page=$counter";
								$pagination.= "<a href=\"".$pagina_uf."\">$counter</a>";					
							}
					}
					$pagination.= "...";
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],$lpm1,$pagina[0]):$pagina."&page=$lpm1";
					$pagination.= "<a href=\"".$pagina_uf."\">$lpm1</a>";
					
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],$lastpage,$pagina[0]):$pagina."&page=$lastpage";
					$pagination.= "<a href=\"".$pagina_uf."\">$lastpage</a>";		
				}
				//close to end; only hide early pages
				else
				{
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],1,$pagina[0]):$pagina."&page=1";
					$pagination.= "<a href=\"".$pagina_uf."&page=1\">1</a>";
					
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],2,$pagina[0]):$pagina."&page=2";
					$pagination.= "<a href=\"".$pagina_uf."&page=2\">2</a>";
					$pagination.= "...";
					for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
					{
						if ($counter == $page)
							$pagination.= "<span class=\"current\">$counter</span>";
						else{
								$pagina_uf = is_array($pagina)?str_replace($pagina[1],$counter,$pagina[0]):$pagina."&page=$counter";
								$pagination.= "<a href=\"".$pagina_uf."\">$counter</a>";					
							}
					}
				}
			}
			
			//next button
			if ($page < $counter - 1) {
					$pagina_uf = is_array($pagina)?str_replace($pagina[1],$next,$pagina[0]):$pagina."&page=$next";
					$pagination.= "<a href=\"".$pagina_uf."\">Next </a>";
				}
			else
				$pagination.= "<span class=\"disabled\">Next </span>";
			$pagination.= "</div>\n";		
		}
		return $pagination;
	}
?>