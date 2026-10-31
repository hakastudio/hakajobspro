<?php
/**
 * single.php — Template khusus untuk membaca artikel Pos bawaan WP (Tips Karier / Blog)
 * Terpisah sepenuhnya dari single-loker.php (Detail Lowongan Kerja).
 */
get_header();

while ( have_posts() ) : the_post();
    $post_id = get_the_ID();
    $cats    = get_the_category();
?>

<div class="container">
<div class="layout">

  <main role="main">

    <article <?php post_class( 'single-post-article' ); ?>>

      <header class="entry-header">
        <?php if ( ! empty( $cats ) ) : ?>
        <div class="loker-card__badges" style="margin-bottom:8px;">
          <?php foreach ( $cats as $c ) : ?>
            <a href="<?php echo esc_url( get_category_link( $c->term_id ) ); ?>" class="badge badge--tipe">
              <?php echo esc_html( $c->name ); ?>
            </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <h1 class="entry-title"><?php the_title(); ?></h1>

        <div class="entry-meta">
          <span><?php echo esc_html( get_the_date( 'd F Y' ) ); ?></span>
          &nbsp;&bull;&nbsp;
          <span><?php printf( esc_html__( 'Oleh %s', 'hakajobspro' ), esc_html( get_the_author() ) ); ?></span>
        </div>
      </header>

      <?php if ( has_post_thumbnail() ) : ?>
      <div style="background:white;border-left:1px solid var(--color-border);border-right:1px solid var(--color-border);padding:0 28px;">
        <?php the_post_thumbnail( 'large', [ 'style' => 'width:100%;height:auto;border-radius:var(--radius-md);' ] ); ?>
      </div>
      <?php endif; ?>

      <div class="entry-content">
        <?php
        the_content();

        wp_link_pages( [
            'before' => '<div class="page-links">' . esc_html__( 'Halaman:', 'hakajobspro' ),
            'after'  => '</div>',
        ] );
        ?>

        <!-- Tags artikel -->
        <?php
        $tags = get_the_tags();
        if ( $tags ) :
        ?>
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--color-border);display:flex;gap:6px;flex-wrap:wrap;align-items:center;">
          <strong style="font-size:13px;color:var(--color-muted);margin-right:4px;"><?php esc_html_e( 'Topik:', 'hakajobspro' ); ?></strong>
          <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="cat-chip" style="text-decoration:none;padding:4px 10px;font-size:12px;">
              <?php echo esc_html( $tag->name ); ?>
            </a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>

    </article>

    <!-- Artikel Tips Karier Terkait -->
    <?php
    $cat_ids = wp_list_pluck( $cats ?: [], 'term_id' );
    $rel_posts = new WP_Query( [
        'post_type'           => 'post',
        'posts_per_page'      => 3,
        'post_status'         => 'publish',
        'post__not_in'        => [ $post_id ],
        'category__in'        => $cat_ids,
        'ignore_sticky_posts' => true,
    ] );
    if ( $rel_posts->have_posts() ) :
    ?>
    <section style="margin-top:28px;" aria-labelledby="related-posts-heading">
      <div class="section-head">
        <h2 id="related-posts-heading"><?php esc_html_e( 'Artikel & Tips Karier Lainnya', 'hakajobspro' ); ?></h2>
      </div>
      <div class="loker-grid">
        <?php while ( $rel_posts->have_posts() ) : $rel_posts->the_post(); ?>
        <article class="loker-card">
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( 'medium', [ 'style' => 'width:100%;height:150px;object-fit:cover;border-radius:var(--radius-sm);' ] ); ?>
            </a>
          <?php endif; ?>
          <h3 class="loker-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
          <div class="loker-card__footer">
            <span class="loker-card__deadline"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
            <a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm"><?php esc_html_e( 'Baca', 'hakajobspro' ); ?></a>
          </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </section>
    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>

</div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>

