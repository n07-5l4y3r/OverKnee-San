<?php

// settings
require_once __DIR__.'/settings.php';

// vendor
include __DIR__.'/vendor/autoload.php';

// modules
require_once __DIR__.'/modules/random_booru_scraper.php';

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
    return 'pong!';
  },
  [
  'description' => 'test'
  ]
);

// booru
$booru = $discord->registerCommand(
  'booru',
  function ($message){
    return 'use: booru <sfw/nsfw> [tag-1 tag-2 ... tag-n]';
  },
  [
    'description' => 'use: booru <sfw/nsfw> [tag-1 tag-2 ... tag-n]'
  ]
);

// booru sfw
$booru->registerSubCommand(
  'sfw',
  function ($message, $params){
    return booru_random(true, $params);
  },
  [
    'description' => "random image from https://safebooru.org"
  ]
);

// booru nsfw
$booru->registerSubCommand(
  'nsfw',
  function ($message, $params){
    return booru_random(false, $params);
  },
  [
    'description' => "random image from https://gelbooru.com"
  ]
);

function isJson($string) {
  return is_object(json_decode($string));
}

// chuck norris joke
$discord->registerCommand(
  'chuck',
  function ($message){
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
