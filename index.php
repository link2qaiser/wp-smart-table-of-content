<?php
   /*
   Plugin Name: Smart Table of Conents 
   Plugin URI: http://dixeam.com
   Description: A Smart Plugin that detect the heading in article and generate the table of content
   Version: 1.0
   Author: Dixeam Developer- Qaiser
   Author URI: http://dixeam.com
   License: GPL2
   */
if ( ! is_admin() ) {
   add_filter( 'the_content', 'update_post_conent', 1 );

   function update_post_conent( $content ) {
      for($i = 1; $i <= 5; $i++) {
         $headings = [];
         preg_match_all('/<h'.$i.'>.*<\/h'.$i.'>/i',$content,$html);
         if(isset($html[0])) {
            foreach ($html[0] as $key => $value) {
               $content = preg_replace('/(<h3\b[^><]*)>.*'.strip_tags($value).'/i', '$1 id="'.strip_tags($value).'">'.strip_tags($value), $content);
            }
         }
      }
      return $content;
   }
   add_shortcode( 'sm-content', 'load_conent' );
   function getHeadings($string) {
      $results = [];
      for($i = 1; $i <= 5; $i++) {
         $headings = [];
         preg_match_all('/<h'.$i.'>.*<\/h'.$i.'>/i',$string,$html);

         if(isset($html[0])) {
            foreach ($html[0] as $key => $value) {
               $headings[] = strip_tags($value);
            }

            $results['h'.$i] = ['total'=>count($headings), "headings"=>$headings];
         }
      }
      return $results;
   }
   function load_conent($atts) {

      
      /*
      Get the current post
      */

      $post = get_post();
      
      /*
      Get all headings
      */
      $list = getHeadings($post->post_content);

      $data = array_reduce($list, function ($a, $b) {
          return @$a['total'] > $b['total'] ? $a : $b ;
      });
      ob_start();
      echo '<ul class="sm-tb-con">';
      foreach($data['headings'] as $key=>$value) {
         echo '<li><a href="#'.$value.'">'.$value.'</a></li>';
      }
      echo '</ul>';
      return ob_get_clean();
   }
   
}