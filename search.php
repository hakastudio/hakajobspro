<?php
/**
 * search.php — Hasil pencarian
 */
get_header(); ?>
<div class="container">
<div class="layout">
<main role="main">
  <div class="search-results-header">
    <?php if ( get_search_query() ) : ?>
      <?php printf(
          esc_html__( 'Menampilkan %s hasil untuk: ', 'hakajobspro' ),
          '<strong>' . (int) $wp_query->found_posts . '</strong>'
      ); ?>
      <strong>"<?php echo esc_html( get_search_query() ); ?>"</strong>
    <?php endif; ?>
  </div>

  <?php if ( have_posts() ) : ?>
  <div class="loker-list">
    <?php while ( have_posts() ) : the_post();
      if ( get_post_type() === 'loker' ) :
        if ( hakajobspro_is_expired( get_the_ID() ) && get_theme_mod( 'hakajobspro_hide_expired_loker', true ) ) {
            continue;
        }
        set_query_var( 'hakajobspro_view', 'list' );
        get_template_part( 'template-parts/content', 'loker' );
      else :
    ?>
    <article <?php post_class( 'loker-list-item' ); ?>>
      <div class="loker-list-item__body" style="grid-template-columns:1fr;">
        <div>
          <h2 class="loker-list-item__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <p style="font-size:13px;color:var(--color-muted);margin-top:5px;"><?php the_excerpt(); ?></p>
        </div>
      </div>
    </article>
    <?php endif; endwhile; ?>
  </div>
  <?php hakajobspro_pagination(); ?>
  <?php else : ?>
  <div class="not-found">
    <h2><?php esc_html_e( 'Tidak ada hasil ditemukan', 'hakajobspro' ); ?></h2>
    <p><?php esc_html_e( 'Coba kata kunci yang berbeda atau gunakan filter di halaman lowongan.', 'hakajobspro' ); ?></p>
    <a href="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" class="btn btn--primary">
      <?php esc_html_e( 'Lihat Semua Lowongan', 'hakajobspro' ); ?>
    </a>
  </div>
  <?php endif; ?>
</main>
<?php get_sidebar(); ?>
</div>
</div>
<?php get_footer(); ?>

