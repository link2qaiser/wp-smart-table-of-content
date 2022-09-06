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
      echo preg_replace('/(.*?):/', "<font color=#F00>$1</font>:", $output);

      $post = get_post();
      $list = getHeadings($post->post_content);
      $data = array_reduce($list, function ($a, $b) {
          return @$a['total'] > $b['total'] ? $a : $b ;
      });
      echo '<ul class="sm-tb-con">';
      foreach($data['headings'] as $key=>$value) {
         echo '<li><a "#'.$value.'">'.$value.'</a></li>';
      }
      echo '</ul>';
   }
}