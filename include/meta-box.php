<?php
// Meta Box for original content on post editor view.
// -------------------------------------------------

// Add a meta box to display the original content.
function chatgpt_rewriter_add_meta_box() {
  add_meta_box(
    'chatgpt_original_content',
    'Content before ChatGPT rewrite',
    'chatgpt_rewriter_meta_box_callback',
    'post',
    'normal',
    'high'
  );
}

// Render the original content meta box.
function chatgpt_rewriter_meta_box_callback($post) {
  // Get the original content for the current post
  $original_content = get_post_meta($post->ID, 'chatgpt_original_content', true);

  // Display the original content in a read-only textarea
  echo '<textarea readonly style="width: 100%; height: 200px;">' . esc_textarea($original_content) . '</textarea>';
}

// Register meta box hooks.
add_action('add_meta_boxes', 'chatgpt_rewriter_add_meta_box');

?>