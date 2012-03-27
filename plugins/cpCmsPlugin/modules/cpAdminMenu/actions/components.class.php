<?php

require_once dirname(__FILE__) . '/../lib/BasecpAdminMenuComponents.class.php';

/**
 *
 *
 * @package     cpAdminMenu
 * @author      Coolpink <dev@coolpink.net>
 */
class cpAdminMenuComponents extends BasecpAdminMenuComponents
{
	public function executeMenu()
	{
		$this->module = $this->context->getModuleName();
		$this->action = $this->context->getActionName();
	}
}