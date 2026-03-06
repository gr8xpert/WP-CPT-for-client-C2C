<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class C2C_Shortcode {

    public function __construct() {
        add_shortcode( 'c2c_properties', array( $this, 'render' ) );
    }

    /**
     * [c2c_properties] shortcode handler.
     *
     * Attributes:
     *   limit   – posts per page (default 12)
     *   type    – filter by property type
     *   location– filter by location (partial match)
     *   orderby – date | price | title (default date)
     */
    public function render( $atts ) {
        $atts = shortcode_atts( array(
            'limit'    => 12,
            'type'     => '',
            'location' => '',
            'orderby'  => 'date',
        ), $atts, 'c2c_properties' );

        // Enqueue CSS when shortcode is used
        wp_enqueue_style(
            'c2c-properties',
            C2C_PROPERTIES_URL . 'assets/css/c2c-properties.css',
            array(),
            C2C_PROPERTIES_VERSION
        );

        // Build query args
        $query_args = array(
            'post_type'      => 'c2c_property',
            'posts_per_page' => intval( $atts['limit'] ),
            'post_status'    => 'publish',
        );

        // Meta query filters
        $meta_query = array();

        if ( ! empty( $atts['type'] ) ) {
            $meta_query[] = array(
                'key'   => '_c2c_property_type',
                'value' => sanitize_text_field( $atts['type'] ),
            );
        }

        if ( ! empty( $atts['location'] ) ) {
            $meta_query[] = array(
                'key'   => '_c2c_location',
                'value' => sanitize_text_field( $atts['location'] ),
            );
        }

        // Ordering
        switch ( $atts['orderby'] ) {
            case 'price_asc':
                $meta_query['price_clause'] = array(
                    'key'  => '_c2c_price',
                    'type' => 'NUMERIC',
                );
                $query_args['orderby'] = array( 'price_clause' => 'ASC' );
                break;
            case 'price_desc':
            case 'price':
                $meta_query['price_clause'] = array(
                    'key'  => '_c2c_price',
                    'type' => 'NUMERIC',
                );
                $query_args['orderby'] = array( 'price_clause' => 'DESC' );
                break;
            case 'title':
                $query_args['orderby'] = 'title';
                $query_args['order']   = 'ASC';
                break;
            default:
                $query_args['orderby'] = 'date';
                $query_args['order']   = 'DESC';
                break;
        }

        if ( ! empty( $meta_query ) ) {
            $query_args['meta_query'] = $meta_query;
        }

        $query = new WP_Query( $query_args );

        if ( ! $query->have_posts() ) {
            return '<p class="c2c-no-results">No properties found.</p>';
        }

        ob_start();
        echo '<div class="c2c-properties-grid">';

        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id  = get_the_ID();
            $price    = get_post_meta( $post_id, '_c2c_price', true );
            $location = get_post_meta( $post_id, '_c2c_location', true );
            $beds     = get_post_meta( $post_id, '_c2c_bedrooms', true );
            $baths    = get_post_meta( $post_id, '_c2c_bathrooms', true );

            // Card image: featured image or first gallery image
            $card_img = '';
            if ( has_post_thumbnail() ) {
                $card_img = get_the_post_thumbnail( $post_id, 'medium_large' );
            } else {
                $gallery_ids_raw = get_post_meta( $post_id, '_c2c_gallery_ids', true );
                if ( $gallery_ids_raw ) {
                    $gal_ids = array_filter( array_map( 'intval', explode( ',', $gallery_ids_raw ) ) );
                    if ( ! empty( $gal_ids ) ) {
                        $card_img = wp_get_attachment_image( reset( $gal_ids ), 'medium_large' );
                    }
                }
            }
            ?>
            <div class="c2c-property-card">
                <?php if ( $card_img ) : ?>
                    <div class="c2c-card-image">
                        <a href="<?php the_permalink(); ?>">
                            <?php echo $card_img; ?>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="c2c-card-body">
                    <h3 class="c2c-card-title">
                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                    </h3>

                    <?php if ( $location ) : ?>
                        <p class="c2c-card-location"><?php echo esc_html( $location ); ?></p>
                    <?php endif; ?>

                    <?php if ( $price ) : ?>
                        <p class="c2c-card-price">&euro;<?php echo esc_html( number_format( floatval( $price ), 0, '.', ',' ) ); ?></p>
                    <?php endif; ?>

                    <div class="c2c-card-meta">
                        <?php if ( $beds ) : ?>
                            <span class="c2c-meta-item">Bedrooms: <?php echo esc_html( $beds ); ?></span>
                        <?php endif; ?>
                        <?php if ( $baths ) : ?>
                            <span class="c2c-meta-item">Bathrooms: <?php echo esc_html( $baths ); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <a href="<?php the_permalink(); ?>" class="c2c-card-btn">View Property</a>
            </div>
            <?php
        }

        echo '</div>';
        wp_reset_postdata();

        return ob_get_clean();
    }
}
