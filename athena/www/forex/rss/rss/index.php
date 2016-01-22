<?php
// Start counting time for the page load
$starttime = explode(' ', microtime());
$starttime = $starttime[1] + $starttime[0];

// Include SimplePie
// Located in the parent directory
include_once('../autoloader.php');
include_once('../idn/idna_convert.class.php');

// Create a new instance of the SimplePie object
$feed = new SimplePie();

//$feed->force_fsockopen(true);

if (isset($_GET['js']))
{
	SimplePie_Misc::output_javascript();
	die();
}

// Make sure that page is getting passed a URL
if (isset($_GET['feed']) && $_GET['feed'] !== '')
{
	// Strip slashes if magic quotes is enabled (which automatically escapes certain characters)
	if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc())
	{
		$_GET['feed'] = stripslashes($_GET['feed']);
	}

	// Use the URL that was passed to the page in SimplePie
	$feed->set_feed_url($_GET['feed']);
}

// Allow us to change the input encoding from the URL string if we want to. (optional)
if (!empty($_GET['input']))
{
	$feed->set_input_encoding($_GET['input']);
}

// Allow us to choose to not re-order the items by date. (optional)
if (!empty($_GET['orderbydate']) && $_GET['orderbydate'] == 'false')
{
	$feed->enable_order_by_date(false);
}

// Trigger force-feed
if (!empty($_GET['force']) && $_GET['force'] == 'true')
{
	$feed->force_feed(true);
}

// Initialize the whole SimplePie object.  Read the feed, process it, parse it, cache it, and
// all that other good stuff.  The feed's information will not be available to SimplePie before
// this is called.
$feed->set_cache_location('/var/www/forex/rss/rss/cache');
$feed->set_cache_duration(1800);
$feed->enable_cache(True);
$success = $feed->init();

// We'll make sure that the right content type and character encoding gets set automatically.
// This function will grab the proper character encoding, as well as set the content type to text/html.
$feed->handle_content_type();

// When we end our PHP block, we want to make sure our DOCTYPE is on the top line to make
// sure that the browser snaps into Standards Mode.
?><!DOCTYPE html>

<html lang="en-US">
<head>
<title>News Collector: RSS</title>

<link rel="stylesheet" href="./for_the_demo/sIFR-screen.css" type="text/css" media="screen">
<link rel="stylesheet" href="./for_the_demo/sIFR-print.css" type="text/css" media="print">
<link rel="stylesheet" href="./for_the_demo/simplepie.css" type="text/css" media="screen, projector" />

<script type="text/javascript" src="./for_the_demo/sifr.js"></script>
<script type="text/javascript" src="./for_the_demo/sifr-config.js"></script>
<script type="text/javascript" src="./for_the_demo/sleight.js"></script>
<script type="text/javascript" src="?js"></script>

</head>

<body id="bodydemo">

<div id="header">
	<div id="headerInner">
		<div id="logoContainer">
			<div id="logoContainerInner">
				<div align="center"><a href="http://forex.fusionworks.org"><img src="./for_the_demo/logo_simplepie_demo.png" border="0" /></a></div>
				<div class="clearLeft"></div>
			</div>

		</div>
		<div id="menu">
		<!-- I know, I know, I know... tables for layout, I know.  If a web standards evangelist (like me) has to resort
		to using tables for something, it's because no other possible solution could be found.  This issue?  No way to
		do centered floats purely with CSS. The table box model allows for a dynamic width while centered, while the
		CSS box model for DIVs doesn't allow for it. :( -->
		<table cellpadding="0" cellspacing="0" border="0"><tbody><tr><td>
<ul><li id="demo"><a href="./">News</a></li><li><a href="http://www.fusionworks.cn/" target="_blank">Site</a></li></ul>

			<div class="clearLeft"></div>
		</td></tr></tbody></table>
		</div>
	</div>
</div>

