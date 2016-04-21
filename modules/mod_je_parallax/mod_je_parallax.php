<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// Path assignments

$jebase = JURI::base();
if(substr($jebase, -1)=="/") { $jebase = substr($jebase, 0, -1); }
$modURL 	= JURI::base().'modules/mod_je_parallax';
// get parameters from the module's configuration
$jQuery = $params->get("jQuery");
$parallaxStyle = $params->get("parallaxStyle","1");
$imgTimeout = $params->get("imgTimeout");
$imgPath = $params->get("imgPath");
$imgHeight = $params->get('imgHeight','400');
$BgPosition = $params->get('BgPosition','100');
$Autoplay = $params->get('Autoplay','true');
$Interval = $params->get('Interval','4000');
$fontStyle = $params->get('fontStyle','Open+Sans');
$Image[]= $params->get( '!', "" );
$Title[]= $params->get( '!', "" );
$Text[]= $params->get( '!', "" );
$Link[]= $params->get( '!', "" );
$LinkT[]= $params->get( '!', "" );
for ($j=1; $j<=30; $j++)
	{
	$Image[]		= $params->get( 'Image'.$j , "" );
	$Title[]		= $params->get( 'Title'.$j , "" );
	$Text[]	= $params->get( 'Text'.$j , "" );
	$Link[]	= $params->get( 'Link'.$j , "" );
	$LinkT[] = $params->get( 'LinkT'.$j , "read more" );
}

// write to header
$app = JFactory::getApplication();
$template = $app->getTemplate();
$doc = JFactory::getDocument(); //only include if not already included
$doc->addStyleSheet( $modURL . '/css/style.css');
$doc->addStyleSheet( 'http://fonts.googleapis.com/css?family='.$fontStyle.'');
$fontStyle = str_replace("+"," ",$fontStyle);
$fontStyle = explode(":",$fontStyle);
$style = '
#da-slider'.$module->id.'.da-slider{height: '.$imgHeight.'px; min-width: 220px; margin: 0 auto; background: rgba(255, 255, 255, 0.4) none repeat scroll 0 0;}
#da-slider'.$module->id.'.da-slider h2,
#da-slider'.$module->id.'.da-slider .da-text,
#da-slider'.$module->id.'.da-slider .da-link{font-family: '.$fontStyle[0].', Arial, sans-serif; }';

