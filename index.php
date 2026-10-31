<?php
/**
 * index.php — Fallback template (post blog standar)
 */
get_header(); ?>
<div class="container">
<div class="layout">
<main role="main">
  <?php if ( have_posts() ) : ?>
    <div class="loker-list">
      <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class( 'loker-list-item' ); ?>>
        <div class="loker-list-item__body" style="grid-template-columns:1fr;">
          <div>
            <h2 class="loker-list-item__title">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </h2>
            <div class="loker-list-item__company"><?php echo esc_html( get_the_date() ); ?></div>
            <?php if ( has_excerpt() ) : ?>
              <p style="margin-top:6px;font-size:13px;color:var(--color-muted);"><?php the_excerpt(); ?></p>
            <?php endif; ?>
          </div>
        </div>
      </article>
      <?php endwhile; ?>
    </div>
    <?php hakajobspro_pagination(); ?>
  <?php else : ?>
    <div class="not-found">
      <h2><?php esc_html_e( 'Belum ada konten.', 'hakajobspro' ); ?></h2>
      <p><?php esc_html_e( 'Tambahkan lowongan kerja pertama Anda melalui dashboard WP.', 'hakajobspro' ); ?></p>
    </div>
  <?php endif; ?>
</main>
<?php get_sidebar(); ?>
</div>
</div>
<?php get_footer(); ?>

