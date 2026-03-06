<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class C2C_Meta_Box {

    /** All custom fields definition. */
    private $fields;

    public function __construct() {
        $this->fields = array(
            array( 'id' => '_c2c_property_type',  'label' => 'Property Type',  'type' => 'select', 'options' => array( '' => '— Select —', 'Apartment' => 'Apartment', 'Villa' => 'Villa', 'Penthouse' => 'Penthouse', 'Townhouse' => 'Townhouse', 'Land' => 'Land', 'Commercial' => 'Commercial' ) ),
            array( 'id' => '_c2c_location',        'label' => 'Location',        'type' => 'select', 'options' => array( '' => '— Select —', 'Bahia Dorada' => 'Bahia Dorada', 'Cancelada' => 'Cancelada', 'Casares' => 'Casares', 'Casares Playa' => 'Casares Playa', 'Doña Julia' => 'Doña Julia', 'El Paraiso' => 'El Paraiso', 'Estepona' => 'Estepona', 'La Alcaidesa' => 'La Alcaidesa', 'La Duquesa' => 'La Duquesa', 'La Noria' => 'La Noria', 'Manilva' => 'Manilva', 'Pueblo Nuevo de Guadiaro' => 'Pueblo Nuevo de Guadiaro', 'Punta Chullera' => 'Punta Chullera', 'San Diego' => 'San Diego', 'San Enrique' => 'San Enrique', 'San Luis de Sabinillas' => 'San Luis de Sabinillas', 'San Martín de Tesorillo' => 'San Martín de Tesorillo', 'San Roque' => 'San Roque', 'Sotogrande' => 'Sotogrande', 'Torreguadiaro' => 'Torreguadiaro', 'Valle Romano' => 'Valle Romano' ) ),
            array( 'id' => '_c2c_size',            'label' => 'Size (m²)',       'type' => 'number' ),
            array( 'id' => '_c2c_reference',       'label' => 'Property ID',     'type' => 'text' ),
            array( 'id' => '_c2c_price',           'label' => 'Price (€)',       'type' => 'number' ),
            array( 'id' => '_c2c_bedrooms',        'label' => 'Bedrooms',        'type' => 'number' ),
            array( 'id' => '_c2c_bathrooms',       'label' => 'Bathrooms',       'type' => 'number' ),
            array( 'id' => '_c2c_energy_rating',   'label' => 'Energy Rating',   'type' => 'select', 'options' => array( '' => '— Select —', 'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', 'E' => 'E', 'F' => 'F', 'G' => 'G', 'Awaiting Information' => 'Awaiting Information' ) ),
            array( 'id' => '_c2c_property_link',   'label' => 'Property Link',   'type' => 'url' ),
            array( 'id' => '_c2c_booking_link',    'label' => 'Book Viewing Link', 'type' => 'url' ),
        );

        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post_c2c_property', array( $this, 'save' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
    }

    /** Register the meta box. */
    public function add_meta_box() {
        add_meta_box(
            'c2c_property_details',
            'Property Details',
            array( $this, 'render' ),
            'c2c_property',
            'normal',
            'high'
        );
    }

    /** Enqueue media uploader on property edit screens. */
    public function enqueue_admin_scripts( $hook ) {
        global $post_type;
        if ( 'c2c_property' !== $post_type || ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
            return;
        }
        wp_enqueue_media();
    }

    /** Render the meta box fields. */
    public function render( $post ) {
        wp_nonce_field( 'c2c_property_save', 'c2c_property_nonce' );

        echo '<table class="form-table"><tbody>';

        foreach ( $this->fields as $field ) {
            $value = get_post_meta( $post->ID, $field['id'], true );
            echo '<tr>';
            echo '<th><label for="' . esc_attr( $field['id'] ) . '">' . esc_html( $field['label'] ) . '</label></th>';
            echo '<td>';

            switch ( $field['type'] ) {
                case 'select':
                    echo '<select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" style="width:100%;max-width:400px;">';
                    foreach ( $field['options'] as $opt_value => $opt_label ) {
                        echo '<option value="' . esc_attr( $opt_value ) . '"' . selected( $value, $opt_value, false ) . '>' . esc_html( $opt_label ) . '</option>';
                    }
                    echo '</select>';
                    break;

                case 'number':
                    echo '<input type="number" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '" style="width:100%;max-width:400px;" min="0" step="any" />';
                    break;

                case 'url':
                    echo '<input type="url" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="' . esc_url( $value ) . '" style="width:100%;max-width:400px;" placeholder="https://" />';
                    break;

                default: // text
                    echo '<input type="text" id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $value ) . '" style="width:100%;max-width:400px;" />';
                    break;
            }

            echo '</td></tr>';
        }

        // Gallery field
        $gallery_ids = get_post_meta( $post->ID, '_c2c_gallery_ids', true );
        echo '<tr><th><label>Gallery Images</label></th><td>';
        echo '<div id="c2c-gallery-preview" style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;">';

        if ( $gallery_ids ) {
            $ids = array_filter( array_map( 'intval', explode( ',', $gallery_ids ) ) );
            foreach ( $ids as $img_id ) {
                $thumb = wp_get_attachment_image_url( $img_id, 'thumbnail' );
                if ( $thumb ) {
                    echo '<div class="c2c-gallery-thumb" data-id="' . esc_attr( $img_id ) . '" style="position:relative;">';
                    echo '<img src="' . esc_url( $thumb ) . '" style="width:80px;height:80px;object-fit:cover;border-radius:4px;" />';
                    echo '<button type="button" class="c2c-remove-img" style="position:absolute;top:-6px;right:-6px;background:#d63638;color:#fff;border:none;border-radius:50%;width:20px;height:20px;cursor:pointer;font-size:14px;line-height:1;">&times;</button>';
                    echo '</div>';
                }
            }
        }

        echo '</div>';
        echo '<input type="hidden" id="c2c_gallery_ids" name="_c2c_gallery_ids" value="' . esc_attr( $gallery_ids ) . '" />';
        echo '<button type="button" id="c2c-gallery-btn" class="button">Add Gallery Images</button>';
        echo '</td></tr>';

        echo '</tbody></table>';

        // Gallery JavaScript
        ?>
        <script>
        jQuery(function($){
            var frame;

            $('#c2c-gallery-btn').on('click', function(e){
                e.preventDefault();
                if (frame) { frame.open(); return; }

                frame = wp.media({
                    title: 'Select Gallery Images',
                    button: { text: 'Add to Gallery' },
                    multiple: true,
                    library: { type: 'image' }
                });

                frame.on('select', function(){
                    var attachments = frame.state().get('selection').toJSON();
                    var currentIds = $('#c2c_gallery_ids').val() ? $('#c2c_gallery_ids').val().split(',').filter(Boolean) : [];

                    attachments.forEach(function(att){
                        if (currentIds.indexOf(String(att.id)) === -1) {
                            currentIds.push(att.id);
                            var thumb = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
                            var $img = $('<img>').attr('src', thumb).css({width:'80px',height:'80px',objectFit:'cover',borderRadius:'4px'});
                            var $btn = $('<button>').attr('type','button').addClass('c2c-remove-img').css({position:'absolute',top:'-6px',right:'-6px',background:'#d63638',color:'#fff',border:'none',borderRadius:'50%',width:'20px',height:'20px',cursor:'pointer',fontSize:'14px',lineHeight:'1'}).html('&times;');
                            var $div = $('<div>').addClass('c2c-gallery-thumb').attr('data-id', att.id).css({position:'relative'}).append($img, $btn);
                            $('#c2c-gallery-preview').append($div);
                        }
                    });

                    $('#c2c_gallery_ids').val(currentIds.join(','));
                });

                frame.open();
            });

            $('#c2c-gallery-preview').on('click', '.c2c-remove-img', function(){
                var $thumb = $(this).closest('.c2c-gallery-thumb');
                var removeId = String($thumb.data('id'));
                var currentIds = $('#c2c_gallery_ids').val().split(',').filter(function(id){ return id !== removeId; });
                $('#c2c_gallery_ids').val(currentIds.join(','));
                $thumb.remove();
            });
        });
        </script>
        <?php
    }

    /** Save meta box data. */
    public function save( $post_id ) {
        // Security checks
        if ( ! isset( $_POST['c2c_property_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['c2c_property_nonce'] ) ), 'c2c_property_save' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }

        // Valid select options for validation
        $valid_types     = array( '', 'Apartment', 'Villa', 'Penthouse', 'Townhouse', 'Land', 'Commercial' );
        $valid_locations = array( '', 'Bahia Dorada', 'Cancelada', 'Casares', 'Casares Playa', 'Doña Julia', 'El Paraiso', 'Estepona', 'La Alcaidesa', 'La Duquesa', 'La Noria', 'Manilva', 'Pueblo Nuevo de Guadiaro', 'Punta Chullera', 'San Diego', 'San Enrique', 'San Luis de Sabinillas', 'San Martín de Tesorillo', 'San Roque', 'Sotogrande', 'Torreguadiaro', 'Valle Romano' );
        $valid_ratings   = array( '', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'Awaiting Information' );

        foreach ( $this->fields as $field ) {
            if ( ! isset( $_POST[ $field['id'] ] ) ) {
                continue;
            }
            $raw = wp_unslash( $_POST[ $field['id'] ] );

            switch ( $field['type'] ) {
                case 'number':
                    $value = is_numeric( $raw ) ? floatval( $raw ) : '';
                    break;
                case 'url':
                    $value = esc_url_raw( $raw );
                    break;
                case 'select':
                    if ( '_c2c_property_type' === $field['id'] ) {
                        $value = in_array( $raw, $valid_types, true ) ? $raw : '';
                    } elseif ( '_c2c_location' === $field['id'] ) {
                        $value = in_array( $raw, $valid_locations, true ) ? $raw : '';
                    } elseif ( '_c2c_energy_rating' === $field['id'] ) {
                        $value = in_array( $raw, $valid_ratings, true ) ? $raw : '';
                    } else {
                        $value = sanitize_text_field( $raw );
                    }
                    break;
                default:
                    $value = sanitize_text_field( $raw );
                    break;
            }

            update_post_meta( $post_id, $field['id'], $value );
        }

        // Gallery IDs
        if ( isset( $_POST['_c2c_gallery_ids'] ) ) {
            $ids = array_filter( array_map( 'intval', explode( ',', wp_unslash( $_POST['_c2c_gallery_ids'] ) ) ) );
            update_post_meta( $post_id, '_c2c_gallery_ids', implode( ',', $ids ) );
        }
    }
}
