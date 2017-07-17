<?php
namespace Appartoo\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Appartoo\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $listUsernames = array('Thibonobo', 'Bonobob' , 'Louistiti' , 'Alain' , 'Thibault' );

    foreach ($listUsernames as $username) {
      $user = new User;
      $user->setUsername($username);
      $user->setEmail($username."@gmail.com");
      $user->setEnabled(true);
      $user->setPlainPassword($username);
      $user->setSalt('');
      $user->setRoles(array('ROLE_USER'));
      $user->setNourriture('Omnivore');
      $user->setBirthday(new \Datetime());
      $user->setRace('Race');
      $user->setFamille('Famille');
      $manager->persist($user);
    }

    $manager->flush();
  }
}
