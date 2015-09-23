<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package visainterns
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function visainterns_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}
add_filter( 'body_class', 'visainterns_body_classes' );

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function visainterns_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name.
		$title .= get_bloginfo( 'name', 'display' );

		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= " $sep $site_description";
		}

		// Add a page number if necessary.
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= " $sep " . sprintf( esc_html__( 'Page %s', 'visainterns' ), max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'visainterns_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function visainterns_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'visainterns_render_title' );
endif;

function get_images_path($doEcho = true)
{
	$path = get_template_directory_uri() . '/images/';
	if($doEcho) echo $path;
	return $path;
}

function visainterns_internSlider($id = null)
{
	$interns  = ACF_HELPER::getRepeater('interns',['name','image','department','office_location','title_of_projects','description'],$id);
	//$interns2 = ['name'=>$interns['name'],'image'=>$interns['image'],'description'=>$interns['description']];
	//d($interns);

	foreach($interns as $k=> $intern){
		$interns[$k]['table'] = [$intern['department'],$intern['office_location'],$intern['title_of_projects']];
		$interns[$k]['image'] = $intern['image'];
	}
	return $interns;
}

function visainterns_campusSlider($id = null)
{
	$campuses  = ACF_HELPER::getRepeater('campuses',['school_name','image','date'],$id);
	/*usort($campuses,function($a,$b){
		return  strtotime($a['date'])  - strtotime($b['date']);
	});*/
	//d($campuses);

	$activeDates = [];
	foreach($campuses as $k => $data){
		$isPassed = (isDatePassed($data['date'])) ? true : false;
		if($isPassed === false){$activeDates[$k] = substr($data['date'],0,4) . '/2015';}
	  $campuses[$k]['isPassed'] = $isPassed;
	}
	if(count($campuses) < 4){
		$campuses = [$campuses,$campuses,$campuses];
	}
	return ['by3' => array_chunk($campuses, 3), 'by4' =>array_chunk($campuses, 4), 'start'=>getFirstActiveDateKey($activeDates)];
}

function getFirstActiveDateKey($dates){
		uasort($dates, function($d1,$d2){
			$d1 = new DateTime($d1);
			$d2 = new DateTime($d2);
			return $d1 > $d2;
		});
		$key = array_keys($dates)[0];
		return round($key/3);
}

function isDatePassed($date){
	$date = substr($date,0,4);
	$date = new DateTime($date);
	$now  = new DateTime();
	//if($da)
	return ($date->format('Y-m-d') < $now->format('Y-m-d')) ? true : false;
}


class TEXT_BY_LOCATION
{
	protected static $replacements = [
		'default'=>['loc'=> 'Somewhere?', 'sess'=>'No Location Available.'],
		'austin' =>['loc'=>'Austin', 'sess'=>'THURSDAY 3:30PM AT WEBSTER HALL, AUSTIN TX'],

	];

	public static function checkDo()
	{
		 if(!self::hasGET()) return false;
		 $key =$_GET['loc'];
		 $replace = self::getReplacements();
		return $replace[$key];//$replacements[$key];
	}

	protected static function getReplacements(){
		$sessions = ACF_HELPER::getRepeater('session',['location','display_name','session_details'],get_the_ID());
		$data	  = [];
		foreach($sessions as $sess){
			$data[$sess['location']] = ['loc'=>$sess['display_name'],'sess'=>$sess['session_details']];
		}
		return $data;
	}

	protected static function hasGET()
	{
		return (isset($_GET['loc'])) ? true : false;
	}
}

function d($data, $exit = true, $key = '')
{
	echo '<pre>';
	echo $key . ':<br>';
	var_dump($data);
	echo '</pre>';
	if($exit) exit;
}
