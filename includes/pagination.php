<?php
/**
 * @link: http://www.Awcore.com/dev
 * Amit Andipara Published on 28 Nov 2015 on https://www.youtube.com/watch?v=UHh5-bo2taM
 */
   function pagination($query, $per_page = 10,$page = 1, $url = '?'){
    	$query = "SELECT COUNT(*) as `num` FROM {$query}";

    	$db = new myDB();
      // echo "<b>pagination</b>$query<br>";
		$res = $db->myQuery($query);
		$res = $res->fetch_assoc();
    	$total = $res['num'];
        $adjacents = "2";

    	$page = ($page == 0 ? 1 : $page);
    	$start = ($page - 1) * $per_page;

    	$prev = $page - 1;
    	$next = $page + 1;
        $lastpage = ceil($total/$per_page);
    	$lpm1 = $lastpage - 1;

    	$pagination = "";
    	if($lastpage > 1)
    	{
    		$pagination .= "<ul class='pagination'>";
                    $pagination .= "<li class='details' style='margin-top:-2px'>Page $page of $lastpage</li>";
    		if ($lastpage < 7 + ($adjacents * 2))
    		{
    			for ($counter = 1; $counter <= $lastpage; $counter++)
    			{
    				if ($counter == $page)
    					$pagination.= "<li><a href='{$url}pn=$counter' class='current'>$counter</a></li>";
    				else
    					$pagination.= "<li><a href='{$url}pn=$counter'>$counter</a></li>";
    			}
    		}
    		elseif($lastpage > 5 + ($adjacents * 2))
    		{
    			if($page < 1 + ($adjacents * 2))
    			{
    				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a href='{$url}pn=$counter' class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}pn=$counter'>$counter</a></li>";
    				}
    				$pagination.= "<li class='dot'>...</li>";
    				$pagination.= "<li><a href='{$url}pn=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}pn=$lastpage'>$lastpage</a></li>";
    			}
    			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
    			{
    				$pagination.= "<li><a href='{$url}pn=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}pn=2'>2</a></li>";
    				$pagination.= "<li class='dot'>...</li>";
    				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}pn=$counter'>$counter</a></li>";
    				}
    				$pagination.= "<li class='dot'>..</li>";
    				$pagination.= "<li><a href='{$url}pn=$lpm1'>$lpm1</a></li>";
    				$pagination.= "<li><a href='{$url}pn=$lastpage'>$lastpage</a></li>";
    			}
    			else
    			{
    				$pagination.= "<li><a href='{$url}pn=1'>1</a></li>";
    				$pagination.= "<li><a href='{$url}pn=2'>2</a></li>";
    				$pagination.= "<li class='dot'>..</li>";
    				for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
    				{
    					if ($counter == $page)
    						$pagination.= "<li><a class='current'>$counter</a></li>";
    					else
    						$pagination.= "<li><a href='{$url}pn=$counter'>$counter</a></li>";
    				}
    			}
    		}

    		if ($page < $counter - 1){
    			$pagination.= "<li><a href='{$url}pn=$next'>Next</a></li>";
                $pagination.= "<li><a href='{$url}pn=$lastpage'>Last</a></li>";
    		}else{
    			$pagination.= "<li><a class='current'>Next</a></li>";
                $pagination.= "<li><a class='current'>Last</a></li>";
            }
    		$pagination.= "</ul>\n";
    	}


        return $pagination;
    }

    /* Mariusz Lewandowski 2018 function helper, sets up for pagination
    * @table tabe for the sql guery
    * @orderBy table column to order data by
    * @limitt amount of rows. Default = PAG wich is PHP global set in config.php file;
    * @order either asc or desc
    * @sql query string, beggining portion of the qery. defaults to "select * from $table"
    * @return array where: "sql" formated query,
    *                      "statment" for pagination function "query" value
    *                      "limit" amount of rows. Default = PAG wich is PHP global set in config.php file;
    *                      "page" paginaton current page number
    *                      "link" current website link
    *                      "dev" development output;
    */
  function paginationInit($table, $orderBy, $limitt=null, $order=null, $sql=null){
    $dev = DEV ? '>>>>>>>>>>>>>>>>>><b>inside function paginationInit</b><<<<<<<<<<<<<<<<<<<<<br>':'';
    // setting up the default values
    if ($limitt === null) $limitt = PAG;
    if ($order === null) $order = 'asc'; // either asc or desc
    if ($sql === null) $sql = "select * from $table";

  	$page = (int) (!isset($_GET["pn"]) ? 1 : $_GET["pn"]);  $dev .= DEV ? "page: $page<br>":'';
  	$limit = $limitt; //if you want to dispaly 10 records per page then you have to change here
  	$startpoint = ($page * $limit) - $limit;  $dev .= DEV ? "startpoint: $startpoint<br>":'';
  	$order = "order by $orderBy $order";
  	$statement = $table; //you have to pass your query over here
  	$sql = "$sql {$order} LIMIT {$startpoint} , {$limit}";  $dev .= DEV ? "$sql <br>":'';
  	$statement = "{$statement} {$order}"; $dev .= DEV ? "statement: $statement<br>":'';
  	$sq = $_SERVER["QUERY_STRING"];  $dev .= DEV ? "sq: $sq<br>":'';
  	$sq = strstr($sq, 'pn', true) === false ? $sq : rtrim(strstr($sq, 'pn', true), "?&") ;  $dev .= "sq trimmed: $sq<br>";
  	$sq = $sq == '' ? '?': '?'.$sq.'&';  $dev .= DEV ? 'url: '.$_SERVER["PHP_SELF"].$sq."<br>":'';
  	$s = ((!empty($_SERVER['HTTPS'])) ? "s" : "");
  	$link = "http".$s."://".$_SERVER['SERVER_NAME'].$_SERVER["PHP_SELF"].$sq;  $dev .= DEV ? "link : $link <br>":'';
    $dev .= DEV ? '<<<<<<<<<<<<<<<<<<b>end function paginationInit</b>>>>>>>>>>>>>>>>>>>>>><br>':'';
  	return array("sql"=>$sql, "statement"=>$statement, "limit"=>$limit, "page"=>$page, "link"=>$link, "dev"=>$dev);
  }
?>
