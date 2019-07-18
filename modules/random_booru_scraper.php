<?php

    function booru_fetch($sfw, $tags, $pid, $limit, &$url_debug = NULL)
    {
      $booru = "gelbooru.com";
      if ($sfw) $booru = "safebooru.org";

      $url = "https://".$booru."/index.php?page=dapi&s=post&q=index&tags=".implode("+",$tags)."&limit=".$limit."&pid=".$pid;
      if ($url_debug === NULL) { } else { $url_debug = $url; }

      $content = "";
      try {
        $content = file_get_contents($url);
      } catch (Exception $e) { }

      return $content;
    }

    function booru_post_count($sfw, $tags)
    {
      $content = booru_fetch($sfw, $tags, 0, 1);
      if (!$content) return 0;

      $re = '/posts count="(\d+)"/m';
      if ( !preg_match($re, $content, $matches) )
        return 0;

      return intval($matches[1]);
    }

    function booru_random_select($count)
    {
      $out = array('page_index' => 0, 'post_index' => 0);
      if ($count < 100)
      {
        $out['post_index'] = rand(0,$count-1);
        return $out;
      }
      $out['page_index'] = rand(0,intdiv($count, 100)-1);
      $out['post_index'] = rand(0,99);
      return $out;
    }

    function booru_random($sfw, $tags)
    {
      $count = booru_post_count($sfw, $tags);
      if (!$count)
        return "error - no image found for that querry";

      $retrycounter = 0;

      while ( $retrycounter < 3 )
      {
        $cnt = booru_random_select($count);

        $url_debug = "";
        $content = booru_fetch($sfw, $tags, $cnt['page_index'], 100, $url_debug);
        if (!$content)
        {
          $retrycounter += 1;
          continue;
        }

        $image_id = $cnt['page_index'];
        $image_id *= 100;
        $image_id += $cnt['post_index'];
        $image_selection_prefix = "[" . $image_id . "/" . $count . "] @ index: " . $cnt['post_index'] . " on page: " . $cnt['page_index'] . "\n";

        $re = '/file_url="([^"]+)"/m';
        if ( !preg_match_all($re, $content, $matches) )
        {
          $retrycounter += 1;
          continue;
        }

        return $image_selection_prefix . $matches[1][$cnt['post_index']];
      }

      return "error - something went terribly wrong :thinking:\n```" . $url_debug . "```";
    }

?>
