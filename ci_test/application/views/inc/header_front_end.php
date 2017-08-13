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
        <link rel="stylesheet" href="/css/ui-lightness/jquery-ui.min.css">
        <!--<link rel="stylesheet" href="/css/ui-lightness/jquery-ui-1.10.4.custom.min.css">-->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="/css/bootstrap-slider.min.css">
		<!--<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">-->
		<link rel="stylesheet" href="/css/datepicker.css">
        <link rel="stylesheet" href="/css/site.css">
<?php
if (isset($css) && is_array($css) && count($css)) {
	foreach ($css as $cs) {
?>
		<link rel="stylesheet" href="/css/<?php echo $cs; ?>">
<?php		
	}
}
?>
	</head>
	<body>
