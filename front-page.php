<?php
/**
 * front-page.php — Halaman Beranda
 */
get_header();
?>

<!-- HERO -->
<section class="hero" style="background:<?php echo esc_attr( get_theme_mod( 'hakajobspro_hero_bg', '#f0f4f8' ) ); ?>;"
         aria-labelledby="hero-heading">
  <div class="container">
    <h1 id="hero-heading"><?php echo esc_html( get_theme_mod( 'hakajobspro_hero_title', __( 'Temukan Pekerjaan Impian Anda', 'hakajobspro' ) ) ); ?></h1>
    <p class="hero__subtitle">
      <?php echo esc_html( get_theme_mod( 'hakajobspro_hero_subtitle', __( 'Ribuan lowongan kerja dari perusahaan terpercaya menanti Anda.', 'hakajobspro' ) ) ); ?>
    </p>

    <!-- Search bar hero -->
    <div class="hero-search">
      <form method="get" action="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" role="search">
        <input
          type="search"
          name="s"
          value="<?php echo esc_attr( get_search_query() ); ?>"
          placeholder="<?php esc_attr_e( 'Posisi, jabatan, atau perusahaan...', 'hakajobspro' ); ?>"
          aria-label="<?php esc_attr_e( 'Kata kunci pencarian', 'hakajobspro' ); ?>"
        >
        <input type="hidden" name="post_type" value="loker">

        <?php
        $lokasi_terms = get_terms( [ 'taxonomy' => 'loker_lokasi', 'hide_empty' => true ] );
        if ( $lokasi_terms && ! is_wp_error( $lokasi_terms ) ) :
        ?>
        <select name="loker_lokasi" aria-label="<?php esc_attr_e( 'Filter kota', 'hakajobspro' ); ?>">
          <option value=""><?php esc_html_e( 'Semua Kota', 'hakajobspro' ); ?></option>
          <?php foreach ( $lokasi_terms as $term ) : ?>
            <option value="<?php echo esc_attr( $term->slug ); ?>">
              <?php echo esc_html( $term->name ); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php endif; ?>

        <button type="submit"><?php esc_html_e( 'Cari Loker', 'hakajobspro' ); ?></button>
      </form>
    </div>

    <?php
    $cta_text = get_theme_mod( 'hakajobspro_hero_cta_text', __( 'Lihat Semua Lowongan', 'hakajobspro' ) );
    $cta_url  = get_theme_mod( 'hakajobspro_hero_cta_url', get_post_type_archive_link( 'loker' ) );
    if ( $cta_text && $cta_url ) :
    ?>
    <div class="hero-cta">
      <a href="<?php echo esc_url( $cta_url ); ?>" class="btn btn--dark btn--lg">
        <?php echo esc_html( $cta_text ); ?>
      </a>
    </div>
    <?php endif; ?>
  </div>
</section>

