<?php

namespace App\Service\Profile;

/**
 * Class Profile.
 *  To manage the functionality related to the Profile entity.
 */
class Profiles{

  /**
   * Public function profile()
   *  To ffetch the profile data from the Profile Entity.
   *
   * @param object $profile
   *  The data of profile.
   */
  public function profile(object $profile):array
  {
    // Fetcing the data from entity.
    $profileId = $profile->getId();
    $userName = $profile->getName();
    $userSchool = $profile->getSchoolingPercent();
    $userGraduation = $profile->getGraduationPercent();
    $userResume = $profile->getResumeLink();

    return [
      'profileId' => $profileId,
      'userName' => $userName,
      'userSchool' => $userSchool,
      'userGraduation' => $userGraduation,
      'userResume' => $userResume,
    ];
  }
}
