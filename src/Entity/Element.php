<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 23.04.18
 * Time: 23:18
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ElementRepository")
 * @ORM\Table(name="element")
 */
class Element
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @var Ion[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Ion",
     *      mappedBy="element",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     * @ORM\OrderBy({"number": "DESC"})
     */
    private $ions;

    public function __construct(string $title)
    {
        $this->title = $title;
        $this->createdAt = new \DateTime();
        $this->ions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getIons(): Collection
    {
        return $this->ions;
    }

    public function addIon(?Ion $ion): void
    {
        $ion->setElement($this);
        if (!$this->ions->contains($ion)) {
            $this->ions->add($ion);
        }
    }

    public function removeIon(Ion $ion): void
    {
        $ion->setElement(null);
        $this->ions->removeElement($ion);
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): void
    {
        $this->number = $number;
    }
}
