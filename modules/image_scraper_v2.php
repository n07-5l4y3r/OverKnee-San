<?php

  $booru_sources = [
      'gelbooru' => [
        'random' => function($tags) {
          $count_func = function($tags) {
            $api_ret = getRequest([
              "protocol" => "https",
              "site" => "gelbooru.com",
              "file" => "index.php",
              "fields" => [
                "page" => "dapi",
                "s" => "post",
                "q" => "index",
                "tags" => implode("+",$tags),
                "limit" => "1",
                "pid" => "0"
              ]
            ]);

            $re = '/posts count="(\d+)"/m';
            if ( !preg_match($re, $api_ret, $matches) )
              return 0;

            return intval($matches[1]);
          };
          $count = $count_func($tags);
          if (!$count) return false;

          $i_rnd = rand(0,$count - 1);

          $i_pge = intdiv($i_rnd, 100) - 1;
          $p_pge = $i_rnd % 100 - 1;

          //gelbooru api seems to have a problem with pages > 200
          if ($i_pge > 200) $i_pge = rand(0,200);

          $api_ret = getRequest([
            "protocol" => "https",
            "site" => "gelbooru.com",
            "file" => "index.php",
            "fields" => [
              "page" => "dapi",
              "s" => "post",
              "q" => "index",
              "tags" => implode("+",$tags),
              "limit" => "100",
              "pid" => $i_pge
            ]
          ]);

          $re = '/file_url="([^"]+)"/m';
          if ( !preg_match_all($re, $api_ret, $matches) ) return false;
          if (count($matches[1]) <= $p_pge) return false;

          return "[".$i_rnd."/".$count."] " . $matches[1][$p_pge];
        }
      ],
      'safebooru' => [
        'random' => function($tags) {
          $count_func = function($tags) {
            $api_ret = getRequest([
              "protocol" => "https",
              "site" => "safebooru.org",
              "file" => "index.php",
              "fields" => [
                "page" => "dapi",
                "s" => "post",
                "q" => "index",
                "tags" => implode("+",$tags),
                "limit" => "1",
                "pid" => "0"
              ]
            ]);

            $re = '/posts count="(\d+)"/m';
            if ( !preg_match($re, $api_ret, $matches) )
              return 0;

            return intval($matches[1]);
          };
          $count = $count_func($tags);
          if (!$count) return false;

          $i_rnd = rand(0,$count - 1);

          $i_pge = intdiv($i_rnd, 100) - 1;
          $p_pge = $i_rnd % 100 - 1;

          $api_ret = getRequest([
            "protocol" => "https",
            "site" => "safebooru.org",
            "file" => "index.php",
            "fields" => [
              "page" => "dapi",
              "s" => "post",
              "q" => "index",
              "tags" => implode("+",$tags),
              "limit" => "100",
              "pid" => $i_pge
            ]
          ]);

          $re = '/file_url="([^"]+)"/m';
          if ( !preg_match_all($re, $api_ret, $matches) ) return false;
          if (count($matches[1]) <= $p_pge) return false;

          return "[".$i_rnd."/".$count."] " . $matches[1][$p_pge];
        }
      ],
      'r34' => [
        'random' => function($tags) {
          $count_func = function($tags) {
            $api_ret = getRequest([
              "protocol" => "https",
              "site" => "rule34.xxx",
              "file" => "index.php",
              "fields" => [
                "page" => "dapi",
                "s" => "post",
                "q" => "index",
                "tags" => implode("+",$tags),
                "limit" => "1",
                "pid" => "0"
              ]
            ]);

            $re = '/posts count="(\d+)"/m';
            if ( !preg_match($re, $api_ret, $matches) )
              return 0;

            return intval($matches[1]);
          };
          $count = $count_func($tags);
          if (!$count) return false;

          $i_rnd = rand(0,$count - 1);

          $i_pge = intdiv($i_rnd, 100) - 1;
          $p_pge = $i_rnd % 100 - 1;

          //r34 api seems to have a problem with pages > 2000
          if ($i_pge > 2000) $i_pge = rand(0,2000);

          $api_ret = getRequest([
            "protocol" => "https",
            "site" => "rule34.xxx",
            "file" => "index.php",
            "fields" => [
              "page" => "dapi",
              "s" => "post",
              "q" => "index",
              "tags" => implode("+",$tags),
              "limit" => "100",
              "pid" => $i_pge
            ]
          ]);

          $re = '/file_url="([^"]+)"/m';
          if ( !preg_match_all($re, $api_ret, $matches) ) return false;
          if (count($matches[1]) <= $p_pge) return false;

          return "[".$i_rnd."/".$count."] " . $matches[1][$p_pge];
        }
      ],
      'e621' => [
        'random' => function($tags) {
          $count_func = function($tags) {
            $api_ret = getRequest([
              "protocol" => "https",
              "site" => "e621.net",
              "file" => "post/index.xml",
              "fields" => [
                "tags" => implode("+",$tags),
                "limit" => "1",
                "page" => "0"
              ]
            ]);

            $re = '/posts count="(\d+)"/m';
            if ( !preg_match($re, $api_ret, $matches) )
              return 0;

            return intval($matches[1]);
          };
          $count = $count_func($tags);
          if (!$count) return false;

          $i_rnd = rand(0,$count - 1);

          $i_pge = intdiv($i_rnd, 100) - 1;
          $p_pge = $i_rnd % 100 - 1;

          //r34 api seems to have a problem with pages > 2000
          if ($i_pge > 750) $i_pge = rand(0,750);

          $api_ret = getRequest([
            "protocol" => "https",
            "site" => "e621.net",
            "file" => "post/index.xml",
            "fields" => [
              "tags" => implode("+",$tags),
              "limit" => "100",
              "page" => $i_pge
            ]
          ]);

          $re = '/<file_url>([^<]+)<\/file_url>/m';
          if ( !preg_match_all($re, $api_ret, $matches) ) return false;
          if (count($matches[1]) <= $p_pge) return false;

          return "[".$i_rnd."/".$count."] " . $matches[1][$p_pge];
        }
      ],
  ];

  function booru_random( $boorus , $tags )
  {
    echo "booru_random_caller([" . implode(", ",$boorus) . '], [' . implode(", ",$tags) . "])" . PHP_EOL;

    // - TODO -
    // first randomize source api 
    // only request another api if request failed

    global $booru_sources;
    $images = array();
    foreach ( $boorus as $booru )
    {
      $retval = call_user_func($booru_sources[$booru]["random"],$tags);
      if ($retval)
        $images[] = $retval;
      echo "[" . $booru . "] -> '" . $retval . "'" . PHP_EOL;
    }
    if (count($images))
    {
      return $images[rand(0,count($images) - 1)];
    } else {
      return "error";
    }
  }

  function booru_random_caller( $params )
  {
    echo "booru_random_caller(" . implode(", ",$params) . ")" . PHP_EOL;

    global $booru_sources;
    $boorus = array();
    $tags = array();

    foreach ( $params as $param )
    {
      if (array_key_exists($param,$booru_sources))
        $boorus[] = $param;
      else
        if ($param == "any")
          $boorus[] = $param;
        else
          $tags[] = $param;
    }

    $boorus = array_unique($boorus);
    $tags = array_unique($tags);

    if( !count($boorus) ) $boorus[] = "any";

    if ( in_array("any",$boorus) )
    {
      $boorus = array();
      foreach ( $booru_sources as $booru => $conf )
      {
        $boorus[] = $booru;
      }
    }

    return booru_random($boorus,$tags);
  }

  function getRequest($arr, &$url_debug=NULL)
  {
    $url = $arr["protocol"]."://".$arr["site"]."/".$arr["file"];
    $first = true;
    foreach ( $arr["fields"] as $key => $val )
    {
      if ($first)
      {
        $first = false;
        $url .= "?";
      }
      else
      {
        $url .= "&";
      }
      $url .= $key . "=" . $val;
    }

    echo "GET " . $url . PHP_EOL;
    if ($url_debug === NULL) { } else { $url_debug = $url; }

    $content = "";
    try {
      $opts = [
          "http" => [
              "method" => "GET",
              "header" => "User-Agent: OverKnee-San\r\n"
          ]
      ];
      $context = stream_context_create($opts);
      $content = file_get_contents($url,false,$context);
    } catch (Exception $e) { }

    return $content;
  }

?>
