<?php
/**
 * @package amSimplePiePlugin
 * @subpackage lib
 * @author Laurent Bachelier <laurent@bachelier.name>
 */

/* Actually, the only dependencies are a valid sfConfig
 * 'sf_root_cache_dir' or 'sf_cache_dir' entry and lime_test.
 */
require_once dirname(__FILE__).'/../../../../test/bootstrap/unit.php';

require_once dirname(__FILE__).'/../../lib/amSimplePie.class.php';

$t = new lime_test(4, new lime_output_color());

$am_sp = new amSimplePie();
$sp = $am_sp->getSP();
$t->isa_ok($sp, 'SimplePie', 'SimplePie object');
$sp->set_feed_url('http://rss.slashdot.org/Slashdot/slashdot');
$sp->set_cache_duration(3600);
$sp->init();
$sp->handle_content_type();
$items_1 = $sp->get_items();
$t->ok(count($items_1) > 0, 'Slashdot has articles');

$am_sp = new amSimplePie('http://rss.slashdot.org/Slashdot/slashdot', 3600);
$sp = $am_sp->getSP();
$t->isa_ok($sp, 'SimplePie', 'SimplePie object');
$items_2 = $sp->get_items();
$t->ok(count($items_2) > 0, 'Slashdot has articles');

// OK, that's weak, but deep comparison crashes
$t->plan += count($items_1) + count($items_2);
foreach ($items_1 as $i => $item_1)
{
  $item_2 = $items_2[$i];
  $t->is($item_1->get_title(), $item_2->get_title(), 'The two methods return identical results');
  $t->is($item_1->get_id(), $item_2->get_id(), 'The two methods return identical results');
}
