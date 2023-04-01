<?php
// Content processing via ChatGPT API.
// -------------------------------------------------

// Main function for intercepting and processing the post content
function chatgpt_rewriter_process_content($post_id) {
  // Check if the post is being published
  if (get_option('chatgpt_rewriter_enabled', 1) != 1 || get_post_status($post_id) != 'publish') {
    return;
  }

  // Get post content
  $post = get_post($post_id);
  $original_content = $post->post_content;

  // Save the original content as post meta
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

// Function to call the ChatGPT-4 API
function chatgpt_rewriter_call_api($content) {
  // Get the API key and other settings from the options
  $api_key = get_option('chatgpt_rewriter_api_key');
  $max_tokens = get_option('chatgpt_rewriter_max_tokens', 2048);
  $temperature = get_option('chatgpt_rewriter_temperature', 0.7);
  $model = get_option('chatgpt_rewriter_model', 'gpt-4');
  $prompt_template = get_option('chatgpt_rewriter_prompt_template', 'Rewrite the following text: {{post_content}}');

  // Check if the API key is set.
  if (!$api_key) {
    return $content;
  }

  // Replace {{post_content}} with the actual post content in the prompt template.
  $prompt = str_ireplace('{{post_content}}', $content, $prompt_template);

  // Set up API request headers and data
  $headers = array(
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
  );

  $data = array(
    'prompt' => $prompt,
    'model' => $model,
    'max_tokens' => (int)$max_tokens, // Use the stored value
    'temperature' => (float)$temperature, // Use the stored value
    'top_p' => 1,
    'frequency_penalty' => 0,
    'presence_penalty' => 0
  );

  // Initialize cURL
  $ch = curl_init('https://api.openai.com/v1/chat/completions');

  // Set cURL options
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

  // Execute the API call and get the response
  $response = curl_exec($ch);

  // Close cURL session
  curl_close($ch);

  // Decode the API response
  $response_data = json_decode($response, true);

  // Check if the API response is valid and return the rewritten content
  if (isset($response_data['choices'][0]['text'])) {
    return $response_data['choices'][0]['text'];
  }

  // If there's an error or no valid response, return the original content
  return $content;
}

// Register content processing hooks.
add_action('publish_post', 'chatgpt_rewriter_process_content', 10, 1);

?>