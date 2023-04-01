<?php
// Content processing via ChatGPT API.
// -------------------------------------------------

// Main function for intercepting and processing the post content.
function chatgpt_rewriter_process_content($post_id) {
  $plugin_enabled = get_option('chatgpt_rewriter_enabled', 1) != 1;
  if ($plugin_enabled && get_post_status($post_id) != 'publish') return;

  $post = get_post($post_id);

  // Save original content.
  $original_content = $post->post_content;
  add_post_meta($post_id, 'chatgpt_original_content', $original_content, true);

  // Call ChatGPT-4 API for content rewriting
  $rewritten_content = chatgpt_rewriter_call_api($original_content);

  // Update the post content with the rewritten version
  if ($rewritten_content) {
    wp_update_post(array(
      'ID' => $post_id,
      'post_content' => $rewritten_content
    ));
  }
}

// Function to call the ChatGPT-4 API for rewritting.
function chatgpt_rewriter_call_api($content) {
  // Plugin setting values.
  $api_key = get_option('chatgpt_rewriter_api_key');
  $max_tokens = get_option('chatgpt_rewriter_max_tokens', 2048);
  $temperature = get_option('chatgpt_rewriter_temperature', 0.7);
  $model = get_option('chatgpt_rewriter_model', 'gpt-4');
  $prompt_template = get_option('chatgpt_rewriter_prompt_template', 'Rewrite the following text: {{post_content}}');

  if (!$api_key) return $content;

  // Replace {{post_content}} with the actual post content in the prompt template from settings.
  $prompt = str_ireplace('{{post_content}}', $content, $prompt_template);

  // Set up API request headers and data.
  $headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
  );
  $data = array(
    'prompt' => $prompt,
    'model' => $model,
    'max_tokens' => (int)$max_tokens,
    'temperature' => (float)$temperature,
    'top_p' => 1,
    'frequency_penalty' => 0,
    'presence_penalty' => 0
  );

  // Do the cURL request.
  $ch = curl_init('https://api.openai.com/v1/chat/completions');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  $response = curl_exec($ch);
  curl_close($ch);

  // Check if the API response is valid and return the rewritten content.
  // Otherwise, return the original content.
  $response_data = json_decode($response, true);
  if (isset($response_data['choices'][0]['text'])) {
    return $response_data['choices'][0]['text'];
  }
  return $content;
}

// Register content processing hooks.
add_action('publish_post', 'chatgpt_rewriter_process_content', 10, 1);

?>