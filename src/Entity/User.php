<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements AdvancedUserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $email;
    
    /**
     * @var array
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    private $roles;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $options = [];
        
        $this->password = password_hash($password, PASSWORD_BCRYPT, $options);

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    
    public function getRoles(): ?array
    {
        $roles = $this->roles;
        //for new User
        if (!is_array($roles)) {
            $roles = [];
        }
        //user without roles get ROLE_USER
        if (in_array('ROLE_USER', $roles) === false) {
            $roles[] = 'ROLE_USER';
        }
        return $roles;
    }
   
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getSalt() {

    }

    public function eraseCredentials() {

    }
    
    public function setActive($active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return $this->active;
    }

    // serialize and unserialize must be updated - see below
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
            $this->active,
        ));
    }
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->roles,
            $this->active,
        ) = unserialize($serialized);
    }
}
