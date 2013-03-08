<?php
$tpl = Util::newTpl($this, 'overview');

$all_players = $players = fRecordSet::build(
    'Player'
);
$test = new Material();
fCore::expose($test->reflect());

// player stats in dashboard
$num = new fNumber($players->count());
$player_stats['tracked'] = $num->format();
$player_stats['died'] = Player::countAllDeaths()->format();
$player_stats['killed'] = Player::countAllKills()->format();

$players = $players->filter(array('getOnline=' => true));
$player_stats['online'] = $players->count();


$tpl->set('players', $player_stats);

// server stats in dashboard
$server = new ServerStatistic();

$server_stats['startup'] = $server->getStartup();
$server_stats['shutdown'] = $server->getShutdown();
$server_stats['cur_uptime'] = $server->getCurrentUptime();
$server_stats['total_uptime'] = $server->getTotalUptime();
$server_stats['total_logins'] = Player::countAllLogins()->format();
$server_stats['max_players'] = $server->getMaxPlayersOnline(true);

$tpl->set('serverstats', $server_stats);

// distance
$distance_stats['total'] = Player::getDistanceOfType('total')->format();
$distance_stats['foot'] = Player::getDistanceOfType('foot')->format();
$distance_stats['minecart'] = Player::getDistanceOfType('minecart')->format();
$distance_stats['boat'] = Player::getDistanceOfType('boat')->format();

$tpl->set('distance', $distance_stats);

// block stats
$block_stats['destroyed'] = TotalBlock::countAllOfType('destroyed')->format();
$block_stats['placed'] = TotalBlock::countAllOfType('placed')->format();
$block_stats['most_placed'] = TotalBlock::getMostOfType('placed');
$block_stats['most_destroyed'] = TotalBlock::getMostOfType('destroyed');

$tpl->set('blocks', $block_stats);

// player stats
$tpl->set('online_players', $players);

// deaths
$death_stats['total'] = 0;
$death_stats['pve'] = 0;
$death_stats['pvp'] = 0;
$death_stats['evp'] = 0;
$death_stats['deaths'] = 0;
$death_stats['dangerous_mob'] = 0;
$death_stats['top_kill'] = 0;
$death_stats['top_weapon'] = 0;
$death_stats['most_kiled_mob'] = 0;
$death_stats['most_kiled_player'] = 0;

// items
$item_stats['dropped'] = TotalItem::countAllOfType('dropped')->format();
$item_stats['picked'] = TotalItem::countAllOfType('picked_up')->format();
$item_stats['most_dropped'] = TotalItem::getMostOfType('dropped');
$item_stats['most_picked'] = TotalItem::getMostOfType('picked_up');

$tpl->set('items', $item_stats);