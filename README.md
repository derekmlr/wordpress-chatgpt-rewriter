# ChatGPT Rewriter for Wordpress

Simple Wordpress plugin that rewrites posts with ChatGPT on publish. Personally created to be used in addition to other automated tools in various projects.

## Structure

- `chatgpt-rewriter.php` - Entry point for the plugin. That's it.
- `include/content-processing.php` - Rewriting functions with the ChatGPT API.
- `include/meta-box.php` - Stores the post's original content in posts_meta and displays it in a widget box in the post editor.
- `include/settings.php` - Simple settings page for things like API key, model, etc in Wordpress Admin.

## Future ideas

Not sure if this will be developed much more but some curious things I might look into:

- Allow manual rewritting with previews (?) and post content history logging.
- Draft the rewrites for approval before publishing.
  - Edit prompt and request regeneration per post.
- Integration with automation plugins.