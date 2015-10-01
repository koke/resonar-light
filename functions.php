<?php

define( 'RESONAR_LIGHT_USE_WEB_FONT_LOADER', true );
define( 'RESONAR_LIGHT_FORCE_LATIN', true );

// -----------
// Font tweaks
// -----------

add_action( 'wp_enqueue_scripts', 'resonar_light_enqueue_styles' );
function resonar_light_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

function resonar_light_deregister_fonts_style() {
  wp_deregister_style( 'resonar-fonts' );
}

function resonar_light_fonts_families_config() {
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Libre Baskerville, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Libre Baskerville font: on or off', 'resonar' ) ) {
		$fonts[] = 'Libre Baskerville:400,700,400italic';
	}

	/* translators: If there are characters in your language that are not supported by Lato, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Lato font: on or off', 'resonar' ) ) {
		$fonts[] = 'Lato:400,700,900,400italic,700italic,900italic';
	}

  /* translators: If there are characters in your language that are not supported by Playfair Display, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Playfair Display font: on or off', 'resonar' ) ) {
		$fonts[] = 'Playfair Display:400,700,400italic,700italic';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'resonar' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'cyrillic'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (cyrillic)', 'resonar' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic';
	}

  if ( RESONAR_LIGHT_FORCE_LATIN ) {
    $subsets = 'latin';
  }

  $result = "\n";
  foreach ( $fonts as $font ) {
    $result .= "'$font:$subsets',\n";
  }

	return $result;
}

function resonar_light_output_webfont_loader() {
  $families = resonar_light_fonts_families_config();
  echo
<<<EMBED
<script type="text/javascript">
  WebFontConfig = {
    google: { families: [ $families ] },
  };
  (function() {
    var wf = document.createElement('script');
    wf.src = 'https://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
    wf.type = 'text/javascript';
    wf.async = 'true';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(wf, s);
  })();
</script>
EMBED;
}

if ( RESONAR_LIGHT_USE_WEB_FONT_LOADER ) {
  add_action( 'wp_print_styles', 'resonar_light_deregister_fonts_style', 100 );
  add_action( 'wp_enqueue_scripts', 'resonar_light_output_webfont_loader' );
}

// -----------
// Misc tweaks
// -----------

/* Don't load emoji JS/CSS since we're not using any */
add_action( 'init', 'rl_disable_emojis' );
function rl_disable_emojis() {
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
}

/* Skip retina detection since this site doesn't use images */
add_action( 'wp_enqueue_scripts', 'rl_remove_devicepx', 20 );
function rl_remove_devicepx() {
  wp_dequeue_script( 'devicepx' );
}

add_filter( 'jetpack_implode_frontend_css', '__return_false' );