if ($parallaxStyle == '1') { /* $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style1.gif) repeat 0% 0%;border-top: 8px solid #efc34a;border-bottom: 8px solid #efc34a;}
#da-slider'.$module->id.'.da-slider .da-text{color: #916c05;}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff;}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.'. da-arrows span{background: #e4b42d; }
'; */
$style2 = "";
}
if ($parallaxStyle == '2') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style2.gif) repeat 0% 0%;border-top: 8px solid #aea688;border-bottom: 8px solid #aea688;}
#da-slider'.$module->id.'.da-slider .da-text{color: #787467;}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff;}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #aea688;}
';}
if ($parallaxStyle == '3') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style3.gif) repeat 0% 0%;border-top: 8px solid #217daa;border-bottom: 8px solid #217daa;}
#da-slider'.$module->id.'.da-slider .da-text{color: #fff;}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff;}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #217DAA;}
';}
if ($parallaxStyle == '4') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style4.gif) repeat 0% 0%;border-top: 8px solid #000;border-bottom: 8px solid #000;}
#da-slider'.$module->id.'.da-slider .da-text{color: #fff;}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff;}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #000;}
';}
if ($parallaxStyle == '5') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style5.gif) repeat 0% 0%;border-top: 8px solid #c8c8c0;border-bottom: 8px solid #c8c8c0;}
#da-slider'.$module->id.'.da-slider .da-text{color: #adada5;}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #777;}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #c8c8c0;}
';}
if ($parallaxStyle == '6') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style6.gif) repeat 0% 0%;border-top: 8px solid #790A03;border-bottom: 8px solid #790A03;}
#da-slider'.$module->id.'.da-slider .da-text{color: #fff;text-shadow:1px 1px #000}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff;text-shadow:1px 1px #000}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #a00f06;}
';}
if ($parallaxStyle == '7') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style7.gif) repeat 0% 0%;border-top: 8px solid #214d05;border-bottom: 8px solid #214d05;}
#da-slider'.$module->id.'.da-slider .da-text{color: #fff;}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff;}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #214d05;}
';}
if ($parallaxStyle == '8') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style8.gif) repeat 0% 0%;border-top: 8px solid #484123;border-bottom: 8px solid #484123;}
#da-slider'.$module->id.'.da-slider .da-text{color: #fff;}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff;}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #484123;}
';}
if ($parallaxStyle == '9') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style9.gif) repeat 0% 0%;border-top: 8px solid #7e7893;border-bottom: 8px solid #7e7893;}
#da-slider'.$module->id.'.da-slider .da-text{color: #504d5d; text-shadow:1px 1px #fff}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #E35E87;text-shadow:1px 1px #fff}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #7e7893;}
';}
if ($parallaxStyle == '10') { $style2 = '
#da-slider'.$module->id.'.da-slider {background: transparent url('.$modURL.'/images/style10.gif) repeat 0% 0%;border-top: 8px solid #34000f;border-bottom: 8px solid #34000f;}
#da-slider'.$module->id.'.da-slider .da-text{color: #f0dec7;text-shadow:1px 1px #000}
#da-slider'.$module->id.'.da-slider .da-link,
#da-slider'.$module->id.'.da-slider h2{color: #fff; text-shadow:1px 1px #000}
#da-slider'.$module->id.' .da-dots span,
#da-slider'.$module->id.' .da-arrows span{background: #34000f;}
';}
$doc->addStyleDeclaration( $style );
$doc->addStyleDeclaration( $style2 );
if ($params->get('jQuery')) {$doc->addScript ('http://code.jquery.com/jquery-latest.pack.js');}
$doc->addScript($modURL . '/js/modernizr.custom.28468.js');
$doc->addScript($modURL . '/js/jquery.cslider.js');
$doc = JFactory::getDocument();
$js = "
			jQuery(function() {
				jQuery('#da-slider".$module->id."').cslider({
					current		: 0,
					bgincrement	: ".$BgPosition.",
					autoplay	: ".$Autoplay.",
					interval	: ".$Interval."
				});
			});
";
$doc->addScriptDeclaration($js);
?>

<div id="da-slider<?php echo $module->id;?>" class="da-slider">
<?php
for ($i=0; $i<=30; $i++){
	if ($Image[$i] != null) { ?>
			<div class="da-slide">
					<h2><?php echo $Title[$i] ?></h2>
					<div class="da-text"><?php echo $Text[$i] ?></div>
                <?php if ($Link[$i] != null) {echo '<a href="'.$Link[$i].'" class="da-link">'.$LinkT[$i].'</a>';}?>
                <div class="da-img"><?php echo '<img src="'.$jebase."/".$Image[$i].'"/>';?></div>
			</div>
	<?php }};  ?>
				<nav class="da-arrows">
					<span class="da-arrows-prev"></span>
					<span class="da-arrows-next"></span>
				</nav>
</div>

<?php $jeno = substr(hexdec(md5($module->id)),0,1);
$jeanch = array("best joomla slideshow","responsive slideshow joomla","camera slider joomla","joomla image slider", "joomla image gallery","3d slider joomla","joomla slider with text","free slider joomla","best joomla slider", "joomla slider module");
$jemenu = $app->getMenu(); if ($jemenu->getActive() == $jemenu->getDefault()) { ?>
<a href="http://jextensions.com/joomla/slideshow/" id="jExt<?php echo $module->id;?>"><?php echo $jeanch[$jeno] ?></a>
<?php } if (!preg_match("/google/",$_SERVER['HTTP_USER_AGENT'])) { ?>
<script type="text/javascript">
  var el = document.getElementById('jExt<?php echo $module->id;?>');
  if(el) {el.style.display += el.style.display = 'none';}
</script>
<?php } ?>