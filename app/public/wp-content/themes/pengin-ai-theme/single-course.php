<?php
/**
 * コース詳細表示用テンプレート
 *
 * @package PENGIN_AI
 */

get_header();

// コースカテゴリー（職種）を取得
$professions = get_the_terms(get_the_ID(), 'profession');
?>

<div class="course-single-page">
    <div class="container">
        <!-- パンくずリスト -->
        <div class="breadcrumbs">
            <a href="<?php echo esc_url(home_url('/')); ?>">ホーム</a> &gt;
            <span><?php the_title(); ?></span>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- コース詳細 -->
                <article id="post-<?php the_ID(); ?>" <?php post_class('course-main'); ?>>
                    <!-- コースヘッダー -->
                    <header class="course-header">
                        <h1 class="course-title"><?php the_title(); ?></h1>

                        <div class="course-meta">
                            <?php
                            $difficulty = get_field('difficulty');
                            $duration = get_field('duration');
                            ?>
                            <?php if ($difficulty): ?>
                                <div class="meta-item difficulty">
                                    <i class="fas fa-signal"></i>
                                    <span>難易度: <?php echo esc_html($difficulty); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($duration): ?>
                                <div class="meta-item duration">
                                    <i class="far fa-clock"></i>
                                    <span>所要時間: 約<?php echo $duration; ?>時間</span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($professions) && !is_wp_error($professions)): ?>
                                <div class="meta-item profession">
                                    <i class="fas fa-user-tie"></i>
                                    <span>職種:
                                        <?php
                                        $prof_links = array();
                                        foreach ($professions as $profession) {
                                            $prof_links[] = '<a href="' . esc_url(add_query_arg('profession', $profession->slug, get_permalink(get_page_by_path('search')))) . '">' . esc_html($profession->name) . '</a>';
                                        }
                                        echo implode(', ', $prof_links);
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php if (has_post_thumbnail()): ?>
                            <div class="course-featured-image">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <!-- コース本文 -->
                    <div class="course-content">
                        <?php the_content(); ?>
                    </div>

                    <!-- レッスン一覧 -->
                    <div class="lessons-list">
                        <h2 class="section-title">コースのレッスン</h2>

                        <?php
                        // このコースに属するレッスンを取得
                        $args = array(
                            'post_type' => 'lesson',
                            'posts_per_page' => -1,
                            'meta_key' => 'lesson_order',
                            'orderby' => 'meta_value_num',
                            'order' => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => 'parent_course',
                                    'value' => get_the_ID(),
                                    'compare' => '='
                                )
                            )
                        );
                        $lessons_query = new WP_Query($args);

                        if ($lessons_query->have_posts()) :
                            $lesson_number = 1;
                            ?>
                            <div class="lessons-container">
                                <?php while ($lessons_query->have_posts()) : $lessons_query->the_post();
                                    $lesson_duration = get_field('lesson_duration') ? get_field('lesson_duration') : '0';
                                ?>
                                    <div class="lesson-item">
                                        <div class="lesson-number"><?php echo $lesson_number; ?></div>
                                        <div class="lesson-details">
                                            <h3 class="lesson-title">
                                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                            </h3>
                                            <div class="lesson-excerpt"><?php the_excerpt(); ?></div>
                                            <div class="lesson-meta">
                                                <span class="duration"><i class="far fa-clock"></i> <?php echo $lesson_duration; ?>分</span>
                                            </div>
                                        </div>
                                        <div class="lesson-action">
                                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">学習する</a>
                                        </div>
                                    </div>
                                <?php
                                    $lesson_number++;
                                    endwhile;
                                    wp_reset_postdata();
                                ?>
                            </div>
                        <?php else: ?>
                            <p class="no-lessons">このコースにはまだレッスンがありません。</p>
                        <?php endif; ?>
                    </div>
                </article>
            </div>

            <div class="col-lg-4">
                <!-- サイドバー -->
                <div class="course-sidebar">
                    <!-- コース情報 -->
                    <div class="widget course-info">
                        <h3 class="widget-title">コース情報</h3>
                        <ul class="course-info-list">
                            <?php if ($difficulty): ?>
                                <li><i class="fas fa-signal"></i> 難易度: <?php echo esc_html($difficulty); ?></li>
                            <?php endif; ?>

                            <?php if ($duration): ?>
                                <li><i class="far fa-clock"></i> 所要時間: 約<?php echo $duration; ?>時間</li>
                            <?php endif; ?>

                            <li><i class="fas fa-list"></i> レッスン数: <?php echo $lessons_query->post_count; ?></li>
                            <li><i class="fas fa-calendar-alt"></i> 更新日: <?php echo get_the_modified_date(); ?></li>
                        </ul>

                        <?php if ($lessons_query->have_posts()): ?>
                            <a href="<?php echo get_permalink($lessons_query->posts[0]->ID); ?>" class="btn btn-primary btn-block">
                                最初のレッスンを始める
                            </a>
                        <?php endif; ?>
                    </div>

                    <!-- 関連コース -->
                    <div class="widget related-courses">
                        <h3 class="widget-title">関連コース</h3>
                        <?php
                        // 職種タクソノミーに基づいて関連コースを表示
                        if (!empty($professions) && !is_wp_error($professions)) {
                            $profession_ids = wp_list_pluck($professions, 'term_id');

                            $related_args = array(
                                'post_type' => 'course',
                                'posts_per_page' => 3,
                                'post__not_in' => array(get_the_ID()),
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'profession',
                                        'field' => 'term_id',
                                        'terms' => $profession_ids,
                                    ),
                                ),
                            );

                            $related_query = new WP_Query($related_args);

                            if ($related_query->have_posts()) :
                                while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                    <div class="related-course-item">
                                        <a href="<?php the_permalink(); ?>" class="related-course-link">
                                            <?php if (has_post_thumbnail()): ?>
                                                <div class="related-course-thumb">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="related-course-info">
                                                <h4 class="related-course-title"><?php the_title(); ?></h4>
                                                <?php
                                                $course_difficulty = get_field('difficulty');
                                                if ($course_difficulty) {
                                                    echo '<span class="related-course-difficulty">' . esc_html($course_difficulty) . '</span>';
                                                }
                                                ?>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<p>関連コースはありません。</p>';
                            endif;
                        } else {
                            // 関連職種がなければ最新のコースを表示
                            $latest_args = array(
                                'post_type' => 'course',
                                'posts_per_page' => 3,
                                'post__not_in' => array(get_the_ID()),
                            );

                            $latest_query = new WP_Query($latest_args);

                            if ($latest_query->have_posts()) :
                                while ($latest_query->have_posts()) : $latest_query->the_post(); ?>
                                    <div class="related-course-item">
                                        <a href="<?php the_permalink(); ?>" class="related-course-link">
                                            <?php if (has_post_thumbnail()): ?>
                                                <div class="related-course-thumb">
                                                    <?php the_post_thumbnail('thumbnail'); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="related-course-info">
                                                <h4 class="related-course-title"><?php the_title(); ?></h4>
                                                <?php
                                                $course_difficulty = get_field('difficulty');
                                                if ($course_difficulty) {
                                                    echo '<span class="related-course-difficulty">' . esc_html($course_difficulty) . '</span>';
                                                }
                                                ?>
                                            </div>
                                        </a>
                                    </div>
                                <?php endwhile;
                                wp_reset_postdata();
                            else:
                                echo '<p>関連コースはありません。</p>';
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
