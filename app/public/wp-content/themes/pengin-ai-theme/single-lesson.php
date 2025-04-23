<?php
/**
 * レッスン詳細表示用テンプレート
 *
 * @package PENGIN_AI
 */

get_header();

// 親コース情報の取得
$parent_course_id = get_field('parent_course');
if (is_array($parent_course_id)) {
    $parent_course_id = isset($parent_course_id[0]) ? $parent_course_id[0] : null;
}
$parent_course = $parent_course_id ? get_post($parent_course_id) : null;

// タグと職種の取得
$tags = get_the_terms(get_the_ID(), 'lesson_tag');
$professions = get_the_terms(get_the_ID(), 'profession');
?>

<div class="lesson-single-page">
    <div class="container">
        <!-- パンくずリスト -->
        <div class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>">ホーム</a> &gt;
            <?php if ($parent_course): ?>
                <a href="<?php echo esc_url(get_permalink($parent_course->ID)); ?>"><?php echo esc_html($parent_course->post_title); ?></a> &gt;
            <?php endif; ?>
            <span><?php the_title(); ?></span>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- レッスン本文 -->
                <article id="post-<?php the_ID(); ?>" <?php post_class('lesson-main'); ?>>
                    <!-- レッスンヘッダー -->
                    <header class="lesson-header">
                        <h1 class="lesson-title"><?php the_title(); ?></h1>

                        <?php if (!empty($professions) && !is_wp_error($professions)): ?>
                            <div class="lesson-profession">
                                <?php foreach ($professions as $profession): ?>
                                    <span class="profession-badge"><?php echo esc_html($profession->name); ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($tags) && !is_wp_error($tags)): ?>
                            <div class="lesson-tags">
                                <?php foreach ($tags as $tag): ?>
                                    <a href="<?php echo esc_url(add_query_arg('lesson_tag', $tag->slug, get_permalink(get_page_by_path('search')))); ?>" class="tag-badge">
                                        <?php echo esc_html($tag->name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="lesson-meta">
                            <?php
                            $lesson_duration = get_field('lesson_duration');
                            if ($lesson_duration):
                            ?>
                                <div class="meta-item duration">
                                    <i class="far fa-clock"></i> 学習時間: 約<?php echo $lesson_duration; ?>分
                                </div>
                            <?php endif; ?>

                            <div class="meta-item date">
                                <i class="far fa-calendar-alt"></i> 更新日: <?php echo get_the_modified_date('Y年n月j日'); ?>
                            </div>
                        </div>

                        <?php if (has_post_thumbnail()): ?>
                            <div class="lesson-featured-image">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <!-- レッスン本文 -->
                    <div class="lesson-content">
                        <?php
                        // 本文の表示
                        the_content();

                        // ページネーション（レッスンが複数ページに分かれている場合）
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . __('ページ:', 'pengin-ai'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <!-- サイドバー -->
                <div class="lesson-sidebar">
                    <?php if ($parent_course): ?>
                        <!-- コース情報 -->
                        <div class="widget course-info">
                            <h3 class="widget-title">コース情報</h3>
                            <div class="course-info-box">
                                <h4 class="course-title">
                                    <a href="<?php echo esc_url(get_permalink($parent_course->ID)); ?>">
                                        <?php echo esc_html($parent_course->post_title); ?>
                                    </a>
                                </h4>
                                <div class="course-excerpt">
                                    <?php echo wp_trim_words(get_the_excerpt($parent_course->ID), 20, '...'); ?>
                                </div>
                                <a href="<?php echo esc_url(get_permalink($parent_course->ID)); ?>" class="btn btn-primary btn-block">
                                    コースに戻る
                                </a>
                            </div>
                        </div>

                        <!-- 同じコースのレッスン一覧 -->
                        <div class="widget course-lessons">
                            <h3 class="widget-title">このコースのレッスン</h3>
                            <?php
                            // 同じコース内のレッスンを取得
                            $args = array(
                                'post_type' => 'lesson',
                                'posts_per_page' => -1,
                                'meta_key' => 'lesson_order',
                                'orderby' => 'meta_value_num',
                                'order' => 'ASC',
                                'meta_query' => array(
                                    array(
                                        'key' => 'parent_course',
                                        'value' => $parent_course_id,
                                        'compare' => '='
                                    )
                                )
                            );
                            $lessons_query = new WP_Query($args);

                            if ($lessons_query->have_posts()) :
                                echo '<ul class="lessons-nav">';
                                $current_lesson_id = get_the_ID();
                                $found_current = false;
                                $prev_lesson = null;
                                $next_lesson = null;

                                // 前後のレッスンを特定
                                while ($lessons_query->have_posts()) : $lessons_query->the_post();
                                    if ($found_current && !$next_lesson) {
                                        $next_lesson = array(
                                            'id' => get_the_ID(),
                                            'title' => get_the_title(),
                                            'permalink' => get_permalink()
                                        );
                                    }

                                    if (get_the_ID() == $current_lesson_id) {
                                        $found_current = true;
                                    } else if (!$found_current) {
                                        $prev_lesson = array(
                                            'id' => get_the_ID(),
                                            'title' => get_the_title(),
                                            'permalink' => get_permalink()
                                        );
                                    }
                                endwhile;

                                // ナビゲーションを表示
                                wp_reset_postdata();
                                $lessons_query->rewind_posts();

                                while ($lessons_query->have_posts()) : $lessons_query->the_post();
                                    $is_current = get_the_ID() == $current_lesson_id;
                                    echo '<li class="' . ($is_current ? 'current' : '') . '">';
                                    echo '<a href="' . get_permalink() . '">';
                                    echo get_the_title();
                                    echo '</a>';
                                    echo '</li>';
                                endwhile;
                                echo '</ul>';

                                // 前後のレッスンへのナビゲーション
                                if ($prev_lesson || $next_lesson): ?>
                                    <div class="prev-next-navigation">
                                        <?php if ($prev_lesson): ?>
                                            <a href="<?php echo esc_url($prev_lesson['permalink']); ?>" class="prev-lesson">
                                                <i class="fas fa-arrow-left"></i> 前のレッスン
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($next_lesson): ?>
                                            <a href="<?php echo esc_url($next_lesson['permalink']); ?>" class="next-lesson">
                                                次のレッスン <i class="fas fa-arrow-right"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif;

                                wp_reset_postdata();
                            else:
                                echo '<p>このコースにはレッスンがありません。</p>';
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>

                    <!-- 関連レッスン -->
                    <div class="widget related-lessons">
                        <h3 class="widget-title">関連レッスン</h3>
                        <?php
                        // タグが設定されていれば、それを基に関連レッスンを表示
                        if (!empty($tags) && !is_wp_error($tags)) {
                            $tag_ids = wp_list_pluck($tags, 'term_id');

                            $related_args = array(
                                'post_type' => 'lesson',
                                'posts_per_page' => 3,
                                'post__not_in' => array(get_the_ID()),
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'lesson_tag',
                                        'field' => 'term_id',
                                        'terms' => $tag_ids,
                                    ),
                                ),
                            );

                            $related_query = new WP_Query($related_args);

                            if ($related_query->have_posts()) :
                                while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                    <div class="related-lesson-item">
                                        <a href="<?php the_permalink(); ?>" class="related-lesson-link">
                                            <?php if (has_post_thumbnail()): ?>
                                                <div class="related-lesson-thumb">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="related-lesson-info">
                                                <h4 class="related-lesson-title"><?php the_title(); ?></h4>
                                                <span class="related-lesson-date"><?php echo get_the_date('Y.m.d'); ?></span>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<p>関連レッスンはありません。</p>';
                            endif;
                        } else {
                            // タグがなければ、最新のレッスンを表示
                            $latest_args = array(
                                'post_type' => 'lesson',
                                'posts_per_page' => 3,
                                'post__not_in' => array(get_the_ID()),
                            );

                            $latest_query = new WP_Query($latest_args);

                            if ($latest_query->have_posts()) :
                                while ($latest_query->have_posts()) : $latest_query->the_post(); ?>
                                    <div class="related-lesson-item">
                                        <a href="<?php the_permalink(); ?>" class="related-lesson-link">
                                            <?php if (has_post_thumbnail()): ?>
                                                <div class="related-lesson-thumb">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="related-lesson-info">
                                                <h4 class="related-lesson-title"><?php the_title(); ?></h4>
                                                <span class="related-lesson-date"><?php echo get_the_date('Y.m.d'); ?></span>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<p>関連レッスンはありません。</p>';
                            endif;
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>
