<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_USERNAME', fields: ['username'])]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, EquatableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Assert\Regex('/^[a-z0-9_-]{3,15}$/')]
    private ?string $username = null;

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

    private ?string $plainPassword = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(name: '`group`', nullable: true, onDelete: 'SET NULL')]
    private ?Group $group = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $section = null;

    /**
     * @var Collection<int, Entry>
     */
    #[ORM\OneToMany(targetEntity: Entry::class, mappedBy: 'user')]
    private Collection $entries;

    /**
     * @var Collection<int, Edition>
     */
    #[ORM\ManyToMany(targetEntity: Edition::class, mappedBy: 'coordinators')]
    private Collection $coordinatedEditions;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\ManyToMany(targetEntity: Group::class, mappedBy: 'coordinators')]
    private Collection $coordinatedGroups;

    public function __toString(): string
    {
        return sprintf('%s - %s %s', $this->username, $this->firstname, $this->lastname);
    }

    public function __construct()
    {
        $this->entries = new ArrayCollection();
        $this->coordinatedEditions = new ArrayCollection();
        $this->coordinatedGroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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
    public function getPassword(): ?string
    {
        return $this->password;
    }

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
         $this->plainPassword = null;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): static
    {
        $this->group = $group;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getSection(): ?string
    {
        return $this->section;
    }

    public function setSection(?string $section): static
    {
        $this->section = $section;

        return $this;
    }

    /**
     * @return Collection<int, Entry>
     */
    public function getEntries(): Collection
    {
        return $this->entries;
    }

    public function addEntry(Entry $entry): static
    {
        if (!$this->entries->contains($entry)) {
            $this->entries->add($entry);
            $entry->setUser($this);
        }

        return $this;
    }

    public function removeEntry(Entry $entry): static
    {
        if ($this->entries->removeElement($entry)) {
            // set the owning side to null (unless already changed)
            if ($entry->getUser() === $this) {
                $entry->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Edition>
     */
    public function getCoordinatedEditions(): Collection
    {
        return $this->coordinatedEditions;
    }

    public function addCoordinatedEdition(Edition $edition): static
    {
        if (!$this->coordinatedEditions->contains($edition)) {
            $this->coordinatedEditions->add($edition);
            $edition->addCoordinator($this);
        }

        return $this;
    }

    public function removeCoordinatedEdition(Edition $edition): static
    {
        if ($this->coordinatedEditions->removeElement($edition)) {
            $edition->removeCoordinator($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getCoordinatedGroups(): Collection
    {
        return $this->coordinatedGroups;
    }

    public function addCoordinatedGroup(Group $group): static
    {
        if (!$this->coordinatedGroups->contains($group)) {
            $this->coordinatedGroups->add($group);
            $group->addCoordinator($this);
        }

        return $this;
    }

    public function removeCoordinatedGroup(Group $group): static
    {
        if ($this->coordinatedGroups->removeElement($group)) {
            $group->removeCoordinator($this);
        }

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        $this->password = null;

        return $this;
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return $this->id === $user->getId();
    }
}
