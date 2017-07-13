<?php
namespace Appartoo\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Appartoo\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $listUsernames = array('Thibonobo', 'Bonobob');

    foreach ($listUsernames as $username) {
      $user = new User;
      $user->setUsername($username);
      $user->setPassword($username);
      $user->setSalt('');
      $user->setRoles(array('ROLE_USER'));
      $user->setNourriture('Omnivore');
      $user->setBirthday(new \Datetime());
      $user->setRace('Singe');
      $user->setFamille('Famille';)
      $manager->persist($user);
    }

    $manager->flush();
  }
}
