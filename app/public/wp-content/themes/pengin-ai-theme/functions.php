<?php
// 子テーマ用functions.php

// スタイルシートとスクリプトの読み込み
function pengin_ai_enqueue_styles() {
    // Reset CSS (最初に読み込む)
    wp_enqueue_style('reset-style', get_stylesheet_directory_uri() . '/assets/css/reset.css');
  // 親テーマのスタイルシート
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

    // Google Fonts - Noto Sans JP
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;500;700&display=swap');

    // Bootstrap (必要に応じて)
    wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css');

        // カスタムCSS
        wp_enqueue_style('pengin-ai-custom', get_stylesheet_directory_uri() . '/assets/css/custom.css', array('parent-style'), '1.0');


    // カスタムJavaScript
    wp_enqueue_script('pengin-ai-custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js', array('jquery'), '1.0', true);

    // Bootstrap JS (必要に応じて)
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'pengin_ai_enqueue_styles');

// テーマサポート
function pengin_ai_theme_setup() {
    // アイキャッチ画像サポート
    add_theme_support('post-thumbnails');

    // タイトルタグサポート
    add_theme_support('title-tag');

    // HTML5サポート
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // メニュー登録
    register_nav_menus(array(
        'header-menu' => 'ヘッダーメニュー',
        'footer-menu' => 'フッターメニュー',
    ));
}
add_action('after_setup_theme', 'pengin_ai_theme_setup');


// コース用カスタム投稿タイプの登録
function create_course_post_type() {
  register_post_type('course',
      array(
          'labels' => array(
              'name' => __('コース'),
              'singular_name' => __('コース')
          ),
          'public' => true,
          'has_archive' => true,
          'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
          'menu_icon' => 'dashicons-welcome-learn-more',
          'rewrite' => array('slug' => 'courses')
      )
  );
}
add_action('init', 'create_course_post_type');

// コースカテゴリー用タクソノミーの登録
function create_course_taxonomy() {
  register_taxonomy(
      'course_category',
      'course',
      array(
          'label' => __('コースカテゴリー'),
          'hierarchical' => true,
          'rewrite' => array('slug' => 'course-category')
      )
  );
}
add_action('init', 'create_course_taxonomy');

// レッスン用カスタム投稿タイプ（新規追加）
function create_lesson_post_type() {
  register_post_type('lesson',
      array(
          'labels' => array(
              'name' => __('レッスン'),
              'singular_name' => __('レッスン')
          ),
          'public' => true,
          'has_archive' => true,
          'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
          'menu_icon' => 'dashicons-book',
          'rewrite' => array('slug' => 'lessons')
      )
  );
}
add_action('init', 'create_lesson_post_type');

// カスタム投稿タイプ「コンテンツ」の登録
function create_content_post_type() {
  register_post_type('content',
      array(
          'labels' => array(
              'name' => __('コンテンツ'),
              'singular_name' => __('コンテンツ'),
              'add_new' => __('新規追加'),
              'add_new_item' => __('新規コンテンツを追加'),
              'edit_item' => __('コンテンツを編集'),
              'new_item' => __('新規コンテンツ'),
              'view_item' => __('コンテンツを表示'),
              'search_items' => __('コンテンツを検索'),
              'not_found' => __('コンテンツが見つかりません'),
              'not_found_in_trash' => __('ゴミ箱にコンテンツはありません'),
          ),
          'public' => true,
          'has_archive' => false,
          'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
          'menu_icon' => 'dashicons-media-text',
          'rewrite' => array('slug' => 'content'),
          'show_in_rest' => true, // Gutenbergエディタ対応
      )
  );
}
add_action('init', 'create_content_post_type');

