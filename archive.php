<?php
/**
 * archive.php — Halaman arsip kategori/tag untuk Pos Artikel (Tips Karier / Blog)
 * Terpisah sepenuhnya dari archive-loker.php.
 */
get_header();
?>

<div class="container">
<div class="layout">

  <main role="main">

    <div class="archive-header">
      <h1><?php the_archive_title(); ?></h1>
      <?php if ( get_the_archive_description() ) : ?>
        <p><?php echo wp_kses_post( get_the_archive_description() ); ?></p>
      <?php else : ?>
        <p><?php esc_html_e( 'Kumpulan artikel, panduan melamar kerja, dan tips pengembangan karier.', 'hakajobspro' ); ?></p>
      <?php endif; ?>
    </div>

    <?php if ( have_posts() ) : ?>
      <div class="loker-grid">
        <?php while ( have_posts() ) : the_post();
          $cats = get_the_category();
        ?>
        <article <?php post_class( 'loker-card' ); ?>>
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>">
              <?php the_post_thumbnail( 'medium', [ 'style' => 'width:100%;height:160px;object-fit:cover;border-radius:var(--radius-sm);' ] ); ?>
            </a>
          <?php endif; ?>

          <?php if ( ! empty( $cats ) ) : ?>
          <div class="loker-card__badges">
            <span class="badge badge--tipe"><?php echo esc_html( $cats[0]->name ); ?></span>
          </div>
          <?php endif; ?>

          <h2 class="loker-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>

          <p style="font-size:13px;color:var(--color-muted);margin:0;">
            <?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?>
          </p>

          <div class="loker-card__footer">
            <span class="loker-card__deadline"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
            <a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
              <?php esc_html_e( 'Baca Artikel', 'hakajobspro' ); ?>
            </a>
          </div>
        </article>
        <?php endwhile; ?>
      </div>

      <?php hakajobspro_pagination(); ?>
    <?php else : ?>
      <div class="not-found">
        <h2><?php esc_html_e( 'Belum ada artikel di rubrik ini', 'hakajobspro' ); ?></h2>
        <p><?php esc_html_e( 'Artikel tips karier yang Anda terbitkan melalui menu Pos akan tampil di sini.', 'hakajobspro' ); ?></p>
      </div>
    <?php endif; ?>

  </main>

  <?php get_sidebar(); ?>

</div>
</div>

<?php get_footer(); ?>

