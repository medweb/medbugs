<?php
/**
 * Take an array of feed URLs and return a single array of item objects
 * 
 */
function combine_feeds( $feeds = array() ) {
	if ( !empty( $feeds ) ) {
		$combinedFeeds = array();
		
		foreach ( $feeds as $feed ) {
			$doc = new DOMDocument();
			$doc->load( $feed );
			
			foreach ( $doc->getElementsByTagName( 'item' ) as $node ) {
				$itemRSS = array ( 
					'title' => $node->getElementsByTagName( 'title' )->item( 0 )->nodeValue,
					'desc' => $node->getElementsByTagName( 'description' )->item( 0 )->nodeValue,
					'link' => $node->getElementsByTagName( 'link' )->item( 0 )->nodeValue,
					'date' => $node->getElementsByTagName( 'pubDate' )->item( 0 )->nodeValue
				);
				
				array_push( $combinedFeeds, $itemRSS );
			}
		}
		
		return $combinedFeeds;
	}
}

$feeds = array( 
	'http://med.ucf.edu/feed/?post_type=news', 
	'http://med.ucf.edu/feed/?post_type=research' 
);

$masterFeed = combine_feeds( $feeds );

/**
 * Set header as XML for proper rendering and set up
 * XML document. Then run a loop and output the items
 * 
 */
header ( "Content-Type:text/xml" );

echo '<?xml version="1.0" encoding="UTF-8"?>
		<rss version="2.0" 
			xmlns:content="http://purl.org/rss/1.0/modules/content/" 
			xmlns:wfw="http://wellformedweb.org/CommentAPI/" 
			xmlns:dc="http://purl.org/dc/elements/1.1/" 
			xmlns:atom="http://www.w3.org/2005/Atom" 
			xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" 
			xmlns:slash="http://purl.org/rss/1.0/modules/slash/">
		
		<channel>
			<title>UCF College of Medicine &#187; News</title>
			<atom:link href="http://med.ucf.edu/feed/" rel="self" type="application/rss+xml" />
			<link>http://med.ucf.edu</link>
			<description></description>
			<lastBuildDate>Thu, 08 Dec 2011 19:19:13 +0000</lastBuildDate>
			<language>en</language>
			<sy:updatePeriod>hourly</sy:updatePeriod>
			<sy:updateFrequency>1</sy:updateFrequency>';

foreach ( $masterFeed as $feed ) {
	?>
	<item>
		<title><?php echo $feed['title']; ?></title>
		<link><?php echo $feed['link']; ?></link>
		<pubDate><?php echo $feed['date']; ?></pubDate>
		<description><?php echo $feed['desc']; ?></description>
	</item>
	<?php
}

echo '</channel>
	</rss>';
?>