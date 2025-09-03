<?php

/**
 * INCLUDE: Video Element
 * 
 * @param array $data  - object of video element - required fields
 *      - 'title' (string) = title attribute to add to video
 *      - 'video_type' (string) = online || file
 *      - 'online_video' (html/iframe) = iframe embed HTML of online video (ACF oEmebed field)
 *      - 'video_file' (array) = video file details
 *          - 'url' (string/url) = URL path of video file
 *      - 'video_settings' (array) = HTML video settings. If string element below is present = enable feature
 *          - 'controls' (string)
 *          - 'autoplay' (string)
 *          - 'loop' (string)
 *          - 'muted' (string)
 * 
 * @example $data = [
 *      'title' => 'Title of my video',
 *      'video_type' => 'file',
 *      'online_video' => null,
 *      'video_file' => [
 *          'url' => 'path/to/video/file.mp4',
 *      ],
 *      'video_settings' => [
 *          'controls',
 *          'autoplay',
 *          'loop',
 *          'muted',
 *      ]
 * ];
 * echo bansheeStarter_video($data);
 */

if (!function_exists('bansheeStarter_video')){
    function bansheeStarter_video($data)
    {
        if (isset($data['video_element']) && $data['video_element']) { // the name of the field group that is inside of item_content group
            $data = $data['video_element'];
        }

        $videoHTML = '';

        // Default classes to empty string
        if (!isset($data['classes'])) {
            $data['classes'] = '';
        }

        // Build HTML based on settings
        if (isset($data['video_type']) && (isset($data['video_file']) || isset($data['online_video']))) :
            $videoHTML .= '<div class="video__wrapper">';

            /******************************
                        VIDEO FILE / MP4
             ******************************/
            if ($data['video_type'] == 'file') :

                $videoSettings = '';

                if (isset($data['video_settings'])) {
                    if (in_array('controls', $data['video_settings']) || (isset($data['video_settings']['conrols']) && $data['video_settings']['conrols'] == 1)) {
                        $videoSettings .= 'controls ';
                    } else {
                        $videoSettings .= 'plays-inline playsinline webkit-playsinline ';
                    }
                    if (in_array('autoplay', $data['video_settings']) || (isset($data['video_settings']['autoplay']) && $data['video_settings']['autoplay'] == 1)) {
                        $videoSettings .= 'autoplay="autoplay" ';
                    }
                    if (in_array('loop', $data['video_settings']) || (isset($data['video_settings']['loop']) && $data['video_settings']['loop'] == 1)) {
                        $videoSettings .= 'loop="loop" ';
                    }
                    if (in_array('muted', $data['video_settings']) || (isset($data['video_settings']['muted']) && $data['video_settings']['muted'] == 1)) {
                        $videoSettings .= 'muted="muted" ';
                    }
                } else {
                    $videoSettings .= 'controls';
                }

                $videoHTML .= '<video src="' . $data['video_file']['url'] . '" ' . $videoSettings . '></video>';


                /******************************
                        ONLINE EMBED 
                 ******************************/
            else :

                /*** YOUTUBE ***/
                if (strpos($data['online_video'], 'youtube') !== false) {
                    $youtube_iframe = $data['online_video']; // oEmbed field
                    // Use preg_match to find iframe src.
                    preg_match('/src="(.+?)"/', $youtube_iframe, $yt_matches);
                    $youtube_src = $yt_matches[1];
                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_src, $yt_match);
                    $yotutube_id = $yt_match[1];

                    // Add extra parameters to src and replcae HTML.
                    $videoSettings = array(
                        // Global Settings
                        'hd'            => 1,
                        'enablejsapi'   => 1,
                        // Defaults
                        'controls'      => 0,
                        'autoplay'      => 0,
                        'loop'          => 0,
                        'mute'          => 0,
                    );
                    // If custom settings are defined
                    if (isset($data['video_settings'])) {
                        if (in_array('controls', $data['video_settings']) || (isset($data['video_settings']['controls']) && $data['video_settings']['controls'] == 1)) {
                            $videoSettings['controls'] = 1;
                        }
                        if (in_array('autoplay', $data['video_settings']) || (isset($data['video_settings']['autoplay']) && $data['video_settings']['autoplay'] == 1)) {
                            $videoSettings['autoplay'] = 1;
                        }
                        if (in_array('loop', $data['video_settings']) || (isset($data['video_settings']['loop']) && $data['video_settings']['loop'] == 1)) {
                            $videoSettings['loop'] = 1;
                            $videoSettings['playlist'] = $yotutube_id;
                        }
                        if (in_array('muted', $data['video_settings']) || (isset($data['video_settings']['muted']) && $data['video_settings']['muted'] == 1)) {
                            $videoSettings['mute'] = 1;
                        }
                    }
                    // No custom settings
                    else {
                        $videoSettings['controls'] = 1;
                    }

                    // Add custom video settings to iframe embed and rebuild html
                    $youtube_newSrc = add_query_arg($videoSettings, $youtube_src);
                    $youtube_iframe = str_replace($youtube_src, $youtube_newSrc, $youtube_iframe);

                    // Add extra attributes to iframe HTML.
                    $attributes = '';
                    if (isset($data['title'])) {
                        $attributes = 'title="' . $data['title'] . '"';
                    }
                    $youtube_iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $youtube_iframe);

                    // Return iframe HTML
                    $videoHTML .= $youtube_iframe;
                }


                /*** VIMEO ***/
                elseif (strpos($data['online_video'], 'vimeo') !== false) {
                    // $videoHTML .= $data['online_video'];

                    $vimeo_iframe = $data['online_video']; // oEmbed field
                    // Use preg_match to find iframe src.
                    preg_match('/src="(.+?)"/', $vimeo_iframe, $v_matches);
                    $vimeo_src = $v_matches[1];

                    // Add extra parameters to src and replcae HTML.
                    $videoSettings = array(
                        // Global Settings
                        'hd'            => 1,
                        'enablejsapi'   => 1,
                        // Defaults
                        'controls'      => 0,
                        'autoplay'      => 0,
                        'loop'          => 0,
                        'mute'          => 0,
                    );
                    // If custom settings are defined
                    if (isset($data['video_settings'])) {
                        if (in_array('controls', $data['video_settings']) || (isset($data['video_settings']['controls']) && $data['video_settings']['controls'] == 1)) {
                            $videoSettings['controls'] = 1;
                        }
                        if (in_array('autoplay', $data['video_settings']) || (isset($data['video_settings']['autoplay']) && $data['video_settings']['autoplay'] == 1)) {
                            $videoSettings['autoplay'] = 1;
                        }
                        if (in_array('loop', $data['video_settings']) || (isset($data['video_settings']['loop']) && $data['video_settings']['loop'] == 1)) {
                            $videoSettings['loop'] = 1;
                            // $videoSettings['playlist'] = $vimeo_id;
                        }
                        if (in_array('muted', $data['video_settings']) || (isset($data['video_settings']['muted']) && $data['video_settings']['muted'] == 1)) {
                            $videoSettings['mute'] = 1;
                        }
                    }
                    // No custom settings
                    else {
                        $videoSettings['controls'] = 1;
                    }

                    // Add custom video settings to iframe embed and rebuild html
                    $vimeo_newSrc = add_query_arg($videoSettings, $vimeo_src);
                    $vimeo_iframe = str_replace($vimeo_src, $vimeo_newSrc, $vimeo_iframe);

                    // Add extra attributes to iframe HTML.
                    $attributes = '';
                    if (isset($data['title'])) {
                        $attributes = 'title="' . $data['title'] . '"';
                    }
                    $vimeo_iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $vimeo_iframe);

                    // Return iframe HTML
                    $videoHTML .= $vimeo_iframe;
                }

                /*** OTHER ***/
                else {
                    $videoHTML .= $data['online_video'];
                }
            endif;

            $videoHTML .= '</div>';
        endif;

        return $videoHTML;
    }
}
