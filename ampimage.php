<?php
namespace Grav\Plugin;

use Grav\Common\Page\Media;
use Grav\Common\Page\Medium\ImageMedium;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class AmpimagePlugin
 * @package Grav\Plugin
 */
class AmpimagePlugin extends Plugin
{
    /**
     * Register events with Grav
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPageContentProcessed' => ['onPageContentProcessed', 0]
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }
    }

    /**
     * Do some work for this event, full details of events can be found
     * on the learn site: http://learn.getgrav.org/plugins/event-hooks
     *
     * @param Event $event
     */
    public function onPageContentProcessed(Event $event)
    {

        $pluginsobject = (array) $this->config->get('plugins');



        $test = 1;

        if (!empty($pluginsobject['ampimage']['enabled'])) {
            /**
             * $var array $ampimageSettings
             */
            $ampimageSettings = $pluginsobject['ampimage'];
            $page = $event['page'];
            $buffer = $page->content();

            /**
             * @var Media $media
             */
            $media = $page->media();
            $mediaArr = $media->images();

            /* Unwrap <img> from <p> */
            $unwrap = "/<p>\s*?(<a .*<img.*<\/a>|<img.*)?\s*<\/p>/";
            $buffer = preg_replace($unwrap, "$1", $buffer);

            $images = [];
            $pattern = '/<img[^>]+>/i';
            preg_match_all($pattern, $buffer, $images);


            if (!empty($images[0])) {
                foreach ($images[0] as $image) {
                    $imgAttr = [];
                    $doc = new \DOMDocument();
                    $doc->loadHTML(mb_convert_encoding($image, 'HTML-ENTITIES', 'UTF-8'));
                    $anchors = $doc->getElementsByTagName('img');
                    /**
                     * @var \DOMElement[] $anchors
                     */
                    foreach($anchors as $node) {
                        if ($node->hasAttributes()) {
                            foreach($node->attributes as $a) {
                                $imgAttr[$a->name] = $a->value;
                            }
                        }
                    }

                    if (empty($imgAttr['src'])) {
                        continue; // no src, no image
                    }

                    /**
                     * For most layout modes amp requires width an height of image
                     */
                    $imgfilename = basename($imgAttr['src']);

                    if (array_key_exists($imgfilename, $mediaArr)) {
                        /**
                         * @var ImageMedium
                         */
                        $thisImgObj = $mediaArr[$imgfilename];
                        $thisImgArray = $thisImgObj->toArray();

                    } else {
                        // Just something to fall back on
                        $thisImgArray['width'] = 50;
                        $thisImgArray['height'] = 50;
                    }



                    // Alt text
                    $alttext = (!empty($imgAttr['alt']) ? $imgAttr['alt']:'');

                    // Class
                    $class = (!empty($imgAttr['class']) ? $imgAttr['class']:'');


                    $output = '<amp-img ';
                    $output .= 'src="'.$imgAttr['src'].'" ';
                    $output .= 'layout="'.$ampimageSettings['defaultLayout'].'" ';
                    $output .= 'alt="'.$alttext.'" ';
                    $output .= ' width="'.$thisImgArray['width'].'"' ;
                    $output .= ' height="'.$thisImgArray['height'].'"' ;

                    if (!empty($imgAttr['srcset'])) {
                        $output .= 'srcset="'.$imgAttr['srcset'].'" ';
                    }
                    if (!empty($imgAttr['sizes'])) {
                        $output .= 'sizes="'.$imgAttr['sizes'].'" ';
                    }

                    if (!empty($class) &&
                        (empty($ampimageSettings['moveClassToFigure']) || empty($ampimageSettings['addFigure']))
                        ) {
                        $output .= 'class="'.$class.'" ';
                        }

                    $output .= '></amp-img>';

                    if (!empty($ampimageSettings['addFigure'])) {
                        $figure = '<figure ';
                        if (!empty($class) && !empty($ampimageSettings['moveClassToFigure'])) {
                            $figure .= 'class="'.$class.'"';
                        }
                        $figure .= '>'.$output;
                        if (!empty($alttext)) {
                            $figure .= '<figcaption>'.$alttext.'</figcaption>';
                        }
                        $figure .= '</figure>';
                        $output = $figure;
                    }

                    // Replace image
                    $buffer = str_replace($image, $output, $buffer);


                }

                $page->setRawContent($buffer);

            }



        }



    }
}
