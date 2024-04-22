<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    /**
     * @var int id.
     *  User Id
     */
    private ?int $id = null;

    #[ORM\Column(length: 180)]

    /**
     * @var string $email.
     *  User email.
     */
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]

    /**
     * @var bool $is_verified.
     *  if the user is verified or not.
     */
    private $is_verified = false;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?Profile $profile = null;

    #[ORM\Column(length: 255, nullable: true)]
    /**
     * @var string $user_type
     *  Stores the type of user.
     */
    private ?string $user_type = null;

    #[ORM\Column]

    /**
     * Public function getId()
     *  To get the user id.
     *
     * @return int $id.
     *  Return the user ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Public function getEmail()
     *  To get the user email.
     *
     * @return string $email.
     *  Return the user email.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Public function setEmail()
     *  To set the user email.
     *
     * @param string $email.
     *  The user email.
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Public funtion setPassword()
     *  To set the password.
     *
     * @param string password.
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setVerified(bool $is_verified): static
    {
        $this->is_verified = $is_verified;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): static
    {
        // set the owning side of the relation if necessary
        if ($profile->getUser() !== $this) {
            $profile->setUser($this);
        }

        $this->profile = $profile;

        return $this;
    }

    /**
     * Public function getUserType()
     *  To get the user type.
     *
     * @return string user_type
     */
    public function getUserType(): ?string
    {
        return $this->user_type;
    }

    /**
     * Public function setUserType()
     *  To set the user type.
     *
     * @param string user_type
     */
    public function setUserType(?string $user_type): static
    {
        $this->user_type = $user_type;

        return $this;
    }
}
