<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
if (isset($seo)) {
	if (property_exists($seo, 'metaDescription') && property_exists($seo, 'metaKeywords')) {
?>
		<meta name="description" content="<?php echo $seo->metaDescription; ?>">
		<meta name="keywords" content="<?php echo $seo->metaKeywords; ?>">
		<meta name="robots" content="index,follow">
		<meta name="revisit-after" content="2 days">
<?php
	}

	if (property_exists($seo, 'pageTitle')) {
?>
		<title><?php echo $seo->pageTitle; ?></title>
<?php
	}
}
?>
        <link rel="stylesheet" href="/<?php echo CSS_PATH; ?>/ui-lightness/jquery-ui.min.css">
        <link rel="stylesheet" href="/<?php echo CSS_PATH; ?>/bootstrap.min.css">
        <link rel="stylesheet" href="/<?php echo CSS_PATH; ?>/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="/<?php echo CSS_PATH; ?>/bootstrap-slider.min.css">
		<link rel="stylesheet" href="/<?php echo CSS_PATH; ?>/datepicker.css">
        <link rel="stylesheet" href="/<?php echo CSS_PATH; ?>/site.css">
<?php
if (isset($css) && is_array($css) && count($css)) {
	foreach ($css as $cs) {
?>
		<link rel="stylesheet" href="/<?php echo CSS_PATH; ?>/<?php echo $cs; ?>">
<?php		
	}
}
?>
		<script src="/<?php echo JS_PATH; ?>/jquery-1.11.3.min.js"></script>
		<meta name="google-site-verification" content="VP1Hrk8DuL7IuzE5vogU5iSpyXUvbrFCLnclLsdqFK0" />
	</head>
	<body>
