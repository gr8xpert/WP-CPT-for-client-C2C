<?php
/**
 * Single Property Template
 *
 * This template is loaded by C2C_Template_Loader for c2c_property posts.
 * Override by placing single-c2c_property.php in your theme directory.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();

while ( have_posts() ) :
    the_post();

    $post_id      = get_the_ID();
    $type         = get_post_meta( $post_id, '_c2c_property_type', true );
    $location     = get_post_meta( $post_id, '_c2c_location', true );
    $size         = get_post_meta( $post_id, '_c2c_size', true );
    $reference    = get_post_meta( $post_id, '_c2c_reference', true );
    $price        = get_post_meta( $post_id, '_c2c_price', true );
    $beds         = get_post_meta( $post_id, '_c2c_bedrooms', true );
    $baths        = get_post_meta( $post_id, '_c2c_bathrooms', true );
    $energy       = get_post_meta( $post_id, '_c2c_energy_rating', true );
    $link         = get_post_meta( $post_id, '_c2c_property_link', true );
    $booking_link = get_post_meta( $post_id, '_c2c_booking_link', true );
    $gallery      = get_post_meta( $post_id, '_c2c_gallery_ids', true );
?>

<div class="c2c-single-property">

    <!-- Hero Banner with Title Overlay -->
    <?php
    // Use featured image, or fall back to first gallery image
    $hero_img_url = '';
    if ( has_post_thumbnail() ) {
        $hero_img_url = get_the_post_thumbnail_url( $post_id, 'full' );
    } elseif ( $gallery ) {
        $first_ids = array_filter( array_map( 'intval', explode( ',', $gallery ) ) );
        if ( ! empty( $first_ids ) ) {
            $hero_img_url = wp_get_attachment_image_url( reset( $first_ids ), 'full' );
        }
    }

    if ( $hero_img_url ) : ?>
        <div class="c2c-hero-banner">
            <img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php the_title_attribute(); ?>" />
            <div class="c2c-hero-overlay">
                <h1 class="c2c-hero-title"><?php the_title(); ?></h1>
                <?php if ( $price ) : ?>
                    <div class="c2c-hero-price">Price: <?php echo esc_html( number_format( floatval( $price ), 0, '.', ',' ) ); ?>&euro;</div>
                <?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="c2c-single-header">
            <h1 class="c2c-single-title"><?php the_title(); ?></h1>
            <?php if ( $price ) : ?>
                <div class="c2c-single-price">&euro;<?php echo esc_html( number_format( floatval( $price ), 0, '.', ',' ) ); ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Highlights — 2 per row -->
    <div class="c2c-single-icons">
        <?php if ( $beds ) : ?>
            <div class="c2c-icon-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7v11"/><path d="M21 7v11"/><path d="M3 18h18"/><path d="M3 11h18"/><rect x="5" y="7" width="5" height="4" rx="1"/></svg>
                <span><?php echo esc_html( $beds ); ?> Bedroom<?php echo intval( $beds ) !== 1 ? 's' : ''; ?></span>
            </div>
        <?php endif; ?>
        <?php if ( $baths ) : ?>
            <div class="c2c-icon-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12h16a1 1 0 0 1 1 1v3a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4v-3a1 1 0 0 1 1-1z"/><path d="M6 12V5a2 2 0 0 1 2-2h3v2.25"/></svg>
                <span><?php echo esc_html( $baths ); ?> Bathroom<?php echo intval( $baths ) !== 1 ? 's' : ''; ?></span>
            </div>
        <?php endif; ?>
        <?php if ( $size ) : ?>
            <div class="c2c-icon-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 3v18"/></svg>
                <span><?php echo esc_html( number_format( floatval( $size ), 0, '.', ',' ) ); ?> m&sup2;</span>
            </div>
        <?php endif; ?>
        <?php if ( $type ) : ?>
            <div class="c2c-icon-item">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18"/><path d="M5 21V7l8-4v18"/><path d="M19 21V11l-6-4"/><path d="M9 9h1"/><path d="M9 13h1"/><path d="M9 17h1"/></svg>
                <span><?php echo esc_html( $type ); ?></span>
            </div>
        <?php endif; ?>
    </div>

    <!-- Gallery -->
    <?php if ( $gallery ) :
        $gallery_ids = array_filter( array_map( 'intval', explode( ',', $gallery ) ) );
        if ( ! empty( $gallery_ids ) ) : ?>
            <div class="c2c-single-gallery">
                <?php $index = 0; foreach ( $gallery_ids as $img_id ) :
                    $img_url   = wp_get_attachment_image_url( $img_id, 'large' ) ?: wp_get_attachment_image_url( $img_id, 'full' );
                    $img_thumb = wp_get_attachment_image_url( $img_id, 'medium' );
                    if ( $img_thumb && $img_url ) : ?>
                        <a href="<?php echo esc_url( $img_url ); ?>" class="c2c-gallery-item" data-c2c-lightbox="<?php echo $index; ?>" onclick="c2cLightbox.open(<?php echo $index; ?>);return false;">
                            <img src="<?php echo esc_url( $img_thumb ); ?>" alt="<?php echo esc_attr( get_post_meta( $img_id, '_wp_attachment_image_alt', true ) ); ?>" loading="lazy" />
                        </a>
                    <?php $index++; endif;
                endforeach; ?>
            </div>

            <!-- Lightbox -->
            <div id="c2c-lightbox" class="c2c-lightbox" aria-hidden="true">
                <div class="c2c-lightbox-overlay"></div>
                <button class="c2c-lightbox-close" aria-label="Close">&times;</button>
                <button class="c2c-lightbox-prev" aria-label="Previous">&#10094;</button>
                <button class="c2c-lightbox-next" aria-label="Next">&#10095;</button>
                <div class="c2c-lightbox-content">
                    <img src="" alt="" class="c2c-lightbox-img" />
                    <div class="c2c-lightbox-counter"></div>
                </div>
            </div>

            <script>
            var c2cLightbox = (function(){
                var images = [];
                var current = 0;
                var el, img, counter;

                document.querySelectorAll('.c2c-gallery-item[data-c2c-lightbox]').forEach(function(a){
                    images.push(a.href);
                });

                function init(){
                    el = document.getElementById('c2c-lightbox');
                    img = el.querySelector('.c2c-lightbox-img');
                    counter = el.querySelector('.c2c-lightbox-counter');

                    el.querySelector('.c2c-lightbox-overlay').addEventListener('click', close);
                    el.querySelector('.c2c-lightbox-close').addEventListener('click', close);
                    el.querySelector('.c2c-lightbox-prev').addEventListener('click', function(){ navigate(-1); });
                    el.querySelector('.c2c-lightbox-next').addEventListener('click', function(){ navigate(1); });

                    document.addEventListener('keydown', function(e){
                        if (el.getAttribute('aria-hidden') !== 'false') return;
                        if (e.key === 'Escape') close();
                        if (e.key === 'ArrowLeft') navigate(-1);
                        if (e.key === 'ArrowRight') navigate(1);
                    });
                }

                function open(index){
                    current = index;
                    show();
                    el.setAttribute('aria-hidden', 'false');
                    document.body.style.overflow = 'hidden';
                }

                function close(){
                    el.setAttribute('aria-hidden', 'true');
                    document.body.style.overflow = '';
                }

                function navigate(dir){
                    current = (current + dir + images.length) % images.length;
                    show();
                }

                function show(){
                    img.src = images[current];
                    counter.textContent = (current + 1) + ' / ' + images.length;
                }

                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', init);
                } else {
                    init();
                }

                return { open: open };
            })();
            </script>
        <?php endif;
    endif; ?>

    <!-- Property Details Table -->
    <div class="c2c-single-details">
        <h2>Property Details</h2>
        <table class="c2c-details-table">
            <tbody>
                <?php if ( $type ) : ?>
                    <tr><th>Property Type</th><td><?php echo esc_html( $type ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $location ) : ?>
                    <tr><th>Location</th><td><?php echo esc_html( $location ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $reference ) : ?>
                    <tr><th>Property ID</th><td><?php echo esc_html( $reference ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $size ) : ?>
                    <tr><th>Size</th><td><?php echo esc_html( number_format( floatval( $size ), 0, '.', ',' ) ); ?> m&sup2;</td></tr>
                <?php endif; ?>
                <?php if ( $beds ) : ?>
                    <tr><th>Bedrooms</th><td><?php echo esc_html( $beds ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $baths ) : ?>
                    <tr><th>Bathrooms</th><td><?php echo esc_html( $baths ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $energy ) : ?>
                    <tr><th>Energy Rating</th><td><?php echo esc_html( $energy ); ?></td></tr>
                <?php endif; ?>
                <?php if ( $price ) : ?>
                    <tr><th>Price</th><td>&euro;<?php echo esc_html( number_format( floatval( $price ), 0, '.', ',' ) ); ?></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Description -->
    <div class="c2c-single-description">
        <h2>Description</h2>
        <div class="c2c-single-content">
            <?php the_content(); ?>
        </div>
    </div>

    <!-- CTA Button -->
    <?php if ( $link ) : ?>
        <div class="c2c-single-cta">
            <a href="<?php echo esc_url( $link ); ?>" class="c2c-cta-btn" target="_blank" rel="noopener noreferrer">Click here for more information</a>
        </div>
    <?php endif; ?>

    <!-- Book Viewing Section -->
    <?php if ( $booking_link ) : ?>
        <div class="c2c-book-viewing">
            <div class="c2c-book-viewing-logo">
                <div class="c2c-book-logo-text">
                    <span class="c2c-logo-c">C</span><span class="c2c-logo-2">2</span><span class="c2c-logo-c">C</span>
                </div>
                <svg class="c2c-logo-wave" width="60" height="16" viewBox="0 0 60 16" fill="none"><path d="M0 8c5 0 5-6 10-6s5 6 10 6 5-6 10-6 5 6 10 6 5-6 10-6 5 6 10 6" stroke="#1a2332" stroke-width="2.5" fill="none"/></svg>
                <div class="c2c-logo-sub">Properties</div>
            </div>
            <a href="<?php echo esc_url( $booking_link ); ?>" class="c2c-book-btn" target="_blank" rel="noopener noreferrer">BOOK VIEWING</a>
        </div>
    <?php endif; ?>

</div>

<?php
endwhile;

get_footer();
