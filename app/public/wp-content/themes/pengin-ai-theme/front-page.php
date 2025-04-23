<?php
/**
 * フロントページテンプレート
 *
 * @package PENGIN AI Theme
 */

get_header();
?>

<!-- メインビジュアル -->
<div class="main-visual">
    <!-- 背景アニメーション要素 -->
    <div class="particles-container">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>

    <!-- キャッチフレーズ部分 -->
    <div class="catchphrase">
        <h1>AIが導く学びの革命。<br>もっと自由に、もっと賢く。</h1>
        <p>Learning Revolution</p>

        <!-- ボタン -->
        <a href="#courses" class="learn-more-btn">
            詳しく見る
            <i class="fas fa-arrow-right"></i>
        </a>
    </div>

    <!-- ペンギンキャラクター (SVG) -->
    <svg class="penguin-character" viewBox="0 0 200 250" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- ペンギンの体 -->
        <ellipse cx="100" cy="130" rx="60" ry="90" fill="#1294BE"/>
        <ellipse cx="100" cy="140" rx="45" ry="65" fill="white"/>

        <!-- 目 -->
        <ellipse cx="85" cy="85" rx="8" ry="10" fill="white"/>
        <ellipse cx="115" cy="85" rx="8" ry="10" fill="white"/>
        <ellipse cx="85" cy="85" rx="4" ry="5" fill="black"/>
        <ellipse cx="115" cy="85" rx="4" ry="5" fill="black"/>

        <!-- くちばし -->
        <path d="M100 90 L85 105 L115 105 Z" fill="#FFAA55"/>

        <!-- 足 -->
        <ellipse cx="85" cy="200" rx="15" ry="8" fill="#FFAA55"/>
        <ellipse cx="115" cy="200" rx="15" ry="8" fill="#FFAA55"/>

        <!-- アンテナ -->
        <path d="M100 50 L100 30 C100 15 120 15 120 30 L120 35" stroke="#13488D" stroke-width="3"/>
        <circle cx="120" cy="35" r="5" fill="#1294BE"/>
        <!-- 点滅LED -->
        <circle class="penguin-led" cx="120" cy="35" r="2" fill="#FFFFFF"/>
    </svg>

    <!-- デジタルアイコン -->
    <svg class="digital-icons" width="100" height="120" viewBox="0 0 100 120" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- 音符 -->
        <path d="M20 30 Q25 20 30 30 L30 60 Q20 55 10 60 L10 30 Q15 20 20 30" fill="#1294BE"/>
        <circle cx="10" cy="60" r="5" fill="#1294BE"/>
        <circle cx="30" cy="60" r="5" fill="#1294BE"/>

        <!-- メールアイコン -->
        <rect x="60" y="40" width="30" height="20" rx="2" fill="#1294BE"/>
        <path d="M60 40 L75 55 L90 40" stroke="white" stroke-width="2"/>

        <!-- ギアアイコン -->
        <circle cx="40" cy="90" r="10" fill="#13488D"/>
        <circle cx="40" cy="90" r="5" fill="white"/>
        <path d="M40 75 L40 80 M55 90 L50 90 M40 105 L40 100 M25 90 L30 90 M50 80 L47 83 M50 100 L47 97 M30 100 L33 97 M30 80 L33 83" stroke="#13488D" stroke-width="2"/>
    </svg>

    <!-- 都市シルエット -->
    <svg class="city-silhouette" viewBox="0 0 1200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,200 L0,150 L30,150 L30,130 L60,130 L60,150 L90,150 L90,120 L120,120 L120,150 L150,150 L150,100 L180,100 L180,150 L210,150 L210,130 L240,130 L240,80 L270,80 L270,150 L300,150 L300,120 L330,120 L330,150 L360,150 L360,70 L390,70 L390,150 L420,150 L420,130 L450,130 L450,90 L480,90 L480,150 L510,150 L510,110 L540,110 L540,60 L570,60 L570,110 L600,110 L600,150 L630,150 L630,120 L660,120 L660,90 L690,90 L690,50 L720,50 L720,130 L750,130 L750,150 L780,150 L780,100 L810,100 L810,150 L840,150 L840,130 L870,130 L870,100 L900,100 L900,40 L930,40 L930,100 L960,100 L960,150 L990,150 L990,110 L1020,110 L1020,150 L1050,150 L1050,80 L1080,80 L1080,150 L1110,150 L1110,130 L1140,130 L1140,100 L1170,100 L1170,150 L1200,150 L1200,200 Z" fill="#13488D"/>

        <!-- ビルの窓など -->
        <rect x="45" y="135" width="5" height="5" fill="#1294BE"/>
        <rect x="45" y="145" width="5" height="5" fill="#1294BE"/>
        <rect x="105" y="125" width="5" height="5" fill="#1294BE"/>
        <rect x="105" y="135" width="5" height="5" fill="#1294BE"/>
        <rect x="105" y="145" width="5" height="5" fill="#1294BE"/>
    </svg>

    <!-- スクロールダウンインジケーター -->
    <div class="scroll-indicator">
        <span>Scroll</span>
        <i class="fas fa-chevron-down"></i>
    </div>
