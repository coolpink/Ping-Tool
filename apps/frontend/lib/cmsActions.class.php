<?php

/**
 * Cms actions file
 *
 * PHP version 5
 *
 * @category Symfony
 * @package  Coolpink
 * @author   Mikee Franklin <mikee@coolpink.net>
 * @link     https://github.com/coolpink/WrenKitchens
 */

/**
 * cmsActions deals with setting meta data into the template
 *
 * @category   Symfony
 * @package    Domain Checker
 * @subpackage Frontend
 * @author     Dave Walker <dave.walker@coolpink.net>
 * @license    http://en.wikipedia.org/wiki/Software_copyright Copyright Coolpink
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 * @link       https://github.com/coolpink/WrenKitchens
 */
class cmsActions extends sfActions
{
      public function prependTitle($page)
      {
        $r = $this->getResponse();
        if ($ptitle = $page->getMetaPageTitle())
        {
        	$r->setTitle($ptitle);
        }
        else
        {
          $r->setTitle($page->getMetaNavigationTitle()." | Mark Hill");
        }
        if ($description = $page->getMetaDescription())
        {
        	$r->addMeta('description', $description);
        }
      }
}

?>