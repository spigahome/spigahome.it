<?php

/**
 * Clever One Pixel
 *
 * @author CleverSoft <hello.cleversoft@gmail.com>
 */
final class CleverOnePixel
{
    static $fake;
    static $fake_max;

    /**
     * Nope constructor
     */
    private function __construct() {

    }

    /**
     * Singleton
     */
    static function instance() {
        static $self = null;
        if ( null === $self ) {
            $self           = new self();
            self::$fake     = intval( get_theme_mod( 'zoo_product_visitor_fake_data', 100 ) );
            self::$fake_max = intval( get_theme_mod( 'zoo_product_visitor_fake_max_data', 200 ) );
            add_action( 'wp_footer', [ $self, '_scripts' ] );
        }
    }


    /**
     * Count visitors for a request
     */
    static function countVisitors( $req = false ) {
        $fake_visitor = rand( self::$fake, self::$fake_max );

        return '<span id="cop-visitors-count" class="visitor" data-fake="' . self::$fake . '"data-fake-max="' . self::$fake_max . '">' . $fake_visitor . '</span>';
    }

    /**
     *
     */
    static function _scripts() {
        echo "<script>jQuery(function($)
        {
             function countVisitors(el){
                let min = parseInt(el.text()) - 10;
                let max = parseInt(el.text()) + 10;
                let count = zoogetRandomArbitrary(min,max);
                if(count<el.data('fake')){
                    count=el.data('fake');
                }if(count>el.data('fakeMax')){
                    count=el.data('fakeMax');
                }
                el.text(count);
            }
            function zoogetRandomArbitrary(min, max) {
			  	let count = parseInt(Math.random() * (max - min) + min);
			 	return count<1?1:count;
			}
            var copCount = $('#cop-visitors-count');
            if (copCount.length) {
        		setInterval(function() { countVisitors(copCount); }, 10000);
            }
        });</script>";
    }
}

CleverOnePixel::instance();
