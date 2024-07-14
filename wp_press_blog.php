<?php 
 
/*
 
Plugin Name: WP Press Blog
Plugin URI: https://github.com/khadijahr/WP-Press-Blog
Description: Display all Artices of Press with hover for to get the post details.   
Version: 1.2.4
Author: Khadija Harmouche
Author URI: https://github.com/khadijahr 
License: GPLv2 or later 
Requires at least: 	6.0
Requires PHP:      	7.0
 
*/

function enqueue_custom_scripts() {  
  wp_enqueue_style('my_style', plugin_dir_url(__FILE__) . 'assets/css/my_style.css', '', time()); 
  wp_enqueue_script('jquery');
  wp_enqueue_script('press', plugin_dir_url(__FILE__) . 'assets/js/press.js', array(), null, false );  
  }
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');


// Create custom post type "Presse"
function create_presse_post_type() {
  $args = array(
      'labels' => array(
          'name' => 'Presse',
          'singular_name' => 'Presse',
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'presse'),
      'menu_icon' => 'dashicons-media-document', 
      'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
      // 'taxonomies'  => array('category'),     
  );
  register_post_type('presse', $args);
}
add_action('init', 'create_presse_post_type');


/*************************************************************************** */


// Add AJAX action to get post details
function hd_get_post_details() {
  $post_id = intval($_POST['post_id']);
  $post = get_post($post_id);

  if ($post) {
      $response = array(
          'title' => get_the_title($post_id),
          'content' => get_the_excerpt($post_id),
          'link' => get_permalink($post_id),
      );
      wp_send_json_success($response);
  } else {
      wp_send_json_error();
  }
}
add_action('wp_ajax_hd_get_post_details', 'hd_get_post_details');
add_action('wp_ajax_nopriv_hd_get_post_details', 'hd_get_post_details');

// Add hover details container
function hd_add_hover_details_container() {
  echo '<div id="hover-details-container"></div>';
}
add_action('wp_footer', 'hd_add_hover_details_container');


// Shortcode to display posts from custom post type "presse"
function hd_display_posts_shortcode() {  

  $args = array(
    'post_type' => 'presse',
    'posts_per_page' => 40,
    'orderby' => 'date',
    'order' => 'DESC',
  );


  // Création de la requête WP_Query
  $query = new WP_Query($args);
  ob_start();
  echo '<div class="news-article">
          <div class="ant-row ant-row-center styles__ArticleRow-sc-1wbeypc-0 hUyWwh">';
  if ($query->have_posts()) {
      echo '<div class="ant-col justify-center border-left ant-col-xs-25 ant-col-sm-25 ant-col-md-10 ant-col-lg-10 hd-posts-container">
       <div class="styles__InfoWrapper-sc-1wbeypc-1 imEQUr">';

      while ($query->have_posts()) {
          $query->the_post();

          $ID = get_the_ID();  
          $title = get_the_title();       
          $content = get_the_content();
          $image = get_the_post_thumbnail_url($ID, 'full'); // Taille complète de l'image
          $link_press = get_field('lien_press', $ID);  
          $image_background = get_field('image_background', $ID);  
  
          echo '<div class="ant-row styles__ArticleWrapper-sc-1kpdw43-0 kmXVUK wrapper-article" 
                    data-title="' . esc_attr($title) . '" 
                    data-image="' . esc_attr($image_background) . '" 
                    data-content="' . esc_attr($content) . '" 
                    data-link="' . esc_url($link_press) . '">
                  <div class="ant-col justify-center">
                      <div class="style-article">                          
                           <a href="' . $link_press . '" target="_blank" class="AsVek">
                             <h3 class="styles__Title-sc-1kpdw43-2 fBsVek">' . $title . '</h3>
                           </a> 
                          <div class="styles__SubTitle-sc-1kpdw43-3 bmtUhg">' . $content . '</div>
                      </div>
                      <img class="styles__ArticleImage-sc-1kpdw43-1 kBPrgp" src="' . $image . '" alt="' . $title . '">
                  </div>                  
                  <a href="' . $link_press . '" target="_blank">
                      <button class="styles__Button-sc-1kpdw43-4 jVJjEx">Lire Article</button>
                  </a>        
                </div>';
      }
      wp_reset_postdata();
      echo '</div>
      <div class="ant-col justify-center border-right ant-col-xs-23 ant-col-sm-23 ant-col-md-8 ant-col-lg-12">
          <div class="styles__ImageWrapper-sc-1wbeypc-2 cmKIsl">
              <div class="ant-col wrapper-article scroll-article">
                  <div class="style-article">
                      <h3 class="styles__Title-sc-1nfpysv-1 erwmvL">E-paiement : objectif « Maroc sans cash » pour Naps</h3>
                      <div class="styles__SubTitle-sc-1nfpysv-3 iAgzhV">Naps, l’acteur national majeur de la dématérialisation du cash et du e-paiement multiplie ses efforts pour atteindre son objectif d’un «Maroc sans cash». S’appuyant sur sa force d’innovation dans le d...</div>
                      <a rel="noreferrer" Target="_blank" href="">
                        <button class="styles__Button-sc-1nfpysv-4 ZfHau">Lire Article</button>
                      </a>
                  </div>
              </div>
          </div>
      </div>';
  }
  echo '
  </div></div>';
  return ob_get_clean();
}
add_shortcode('display_list_presse', 'hd_display_posts_shortcode');


?>