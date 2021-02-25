<?
if(!function_exists('str_split')) {
  function str_split($string, $split_length = 1) {
    $array = explode("\r\n", chunk_split($string, $split_length));
    array_pop($array);
    return $array;
  }
}
function drawbox($pos, $pattern,$image,$color,$font,$start=0){
   $black_color = imagecolorallocate($image, 0, 0, 0);
   $posy = 50*(floor($pos/10));
   $posx = 50*($pos%10);
   if(floor($pattern/10)==1){//draw underline
     imageline ($image , $posx , $posy+50 , $posx+50 , $posy+50 , $black_color );
   }
   if($pattern%10==1){//draw rightwall
     imageline ($image , $posx+50 , $posy , $posx+50 , $posy+50 , $black_color );
   }
   if($start>0){
     imagestring($image, $font, $posx+18, $posy+8,  $pos, $color);
     imagestring($image, $font, $posx+5, $posy+28,  'start', $color);
   }elseif($start<0){
     imagestring($image, $font, $posx+18, $posy+8,  $pos, $color);
     imagestring($image, $font, $posx+12, $posy+28,  'end', $color);
   }else{
      imagestring($image, $font, $posx+18, $posy+18,  $pos, $color);
   }
}

function mazeAndSolution(){
  $dim_x = 10;
  $dim_y = 10;
  $cell_count = $dim_x*$dim_y;
  $moves = array();
  for($x=0;$x<$cell_count;$x++){
    $maze[$x] = "01111"; // visted, NSEW
  }
  $pos = rand(0,$cell_count-1);
  $maze[$pos]{0} = 1;
  $visited ++;
  $mazepic = "";
  // determine possible directions
  while($visited<$cell_count){
    $possible = "";
    if((floor($pos/$dim_x)==floor(($pos-1)/$dim_x)) and ($maze[$pos-1]{0}==0)){
      $possible .= "W";
    }
    if((floor($pos/$dim_x)==floor(($pos+1)/$dim_x)) and ($maze[$pos+1]{0}==0)){
      $possible .= "E";
    }
    if((($pos+$dim_x)<$cell_count) and ($maze[$pos+$dim_x]{0}==0)){
      $possible .= "S";
    }
    if((($pos-$dim_x)>=0) and ($maze[$pos-$dim_x]{0}==0)){
      $possible .= "N";
    }
    if($possible){
      $visited ++;
      array_push($moves,$pos);
      $direction = $possible{rand(0,strlen($possible)-1)};
      switch($direction){
        case "N":
          $maze[$pos]{1} = 0;
          $maze[$pos-$dim_x]{2} = 0;
          $pos -= $dim_x;
          break;
        case "S":
          $maze[$pos]{2} = 0;
          $maze[$pos+$dim_x]{1} = 0;
          $pos += $dim_x;
          break;
        case "E":
          $maze[$pos]{3} = 0;
          $maze[$pos+1]{4} = 0;
          $pos ++;
          break;
        case "W":
          $maze[$pos]{4} = 0;
          $maze[$pos-1]{3} = 0;
          $pos --;
          break;
      }
      $maze[$pos]{0} = 1; //mark as visited
    }else{
      $pos = array_pop($moves);
    }
  }
  $html .= "<table style = \"border:0px solid black\"; cellspacing = \"0\" cellpadding = \"0\">";
  for($x=0;$x<$cell_count;$x++){
    if($x % $dim_x == 0){
      $html .= "<tr>";
    }
    $maze[$x]{0} = 0;
    $style = substr($maze[$x],1);
    $mazepicture[$x] = substr($maze[$x],2,2);
    $mazepic .= $mazepicture[$x] ;
/*    if($x==$mazestart){
      $html.= "<td class = \"$style\"><font color=\"red\">$x</font></td>";
    }elseif($x==$mazeend){
      $html.= "<td class = \"$style\"><font color=\"blue\">$x</font></td>";
    }else{
      $html.= "<td class = \"$style\">$x</td>";
    }*/
    $html.= "<td class = \"$style\">$x</td>";
    if(($x % $dim_x) == ($dim_x-1)){
      $html .= "</tr>";
    }
  }
  $html .= "</table>";

  while(count($solution)<20 || count($solution)>25){//start while solution
    $mazesol = $maze;
    $mazestart = rand(0,$cell_count-1);
    $mazeend = $mazestart;
    while(($mazeend-$mazestart)<40 && ($mazeend-$mazestart)>-40){
      $mazeend = rand(0,$cell_count-1);
    }
    if($mazeend<$mazestart){
      $temp = $mazeend;
      $mazeend = $mazestart;
      $mazestart = $temp;
    }
    unset($solution);
    $solution = array();
    array_unshift($solution, $mazestart);
    $tempcount=0;
    $nextcell = $mazestart;
    while($solution[0] != $mazeend && $nextcell >= 0 && $nextcell < 100){
      $mazesol[$solution[0]]{0} = 1; // Mark the current cell as 'Visited'
      if($mazesol[$solution[0]]{1} == 0 && $mazesol[($solution[0]-10)]{0} == 0){ // if N no wall and not visit go N
        $nextcell = $solution[0]-10; // next cell = N
        $mazesol[$solution[0]]{1} = 1;
        array_unshift($solution, $nextcell); // add the current cell to the stack
      }elseif($mazesol[$solution[0]]{2} == 0 && $mazesol[($solution[0]+10)]{0} == 0){
        $nextcell = $solution[0]+10;
        $mazesol[$solution[0]]{2} = 1;
        array_unshift($solution, $nextcell);
      }elseif($mazesol[$solution[0]]{3} == 0 && $mazesol[($solution[0]+1)]{0} == 0){
        $nextcell = $solution[0]+1;
        $mazesol[$solution[0]]{3} = 1;
        array_unshift($solution, $nextcell);
      }elseif($mazesol[$solution[0]]{4} == 0 && $mazesol[($solution[0]-1)]{0} == 0){
        $nextcell = $solution[0]-1;
        $mazesol[$solution[0]]{4} = 1;
        array_unshift($solution, $nextcell);
      }else{
        array_shift($solution);
      }
    }
    krsort($solution);
  }// end while solution

  $solutiontext = '';
  foreach($solution as $walker){
    $solutiontext .= $walker.'-';
  }
  $solutiontext = substr($solutiontext, 0, -1);

  return array($html,$mazestart,$mazeend,$mazepic,$solutiontext);
}