<div id="site">

	<div id="content">

		<div class="chunk">
			<form action="" method="get" name="sp_form" id="sp_form">
				<div id="sp_input">


					<!-- If a feed has already been passed through the form, then make sure that the URL remains in the form field. -->
					<p><input type="text" name="feed" value="<?php if ($feed->subscribe_url()) echo $feed->subscribe_url(); ?>" class="text" id="feed_input" />&nbsp;<input type="submit" value="Read" class="button" /></p>


				</div>
			</form>


			<?php
			// Check to see if there are more than zero errors (i.e. if there are any errors at all)
			if ($feed->error())
			{
				// If so, start a <div> element with a classname so we can style it.
				echo '<div class="sp_errors">' . "\r\n";

					// ... and display it.
					echo '<p>' . htmlspecialchars($feed->error()) . "</p>\r\n";

				// Close the <div> element we opened.
				echo '</div>' . "\r\n";
			}
			?>

			<!-- Here are some sample feeds. -->
                        <p>News</p>
			<p class="sample_feeds"><strong>Tech:</strong>
			<a href="?feed=https://lwn.net/headlines/rss">LWN News</a>,
			<a href="?feed=http://rss.cnbeta.com/rss">cnBeta</a>
			<p class="sample_feeds"><strong>World:</strong>
			<a href="?feed=http://www.people.com.cn/rss/politics.xml">人民日报－国内政治</a>,
			<a href="?feed=http://www.people.com.cn/rss/world.xml">人民日报－国际新闻</a>,
			<a href="?feed=http://www.people.com.cn/rss/finance.xml">人民日报－经济新闻</a>,
			<a href="?feed=http://www.people.com.cn/rss/haixia.xml">人民日报－海峡两岸</a>,
			<a href="?feed=http://www.people.com.cn/rss/haixia.xml">人民日报－教育新闻</a>,
			<a href="?feed=http://newsrss.bbc.co.uk/rss/newsonline_world_edition/front_page/rss.xml" title="World News">BBC News</a>,
			<a href="?feed=http://newsrss.bbc.co.uk/rss/chinese/simp/news/rss.xml" title="Test: GB2312 Encoding">BBC China</a>,
			<a href="?feed=http://rss.news.yahoo.com/rss/topstories" title="World News">Yahoo! News</a>,
			<a href="?feed=http://rss.cnn.com/rss/cnn_topstories.rss" title="World News">CNN</a>,
			<a href="?feed=http://news.google.com/?output=rss" title="World News">Google News</a>
			<p class="sample_feeds"><strong>Forex:</strong>
			<a href="?feed=http://www.fxtimes.com/feed" title="World News">Forex Times</a>,
			<a href="?feed=http://rss.forexfactory.net/news/all.xml">Forex Factory</a>,
			<a href="?feed=http://rss.forexfactory.net/forum/all.xml">Forex Factory Forum</a>,
			<a href="?feed=http://xml.fxstreet.com/news/forex-news/index.xml">Forex Street</a>,
                        <p>Banks</p>
			<p class="sample_feeds"><strong>BIS国际结算银行:</strong>
			<a href="?feed=http://www.bis.org/list/bis/index.rss">BIS</a>,
			<a href="?feed=http://www.bis.org/list/statistics/index.rss">BIS Statistic</a>,
			<a href="?feed=http://www.bis.org/list/press_releases/index.rss">BIS Press</a>
			<a href="?feed=http://www.bis.org/list/papers/index.rss">BIS Papers</a>
			<p class="sample_feeds"><strong>FRS美联储:</strong>
			<a href="?feed=http://www.federalreserve.gov/feeds/press_all.xml">Press Releases</a>,
			<a href="?feed=http://www.federalreserve.gov/feeds/data/H10_H10.XML">Forex Exchange Rate</a>,
			<a href="?feed=http://www.federalreserve.gov/feeds/datadownload.xml">Data Download</a>,
			<a href="?feed=http://www.federalreserve.gov/feeds/speeches.xml">Speeches</a>
			<p class="sample_feeds"><strong>ECB欧洲中央银行:</strong>
			<a href="?feed=https://www.ecb.europa.eu/rss/press.html">Press Releases</a>,
			<a href="?feed=https://www.ecb.europa.eu/rss/statpress.html">Statistical Press Releases</a>,
			<a href="?feed=https://www.ecb.europa.eu/rss/pub.html">Publications</a>,
			<a href="?feed=https://www.ecb.europa.eu/rss/wppub.html">Working Papaers</a>,
			<a href="?feed=https://www.ecb.europa.eu/rss/legalacts.html">Legal acts</a>,
			<a href="?feed=https://www.ecb.europa.eu/rss/operations.html">Recent open market</a>,
			<a href="?feed=https://www.ecb.europa.eu/rss/procurements.html">Open procurements</a>,
			<a href="?feed=https://www.ecb.europa.eu/rss/yc.html">Yield curve</a>
			<p class="sample_feeds"><strong>BOJ日本银行:</strong>
			<a href="?feed=http://www.boj.or.jp/en/rss/whatsnew.xml">Recent News</a>
			<p class="sample_feeds"><strong>SNB瑞士国民银行:</strong>
			<a href="?feed=http://www.snb.ch/selector/en/mmr/news/rss">Recent News</a>,
			<a href="?feed=http://www.snb.ch/selector/en/mmr/pressrel/rss">Press</a>,
			<a href="?feed=http://www.snb.ch/selector/en/mmr/mopo/rss">Monetary policy</a>,
			<a href="?feed=http://www.snb.ch/selector/en/mmr/statistics/rss">Statistical publications</a>,
			<a href="?feed=http://www.snb.ch/selector/en/mmr/papers/rss">Working Papers</a>
			<p class="sample_feeds"><strong>BOE英格兰银行:</strong>
			<a href="?feed=http%3A%2F%2Fwww.bankofengland.co.uk%2F_layouts%2FBOE.internetCMS%2Ffeeddirector.aspx%3FSourcePage%3D%252FPages%252FSiteFeed%252Easpx%26RssSource%3DNews%2520Release%2520RSS">Recent News</a>,
			<a href="?feed=http%3A%2F%2Fwww.bankofengland.co.uk%2F_layouts%2FBOE.internetCMS%2Ffeeddirector.aspx%3FSourcePage%3D%252FPages%252FSiteFeed%252Easpx%26RssSource%3DPublications%2520RSS">Publications</a>,
			<a href="?feed=http%3A%2F%2Fwww.bankofengland.co.uk%2F_layouts%2FBOE.internetCMS%2Ffeeddirector.aspx%3FSourcePage%3D%252FPages%252FSiteFeed%252Easpx%26RssSource%3DSpeeches%2520RSS">Speeches</a>,
			<a href="?feed=http%3A%2F%2Fwww.bankofengland.co.uk%2F_layouts%2FBOE.internetCMS%2Ffeeddirector.aspx%3FSourcePage%3D%252FPages%252FSiteFeed%252Easpx%26RssSource%3DPRA%2520RSS">Prudential Regulation Authority</a>,
			<a href="?feed=http%3A%2F%2Fwww.bankofengland.co.uk%2F_layouts%2FBOE.internetCMS%2Ffeeddirector.aspx%3FSourcePage%3D%252FPages%252FSiteFeed%252Easpx%26RssSource%3DStatistical%2520Releases%2520RSS">Statistical Releases</a>
			<p class="sample_feeds"><strong>RBA澳大利亚储备银行:</strong>
			<a href="?feed=http://www.rba.gov.au/rss/rss-cb-media-releases.xml">Media Releases</a>,
			<a href="?feed=http://www.rba.gov.au/rss/rss-cb-speeches.xml">Speeches</a>,
			<a href="?feed=http://www.rba.gov.au/rss/rss-cb-exchange-rates.xml">Exchange Rates</a>,
			<a href="?feed=http://www.rba.gov.au/rss/rss-cb-rdp.xml">Research Papers</a>,
			<a href="?feed=http://www.rba.gov.au/rss/rss-cb-foi.xml">Freedom of Information</a>,
			<a href="?http://www.rba.gov.au/rss/rss-cb-changes-to-tables.xml">Changes to Statistical Tables</a>
			<p class="sample_feeds"><strong>RBNZ新西兰储备银行:</strong>
			<a href="?feed=http://www.rbnz.govt.nz/feeds/news.xml">News Releases</a>
			<p class="sample_feeds"><strong>OECD经济合作与发展组织:</strong>
			<a href="?feed=http://www.oecd.org/index.xml">Front Page</a>,
			<a href="?feed=http://www.oecd.org/newsroom/index.xml">News Releases</a>,
			<a href="?feed=http://feeds.feedburner.com/OecdObserver">OECD Observer</a>,
			<a href="?feed=http://oecdinsights.org/feed/">Insights Blog</a>
                        <p>Statistics</p>
			<p class="sample_feeds"><strong>STATS.CN中国统计局:</strong>
			<a href="?feed=http://www.stats.gov.cn/tjsj/zxfb/rss.xml">最新发布</a>,
			<a href="?feed=http://www.stats.gov.cn/tjsj/sjjd/rss.xml">数据解读</a>
			<p class="sample_feeds"><strong>ESA美国经济统计局:</strong>
			<a href="?feed=http://www.esa.doc.gov/rss.xml">News</a>
			<p class="sample_feeds"><strong>BLS美国劳工统计局:</strong>
			<a href="?feed=http://www.bls.gov/feed/cpi.rss">CPI</a>
			<p class="sample_feeds"><strong>CENSUS美国人口普查局:</strong>
			<a href="?feed=http://www.census.gov/economic-indicators/indicator.xml">Economic Indicators</a>,
			<a href="?feed=http://www.census.gov/newsroom/press-releases/by-year.xml">Press Releases</a>,
			<a href="?feed=http://globalreach.blogs.census.gov/feed/">Global Reach</a>,
			<a href="?feed=http://blogs.census.gov/feed/">Random Samplings</a>,
			<a href="?feed=http://researchmatters.blogs.census.gov/feed/">Research Matters</a>
			<p class="sample_feeds"><strong>EC.EUROPA欧盟委员会欧洲统计局:</strong>
			<a href="?feed=http://ec.europa.eu/eurostat/cache/RSS/rss_estat_news.xml">News Releases</a>,
			<a href="?feed=http://ec.europa.eu/eurostat/cache/RSS/rss_estat_sif.xml">Statistic in focus</a>
			<p class="sample_feeds"><strong>STAT.JP日本统计局和统计中心:</strong>
			<a href="?feed=http://www.stat.go.jp/whatsnew/news.rdf">News Releases(Japanese)</a>
			<p class="sample_feeds"><strong>BFS瑞士联邦统计办公室:</strong>
			<a href="?feed=http://www.bfs.admin.ch/bfs/portal/en/index/news/publikationen.rss2.html">Publications</a>
			<p class="sample_feeds"><strong>STA.UK英国国家统计局:</strong>
			<a href="?feed=https://www.gov.uk/government/statistics.atom?publication_filter_option=statistics">Statistics</a>
			<p class="sample_feeds"><strong>ABS.AU澳大利亚统计局:</strong>
			<a href="?feed=http://www.abs.gov.au/AUSSTATS/wmdata.nsf/activerss/headline_rss/$File/headline_rss.xml">Statistics Headlines</a>

		</div>

		<div id="sp_results">

			<!-- As long as the feed has data to work with... -->
			<?php if ($success): ?>
				<div class="chunk focus" align="center">

					<!-- If the feed has a link back to the site that publishes it (which 99% of them do), link the feed's title to it. -->
					<h3 class="header">
					<?php 
						$link = $feed->get_link();
						$title = $feed->get_title();
						if ($link) 
						{ 
							$title = "<a href='$link' title='$title'>$title</a>"; 
						}
						echo $title;
					?>
					</h3>

					<!-- If the feed has a description, display it. -->
					<?php echo $feed->get_description(); ?>

				</div>

				<!-- Let's begin looping through each individual news item in the feed. -->
				<?php foreach($feed->get_items() as $item): ?>
					<div class="chunk">

						<!-- If the item has a permalink back to the original post (which 99% of them do), link the item's title to it. -->
						<h4><?php if ($item->get_permalink()) echo '<a target="_blank" href="' . $item->get_permalink() . '">'; echo $item->get_title(); if ($item->get_permalink()) echo '</a>'; ?>&nbsp;<span class="footnote"><?php echo $item->get_date('j M Y, g:i a'); ?></span></h4>

						<!-- Display the item's primary content. -->
						<?php echo $item->get_content(); ?>

						<?php
						// Check for enclosures.  If an item has any, set the first one to the $enclosure variable.
						if ($enclosure = $item->get_enclosure(0))
						{
							// Use the embed() method to embed the enclosure into the page inline.
							echo '<div align="center">';
							echo '<p>' . $enclosure->embed(array(
								'audio' => './for_the_demo/place_audio.png',
								'video' => './for_the_demo/place_video.png',
								'mediaplayer' => './for_the_demo/mediaplayer.swf',
								'altclass' => 'download'
							)) . '</p>';

							if ($enclosure->get_link() && $enclosure->get_type())
							{
								echo '<p class="footnote" align="center">(' . $enclosure->get_type();
								if ($enclosure->get_size())
								{
									echo '; ' . $enclosure->get_size() . ' MB';
								}
								echo ')</p>';
							}
							if ($enclosure->get_thumbnail())
							{
								echo '<div><img src="' . $enclosure->get_thumbnail() . '" alt="" /></div>';
							}
							echo '</div>';
						}
						?>

					</div>

				<!-- Stop looping through each item once we've gone through all of them. -->
				<?php endforeach; ?>

			<!-- From here on, we're no longer using data from the feed. -->
			<?php endif; ?>

		</div>

		<div>
			<!-- Display how fast the page was rendered. -->
			<p class="footnote">Page processed in <?php $mtime = explode(' ', microtime()); echo round($mtime[0] + $mtime[1] - $starttime, 3); ?> seconds.</p>

			<!-- Display the version of SimplePie being loaded. -->
			<p class="footnote">Powered by <a href="http://www.fusionworks.cn">FusionWorks.cn</a>.</p>
		</div>

	</div>

</div>

</body>
</html>
