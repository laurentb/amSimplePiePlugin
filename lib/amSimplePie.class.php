<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * The purpose of this plugin is to facilitate the use of SimplePie
 * whithin a Symfony project:
 *  - it allows you to autoload the SimplePie class
 *  - it uses the Symfony cache directory
 *
 * @package    symfony
 * @subpackage amSimplePiePlugin
 * @author     Laurent Bachelier <laurentb@allomatch.com>
 * @link       http://www.allomatch.com/
 * @version    SVN: $Id: amSimplePie.class.php 3179 2008-08-29 12:21:39Z laurentb $
 */

require_once('simplepie/simplepie.inc');

class amSimplePie
{
  /**
   * SimplePie object
   */
  private $sp;

  /**
   * Inits an amSimplePie object, wrapping a SimplePie object
   * @param string $feed_url Same as the $feed_url parameter of SimplePie()
   * @param integer $cache_duration Same as the $cache_duration parameter of SimplePie()
   * @param boolean $no_cache Don't do anything with the cache
   */
  function amSimplePie($feed_url = null, $cache_duration = null, $no_cache = false)
  {
    // Set and create (if needed) the SimplePie cache directory
    if ($no_cache == false)
    {
      $cache_location = sfConfig::get('sf_root_cache_dir').DIRECTORY_SEPARATOR.'SimplePie';

      // Thanks to sfSmartyViewPlugin - create cache directory if needed:
      $log = sfConfig::get('sf_logging_enabled') ? sfContext::getInstance()->getLogger() : false;
      if (!file_exists($cache_location))
      {
        if (!mkdir($cache_location, 0777, true))
        {
          throw new sfCacheException('Unable to create cache directory "' . $cache_location . '"');
        }
        if ($log) $log->info('{amSimplePie} creating cache directory: ' . $cache_location);
      }
    }
    else
    {
      $cache_location = null;
    }

    /* Constructs the SimplePie object
     * These steps are necessary because SimplePie() reacts differently
     * if called with filled parameters (it calls init()).
     */
    if ($feed_url === null && $cache_duration === null)
    {
      $this->sp = new SimplePie();
      if ($no_cache == false)
      {
        $this->sp->set_cache_location($cache_location);
      }
    }
    else
    {
      $this->sp = new SimplePie($feed_url, $cache_location, $cache_duration);
    }
  }

  /**
   * Returns the wrapped SimplePie object
   * @return SimplePie
   */
  public function getSP()
  {

    return $this->sp;
  }
}
?>
