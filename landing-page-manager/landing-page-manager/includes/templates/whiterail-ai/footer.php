        </main>

        <?php
        use \LPManager\assets\Assets_Manager;
        use LPManager\core\Google_Maps_Helper;
        
        // Get term ID as described above
        $current_post_id = get_the_ID();
	    $client_terms = get_the_terms($current_post_id, 'client');
        	// Get carbon fields
        if (!empty($client_terms) && !is_wp_error($client_terms)) {
            $client_term = $client_terms[0]; // assuming one client per landing page
            $client_term_id = $client_term->term_id;
            // Get taxonomy meta (only if valid term ID)
            $client_address = $client_term_id ? carbon_get_term_meta($client_term_id, 'client_address') : '';
            $client_place_id = $client_term_id ? carbon_get_term_meta($client_term_id, 'client_place_id') : '';
            $client_google_search_url = $client_term_id ? carbon_get_term_meta($client_term_id, 'client_google_search_url') : '';
            if ($client_place_id) {
                $gmb_url = Google_Maps_Helper::get_gmb_url($client_place_id);
            } else {
                $gmb_url = $client_google_search_url;
            }
            $client_tel = carbon_get_the_post_meta('client_tel');
            $client_primary_color = $client_term_id ? carbon_get_term_meta($client_term_id, 'client_primary_color') : '#007BFF'; // fallback color: web-safe blue
            $client_secondary_color = $client_term_id ? carbon_get_term_meta($client_term_id, 'client_secondary_color') : '#FF4136'; // fallback color: web-safe red
            $client_logo_id = $client_term_id ? carbon_get_term_meta($client_term_id, 'client_logo') : '';
            if ($client_logo_id) {
                $client_logo_url = wp_get_attachment_image_url($client_logo_id, 'full'); // or 'large', 'medium'
            } else {
                $client_logo_url = ''; // or your fallback image URL
            }
            $social_links = $client_term_id ? carbon_get_term_meta($client_term_id, 'social_links') : [];
            // Theme option for API key (always global)
            $google_maps_api_key = carbon_get_theme_option('lpmanager_google_maps_api_key');

            $footer_text_color = Assets_Manager::get_contrast_text_color($client_primary_color);
            $copyright_text_color = Assets_Manager::get_contrast_text_color($client_secondary_color);
            error_log("Footer Contrast Color: " . $footer_text_color);
        }
        ?>

        <footer class="site-footer" style="max-width: 100%; color:<?php echo esc_attr($footer_text_color); ?>";>

        <!-- Google Map -->
        <?php
        // Step 1: Get client term ID linked to current landing page
        $post_id = get_the_ID();
        $client_terms = wp_get_post_terms($post_id, 'client');

        if (!is_wp_error($client_terms) && !empty($client_terms)) {
            $term_id = $client_terms[0]->term_id;

            // Step 2: Fetch lat/lng from term meta
            $lat = carbon_get_term_meta($term_id, 'client_address_lat');
            $lng = carbon_get_term_meta($term_id, 'client_address_lng');
            $api_key = carbon_get_theme_option('lpmanager_google_maps_api_key');

            if ($lat && $lng && $api_key) :
            ?>
                <div id="footer-map" style="width: 100%; height: 400px;"></div>
                <script>
                    function initFooterMap() {
                        var mapOptions = {
                            center: { lat: <?php echo floatval($lat); ?>, lng: <?php echo floatval($lng); ?> },
                            zoom: 14,
                            disableDefaultUI: true,
                            styles: [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}]
                        };
                        var map = new google.maps.Map(document.getElementById("footer-map"), mapOptions);

                        var marker = new google.maps.Marker({
                            position: mapOptions.center,
                            map: map,
                            title: "<?php echo esc_js($client_name); ?>" // if you have this defined
                        });

                        var infoWindowContent = `
                            <div style="max-width:200px;">
                                <h3 style="margin-top:0;"><?php echo esc_html($client_name); ?></h3>
                                <p><?php echo esc_html($client_address); ?></p>
                                <p><a href="tel:<?php echo esc_attr($client_phone); ?>"><?php echo esc_html($client_phone); ?></a></p>
                            </div>
                        `;

                        var infoWindow = new google.maps.InfoWindow({
                            content: infoWindowContent
                        });

                        // Open on marker click
                        marker.addListener("click", function() {
                            infoWindow.open(map, marker);
                        });

                        // Optionally auto-open on load
                        infoWindow.open(map, marker);
                    }
                </script>
                <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($api_key); ?>&callback=initFooterMap" async defer></script>
            <?php
            else :
            ?>
                <p style="text-align:center; color:#fff;">Map unavailable — missing latitude, longitude, or API key.</p>
            <?php
            endif;
        } else {
            echo '<p style="text-align:center; color:#fff;">No client assigned to this landing page.</p>';
        }
        ?>

        <!-- Footer Main Section -->
        <div class="footer-main" style="background-color: <?php echo esc_attr($client_primary_color); ?>" style="color: <?php echo esc_attr($contrast_text_color); ?>";>

            <div class="footer-container">

                <!-- Column 1: Logo -->
                <div class="footer-col footer-logo">
                    <a href="<?php echo esc_url($client_domain); ?>">
                        <?php if ($client_logo_url) : ?>
                            <img src="<?php echo esc_url($client_logo_url); ?>" alt="Logo" />
                        <?php else : ?>
                            <span>Logo</span>
                        <?php endif; ?>
                    </a>
                </div>

                <!-- Column 3: Hours -->
                <div class="footer-col">
                    <h3>Hours of Operation</h3>
                    <table class="hours-table">
                        <tbody>
                            <?php 
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            foreach ($days as $day) {
                                echo '<tr><td>' . esc_html($day) . '</td><td>Open 24 Hours</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <!-- Column 4: Contact -->
                <div class="footer-col">
                    <h3>Contact</h3>
                    <p><a href="<?php echo $gmb_url ?>" target="_blank" style="color: <?php echo esc_attr($contrast_text_color); ?>" rel="nofollow"><?php echo esc_html($client_address); ?></a></p>
                    <?php if (!empty($client_tel)) : ?>
                    <p><a href="tel:<?php echo esc_attr($client_tel); ?>" style="color: <?php echo esc_attr($contrast_text_color); ?>"><?php echo esc_html($client_tel); ?></a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="footer-bottom" style="background-color:<?php echo esc_attr($client_secondary_color); ?>;">
            <div class="footer-container">
                <div class="copyright" style="color:<?php echo esc_attr($copyright_text_color); ?>;">
                    &copy; <?php echo date('Y'); ?> <?php echo esc_html(get_bloginfo('name')); ?>. All rights reserved.
                </div>

                <?php if (!empty($social_links) && is_array($social_links)) : ?>
                    <div class="social-icons">
                        <?php foreach ($social_links as $link) : 
                        $icon_class = trim($link['platform'] ?? '');
                        $url        = trim($link['url'] ?? '');
                        if (empty($url) || empty($icon_class)) {
                            error_log('Skipped link: ' . print_r($link, true));
                            continue;
                        }
                        ?>
                        <li class="social-links__item">
                            <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="<?php echo esc_attr($icon_class); ?>" style="color: <?php echo esc_attr($copyright_text_color); ?>" aria-hidden="true"></i>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </footer>

		<?php do_action( 'wp_footer' ); ?>
	</body>
</html>