<?php

// settings
require_once __DIR__.'/settings.php';

// vendor
include __DIR__.'/vendor/autoload.php';

// modules
require_once __DIR__.'/modules/image_scraper_v2.php';

// init DiscordPHP
$discord = new \Discord\DiscordCommandClient([
    'token' => $bot_token,
    'description' => "A customized Discord-Bot written in PHP",
    'discordOptions' => array( 'logging' => false )
]);

// source
$discord->registerCommand(
  'source',
  function ($message){
    echo "-source-" . PHP_EOL;
    return "Hey look I'm Open-Source: https://github.com/n07-5l4y3r/OverKnee-San";
  },
  [
  'description' => 'Wanna use this bot? - Get the source here! c:'
  ]
);

// ping
$discord->registerCommand(
  'ping',
  function ($message){
    echo "-ping-" . PHP_EOL;
    return 'pong!';
  },
  [
  'description' => 'test'
  ]
);

// list_boorus
$discord->registerCommand(
  'list_boorus',
  function ($message, $params){
    echo "-list_boorus-" . PHP_EOL;
    global $booru_sources;
    $str = "";
    foreach($booru_sources as $booru_name => $val)
    {
      $str .= $booru_name . "\n";
    }
    return $str;
  },
  [
    'description' => 'list possible boorus'
  ]
);

// booru
$discord->registerCommand(
  'booru',
  function ($message, $params){
    echo "-booru-" . PHP_EOL;
    return booru_random_caller($params);
  },
  [
    'description' => 'use: booru [image source names or tags]'
  ]
);

function isJson($string) {
  return is_object(json_decode($string));
}

// chuck norris joke
$discord->registerCommand(
  'chuck',
  function ($message){
    echo "-chuck-" . PHP_EOL;
    $api_ret = file_get_contents("https://api.chucknorris.io/jokes/random");
    if ( !isJson($api_ret) )
      return "api error";
    else
      return json_decode($api_ret,true)["value"];
  },
  [
  'description' => 'random Chuck-Norris-Joke'
  ]
);

// geo ip
$discord->registerCommand(
  'geoip',
  function ($message, $params){
    echo "-geoip-" . PHP_EOL;
    if (count($params))
    {
      $api_ret = file_get_contents("http://ip-api.com/json/".$params[0]);
      if ( !isJson($api_ret) )
        return "api error";
      else
      {
        $out  = "```";
        $data = json_decode($api_ret);
        foreach ($data as $key => $value)
        {
          $out .= $key . " => " . $value . "\n";
        }
        $out .= "```";
        return $out;
      }
    }
    else
    {
      return "no ip specified";
    }
  },
  [
  'description' => 'print info about ip'
  ]
);

// joke
$discord->registerCommand(
  'joke',
  function ($message){
    echo "-joke-" . PHP_EOL;
    $api_ret = file_get_contents("https://sv443.net/jokeapi/category/Programming");
    if ( !isJson($api_ret) )
      return "api error";
    else {
      $out  = "```";
      $data = json_decode($api_ret,true);
      if ($data["type"] == "twopart")
      {
        $out .= $data["setup"] . "\n";
        $out .= $data["delivery"];
      }
      else
      {
        $out .= $data["joke"];
      }
      $out .= "```";
      return $out;
    }
  },
  [
  'description' => 'a random programming joke'
  ]
);

// iss
$discord->registerCommand(
  'iss',
  function ($message){
    echo "-iss-" . PHP_EOL;
    $api_ret = file_get_contents("http://api.open-notify.org/iss-now.json");
    if ( !isJson($api_ret) )
      return "api error";
    else {
      $out  = "```";
      $data = json_decode($api_ret,true);
      $out .= "Longitude: " . $data["iss_position"]["longitude"] . "\n";
      $out .= "Latitude:  " . $data["iss_position"]["latitude"];
      $out .= "```";
      return $out;
    }
  },
  [
  'description' => 'get current position of the International Space Station'
  ]
);

// on bot ready
$discord->on('ready', function ($discord) {

    echo "Bot is ready.", PHP_EOL;

    // Update game activity feed
    /*
    $game = $discord->factory(Discord\Parts\User\Game::class, [
      'name' => 'with itself...',
    ]);
    $discord->updatePresence($game);
    */

    // Message Recieved
    /*
    $discord->on('message', function ($message) {
        echo "Recieved a message from {$message->author->username}: {$message->content}", PHP_EOL;
        echo $booru->random(true, ["kiss"]), PHP_EOL;
    });
    */

});

$discord->run();

?>
