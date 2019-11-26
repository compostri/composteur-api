<?php

namespace App\Entity;

use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ApiResource(
 *     attributes={"security"="is_granted('ROLE_USER')"},
 *     normalizationContext={"groups"={"userComposter"}},
 *     denormalizationContext={"groups"={"userComposter", "userComposter:write"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserComposterRepository")
 * @ORM\Table(uniqueConstraints={@UniqueConstraint(name="user_composter_unique", columns={"user_id", "composter_id"})})
 * @ApiFilter(SearchFilter::class, properties={
 *     "composter"  : "exact",
 *     "user"       : "exact",
 * })
 */
class UserComposter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"userComposter"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userComposters", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"userComposter"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Composter", inversedBy="userComposters")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user:read", "userComposter"})
     */
    private $composter;

    /**
     * @ORM\Column(type="enumcapability", options={"default":"Opener"})
     * @Groups({"user:read", "userComposter"})
     */
    private $capability;

    /**
     * @ORM\Column(type="boolean", options={"default":true})
     * @Groups({"user:read","userComposter"})
     */
    private $notif;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     * @Groups({"user:read","userComposter"})
     */
    private $newsletter;

    /**
     * @ORM\Column(type="boolean", options={"default":false})
     * @Groups({"user:read","userComposter"})
     */
    private $composterContactReceiver;

    public function __construct()
    {
        $this->capability = "Opener";
        $this->notif = true;
        $this->newsletter = false;
        $this->composterContactReceiver = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getComposter(): ?Composter
    {
        return $this->composter;
    }

    public function setComposter(?Composter $composter): self
    {
        $this->composter = $composter;

        return $this;
    }

    public function getCapability()
    {
        return $this->capability;
    }

    public function setCapability($capability): self
    {
        $this->capability = $capability;

        return $this;
    }

    public function getNotif(): ?bool
    {
        return $this->notif;
    }

    public function setNotif(bool $notif): self
    {
        $this->notif = $notif;

        return $this;
    }

    public function getNewsletter(): ?bool
    {
        return $this->newsletter;
    }

    public function setNewsletter(bool $newsletter): self
    {
        $this->newsletter = $newsletter;

        return $this;
    }

    public function getComposterContactReceiver(): ?bool
    {
        return $this->composterContactReceiver;
    }

    public function setComposterContactReceiver(bool $composterContactReceiver): self
    {
        $this->composterContactReceiver = $composterContactReceiver;

        return $this;
    }
}
