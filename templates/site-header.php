<!DOCTYPE html>
<html dir="ltr" lang="en">
<head>
    <meta charset="utf-8">
    <title><?php e(@$title); ?></title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
<?php if (@$css) foreach ((array) $css as $css_url) { ?>
    <link rel="stylesheet" type="text/css" href="<?php e($css_url); ?>" />
<?php } ?>
<!--[if IE]>
    <link rel="stylesheet" type="text/css" href="css/ie.css" />
<![endif]-->
<?php if (@$feed_url) { ?>
    <link rel="alternate" type="application/atom+xml" href="<?php e($feed_url); ?>">
<?php } ?>
<?php if (@$scripts) foreach ((array) $scripts as $script_url) { ?>
    <script type="text/javascript" src="<?php e($script_url); ?>"></script>
<?php } ?>
</head>

<body id="top">
<?php if (@$feed_url) { ?>
    <div id="feedicon"><a href="<?php e($feed_url); ?>"><img src="/images/feed-icon-28x28.png" alt="RSS feed" title="Subscribe to RSS feed" /></a></div>
<?php } ?>
    <h1><?php e(@$title); ?></h1>
    <div id="content">