// コンテンツ用のタクソノミー設定
function pengin_ai_register_content_taxonomies() {
  // 職種タクソノミー
  $profession_labels = array(
      'name'              => '職種',
      'singular_name'     => '職種',
      'search_items'      => '職種を検索',
      'all_items'         => 'すべての職種',
      'parent_item'       => '親職種',
      'parent_item_colon' => '親職種:',
      'edit_item'         => '職種を編集',
      'update_item'       => '職種を更新',
      'add_new_item'      => '新しい職種を追加',
      'new_item_name'     => '新しい職種名',
      'menu_name'         => '職種',
  );

  register_taxonomy('profession', array('content'), array(
      'hierarchical'      => true,
      'labels'            => $profession_labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array('slug' => 'profession'),
      'show_in_rest'      => true,
  ));

  // タグタクソノミー
  $content_tag_labels = array(
      'name'              => 'コンテンツタグ',
      'singular_name'     => 'コンテンツタグ',
      'search_items'      => 'タグを検索',
      'all_items'         => 'すべてのタグ',
      'parent_item'       => null,
      'parent_item_colon' => null,
      'edit_item'         => 'タグを編集',
      'update_item'       => 'タグを更新',
      'add_new_item'      => '新しいタグを追加',
      'new_item_name'     => '新しいタグ名',
      'menu_name'         => 'タグ',
  );

  register_taxonomy('content_tag', array('content'), array(
      'hierarchical'      => false,
      'labels'            => $content_tag_labels,
      'show_ui'           => true,
      'show_admin_column' => true,
      'query_var'         => true,
      'rewrite'           => array('slug' => 'content-tag'),
      'show_in_rest'      => true,
  ));
}
add_action('init', 'pengin_ai_register_content_taxonomies');

// Font Awesome の読み込み
function pengin_ai_enqueue_font_awesome() {
  wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'pengin_ai_enqueue_font_awesome');


// レッスンにタクソノミーを追加
function pengin_ai_modify_lesson_taxonomies() {
  // 既存のコンテンツ用タクソノミーがあれば、レッスンにも適用
  if (taxonomy_exists('profession')) {
      register_taxonomy_for_object_type('profession', 'lesson');
  } else {
      // 職種タクソノミー
      $profession_labels = array(
          'name'              => '職種',
          'singular_name'     => '職種',
          'search_items'      => '職種を検索',
          'all_items'         => 'すべての職種',
          'parent_item'       => '親職種',
          'parent_item_colon' => '親職種:',
          'edit_item'         => '職種を編集',
          'update_item'       => '職種を更新',
          'add_new_item'      => '新しい職種を追加',
          'new_item_name'     => '新しい職種名',
          'menu_name'         => '職種',
      );

      register_taxonomy('profession', array('lesson'), array(
          'hierarchical'      => true,
          'labels'            => $profession_labels,
          'show_ui'           => true,
          'show_admin_column' => true,
          'query_var'         => true,
          'rewrite'           => array('slug' => 'profession'),
          'show_in_rest'      => true,
      ));
  }

  // コンテンツタグの処理も同様
  if (taxonomy_exists('content_tag')) {
      register_taxonomy_for_object_type('content_tag', 'lesson');
  } else {
      // タグタクソノミー
      $content_tag_labels = array(
          'name'              => 'レッスンタグ',
          'singular_name'     => 'レッスンタグ',
          'search_items'      => 'タグを検索',
          'all_items'         => 'すべてのタグ',
          'parent_item'       => null,
          'parent_item_colon' => null,
          'edit_item'         => 'タグを編集',
          'update_item'       => 'タグを更新',
          'add_new_item'      => '新しいタグを追加',
          'new_item_name'     => '新しいタグ名',
          'menu_name'         => 'タグ',
      );

      register_taxonomy('lesson_tag', array('lesson'), array(
          'hierarchical'      => false,
          'labels'            => $content_tag_labels,
          'show_ui'           => true,
          'show_admin_column' => true,
          'query_var'         => true,
          'rewrite'           => array('slug' => 'lesson-tag'),
          'show_in_rest'      => true,
      ));
  }
}
add_action('init', 'pengin_ai_modify_lesson_taxonomies');

// ACFフィールドの調整（既存のフィールドがある場合）
function pengin_ai_adjust_acf_fields() {
  if (function_exists('acf_add_local_field_group')) {
      // 既存のコンテンツ情報フィールドグループを確認・調整
      $field_groups = acf_get_field_groups();
      $found_content_group = false;

      foreach ($field_groups as $group) {
          if (strpos($group['title'], 'コンテンツ情報') !== false) {
              $found_content_group = true;
              break;
          }
      }

      // コンテンツ情報フィールドグループが見つからない場合のみ新規作成
      if (!$found_content_group) {
          // レッスン用のフィールドグループを作成（必要に応じて）
          acf_add_local_field_group(array(
              'key' => 'group_lesson_info',
              'title' => 'レッスン情報',
              'fields' => array(
                  array(
                      'key' => 'field_parent_course',
                      'label' => '親コース',
                      'name' => 'parent_course',
                      'type' => 'post_object',
                      'instructions' => 'このレッスンが属するコースを選択してください',
                      'required' => 1,
                      'post_type' => array('course'),
                      'return_format' => 'id',
                  ),
                  array(
                      'key' => 'field_lesson_order',
                      'label' => 'レッスン順序',
                      'name' => 'lesson_order',
                      'type' => 'number',
                      'instructions' => 'レッスンの表示順序（小さい数字が先に表示されます）',
                      'default_value' => 0,
                      'min' => 0,
                      'max' => 999,
                  ),
                  array(
                      'key' => 'field_lesson_duration',
                      'label' => '所要時間',
                      'name' => 'lesson_duration',
                      'type' => 'number',
                      'instructions' => '学習の所要時間（分）',
                      'default_value' => 0,
                      'min' => 0,
                      'max' => 999,
                  ),
              ),
              'location' => array(
                  array(
                      array(
                          'param' => 'post_type',
                          'operator' => '==',
                          'value' => 'lesson',
                      ),
                  ),
              ),
          ));
      }
  }
}
add_action('acf/init', 'pengin_ai_adjust_acf_fields');




