<?php
/**
 * Created by PhpStorm.
 * User: sergey
 * Date: 23.04.18
 * Time: 23:32
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ion")
 */
class Ion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var Line[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Line",
     *      mappedBy="ion",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     * @ORM\OrderBy({"number": "DESC"})
     */
    private $lines;

    /**
     * @var Element
     *
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="ions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $element;

    public function __construct($title)
    {
        $this->title = $title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): void
    {
        $this->element = $element;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function addLine(?Line $line): void
    {
        $line->setIon($this);
        if (!$this->lines->contains($line)) {
            $this->lines->add($line);
        }
    }

    public function removeIon(Line $line): void
    {
        $line->setIon(null);
        $this->lines->removeElement($line);
    }
}