<div id="homepage-content">
<div class="container">

  <?php if ( get_theme_mod( 'hakajobspro_show_category_filter', true ) ) :
    $all_cats = get_terms( [ 'taxonomy' => 'loker_kategori', 'hide_empty' => true ] );
    if ( $all_cats && ! is_wp_error( $all_cats ) ) :
  ?>
  <div class="category-filter" style="padding-top:20px;" role="navigation" aria-label="<?php esc_attr_e( 'Filter kategori', 'hakajobspro' ); ?>">
    <a href="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" class="cat-chip active">
      <?php esc_html_e( 'Semua', 'hakajobspro' ); ?>
    </a>
    <?php foreach ( $all_cats as $cat ) : ?>
    <a href="<?php echo esc_url( get_term_link( $cat ) ); ?>" class="cat-chip">
      <?php echo esc_html( $cat->name ); ?>
      <span>(<?php echo (int) $cat->count; ?>)</span>
    </a>
    <?php endforeach; ?>
  </div>
  <?php endif; endif; ?>

  <!-- LOWONGAN UNGGULAN -->
  <?php
  $featured_count = absint( get_theme_mod( 'hakajobspro_featured_count', 6 ) );
  $featured_query = new WP_Query( [
      'post_type'      => 'loker',
      'posts_per_page' => $featured_count,
      'post_status'    => 'publish',
      'meta_query'     => [ [ 'key' => '_loker_featured', 'value' => 'Ya' ] ],
      'orderby'        => 'date',
      'order'          => 'DESC',
  ] );
  if ( $featured_query->have_posts() ) :
  ?>
  <section style="padding-top:24px;" aria-labelledby="featured-heading">
    <div class="section-head">
      <h2 id="featured-heading">
        <?php echo esc_html( get_theme_mod( 'hakajobspro_section_featured_title', __( 'Lowongan Unggulan', 'hakajobspro' ) ) ); ?>
      </h2>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" class="see-all">
        <?php esc_html_e( 'Lihat Semua', 'hakajobspro' ); ?> &rsaquo;
      </a>
    </div>

    <div class="loker-grid">
      <?php while ( $featured_query->have_posts() ) : $featured_query->the_post();
        set_query_var( 'hakajobspro_view', 'grid' );
        get_template_part( 'template-parts/content', 'loker' );
      endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- LOWONGAN TERBARU -->
  <?php
  $latest_count = absint( get_theme_mod( 'hakajobspro_latest_count', 9 ) );
  $latest_query = new WP_Query( [
      'post_type'      => 'loker',
      'posts_per_page' => $latest_count,
      'post_status'    => 'publish',
      'orderby'        => 'date',
      'order'          => 'DESC',
  ] );
  if ( $latest_query->have_posts() ) :
  ?>
  <section style="padding-top:32px;padding-bottom:20px;" aria-labelledby="latest-heading">
    <div class="section-head">
      <h2 id="latest-heading">
        <?php echo esc_html( get_theme_mod( 'hakajobspro_section_latest_title', __( 'Lowongan Terbaru', 'hakajobspro' ) ) ); ?>
      </h2>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'loker' ) ); ?>" class="see-all">
        <?php esc_html_e( 'Lihat Semua', 'hakajobspro' ); ?> &rsaquo;
      </a>
    </div>

    <div class="loker-list">
      <?php while ( $latest_query->have_posts() ) : $latest_query->the_post();
        set_query_var( 'hakajobspro_view', 'list' );
        get_template_part( 'template-parts/content', 'loker' );
      endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endif; ?>

  <!-- SEKSI KHUSUS TIPS KARIER (POS ARTIKEL WP — TERPISAH DARI LOKER) -->
  <?php
  if ( get_theme_mod( 'hakajobspro_show_tips_home', true ) ) :
    $tips_query = new WP_Query( [
        'post_type'           => 'post',
        'posts_per_page'      => 3,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
    ] );
    if ( $tips_query->have_posts() ) :
  ?>
  <section style="padding-top:24px;padding-bottom:28px;" aria-labelledby="tips-heading">
    <div class="section-head">
      <h2 id="tips-heading">
        <?php echo esc_html( get_theme_mod( 'hakajobspro_section_tips_title', __( 'Tips Karier & Artikel Terbaru', 'hakajobspro' ) ) ); ?>
      </h2>
    </div>

    <div class="loker-grid">
      <?php while ( $tips_query->have_posts() ) : $tips_query->the_post();
        $cats = get_the_category();
      ?>
      <article <?php post_class( 'loker-card' ); ?>>
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>">
            <?php the_post_thumbnail( 'medium', [ 'style' => 'width:100%;height:155px;object-fit:cover;border-radius:var(--radius-sm);' ] ); ?>
          </a>
        <?php endif; ?>

        <?php if ( ! empty( $cats ) ) : ?>
        <div class="loker-card__badges">
          <span class="badge badge--tipe"><?php echo esc_html( $cats[0]->name ); ?></span>
        </div>
        <?php endif; ?>

        <h3 class="loker-card__title">
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <p style="font-size:13px;color:var(--color-muted);margin:0;">
          <?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?>
        </p>

        <div class="loker-card__footer">
          <span class="loker-card__deadline"><?php echo esc_html( get_the_date( 'd M Y' ) ); ?></span>
          <a href="<?php the_permalink(); ?>" class="btn btn--outline btn--sm">
            <?php esc_html_e( 'Baca Artikel', 'hakajobspro' ); ?>
          </a>
        </div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </section>
  <?php endif; endif; ?>

</div>
</div>

<?php get_footer(); ?>