/**
 * コンテンツからレッスンへのデータ移行
 * 注意: このコードは一度だけ実行してください。実行後はコメントアウトしてください。
 */
function migrate_content_to_lesson() {
  // 管理者のみ実行可能
  if (!current_user_can('administrator')) {
      return;
  }

  // 移行済みフラグをチェック
  $migration_completed = get_option('content_to_lesson_migration_completed');
  if ($migration_completed) {
      return;
  }

  // すべてのコンテンツを取得
  $contents = get_posts(array(
      'post_type' => 'content',
      'posts_per_page' => -1,
      'post_status' => 'any',
  ));

  $migration_count = 0;

  foreach ($contents as $content) {
      // 親レッスンIDを取得
      $parent_lesson_id = get_post_meta($content->ID, 'parent_lesson', true);

      // 新しいレッスンを作成
      $lesson_data = array(
          'post_title'    => $content->post_title,
          'post_content'  => $content->post_content,
          'post_excerpt'  => $content->post_excerpt,
          'post_status'   => $content->post_status,
          'post_author'   => $content->post_author,
          'post_type'     => 'lesson',
          'post_date'     => $content->post_date,
          'post_modified' => $content->post_modified,
      );

      $new_lesson_id = wp_insert_post($lesson_data);

      if (!is_wp_error($new_lesson_id)) {
          // アイキャッチ画像の移行
          $thumbnail_id = get_post_thumbnail_id($content->ID);
          if ($thumbnail_id) {
              set_post_thumbnail($new_lesson_id, $thumbnail_id);
          }

          // メタデータの移行
          $content_metas = get_post_meta($content->ID);
          if (!empty($content_metas)) {
              foreach ($content_metas as $meta_key => $meta_values) {
                  // 'parent_lesson' は 'parent_course' に変換
                  if ($meta_key === 'parent_lesson' && !empty($meta_values)) {
                      update_post_meta($new_lesson_id, 'parent_course', $meta_values[0]);
                  }
                  // その他のメタデータは直接コピー
                  else if ($meta_key !== '_thumbnail_id') {
                      foreach ($meta_values as $meta_value) {
                          update_post_meta($new_lesson_id, $meta_key, maybe_unserialize($meta_value));
                      }
                  }
              }
          }

          // タクソノミーの移行
          $taxonomies = get_object_taxonomies('content');
          foreach ($taxonomies as $taxonomy) {
              $terms = wp_get_object_terms($content->ID, $taxonomy, array('fields' => 'ids'));
              if (!empty($terms) && !is_wp_error($terms)) {
                  wp_set_object_terms($new_lesson_id, $terms, $taxonomy);
              }
          }

          $migration_count++;
      }
  }

  // 移行完了フラグを設定
  update_option('content_to_lesson_migration_completed', true);

  // 管理画面に通知
  add_action('admin_notices', function() use ($migration_count) {
      echo '<div class="notice notice-success is-dismissible"><p>';
      echo sprintf('%d件のコンテンツがレッスンに移行されました。', $migration_count);
      echo '</p></div>';
  });
}

// 管理画面が読み込まれたときに実行
// add_action('admin_init', 'migrate_content_to_lesson');
// 注意: このコードは一度だけ実行してください。実行後はコメントアウトしてください。


// コンテンツカスタム投稿タイプを無効化（既存データを移行した後に実行する）
function pengin_ai_disable_content_post_type() {
  unregister_post_type('content');
}
add_action('init', 'pengin_ai_disable_content_post_type', 20);

