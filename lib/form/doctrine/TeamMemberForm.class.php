<?php

/**
 * TeamMember form.
 *
 * @package    Domain Checker
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class TeamMemberForm extends BaseTeamMemberForm
{
  public function configure()
  {
    $this->setValidators(array(
      'project_team_list' => new sfValidatorDoctrineChoice(array('multiple' => true, 'model' => 'Project', 'required' => false))
    ));
  }
}
