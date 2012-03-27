<?php

/**
 * cms module helper.
 *
 * @package    awesomeadmin
 * @subpackage cms
 * @author     Your name here
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class cpCmsGeneratorHelper extends BaseCpCmsGeneratorHelper
{
    public static function cms_url_for_form(sfFormObject $form, $routePrefix, $additional = "")
    {
        sfProjectConfiguration::getActive()->loadHelpers(array("Url"));
        $format = '%s/%s';
        if ('@' == $routePrefix[0])
        {
            $format = '%s_%s';
            $routePrefix = substr($routePrefix, 1);
        }

        $uri = sprintf($format, $routePrefix, $form->getObject()->isNew() ? 'create' : 'update');

        return url_for($uri, $form->getObject()).$additional;
    }
}
