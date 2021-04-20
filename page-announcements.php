<?php
get_header();
?>
<div class="container">
  <div class="row">
  <?php 
  $current = absint(
    max(
      1,
      get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' )
    )
  );
  $args = [
    'post_type' => 'announcement',
    'posts_per_page' => 2,
    'paged' => $current,
    'order' => 'ASC',
    'meta_query' => [
      [
        'key' => 'price_meta_key',
        'value' => '100',
        'compare' => '!=',
      ]
    ]
  ];
  $query = new WP_Query( $args );
  while ( $query->have_posts() ) {
    $query->the_post();
    $price = get_post_meta( $post->ID, 'price_meta_key', true );
    $price = !$price == '' ? $price : 'Free';
  ?>
    <div class="card">
      <div class="card__title">
        <a href="<?php the_permalink(); ?>"><?php echo charlimit( get_the_title(), 65 ); ?></a>
      </div>  
      <div class="card__description">
        <?php echo wp_trim_words( get_the_excerpt(), 10, ' ...' ); ?>
      </div>
      <div class="card__image">
        <?php the_post_thumbnail( 'medium' ); ?>
      </div>
      <div class="card__price">
        <?php echo esc_html( $price ); ?>
      </div>
    </div>
  <?php  
  }
  wp_reset_postdata();
  ?>
  </div>
  <?php 
  echo wp_kses_post(
    paginate_links(
      [
        'total'   => $query->max_num_pages,
        'current' => $current,
      ]
    )
  );
  ?>
</div>

<?php 
get_footer();