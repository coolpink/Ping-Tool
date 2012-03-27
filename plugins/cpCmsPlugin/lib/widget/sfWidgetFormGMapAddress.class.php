<?php

/**
 * An example of how to use this is:
 *
 * $this->widgetSchema["lat_long"] = new sfWidgetFormGMapAddress();
 * $this->setValidator("lat_long", new sfValidatorGMapAddress());
 * if ($this->getObject()->getLongitude() && $this->getObject()->getLatitude())
 * {
 *   $this->widgetSchema->setDefault("lat_long", array(
 *     'address' => '',
 *     'longitude' => $this->getObject()->getLongitude(),
 *     'latitude' => $this->getObject()->getLatitude()
 *   ));
 * }
 * $this->widgetSchema["latitude"] = new hidden();
 * $this->setValidator("latitude", new sfValidatorNumber(array( 'min' => -90, 'max' => 90, 'required' => true )));
 * $this->widgetSchema["longitude"] = new hidden();
 * $this->setValidator("longitude", new sfValidatorNumber(array( 'min' => -180, 'max' => 180, 'required' => true )));
 */
class sfWidgetFormGMapAddress extends sfWidgetForm
{
  public function configure($options = array(), $attributes = array())
  {
    $this->addOption('address.options', array('style' => 'width:400px'));

    $this->setOption('default', array(
      'address' => '',
      'longitude' => '-4.2412109375',
      'latitude' => '54.64463782485651'
    ));

    $this->addOption('div.class', 'sf-gmap-widget');
    $this->addOption('map.height', '300px');
    $this->addOption('map.width', '500px');
    $this->addOption('map.style', "");
    $this->addOption('lookup.name', "Lookup");
    $this->addOption('latitude_field', "latitude");
    $this->addOption('longitude_field', "longitude");

    $this->addOption('template.html', '
      <div id="{div.id}" class="{div.class}">
        {input.search} <input type="submit" value="{input.lookup.name}"  id="{input.lookup.id}" /> <br />
        {input.longitude}
        {input.latitude}
        <div id="{map.id}" style="width:{map.width};height:{map.height};{map.style}"></div>
      </div>
    ');

     $this->addOption('template.javascript', '
      <script type="text/javascript">
        jQuery(window).bind("load", function() {
          new sfGmapWidgetWidget({
            longitude: "{input.longitude.id}",
            latitude: "{input.latitude.id}",
            address: "{input.address.id}",
            lookup: "{input.lookup.id}",
            map: "{map.id}"
          });
        })
      </script>
    ');
  }

  public function getJavascripts()
  {
    return array(
      'http://maps.google.com/maps/api/js?sensor=false',
      '/cpCmsPlugin/form/js/sf_widget_gmap_address.js'
    );
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    // define main template variables
    $template_vars = array(
      '{div.id}'             => $this->generateId($name),
      '{div.class}'          => $this->getOption('div.class'),
      '{map.id}'             => $this->generateId($name.'[map]'),
      '{map.style}'          => $this->getOption('map.style'),
      '{map.height}'         => $this->getOption('map.height'),
      '{map.width}'          => $this->getOption('map.width'),
      '{input.lookup.id}'    => $this->generateId($name.'[lookup]'),
      '{input.lookup.name}'  => $this->getOption('lookup.name'),
      '{input.address.id}'   => $this->generateId($name.'[address]'),
      '{input.latitude.id}'  => $this->generateId($name.$this->getParent()->generateName($this->getOption('latitude_field'))),
      '{input.longitude.id}' => $this->generateId($name.$this->getParent()->generateName($this->getOption('longitude_field'))),
    );

    // avoid any notice errors to invalid $value format
    $value = !is_array($value) ? array() : $value;
    $value['address']   = isset($value['address'])   ? $value['address'] : '';
    $value['longitude'] = isset($value['longitude']) ? $value['longitude'] : '';
    $value['latitude']  = isset($value['latitude'])  ? $value['latitude'] : '';

    // define the address widget
    $address = new sfWidgetFormInputText(array(), $this->getOption('address.options'));
    $template_vars['{input.search}'] = $address->render($name.'[address]', $value['address']);

    // define the longitude and latitude fields
    $hidden = new sfWidgetFormInputHidden();
    $template_vars['{input.longitude}'] = $hidden->render($this->getParent()->generateName($this->getOption('longitude_field')), $value['longitude'], array('id' => $this->generateId($name.$this->getParent()->generateName($this->getOption('longitude_field')))));
    $template_vars['{input.latitude}']  = $hidden->render($this->getParent()->generateName($this->getOption('latitude_field')), $value['latitude'], array('id' => $this->generateId($name.$this->getParent()->generateName($this->getOption('latitude_field')))));

    // merge templates and variables
    return strtr(
      $this->getOption('template.html').$this->getOption('template.javascript'),
      $template_vars
    );
  }
}