if(!empty($_GET['mazedata'])){
  header("Content-type: image/png");
  $data = str_split($_GET['mazedata'],2);
  $im = @imagecreate(500, 500)
      or die("Cannot Initialize new GD image stream");
  $background_color = imagecolorallocate($im, 255, 255, 255);
  $black_color = imagecolorallocate($im, 0, 0, 0);
  $red_color = imagecolorallocate($im, 255, 0, 0);
  $blue_color = imagecolorallocate($im, 0, 0, 255);
  imageline ($im , 0 , 0 , 0 , 499 , $black_color );
  imageline ($im , 0 , 0 , 499 , 0 , $black_color );
  imageline ($im , 0 , 499 , 499 , 499 , $black_color );
  imageline ($im , 499 , 0 , 499 , 499 , $black_color );
  for($i=0;$i<count($data);$i++){
    $color=($i==$_GET['s'])?$red_color:(($i==$_GET['e'])?$blue_color:$black_color);
    $font=($i==$_GET['s'])?5:(($i==$_GET['e'])?5:4);
    $start=($i==$_GET['s'])?1:(($i==$_GET['e'])?-1:0);
    drawbox($i, $data[$i],$im,$color,$font,$start);
  }
  imagepng($im);
  imagedestroy($im);

}else{
  list($html,$mazestart,$mazeend,$mazepic,$solutiontext) = mazeAndSolution();
  setcookie('solutiontext',$solutiontext);
?>
<html>
<head>
<style>
body{line-height:25px;}
td{text-align:center;}
.0000{width:50px;height:50px;border:0px;}
.0001{width:50px;height:50px;border-left:1px solid black;}
.0100{width:50px;height:50px;border-bottom:1px solid black;}
.0101{width:50px;height:50px;border-bottom:1px solid black;border-left:1px solid black;}
.0010{width:50px;height:50px;border-right:1px solid black;}
.0011{width:50px;height:50px;border-right:1px solid black;border-left:1px solid black;}
.0110{width:50px;height:50px;border-right:1px solid black;border-bottom:1px solid black;}
.0111{width:50px;height:50px;border-right:1px solid black;border-bottom:1px solid black;border-left:1px solid black;}
.1000{width:50px;height:50px;border-top:1px solid black;}
.1001{width:50px;height:50px;border-top:1px solid black;border-left:1px solid black;}
.1100{width:50px;height:50px;border-top:1px solid black;border-bottom:1px solid black;}
.1101{width:50px;height:50px;border-top:1px solid black;border-bottom:1px solid black;border-left:1px solid black;}
.1010{width:50px;height:50px;border-top:1px solid black;border-right:1px solid black;}
.1011{width:50px;height:50px;border-top:1px solid black;border-right:1px solid black;border-left:1px solid black;}
.1110{width:50px;height:50px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;}
.1111{width:50px;height:50px;border-top:1px solid black;border-right:1px solid black;border-bottom:1px solid black;border-left:1px solid black;}
strong{color:red;}
</style>
</head>
<body>
<table>
<tr>
  <td><?php $html .= "<font color=\"red\">".$mazestart." = start point</font><BR><font color=\"blue\"> ".$mazeend." = end point</font>"; //echo $html; ?></td>
  <td>
<img src="maze.php?mazedata=<?echo $mazepic;?>&s=<?echo $mazestart;?>&e=<?echo $mazeend;?>"><BR><font color="red"><?echo $mazestart;?> = start point</font><BR><font color="blue"> <?echo $mazeend;?> = end point</font>
</td>
</tr>
</table>
  <HR>
  <?php //echo $solutiontext; ?>
</body>
</html>
<?}?>