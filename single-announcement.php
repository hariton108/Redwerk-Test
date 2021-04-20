<?php
get_header();
?>
<div class="container">
  <?php 
  if ( have_posts() ) : while ( have_posts() ) : the_post(); 
  the_title();
  $views = get_post_meta( $post->ID, '_views_count', true );
  $views = ( empty( $views ) ) ? 0 : $views;
  echo '<div>Просмотров: ' . $views . '</div>';
  ?>
  <script>
    var send_pid_view = <?php the_ID(); ?>;
  </script>
  <?php 
  endwhile; endif;
  ?>
  <div>
    <?php echo do_shortcode( '[announcements_amount]' ); ?>
  </div>
</div>
<script>
  jQuery(document).ready(function($){
 
    if ( typeof( send_pid_view ) === 'undefined' ) {
      return;
    }

    $.ajax({
      url: '/wp-admin/admin-ajax.php',
      type: 'POST', 
      data: {
        action: 'post_view_set',
        pid: send_pid_view
      },
    }); 
  });
</script>

<?php 
get_footer();