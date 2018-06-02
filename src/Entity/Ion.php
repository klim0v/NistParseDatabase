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
     * @ORM\Column(type="string", unique=true)
     */
    private $title;

    /**
     * @var Spectrum[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Spectrum",
     *      mappedBy="ion",
     *     cascade={"persist"}
     * )
     */
    private $spectra;

    /**
     * @var Element
     *
     * @ORM\ManyToOne(targetEntity="Element", inversedBy="ions", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false, onDelete="cascade")
     */
    private $element;

    public function __construct($title)
    {
        $this->title = $title;
        $this->spectra = new ArrayCollection();
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

    public function getSpectra(): Collection
    {
        return $this->spectra;
    }

    public function addSpectrum(?Spectrum $spectrum): void
    {
        $spectrum->setIon($this);
        if (!$this->spectra->contains($spectrum)) {
            $this->spectra->add($spectrum);
        }
    }

    public function removeIon(Spectrum $spectrum): void
    {
        $spectrum->setIon(null);
        $this->spectra->removeElement($spectrum);
    }
}
