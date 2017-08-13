
<h2><a class="brown" href="http://blog.twobeerdudes.com">Sips Blog</a></h2>
<ul>
<?php
for ($i = 0; $i < BLOG_RSS_COUNT; $i++) {
?>
	<li>
		<p>
			<span class="label label-default"><?php echo date('m/d/y', strtotime($xml->pubdate[$i])); ?></span>
			<span class="blogLink"><a href="<?php echo $xml->link[$i]; ?>"><?php echo $xml->title[$i]; ?></a></span>
			<span class="mediumgray">by <?php echo $xml->creator[$i]; ?></span>
		</p>
		<p><?php echo str_replace('[...]', '', $xml->description[$i]); ?></p>
	</li>
<?php
}

////<p>' . date('M d, Y', strtotime($result['pubdate'][$i])) . ' <a href="' . $result['link'][$i] . '"><span class="bold">' . $result['title'][$i] . '</bold></a> by ' . $result['creator'][$i] . '</p>
?>
</ul>