</div>

<!-- コース一覧セクション -->
<section id="courses" class="courses-section">
    <div class="container">
        <h2 class="section-title">コース一覧</h2>

        <?php
        // カスタマイズオプション1: カテゴリーの表示順を変更
        $categories = get_terms(array(
            'taxonomy' => 'course_category',
            'hide_empty' => true,
            'orderby' => 'name', // または 'id', 'count' など
            'order' => 'ASC', // または 'DESC'
        ));

        // カスタマイズオプション3: 特定のカテゴリーのみ表示する場合
        // 以下の2行のコメントを解除し、実際のカテゴリーIDを指定
        // $category_ids = array(4, 5, 6); // カテゴリーIDを指定
        // $categories = array_filter($categories, function($cat) use ($category_ids) { return in_array($cat->term_id, $category_ids); });

        // カテゴリーごとにコースを表示
        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                ?>
                <div class="course-category">
                    <h3 class="category-title"><?php echo $category->name; ?></h3>

                    <div class="course-cards">
                        <div class="row">
                            <?php
                            // カスタマイズオプション2: コースの並び順を変更
                            $args = array(
                                'post_type' => 'course',
                                'posts_per_page' => 3,
                                'orderby' => 'date', // または 'title', 'menu_order' など
                                'order' => 'DESC', // または 'ASC'
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'course_category',
                                        'field' => 'term_id',
                                        'terms' => $category->term_id,
                                    ),
                                ),
                            );
                            $query = new WP_Query($args);

                            // 以下は変更なし


                            if ($query->have_posts()) {
                                while ($query->have_posts()) {
                                    $query->the_post();
                                    // 難易度と所要時間を取得
                                    $difficulty = get_field('difficulty') ? get_field('difficulty') : '初級';
                                    $duration = get_field('duration') ? get_field('duration') : '0';
                                    ?>
                                    <div class="col-md-4">
                                        <div class="course-card">
                                            <div class="card-image">
                                                <?php if (has_post_thumbnail()) { ?>
                                                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                                <?php } else { ?>
                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/default-course.jpg" alt="<?php the_title(); ?>" class="img-fluid">
                                                <?php } ?>
                                                <div class="card-image-overlay"></div>
                                            </div>
                                            <div class="card-body">
                                                <h3 class="card-title"><?php the_title(); ?></h3>
                                                <p class="card-text"><?php echo get_the_excerpt(); ?></p>
                                                <div class="course-meta">
                                                    <div class="difficulty">
                                                        <i class="fas fa-signal"></i>
                                                        <?php echo $difficulty; ?>
                                                    </div>
                                                    <div class="duration">
                                                        <i class="far fa-clock"></i>
                                                        <?php echo $duration; ?>時間
                                                    </div>
                                                </div>
                                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">コースを見る</a>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                wp_reset_postdata();
                            } else {
                                // コースがない場合のメッセージ
                                echo '<div class="col-12"><p>このカテゴリーにはまだコースがありません。</p></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            // カテゴリーがない場合のメッセージ
            echo '<p>コースカテゴリーがまだ作成されていません。</p>';
        }
        ?>
    </div>
</section>


<?php
get_footer();
