<?php
// Settings page for the plugin.
// -------------------------------------------------

// Create the page.
function chatgpt_rewriter_settings_page() {
  add_options_page(
    'ChatGPT Rewriter Settings',
    'ChatGPT Rewriter',
    'manage_options',
    'chatgpt-rewriter',
    'chatgpt_rewriter_settings_page_html'
  );
}

// Register plugin settings.
function chatgpt_rewriter_register_settings() {
  register_setting('chatgpt-rewriter', 'chatgpt_rewriter_enabled');
  register_setting('chatgpt-rewriter', 'chatgpt_rewriter_api_key');
  register_setting('chatgpt-rewriter', 'chatgpt_rewriter_max_tokens');
  register_setting('chatgpt-rewriter', 'chatgpt_rewriter_temperature');
  register_setting('chatgpt-rewriter', 'chatgpt_rewriter_model');
  register_setting('chatgpt-rewriter', 'chatgpt_rewriter_prompt_template');
}

// Render the settings page HTML.
function chatgpt_rewriter_settings_page_html() {
  if (!current_user_can('manage_options')) return;

  ?>
  <div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form action="options.php" method="post">
      <?php
      settings_fields('chatgpt-rewriter');
      do_settings_sections('chatgpt-rewriter');
      submit_button('Save Settings');
      ?>
      <table class="form-table">
        <tr valign="top">
          <th scope="row">Enable Rewriting</th>
          <td>
            <input type="checkbox" name="chatgpt_rewrite_enabled" value="1" <?php checked(1, get_option('chatgpt_rewriter_enabled', 1)); ?>>
            <p class="description">Enable or disable the ChatGPT content rewriting functionality. (default: enabled)</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">OpenAI API Key</th>
          <td>
            <input type="text" name="chatgpt_rewriter_api_key" value="<?php echo esc_attr(get_option('chatgpt_rewriter_api_key')); ?>" size="50" />
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">ChatGPT Model</th>
          <td>
            <select name="chatgpt_rewriter_model">
              <optgroup>
                <option value="gpt-4" <?php selected(get_option('chatgpt_rewriter_model', 'gpt-4'), 'gpt-4'); ?>>gpt-4</option>
                <option value="gpt-3.5-turbo" <?php selected(get_option('chatgpt_rewriter_model'), 'gpt-3.5-turbo'); ?>>gpt-3.5-turbo</option>
              </optgroup>
              <optgroup label="_________">
                <option value="gpt-4-0314" <?php selected(get_option('chatgpt_rewriter_model'), 'gpt-4-0314'); ?>>gpt-4-0314</option>
                <option value="gpt-4-32k" <?php selected(get_option('chatgpt_rewriter_model'), 'gpt-4-32k'); ?>>gpt-4-32k</option>
                <option value="gpt-4-32k-0314" <?php selected(get_option('chatgpt_rewriter_model'), 'gpt-4-32k-0314'); ?>>gpt-4-32k-0314</option>
                <option value="gpt-3.5-turbo-0301" <?php selected(get_option('chatgpt_rewriter_model'), 'gpt-3.5-turbo-0301'); ?>>gpt-3.5-turbo-0301</option>
              </optgroup>
            </select>
            <p class="description">Select the <a href="https://platform.openai.com/docs/models/overview" target="_blank">ChatGPT model</a> to use for rewriting. (default: gpt-4)</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Max Tokens</th>
          <td>
            <input type="number" name="chatgpt_rewriter_max_tokens" value="<?php echo esc_attr(get_option('chatgpt_rewriter_max_tokens', 2048)); ?>" min="1" max="4096" />
            <p class="description">The maximum number of tokens (words and characters) in the AI-generated content. Range: 1-4096 (default: 2048).</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Temperature</th>
          <td>
            <input type="text" name="chatgpt_rewriter_temperature" value="<?php echo esc_attr(get_option('chatgpt_rewriter_temperature', 0.7)); ?>" />
            <p class="description">Adjust creativity vs. consistency. Higher values (e.g., 1.0) increase creativity, while lower values (e.g., 0.1) make the output more focused and deterministic (default: 0.7).</p>
          </td>
        </tr>
        <tr valign="top">
          <th scope="row">Prompt Template</th>
          <td>
            <textarea name="chatgpt_rewriter_prompt_template" rows="4" cols="50"><?php echo esc_textarea(get_option('chatgpt_rewriter_prompt_template', 'Rewrite the following text: {{post_content}}')); ?></textarea>
            <p class="description">Customize the prompt sent to ChatGPT. Use <code>{{post_content}}</code> as a placeholder for the post content. (default: "Rewrite the following text: {{post_content}}")</p>
          </td>
        </tr>
      </table>
      <?php submit_button(); ?>
    </form>
  </div>
  <?php
}

// Add a 'Settings' link in the plugin activation page
function chatgpt_rewriter_plugin_action_links($links) {
  $settings_link = '<a href="' . esc_url(admin_url('options-general.php?page=chatgpt-rewriter')) . '">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}


// Register settings hooks.
add_action('admin_menu', 'chatgpt_rewriter_settings_page');
add_action('admin_init', 'chatgpt_rewriter_register_settings');
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'chatgpt_rewriter_plugin_action_links');

?>