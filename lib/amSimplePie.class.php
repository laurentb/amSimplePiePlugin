<?php

/*
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
require_once(dirname(__FILE__).'/vendor/simplepie/simplepie.inc');

/**
 * The purpose of this plugin is to facilitate the use of SimplePie
 * within a Symfony project:
 *  - it allows you to autoload the SimplePie class
 *  - it uses the Symfony cache directory
 *
 * @package    amSimplePiePlugin
 * @subpackage lib
 * @author     Laurent Bachelier <laurent@bachelier.name>
 * @link       http://www.symfony-project.org/plugins/amSimplePiePlugin
 */
class amSimplePie
{
  /**
   * SimplePie object
   */
  private $sp;

  /**
   * Inits an amSimplePie object, wrapping a SimplePie object
   * It works the same as the SimplePie constructor,
   * except that there is a $no_cache parameter instead of $cache_location.
   * @param string $feed_url Same as the $feed_url parameter of SimplePie()
   * @param integer $cache_duration Same as the $cache_duration parameter of SimplePie()
   * @param boolean $no_cache Don't do anything with the cache
   * @see SimplePie
   */
  function __construct($feed_url = null, $cache_duration = null, $no_cache = false)
  {
    // Set and create (if needed) the SimplePie cache directory
    if ($no_cache == false)
    {
      // Symfony 1.0+ use sf_cache_dir
      $sf_cache_dir = sfConfig::get('sf_root_cache_dir', sfConfig::get('sf_cache_dir'));
      $cache_location = $sf_cache_dir.DIRECTORY_SEPARATOR.'SimplePie';

      /* Check the sfContext::getInstance availability.
       * Necessary because, when running `php symfony test:unit`,
       * there is no available instance and an exception is thrown.
       */
      if (sfContext::hasInstance())
      {
        $log = sfConfig::get('sf_logging_enabled') ? sfContext::getInstance()->getLogger() : false;
      }

      // Thanks to sfSmartyViewPlugin - create cache directory if needed:
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
