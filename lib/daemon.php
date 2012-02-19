<?php
 
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'dev', true);
sfContext::createInstance($configuration);

// Obtain lock
$fp = fopen(dirname(__FILE__) . '/daemon.lock', 'r');
if(!$fp || !flock($fp, LOCK_EX | LOCK_NB)) {
  #print "Failed to acquire lock\n";
  fputs(STDERR, "Failed to acquire lock! Daemon already running?\n");
  exit;
}

// Signal handler
function sig_handler($signal)
{
  if($signal == SIGTERM || $signal == SIGHUP || $signal == SIGINT) {
    print "\n\n------------\nSo Long, and Thanks for All the Fish!\n";
    exit();
  }
}

declare(ticks=1);
pcntl_signal(SIGTERM, "sig_handler");
pcntl_signal(SIGHUP, "sig_handler");
pcntl_signal(SIGINT, "sig_handler");

// Remove the following lines if you don't use the database layer
#$databaseManager = new sfDatabaseManager($configuration);
 
// Get configuration
$bot_lowerlimit = sfConfig::get('app_daemon_lowerlimit', 5);
$bot_upperlimit = sfConfig::get('app_daemon_upperlimit', 15);
$bot_limitdiff = $bot_upperlimit - $bot_lowerlimit;

print 'Bot lower limit: ' . $bot_lowerlimit . "\n";
print 'Bot upper limit: ' . $bot_upperlimit . "\n";

$auction_table = AuctionTable::getInstance();
$bot_table = BotTable::getInstance();

$auction = $auction_table->getActiveAuction();

// Start loop to check auctions
print "Starting daemon\n------------\n\n";
while(true) {
  if($auction == false) {
    sleep(10);
    $auction = $auction_table->getActiveAuction();
    if($auction !== false) {
      print "Auction \"".$auction."\" has been started\n------------\n";
    } else {
      continue;
    }
  }

  $auction->refresh();
  $countdown = $auction->getCurrentCountdown();
  print $countdown."... ";
  
  if(!$auction->getStartTime()) {
    print "\n------------\nAuction \"".$auction."\" has been interrupted!\n\n";
    $auction = false;
  } elseif($countdown < 0) {
    $auction->stopAuction();
    print "\n------------\nStopping Auction \"".$auction."\" - ";
    if($auction->relatedExists('LastBidder')) {
      print "The winner is " . $auction->LastBidder->Participant->getName() . " at a price of " . $auction->getCurrentPrice() . ".";
    } else {
      print "There is no winner.";
    }
    print "\n\n";
    $auction = false;
  } elseif($countdown < ($bot_upperlimit + $bot_limitdiff) && $countdown >= $bot_upperlimit) {
    // Wait random amount of time between lower and upper limit
    $rand = mt_rand(($countdown - $bot_upperlimit), ($countdown - $bot_lowerlimit));
    // Ensure that daemon doesn't wait too long, 1 second buffer
    if($countdown - $rand < 2) {
      $rand = $countdown - 2;
    }
    print "Waiting ".$rand." seconds... ";
    sleep($rand);
  } elseif($countdown < $bot_upperlimit) {
    if($auction->getBotBehaviour() != 'off') {
      $bot = $bot_table->placeAutomatedBid($auction);
      if($bot > 0) {
        print "\n" . ($bot == 1 ? 'A bot has' : $bot . ' bots have') . " bidden... ";
      }
      $bot_table->deactivateOutbidBots($auction);
    }
    usleep(500000); #5ms
  } else {
    if($auction->getBotBehaviour() != 'off') {
      $bot_table->deactivateOutbidBots($auction);
    }
    sleep(10);
  }
}

print "\n";
exit;
