<?php
/**
 * @package    sfGdataPlugin
 * @subpackage widget
 * @author     Coolpink <dev@coolpink.net>
 */

class sfWidgetFormYoutube extends sfWidgetFormChoice
{
  /**
   * @var array used for tracking the status of each account to display appropriate values
   */
  protected $accountStatus = array();

  /**
   * Constructor.
   *
   * Available options:
   *
   *  account: string/array the account or array of accounts to display (required)
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/../');
    parent::configure($options, $attributes);

    $this->addRequiredOption('account');
    $this->addOption('class', 'sfWidgetFormYoutube');
    $this->setOption('choices', array());
  }

  /**
   * Builds the choices array from the account given
   *
   * @see sfWidgetFormChoice::render
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $choices = array('' => 'Select a video...');
    if (is_array($this->getOption('account')))
    {
      foreach ($this->getOption('account') as $account)
      {
        $this->accountStatus[$account] = array();
        $choices[$account] = $this->getListOfVideos($account);
      }
    }
    else
    {
      $this->accountStatus[$this->getOption('account')] = array();
      $choices[$this->getOption('account')] = $this->getListOfVideos($this->getOption('account'));
    }

    if (!in_array(true, $this->accountStatus))
    {
      $choices = array('' => 'No videos available.');
    }
    else
    {
      // Remove empty accounts
      $emptyAccounts = array_keys($this->accountStatus, false, true);
      foreach ($emptyAccounts as $emptyAccount)
      {
        unset($choices[$emptyAccount]);
      }
    }

    $this->setOption('choices', $choices);

    if ($this->getOption('multiple'))
    {
      $attributes['multiple'] = 'multiple';

      if ('[]' != substr($name, -2))
      {
        $name .= '[]';
      }
    }

    if (!$this->getOption('renderer') && !$this->getOption('renderer_class') && $this->getOption('expanded'))
    {
      unset($attributes['multiple']);
    }

    $attributes['class'] = $this->getOption('class');

    $imageTag = $value ? $this->renderTag('img', array_merge(array('src' => "http://img.youtube.com/vi/{$value}/1.jpg", 'class' => 'sfWidgetFormYoutube'), $attributes)) : '';

    return $imageTag . $this->getRenderer()->render($name, $value, $attributes, $errors);
  }


  /**
   * @param string $account the account to get
   * @return array of videos in id=>title pairs
   */
  protected function getListOfVideos($account)
  {
    $videoList = array();
    try
    {
      $yt = new Zend_Gdata_YouTube();
      $yt->setMajorProtocolVersion(2);
      $videoFeed = $yt->getUserUploads($account);

      foreach ($videoFeed as $videoEntry)
      {
        $videoList[$videoEntry->getVideoId()] = $videoEntry->getVideoTitle();
      }
      $this->accountStatus[$account] = true;

      if (empty($videoList))
      {
        $this->accountStatus[$account] = false;
      }
    }
    catch (Zend_Gdata_App_Exception $ex)
    {
      $this->accountStatus[$account] = false;
    }

    return $videoList;
  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
   public function getStylesheets()
   {
     return array('/sfGdataPlugin/css/youtube.css' => 'all');
   }

    /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
   public function getJavascripts()
   {
     return array('/sfGdataPlugin/js/youtube.js');
   }
}
