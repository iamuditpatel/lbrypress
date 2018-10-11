<?php
use League\HTMLToMarkdown\HtmlConverter;

/**
 * Parses wordpress posts to be ready for the LBRY Network
 * Uses the Html-to-Markdown package
 * https://github.com/thephpleague/html-to-markdown
 *
 * @package LBRYPress
 */

class LBRY_Network_Parser
{
    public $converter = null;

    public function __construct()
    {
        // COMBAK: Composer is not safe in a wordpress environment. May have to write our own package.
        require_once LBRY_ABSPATH . 'vendor/autoload.php';
        $this->converter = new HtmlConverter(array(
            'strip_tags' => true
        ));
    }

    public function convert_to_markdown($post_id)
    {
        $post = get_post($post_id);
        $title =  '<h1>' . $post->post_title . '</h1>';

        $featured_image = get_the_post_thumbnail($post);

        $content = $title;
        if ($featured_image) {
            $content .= $featured_image . '<br />';
        }
        $content .= apply_filters('the_content', get_post($post_id)->post_content);
        $converted = $this->converter->convert($content);

        return $converted;
    }
}
