<?php

require_once DING_VOXB_PATH . '/lib/VoxbUser.class.php';

/**
 * @file
 *
 * This class handles all the login logic to Drupal
 */
class VoxbLogin {

  /**
   * User authentification in VoxB.
   *
   * Its not an anthentication really, we just check if such user exists in
   * VoxB database and save his voxb userId to _SESSION to use it in user
   * actions rating/reviewing etc.
   *
   * @param object $account
   */
  public function login($account) {
    $obj = new VoxbUser();

    if (!isset($_SESSION['voxb']['userId']) && !isset($_SESSION['voxb']['aliasName']) && !isset($_SESSION['voxb']['profile'])) {
      if ($obj->getUserBySSN($account->name, variable_get('voxb_identity_provider', ''), variable_get('voxb_institution_name', ''))) {
        // Each user in Voxb can have several profiles but we take just the
        // first one.
        $profiles = $obj->getProfiles();
        $_SESSION['voxb']['userId'] = $profiles[0]->getUserId();
        $_SESSION['voxb']['aliasName'] = $profiles[0]->getAliasName();
        // Fetch user actions and put serialized profile object into session.
        $profiles[0]->fetchMyData();
        $_SESSION['voxb']['profile'] = serialize($profiles[0]);
        return TRUE;
      }

      return FALSE;
    }

    return TRUE;
  }

  /**
   * Create a new user.
   *
   * Use his username as user CPR and aliasName
   *   (we will give the possibility to update it later).
   * Use user email as profile link.
   *
   * @TODO: Replace profile link with a real link to users profiles in
   * ding-system.
   */
  public function create($account) {
    $user_id = $this->createUser($account->name, $account->voxb['alias_name'], $account->mail);

    if ($user_id != 0) {
      $_SESSION['voxb']['userId'] = $user_id;
      $_SESSION['voxb']['aliasName'] = $account->voxb['alias_name'];

      // Fetch user actions and put serialized profile object into session.
      $profile = new VoxbProfile();
      $profile->setUserId($user_id);
      $profile->fetchMyData();
      $_SESSION['voxb']['profile'] = serialize($profile);

      return TRUE;
    }

    return FALSE;
  }

  /**
   * Create a new user (with 1 profile).
   *
   * @param string $cpr
   * @param string $alias_name
   * @param string $profile_link
   */
  public function createUser($cpr, $alias_name, $profile_link) {

    $obj = new VoxbProfile();
    $obj->setCpr($cpr);
    $obj->setAliasName($alias_name);
    $obj->setProfileLink($profile_link);
    if ($obj->createUser(variable_get('voxb_identity_provider', ''), variable_get('voxb_institution_name', ''))) {
      // User successfully created.
      return $obj->getUserId();
    }

    return 0;
  }
}
